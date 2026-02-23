<template>
  <div class="form-wrapper">
    <div class="form-container">
      <div class="form-header">
        <div class="icon-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
          </svg>
        </div>
        <h2>Spin & Win!</h2>
        <p class="subtitle">Fill in your details to play</p>
      </div>

      <form @submit.prevent="submitForm">
        <!-- Name -->
        <div class="form-group">
          <label for="name">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            Full Name
            <span class="required">*</span>
          </label>
          <input id="name" v-model="form.name" type="text" placeholder="John Doe" required />
        </div>

        <!-- Phone Number -->
        <div class="form-group">
          <label for="number">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
            Phone Number
            <span class="required">*</span>
          </label>
          <input id="number" v-model="form.number" type="tel" placeholder="+1 (555) 123-4567" required />
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <rect x="2" y="4" width="20" height="16" rx="2" />
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            </svg>
            Email Address
            <span class="required">*</span>
          </label>
          <input id="email" v-model="form.email" type="email" placeholder="john@example.com" required />
        </div>

        <!-- Interested in -->
        <div class="form-group">
          <label for="interested">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
            Interested In
            <span class="required">*</span>
          </label>
          <input id="interested" v-model="form.interested" type="text" placeholder="What interests you?" required />
        </div>

        <!-- Resume Upload -->
        <div class="form-group">
          <label for="resume">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="16" y1="13" x2="8" y2="13" />
              <line x1="16" y1="17" x2="8" y2="17" />
              <polyline points="10 9 9 9 8 9" />
            </svg>
            Upload Resume
            <span class="optional">(Optional)</span>
          </label>
          <div class="file-upload-wrapper">
            <input id="resume" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp" @change="handleFileUpload"
              class="file-input" />
            <label for="resume" class="file-label">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" y1="3" x2="12" y2="15" />
              </svg>
              <span v-if="!form.resumeFileName">Choose file</span>
              <span v-else class="file-selected">{{ form.resumeFileName }}</span>
            </label>
          </div>
          <p class="file-hint">PDF, DOC, or DOCX (Max 5MB)</p>
        </div>

        <!-- Address -->
        <div class="form-group">
          <label for="address">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
              <circle cx="12" cy="10" r="3" />
            </svg>
            Address
            <span class="optional">(Optional)</span>
          </label>
          <input id="address" v-model="form.address" type="text" placeholder="123 Main St, City, Country" />
        </div>

        <button type="submit" class="submit-btn" :disabled="loading">
          <template v-if="loading">
            <span class="spinner"></span>
            Submitting...
          </template>

          <template v-else>
            <span>Let's Play!</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14" />
              <path d="m12 5 7 7-7 7" />
            </svg>
          </template>
        </button>
      </form>

      <div class="decorative-elements">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from "axios";

const emit = defineEmits(['formSubmitted'])

const loading = ref(false)

const form = reactive({
  name: '',
  number: '',
  email: '',
  interested: '',
  resume: null,
  resumeFileName: '',
  address: ''
})

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 5 * 1024 * 1024) {
      alert('File size must be less than 5MB')
      event.target.value = ''
      return
    }
    form.resume = file
    form.resumeFileName = file.name
  }
}

