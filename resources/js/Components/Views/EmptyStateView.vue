<template>
    <div class="flex flex-col items-center gap-12">
        <EmptyStateIcon/>
        <p class="font-semibold text-[24px] leading-[32px]">{{desc}}</p>
    </div>
</template>

<script setup>
import EmptyStateIcon from "@/Components/Icons/EmptyStateIcon.vue";

const props = defineProps({
    desc: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>

</style>
