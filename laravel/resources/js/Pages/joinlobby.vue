<template>
    <div class="joinlobby-page m-20">
        <h2 class = "text-3xl">Enter the code to join the lobby</h2>
        
        <div class="players-list">
            <h2 class = "text-xl">Players in Lobby:</h2>
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{ player.name }}
                </li>
            </ul>
        </div>  
        <button class = "px-6 py-3 text-2xl bg-green-400 text-white rounded-lg hover:bg-green-800">Join</button>
        </div>
</template>

<script>
export default {
    data() {
        return {
            players: [
                // Example players, replace with dynamic data
                { id: 1, name: "Player 1" },
                { id: 2, name: "Player 2" },
            ],
        };
    },
    methods: {
        startGame() {
            // Logic to start the game
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