<script setup>
import {calculateTopDistance, singleTileHeight} from "@/Helpers/helpers.js";
import {computed} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    date: {
        type: String,
        default: null
    },
    lesson: {
        type: Object,
        default() {
            return {}
        }
    },
    timeFrom: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: ''
    }
})

const userRole = (router.page.props.auth.user.role);

function handleScheduleClick() {
    if (props.type === 'schedule') {
        router.visit(route('lesson.create', {id: props.lesson.id, date: props.date}))
    } else {
        router.visit(route('lesson.edit', {id: props.lesson.id}))
    }
}

const preparedBg = computed(() => {
    if (props.type === 'schedule') {
        return 'bg-schedule-incoming'
    }
    if (props.type === 'lesson') {
        if (props.lesson.canceled_by_student === 1) {
            return 'bg-schedule-student_absence'
        } else if (props.lesson.canceled_by_teacher === 1) {
            return 'bg-schedule-teacher_absence'
        } else {
            return 'bg-schedule-completed'
        }
    }

})


</script>

<template>
    <div class="absolute left-2 w-full max-w-[calc(100%_-_16px)] p-4 flex items-center justify-center cursor-pointer"
         @click="handleScheduleClick"
         :class="preparedBg"
         :style="'top:'+ calculateTopDistance(props.lesson.classes_time_start, props.timeFrom) + 'px; height:'+ singleTileHeight(props.lesson.classes_time_start, props.lesson.classes_time_end)+'px'">
            <p class="font-[10px] leading-[14px] font-bold" :class="preparedBg === 'bg-schedule-student_absence' && 'text-white'">{{ userRole==='teacher' ? props.lesson.student_first_name.charAt(0) : props.lesson.teacher_first_name.charAt(0) }}.
                {{ userRole==='teacher' ? props.lesson.student_last_name : props.lesson.teacher_last_name}}</p>
    </div>
</template>

<style scoped>

</style>
