<template>
  <div class="spinner-wheel-wrapper">
    <div class="background-elements">
      <div class="glow-orb orb-1"></div>
      <div class="glow-orb orb-2"></div>
      <div class="glow-orb orb-3"></div>
    </div>

    <div class="wheel-container">
      <div class="outer-ring">
        <div class="ring-dot" v-for="n in 12" :key="n"
             :style="{ transform: `rotate(${n * 30}deg) translateY(-250px)` }"></div>
      </div>

      <div class="pointer">
        <div class="pointer-glow"></div>
      </div>

      <div class="wheel-shadow"></div>
      <svg
          class="wheel-svg"
          :style="{ transform: `rotate(${rotation}deg)` }"
          viewBox="0 0 400 400"
          width="200"
          height="200"
      >
        <g v-for="(slice, index) in slices" :key="`slice-${index}`">
          <path
              :d="slice.path"
              :fill="slice.color"
              stroke="white"
              stroke-width="3"
          />

          <!-- Background Icon -->
          <text
              :x="slice.iconX"
              :y="slice.iconY"
              :transform="`rotate(${slice.labelRotation} ${slice.iconX} ${slice.iconY})`"
              class="slice-icon"
              text-anchor="middle"
              dominant-baseline="middle"
          >
            {{ slice.item.icon || '🎁' }}
          </text>

          <!-- Foreground Label -->
          <text
              :x="slice.labelX"
              :y="slice.labelY"
              :transform="`rotate(${slice.labelRotation} ${slice.labelX} ${slice.labelY})`"
              class="slice-label"
              :fill="index % 2 === 0 ? '#FFFFFF' : '#1a1a2e'"
              text-anchor="middle"
              dominant-baseline="middle"
          >
            {{ slice.item.label || slice.item }}
          </text>
        </g>

        <defs>
          <linearGradient id="centerGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#FF1744;stop-opacity:1"/>
            <stop offset="100%" style="stop-color:#C2185B;stop-opacity:1"/>
          </linearGradient>
          <filter id="glow">
            <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
            <feMerge>
              <feMergeNode in="coloredBlur"/>
              <feMergeNode in="SourceGraphic"/>
            </feMerge>
          </filter>
        </defs>

        <g
            @click="spin"
            :style="{ cursor: isSpinning ? 'not-allowed' : 'pointer' }"
            class="center-button"
        >
          <circle cx="200" cy="200" r="50" fill="url(#centerGradient)" filter="url(#glow)" stroke="white"
                  stroke-width="4"/>
          <circle cx="200" cy="200" r="42" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
          <text
              x="200"
              y="205"
              class="spin-text"
              text-anchor="middle"
              dominant-baseline="middle"
              :style="{ cursor: isSpinning ? 'not-allowed' : 'pointer', opacity: isSpinning ? 0.6 : 1 }"
          >
            {{ isSpinning ? 'WAIT' : 'SPIN' }}
          </text>
        </g>
      </svg>

      <canvas
          v-if="showConfetti"
          ref="confettiCanvas"
          class="confetti-canvas"
      ></canvas>
    </div>

    <transition name="indicator-pop">
      <div v-if="lastResult && !isSpinning" class="indicator-circle">
        <div class="sparkle sparkle-1">✨</div>
        <div class="sparkle sparkle-2">✨</div>
        <div class="sparkle sparkle-3">✨</div>
        <div class="sparkle sparkle-4">✨</div>

        <div class="indicator-content">
          <div class="trophy-icon">🏆</div>
          <h2 class="result-text">You Won!</h2>
          <div class="result-prize">{{ lastResult.label || lastResult }}</div>
        </div>
      </div>
    </transition>

    <div class="spin-counter" v-if="spinCount > 0">
      <span class="counter-icon">🎯</span>
      <span class="counter-value">{{ spinCount }}</span>
    </div>
  </div>
</template>

<script setup>
import {ref, computed, onMounted, onUnmounted} from 'vue'

