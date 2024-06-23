<script setup>

import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import ModalConfirmation from "@/Components/Modals/ModalConfirmation.vue";
import {onMounted, ref} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";

const emits = defineEmits(['filesUploaded'])
const props = defineProps({
    filesIds: {
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
    lessonId: {
        type: [Number, String],
        default: null
    },
    teacherAttachments: {
        type: Array,
        default() {
            return []
        }
    },
    userType: {
        type: String,
        default: ''
    },
})

const isAttachmentModal = ref(false);
const isRemoveModal = ref(false);

const attachments = defineModel();
const chosenElem = ref(null);
const localAttachments = ref([]);

function handleModalClose() {
    isRemoveModal.value = false;
    chosenElem.value = null;
}

function handleRemoveElemConfirmation() {
    //@TODO
    alert('remove');
    handleModalClose();
}

function handleDownloadButtonClicked() {
    //@TODO
    alert('download');
}

function handleRemoveButtonClicked() {
    //@TODO
    alert('remove');
}

async function handleFileUploaded(event) {
    const selectedFiles = event.target.files;
    for (let i = 0; i < selectedFiles.length; i++) {
        attachments.value.push(selectedFiles[i]);
    }

    const formData = new FormData();
    for (let i = 0; i < attachments.value.length; i++) {
        formData.append('files[]', attachments.value[i]);
    }

    try {
        const response = await axios.post(route('file.upload'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        response.data.files.forEach(item => {
            localAttachments.value.push(item)
        })
        emits('filesUploaded', response.data.files.map(item => item.id));
        attachments.value = [];
        // filesIds.value.push(response.data.id);
    } catch (error) {
        console.error(error);
    }
}
</script>

<template>
    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h2 class="text-[24px] heading-[32px] font-bold">Załączniki do lekcji:</h2>
            <input type="file" multiple @input="handleFileUploaded">
<!--            <Btn class="w-max">Dodaj pliki</Btn>-->
        </div>
        <div class="mt-4 border border-textfield-border rounded-lg p-4">
            <p v-if="localAttachments.length === 0 && lessonAttachments.length === 0 ">
                Brak załączników dla tej lekcji.
            </p>
            <template v-else>
                <div v-for="(attachment, index) in localAttachments" class="flex gap-4 items-center justify-between">
                    <div class="flex items-center gap-2 py-2">
                        <p>{{attachment.filename}}</p>
                    </div>
                    <div class="flex gap-4 w-[120px]" :class="userType==='teacher' ? 'w-[120px]' : 'w-[200px]'">
                            <BorderBottomBtn @click="handleDownloadButtonClicked()">Pobierz</BorderBottomBtn>
                            <BorderBottomBtn @click="handleRemoveButtonClicked()" v-if="userType==='teacher'">Usuń</BorderBottomBtn>
                    </div>
                </div>
                <div v-for="(attachment, index) in lessonAttachments" class="flex gap-4 items-center justify-between">
                    <div class="flex items-center gap-2 py-2">
                        <p>{{attachment.filename}}</p>
                    </div>
                    <div class="flex gap-4 w-[120px]" :class="userType==='teacher' ? 'w-[120px]' : 'w-[200px]'">
                        <BorderBottomBtn @click="handleDownloadButtonClicked(homework, index)">Pobierz</BorderBottomBtn>
                        <BorderBottomBtn @click="handleRemoveButtonClicked(homework, index)" v-if="userType==='teacher'">Usuń</BorderBottomBtn>
                    </div>
                </div>
            </template>
        </div>
        <ModalConfirmation desc="Czy na pewno chcesz usunąć zadanie domowe?" v-if="isRemoveModal" @close="handleModalClose"
                           @confirm="handleRemoveElemConfirmation"/>
    </div>
</template>

<style scoped>

</style>
