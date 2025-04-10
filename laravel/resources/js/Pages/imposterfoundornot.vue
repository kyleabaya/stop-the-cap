<template>
<<<<<<< Updated upstream
    <div
        class="flex h-screen w-full items-center justify-center bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 p-6"
    >
        <!-- The reveal Section -->
        <div
            v-if="isRevealed"
            class="relative w-full max-w-4xl overflow-hidden rounded-xl bg-white p-6 shadow-xl"
        >
            <div class="bg-gray absolute inset-0 opacity-50"></div>
            <div
                class="relative z-10 flex flex-col items-center justify-center space-y-6 text-center text-black"
            >
                <h2 class="text-4xl font-extrabold md:text-6xl">
                    <span v-if="isImposterFound" class="text-amber-400"
                        >The Capper</span
                    >
                    <span v-else>The Capper Was Not Found! 👀 </span>
                </h2>
                <p class="text-lg font-semibold">
                    <span v-if="isImposterFound">
                        Revealed! It was
                        <span class="font-bold">{{ imposter.name }}</span
                        >!
                    </span>
                    <span v-else class="text-3xl"
                        >No one could identify the Capper...</span
                    >
                </p>
                <button
                    @click="goToNextRound"
                    class="transform rounded-full bg-green-300 px-8 py-4 text-3xl font-semibold text-black transition-all duration-300 hover:scale-110 hover:bg-green-400"
                >
                    Try to find the imposter again
                </button>
            </div>
=======
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
            @click="checkIfPressed"
            class="bg-green-300 text-black text-3xl font-semibold px-8 py-4 rounded-full transition-all duration-300 hover:bg-green-400 transform hover:scale-110"
          >
          try to find the imposter again
          </button>
>>>>>>> Stashed changes
        </div>

        <!-- Revealing State -->
        <div
            v-else
            class="flex h-screen items-center justify-center space-x-2 dark:invert"
        >
            <span class="text-5xl text-white"
                >Has the Capper been caught?...</span
            >
            <div
                class="h-8 w-8 animate-bounce rounded-full bg-black [animation-delay:-0.3s]"
            ></div>
            <div
                class="h-8 w-8 animate-bounce rounded-full bg-black [animation-delay:-0.15s]"
            ></div>
            <div class="h-8 w-8 animate-bounce rounded-full bg-black"></div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

export default {
    setup() {
<<<<<<< Updated upstream
        const gameID = ref(localStorage.getItem('game_id'));
        console.log('Game ID:', gameID.value);
        const isRevealed = ref(false);
        const isImposterFound = ref(false);
        const imposter = ref(null);
=======
      const gameID = ref(localStorage.getItem("game_id"));
      console.log("Game ID:", gameID.value);
      const isRevealed = ref(false);
      const isImposterFound = ref(false);
      const imposter = ref(null);
      const alreadyPressed = ref(false);
      console.log("already pressed", alreadyPressed.value);
>>>>>>> Stashed changes

        const revealImposter = async () => {
            try {
                // Call the endpoint that determines if the imposter is caught
                const response = await axios.post(
                    `api/game/${gameID.value}/reveal-imposter`,
                );
                // Update state based on the response
                isRevealed.value = true;
                isImposterFound.value = response.data.is_imposter_revealed;
                imposter.value = response.data.imposter;
            } catch (error) {
                console.error('Error revealing imposter:', error);
            }
        };

<<<<<<< Updated upstream
         const goToNextRound = async () => {        
=======
      const checkIfPressed= async () => {
          if (alreadyPressed === true) {
            console.log("second person to press")
            router.visit('/promptscreen');
          } else {
            console.log("first person to press")
            alreadyPressed.value = true;
            goToNextRound();
          }
      };

      const goToNextRound = async () => {        
>>>>>>> Stashed changes
        // start the next round. First phase is Lobby so change it to Prompt phase 
        //also increment the round number that imposter is on, if it is already at 3 rounds, then set it to zero and then start the round. 
        console.log('Game ID:', gameID.value);
         const res = await axios.get(`api/rounds/${gameID.value}/reset-or-continue-imposter`);
         if (res.data.newRound){
          router.visit('/roundtally');
         }
         else if (res.data.gameOver){
          router.visit('/finalresult');
         } else{
        router.visit('/promptscreen'); 
         }// goes to promptscreen if the imposter is not caught
      }

<<<<<<< Updated upstream
        // Automatically reveal the imposter after a short delay
        setTimeout(() => {
            revealImposter();
        }, 1000);

        onMounted(() => {
            // Additional onMounted logic here if needed.
        });

        return {
            revealImposter,
            isRevealed,
            isImposterFound,
            imposter,
            goToNextRound,
        };
=======
      setTimeout(() => {
        revealImposter(); 
      }, 1000);
      
    onMounted(() => {
      
    });
  
      return { revealImposter, isRevealed, isImposterFound, imposter, goToNextRound, checkIfPressed };
>>>>>>> Stashed changes
    },
};
</script>