const props = defineProps({
  items: {
    type: Array,
    required: true,
    validator: (arr) => arr.length > 0
  },
  duration: {
    type: Number,
    default: 2000
  },
  title: {
    type: String,
    default: 'Spinning Wheel - Wheel of Fortune'
  },
  autoSpin: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['onFinish'])

const rotation = ref(0)
const isSpinning = ref(false)
const showConfetti = ref(false)
const confettiCanvas = ref(null)
const animationFrameId = ref(null)
const spinCount = ref(0)
const lastResult = ref(null)

const SLICE_COUNT = props.items.length
const FULL_ROTATION = 360
const MIN_ROTATIONS = 5

const COLORS = [
  '#FF1744', '#FFFFFF', '#FF1744', '#FFFFFF', '#FF1744'
]

function getSliceColor(index) {
  return COLORS[index % COLORS.length]
}

const slices = computed(() => {
  const totalWeight = props.items.reduce((sum, item) => {
    const weight = typeof item === 'object' ? (item.weight || 1) : 1
    return sum + weight
  }, 0)

  return props.items.map((item, index) => {
    const sliceAngle = FULL_ROTATION / SLICE_COUNT
    const startAngle = (index * sliceAngle - 90) * (Math.PI / 180)
    const endAngle = ((index + 1) * sliceAngle - 90) * (Math.PI / 180)
    const radius = 180

    const x1 = 200 + radius * Math.cos(startAngle)
    const y1 = 200 + radius * Math.sin(startAngle)
    const x2 = 200 + radius * Math.cos(endAngle)
    const y2 = 200 + radius * Math.sin(endAngle)

    const largeArc = sliceAngle > 180 ? 1 : 0
    const path = `M 200 200 L ${x1} ${y1} A ${radius} ${radius} 0 ${largeArc} 1 ${x2} ${y2} Z`

    const labelAngle = startAngle + (endAngle - startAngle) / 2

    // Icon position (closer to center, larger)
    const iconRadius = radius * 0.45
    const iconX = 200 + iconRadius * Math.cos(labelAngle)
    const iconY = 200 + iconRadius * Math.sin(labelAngle)

    // Label position (further out)
    const labelRadius = radius * 0.75
    const labelX = 200 + labelRadius * Math.cos(labelAngle)
    const labelY = 200 + labelRadius * Math.sin(labelAngle)

    const labelRotation = (labelAngle * 180 / Math.PI) + 90

    const weight = typeof item === 'object' ? (item.weight || 1) : 1
    const probability = ((weight / totalWeight) * 100).toFixed(0)

    return {
      item,
      path,
      color: getSliceColor(index),
      text: index % 2 === 0 ? '#FFFFFF' : '#000000',
      iconX,
      iconY,
      labelX,
      labelY,
      labelRotation,
      probability
    }
  })
})

function getRandomIndex() {
  const hasWeights = props.items.some(item => typeof item === 'object' && item.weight !== undefined)

  if (hasWeights) {
    const totalWeight = props.items.reduce((sum, item) => {
      const weight = typeof item === 'object' ? (item.weight || 1) : 1
      return sum + weight
    }, 0)

    let random = Math.random() * totalWeight
    for (let i = 0; i < props.items.length; i++) {
      const weight = typeof props.items[i] === 'object' ? (props.items[i].weight || 1) : 1
      if (random < weight) {
        return i
      }
      random -= weight
    }
    return props.items.length - 1
  } else {
    return Math.floor(Math.random() * SLICE_COUNT)
  }
}

function spinTo(targetIndex) {
  if (isSpinning.value) return

  lastResult.value = null
  showConfetti.value = false
  isSpinning.value = true

  const currentRotation = rotation.value % 360
  const sliceAngle = FULL_ROTATION / SLICE_COUNT
  const targetAngle = targetIndex * sliceAngle + (sliceAngle / 2)
  const extraRotation =
      MIN_ROTATIONS * FULL_ROTATION +
      (FULL_ROTATION - targetAngle) -
      currentRotation
  const finalRotation = rotation.value + extraRotation

  spinCount.value++

  animateWheel(finalRotation, props.duration, () => {
    lastResult.value = props.items[targetIndex]
    showConfetti.value = true
    triggerConfetti()
    isSpinning.value = false
    emit('onFinish', lastResult.value)
  })
}

function spin() {
  const targetIndex = getRandomIndex()
  spinTo(targetIndex)
}

function animateWheel(targetRotation, duration, onComplete) {
  const startRotation = rotation.value
  const startTime = Date.now()

  const animate = () => {
    const elapsed = Date.now() - startTime
    const progress = Math.min(elapsed / duration, 1)

    const easeProgress = 1 - Math.pow(1 - progress, 4)

    rotation.value = startRotation + (targetRotation - startRotation) * easeProgress

    if (progress < 1) {
      animationFrameId.value = requestAnimationFrame(animate)
    } else {
      rotation.value = rotation.value % 360
      if (onComplete) {
        onComplete()
      }
    }
  }

  animationFrameId.value = requestAnimationFrame(animate)
}

function triggerConfetti() {
  if (!confettiCanvas.value) return

  const canvas = confettiCanvas.value
  const ctx = canvas.getContext('2d')
  const {width, height} = canvas.getBoundingClientRect()

  canvas.width = width
  canvas.height = height

  const centerX = width / 2
  const centerY = height / 3

  const particles = Array.from({length: 100}, (_, i) => {
    let particle

    if (i < 30) {
      const angle = (i / 30) * Math.PI * 2 + (Math.random() - 0.5) * 0.5
      const speed = Math.random() * 8 + 10
      const distance = Math.random() * 50 + 30

      particle = {
        x: centerX + Math.cos(angle) * distance,
        y: centerY + Math.sin(angle) * distance,
        vx: Math.cos(angle) * speed,
        vy: Math.sin(angle) * speed,
        size: Math.random() * 8 + 10,
        color: Math.random() > 0.5 ? '#FFD700' : '#FF69B4',
        rotation: Math.random() * Math.PI * 2,
        rotationSpeed: (Math.random() - 0.5) * 0.25,
        type: Math.random() > 0.5 ? 'coin' : 'gem'
      }
    } else {
      const type = Math.random()
      particle = {
        x: Math.random() * width,
        y: -20,
        vx: (Math.random() - 0.5) * 12,
        vy: Math.random() * 6 + 3,
        size: Math.random() * 10 + 6,
        color: COLORS[Math.floor(Math.random() * COLORS.length)],
        rotation: Math.random() * Math.PI * 2,
        rotationSpeed: (Math.random() - 0.5) * 0.15,
        type: type < 0.4 ? 'balloon' : type < 0.7 ? 'star' : 'square'
      }
    }

    return particle
  })

  let frameCount = 0
  const maxFrames = 180

  const drawBalloon = (x, y, size, color) => {
    ctx.fillStyle = color
    ctx.beginPath()
    ctx.arc(x, y, size / 2, 0, Math.PI * 2)
    ctx.fill()
    ctx.strokeStyle = color
    ctx.lineWidth = 1
    ctx.beginPath()
    ctx.moveTo(x, y + size / 2)
    ctx.lineTo(x, y + size / 2 + 15)
    ctx.stroke()
  }

  const drawStar = (x, y, size, color) => {
    ctx.save()
    ctx.translate(x, y)
    ctx.rotate(Math.random() * Math.PI)
    ctx.fillStyle = color
    ctx.beginPath()
    for (let i = 0; i < 5; i++) {
      const angle = (i * 4 * Math.PI) / 5 - Math.PI / 2
      const x1 = Math.cos(angle) * size
      const y1 = Math.sin(angle) * size
      if (i === 0) ctx.moveTo(x1, y1)
      else ctx.lineTo(x1, y1)
    }
    ctx.closePath()
    ctx.fill()
    ctx.restore()
  }

  const drawCoin = (x, y, size, color) => {
    ctx.save()
    ctx.translate(x, y)
    ctx.fillStyle = color
    ctx.beginPath()
    ctx.arc(0, 0, size / 2, 0, Math.PI * 2)
    ctx.fill()
    ctx.strokeStyle = '#FFA500'
    ctx.lineWidth = 2
    ctx.stroke()
    ctx.fillStyle = 'rgba(255,255,255,0.3)'
    ctx.beginPath()
    ctx.arc(-size / 6, -size / 6, size / 8, 0, Math.PI * 2)
    ctx.fill()
    ctx.restore()
  }

  const drawGem = (x, y, size, color) => {
    ctx.save()
    ctx.translate(x, y)
    ctx.rotate(frameCount * 0.05)
    ctx.fillStyle = color
    ctx.beginPath()
    ctx.moveTo(0, -size / 2)
    ctx.lineTo(size / 2, 0)
    ctx.lineTo(size / 3, size / 2)
    ctx.lineTo(-size / 3, size / 2)
    ctx.lineTo(-size / 2, 0)
    ctx.closePath()
    ctx.fill()
    ctx.strokeStyle = 'rgba(255,255,255,0.6)'
    ctx.lineWidth = 1
    ctx.stroke()
    ctx.restore()
  }

  const drawConfetti = () => {
    ctx.clearRect(0, 0, width, height)

    particles.forEach((particle) => {
      if (particle.type === 'coin' || particle.type === 'gem') {
        particle.vy += 0.3
      } else {
        particle.vy += 0.15
      }
      particle.y += particle.vy
      particle.x += particle.vx
      particle.rotation += particle.rotationSpeed

      const flash = Math.sin(frameCount * 0.1) * 0.5 + 0.5
      ctx.globalAlpha = (1 - frameCount / maxFrames) * (0.5 + flash * 0.5)

      if (particle.type === 'balloon') {
        drawBalloon(particle.x, particle.y, particle.size, particle.color)
      } else if (particle.type === 'star') {
        drawStar(particle.x, particle.y, particle.size / 2, particle.color)
      } else if (particle.type === 'coin') {
        drawCoin(particle.x, particle.y, particle.size, particle.color)
      } else if (particle.type === 'gem') {
        drawGem(particle.x, particle.y, particle.size, particle.color)
      } else {
        ctx.save()
        ctx.translate(particle.x, particle.y)
        ctx.rotate(particle.rotation)
        ctx.fillStyle = particle.color
        ctx.fillRect(-particle.size / 2, -particle.size / 2, particle.size, particle.size)
        ctx.restore()
      }
    })

    frameCount++
    if (frameCount < maxFrames) {
      requestAnimationFrame(drawConfetti)
    } else {
      showConfetti.value = false
    }
  }

  drawConfetti()
}

function reset() {
  lastResult.value = null
  showConfetti.value = false
  spinCount.value = 0
}

onMounted(() => {
  if (props.autoSpin) {
    setTimeout(() => spin(), 500)
  }
})

onUnmounted(() => {
  if (animationFrameId.value) {
    cancelAnimationFrame(animationFrameId.value)
  }
})

defineExpose({
  spin,
  spinTo,
  reset
})
</script>

<style scoped>
.spinner-wheel-wrapper {
  position: relative;
  min-height: calc(100vh - 40px);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', sans-serif;
  color: white;
  overflow: hidden;
  padding: 20px;
}

.background-elements {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.glow-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(100px);
  opacity: 0.3;
  animation: float-orb 10s ease-in-out infinite;
}

.orb-1 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(102, 126, 234, 0.4), transparent);
  top: -250px;
  left: -150px;
  animation-delay: 0s;
}

