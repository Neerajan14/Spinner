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
  {label: 'Pen 🖊️', weight: 65, price: '$5'},
  {label: 'Diary 📔', weight: 8.75, price: '$15'},
  {label: 'Stickers 🏷️', weight: 8.75, price: '$8'},
  {label: 'Key Ring 🗝️', weight: 8.75, price: '$12'}, // keyring loop style
  {label: 'Lanyard 🎗️', weight: 8.75, price: '$10'}
])


//this is to handel form data
function handleFormSubmit(data) {
  userData.value = data
  showWheel.value = true
  console.log(showWheel.value)
}


// Handle spin completion
function handleSpinFinish(item) {
  console.log(item, userData.value)
  axios.post('.netlify/functions/update', {id: userData.value.id, won_item: item.label})
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
