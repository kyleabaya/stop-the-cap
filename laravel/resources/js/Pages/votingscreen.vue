<template>
  <div
    class="min-h-screen flex flex-col items-center justify-center p-6 "
    style=" background-size: cover; background-blend-mode: overlay; background-image: url('https://png.pngtree.com/background/20210717/original/pngtree-minimalist-dreamland-tennis-club-picture-image_1439360.jpg');"
  >
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl max-w-xl w-full p-8">
      <h1 class="text-3xl font-extrabold text-center text-blue-900 mb-6">
        🕵️‍♀️ Who's Capping? 
      </h1>

      <div v-if="!hasVoted">
        <div v-if="players.length > 0" class="space-y-4">
          <div
            v-for="player in players"
            :key="player.id"
            class="flex justify-between items-center bg-white border border-gray-200 p-4 rounded-xl shadow-md hover:scale-[1.10] transition-transform"
          >
            <span class="text-lg font-medium text-gray-700">
              👤 {{ player.name }}
            </span>

            <button
              @click="voteForPlayer(player.id)"
              :disabled="hasVoted"
              class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-full hover:from-blue-600 hover:to-indigo-600 transition-all"
            >
              Vote
            </button>
          </div>
        </div>
        <p v-else class="text-center text-gray-600 mt-4">Loading players...</p>
      </div>

      <div v-else class="text-center mt-8">
        <h2 class="text-xl font-semibold text-green-700">You have voted! </h2>
        <p class="text-gray-700 mt-2">Wait until everyone else has finished voting. 🤔 </p>
      </div>
    </div>
  </div>
</template>

  
  <script>
  import { ref, onMounted, onUnmounted} from "vue";
  import axios from "axios";
  import { router } from "@inertiajs/vue3";
  
  export default {
    setup() {
      const players = ref([]);
      const hasVoted = ref(false);
      const gameCode = ref(null); 
      const gameID = ref(localStorage.getItem("game_id"));
      const playerID = ref(localStorage.getItem("player_id"));
      const game_round = ref(1);

  
      const getPlayers = async () => {
        try {
        const response1 = await axios.get(`/api/get-code`);
        gameCode.value = response1.data.code; // assign game code from response
        const response2 =  await axios.get(`/api/getPlayers/${gameCode.value}`);
        players.value = response2.data.players; //asign players from response
        } catch (error) {
          console.error("Error fetching players:", error);
        }
      };

    const fetchLatestRound = async () => {
      const res = await axios.get(`api/rounds/${gameID.value}/latest-round`);
      console.log("Latest round data:", res.data);
      game_round.value = res.data.round_number;
      console.log("Round", game_round.value);
    };

      const voteForPlayer = async (suspectplayerId) => {
        try {
          console.log("Voting for player ID:", suspectplayerId);
          console.log("Voting in Round:", game_round.value);
          if (!game_round.value) {
            console.error("Error: Round ID is null or undefined.");
            return;
        }
        console.log("Sending data:", {
            round_id: Number(game_round.value),
            game_id: Number(gameID.value),
            voter_id: Number(playerID.value),
            suspect_id: suspectplayerId
          });

          axios.post(`/api/rounds/${game_round.value}/votes`, 
            { 
              game_id: Number(gameID.value), 
              round_id: Number(game_round.value), 
              voter_id: Number(playerID.value), 
              suspect_id: suspectplayerId 
            })
          hasVoted.value = true;
          alert("Vote submitted successfully!");
        } catch (error) {
          console.error("Error voting:", error);
        }
      };

      const checkAllVoted = async () => {
        const res = await axios.get(`/api/games/${gameID.value}/has-everyone-voted/${game_round.value}`);        ;
        const allVoted = res.data.all_voted; 
        console.log("Voting status:", res.data);
        if (res.data.all_voted) {
          router.visit("/imposterfoundornot");
        }
    };

      // const checkPhase = async () => {
      //   try {
      //     const response = await axios.get(`api/rounds/${gameID.value}/latest-round`);
      //     game_round.value = response.data.id;
      //     console.log("current phase:", response.data.phases);
      //     if (response.data.phases === "voting") {
      //       console.log("In voting phase");
      //     } else if (response.data.phases === "nextRound") {
      //       axios.post(`api/rounds/nextPhase/${gameID}`); // move to next phase and then refirect
      //       router.visit('/imposterfoundornot');
      //     }
      //   } catch (error) {
      //     console.error("Error", error);
      //   }
      // };
      
    // setInterval(() => checkPhase(), 2000);
    const interval = setInterval(() => checkAllVoted(), 2000);

    onMounted(async () => {
        await fetchLatestRound(); 
        getPlayers();        
      });
    
      onUnmounted(() => {
          clearInterval(interval);
        });
      return { players, voteForPlayer, hasVoted, gameCode, game_round };
    },
  };
  </script>
  