.orb-2 {
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(118, 75, 162, 0.35), transparent);
  bottom: -300px;
  right: -200px;
  animation-delay: 3s;
}

.orb-3 {
  width: 450px;
  height: 450px;
  background: radial-gradient(circle, rgba(139, 92, 246, 0.3), transparent);
  top: 50%;
  right: -150px;
  animation-delay: 6s;
}

@keyframes float-orb {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(40px, -40px) scale(1.15);
  }
  66% {
    transform: translate(-30px, 30px) scale(0.9);
  }
}

.wheel-title {
  position: relative;
  font-size: clamp(1.5rem, 4vw, 3.5rem);
  margin-bottom: clamp(30px, 5vh, 60px);
  text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  letter-spacing: 3px;
  text-align: center;
  font-weight: 900;
  animation: title-glow 3s ease-in-out infinite;
  z-index: 10;
  padding: 0 20px;
  color: white;
}

.title-icon {
  display: inline-block;
  animation: spin-icon 4s linear infinite;
  font-size: clamp(2rem, 4vw, 3rem);
  margin: 0 clamp(10px, 2vw, 20px);
  filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.6));
}

@keyframes title-glow {
  0%, 100% {
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3),
    0 0 30px rgba(255, 255, 255, 0.3);
  }
  50% {
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3),
    0 0 50px rgba(255, 255, 255, 0.5);
  }
}

