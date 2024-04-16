<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {router} from "@inertiajs/vue3";
import {useMainStore} from "@/Store/mainStore.js";
import {ref} from "vue";
import {getStringFromDate} from "../../Helpers/helpers.js";
import AbsenceModal from "@/Components/Modals/AbsenceModal.vue";
import TextField from "@/Components/Inputs/TextField.vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";

const props = defineProps({
    auth: {
        type: Object,
        default() {
            return {}
        }
    },
    lessonData: {
        type: Object,
        default() {
            return null
        }
    },
    lessonDate: {
        type: String,
        default: ''
    },
    scheduleData: {
        type: Object,
        default() {
            return null
        }
    },
})


const store = useMainStore();
const isBtnLoader = ref(false);
const isAbsenceModal = ref(false);

const lesson = ref({
    topic: props.lessonData ? props.lessonData.topic : '',
    notes: props.lessonData ? props.lessonData.notes : '',
    canceled_by_teacher: props.lessonData ? props.lessonData.canceled_by_teacher === 1 : false,
    canceled_by_student: props.lessonData ? props.lessonData.canceled_by_student === 1 : false,
})


const type = (props.lessonData === null ? 'new' : 'edit');
const userType = props.auth.user.role;

function undoAbsence() {
    lesson.value.canceled_by_teacher = false;
    lesson.value.canceled_by_student = false;
}

function saveBasicData() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('lesson.save'), {
            topic: lesson.value.topic,
            notes: lesson.value.notes,
            schedule_id: props.scheduleData.id,
            lessonDate: props.lessonDate
        })
            .then(response => {
                if (response.data.status === 'ok') {
                    router.visit(route('schedule.index'));
                }
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response.data.errors)
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    console.log(error)
                }
            }).finally(() => {
            store.setIsLock(false);
            isBtnLoader.value = false;
        })
    }

    console.log('test');
    //lesson.save
}

function updateBasicData() {
    console.log('update')
}

const currentData = ref(props.lessonData ? props.lessonData : props.scheduleData)

</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="max-w-[672px] mx-auto">
            <div class="flex flex-wrap justify-between items-center">
                <TitleComponent :desc="type==='edit' ? 'Edytuj lekcje' : 'Stwórz lekcje'" :isSearch="false"/>
                <div v-if="type==='new' || (!lesson.canceled_by_teacher && !lesson.canceled_by_student)">
                    <Btn btnType="danger" @click="isAbsenceModal=true">Zgłoś nieobecność</Btn>
                </div>
            </div>

            <div v-if="lesson.canceled_by_teacher || lesson.canceled_by_student"
                 class="border border-[red] rounded-lg p-4">
                <div class="bg-[red] w-max px-4 py-2 rounded-[4px]">
                    <p class="text-white text-[12px] leading-[16px] font-bold">Zajęcia odwołane przez
                        {{ currentData.canceled_by_student === 1 ? 'ucznia.' : 'nauczyciela' }}</p>
                </div>
                <p class="mt-2">Powód: {{ currentData.absence_reason ? currentData.absence_reason : 'Brak.' }}</p>

                <BorderBottomBtn class="mt-2" @click="undoAbsence">Oznacz jako obecny</BorderBottomBtn>
            </div>

            <div class="mt-6 flex flex-col gap-2">
                <p class="text-[22px] leading-[26px]">Zajęcia z: <b>
                    {{ userType === 'teacher' ? (currentData.student_first_name + ' ' + currentData.student_last_name) : (currentData.teacher_first_name + ' ' + currentData.teacher_last_name) }}</b></p>
                <p class="text-[22px] leading-[26px]">Termin zajęć: <b>
                    {{ getStringFromDate(new Date(scheduleData ? lessonDate : lessonData.date)).split(', ')[0] }}</b></p>
                <p  class="text-[22px] leading-[26px]">Godziny zajęć: <b>{{ currentData.classes_time_start.split(':').slice(0, 2).join(':') }} - {{ currentData.classes_time_end.split(':').slice(0, 2).join(':') }}</b></p>
            </div>

            <div v-if="!lesson.canceled_by_teacher && !lesson.canceled_by_student" class="mt-4">
                <div class="mb-6">
                    <p class="text-[18px] leading-[24px] font-bold">Temat zajęć:</p>
                    <TextField v-model="lesson.topic" placeholder="Wpisz temat zajęć..." errorName="topic"/>
                </div>

                <div class="mb-6">
                    <p class="text-[18px] leading-[24px] font-bold">Opis zajęć:</p>
                    <TextField v-model="lesson.notes" placeholder="Wpisz notatkę do zajęć..." errorName="notes"/>
                </div>
            </div>
            <p>{{ lessonData }}</p>
            <p>{{ scheduleData }}</p>
            <Btn @click="saveBasicData" v-if="type==='new'">Zapisz</Btn>
            <Btn @click="updateBasicData" v-if="type==='edit'">Aktualizuj</Btn>
        </div>
    </Layout>
    <AbsenceModal :userType="userType" :date="lessonDate ? lessonDate : lessonData.date" :scheduleId="scheduleData ? scheduleData.id : lessonData.schedule_id" v-if="isAbsenceModal"
                  @close="isAbsenceModal=false"/>
</template>

<style scoped>

</style>
