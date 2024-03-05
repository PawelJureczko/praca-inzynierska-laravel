<template>
    <div class="container">
        <header>
            <Navigation :isLogged="isLogged" :user="user"/>
        </header>
        <main>
            <slot/>
        </main>
        <footer>

        </footer>
    </div>
</template>

<script setup>

import Navigation from "@/Navigation/Navigation.vue";
const props = defineProps({
    isLogged: {
        type: Boolean,
        default: true,
    },
    userType: {
        type: String,
        default: null
    },
    user: {
        type: Object,
        default: null
    }
})
</script>

<style scoped>

</style>