@keyframes spin-icon {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.wheel-container {
  position: relative;
  width: min(85vw, 600px);
  height: min(85vw, 600px);
  max-width: 600px;
  max-height: 600px;
  margin-bottom: clamp(30px, 5vh, 50px);
  animation: wheel-entrance 1s ease-out;
}

@keyframes wheel-entrance {
  from {
    opacity: 0;
    transform: scale(0.5) rotateZ(-180deg);
  }
  to {
    opacity: 1;
    transform: scale(1) rotateZ(0deg);
  }
}

.outer-ring {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  pointer-events: none;
}

.ring-dot {
  position: absolute;
  width: clamp(14px, 2vw, 20px);
  height: clamp(14px, 2vw, 20px);
  background: linear-gradient(135deg, #667eea, #764ba2);
  border-radius: 50%;
  top: 50%;
  left: 50%;
  transform-origin: 0 0;
  box-shadow: 0 0 20px rgba(102, 126, 234, 0.8),
  0 0 40px rgba(118, 75, 162, 0.4);
  animation: pulse-dot 2s ease-in-out infinite;
}

.ring-dot:nth-child(odd) {
  animation-delay: 0.5s;
}

@keyframes pulse-dot {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.4);
    opacity: 0.6;
  }
}

.pointer {
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: clamp(24px, 4vw, 36px) solid transparent;
  border-right: clamp(24px, 4vw, 36px) solid transparent;
  border-top: clamp(45px, 7vw, 65px) solid #FF1744;
  z-index: 20;
  filter: drop-shadow(0 8px 20px rgba(255, 23, 68, 0.8));
  animation: pointer-bounce 1.5s ease-in-out infinite;
}

