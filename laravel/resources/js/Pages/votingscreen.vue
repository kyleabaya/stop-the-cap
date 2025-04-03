<template>
    <div class="p-6 max-w-lg mx-auto">
      <h1 class="text-2xl font-bold text-center mb-4">Vote for a Player</h1>
  
      <div v-if="players.length > 0">
        <div 
          v-for="player in players" 
          :key="player.id" 
          class="flex justify-between items-center bg-gray-200 p-4 rounded-lg shadow mb-2"
        >
          <span class="text-lg font-semibold">{{ player.name }}</span>
          <button 
            @click="voteForPlayer(player.id)" 
            :disabled="hasVoted"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 disabled:bg-gray-400"
          >
            Vote
          </button>
        </div>
      </div>
      <p v-else class="text-center text-gray-500">Loading players...</p>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from "vue";
  import axios from "axios";
  
  export default {
    setup() {
      const players = ref([]);
      const hasVoted = ref(false);
      const gameCode = ref(null); 
      const gameID = localStorage.getItem("game_id")
      const playerID = localStorage.getItem("player_id")
      const game_round = ref(1)
  
      const getPlayers = async () => {
        try {
        const response1 = await axios.get("/api/get-code");
        gameCode.value = response1.data.code; // assign game code from response
        const response2 =  await axios.get(`/api/getPlayers/${gameCode.value}`);
        players.value = response2.data.players; //asign players from response
        } catch (error) {
          console.error("Error fetching players:", error);
        }
      };

    const fetchLatestRound = async () => {
      const res = await axios.get('api/rounds/${gameID.value}/latest-round');
      game_round.value = res.data.round_number;
      console.log("Round", game_round);
    }
  
      const voteForPlayer = async (playerId) => {
        try {
          await axios.post("/api/rounds/vote", { round: game_round, game_id: gameID, player_id: playerID });
          hasVoted.value = true;
          alert("Vote submitted successfully!");
        } catch (error) {
          console.error("Error voting:", error);
        }
      };
      
    onMounted(() => {
      fetchLatestRound(); 
      getPlayers();
    });
  
      return { players, voteForPlayer, hasVoted, gameCode, game_round };
    },
  };
  </script>
  