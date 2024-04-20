<template>
    <Layout :user="$page.props.auth.user" :isLogged="$page.props.auth.user!==null"
            :userType="$page.props.auth.user.role">
        <TitleComponent desc="Dodaj nowe zajęcia" :isSearch="false"/>

        <div class="max-w-[428px] mx-auto flex flex-col gap-6">
            <div>
                <p>Wybierz ucznia:</p>
                <Select :options="preparedOptions" placeholder="Wybór ucznia"
                        v-model="form.student_id" errorName="student_id"/>
            </div>

            <div>
                <p>Data rozpoczęcia zajęć:</p>
                <Datepicker v-model="form.date_begin" :enableTimePicker="false" :minDate="minDate" errorName="date_begin" />
            </div>

            <div>
                <p>Data zakończenia zajęć (opcjonalnie):</p>
                <Datepicker v-model="form.date_end" :enableTimePicker="false" :minDate="minEndDateCp" errorName="date_end" />

            </div>

            <div>
                <p>Dzień zajęć:</p>
                <Select :options="weekdays" placeholder="Wybierz dzień zajęć"
                        v-model="form.class_weekday" errorName="class_weekday"/>
            </div>

            <div>
                <p>Godzina rozpoczęcia zajęć:</p>
                <Datepicker v-model="form.class_time_start" :timePicker="true" errorName="class_time_start"/>
            </div>

            <div>
                <p>Godzina zakończenia zajęć:</p>
                <Datepicker v-model="form.class_time_end" :timePicker="true" errorName="class_time_end"/>
            </div>

            <div class="flex items-center justify-between mt-6">
                <Btn class="w-[calc(50%_-_8px)]" @click="save">Zapisz</Btn>
                <Btn class="w-[calc(50%_-_8px)]" btnType="secondary" @click="$inertia.visit(route('schedule.index'))">Wróć</Btn>
            </div>
        </div>

    </Layout>
</template>

<script setup>

import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Layout from "@/Layouts/Layout.vue";
import Select from "@/Components/Inputs/Select.vue";
import {computed, ref, watch} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";
import Datepicker from "@/Components/Inputs/Datepicker.vue";
import {addLeadingZero, getDateFromString, getStringFromDate, scrollToError, prepareDateForSend} from "@/Helpers/helpers.js";
import {useMainStore} from "@/Store/mainStore.js";
import {router} from "@inertiajs/vue3";
const store = useMainStore();

const props = defineProps({
    students: {
        type: Array,
        default() {
            return []
        }
    }
})

const form = ref({
    student_id: null,
    date_begin: new Date(),
    date_end: null,
    class_weekday: null,
    class_time_start: null,
    class_time_end: null
})

const dateBeginCp = computed(() => {
    return form.value.date_begin;
})

watch(dateBeginCp, () => {
    if (form.value.date_end && (form.value.date_begin > form.value.date_end)) {
        const date = getDateFromString(getStringFromDate(form.value.date_begin));
        date.setDate(date.getDate() + 1);
        form.value.date_end = date;
    }
})

const minEndDateCp = computed(() => {
    const preparedEndDate = new Date(form.value.date_begin);
    preparedEndDate.setDate(preparedEndDate.getDate() + 7);
    return preparedEndDate;
})

const minDate = new Date();

const weekdays = ref([
    {
        key: 1,
        value: 'Poniedziałek'
    },
    {
        key: 2,
        value: 'Wtorek'
    },
    {
        key: 3,
        value: 'Środa'
    },
    {
        key: 4,
        value: 'Czwartek'
    },
    {
        key: 5,
        value: 'Piątek'
    },
    {
        key: 6,
        value: 'Sobota'
    },
    {
        key: 7,
        value: 'Niedziela'
    }
])

const preparedOptions = ref(props.students.map(item => {
    return {
        key: item.id,
        value: item.first_name + ' ' + item.last_name
    }
}));

function save() {
    const preparedForm = {
        student_id: form.value.student_id ? parseInt(form.value.student_id) : null,
        date_begin: prepareDateForSend(form.value.date_begin, 'from', form.value.class_weekday ? parseInt(form.value.class_weekday) : null),
        date_end: form.value.date_end ? prepareDateForSend(form.value.date_end, 'to', form.value.class_weekday ? parseInt(form.value.class_weekday) : null) : null,
        class_weekday: form.value.class_weekday ? parseInt(form.value.class_weekday) : null,
        class_time_start: form.value.class_time_start ? (addLeadingZero(form.value.class_time_start.hours) + ':' + addLeadingZero(form.value.class_time_start.minutes)) + ':' + '00' : null,
        class_time_end: form.value.class_time_end ? (addLeadingZero(form.value.class_time_end.hours) + ':' + addLeadingZero(form.value.class_time_end.minutes)) + ':' + '00' : null,
    }

    if (store.getIsLock === false) {
        store.setIsLock(true);
        store.clearErrors();
        axios.post(route('schedule.save'), preparedForm)
            .then(response => {
                console.log(response);
                if (response.data.status === 'ok') {
                    store.showSnackbar('Zajęcia dodane pomyślnie!', 'success');
                    router.visit(route('schedule.index'));
                }
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response.data.errors)
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                    if (error.response.data.errors.general) {
                        store.showSnackbar(error.response.data.errors.general, 'error', 10000);
                    }
                    scrollToError();
                } else {
                    console.log(error.data)
                    store.showSnackbar('Wystąpił niespodziewany błąd, spróbuj ponownie później.', 'error');
                }
            }).finally(() => {
            store.setIsLock(false);
        })}
}


</script>

<style scoped>

</style>
