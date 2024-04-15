<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {router} from "@inertiajs/vue3";
import {useMainStore} from "@/Store/mainStore.js";
import {ref} from "vue";
import {getStringFromDate} from "../../Helpers/helpers.js";
import AbsenceModal from "@/Components/Modals/AbsenceModal.vue";

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
const isAbsenceModal = ref(props.lessonData===null);



const type = (props.lessonData===null ? 'new' : 'edit');
const userType = props.auth.user.role;

function save() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('lesson.save'), {

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
            isBtnLoader.value=false;
        })}

    console.log('test');
    //lesson.save
}

const currentData = ref(props.lessonData ? props.lessonData : props.scheduleData)

</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex justify-between items-center">
            <TitleComponent :desc="type==='edit' ? 'Edytuj lekcje' : 'Stwórz lekcje'" :isSearch="false"/>
            <div v-if="type==='new'">
                <Btn btnType="danger">Zgłoś nieobecność</Btn>
            </div>
        </div>
        <div>
            <p>Zajęcia z: {{userType==='teacher' ? (currentData.student_first_name + ' ' + currentData.student_last_name) : (currentData.teacher_first_name + ' ' + currentData.teacher_last_name)}}</p>
            <p>Termin zajęć: {{getStringFromDate(new Date(scheduleData ? lessonDate : lessonData.date)).split(', ')[0]}}</p>
            <p>Zajęcia od: {{currentData.classes_time_start.split(':').slice(0,2).join(':')}}</p>
            <p>Zajęcia do: {{currentData.classes_time_end.split(':').slice(0,2).join(':')}}</p>
        </div>
        <p>{{lessonData}}</p>
        <p>{{scheduleData}}</p>
        <Btn @click="save">Zapisz</Btn>
    </Layout>
    <AbsenceModal :userType="userType" :date="lessonDate" v-if="isAbsenceModal"/>
</template>

<style scoped>

</style>
