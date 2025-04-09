<template>
  <div class = "justify center bg-gradient-to-r from-pink-200 to-blue-100">
    <p class = "text-center">This is the chat screen. You can send and receive messages here.</p>
    <p class = "text-center">The game ID is: {{ currentGameId }}</p>
    <p class = "text-center">The player is: {{ currentPlayerId }}</p>
    <p class = "text-center text-2xl font-bold">Prompt : {{ prompt }}</p>

  <div class="flex space-x-4">
    <div v-for="response in responses" :key="response.id" class="rounded-sm  p-2">
      {{ response.player?.name }} ->{{ response.raised_hand ? '✋' : '✊' }}
    </div>      
  </div>
 </div>

    <!-- 60 second timer -->
    <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg">
    <p class="text-lg font-bold">Time Left: {{ timeLeft }}</p>
    </div>

 
    <div class = "m-30 bg-gradient-to-r from-pink-200 to-blue-100 h-max">
      <h2 class = "text-3xl">Live Chat</h2>
      <div class = "rounded-sm bg-gradient-to-r to-pink-50 from-blue-50" id="chat-box">
        <div v-for="msg in messages" :key="msg.id" class="message">
          <strong>{{ msg.player?.name || 'Unknown' }}:</strong> {{ msg.content }}
        </div>
      </div>
  
      <input type="text" v-model="newMessage" placeholder="Type your message">
      <button @click="sendMessage"
      class="mt-6 px-3 py-2 text-l font-semibold bg-green-400 text-white rounded-lg shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300">
      >Send</button>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  import { ref, onMounted } from "vue";
  
  export default {
    setup() {
      const messages = ref([]);
      const newMessage = ref("");
      let lastMessageTime = "";
      const currentGameId = ref(null); //replaced later
      const currentPlayerId = ref(null); //replaced later
      const responses = ref([]); //responses from the previous screen so they can vote on them
      const timeLeft = ref(60); // 60 seconds timer
      const prompt = localStorage.getItem("prompt"); // get the prompt from local storage
      console.log("Prompt:", prompt);

    const fetchGameId = async () => {
        const response = await axios.get("/api/latest-game");
        currentGameId.value = response.data.id; // Store game ID of the latest game
        const playerName = localStorage.getItem("player_name");
        const currentPlayerId = localStorage.getItem("player_id");

        console.log("Current Game ID:", currentGameId.value);
        console.log("Current Player Name:", playerName);

    };
    const redirect = async () =>{
          window.location.href = "/votingscreen"; 
        };

    const startCountdown = async () =>{
      const timer = setInterval(() => {
        if (timeLeft.value > 0) {
          timeLeft.value--;
        } else {
          clearInterval(timer);
          redirect();
        }
      }, 1000);
    };
    
      // Fetch all messages initially
      const fetchMessages = async () => {
        console.log("Fetching messages for game ID:", currentGameId.value);
        const response = await axios.get(`/api/messages/${currentGameId.value}`);
        currentPlayerId.value = localStorage.getItem("player_id");
        console.log("Messages fetched:", response.data);
        messages.value = response.data;
        lastMessageTime = response.data.length > 0 ? response.data[0].created_at : "";
      };
  
      // Fetch new messages every 0.5 seconds
      // const fetchNewMessages = async () => {
      //   if (!lastMessageTime) return;
      //   const response = await axios.post("/api/messages/new", { 
      //           last_message_time: lastMessageTime, game_id: currentGameId});
      //           messages.value.push(...response.data);

      //   if (response.data.length > 0) lastMessageTime = response.data[response.data.length - 1].created_at;
      // };
  
      // Send a new message
      const sendMessage = async () => {
        if (!newMessage.value.trim()) return;
        const response = await axios.post("/api/messages/send", { 
                content: newMessage.value, 
                game_id: currentGameId.value,
                player_id: currentPlayerId.value
                });

        if (response.data.success) {
          newMessage.value = ""; // Clear input
          fetchNewMessages(); // Immediately show new message
        }
      };

      //fetch responses to be displayed at the top of the screen
      const fetchResponses = async () => {
        try {
          const response = await fetch(`/api/responses/${currentGameId.value}`);
          if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
          
          const data = await response.json();
          console.log("Responses fetched:", data);
          responses.value = data ?? [];
        } catch (error) {
          console.error("Error fetching responses:", error);
          responses.value = [];
        }
      };

      setInterval(fetchMessages, 500); // Check for new messages every 0.5 seconds

      onMounted(async () => {
        startCountdown();
        await fetchGameId();
        fetchMessages();
        fetchResponses();
        
        
      });
  
      return { messages, newMessage, sendMessage, responses, timeLeft, currentPlayerId, currentGameId, prompt };
    },
  };
  </script>
  
  <style scoped>
  #chat-box {
    width: 60%;
    margin: auto;
    border: 1px solid #ccc;
    padding: 10px;
    height: 600px;
    overflow-y: auto;
  }
  .message {
    padding: 5px;
    border-bottom: 1px solid #ddd;
  }
  </style>
  