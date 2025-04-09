<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome to the Game" />
    <div
        class="bg-[url('https://png.pngtree.com/background/20210717/original/pngtree-minimalist-dreamland-tennis-club-picture-image_1439360.jpg')] bg-cover text-black dark:text-white/50"
    >
        <!-- <img
            id="background"
            src="https://static.vecteezy.com/system/resources/previews/008/058/831/non_2x/a-simple-modern-abstract-design-a-beautiful-soft-off-white-element-twisting-and-flowing-on-a-beige-background-illustration-vector.jpg"
        /> -->
        <!-- More options for images  
         https://img.freepik.com/premium-photo/gaming-geometry-button-icon-floating-background-3d-illustration-rendering-abstract-wallpaper-gamer_42100-4243.jpg
         https://png.pngtree.com/thumb_back/fh260/background/20191029/pngtree-abstract-blue-background-with-white-dots-image_320887.jpg 
         https://wallpapers.com/images/hd/simple-background-nfe3kq9cr13oqs3l.jpg  
         https://i.pinimg.com/736x/ea/89/5f/ea895f26200ae65449b4d3a1b5851d20.jpg
         -->

        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-right gap-0 py-16 text-4xl font-bold"
                >
                    Welcome to Stop The Cap!
                </header>

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <a
                            href="/newlobby"
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-black dark:text-white"
                                >
                                    Create a Lobby
                                </h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Start a new lobby to play Stop The Cap with
                                    your friends. You can create a new lobby and
                                    invite your friends to join you.
                                </p>
                            </div>
                              <svg
                                class="size-6 shrink-0 self-center stroke-[#FF2D20]"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                />
                            </svg>
                        </a>

                        <a
                            href="/joinlobby"
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-black dark:text-white"
                                >
                                    Join a Lobby
                                </h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Join a lobby with a code to play Stop The
                                    Cap with your friends. You can join a lobby
                                    by entering the code provided by your
                                    friend.
                                </p>
                            </div>

                            <svg
                                class="size-6 shrink-0 self-center stroke-[#FF2D20]"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                />
                            </svg>
                        </a>
                        <a
                            href="/Help"
                            class="flex items-start rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-black dark:text-white"
                                >
                                    Help
                                </h2>
                                <p>Click here for the rules of the game</p>
                            </div>
                        </a>
                    </div>
                </main>

                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    Created by Maya Laidler and Kyle Abaya
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
export default {

setup() {
generateNewCode = () => {
        axios.post("/api/new-game"); // generate a new game code
        fetchPlayers(); // fetch players again to update the list
    }
}
};
    </script>