@keyframes pointer-bounce {
  0%, 100% {
    transform: translateX(-50%) translateY(0);
  }
  50% {
    transform: translateX(-50%) translateY(-10px);
  }
}

.pointer-glow {
  position: absolute;
  top: -60px;
  left: clamp(-24px, -4vw, -36px);
  width: clamp(48px, 8vw, 72px);
  height: 60px;
  background: radial-gradient(circle, rgba(255, 23, 68, 0.8), transparent);
  filter: blur(20px);
  animation: glow-pulse 1.5s ease-in-out infinite;
}

@keyframes glow-pulse {
  0%, 100% {
    opacity: 0.6;
  }
  50% {
    opacity: 1;
  }
}

.wheel-shadow {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: radial-gradient(circle, transparent 40%, rgba(0, 0, 0, 0.4));
  filter: blur(25px);
  transform: translateY(15px);
}

.wheel-svg {
  width: 100%;
  height: 100%;
  filter: drop-shadow(0 15px 40px rgba(0, 0, 0, 0.4));
  transition: filter 0.3s ease;
}

.wheel-svg:hover .center-button circle:first-child {
  filter: url(#glow) drop-shadow(0 0 30px rgba(255, 23, 68, 0.9));
}

.slice-icon {
  font-size: clamp(50px, 10vw, 85px);
  opacity: 0.25;
  pointer-events: none;
  filter: drop-shadow(3px 3px 6px rgba(0, 0, 0, 0.4));
}

.slice-label {
  font-size: clamp(18px, 4vw, 32px);
  font-weight: 900;
  text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.8);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  pointer-events: none;
  letter-spacing: 1px;
}

.center-button {
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.center-button:hover {
  transform: scale(1.08);
}

.center-button:active {
  transform: scale(0.92);
}

.spin-text {
  font-size: clamp(18px, 3.5vw, 28px);
  font-weight: 900;
  fill: white;
  cursor: pointer;
  transition: all 0.3s ease;
  filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.6));
  letter-spacing: 3px;
}

.confetti-canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.indicator-circle {
  position: relative;
  margin-top: clamp(20px, 4vh, 40px);
  padding: clamp(30px, 5vw, 45px) clamp(40px, 6vw, 65px);
  border-radius: 30px;
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  border: 6px solid rgba(255, 255, 255, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 60px rgba(255, 215, 0, 1),
  0 20px 60px rgba(0, 0, 0, 0.3),
  inset 0 0 40px rgba(255, 255, 255, 0.3);
  animation: indicator-pulse 0.8s cubic-bezier(0.34, 1.56, 0.64, 1),
  indicator-float 3s ease-in-out 0.8s infinite;
  max-width: min(90vw, 600px);
}

.sparkle {
  position: absolute;
  font-size: clamp(22px, 4vw, 36px);
  animation: sparkle-twinkle 1.5s ease-in-out infinite;
  filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.8));
}

.sparkle-1 {
  top: clamp(-12px, -2vw, -18px);
  left: clamp(-12px, -2vw, -18px);
  animation-delay: 0s;
}