const submitForm = async () => {
  if (loading.value) return

  loading.value = true

  try {
    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('number', form.number)
    formData.append('email', form.email)
    formData.append('interested', form.interested)
    formData.append('address', form.address)

    if (form.resume) {
      formData.append('resume', form.resume)
      formData.append('resumeFileName', form.resumeFileName)
    }

    // const resp = await axios.post('.netlify/functions/create', formData, {
    const resp = await axios.post(`${API}/create`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    emit('formSubmitted', {
      name: form.name,
      number: form.number,
      email: form.email,
      interested: form.interested,
      address: form.address,
      resumeFileName: form.resumeFileName,
      id: resp.data.id
    })

  } catch (error) {
    console.error(error)
    alert('Something went wrong. Please try again.')
  } finally {
    loading.value = false
  }
}

</script>

<style scoped>
.form-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.form-container {
  position: relative;
  max-width: 480px;
  width: 100%;
  padding: 40px 35px;
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(10px);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
    0 0 0 1px rgba(255, 255, 255, 0.1);
  overflow: hidden;
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.form-header {
  text-align: center;
  margin-bottom: 35px;
}

.icon-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  margin-bottom: 20px;
  animation: rotate 3s linear infinite;
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

h2 {
  font-size: 32px;
  font-weight: 700;
  color: #2d3748;
  margin: 0 0 8px 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.subtitle {
  color: #718096;
  font-size: 15px;
  margin: 0;
}

.form-group {
  margin-bottom: 24px;
  animation: fadeIn 0.5s ease-out backwards;
}

.form-group:nth-child(1) {
  animation-delay: 0.1s;
}

.form-group:nth-child(2) {
  animation-delay: 0.2s;
}

.form-group:nth-child(3) {
  animation-delay: 0.3s;
}

.form-group:nth-child(4) {
  animation-delay: 0.4s;
}

.form-group:nth-child(5) {
  animation-delay: 0.5s;
}

.form-group:nth-child(6) {
  animation-delay: 0.6s;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  font-size: 14px;
  color: #2d3748;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

label svg {
  color: #667eea;
  flex-shrink: 0;
}

.required {
  color: #e53e3e;
  font-weight: 700;
  margin-left: auto;
}

.optional {
  color: #718096;
  font-weight: 400;
  font-size: 12px;
  text-transform: none;
  margin-left: auto;
}

.file-upload-wrapper {
  position: relative;
}

.file-input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.file-label {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 100%;
  padding: 14px 16px;
  border-radius: 12px;
  border: 2px dashed #cbd5e0;
  font-size: 15px;
  background: #f7fafc;
  color: #4a5568;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: none;
  font-weight: 500;
  letter-spacing: normal;
  margin: 0;
}

.file-label:hover {
  border-color: #667eea;
  background: #edf2f7;
}

.file-label svg {
  color: #667eea;
}

.file-selected {
  color: #667eea;
  font-weight: 600;
}

.file-hint {
  margin-top: 6px;
  font-size: 12px;
  color: #a0aec0;
  margin-bottom: 0;
}

input {
  width: 100%;
  padding: 14px 16px;
  border-radius: 12px;
  border: 2px solid #e2e8f0;
  font-size: 15px;
  transition: all 0.3s ease;
  background: #f7fafc;
  color: #2d3748;
  box-sizing: border-box;
}

input::placeholder {
  color: #a0aec0;
}

input:focus {
  border-color: #667eea;
  background: white;
  outline: none;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  transform: translateY(-2px);
}

input:hover:not(:focus) {
  border-color: #cbd5e0;
}

.submit-btn {
  width: 100%;
  padding: 16px;
  border: none;
  border-radius: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
  animation: fadeIn 0.5s ease-out 0.7s backwards;
}

.submit-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.submit-btn:active {
  transform: translateY(-1px);
}

.submit-btn svg {
  transition: transform 0.3s ease;
}

.submit-btn:hover svg {
  transform: translateX(5px);
}

.decorative-elements {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  border-radius: 24px;
}

.circle {
  position: absolute;
  border-radius: 50%;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
}

.circle-1 {
  width: 200px;
  height: 200px;
  top: -100px;
  right: -50px;
  animation: float 6s ease-in-out infinite;
}

.circle-2 {
  width: 150px;
  height: 150px;
  bottom: -75px;
  left: -40px;
  animation: float 8s ease-in-out infinite reverse;
}

.circle-3 {
  width: 100px;
  height: 100px;
  top: 50%;
  right: -30px;
  animation: float 7s ease-in-out infinite;
}

@keyframes float {

  0%,
  100% {
    transform: translateY(0) rotate(0deg);
  }

  50% {
    transform: translateY(-20px) rotate(10deg);
  }
}

@media (max-width: 540px) {
  .form-container {
    padding: 30px 25px;
  }

  h2 {
    font-size: 26px;
  }

  .icon-wrapper {
    width: 65px;
    height: 65px;
  }

  .icon-wrapper svg {
    width: 36px;
    height: 36px;
  }
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.spinner {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>