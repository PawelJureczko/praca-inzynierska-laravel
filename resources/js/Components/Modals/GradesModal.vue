<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import Select from "@/Components/Inputs/Select.vue";
import TextField from "@/Components/Inputs/TextField.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {ref} from "vue";

const props = defineProps({
    grade: {
        type: String,
        default: ''
    },
    desc: {
        type: String,
        default: ''
    }
})

const form = ref({
    grade: props.grade,
    desc: props.desc
})
</script>

<template>
    <ModalWrapper @close="$emit('close')">
        <div class="w-screen max-w-[calc(100%_-_32px)] mx-auto md:max-w-[420px]">
            <Select label="Ocena:" placeholder="Wybierz ocenę..." :options="['1', '2-', '2', '2+', '3-', '3', '3+', '4-', '4', '4+', '5-', '5', '5+', '6']" v-model="form.grade" :keyAsValue="false"/>

            <p class="mt-4">Opis oceny:</p>
            <TextField class="mt-1" placeholder="Wpisz opis oceny..." v-model="form.desc" />
        </div>

        <div class="flex gap-4 mt-8">
            <Btn @click="$emit('save', form)">Zapisz</Btn>
            <Btn @click="$emit('close')" btnType="secondary">Anuluj</Btn>
        </div>
    </ModalWrapper>
</template>

<style scoped>

</style>
