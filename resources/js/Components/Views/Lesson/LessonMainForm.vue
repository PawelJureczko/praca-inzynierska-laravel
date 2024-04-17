<script setup>
import TextField from "@/Components/Inputs/TextField.vue";
import TextArea from "@/Components/Inputs/TextArea.vue";

const lesson = defineModel();

</script>

<template>
    <div v-if="!lesson.canceled_by_teacher && !lesson.canceled_by_student" class="mt-8">
        <div class="mb-4">
            <p class="text-[18px] leading-[24px] font-bold">Temat zajęć:</p>
            <TextField class="mt-2" v-model="lesson.topic" placeholder="Wpisz temat zajęć..." errorName="topic"/>
        </div>

        <div class="mb-4">
            <p class="text-[18px] leading-[24px] font-bold">Opis zajęć:</p>
            <TextArea class="mt-2" v-model="lesson.notes" placeholder="Wpisz notatkę do zajęć..." errorName="notes"/>
        </div>
    </div>
</template>

<style scoped>

</style>
