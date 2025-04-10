<template>
    <div class="justify-center bg-gradient-to-r from-pink-200 to-blue-100">
        <p class="text-center">
            This is the chat screen. You can send and receive messages here.
        </p>
        <p class="text-center">The game ID is: {{ currentGameId }}</p>
        <p class="text-center">The player is: {{ currentPlayerId }}</p>
        <p class="text-center text-2xl font-bold">
            Prompt: {{ prompt || 'Loading prompt...' }}
        </p>

        <div class="flex space-x-4">
            <div
                v-for="response in responses"
                :key="response.id"
                class="rounded-sm p-2"
            >
                {{ response.player?.name }} ->
                {{ response.raised_hand ? '✋' : '✊' }}
            </div>
        </div>

        <!-- 60 second timer -->
        <div
            class="fixed right-4 top-4 rounded-lg bg-red-500 px-4 py-2 text-white shadow-lg"
        >
            <p class="text-lg font-bold">Time Left: {{ timeLeft }}</p>
        </div>

        <div class="m-30 h-max bg-gradient-to-r from-pink-200 to-blue-100">
            <h2 class="text-3xl">Live Chat</h2>
            <div
                class="rounded-sm bg-gradient-to-r from-blue-50 to-pink-50"
                id="chat-box"
            >
                <div v-for="msg in messages" :key="msg.id" class="message">
                    <strong>{{ msg.player?.name || 'Unknown' }}:</strong>
                    {{ msg.content }}
                </div>
            </div>

            <input
                type="text"
                v-model="newMessage"
                placeholder="Type your message"
            />
            <button
                @click="sendMessage"
                class="text-l mt-6 rounded-lg bg-green-400 px-3 py-2 font-semibold text-white shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300"
            >
                Send
            </button>
        </div>
    </div>

</template>

<script>
import axios from 'axios';
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { onBeforeRouteLeave } from 'vue-router';

export default {

    setup() {
        const messages = ref([]);
        const newMessage = ref('');
        const lastMessageTime = ref('');
        const currentGameId = ref(null);
        const currentPlayerId = ref(null);
        const responses = ref([]);
        const prompt = ref('');
        const timeLeft = ref(60);
        let interval = null;

        const fetchGameId = async () => {
            const response = await axios.get('/api/latest-game');
            currentGameId.value = response.data.id;
            currentPlayerId.value = localStorage.getItem('player_id');
        };

        const fetchPrompt = async () => {
            try {
                const res = await axios.get(
                    `/api/rounds/${currentGameId.value}/latest-round`,
                );
                if (res.data.prompt && res.data.prompt.prompt_text) {
                    prompt.value = res.data.prompt.prompt_text;
                    console.log('Prompt on chatscreen:', prompt.value);
                } else {
                    prompt.value = 'Prompt not found';
                }
            } catch (error) {
                console.error('Error fetching prompt:', error);
            }
        };

        const fetchMessages = async () => {
            if (!currentGameId.value) return;
            const response = await axios.get(
                `/api/messages/${currentGameId.value}`,
            );
            messages.value = response.data;
            if (response.data.length > 0) {
                lastMessageTime.value =
                    response.data[response.data.length - 1].created_at;
            }
        };

        const fetchResponses = async () => {
            try {
                const response = await fetch(
                    `/api/responses/${currentGameId.value}`,
                );
                if (!response.ok)
                    throw new Error(`HTTP error! Status: ${response.status}`);
                const data = await response.json();
                responses.value = data ?? [];
            } catch (error) {
                console.error('Error fetching responses:', error);
                responses.value = [];
            }
        };

        const sendMessage = async () => {
            if (!newMessage.value.trim()) return;
            const response = await axios.post('/api/messages/send', {
                content: newMessage.value,
                game_id: currentGameId.value,

                player_id: currentPlayerId.value,
            });
            if (response.data.success) {
                newMessage.value = '';
                fetchMessages();
            }
        };

        const startCountdown = () => {
            const timer = setInterval(() => {
                if (timeLeft.value > 0) {
                    timeLeft.value--;
                } else {
                    clearInterval(timer);
                    router.visit('/votingscreen');
                }
            }, 1000);
        };

        onBeforeRouteLeave(() => {
            axios.delete(`/api/messages/cleanup/${currentGameId.value}`);
        });

        onMounted(async () => {
            await fetchGameId();
            await fetchPrompt();
            fetchMessages();
            fetchResponses();
            startCountdown();
            interval = setInterval(fetchMessages, 500);
        });

        onUnmounted(() => {
            clearInterval(interval);
            axios
                .delete(`/api/messages/cleanup/${currentGameId.value}`)
                .then(() => console.log('Messages cleaned up'))
                .catch((err) => console.error('Cleanup error:', err));
        });

        return {
            messages,
            newMessage,
            sendMessage,
            responses,
            timeLeft,
            currentPlayerId,
            currentGameId,
            prompt,
        };

    },
};
</script>

<style scoped>
#chat-box {
    width: 60%;
    margin: auto;
    border: 1px solid #ccc;
    padding: 10px;
    height: 600px;
    overflow-y: auto;
}
.message {
    padding: 5px;
    border-bottom: 1px solid #ddd;
}
</style>
