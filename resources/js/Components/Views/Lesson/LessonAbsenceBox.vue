<script setup>
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";

const props = defineProps({
    currentData: {
        type: Object,
        default() {
            return {

            }
        }
    }
})
</script>

<template>
    <div class="border border-[red] rounded-lg p-4">
        <div class="bg-[red] w-max px-4 py-2 rounded-[4px]">
            <p class="text-white text-[12px] leading-[16px] font-bold">Zajęcia odwołane przez
                {{ currentData.canceled_by_student === 1 ? 'ucznia.' : 'nauczyciela' }}</p>
        </div>
        <p class="mt-2">Powód: {{ currentData.absence_reason ? currentData.absence_reason : 'Brak.' }}</p>

        <BorderBottomBtn class="mt-2" @click="$emit('undoAbsence')">Oznacz jako obecny</BorderBottomBtn>
    </div>
</template>

<style scoped>

</style>
