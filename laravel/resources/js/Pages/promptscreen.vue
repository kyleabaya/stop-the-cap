<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[url('https://media.istockphoto.com/id/1160039385/vector/wave-sea-with-sky-background-wallpaper.jpg?s=170667a&w=0&k=20&c=PAf2tNhI1ZLU-z2rBVpMWmq2GkHRuNgcg2N1mqE4L9g=')] bg-cover bg-no-repeat bg-center">
      <div class="bg-white p-8 rounded-xl shadow-lg w-96 text-center">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">Prompt</h1>
  
        <div>
            <label for="promptResponse" class="block mb-6 text-sm font-medium text-gray-900 dark:text-white">Game Code</label>
            <input v-model="promptResponse" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-800 block w-full p-2.5" required />
        </div>

        <div>{{prompt}}</div>

        <button 
        @click="submitResponse"
        class = "px-6 py-1 text-2xl bg-green-400 text-white rounded-lg hover:bg-green-800">submit your response!</button>
      </div>
    </div>
  </template>


<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const prompt = ref("");  // Store prompt
    const promptResponse = ref("");  // Store user input

    const fetchPrompt = async () => {
        try {
            const response = await axios.get("/api/prompts/random");
            prompt.value = response.data.prompt; // assign prompt from response
            console.log("Prompt fetched:", prompt.value);
        } catch (error) {
            console.error("Error fetching prompt:", error);
        }
    };

    //submit the vote when the button is pressed
    const submitResponse = async () => {
        try {
            const response = await axios.post("/api/responses/store", { promptResponse: promptResponse.value });
            console.log("response submitted:", response.data);
        } catch (error) {
            console.error("Error submitting response:", error);
        }
    };
    
    onMounted(fetchPrompt); // Fetch prompt when component mounts
    return { prompt, promptResponse, submitResponse };
}
};
</script>
