<template>
    <div class="flex">
        <div>
            <div class="w-[12.5%] min-w-[100px]">
                <SingleScheduleCeil v-for="time in timeInterval" class="bg-[#ffcda3]">
                    <p class="font-bold">{{ time }}</p>
                </SingleScheduleCeil>
            </div>
        </div>
        <div class="flex max-w-full overflow-auto">
            <ScheduleTableColumn v-for="day in days" :day="day" :columnLength="timeInterval.length-1"/>
        </div>
    </div>
</template>

<script setup>
import SingleScheduleCeil from "@/Components/Views/Schedule/SingleScheduleCeil.vue";
import ScheduleTableColumn from "@/Components/Views/Schedule/ScheduleTableColumn.vue";

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
</script>

<style scoped>

</style>
