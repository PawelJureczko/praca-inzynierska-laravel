<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {router} from "@inertiajs/vue3";
import {useMainStore} from "@/Store/mainStore.js";
import {onMounted, ref} from "vue";
import AbsenceModal from "@/Components/Modals/AbsenceModal.vue";
import LessonMainForm from "@/Components/Views/Lesson/LessonMainForm.vue";
import LessonButtons from "@/Components/Views/Lesson/LessonButtons.vue";
import LessonDataBox from "@/Components/Views/Lesson/LessonDataBox.vue";
import LessonAbsenceBox from "@/Components/Views/Lesson/LessonAbsenceBox.vue";
import LessonGrades from "@/Components/Views/Lesson/LessonGrades.vue";
import LessonHomeworks from "@/Components/Views/Lesson/LessonHomeworks.vue";
import LessonResign from "@/Components/Views/Lesson/LessonResign.vue";
import LessonAttachments from "@/Components/Views/Lesson/LessonAttachments.vue";

const props = defineProps({
    attachments: {
        type: Array,
        default() {
            return []
        }
    },
    auth: {
        type: Object,
        default() {
            return {}
        }
    },
    grades: {
        type: Array,
        default() {
            return []
        }
    },
    homeworks: {
        type: Array,
        default() {
            return []
        }
    },
    lessonAttachments: {
        type: Array,
        default(){
            return []
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
    teacherAttachments: {
        type: Array,
        default(){
            return []
        }
    },
})


const store = useMainStore();
const isBtnLoader = ref(false);
const isAbsenceModal = ref(false);
const filesIds = ref([]);

onMounted(() => {
    filesIds.value = props.lessonAttachments.map(item => item.id);
})

function handleFilesUploaded(val) {
    val.forEach(item => {
        if (!filesIds.value.includes(item)) {
            filesIds.value.push(item);
        }
    })
    console.log(filesIds.value)
}

const lesson = ref({
    topic: props.lessonData ? props.lessonData.topic : '',
    notes: props.lessonData ? props.lessonData.notes : '',
    canceled_by_teacher: props.lessonData ? props.lessonData.canceled_by_teacher === 1 : false,
    canceled_by_student: props.lessonData ? props.lessonData.canceled_by_student === 1 : false,
    grades: props.grades,
    homeworks: props.homeworks,
    attachments: props.attachments
})


const type = (props.lessonData === null ? 'new' : 'edit');
const userType = props.auth.user.role;

function undoAbsence() {
    lesson.value.canceled_by_teacher = false;
    lesson.value.canceled_by_student = false;
}

function save() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('lesson.save'), {
            topic: lesson.value.topic,
            notes: lesson.value.notes,
            schedule_id: props.scheduleData.id,
            lessonDate: props.lessonDate,
            grades: lesson.value.grades,
            homeworks: lesson.value.homeworks,
            filesIds: filesIds.value
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
    //lesson.save
}

function update() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.put(route('lesson.update'), {
            topic: lesson.value.topic,
            notes: lesson.value.notes,
            lessonId: props.lessonData.id,
            lessonDate: props.lessonData.date,
            canceledByStudent: lesson.value.canceled_by_student,
            canceledByTeacher: lesson.value.canceled_by_teacher,
            absenceReason: (lesson.value.canceled_by_student || lesson.value.canceled_by_teacher) ? props.lessonData.absence_reason : null,
            grades: lesson.value.grades,
            homeworks: lesson.value.homeworks,
            filesIds: filesIds.value
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

            <LessonAbsenceBox v-if="lesson.canceled_by_teacher || lesson.canceled_by_student" :currentData="currentData"
                              @undoAbsence="undoAbsence"/>

            <LessonDataBox :currentData="currentData" :lessonData="lessonData" :lessonDate="lessonDate"
                           :scheduleData="scheduleData" :userType="auth.user.role"/>

            <LessonMainForm :userType="userType" v-model="lesson"/>

            <LessonGrades :userType="userType" v-model="lesson.grades"/>

            <LessonHomeworks :userType="userType" v-model="lesson.homeworks"
                             :lessonId="lessonData ? lessonData.id : null"/>

            <LessonAttachments :userType="userType" v-model="lesson.attachments" :filesIds=filesIds :lessonAttachments="lessonAttachments" :teacherAttachments="teacherAttachments" @filesUploaded="handleFilesUploaded"/>

            <LessonButtons :userType="userType" :type="type" :lesson="lesson" @save="save" @update="update"/>


            <LessonResign :userType="userType"
                          :scheduleId="currentData.schedule_id ? currentData.schedule_id : scheduleData.id"
                          :recipient="userType === 'teacher' ? ({name: (currentData.student_first_name + ' ' + currentData.student_last_name), id: currentData.student_id}) : ({name: (currentData.teacher_first_name + ' ' + currentData.teacher_last_name), id: currentData.teacher_id})"
                          v-if="currentData.resigned_by===null"/>


        </div>
    </Layout>
    <AbsenceModal :userType="userType" :date="lessonDate ? lessonDate : lessonData.date"
                  :scheduleId="scheduleData ? scheduleData.id : lessonData.schedule_id" v-if="isAbsenceModal"
                  @close="isAbsenceModal=false"/>
</template>

<style scoped>

</style>
