
    <template>
    <div class="joinlobby-page m-20">
        <h1 class = "text-6xl">Code to Join:</h1>
    <div v-if="gameCode" class="text-xl font-mono bg-gray-200 p-4 rounded">
      Current Game Code: <strong>{{ gameCode }}</strong>
    </div>
    <p v-else class="text-gray-500">No active lobby. Start a new one!</p>
    
        <div class="players-list">

        <h2 class = "text-xl">Players in Lobby:</h2>
          </div>
        <button @click="startGame" class="w-full mb-2 start-button px-6 py-3 text-2xl bg-green-400 text-white rounded-lg hover:bg-green-800 ">Start Game</button>
        </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const gameCode = ref(null);

    // Fetch the latest game code from the database
    const fetchLatestGame = async () => {
      try {
        const response = await axios.get("/api/latest-game");
        gameCode.value = response.data.code;
      } catch (error) {
        console.error("Error fetching latest game:", error);
      }
    }

    // Create a new game lobby and get a new code
    const generateNewGame = async () => {
      try {
        const response = await axios.post("/api/new-game");
        gameCode.value = response.data.code; // Update UI with new code
      } catch (error) {
        console.error("Error generating new game:", error);
      }
    }  // Fetch latest game when the component mounts
    onMounted(fetchLatestGame);

    return { gameCode, generateNewGame };
  }
}

</script>