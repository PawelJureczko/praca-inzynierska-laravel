<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {ref} from "vue";
import TextArea from "@/Components/Inputs/TextArea.vue";

const props = defineProps({
    desc: {
        type: String,
        default: ''
    }
})

const form = ref({
    desc: props.desc
})
</script>

<template>
    <ModalWrapper @close="$emit('close')">
        <div class="w-screen max-w-[100%_-_32px] md:max-w-[420px]">
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
