<script setup>
import Btn from "@/Components/Buttons/Btn.vue";
import {ref} from "vue";
import GradesModal from "@/Components/Modals/GradesModal.vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import ModalConfirmation from "@/Components/Modals/ModalConfirmation.vue";
import {getUuid} from "@/Helpers/helpers.js";

const isGradesModal = ref(false);
const isRemoveModal = ref(false);

function addGrade(val) {
    if (chosenElem.value.grade !== '') {
        if (chosenElem.value.id) {
            grades.value.find(item => item.id === chosenElem.value.id).grade = val.grade;
            grades.value.find(item => item.id === chosenElem.value.id).desc = val.desc;
        } else {
            grades.value[chosenElem.value.tempId] = val
        }
    } else {
        grades.value.push({
            ...val,
            tempId: getUuid()
        });
    }
    chosenElem.value = {
        grade: '',
        desc: ''
    };
    isGradesModal.value = false;
}

function handleEditButtonClicked(item, index) {
    chosenElem.value = item;
    isGradesModal.value = true;
}

function handleRemoveButtonClicked(item, index) {
    chosenElem.value = item;
    isRemoveModal.value = true;
}

function handleRemoveElemConfirmation() {
    if (chosenElem.value.tempId) {
        grades.value = grades.value.filter(item => item.tempId !== chosenElem.value.tempId)
    } else {
        grades.value = grades.value.filter(item => item.id !== chosenElem.value.id)
    }
    chosenElem.value = {
        grade: '',
        desc: ''
    }
    isRemoveModal.value = false;
}

function handleModalClose() {
    isRemoveModal.value = false;
    isGradesModal.value = false;
    chosenElem.value = {
        grade: '',
        desc: ''
    }
}

const grades = defineModel();
const chosenElem = ref({
    grade: '',
    desc: ''
});
</script>

<template>
    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h2 class="text-[24px] heading-[32px] font-bold">Oceny:</h2>
            <Btn @click="isGradesModal = true" class="w-max">Dodaj ocenę</Btn>
        </div>

        <div class="mt-4 border border-main rounded-lg p-4">
            <p v-if="grades.length===0">
                Brak ocen dla tej lekcji.
            </p>
            <div v-else>
                <div v-for="(grade, index) in grades" class="flex gap-4 items-center justify-between">
                    <div class="flex items-center gap-2 py-2">
                        <div class="w-8">
                            <p class="text-center">{{ grade.grade }}</p>
                        </div>
                        <div>
                            <p>{{ grade.desc }}</p>
                        </div>
                    </div>
                    <div class="flex gap-4 w-[120px]">
                        <BorderBottomBtn @click="handleEditButtonClicked(grade, index)">Edytuj</BorderBottomBtn>
                        <BorderBottomBtn @click="handleRemoveButtonClicked(grade, index)">Usuń</BorderBottomBtn>
                    </div>
                </div>
            </div>
        </div>
        <GradesModal v-if="isGradesModal" @close="handleModalClose" @save="addGrade" :grade="chosenElem.grade"
                     :desc="chosenElem.desc"/>
        <ModalConfirmation desc="Czy na pewno chcesz usunąć ocenę?" v-if="isRemoveModal" @close="handleModalClose"
                           @confirm="handleRemoveElemConfirmation"/>

    </div>
</template>

<style scoped>

</style>
