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
        <Loader  v-if="store.getIsLock"/>
        <Snackbar />
    </div>
</template>

<script setup>
import Navigation from "@/Navigation/Navigation.vue";
import {useMainStore} from "@/Store/mainStore.js";
import Loader from "@/Components/Universal/Loader.vue";
import Snackbar from "@/Components/Universal/Snackbar.vue";
const store = useMainStore();

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
