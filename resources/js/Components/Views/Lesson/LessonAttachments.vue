<script setup>

import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import ModalConfirmation from "@/Components/Modals/ModalConfirmation.vue";
import {onBeforeMount, onMounted, ref} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";
import AttachmentsModal from "@/Components/Modals/AttachmentsModal.vue";

const emits = defineEmits(['filesUploaded', 'fileRemoved'])
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
const store = useMainStore();

const attachments = defineModel();
const chosenElem = ref(null);
const localAttachments = ref([]);

function handleModalClose() {
    isRemoveModal.value = false;
    chosenElem.value = null;
}

function handleRemoveElemConfirmation() {
    handleModalClose();
}

function handleDownloadButtonClicked(id) {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        store.clearErrors();

        axios({
            url: route('file.download', {id: id}),
            method: 'GET',
            responseType: 'blob', // Oczekujemy binarnego strumienia danych
        })
            .then(response => {
                // Utwórz URL dla pliku
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;

                // Pobierz nazwę pliku z nagłówków odpowiedzi
                const contentDisposition = response.headers['content-disposition'];
                let fileName = 'downloaded_file';
                if (contentDisposition) {
                    const fileNameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                    if (fileNameMatch && fileNameMatch[1]) {
                        fileName = fileNameMatch[1].replace(/['"]/g, '');
                    }
                }

                link.setAttribute('download', fileName); // Określ nazwę pliku
                document.body.appendChild(link);
                link.click();

                // Usuń element po kliknięciu
                document.body.removeChild(link);
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response?.data?.errors);
                if (error.response && error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    console.log(error);
                }
            })
            .finally(() => {
                store.setIsLock(false);
            });
    }
}


function handleRemoveButtonClicked(id, type) {
    emits('fileRemoved', id)
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
<!--            <input type="file" multiple @input="handleFileUploaded" v-if="userType === 'teacher'">-->
            <Btn class="w-max" @click="isAttachmentModal=true">Dodaj pliki</Btn>
        </div>
        <div class="mt-4 border border-textfield-border rounded-lg p-4">
            <p v-if="(localAttachments.length === 0 && lessonAttachments.length === 0) || filesIds.length === 0">
                Brak załączników dla tej lekcji.
            </p>
            <template v-else>
                <div v-for="(attachment) in localAttachments" class="flex gap-4 items-center justify-between">
                    <template v-if="filesIds.includes(attachment.id)">
                        <div class="flex items-center gap-2 py-2">
                            <p>{{attachment.filename}}</p>
                        </div>
                        <div class="flex gap-4 w-[120px]" :class="userType==='teacher' ? 'w-[120px]' : 'w-[200px]'">
                            <BorderBottomBtn @click="handleDownloadButtonClicked(attachment.id)">Pobierz</BorderBottomBtn>
                            <BorderBottomBtn @click="handleRemoveButtonClicked(attachment.id, 'local')" v-if="userType==='teacher'">Usuń</BorderBottomBtn>
                        </div>
                    </template>
                </div>
                <div v-for="(attachment) in lessonAttachments" class="flex gap-4 items-center justify-between">
                    <template v-if="filesIds.includes(attachment.id)">
                        <div class="flex items-center gap-2 py-2">
                            <p>{{attachment.filename}}</p>
                        </div>
                        <div class="flex gap-4 w-[120px]" :class="userType==='teacher' ? 'w-[120px]' : 'w-[200px]'">
                            <BorderBottomBtn @click="handleDownloadButtonClicked(attachment.id)">Pobierz</BorderBottomBtn>
                            <BorderBottomBtn @click="handleRemoveButtonClicked(attachment.id, 'lesson')" v-if="userType==='teacher'">Usuń</BorderBottomBtn>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        <ModalConfirmation desc="Czy na pewno chcesz usunąć zadanie domowe?" v-if="isRemoveModal" @close="handleModalClose" @confirm="handleRemoveElemConfirmation"/>

        <AttachmentsModal topBarDesc="Wszystkie pliki" v-if="isAttachmentModal" @close="isAttachmentModal=false" :teacherAttachments="teacherAttachments"/>
    </div>
</template>

<style scoped>

</style>
