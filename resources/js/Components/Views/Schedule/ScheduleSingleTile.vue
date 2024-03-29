<script setup>
import {calculateTopDistance, singleTileHeight} from "@/Helpers/helpers.js";
import {computed} from "vue";

const props = defineProps({
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
    <div class="absolute left-2 w-full max-w-[calc(100%_-_16px)] p-4 flex items-center justify-center"
         :class="preparedBg"
         :style="'top:'+ calculateTopDistance(props.lesson.classes_time_start, props.timeFrom) + 'px; height:'+ singleTileHeight(props.lesson.classes_time_start, props.lesson.classes_time_end)+'px'">
            <p class="font-[10px] leading-[14px] font-bold">{{ props.lesson.student_first_name.charAt(0) }}.
                {{ props.lesson.student_last_name }}</p>
    </div>
</template>

<style scoped>

</style>
