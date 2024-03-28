<template>
    <div class="w-[12.5%] min-w-[200px] relative">
        <SingleScheduleCeil class="bg-[#ffcda3]">
            <div>
                <p class="font-bold">{{ day }}</p>
                <p>{{date}}</p>
            </div>
        </SingleScheduleCeil>
        <SingleScheduleCeil v-for="i in columnLength">

        </SingleScheduleCeil>

        <div v-for="lesson in lessons" class="absolute left-2 bg-[yellow] max-w-[calc(100%_-_16px)]l p-4" :style="'top: 48px'">
            <p>{{lesson.classes_time_start}} - {{lesson.classes_time_end}}</p>
            <p>{{lesson.student_first_name}}</p>
            <p>{{lesson.student_last_name}}</p>
            <p>{{lesson.topic}}</p>
            <p>{{lesson.notes}}</p>
        </div>

        <div v-for="(schedule, index) in schedules" class="absolute left-2 bg-[red] max-w-[calc(100%_-_16px)]l p-4" :style="'top:'+48*((index+1)*10) + 'px'">
            <p>{{schedule}}</p>
        </div>
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
    }
})
</script>

<style scoped>

</style>
