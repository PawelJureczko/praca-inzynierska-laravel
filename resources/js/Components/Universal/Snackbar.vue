<template>
    <div class="fixed left-[50%] -translate-x-[50%] px-4 py-2 rounded-lg transition-[top]"
         :class="[snackbar.snackbarType && wrapperStyles[snackbar.snackbarType], store.getSnackbar.isVisible ? 'top-[80px]' : '-top-[50px]']" @click="store.hideSnackbar">
        <p :class="['font-bold text-white']">{{snackbar.snackbarMessage}}</p>
    </div>
</template>

<script setup>
import {useMainStore} from "@/Store/mainStore.js";
import {computed} from "vue";

const store = useMainStore();

const snackbar = computed(() => {
    return store.getSnackbar;
})


const wrapperStyles = {
    error: 'bg-[#B21B00]',
    info: 'bg-[blue]',
    success: 'bg-[green]'
}

const textStyles = {
    error: 'text-white',
    success: 'text-white'
}


</script>

<style scoped>

</style>
