<template>
    <div class = "m-30 bg-gradient-to-r from-pink-200 to-blue-100 h-max">
      <h2 class = "text-3xl">Live Chat</h2>
      <div class = "rounded-sm bg-gradient-to-r to-pink-50 from-blue-50" id="chat-box">
        <div v-for="msg in messages" :key="msg.id" class="message">
          {{ msg.content.name }}: {{ msg.content }}
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
      // const currentPlayerId = ref(null); //replaced later

    const fetchGameId = async () => {
        const response = await axios.get("/api/latest-game");
        currentGameId.value = response.data.id; // Store game ID of the latest game
        const playerName = localStorage.getItem("player_name");
        // const currentPlayerId = localStorage.getItem("playerId");

        console.log("Current Game ID:", currentGameId.value);
        console.log("Current Player Name:", playerName);

    };

      // Fetch all messages initially
      const fetchMessages = async () => {
        console.log("Fetching messages for game ID:", currentGameId.value);
        const response = await axios.get(`/api/messages/${currentGameId.value}`);
        console.log("response: ", response);
        // currentPlayerId.value = localStorage.getItem("playerId");
        console.log("Messages fetched:", response.data);
        messages.value = response.data;
        lastMessageTime = response.data.length > 0 ? response.data[0].created_at : "";
      };
  
      // Fetch new messages every 0.5 seconds
      const fetchNewMessages = async () => {
        if (!lastMessageTime) return;
        const response = await axios.post("/api/messages/new", { 
                last_message_time: lastMessageTime, game_id: currentGameId});
                messages.value.push(...response.data);

        if (response.data.length > 0) lastMessageTime = response.data[response.data.length - 1].created_at;
      };
  
      // Send a new message
      const sendMessage = async () => {
        if (!newMessage.value.trim()) return;
        const response = await axios.post("/api/messages/send", { 
                content: newMessage.value, 
                game_id: currentGameId.value
                });

        if (response.data.success) {
          newMessage.value = ""; // Clear input
          fetchNewMessages(); // Immediately show new message
        }
      };
  
      onMounted(async () => {
        await fetchGameId();
        fetchMessages();
        setInterval(fetchNewMessages, 500); // Check for new messages every 0.5 seconds
      });
  
      return { messages, newMessage, sendMessage };
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
  