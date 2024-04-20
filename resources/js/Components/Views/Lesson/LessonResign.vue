<script setup>
// schedule.resign
import Btn from "@/Components/Buttons/Btn.vue";
import {ref} from "vue";
import ResignationModal from "@/Components/Modals/ResignationModal.vue";

const isResignationModal = ref(false);
const props = defineProps({
    recipient: {
        type: Object,
        default() {
            return {}
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

</script>

<template>
    <div class="max-w-[450px] mt-12 mx-auto">
        <Btn btnType="danger" @click="isResignationModal=true">Zrezygnuj z zajęć</Btn>
        <ResignationModal v-if="isResignationModal" @close="isResignationModal=false" :recipient="recipient" :scheduleId="scheduleId" :userType="userType"/>

    </div>
</template>

<style scoped>

</style>
