<template>
    <Layout :user="$page.props.auth.user" :isLogged="$page.props.auth.user!==null"
            :userType="$page.props.auth.user.role">
        <TitleComponent desc="Dodaj nowe zajęcia" :isSearch="false"/>

        <div class="max-w-[428px] mx-auto flex flex-col gap-6">
            <Datepicker v-model="test" :enableTimePicker="false" :timePicker="true"/>

            <div>
                <p>Wybierz ucznia:</p>
                <Select :options="preparedOptions" placeholder="Wybór ucznia"
                        v-model="form.student_id"/>
            </div>

            <div>
                <p>Data rozpoczęcia zajęć:</p>
                <VueDatePicker
                    v-model="form.date_begin"
                    :min-date="new Date()"
                    :enable-time-picker="false"/>
            </div>

            <div>
                <p>Data zakończenia zajęć (opcjonalnie):</p>
                <VueDatePicker
                    v-model="form.date_end"
                    :min-date="form.date_begin"
                    :enable-time-picker="false"/>
            </div>

            <div>
                <p>Dzień zajęć:</p>
                <Select :options="weekdays" placeholder="Wybierz dzień zajęć"
                        v-model="form.class_weekday"/>
            </div>

            <div>
                <p>Godzina rozpoczęcia zajęć:</p>
                <VueDatePicker v-model="form.class_time_start" time-picker />
            </div>

            <div>
                <p>Godzina zakończenia zajęć:</p>
                <VueDatePicker v-model="form.class_time_end" time-picker />
            </div>

            <div class="flex items-center justify-between mt-6">
                <Btn class="w-[calc(50%_-_8px)]">Zapisz</Btn>
                <Btn class="w-[calc(50%_-_8px)]" btnType="secondary">Wróć</Btn>
            </div>
        </div>

    </Layout>
</template>

<script setup>

import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Layout from "@/Layouts/Layout.vue";
import Select from "@/Components/Inputs/Select.vue";
import {ref} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";
import Datepicker from "@/Components/Inputs/Datepicker.vue";

const props = defineProps({
    students: {
        type: Array,
        default() {
            return []
        }
    }
})

const test = ref();

const form = ref({
    student_id: null,
    date_begin: new Date(),
    date_end: null,
    class_weekday: null,
    class_time_start: null,
    class_time_end: null
})

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


</script>

<style scoped>

</style>
