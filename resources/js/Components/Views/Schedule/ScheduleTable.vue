<template>
    <div>
        <div>
            <p @click="handleIteratorClicked('dec')">Wstecz</p>
            <p>{{dates[0]}} - {{dates[6]}}</p>
            <p @click="handleIteratorClicked('inc')">Dalej</p>
        </div>
        <div class="flex">
            <div>
                <div class="w-[12.5%] min-w-[100px]">
                    <SingleScheduleCeil v-for="time in timeInterval" class="bg-[#ffcda3]">
                        <p class="font-bold">{{ time }}</p>
                    </SingleScheduleCeil>
                </div>
            </div>
            <div class="flex max-w-full overflow-auto">
                <ScheduleTableColumn v-for="(day, index) in days" :date="dates[index]" :day="day" :columnLength="timeInterval.length-1" class="last-of-type:border-r last-of-type:border-r-main_hover" :class="[currentWeekDay-1 === index && weekIterator===0 && 'bg-[#f7dcc6]']"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import SingleScheduleCeil from "@/Components/Views/Schedule/SingleScheduleCeil.vue";
import ScheduleTableColumn from "@/Components/Views/Schedule/ScheduleTableColumn.vue";
import { onBeforeMount, ref} from "vue";
import {getStringFromDate} from "@/Helpers/helpers";

const timeInterval = [
    '', '8:00', '8:30', '9:00', '9:30', '10:00', '10:30',
    '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
    '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
    '17:00', '17:30', '18:00', '18:30', '19:00', '19:30',
    '20:00', '20:30', '21:00', '21:30', '22:00', '22:30',
    '23:00'
];
const days = [
    'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'
];

const dates = ref([]);

const currentWeekDay = ref(10);

const weekIterator = ref(0); //0 oznacza obecny tydzien, wartość dodatnia to wartość x 7 dni do przodu, ujemna analogicznie wartość x 7 do tyłu

function prepareDates(iterator) {
    let currentDay = new Date();
    currentWeekDay.value = currentDay.getDay() === 0 ? 7 : currentDay.getDay();
    dates.value = [];

    for (let i = 1; i<8; i++) {
        let today = new Date();
        let iteratedDate = new Date(today);
        iteratedDate.setDate(today.getDate() + (i - currentWeekDay.value) + (7*iterator));
        dates.value.push(getStringFromDate(iteratedDate).split(',')[0])
    }
    console.log('asd')
}

function handleIteratorClicked(type) {
    if (type === 'dec') {
        weekIterator.value = weekIterator.value-1;
    }

    if (type === 'inc') {
        weekIterator.value = weekIterator.value+1;
    }

    prepareDates(weekIterator.value)
}

onBeforeMount(() => {
    prepareDates(weekIterator.value);
})

</script>

<style scoped>

</style>
