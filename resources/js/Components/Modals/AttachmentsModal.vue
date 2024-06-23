<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import {computed, ref} from "vue";
import TextField from "@/Components/Inputs/TextField.vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import {useMainStore} from "@/Store/mainStore.js";

const store = useMainStore();

const props = defineProps({
    teacherAttachments: {
        type: Array,
        default() {
            return []
        }
    },
    topBarDesc: {
        type: String,
        default: ''
    },
})

const localAttachments = ref(props.teacherAttachments);
const searchPhrase = ref('');
const localAttachmentsCp = computed(() => {
    return localAttachments.value.filter(item => item.filename.toLowerCase().includes(searchPhrase.value.toLowerCase()));
})

function handleDelete(attachment) {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        store.clearErrors();
        axios.put(route('file.delete', {
            id: attachment.id
        }))
            .then(response => {
                localAttachments.value = localAttachments.value.filter(item => item.id !== attachment.id)
                console.log(localAttachments.value)
                console.log(attachment)
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
        });
    }
}
</script>

<template>
    <ModalWrapper :topBarDesc="topBarDesc">
        <div class="min-w-[40vw]">
            <p>Znajdź plik:</p>
            <TextField class="mt-1" v-model="searchPhrase" placeholder="Szukana nazwa pliku..."/>
        </div>
        <div class="border p-4 rounded-[8px] min-h-[50vh] max-h-[70vh] overflow-y-auto mt-4 flex flex-col gap-3">
            <div class="px-2 py-3 border border-light_grey" v-for="attachment in localAttachmentsCp">
                <p class="text-[12px] leading-[14px]">{{attachment.filename}}</p>
                <div class="flex gap-4 mt-2">
                    <BorderBottomBtn desc="Dodaj" @click="$emit('addFile', attachment)"/>
                    <BorderBottomBtn desc="Trwale usuń plik" @click="handleDelete(attachment)"/>
                </div>
            </div>
        </div>
    </ModalWrapper>
</template>

<style scoped>

</style>
