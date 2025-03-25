<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-r from-pink-200 to-blue-100">
        <div class="bg-white p-8 rounded-xl shadow-lg w-96 text-center">
        <h2 class = "text-3xl">Code to join the lobby:</h2>
        
        <div class="players-list">
            <h2 class = "text-xl">Players in Lobby:</h2>
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{ player.name }}
                </li>
            </ul>
        </div>  
        <button 
          @click="startGame"
          class="mt-6 w-full px-6 py-3 text-xl font-semibold bg-green-500 text-white rounded-lg shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300"
        >
          Start Game
        </button>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            players: [
                //replace with dynamic data
                { id: 1, name: "Player 1" },
                { id: 2, name: "Player 2" },
            ],
        };
    },
    methods: {
        startGame() {
            //logic to start the game
            console.log("Game started!");
        },
    },
};

</script>

<style scoped>
.lobby-page {
    text-align: center;
    padding: 20px;
}

.players-list {
    margin: 20px 0;
}

.start-button {
    margin: auto;
}

</style>style scoped>