<template>
    <div class="mb-4 mt-8 flex flex-col md:flex-row justify-between items-center">
        <h2 class="text-base xl:text-[20px] heading-[24px] font-bold">{{desc}}</h2>
    </div>
</template>

<script setup>
const props = defineProps({
    desc: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>

</style>
