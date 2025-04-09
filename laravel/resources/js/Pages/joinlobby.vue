<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-r from-pink-200 to-blue-100">
      <div class="bg-white p-8 rounded-xl shadow-lg w-96 text-center">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">Join Lobby</h1>
  
        <div>
            <label  for="name" class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">Username</label>
            <input v-model="name" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-800 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-pink-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="nickname" required />
        </div>
        <div>
            <label for="gameCode" class="block mb-6 text-sm font-medium text-gray-900 dark:text-white">Game Code</label>
            <input v-model="gameCode" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-800 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-pink-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
        </div>
  
        <div class="mt-6">
          <h2 class="text-lg font-semibold text-gray-700">Players in Lobby:</h2>
          <ul class="mt-2 space-y-2">
          </ul>
        </div>

        <button 
        @click="joinLobby"
        class = "px-6 py-3 text-2xl bg-green-400 text-white rounded-lg hover:bg-green-800">Join</button>
      </div>
    </div>
  </template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

export default {
  setup() {
    const name = ref("");  // Store user input
    const gameCode = ref("");  // Store game code input
    const players = ref([]); 
    const currPlayer = ref(null);

    const fetchLatestGame = async () => {
      try {
        const response = await axios.get("/api/latest-game");
        gameCode.value = response.data.game_code; // Set game code from API response
      } catch (error) {
        console.error("Error fetching latest game:", error);
      }
    };

    // Fetch players in the lobby based on the game code -- ((((maybe delete this))))
    const getPlayers = async () => {
      try {
        const response = await axios.get(`/api/getPlayers/${gameCode.value}`);
        players.value = response.data.players; // Set players list
      } catch (error) {
        console.error("Error fetching players:", error);
      }
    };

    console.log("Current Player:", currPlayer.value);
    localStorage.setItem("currPlayer", JSON.stringify(currPlayer.value));

    // Join the game lobby when the button is pressed
    const joinLobby = async () => {
      try {
        const response = await axios.post("/api/join-lobby", { code: gameCode.value, name: name.value });

        //store things in local storage to accces in other pages
        //can condense this to just saving currPlayer value and extracting the rest from there
        
        localStorage.setItem("player_id", response.data.id); 
        localStorage.setItem("player_name", name.value); 
        localStorage.setItem("gameCode", gameCode.value); 
        localStorage.setItem("game_id", response.data.game_id); 

        const player_id = localStorage.getItem("player_id");

        console.log("Current Player:", response.data);
        currPlayer.value = response.data;  //assign the player to the current player
        localStorage.setItem("currPlayer", currPlayer.value);

        // Redirect if this player is an imposter
        if (currPlayer.value.is_imposter) {
          router.visit('/imposter');  
        } else {
          router.visit('/waiting');  
        }

      } catch (error) {
        console.error("Error Joining:", error);
      }
    };

    // Generate a new game lobby
    const generateNewGame = async () => {
      const response = await axios.post("/api/new-game");
      gameCode.value = response.data.game_code; // Set game code from API response};

    //move all players from waiting when the phase is changed

    const checkPhase = async () => {
        try {
          const response = await axios.get(`api/rounds/${gameID.value}/latest-round`);
          if (response.data.phase === "voting") {
            console.log("In voting phase");
          } else {
            axios.post(`api/rounds/${gameID.value}/nextPhase`); // move to next phase and then refirect
            router.visit('/imposterfoundornot');
          }
        } catch (error) {
          console.error("Error checking phase:", error);
        }
      };

    setInterval(checkPhase(), 2000);

    // Fetch latest game code and players when the component loads
    onMounted(() => {
      fetchLatestGame(); 
      fetchPlayer();
    });
  };

    return { gameCode, name, joinLobby, generateNewGame, players, currPlayer };
  }
};
</script>