<script setup>
    const data = [
        {
            desc: 'Zajęcia ukończone',
            value: 'bg-schedule-completed',
        },
        {
            desc: 'Nieobecność nauczyciela',
            value: 'bg-schedule-teacher_absence',
        },
        {
            desc: 'Nieobecność ucznia',
            value: 'bg-schedule-student_absence',
        },
        {
            desc: 'Zajęcia zaplanowane',
            value: 'bg-schedule-incoming',
        },

    ]
</script>

<template>
    <div class="flex items-center justify-start gap-4 max-w-full flex-wrap">
        <div v-for="item in data" class="flex items-center gap-2">
            <div class="w-4 h-4" :class="item.value"></div>
            <p class="text-[12px] leading-[16px]">{{item.desc}}</p>
        </div>
    </div>
</template>

<style scoped>

</style>
