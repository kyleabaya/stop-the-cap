<template>
    <div
        class="flex h-screen w-full items-center justify-center bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 p-6"
    >
        <!-- Loading Spinner -->
        <div
            v-if="!isRevealed"
            class="flex h-screen items-center justify-center space-x-2 dark:invert"
        >
            <span class="text-5xl text-white"
                >Has the Capper been caught?...</span
            >
            <div
                class="h-8 w-8 animate-bounce rounded-full bg-black [animation-delay:-0.3s]"
            ></div>
            <div
                class="h-8 w-8 animate-bounce rounded-full bg-black [animation-delay:-0.15s]"
            ></div>
            <div class="h-8 w-8 animate-bounce rounded-full bg-black"></div>
        </div>

        <!-- Reveal Results -->
        <div
            v-else
            class="relative w-full max-w-4xl overflow-hidden rounded-xl bg-white p-6 shadow-xl"
        >
            <div class="bg-gray absolute inset-0 opacity-50"></div>
            <div
                class="relative z-10 flex flex-col items-center justify-center space-y-6 text-center text-black"
            >
                <h2 class="text-4xl font-extrabold md:text-6xl">
                    <span v-if="isImposterFound" class="text-amber-400"
                        >The Capper</span
                    >
                    <span v-else>The Capper Was Not Found! 👀</span>
                </h2>
                <p class="text-lg font-semibold">
                    <span v-if="isImposterFound">
                        Revealed! It was
                        <span class="font-bold">{{ imposter.name }}</span
                        >!
                    </span>
                    <span v-else class="text-3xl">
                        No one could identify the Capper...
                    </span>
                </p>
                <button
                    @click="checkIfPressed"
                    class="transform rounded-full bg-green-300 px-8 py-4 text-3xl font-semibold text-black transition-all duration-300 hover:scale-110 hover:bg-green-400"
                >
                    Try to find the imposter again
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

export default {
    setup() {
        const gameID = ref(localStorage.getItem('game_id'));
        const isRevealed = ref(false);
        const isImposterFound = ref(false);
        const imposter = ref(null);
        const alreadyPressed = ref(false); // this should be .value checked

        const revealImposter = async () => {
            try {
                const response = await axios.post(
                    `/api/game/${gameID.value}/reveal-imposter`,
                );
                isImposterFound.value = response.data.is_imposter_revealed;
                imposter.value = response.data.imposter;
            } catch (error) {
                console.error('Error revealing imposter:', error);
            } finally {
                isRevealed.value = true;
            }
        };

        const checkIfPressed = async () => {
            if (alreadyPressed.value) {
                // second person or reload
                router.visit('/promptscreen');
            } else {
                alreadyPressed.value = true;
                await goToNextRound();
            }
        };

        const goToNextRound = async () => {
            try {
                const res = await axios.get(
                    `/api/rounds/${gameID.value}/reset-or-continue-imposter`,
                );
                if (res.data.gameOver) {
                    router.visit('/finalresult');
                } else if (isImposterFound.value) {
                    router.visit('/roundtally');
                } else {
                    router.visit('/promptscreen');
                }
            } catch (error) {
                console.error('Error moving to next round:', error);
            }
        };

        onMounted(() => {
            setTimeout(revealImposter, 1000); // brief suspense delay
        });

        return {
            isRevealed,
            isImposterFound,
            imposter,
            checkIfPressed,
        };
    },
};
</script>
