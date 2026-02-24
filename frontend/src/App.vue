<template>
  <div class="app"> 
    <RegistrationList v-if="showParticipantsList"/>

    <UserForm
        v-else-if="!showWheel"
        @formSubmitted="handleFormSubmit"
    />
    <SpinningWheel
        v-else :items="wheelItems"
        :duration="4000"
        title="🎡 Spinning Wheel - Wheel of Fortune"
        @onFinish="handleSpinFinish"
        ref="wheelRef"
    />
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import SpinningWheel from './components/SpinningWheel.vue'
import UserForm from './components/UserForm.vue'
import axios from "axios";
import RegistrationList from "./components/RegistrationList.vue";

const wheelRef = ref(null)
const showWheel = ref(false) //no need to show wheel initially

const showParticipantsList = ref(false)

const allowedUuid = "123e4567-e89b-12d3-a456-426614174000"

onMounted(() => {
  const params = new URLSearchParams(window.location.search)
  const uuid = params.get('uuid')

  if (uuid === allowedUuid) {
    showParticipantsList.value = true
  }
})

//store form data
const userData = ref(null)

// Define wheel items with probability weights and prices
const wheelItems = ref([
  {id: 1, label: 'Pen 🖊️', weight: 65, price: '$5'},
  {id: 2, label: 'Diary 📔', weight: 8.75, price: '$15'},
  {id: 3, label: 'Stickers 🏷️', weight: 8.75, price: '$8'},
  {id: 4, label: 'Key Ring 🗝️', weight: 8.75, price: '$12'}, // keyring loop style
  {id: 5, label: 'Lanyard 🎗️', weight: 8.75, price: '$10'}
])


//this is to handel form data
async function handleFormSubmit(data) {
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/store-user', data)
    userData.value = { ...data, id: res.data.id } // ✅ stores user id
  } catch (err) {
    console.error('Error storing user:', err)
    userData.value = data // fallback
  }

  showWheel.value = true
  fetchPrizesFromServer()
}


// Fetch prizes dynamically from Laravel API
async function fetchPrizesFromServer() {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/prizes')
    console.log('API response:', res.data)
    wheelItems.value = res.data.data ?? res.data
  } catch (err) {
    console.error('Error fetching prizes:', err)
  }
}

// Server-controlled spin
async function spinFromServer() {
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/spin', {
      user_name: userData.value.name,      // ✅ use form fields instead of .id
      user_email: userData.value.email,
      user_number: userData.value.number,
      user_address: userData.value.address,
    })
    const winner = res.data

    // Find index of winner in wheelItems
    const index = wheelItems.value.findIndex(p => p.id === winner.id)

    if (index !== -1) {
      // Trigger wheel spin to server-selected index
      wheelRef.value.spinTo(index)
    }
  } catch (err) {
    console.error('Error spinning:', err)
  }
}

// Handle spin finish
async function handleSpinFinish(item) {
  console.log('User won:', item, userData.value)

  // ✅ If item is a string label, find the full object
  const prize = typeof item === 'string'
    ? wheelItems.value.find(p => p.label === item)
    : item

  if (!prize) {
    console.error('Could not resolve prize from:', item)
    return
  }

  // Store result in backend if needed
  try {
    await axios.post('http://127.0.0.1:8000/api/store-spin', {
      prize_id: item.id,
      user_name: userData.value.name,       // ✅ use actual form fields
      user_email: userData.value.email,
      user_number: userData.value.number,
      user_address: userData.value.address,
    })
  } catch (err) {
    console.error('Error storing spin result:', err)
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
}

</style>
