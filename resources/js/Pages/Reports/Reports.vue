<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <TitleComponent desc="Raporty i statystyki" :isSearch="false"/>
        <div class="flex flex-col gap-4 w-full max-w-[calc(100%_-_32px)] mx-auto md:max-w-[672px] mx-auto">
            <div>
                <p>Wybierz {{role === 'teacher' ? 'ucznia' : 'nauczyciela'}}:</p>
                <Select :options="preparedConnectedUsers" v-model="form.person" :placeholder="role === 'teacher' ? 'Nie wybrano ucznia' : 'Nie wybrano nauczyciela'" errorName="chosenUserId"/>
            </div>
            <div>
                <p>Wybierz datę początkową</p>
                <Datepicker v-model="form.dateFrom" :minDate="null" errorName="dateFrom"/>
            </div>
            <div>
                <p>Wybierz datę końcową</p>
                <Datepicker v-model="form.dateTo" :minDate="null" errorName="dateTo"/>
            </div>
            <Btn @click="getData">Pobierz dane</Btn>
        </div>
        <StatisticsModal :dateFrom="form.dateFrom" :dateTo="form.dateTo" :person="form.person ? preparedConnectedUsers.find(item => item.key == form.person).value : ''" :data="lessonData" :role="role" v-if="showSummaryModal" @close="showSummaryModal=false"/>
    </Layout>
</template>

<script setup>

import Layout from "@/Layouts/Layout.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";
import {onBeforeMount, onMounted, ref} from "vue";
import Datepicker from "@/Components/Inputs/Datepicker.vue";
import {getStringFromDate} from "@/Helpers/helpers.js";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Select from "@/Components/Inputs/Select.vue";
import StatisticsModal from "@/Components/Modals/StatisticsModal.vue";

const store = useMainStore();
const isBtnLoader = ref(false);
const showSummaryModal = ref(false);
const lessonData = ref([]);

const props = defineProps({
    connectedUsers: {
        type: Array,
        default() {
            return []
        }
    },
    role: {
        type: String,
        default: ''
    },
})

const preparedConnectedUsers = ref(props.connectedUsers.map(item => {
    return {
        key: item.id,
        value: item.first_name + ' ' + item.last_name
    }
}))

const form = ref({
    dateFrom: new Date(),
    dateTo: new Date(),
    person: null
})

onBeforeMount(() => {
    form.value.dateTo.setDate(form.value.dateTo.getDate() + 7);
})
function getData() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        store.clearErrors();
        isBtnLoader.value = true;
        axios.get(route('reports.details'), {
            params: {
                dateFrom: getStringFromDate(form.value.dateFrom).split(', ')[0].split('.').reverse().join('-'),
                dateTo: getStringFromDate(form.value.dateTo).split(', ')[0].split('.').reverse().join('-'),
                chosenUserId: form.value.person
            }
        })
            .then(response => {
                store.showSnackbar('Dane załadowane pomyślnie', 'success');
                lessonData.value = response.data.lessonsData;
                console.log(response.data.lessonsData)
                showSummaryModal.value = true;
                console.log(lessonData.value);
            })
            .catch(error => {
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    store.showSnackbar('Wystąpił niespodziewany błąd, odśwież stronę', 'error');
                }
            }).finally(() => {
            isBtnLoader.value = false;
            store.setIsLock(false);
        });
    }
}
</script>

<style scoped>

</style>
