<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {ref, watch} from "vue";
import TextArea from "@/Components/Inputs/TextArea.vue";
import Datepicker from "@/Components/Inputs/Datepicker.vue";
import {getStringFromDate} from "@/Helpers/helpers.js";
import {router} from "@inertiajs/vue3";
import {useMainStore} from "@/Store/mainStore.js";

const props = defineProps({
    recipient: {
        type: Object,
        default() {
            return {

            }
        }
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

const form = ref({
    desc: '',
    date: getStringFromDate(new Date()).split(', ')[0]
})

const store = useMainStore();
const isBtnLoader = ref(false);

function save() {
    console.log(form.value.date)
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.put(route('schedule.resign'), {
           date: form.value.date.split('.').reverse().join('-'),
            desc:form.value.desc,
            scheduleId: props.scheduleId,
            recipientId: props.recipient.id,
            userType: props.userType
        })
            .then(response => {
                if (response.data.status === 'ok') {
                    window.location.reload();
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


</script>

<template>
    <ModalWrapper @close="$emit('close')">
        <div class="w-screen max-w-[calc(100%_-_32px)] mx-auto md:max-w-[420px]">
            <p>Wybierz termin rezygnacji z zajęć</p>
            <Datepicker v-model="form.date"/>
            <p class="mt-4">Napisz powód rezygnacji z zajęć (opcjonalnie):</p>
            <p class="text-[12px] leading-[16px]">Wiadomość zostanie wysłana do użytkownika {{recipient.name}} przez systemowy chat.</p>
            <TextArea class="mt-1" placeholder="Wpisz treść zadania..." v-model="form.desc" />
        </div>

        <div class="flex gap-4 mt-8">
            <Btn @click="save" :isLoader="isBtnLoader">Zapisz</Btn>
            <Btn @click="$emit('close')" btnType="secondary">Anuluj</Btn>
        </div>
    </ModalWrapper>
</template>

<style scoped>

</style>
