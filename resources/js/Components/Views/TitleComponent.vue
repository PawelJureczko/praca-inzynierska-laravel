<template>
    <div class="my-4 xl:my-8 flex flex-col md:flex-row justify-between items-center">
        <h2 class="text-[32px] heading-[40px] xl:text-[48px] xl:leading-[56px] font-bold">{{desc}}</h2>
        <TextField class="w-full md:max-w-[328px]" v-model="searchValue" isSearchField placeholder="Szukaj..."/>
    </div>
</template>

<script setup>
import TextField from "@/Components/Inputs/TextField.vue";
import {ref} from "vue";

const props = defineProps({
    desc: {
        type: String,
        default: ''
    }
})

const searchValue = defineModel();
</script>

<style scoped>

</style>
