<template>
    <div
        class="flex h-screen flex-col items-center justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-4"
    >
        <!-- Heading -->
        <h1 class="mb-8 text-5xl font-bold text-black">Round Tally</h1>

        <!-- Results Card -->
        <div
            class="w-full max-w-lg rounded-lg bg-white p-8 text-center shadow-lg"
        >
            <!-- Display Tally Message from the API -->
            <p class="mb-4 text-xl font-semibold">
                {{ tallyMessage }}
            </p>

            <!-- Leaderboard Heading -->
            <h2 class="mb-4 text-2xl font-bold">Leaderboard</h2>

            <!-- Leaderboard Table: List all players with their scores -->
            <table class="mx-auto w-full text-left">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="player in players" :key="player.id">
                        <td class="border px-4 py-2">
                            {{ player.name }}
                            <span v-if="player.is_imposter" class="text-red-500"
                                >(Imposter)</span
                            >
                        </td>
                        <td class="border px-4 py-2">{{ player.points }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Button to proceed to the next round (or prompt screen) -->
            <button
                @click="nextImposterRound"
                :disabled="!buttonEnabled"
                class="mt-6 rounded px-6 py-2 text-white transition-all"
                :class="
                    buttonEnabled
                        ? 'bg-blue-600 hover:bg-blue-700'
                        : 'bg-gray-400'
                "
            >
                {{ buttonEnabled ? 'Next Imposter Round' : 'Loading...' }}
            </button>
        </div>

        <!-- Footer: Display game code for reference -->
        <div class="mt-8 text-white" v-if="gameCode">
            <p class="text-md">Game Code: {{ gameCode }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Extract gameId from URL query parameters using URLSearchParams
const params = new URLSearchParams(window.location.search);
//const gameId = params.get('gameId') || '';

const gameId = ref(localStorage.getItem('game_id'));

const gameCode = ref('');
const players = ref([]);
const tallyMessage = ref('');

const fetchGameCode = async () => {
    try {
        const res = await axios.get('/api/latest-game');
        gameCode.value = res.data.code;
    } catch (error) {
        console.error('Error fetching game code:', error);
        tallyMessage.value = 'Failed to fetch game code.';
    }
};

const isGameOver = ref(false);

const fetchRoundTally = async () => {
    try {
        const res = await axios.get(
            `/api/games/${gameId.value}/imposter-tally`,
        );
        tallyMessage.value = res.data.message;
        players.value = res.data.players || [];
    } catch (error) {
        console.error('Error fetching round tally:', error);
        tallyMessage.value = 'Failed to fetch round tally.';
    }
};

const nextImposterRound = () => {
    if (isGameOver.value) {
        window.location.href = '/finalresult?gameId=${gameId}';
    } else {
        window.location.href = '/promptscreen?gameId=${gameId}';
    }
};

const buttonEnabled = ref(false);
onMounted(async () => {
    await fetchGameCode();
    await fetchRoundTally();
    setTimeout(() => {
        buttonEnabled.value = true;
    }, 3000);
});
</script>
