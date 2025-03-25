<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-r from-pink-200 to-blue-100">
        <div class="bg-white p-8 rounded-xl shadow-lg w-96 text-center">
        <h2 class = "text-3xl">Code to join the lobby:</h2>
        
        <div class="players-list">
            <h2 class = "text-xl">Players in Lobby:</h2>
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{player.name}}
                </li>
            </ul>
        </div> 
        <button 
          @click="startGame"
          class="mt-6 w-full px-6 py-3 text-xl font-semibold bg-green-500 text-white rounded-lg shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300">
          Start Game
        </button>
    </div>
</div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {

    const players = ref([]);  // players list (empty at first)
    const gameCode = ref("ABC123"); // Sample game code (replace with dynamic data)

    // Fetch players from backend
    const fetchPlayers = async () => {
      try {
        const response = await axios.get(`/api/players/${gameCode.value}`);
        players.value = response.data;
      } catch (error) {
        console.error("problem fetching players:", error);
      }
    };

    // Function to start the game
    const startGame = () => {
      console.log("Game started!");
    };

    // Run fetchPlayers() after component loads
    onMounted(fetchPlayers);

    return { gameCode, players, startGame };
  },
};
</script>