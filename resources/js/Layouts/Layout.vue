<template>
    <div class="container pt-[64px]">
        <header>
            <Navigation :isLogged="isLogged" :user="user"/>
        </header>
        <main>
            <slot/>
        </main>
        <Footer/>
        <Loader  v-if="store.getIsLock"/>
        <Snackbar />
    </div>
</template>

<script setup>
import Navigation from "@/Components/Navigation/Navigation.vue";
import {useMainStore} from "@/Store/mainStore.js";
import Loader from "@/Components/Universal/Loader.vue";
import Snackbar from "@/Components/Universal/Snackbar.vue";
import Footer from "@/Components/Footer/Footer.vue";
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
