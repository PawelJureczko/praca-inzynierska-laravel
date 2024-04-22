<script setup>

import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import Btn from "@/Components/Buttons/Btn.vue";

const props = defineProps({
    desc: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <ModalWrapper @close="$emit('close')">
        <div class="w-screen max-w-[calc(100%_-_32px)] mx-auto md:max-w-[420px]">
            <p class="text-[18px] leading-[26px]">{{desc}}</p>

            <div class="flex items-center justify-between gap-4 mt-8">
                <Btn @click="$emit('confirm')">Potwierdź</Btn>
                <Btn btnType="secondary" @click="$emit('close')">Anuluj</Btn>
            </div>
        </div>
    </ModalWrapper>
</template>

<style scoped>

</style>
