<template>
    <div class="lobby-page">
        <h1>Lobby</h1>
        <div class="players-list">
            <h2>Players in Lobby:</h2>
            <ul>
                <li v-for="player in players" :key="player.id">
                    {{ player.name }}
                </li>
            </ul>
        </div>
        <button @click="startGame" class="start-button">Start Game</button>
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