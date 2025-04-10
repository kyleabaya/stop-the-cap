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
            <h1 class="mb-4 text-5xl font-bold text-gray-800">Prompt:</h1>
            <div class="text-4xl font-bold">
                {{ prompt || 'Loading prompt...' }}
            </div>
        </div>

        <div v-if="!hasResponded">
            <div class="flex space-x-4 pt-10">
                <div
                    class="relative mb-5 pr-8 transition-transform hover:scale-[1.25] lg:w-[130px]"
                >
                    <img
                        src="https://static.vecteezy.com/system/resources/previews/007/804/318/non_2x/close-icon-delete-logo-illustration-isolated-on-white-background-editable-stroke-vector.jpg"
                        alt="no"
                        class="lg:w-[130px]"
                    />
                    <button
                        class="absolute left-0 top-0 h-full w-full"
                        @click="submitFalse"
                    ></button>
                    <p>click the x for no</p>
                </div>

                <div
                    class="relative mb-5 pl-8 transition-transform hover:scale-[1.25] lg:w-[130px]"
                >
                    <img
                        src="https://i.pinimg.com/474x/6b/42/28/6b4228903e0489c5dfcfdf5cc7196e33.jpg"
                        alt="hand"
                        class="lg:w-[130px]"
                    />
                    <button
                        class="absolute left-0 top-0 h-full w-full"
                        @click="submitTrue"
                    ></button>
                    <p>click the hand for yes</p>
                </div>
            </div>
        </div>

        <div v-else>
            <h2 class="mb-4 text-3xl font-bold text-gray-800">
                You have already responded!
            </h2>
            <p class="text-xl">Wait for the other players to respond.</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
    setup() {
        const prompt = ref('');
        const promptResponse = ref(false);
        const gameID = ref(localStorage.getItem('game_id'));
        const player_id = ref(localStorage.getItem('player_id'));
        const game_round = ref({});
        const isImposter = ref(false);
        const hasResponded = ref(false);
        const timeLeft = ref(12);
        let transitioned = false;

        const fetchRoundAndPrompt = async () => {
            try {
                const res = await axios.get(
                    `/api/rounds/${gameID.value}/latest-round`,
                );
                console.log('Full round response:', res.data);

                game_round.value = res.data;

                if (res.data.prompt && res.data.prompt.prompt_text) {
                    prompt.value = res.data.prompt.prompt_text;
                    console.log('Prompt fetched:', prompt.value);
                } else {
                    prompt.value = 'Prompt not found';
                    console.warn('Prompt missing from round:', res.data);
                }
            } catch (error) {
                console.error('Error fetching round/prompt:', error);
            }
        };

        const checkIfPlayerIsImposter = async () => {
            try {
                const res = await axios.get(
                    `/api/getPlayer/${player_id.value}`,
                );
                isImposter.value = res.data.player.is_imposter;
                console.log('Is Imposter:', isImposter.value);
            } catch (error) {
                console.error('Error checking imposter:', error);
            }
        };

        const submitTrue = async () => {
            try {
                fetchRoundAndPrompt();
                const res = await axios.post(
                    `/api/responses/${game_round.value.id}/store`,
                    {
                        player_id: player_id.value,
                        raised_hand: true,
                    },
                );
                hasResponded.value = true;
                console.log('Response submitted (YES):', res.data);
            } catch (error) {
                console.error('Error submitting true response:', error);
            }
        };

        const submitFalse = async () => {
            try {
                fetchRoundAndPrompt();
                const res = await axios.post(
                    `/api/responses/${game_round.value.id}/store`,
                    {
                        player_id: player_id.value,
                        raised_hand: false,
                    },
                );
                hasResponded.value = true;
                console.log('Response submitted (NO):', res.data);
            } catch (error) {
                console.error('Error submitting false response:', error);
            }
        };

        const checkIfAllResponded = async () => {
            try {
                const res = await axios.get(
                    `/api/games/${gameID.value}/rounds/${game_round.value.id}/has-everyone-responded`,
                );
                if (res.data.all_responded && !transitioned) {
                    transitioned = true;
                    router.visit('/chatscreen');
                } else {
                    console.log('Waiting for more responses...');
                }
            } catch (error) {
                console.error('Failed to check if all responded:', error);
            }
        };

        let pollInterval;

        onMounted(() => {
            fetchRoundAndPrompt();
            checkIfPlayerIsImposter();
            checkIfAllResponded();
            //startCountdown();
            pollInterval = setInterval(() => checkIfAllResponded(), 5000);
        });

        onUnmounted(() => {
            clearInterval(pollInterval);
        });

        return {
            prompt,
            submitTrue,
            submitFalse,
            isImposter,
            hasResponded,
            timeLeft,
        };
    },
};
</script>
