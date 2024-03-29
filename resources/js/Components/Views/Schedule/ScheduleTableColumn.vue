<template>
    <div class="w-[12.5%] min-w-[200px] relative">
        <ScheduleSingleTile v-for="schedule in schedules" :lesson="schedule" :timeFrom="timeFrom" type="schedule"/>
        <ScheduleSingleTile v-for="lesson in lessons" :lesson="lesson" :timeFrom="timeFrom" type="lesson"/>
        <SingleScheduleCeil class="bg-[#ffcda3]">
            <div>
                <p class="font-bold">{{ day }}</p>
                <p>{{ date }}</p>
            </div>
        </SingleScheduleCeil>
        <SingleScheduleCeil v-for="i in columnLength">

        </SingleScheduleCeil>
    </div>
</template>

<script setup>
import SingleScheduleCeil from "@/Components/Views/Schedule/SingleScheduleCeil.vue";
import ScheduleSingleTile from "@/Components/Views/Schedule/ScheduleSingleTile.vue";

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
    },
    lessons: {
        type: Array,
        default() {
            return []
        }
    },
    schedules: {
        type: Array,
        default() {
            return []
        }
    },
    timeFrom: {
        type: String,
        default: ''
    }
})

</script>

<style scoped>

</style>
