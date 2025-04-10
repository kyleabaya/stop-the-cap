<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
      <h1 class="text-5xl font-bold text-white animate-pulse mb-8">Waiting for Players to Join...</h1>
  
      <!-- Player List -->
      <div class="flex flex-5 items-center">
        <div v-for="player in players" :key="player.id" class="pl-5 bg-white p-4 rounded-lg shadow-lg text-lg font-semibold text-gray-800 animate-bounce">
          {{ player.name }}
        </div>
      </div>
  
      <div class="mt-8 text-white">
        <p class="text-xl">Game Code: {{ gameCode }}</p>
        <p class="text-md mt-2">Total Players: {{ players.length }}</p>
      </div>
  
    </div>

</template>
 

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { router } from "@inertiajs/vue3";

export default {

  setup() {
    const gameCode = ref('');  // Store game code input
    const players = ref([]); 
    const gameID = ref(localStorage.getItem("game_id"));
    const game_round = ref(1);


    const fetchLatestGame = async () => {
  try {
    const response = await axios.get("/api/latest-game");
    gameCode.value = response.data.code; 
    //gameID.value = response.data.id;
    console.log("Fetched Game Code:", gameCode.value); 
    console.log("fetched game id:", gameID.value);
  } catch (error) {
    console.error("Error fetching latest game:", error);
  }
};

    // Fetch players in the lobby based on the game code
    const getPlayers = async () => {
      try {
        console.log("Fetching players for game code:", gameCode.value);
        const response = await axios.get(`/api/getPlayers/${gameCode.value}`);
        players.value = response.data.players; // Set players list
      } catch (error) {
        console.error("Error getting the players:", error);
      }
    };

    const checkPhase = async () => {
        try {
          const response = await axios.get(`api/rounds/${gameID.value}/latest-round`);
          if (response.data.phase === "lobby") {
            console.log("in lobby");
          } else {
            axios.post(`api/rounds/${gameID.value}/next-phase`); // move to next phase and then refirect
            router.visit('/promptscreen');
          }
        } catch (error) {
          console.error("Error checking phase:", error);
        }
      };

    const interval = setInterval(() => checkPhase(), 2000);

    onMounted(async () => {
      await fetchLatestGame();
      console.log("Game ID after fetch:", gameID.value); // This should log after the data is fetched
      getPlayers();
      setInterval(getPlayers, 5000);
      checkPhase();
});

    onUnmounted(() => {
              clearInterval(interval);
            });

    return { gameCode, players, gameID, checkPhase };
}
};
</script>
  
  
  <style scoped>
  @keyframes bounce {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-20px);
    }
  }
  
  .animate-bounce {
    animation: bounce 1s infinite;
  }
  
  .animate-pulse {
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% {
      opacity: 1;
    }
    50% {
      opacity: 0.7;
    }
    100% {
      opacity: 1;
    }
  }
  </style>
  