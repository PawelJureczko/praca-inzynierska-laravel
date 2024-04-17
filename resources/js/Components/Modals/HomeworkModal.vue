<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {ref, watch} from "vue";
import TextArea from "@/Components/Inputs/TextArea.vue";
import Datepicker from "@/Components/Inputs/Datepicker.vue";
import {getDateFromString, getStringFromDate} from "@/Helpers/helpers.js";

const props = defineProps({
    date: {
        type: String,
        default: ''
    },
    desc: {
        type: String,
        default: ''
    }
})

// const preparedDate = props.date!=='' ? getDateFromString(props.date) : new Date();
const preparedDate = ref(props.date!=='' ? getDateFromString(props.date) : '');

const form = ref({
    desc: props.desc,
    date: props.date ? props.date : ''
})

watch(preparedDate, () => {
    form.value.date = getStringFromDate(preparedDate.value).split(', ')[0].split('.').reverse().join('-');
})


</script>

<template>
    <ModalWrapper @close="$emit('close')">
        <div class="w-screen max-w-[100%_-_32px] md:max-w-[420px]">
            <p>Termin ukończenia zadania domowego</p>
            <Datepicker v-model="preparedDate"/>
            <p class="mt-4">Treść zadania domowego:</p>
            <TextArea class="mt-1" placeholder="Wpisz treść zadania..." v-model="form.desc" />
        </div>

        <div class="flex gap-4 mt-8">
            <Btn @click="$emit('save', form)">Zapisz</Btn>
            <Btn @click="$emit('close')" btnType="secondary">Anuluj</Btn>
        </div>
    </ModalWrapper>
</template>

<style scoped>

</style>