.sparkle-2 {
  top: clamp(-12px, -2vw, -18px);
  right: clamp(-12px, -2vw, -18px);
  animation-delay: 0.3s;
}

.sparkle-3 {
  bottom: clamp(-12px, -2vw, -18px);
  left: clamp(-12px, -2vw, -18px);
  animation-delay: 0.6s;
}

.sparkle-4 {
  bottom: clamp(-12px, -2vw, -18px);
  right: clamp(-12px, -2vw, -18px);
  animation-delay: 0.9s;
}

@keyframes sparkle-twinkle {
  0%, 100% {
    transform: scale(1) rotate(0deg);
    opacity: 1;
  }
  50% {
    transform: scale(1.6) rotate(180deg);
    opacity: 0.6;
  }
}

.indicator-content {
  text-align: center;
}

.trophy-icon {
  font-size: clamp(50px, 8vw, 80px);
  margin-bottom: clamp(12px, 2vh, 18px);
  animation: trophy-bounce 0.6s ease-out;
  filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.8));
}

@keyframes trophy-bounce {
  0% {
    transform: translateY(-60px) scale(0);
    opacity: 0;
  }
  60% {
    transform: translateY(12px) scale(1.2);
  }
  100% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

.result-text {
  font-size: clamp(1.6rem, 4.5vw, 3rem);
  margin: 0 0 clamp(12px, 2vh, 18px) 0;
  font-weight: 900;
  color: #1a1a2e;
  text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
  letter-spacing: 2px;
}

.result-prize {
  font-size: clamp(1.2rem, 3.5vw, 2rem);
  font-weight: 800;
  color: #C2185B;
  text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.6);
  padding: clamp(10px, 2vw, 14px) clamp(18px, 3vw, 25px);
  background: rgba(255, 255, 255, 0.3);
  border-radius: 16px;
  display: inline-block;
  word-break: break-word;
  max-width: 100%;
}

@keyframes indicator-pulse {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes indicator-float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-15px);
  }
}

.spin-counter {
  position: absolute;
  top: clamp(15px, 3vh, 25px);
  right: clamp(15px, 3vw, 25px);
  padding: clamp(12px, 2vw, 20px) clamp(18px, 3vw, 35px);
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  gap: clamp(8px, 1.5vw, 12px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2),
  inset 0 0 20px rgba(255, 255, 255, 0.05);
  animation: counter-entrance 0.5s ease-out;
}

@keyframes counter-entrance {
  from {
    opacity: 0;
    transform: translateX(50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.counter-icon {
  font-size: clamp(22px, 4vw, 32px);
  filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.6));
}

.counter-value {
  font-size: clamp(24px, 4vw, 36px);
  font-weight: 900;
  color: white;
  text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.indicator-pop-enter-active {
  transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.indicator-pop-enter-from {
  opacity: 0;
  transform: scale(0) rotate(-180deg);
}

.indicator-pop-leave-active {
  transition: all 0.4s ease;
}

.indicator-pop-leave-to {
  opacity: 0;
  transform: scale(0.5) rotate(180deg);
}

/* Desktop optimizations */
@media (min-width: 1024px) {
  .wheel-container {
    width: 600px;
    height: 600px;
  }

  .wheel-title {
    font-size: 3.5rem;
  }

  .title-icon {
    font-size: 3rem;
  }

  .slice-icon {
    font-size: 85px;
  }

  .slice-label {
    font-size: 32px;
  }
}

/* Tablet and below */
@media (max-width: 768px) {
  .spinner-wheel-wrapper {
    padding: 15px;
  }

  .wheel-title {
    margin-bottom: 30px;
    letter-spacing: 2px;
  }

  .title-icon {
    margin: 0 10px;
  }

  .spin-counter {
    top: 15px;
    right: 15px;
  }
}

/* Mobile optimizations */
@media (max-width: 480px) {
  .spinner-wheel-wrapper {
    padding: 10px;
  }

  .pointer {
    top: -12px;
  }

  .indicator-circle {
    border: 4px solid rgba(255, 255, 255, 0.9);
  }

  .spin-counter {
    top: 10px;
    right: 10px;
  }
}

/* Touch device optimizations */
@media (hover: none) and (pointer: coarse) {
  .center-button:hover {
    transform: scale(1);
  }

  .center-button:active {
    transform: scale(0.92);
  }
}
</style>