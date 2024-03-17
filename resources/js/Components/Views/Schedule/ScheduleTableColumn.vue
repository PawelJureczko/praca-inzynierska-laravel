<template>
    <div class="w-[12.5%] min-w-[200px]">
        <SingleScheduleCeil class="bg-[#ffcda3]">
            <div>
                <p class="font-bold">{{ day }}</p>
                <p>{{date}}</p>
            </div>
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
    },
    date: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>

</style>
