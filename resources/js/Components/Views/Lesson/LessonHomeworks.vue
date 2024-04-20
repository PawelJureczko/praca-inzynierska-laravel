<script setup>
import Btn from "@/Components/Buttons/Btn.vue";
import {ref} from "vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import ModalConfirmation from "@/Components/Modals/ModalConfirmation.vue";
import HomeworkModal from "@/Components/Modals/HomeworkModal.vue";
import {getUuid} from "@/Helpers/helpers.js";

const isHomeworkModal = ref(false);
const isRemoveModal = ref(false);

function addHomework(val) {
    console.log(val)
    if (chosenElem.value.desc !== '') {
        if (chosenElem.value.id) {
            console.log('tu1')
            homeworks.value.find(item => item.id === chosenElem.value.id).desc = val.desc;
            homeworks.value.find(item => item.id === chosenElem.value.id).date = val.date;
        } else {
            homeworks.value.find(item => item.tempId === chosenElem.value.tempId).desc = val.desc;
            homeworks.value.find(item => item.tempId === chosenElem.value.tempId).date = val.date;
        }
    } else {
        homeworks.value.push({
            desc: val.desc,
            date: val.date,
            tempId: getUuid()
        });
    }
    chosenElem.value = {
        desc: '',
        date: ''
    };
    isHomeworkModal.value = false;
}

function handleEditButtonClicked(item, index) {
    chosenElem.value = item;
    isHomeworkModal.value = true;
}

function handleRemoveButtonClicked(item, index) {
    chosenElem.value = item;
    isRemoveModal.value = true;
}

function handleModalClose() {
    isRemoveModal.value = false;
    isHomeworkModal.value = false;
    chosenElem.value = {
        desc: '',
        date: ''
    }
}

function handleRemoveElemConfirmation() {
    if (chosenElem.value.tempId) {
        homeworks.value = homeworks.value.filter(item => item.tempId !== chosenElem.value.tempId)
    } else {
        homeworks.value = homeworks.value.filter(item => item.id !== chosenElem.value.id)
    }    chosenElem.value = {
        desc: '',
        date: ''
    }

    handleModalClose();
}

const homeworks = defineModel();
const chosenElem = ref({
    desc: '',
    date: ''
});
</script>

<template>
    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h2 class="text-[24px] heading-[32px] font-bold">Zadania domowe:</h2>
            <Btn @click="isHomeworkModal = true" class="w-max">Dodaj zadanie</Btn>
        </div>

        <div class="mt-4 border border-textfield-border rounded-lg p-4">
            <p v-if="homeworks.length===0">
                Brak zadań domowych dla tej lekcji.
            </p>
            <div v-else>
                <div v-for="(homework, index) in homeworks" class="flex gap-4 items-center justify-between">
                    <div class="flex items-center gap-2 py-2">
                        <div>
                            <p>{{ homework.date ? homework.date.split('-').reverse().join('.') : '' }}</p>
                        </div>
                        <div>
                            <p>{{ homework.desc }}</p>
                        </div>
                    </div>
                    <div class="flex gap-4 w-[120px]">
                        <BorderBottomBtn @click="handleEditButtonClicked(homework, index)">Edytuj</BorderBottomBtn>
                        <BorderBottomBtn @click="handleRemoveButtonClicked(homework, index)">Usuń</BorderBottomBtn>
                    </div>
                </div>
            </div>
        </div>
        <HomeworkModal v-if="isHomeworkModal" @close="handleModalClose" @save="addHomework" :desc="chosenElem.desc" :date="chosenElem.date"/>
        <ModalConfirmation desc="Czy na pewno chcesz usunąć zadanie domowe?" v-if="isRemoveModal" @close="handleModalClose"
                           @confirm="handleRemoveElemConfirmation"/>

    </div>
</template>

<style scoped>

</style>
