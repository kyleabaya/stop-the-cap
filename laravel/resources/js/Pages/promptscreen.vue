<template>

    <div class="min-h-screen flex flex-col justify-center items-center bg-[url('https://png.pngtree.com/background/20210717/original/pngtree-minimalist-dreamland-tennis-club-picture-image_1439360.jpg')] bg-cover bg-no-repeat bg-center">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">Prompt</h1>
        <div class = text-4xl font-bold >{{prompt}}</div>
        <!-- <div>
            <label for="promptResponse" class="block mb-6 text-sm font-medium text-gray-900 dark:text-white">Submit your response below</label>
            <input v-model="promptTrue" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-800 block w-full p-2.5" required />
        </div> -->

    <div class="relative lg:w-[130px]">
    <img 
      src="https://i.pinimg.com/474x/6b/42/28/6b4228903e0489c5dfcfdf5cc7196e33.jpg" alt="hand" class="lg:w-[130px]" />
    
    <!-- Button (Full overlay over the image) -->
    <button 
      class="absolute w-full h-full top-0 left-0 flex items-center justify-center text-black bg-transparent"
      @click="submitTrue"
    >
    </button>
  </div>
</div>
  </template>


<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const prompt = ref("");  // Store prompt
    const promptResponse = Boolean;  // Store user input
    const gameId = localStorage.getItem("game_id"); 
    const player_id = localStorage.getItem("player_id");
  
    const fetchPrompt = async () => {
        try {
            const response = await axios.get('/api/prompts/random');
            prompt.value = response.data.prompt_text; // assign prompt from response
            console.log("Prompt fetched:", response.data);
   
        } catch (error) {
            console.error("Error fetching prompt:", error);
        }
    }

    //submit the vote when the button is pressed
    const submitTrue = async () => {
        try {
            const response = await axios.post("/api/responses/store", { player_id: player_id, round_id: "chat_for_a_min", raised_hand: true});
            promptResponse.value = false; // Clear input after submission
            console.log("response submitted:", response.data);
        } catch (error) {
            console.error("Error submitting response:", error);
        }
    };

    async function fetchResponses(gameId) {
        const response = await fetch(`/api/responses/${gameId}`);
        const data = await response.json();

        const responseContainer = document.getElementById('responses');
        responseContainer.innerHTML = ""; //clear previous responses

        data.forEach(res => {
            const responseElement = document.createElement("div");
            responseElement.classList.add("response-item");
            
            const raisedHandText = res.raised_hand === 1 ? "Raised Hand" : "Not Raised";

            responseElement.innerHTML = `<strong>${res.player_name}:</strong> ${raisedHandText}`;
            responseContainer.appendChild(responseElement);
        });
}
    // setInterval(() => fetchResponses(gameId), 5000);
    
    onMounted(fetchPrompt); // Fetch prompt when component mounts
    return { prompt, promptResponse, submitTrue };
}
};
</script>
