<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import {ref} from "vue";
import Radiobox from "@/Components/Inputs/Radiobox.vue";
import TextArea from "@/Components/Inputs/TextArea.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    date: {
        type: String,
        default: ''
    },
    scheduleId: {
        type: Number,
        default: null
    },
    userType: {
        type: String,
        default: ''
    }
})

const absentPerson = ref('student');
const absenceReason = ref('');
const store = useMainStore();
const isBtnLoader = ref(false);

function save() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('absence.create'), {
            date: props.date,
            schedule_id: props.scheduleId,
            absent_person: absentPerson.value,
            absence_reason: absenceReason.value
        })
            .then(response => {
                store.showSnackbar('Zgłoszono nieobecność', 'success', 10000);
                router.visit(route('lesson.edit', {id: response.data.lessonId}));
                // window.location.reload();
                // if (response.data.status === 'ok') {
                // }
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response.data.errors)
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    console.log(error)
                    store.showSnackbar('Wystąpił nieoczekiwany błąd', 'error', 10000);
                }
            }).finally(() => {
            store.setIsLock(false);
            isBtnLoader.value=false;
        })}
    console.log(absenceReason.value + ' ' + absentPerson.value)
}

</script>

<template>
    <ModalWrapper>
        <div class="w-screen max-w-[100%_-_32px] md:max-w-[420px]">

            <p class="text-[18px] leading-[24px]">Zgłoś nieobecność w dniu:<br> <b>{{date}}</b></p>
            <div v-if="userType === 'teacher'">

                <p class="text-[18px] leading-[24px] font-bold mt-4">Osoba nieobecna:</p>
                <div class="flex items-center gap-4 mt-2">
                    <Radiobox label="Uczeń" :selectedValue="absentPerson" value="student" @click="absentPerson='student'"/>
                    <Radiobox label="Nauczyciel" :selectedValue="absentPerson" value="teacher" @click="absentPerson='teacher'"/>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-[18px] leading-[24px] font-bold mt-4">Powód nieobecności:</p>
            <TextArea v-model="absenceReason" class="mt-2"/>
        </div>

        <Btn class="max-w-[220px] mx-auto mt-6" @click="save">Zgłoś</Btn>


    </ModalWrapper>
</template>

<style scoped>

</style>
