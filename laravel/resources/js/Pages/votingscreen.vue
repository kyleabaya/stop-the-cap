<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center p-6"
        style="
            background-size: cover;
            background-blend-mode: overlay;
            background-image: url('https://png.pngtree.com/background/20210717/original/pngtree-minimalist-dreamland-tennis-club-picture-image_1439360.jpg');
        "
    >
        <div
            class="w-full max-w-xl rounded-2xl bg-white/80 p-8 shadow-xl backdrop-blur-md"
        >
            <h1 class="mb-6 text-center text-3xl font-extrabold text-blue-900">
                🕵️‍♀️ Who's Capping?
            </h1>

            <div v-if="!hasVoted">
                <div v-if="players.length > 0" class="space-y-4">
                    <div
                        v-for="player in players"
                        :key="player.id"
                        class="flex items-center justify-between rounded-xl border border-gray-200 bg-white p-4 shadow-md transition-transform hover:scale-[1.10]"
                    >
                        <span class="text-lg font-medium text-gray-700">
                            👤 {{ player.name }}
                        </span>

                        <button
                            @click="voteForPlayer(player.id)"
                            :disabled="hasVoted"
                            class="rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 px-4 py-2 font-semibold text-white transition-all hover:from-blue-600 hover:to-indigo-600"
                        >
                            Vote
                        </button>
                    </div>
                </div>
                <p v-else class="mt-4 text-center text-gray-600">
                    Loading players...
                </p>
            </div>

            <div v-else class="mt-8 text-center">
                <h2 class="text-xl font-semibold text-green-700">
                    You have voted!
                </h2>
                <p class="mt-2 text-gray-700">
                    Wait until everyone else has finished voting. 🤔
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

export default {
    setup() {
        const players = ref([]);
        const hasVoted = ref(false);
        const gameCode = ref(null);
        const gameID = ref(localStorage.getItem('game_id'));
        const playerID = ref(localStorage.getItem('player_id'));
        // Use the unique round id instead of the round number.
        const game_round = ref(null);

        const getPlayers = async () => {
            try {
                const response1 = await axios.get(`/api/get-code`);
                gameCode.value = response1.data.code;
                const response2 = await axios.get(
                    `/api/getPlayers/${gameCode.value}`,
                );
                players.value = response2.data.players;
            } catch (error) {
                console.error('Error fetching players:', error);
            }
        };

        const fetchLatestRound = async () => {
            try {
                const res = await axios.get(
                    `/api/rounds/${gameID.value}/latest-round`,
                );
                console.log('Latest round data:', res.data);
                // Set game_round to the unique round ID.
                game_round.value = res.data.id;
                console.log('Round (ID):', game_round.value);
            } catch (error) {
                console.error('Error fetching latest round:', error);
            }
        };

        const voteForPlayer = async (suspectplayerId) => {
            try {
                console.log('Voting for player ID:', suspectplayerId);
                console.log('Voting in Round (ID):', game_round.value);
                if (!game_round.value) {
                    console.error('Error: Round ID is null or undefined.');
                    return;
                }
                console.log('Sending data:', {
                    round_id: Number(game_round.value),
                    game_id: Number(gameID.value),
                    voter_id: Number(playerID.value),
                    suspect_id: suspectplayerId,
                });
                // Await the vote submission to ensure it is recorded properly.
                await axios.post(`/api/rounds/${game_round.value}/votes`, {
                    game_id: Number(gameID.value),
                    round_id: Number(game_round.value),
                    voter_id: Number(playerID.value),
                    suspect_id: suspectplayerId,
                });
                hasVoted.value = true;
                console.log('Vote submitted successfully!');
            } catch (error) {
                console.error('Error voting:', error);
            }
        };

        const checkAllVoted = async () => {
            try {
                const res = await axios.get(
                    `/api/games/${gameID.value}/has-everyone-voted/${game_round.value}`,
                );
                console.log('Voting status:', res.data);
                if (res.data.all_voted) {
                    router.visit('/imposterfoundornot');
                }
            } catch (error) {
                console.error('Error checking if everyone voted:', error);
            }
        };

        const pollingInterval = setInterval(() => {
            checkAllVoted();
        }, 2000);

        onMounted(async () => {
            await fetchLatestRound();
            await getPlayers();
            console.log('Players count:', players.value.length);
        });

        onUnmounted(() => {
            clearInterval(pollingInterval);
        });

        return { players, voteForPlayer, hasVoted, gameCode, game_round };
    },
};
</script>
