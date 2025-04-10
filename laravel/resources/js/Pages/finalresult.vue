<template>
    <div
        class="flex h-screen flex-col items-center justify-center p-4"
        style=" background-size: cover; background-blend-mode: overlay; background-image: url('https://img.freepik.com/premium-vector/colorful-bright-confetti-pieces-isolated-white-background_166207-157.jpg');"

    >
        <div
            class="fade-in w-full max-w-lg rounded-lg bg-white p-8 text-center shadow-lg"
        >
            <!-- Page Heading -->
            <h1 class="mb-4 text-5xl font-bold text-black">Game Over</h1>

            <!-- Winner Announcement -->
            <h2 class="mb-6 text-2xl font-bold">
                Winner: {{ winner.name }} with {{ winner.points }} pts!
            </h2>

            <!-- Leaderboard Title -->
            <h3 class="mb-4 text-xl font-semibold">Final Leaderboard</h3>

            <!-- Leaderboard List: All players and their scores -->
            <ul class="text-lg">
                <li
                    v-for="player in players"
                    :key="player.id"
                    class="border-b border-gray-200 py-2"
                >
                    <span class="font-medium">{{ player.name }}</span>
                    — {{ player.points }} pts
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const params = new URLSearchParams(window.location.search);
const gameId = params.get('gameId') || '';

const players = ref([]);
const winner = ref({ name: '', points: 0 });

onMounted(async () => {
    try {
        const { data } = await axios.get(`/api/games/${gameId}/final-results`);
        players.value = data.players;
        winner.value = data.winner;
    } catch (error) {
        console.error('Error fetching final results:', error);
    }
});
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
.fade-in {
    animation: fadeIn 1s ease-in;
}
</style>
