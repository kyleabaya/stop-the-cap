<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-[url('https://png.pngtree.com/background/20210717/original/pngtree-minimalist-dreamland-tennis-club-picture-image_1439360.jpg')] bg-cover bg-center bg-no-repeat"
    >
        <div v-if="isImposter">
            <h2 class="mb-4 text-6xl font-bold text-gray-800">
                You are the Capper, blend in!
            </h2>
        </div>
        <div v-else>
            <h1
                class="mb-4 items-center justify-center text-5xl font-bold text-gray-800"
            >
                Prompt:
            </h1>
            <div class="text-4xl font-bold">{{ prompt }}</div>
        </div>
        <!-- if the player has not responded -->
        <div v-if="!hasResponded">
            <div class="flex space-x-4 pt-10">
                <!-- For the no button -->
                <div
                    class="relative mb-5 pr-8 transition-transform hover:scale-[1.25] lg:w-[130px]"
                >
                    <img
                        src="https://static.vecteezy.com/system/resources/previews/007/804/318/non_2x/close-icon-delete-logo-illustration-isolated-on-white-background-editable-stroke-vector.jpg"
                        alt="no"
                        class="lg:w-[130px]"
                    />
                    <!-- Button over the image -->
                    <button
                        class="absolute left-0 top-0 flex h-full w-full items-center justify-center bg-transparent text-black transition-transform"
                        @click="submitFalse"
                    ></button>
                    <p>click the x for no</p>
                </div>

                <!-- For the yes button -->
                <div
                    class="relative mb-5 pl-8 transition-transform hover:scale-[1.25] lg:w-[130px]"
                >
                    <img
                        src="https://i.pinimg.com/474x/6b/42/28/6b4228903e0489c5dfcfdf5cc7196e33.jpg"
                        alt="hand"
                        class="lg:w-[130px]"
                    />
                    <!-- Button over the image -->
                    <button
                        class="absolute left-0 top-0 flex h-full w-full items-center justify-center bg-transparent text-black transition-transform"
                        @click="submitTrue"
                    ></button>
                    <p>click the hand for yes</p>
                </div>
            </div>
        </div>

        <!-- if the player has responded -->
        <div v-else>
            <h2 class="mb-4 text-3xl font-bold text-gray-800">
                You have already responded!
            </h2>
            <p class="text-xl">Wait for the other players to respond.</p>
        </div>
        <!-- Countdown Timer Box -->
        <div
            class="fixed right-4 top-4 rounded-lg bg-yellow-400 px-4 py-2 text-black shadow-lg"
        >
            <p class="text-lg font-bold">Time Left: {{ timeLeft }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
    setup() {
        // State variables
        const prompt = ref('');
        const promptResponse = ref(false);
        const gameID = ref(localStorage.getItem('game_id'));
        const gameCode = ref(localStorage.getItem('gameCode'));
        const player_id = ref(localStorage.getItem('player_id'));
        const game_round = ref({});
        const isImposter = ref(false);
        const hasResponded = ref(false);
        // For polling/checking responses
        const allPlayers = ref([]);
        const allResponses = ref([]);
        let transitioned = false; // Prevent multiple navigations

        // Countdown state
        const timeLeft = ref(12);

        console.log('Game ID:', gameID.value);
        console.log('Player ID:', player_id.value);

        // Start the countdown timer
        const startCountdown = () => {
            const timer = setInterval(async () => {
                if (timeLeft.value > 0) {
                    timeLeft.value--;
                } else {
                    clearInterval(timer);
                    // If the player hasn't responded, auto-submit a "no" response
                    if (!hasResponded.value) {
                        console.log(
                            'Auto-submitting response as false due to timeout.',
                        );
                        await submitFalse(); // Auto-submit with raised_hand false
                    }
                    // Transition to the chat screen
                    router.visit('/chatscreen');
                }
            }, 1000);
        };

        // Check if the current player is the imposter
        const checkIfPlayerIsImposter = async () => {
            try {
                const currPlayer = await axios.get(
                    `/api/getPlayer/${player_id.value}`,
                );
                console.log('Current Player Data:', currPlayer.data.player);
                isImposter.value = currPlayer.data.player.is_imposter;
                console.log('Is Imposter:', isImposter.value);
            } catch (error) {
                console.error('Error checking if player is imposter:', error);
            }
        };

        // Fetch random prompt for the round
        const fetchPrompt = async () => {
            try {
                const res = await axios.get(
                    '/api/rounds/${gameID.value}/latest-round',
                );
                prompt.value = res.data.prompt.prompt_text;
                console.log('Prompt fetched:', response.data.prompt_text);
                localStorage.setItem(
                    'prompt',
                    JSON.stringify(response.data.prompt_text),
                );
            } catch (error) {
                console.error('Error fetching prompt:', error);
            }
        };

        // Fetch the latest round for this game
        const fetchLatestRound = async () => {
            try {
                const res = await axios.get(
                    `/api/rounds/${gameID.value}/latest-round`,
                );
                console.log('Latest round data:', res.data.round_number);
                game_round.value = res.data;
                console.log('Round', game_round.value);
            } catch (error) {
                console.error('Error fetching latest round:', error);
            }
        };

        // Submit a true (yes) response
        const submitTrue = async () => {
            try {
                console.log('Player ID:', player_id.value);
                const response = await axios.post(
                    `/api/responses/${game_round.value.id}/store`,
                    {
                        player_id: player_id.value,
                        raised_hand: true,
                    },
                );
                promptResponse.value = false;
                hasResponded.value = true;
                console.log('Response submitted:', response.data);
            } catch (error) {
                console.error('Error submitting response:', error);
            }
        };

        // Submit a false (no) response
        const submitFalse = async () => {
            try {
                console.log('Player ID:', player_id.value);
                const response = await axios.post(
                    `/api/responses/${game_round.value.id}/store`,
                    {
                        player_id: player_id.value,
                        raised_hand: false,
                    },
                );
                hasResponded.value = true;
                console.log('Response submitted:', response.data);
            } catch (error) {
                console.error('Error submitting response:', error);
            }
        };

        // Check if all players have responded by comparing response count to player count
        const checkIfAllResponded = async () => {
            try {
                const [playersRes, responsesRes] = await Promise.all([
                    axios.get(`/api/games/${gameID.value}/players`),
                    axios.get(`/api/responses/${gameID.value}`),
                ]);

                console.log('Players fetched:', playersRes.data.players);
                console.log('Responses fetched:', responsesRes.data);

                allPlayers.value = playersRes.data.players;
                allResponses.value = responsesRes.data;

                const respondedIDs = new Set(
                    allResponses.value.map((r) => r.player_id),
                );
                const everyoneResponded = allPlayers.value.every((player) =>
                    respondedIDs.has(player.id),
                );
                console.log('All players responded:', everyoneResponded);
                if (everyoneResponded && !transitioned) {
                    transitioned = true; // Avoid multiple navigations
                    router.visit('/chatscreen');
                }
            } catch (error) {
                console.error('Error checking responses:', error);
            }
        };
    const checkIfAllResponded = async () => {
    try {
    const res = await axios.get(`/api/games/${gameID.value}/rounds/${game_round.value.id}/has-everyone-responded`);
    if (res.data.all_responded) {
      router.visit('/chatscreen');
    } else {
      console.log("Waiting for more responses");
    } 
  } catch (error) {
    console.error("Failed to check responses:", error);
  }
};

        // Start polling and countdown on component mount
        onMounted(() => {
            fetchLatestRound();
            fetchPrompt();
            checkIfPlayerIsImposter();
            checkIfAllResponded();
            startCountdown();

            setInterval(() => checkIfAllResponded(), 5000);
        });

        return {
            prompt,
            promptResponse,
            submitTrue,
            submitFalse,
            fetchPrompt,
            gameID,
            game_round,
            player_id,
            fetchLatestRound,
            isImposter,
            hasResponded,
            allPlayers,
            allResponses,
            timeLeft, // This makes the countdown visible in the template.
        };
    },
};
</script>
