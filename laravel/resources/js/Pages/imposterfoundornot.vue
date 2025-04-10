<template>
    <div class="w-full h-screen bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 flex items-center justify-center p-6">
      <!-- The reveal Section -->
      <div v-if="isRevealed" class="w-full max-w-4xl p-6 bg-white rounded-xl shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 bg-gray opacity-50"></div>
        <div class="relative z-10 flex flex-col items-center justify-center space-y-6 text-black text-center">
          <h2 class="text-4xl font-extrabold md:text-6xl">
            <span v-if="isImposterFound" class="text-amber-400">The Capper</span>
            <span v-else>The Capper Was Not Found! 👀 </span>
          </h2>
          <p class="text-lg font-semibold">
            <span v-if="isImposterFound">Revealed! It was <span class="font-bold">{{ imposter.name }}</span>!</span>
            <span v-else class = "text-3xl">No one could identify the Capper...</span>
          </p>
          <button
            @click="goToNextRound"
            class="bg-green-300 text-black text-3xl font-semibold px-8 py-4 rounded-full transition-all duration-300 hover:bg-green-400 transform hover:scale-110"
          >
          try to find the imposter again
          </button>
        </div>
      </div>
  
      <!-- revealing State  -->
      <div v-else class = 'flex space-x-2 justify-center items-center h-screen dark:invert'>
          <span class = "text-white text-5xl">Has the Capper been caught?...</span>
          <div class='h-8 w-8 bg-black rounded-full animate-bounce [animation-delay:-0.3s]'></div>
          <div class='h-8 w-8 bg-black rounded-full animate-bounce [animation-delay:-0.15s]'></div>
          <div class='h-8 w-8 bg-black rounded-full animate-bounce'></div>
        </div>
    </div>
  </template>
    
  <script>
  import { ref, onMounted } from "vue";
  import axios from "axios";
  import { router } from "@inertiajs/vue3";
  
  export default {
    setup() {
      const gameID = ref(localStorage.getItem("game_id"));
      console.log("Game ID:", gameID.value);
      const isRevealed = ref(false);
      const isImposterFound = ref(false);
      const imposter = ref(null);

      const revealImposter = async () => {
        try {
          const response = await axios.post(`api/game/${gameID.value}/reveal-imposter`);
          
          // Set the state based on the response
          isRevealed.value = true;
          isImposterFound.value = response.data.is_imposter_revealed;
          imposter.value = response.data.imposter;
        } catch (error) {
          console.error('Error revealing imposter:', error);
        }
      }

      const goToNextRound = async () => {        
        // start the next round. First phase is Lobby so change it to Prompt phase 
        //also increment the round number that imposter is on, if it is already at 3 rounds, then set it to zero and then start the round. 
        console.log('Game ID:', gameID.value);
        await axios.get(`api/rounds/${gameID.value}/reset-or-continue-imposter`); 
        router.visit('/promptscreen'); // goes to promptscreen if the imposter is not caught
      }
  
      const checkPhase = async () => {
        try {
          const response = await axios.get(`api/rounds/${gameID.value}/latest-round`);
          if (response.data.phase === "voting") {
            console.log("In voting phase");
          } else {
            const res = axios.post(`api/rounds/${gameID.value}/next-phase`); // move to next phase and then refirect
            console.log("changing phase to next phase ->> next round", (await res).data.phases);

            router.visit('/imposterfoundornot');
          }
        } catch (error) {
          console.error("Error checking phase:", error);
        }
      };

      setTimeout(() => {
        revealImposter(); 
      }, 1000);
      
    onMounted(() => {
      
    });
  
      return { revealImposter, isRevealed, isImposterFound, imposter, goToNextRound };
    },
  };
  </script>
  