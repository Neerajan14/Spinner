<template>
  <div class="app"> 
    <RegistrationList v-if="showParticipantsList"/>

    <UserForm
        v-else-if="!showWheel"
        @formSubmitted="handleFormSubmit"
    />
    
    <div v-else>
      <!-- ✅ FIXED: Only show wheel when items exist -->
      <SpinningWheel
          v-if="wheelItems.length > 0"
          :items="wheelItems"
          :duration="4000"
          title="🎡 Spinning Wheel - Wheel of Fortune"
          @onFinish="handleSpinFinish"
          ref="wheelRef"
      />
      
      <!-- ✅ Show loading or error message -->
      <div v-else class="loading-message">
        <p>⚠️ No prizes available. Please contact support.</p>
      </div>
      
      <!-- ✅ Display user wins -->
      <div class="wins-container" v-if="userWins.length > 0">
        <h2>🏆 Your Wins</h2>
        <div class="wins-list">
          <div v-for="win in userWins" :key="win.prize.id" class="win-card">
            <p><strong>Prize:</strong> {{ win.prize.label }}</p>
            <p><strong>Price:</strong> {{ win.prize.price }}</p>
            <p><strong>User:</strong> {{ win.user.name }}</p>
            <p><strong>Phone:</strong> {{ win.user.number }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import SpinningWheel from './components/SpinningWheel.vue'
import UserForm from './components/UserForm.vue'
import axios from "axios";
import RegistrationList from "./components/RegistrationList.vue";

const wheelRef = ref(null)
const showWheel = ref(false)
const showParticipantsList = ref(false)
const allowedUuid = "123e4567-e89b-12d3-a456-426614174000"
const userData = ref(null)
const userWins = ref([])

const wheelItems = ref([
  {id: 1, label: 'Pen 🖊️', weight: 65, price: '$5'},
  {id: 2, label: 'Diary 📔', weight: 8.75, price: '$15'},
  {id: 3, label: 'Stickers 🏷️', weight: 8.75, price: '$8'},
  {id: 4, label: 'Key Ring 🗝️', weight: 8.75, price: '$12'},
  {id: 5, label: 'Lanyard 🎗️', weight: 8.75, price: '$10'}
])

onMounted(() => {
  const params = new URLSearchParams(window.location.search)
  const uuid = params.get('uuid')
  if (uuid === allowedUuid) {
    showParticipantsList.value = true
  }
})

// ✅ Store form data
async function handleFormSubmit(data) {
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/store-user', data)
    console.log('✅ User stored:', res.data)
    userData.value = { ...data, id: res.data.id }
  } catch (err) {
    console.error('❌ Error storing user:', err)
    console.error('Error details:', err.response?.data)
    userData.value = data
  }
  
  showWheel.value = true
  await fetchPrizesFromServer()
}

// ✅ FIXED: Fetch prizes dynamically from Laravel API
async function fetchPrizesFromServer() {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/prizes')
    console.log('✅ API response:', res.data)
    
    // ✅ Check if prizes exist
    if (res.data.prizes && res.data.prizes.length > 0) {
      wheelItems.value = res.data.prizes
      console.log('✅ Loaded prizes:', wheelItems.value)
    } else {
      console.error('❌ Backend returned empty prizes array')
      console.error('Using default prizes as fallback')
      // Keep the default wheelItems defined above
    }
  } catch (err) {
    console.error('❌ Error fetching prizes:', err)
    console.error('Error details:', err.response?.data)
    // Keep the default wheelItems defined above
  }
}

// Server-controlled spin
async function spinFromServer() {
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/spin', {
      user_name: userData.value.name,
      user_email: userData.value.email,
      user_number: userData.value.number,
      user_address: userData.value.address,
    })
    const winner = res.data
    const index = wheelItems.value.findIndex(p => p.id === winner.id)
    if (index !== -1) {
      wheelRef.value.spinTo(index)
    }
  } catch (err) {
    console.error('Error spinning:', err)
  }
}

// ✅ UPDATED: Record win in database
async function handleSpinFinish(item) {
  console.log('User won:', item, userData.value)

  const prize = typeof item === 'string'
    ? wheelItems.value.find(p => p.label === item)
    : item

  if (!prize) {
    console.error('Could not resolve prize from:', item)
    return
  }

  // ✅ FIXED: Check if user has ID before recording win
  if (!userData.value?.id) {
    console.error('❌ Cannot record win: User ID is missing')
    console.error('User data:', userData.value)
    return
  }

  try {
    console.log('Recording win...')
    console.log('user_id:', userData.value.id)
    console.log('prize_id:', prize.id)

    const winResponse = await axios.post('http://127.0.0.1:8000/api/record-win', {
      user_id: userData.value.id,
      prize_id: prize.id
    })
    
    console.log('✅ Win recorded:', winResponse.data)
    await fetchUserWins()
    
  } catch (err) {
    console.error('❌ Error recording win:', err)
    console.error('Error details:', err.response?.data)
  }
}

// ✅ NEW: Fetch user's wins from database
async function fetchUserWins() {
  try {
    if (!userData.value?.id) {
      console.warn('⚠️ Cannot fetch wins: User ID is missing')
      return
    }
    
    const res = await axios.get(`http://127.0.0.1:8000/api/user/${userData.value.id}/wins`)
    userWins.value = res.data
    console.log('✅ User wins:', userWins.value)
  } catch (err) {
    console.error('❌ Error fetching wins:', err)
    console.error('Error details:', err.response?.data)
  }
}

</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  padding: 20px;
}

/* ✅ NEW: Loading message styles */
.loading-message {
  background: rgba(255, 255, 255, 0.9);
  padding: 40px;
  border-radius: 10px;
  text-align: center;
  max-width: 500px;
  margin: 50px auto;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.loading-message p {
  font-size: 18px;
  color: #d32f2f;
  font-weight: 500;
}

.wins-container {
  background: rgba(255, 255, 255, 0.9);
  padding: 20px;
  margin-top: 20px;
  border-radius: 10px;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
}

.wins-container h2 {
  color: #333;
  margin-bottom: 20px;
  text-align: center;
}

.wins-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 15px;
}

.win-card {
  background: #f5f5f5;
  padding: 15px;
  border-radius: 8px;
  border-left: 4px solid #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.win-card:hover {
  transform: translateY(-5px);
}

.win-card p {
  margin: 8px 0;
  font-size: 14px;
  color: #333;
}

.win-card strong {
  color: #667eea;
}
</style>