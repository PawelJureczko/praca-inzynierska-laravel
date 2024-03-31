<template>
    <div>
        <div class="flex justify-between items-center flex-wrap gap-2">
            <ScheduleDatepicker class="flex-shrink-0" :dates="dates" @iteratorClicked="handleIteratorClicked"
                                @dateChanged="handleDateChanged"/>
            <Btn class="w-max" @click="$inertia.visit(route('schedule.create'))" v-if="userType==='teacher'">Dodaj
                zajęcia
            </Btn>
        </div>
        <ScheduleLegend class="mt-4"/>
        <div class="flex mt-4">
            <div>
                <div class="w-[12.5%] min-w-[100px]">
                    <SingleScheduleCeil v-for="time in timeInterval" class="bg-[#ffcda3]">
                        <p class="font-bold">{{ time }}</p>
                    </SingleScheduleCeil>
                </div>
            </div>
            <div class="flex max-w-full overflow-x-auto overflow-y-hidden">
                <ScheduleTableColumn v-for="(day, index) in days" :date="dates[index]" :day="day"
                     :columnLength="timeInterval.length-1"
                     :timeFrom="timeInterval[1]"
                     class="last-of-type:border-r last-of-type:border-r-main_hover"
                     :class="[currentWeekDay-1 === index && weekIterator===0 && 'bg-[#f7dcc6]']"
                     :lessons="(filledLessons && filledLessons.length>0) ? (filledLessons.filter(item => item.date.split('-').reverse().join('.') === dates[index])) :  null"
                        :schedules="schedules ? schedules[index+1] : []"
                    />
            </div>
        </div>
    </div>
</template>

<script setup>
import SingleScheduleCeil from "@/Components/Views/Schedule/SingleScheduleCeil.vue";
import ScheduleTableColumn from "@/Components/Views/Schedule/ScheduleTableColumn.vue";
import {onBeforeMount, ref} from "vue";
import {getStringFromDate, prepareDateForRequest} from "@/Helpers/helpers";
import ScheduleDatepicker from "@/Components/Views/Schedule/ScheduleDatepicker.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";
import ScheduleLegend from "@/Components/Views/Schedule/ScheduleLegend.vue";

const props = defineProps({
    userType: {
        type: String,
        default: ''
    }
})

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

const store = useMainStore();
const chosenDay = ref(new Date());

const dates = ref([]);
const dateFrom = ref(null);
const dateTo = ref(null);

const currentWeekDay = ref(10);
const rerenderKey = ref(0);

const weekIterator = ref(0); //0 oznacza obecny tydzien, wartość dodatnia to wartość x 7 dni do przodu, ujemna analogicznie wartość x 7 do tyłu

const schedules = ref(null);
const filledLessons = ref(null);

function getSchedules() {
    // schedule.getData
    if (store.getIsLock === false) {
        store.setIsLock(true);
        axios.get(route('schedule.getData'), {
            params: {
                dateFrom: dateFrom.value,
                dateTo: dateTo.value
            }
        })
            .then(response => {
                const tempSchedules = response.data.schedule.schedules && Object.values(response.data.schedule.schedules);
                filledLessons.value = response.data.schedule.lessons && Object.values(response.data.schedule.lessons);
                schedules.value = prepareSchedules(tempSchedules);
                rerenderKey.value = rerenderKey.value + 1;

                store.showSnackbar('Dane załadowane pomyślnie', 'success');
            })
            .catch(error => {
                store.showSnackbar('Wystąpił niespodziewany błąd, odśwież stronę', 'error');
            }).finally(() => {
            store.setIsLock(false);
        });
    }
}

function prepareSchedules(schedules) {
    let res = {
        1: [],
        2: [],
        3: [],
        4: [],
        5: [],
        6: [],
        7: [],
    }
    schedules.forEach(item => {
        // console.log(res[item.classes_weekday])
        res[item.classes_weekday] = [...res[item.classes_weekday], item]
    })
    return res;
}

function handleDateChanged(date) {
    chosenDay.value = date.value;
    prepareDates(0, date.value);
    weekIterator.value = 0;
}

function prepareDates(iterator) {
    currentWeekDay.value = chosenDay.value.getDay() === 0 ? 7 : chosenDay.value.getDay();
    dates.value = [];

    for (let i = 1; i < 8; i++) {
        let iteratedDate = new Date(chosenDay.value);
        iteratedDate.setDate(chosenDay.value.getDate() + (i - currentWeekDay.value) + (7 * iterator));
        dates.value.push(getStringFromDate(iteratedDate).split(',')[0])
    }

    dateFrom.value = prepareDateForRequest(dates.value[0]);
    dateTo.value = prepareDateForRequest(dates.value[dates.value.length - 1]);

    getSchedules();
}

function handleIteratorClicked(type) {
    if (type === 'dec') {
        weekIterator.value = weekIterator.value - 1;
    }

    if (type === 'inc') {
        weekIterator.value = weekIterator.value + 1;
    }

    prepareDates(weekIterator.value)
}

onBeforeMount(() => {
    prepareDates(weekIterator.value);
})

</script>

<style scoped>

</style>
