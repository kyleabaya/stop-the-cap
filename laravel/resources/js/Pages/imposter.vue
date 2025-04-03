<template>
    <div class="h-screen flex items-center justify-center bg-red-600 text-white text-center">
      <div class="animate-fade-in">
        <h1 class="text-6xl font-bold mb-4 animate-pulse">You Are the Imposter!</h1>
        <p class="text-xl">Stay hidden... Redirecting in {{ countdown }} seconds...</p>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from "vue";
  
  export default {
    setup() {
      const countdown = ref(10);
  
      const startCountdown = () => {
        const timer = setInterval(() => {
          if (countdown.value > 0) {
            countdown.value--;
          } else {
            clearInterval(timer);
            window.location.href = "/waiting"; // Redirect after 10s
          }
        }, 1000);
      };
  
      onMounted(startCountdown);
  
      return { countdown };
    },
  };
  </script>
  
  <style>
  @keyframes fade-in {
    from { opacity: 0.3; }
    to { opacity: 1; }
  }
  .animate-fade-in {
    animation: fade-in 1s ease-in-out;
  }
  </style>  