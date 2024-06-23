<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import {getStringFromDate} from "../../Helpers/helpers.js";
import {onBeforeMount, ref} from "vue";

const props = defineProps({
    dateFrom: {
        type: Date,
        default: null
    },
    dateTo: {
        type: Date,
        default: null
    },
    role: {
        type: String,
        default: ''
    },
    person: {
        type: String,
        default: ''
    },
    data: {
        type: Array,
        default() {
            return []
        }
    }
})

const preparedPresenceData = ref({
    lessonAmount: props.data.length,
    lessonTaken: props.data.filter(item => item.canceled_by_student === 0 && item.canceled_by_teacher === 0).length,
    lessonMissedByTeacher: props.data.filter(item => item.canceled_by_teacher === 1).length,
    lessonMissedByStudent: props.data.filter(item => item.canceled_by_student === 1).length
})

const preparedGradesData = ref({
    gradesAmount: -1,
    arithmeticMean: -1
})

function prepareGrades() {
    let grades = [];
    props.data.forEach(item => {
        if (item.grades.length) {
            grades.push(...item.grades.map(grade => grade.grade))
        }
    })

    preparedGradesData.value.gradesAmount = grades.length;

    let sum = grades.reduce((accumulator, currentValue) => {
        let val = parseInt(currentValue);
        if (currentValue.includes('+')) {
            val = parseInt(currentValue) + 0.5;
        } else if (currentValue.includes('-')) {
            val = parseInt(currentValue) - 0.25
        }
        return accumulator + val
    }, 0);

    preparedGradesData.value.arithmeticMean = (Math.floor(100*(sum/grades.length)))/100;
}

onBeforeMount(() => {
    prepareGrades();
})

function showProperResult(val) {
    if (isNaN(val)) {
        return '-'
    } else {
        return val;
    }
}


</script>

<template>
<ModalWrapper @close="$emit('close')">
    <div class="w-screen max-w-[calc(100%_-_32px)] mx-auto md:max-w-[420px]">
        <p>Wygenerowane statystyki</p>
        <p>Zakres: <span class="font-bold">{{getStringFromDate(dateFrom).split(', ')[0]}} - {{getStringFromDate(dateTo).split(', ')[0]}}</span></p>
        <p>{{role === 'teacher' ? 'Uczeń' : 'Nauczyciel'}}: <span class="font-bold">{{person}}</span></p>

        <p class="mt-4 text-[18px] leading-[22px] font-bold">Frekwencja:</p>
        <p>Łączna liczba lekcji:<span class="font-bold"> {{preparedPresenceData.lessonAmount}}</span></p>
        <p>Liczba lekcji odbytych: <span class="font-bold text-[green]">{{showProperResult(preparedPresenceData.lessonTaken)}}</span> ({{showProperResult(Math.floor((preparedPresenceData.lessonTaken/preparedPresenceData.lessonAmount)*100))}}%)</p>
        <p>Liczba nieobecności z winy ucznia: <span class="font-bold text-[red]">{{preparedPresenceData.lessonMissedByStudent}}</span></p>
        <p>Liczba nieobecności z winy nauczyciela: <span class="font-bold text-[red]">{{preparedPresenceData.lessonMissedByTeacher}}</span></p>


        <p class="mt-4 text-[18px] leading-[22px] font-bold">Oceny:</p>
        <p>Łączna liczba wystawionych ocen: <span class="font-bold">{{preparedGradesData.gradesAmount}}</span></p>
        <p>Średnia arytmetyczna z wystawionych ocen: <span class="font-bold">{{showProperResult(preparedGradesData.arithmeticMean)}}</span></p>

    </div>
</ModalWrapper>
</template>

<style scoped>

</style>
