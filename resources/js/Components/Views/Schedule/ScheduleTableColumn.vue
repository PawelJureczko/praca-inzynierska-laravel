<template>
    <div class="w-[12.5%] min-w-[200px]">
        <SingleScheduleCeil class="bg-[#ffcda3]">
            <p class="font-bold">{{ day }}</p>
        </SingleScheduleCeil>
        <SingleScheduleCeil v-for="i in columnLength">

        </SingleScheduleCeil>
    </div>
</template>

<script setup>
import SingleScheduleCeil from "@/Components/Views/Schedule/SingleScheduleCeil.vue";

const props = defineProps({
    day: {
        type: String,
        default: ''
    },
    columnLength: {
        type: Number,
        default: 0
    }
})
</script>

<style scoped>

</style>
