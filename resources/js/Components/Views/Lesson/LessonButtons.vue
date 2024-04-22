<script setup>
import Btn from "@/Components/Buttons/Btn.vue";

const props = defineProps({
    lesson: {
        type: Object,
        default() {
            return {

            }
        }
    },
    type: {
        type: String,
        default: ''
    },
    userType: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <div class="max-w-[450px] mx-auto flex gap-[20px] mt-10">
        <template v-if="userType === 'teacher'">
            <Btn @click="$emit('save')" v-if="type==='new'">Zapisz</Btn>
            <Btn @click="$emit('update')" v-if="type==='edit' && (!lesson.canceled_by_teacher && !lesson.canceled_by_student)">Aktualizuj</Btn>
        </template>
        <Btn btnType="secondary" @click="$inertia.visit(route('schedule.index'))">Wróć do terminarza</Btn>
    </div>
</template>

<style scoped>

</style>
