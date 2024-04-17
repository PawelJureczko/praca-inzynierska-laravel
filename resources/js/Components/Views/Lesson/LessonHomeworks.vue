<script setup>
import Btn from "@/Components/Buttons/Btn.vue";
import {ref} from "vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import ModalConfirmation from "@/Components/Modals/ModalConfirmation.vue";
import HomeworkModal from "@/Components/Modals/HomeworkModal.vue";

const isHomeworkModal = ref(false);
const isRemoveModal = ref(false);

function addHomework(val) {
    if (chosenElem.value.desc !== '') {
        if (chosenElem.value.id) {
            homeworks.value.find(item => item.id === chosenElem.value.id).grade = val.grade;
            homeworks.value.find(item => item.id === chosenElem.value.id).desc = val.desc;
        } else {
            homeworks.value[chosenElem.value.tempId] = val
        }
    } else {
        homeworks.value.push(val);
    }
    chosenElem.value = {
        desc: ''
    };
    isHomeworkModal.value = false;
}

function handleEditButtonClicked(item, index) {
    if (item.id) {
        chosenElem.value = item;
    } else {
        chosenElem.value = {
            ...item,
            tempId: index
        };
    }
    isHomeworkModal.value = true;
}

function handleRemoveButtonClicked(item, index) {
    if (item.id) {
        chosenElem.value = item;
    } else {
        chosenElem.value = {
            ...item,
            tempId: index
        };
    }
    isRemoveModal.value = true;
}

function handleRemoveElemConfirmation() {
    homeworks.value = homeworks.value.filter(item => item.id !== chosenElem.value.id)
    chosenElem.value = {
        desc: ''
    }
}

function handleModalClose() {
    isRemoveModal.value = false;
    isHomeworkModal.value = false;
    chosenElem.value = {
        desc: ''
    }
}

const homeworks = defineModel();
const chosenElem = ref({
    desc: ''
});
</script>

<template>
    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h2 class="text-[24px] heading-[32px] font-bold">Zadania domowe:</h2>
            <Btn @click="isHomeworkModal = true" class="w-max">Dodaj zadanie</Btn>
        </div>

        <div class="mt-4 border border-main rounded-lg p-4">
            <p v-if="homeworks.length===0">
                Brak zadań domowych dla tej lekcji.
            </p>
            <div v-else>
                <div v-for="(homework, index) in homeworks" class="flex gap-4 items-center justify-between">
                    <div class="flex items-center gap-2 py-2">
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
        <HomeworkModal v-if="isHomeworkModal" @close="handleModalClose" @save="addHomework" />
        <ModalConfirmation desc="Czy na pewno chcesz usunąć zadanie domowe?" v-if="isRemoveModal" @close="handleModalClose"
                           @confirm="handleRemoveElemConfirmation"/>

    </div>
</template>

<style scoped>

</style>
