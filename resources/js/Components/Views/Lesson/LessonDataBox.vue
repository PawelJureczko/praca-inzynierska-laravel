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
        <h2 class="text-[24px] heading-[32px] font-bold">Dane podstawowe:</h2>
        <p class="text-[18px] leading-[24px]">Zajęcia z: <b>
            {{ userType === 'teacher' ? (currentData.student_first_name + ' ' + currentData.student_last_name) : (currentData.teacher_first_name + ' ' + currentData.teacher_last_name) }}</b></p>
        <p class="text-[18px] leading-[24px]">Termin zajęć: <b>
            {{ getStringFromDate(new Date(scheduleData ? lessonDate : lessonData.date)).split(', ')[0] }}</b></p>
        <p  class="text-[18px] leading-[24px]">Godziny zajęć: <b>{{ currentData.classes_time_start.split(':').slice(0, 2).join(':') }} - {{ currentData.classes_time_end.split(':').slice(0, 2).join(':') }}</b></p>


        <div v-if="currentData.date_end && currentData.resigned_by">
            <p class="text-[18px] leading-[24px]">Zajęcia przewidziane do: <b>{{currentData.date_end.split('-').reverse().join('.')}}</b></p>
            <p class="text-[18px] leading-[24px]">Rezygnacja z zajęć zainicjowana przez: <b>{{currentData.resigned_by === 'teacher' ? (currentData.student_first_name + ' ' + currentData.student_last_name) : (currentData.teacher_first_name + ' ' + currentData.teacher_last_name)}}</b></p>
            <p class="text-[18px] leading-[24px]" v-if="currentData.resignation_reason">Powód rezygnacji z zajęć: <b>{{currentData.resignation_reason}}</b></p>
        </div>
    </div>
</template>

<style scoped>

</style>
