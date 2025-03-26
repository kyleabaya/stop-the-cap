<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-r from-pink-200 to-blue-100">
      <div class="bg-white p-8 rounded-xl shadow-lg w-96 text-center">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">Join Lobby</h1>
  
        <div v-if="gameCode" class="text-2xl font-mono bg-gray-200 p-4 rounded-lg text-gray-900">
          {{ gameCode }}
        </div>
        <p v-else class="text-gray-500">No active lobby. Start a new one!</p>
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
            <li v-for="player in players" :key="player.id" class="bg-gray-100 px-4 py-2 rounded-md shadow-sm">
              {{ player.name }}
            </li>
          </ul>
        </div>

        <button 
        @click="joinLobby"
        class = "px-6 py-3 text-2xl bg-green-400 text-white rounded-lg hover:bg-green-800">Join</button>
      </div>
    </div>
  </template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    //For user input 
    const name = ref("");  // Store user input
    const gameCode = ref("");  // Store game code input
    const players = axios.get("/api/getPlayers");  // players in the lobby

    // Fetch the latest game code from the database and join game if the codes match
    const fetchLatestGame = async () => {
      try {
        const response = await axios.get("/api/latest-game");
      } catch (error) {
        console.error("Error fetching latest game:", error);
      }
    };

    // join the game lobby when the button is pressed
    const joinLobby = async () => {
      try {
        await axios.post("/api/join-lobby", { code: gameCode.value, name: name.value });
      } catch (error) {
        console.error("Error Joining:", error);
      }
    };

    // generate a new game lobby
    const generateNewGame = async () => {
        const response = await axios.post("/api/new-game");
    };

    // Fetch latest game code when the component loads
    onMounted(fetchLatestGame);

    return { gameCode, name, joinLobby, generateNewGame };
  }
};
</script>