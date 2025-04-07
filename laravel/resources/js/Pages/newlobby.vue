<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-r from-pink-200 to-blue-100">
        <div class="bg-white p-8 rounded-xl shadow-lg w-96 text-center text-2xl font-bold">
        <h2 class = "text-3xl">Code to join the lobby:</h2>
        <div class = "text-6xl" v-if="gameCode && gameCode !== ''">{{ gameCode }}</div>
        <div v-else>No game code available</div>
        
        <div class="players-list"></div>
            <h2 class = "text-xl">Players in Lobby:</h2>
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{player.name}}
                </li>
            </ul>
        
        <button 
          @click="generateNewCode"
          class="mt-6 px-6 py-3 text-xl font-semibold bg-green-500 text-white rounded-lg shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300">
          Generate New Code
        </button>
        <button 
          @click="startGame"
          class="mt-6 px-6 py-3 text-xl font-semibold bg-green-500 text-white rounded-lg shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300">
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
    const gameCode = ref("");  // game code (empty at first)
    const latestGame = ref(null);
    localStorage.setItem("game_code", gameCode);
    const game_id = ref(localStorage.getItem("game_id"));

    // Fetch players from backend
    const fetchPlayers = async () => {
      try {
        const response1 = await axios.get('/api/get-code');
        gameCode.value = response1.data.code; // assign game code from response

        const response2 =  await axios.get(`/api/getPlayers/${gameCode.value}`);
        players.value = response2.data.players; //asign players from response
      } 
       catch (error) {
        console.error("problem fetching:", error);
      }
    };

    const fetchLatestGame = async () => {
        latestGame.value = await axios.get("/api/latest-game-return-game");
    };

    // Function to start the game
    const startGame = () => {
      console.log("Starting game with ID:", latestGame.value.data);
      const plainGame = JSON.parse(JSON.stringify(latestGame.value.data)); //making the Proxy object a plain object
      axios.post(`/api/rounds/${plainGame.id}/start-round`, {game: plainGame}); // start the the round. First phase is Lobby
      axios.post(`/api/rounds/${latestGame.value.data.id}/next-phase`, { game_id: latestGame.value.data.id } ); 
      // move to next phase of round
      console.log("Game and Round started with ID:", latestGame.value);
      //window.location.href = "/waiting"; // redirect to waiting screen
    };

    const generateNewCode = () => {
        axios.post("/api/new-game"); // generate a new game code
        fetchPlayers(); // fetch players again to update the list
    }
    // run fetchPlayers() after component loads
    onMounted(() => {
      fetchLatestGame();
      //fetchLatestRound(); 
      fetchPlayers();
    });

    return {gameCode, players, startGame, latestGame, generateNewCode, game_id };
  },
};
</script>