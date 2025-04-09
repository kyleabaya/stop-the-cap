<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[url('https://png.pngtree.com/background/20210717/original/pngtree-minimalist-dreamland-tennis-club-picture-image_1439360.jpg')] bg-cover bg-no-repeat bg-center">
        <div v-if="isImposter">
    <h2 class="text-6xl font-bold mb-4 text-gray-800" >You are the Capper, blend in!</h2>
  </div>
  <div v-else>
      <h1 class="text-5xl font-bold mb-4 text-gray-800 items-center justify-center">Prompt:</h1>
        <div class ="text-4xl font-bold" >{{prompt}}</div>
  </div>
  <!-- if the player has not responded -->
     <div v-if ="!hasResponded">
  <div class="flex space-x-4 px pt-10">
    <!-- For the no button -->
    <div class="relative lg:w-[130px] hover:scale-[1.25] transition-transform mb-5 pr-8">
    <img 
      src="https://static.vecteezy.com/system/resources/previews/007/804/318/non_2x/close-icon-delete-logo-illustration-isolated-on-white-background-editable-stroke-vector.jpg " alt="no" class="lg:w-[130px]" />
    
    <!-- Button (Full overlay over the image) -->
    <button 
      class="absolute w-full h-full top-0 left-0 flex items-center justify-center text-black bg-transparent hover:scale-[1.25] transition-transform"
      @click="submitFalse"
    >
    </button>
    <p>click the x for no</p>
    </div>

    <div class="relative lg:w-[130px] hover:scale-[1.25] transition-transform mb-5 pl-8">
    <img 
      src="https://i.pinimg.com/474x/6b/42/28/6b4228903e0489c5dfcfdf5cc7196e33.jpg" alt="hand" class="lg:w-[130px]" />
    
    <!-- Button (Full overlay over the image) -->
    <button 
      class="absolute w-full h-full top-0 left-0 flex items-center justify-center text-black bg-transparent hover:scale-[1.25] transition-transform"
      @click="submitTrue"
    >
    </button
    @click="submitFalse">
    <p>click the hand for yes</p>
  </div> 
    </div>
  </div>
    <div v-else>
      <h2 class="text-3xl font-bold mb-4 text-gray-800">You have already responded!</h2>
      <p class="text-xl">Wait for the other players to respond.</p>
    
  </div>
</div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router"; 

export default {
  setup() {
    const prompt = ref("");  // Store prompt
    const promptResponse = ref(false);  // Store user input
    const gameID = ref(localStorage.getItem("game_id")); 
    const gameCode = ref(localStorage.getItem("gameCode"));
    const player_id = ref(localStorage.getItem("player_id"));
    const game_round = ref({}); // Store game round
    const router = useRouter();
    const isImposter = ref(false);
    const hasResponded = ref(false); // Store if player has responded

    //For the check if all players have responded function
    const allPlayers = ref([]);
    const allResponses = ref([]);

    console.log("Game ID:", gameID.value);
    console.log("Player ID:", player_id.value);

    //player_id = ref(null); // Store player ID
    //const stored = ref(JSON.parse(localStorage.getItem("currPlayer") || "{}"));
    //player_id.value = stored.id;

    const checkIfPlayerIsImposter = async () => {
          try {
            const currPlayer = await axios.get(`/api/getPlayer/${player_id.value}`);
            console.log("Current Player Data:", currPlayer.data.player);
            isImposter.value = currPlayer.data.player.is_imposter;
            console.log("Is Imposter:", isImposter.value);
          } catch (error) {
            console.error("Error checking if player is imposter:", error);
          }
        };

    const fetchPrompt = async () => {
        try {
            const response = await axios.get('/api/prompts/random');
            prompt.value = response.data.prompt_text; // assign prompt from response
            console.log("Prompt fetched:", response.data.prompt_text);
            localStorage.setItem("prompt", JSON.stringify(response.data.prompt_text)); //store in local storage
   
        } catch (error) {
            console.error("Error fetching prompt:", error);
        }
    };

    const fetchLatestRound = async () => {
      const res = await axios.get(`api/rounds/${gameID.value}/latest-round`);
      console.log("Latest round data:", res.data.round_number);
     
      game_round.value = res.data; //submitTrue takes in the round id
      console.log("Round", game_round.value);
    };
  

    //submit the vote when the button is pressed
    const submitTrue = async () => {
        try {
            console.log("Player ID:", player_id.value);
            const response = await axios.post(`/api/responses/${game_round.value.id}/store`, {
                player_id: player_id.value,
                raised_hand: true
              });
            promptResponse.value = false; // Clear input after submission
            hasResponded.value = true; 
            console.log("response submitted:", response.data);
        } catch (error) {
            console.error("Error submitting response:", error);
        }
    };

    const submitFalse = async () => {
        try {
            console.log("Player ID:", player_id.value);
            const response = await axios.post(`/api/responses/${game_round.value.id}/store`, {
                player_id: player_id.value,
                raised_hand: false
              });
            promptResponse.value = false; // Clear input after submission
            hasResponded.value = true;
            console.log("response submitted:", response.data);
        } catch (error) {
            console.error("Error submitting response:", error);
        }
    };

    const checkIfAllResponded = async () => {
  try {
    const [playersRes, responsesRes] = await Promise.all([
      axios.get(`/api/games/${gameCode.value}/players`),
      axios.get(`/api/responses/${gameID.value}`)
    ]);

    console.log("Players fetched:", playersRes.data.players);
    console.log("Responses fetched:", responsesRes.data);

    allPlayers.value = playersRes.data.players;
    allResponses.value = responsesRes.data;

    const respondedIDs = new Set(allResponses.value.map(r => r.player_id));
    const everyoneResponded = allPlayers.value.every(player =>
      respondedIDs.has(player.id)
    );
    console.log("All players responded:", everyoneResponded);
    if (everyoneResponded) {
      router.visit('/chatscreen'); 
    }

  } catch (error) {
    console.error('Error checking responses:', error);
  }
};

    async function fetchResponses(gameID) {
        const response = await fetch(`/api/responses/${gameID.value}`);
        const data = await response.json();
}
    setInterval(() => fetchResponses(gameID), 5000);
    setInterval(() => checkIfAllResponded(), 5000);
    onMounted(() => {
      fetchLatestRound(); 
      fetchPrompt();
      checkIfAllResponded();
      checkIfPlayerIsImposter();
    });

    return { prompt, promptResponse, submitTrue, fetchPrompt, gameID, game_round, player_id,
            fetchLatestRound, fetchResponses, submitFalse, isImposter};
}
};
</script>
