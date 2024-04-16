<script setup>
import {getStringFromDate} from "@/Helpers/helpers.js";

const props = defineProps({
    currentData: {
        type: Object,
        default() {
            return {

            }
        }
    },
    lessonData: {
        type: Object,
        default() {
            return {

            }
        }
    },
    lessonDate: {
        type: String,
        default: ''
    },
    scheduleData: {
        type: Object,
        default() {
            return {

            }
        }
    },
    userType: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <div class="mt-6 flex flex-col gap-2">
        <p class="text-[22px] leading-[26px]">Zajęcia z: <b>
            {{ userType === 'teacher' ? (currentData.student_first_name + ' ' + currentData.student_last_name) : (currentData.teacher_first_name + ' ' + currentData.teacher_last_name) }}</b></p>
        <p class="text-[22px] leading-[26px]">Termin zajęć: <b>
            {{ getStringFromDate(new Date(scheduleData ? lessonDate : lessonData.date)).split(', ')[0] }}</b></p>
        <p  class="text-[22px] leading-[26px]">Godziny zajęć: <b>{{ currentData.classes_time_start.split(':').slice(0, 2).join(':') }} - {{ currentData.classes_time_end.split(':').slice(0, 2).join(':') }}</b></p>
    </div>
</template>

<style scoped>

</style>
