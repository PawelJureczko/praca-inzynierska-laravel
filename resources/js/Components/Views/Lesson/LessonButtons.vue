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
    }
})
</script>

<template>
    <div class="max-w-[450px] mx-auto flex gap-[20px] mt-10">
        <Btn @click="$emit('save')" v-if="type==='new'">Zapisz</Btn>
        <Btn @click="$emit('update')" v-if="type==='edit' && (!lesson.canceled_by_teacher && !lesson.canceled_by_student)">Aktualizuj</Btn>
        <Btn btnType="secondary" @click="$inertia.visit(route('schedule.index'))">Wróć</Btn>
    </div>
</template>

<style scoped>

</style>
