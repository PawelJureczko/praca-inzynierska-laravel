<template>
    <button class="button min-h-12 min-w-20 w-full py-2 rounded-lg flex items-center justify-center w-max px-4 cursor-auto transition-colors cursor-pointer h-max"
            :class="[
                isLoader && 'pointer-events-none',
                disabled && 'opacity-[0.7] pointer-events-none',
                btnType==='primary' && 'border border-btn-primaryHover bg-btn-primary xl:hover:bg-btn-primaryHover',
                btnType==='secondary' && 'bg-btn-secondary xl:hover:bg-btn-secondaryHover',
                btnType==='danger' && 'bg-btn-danger xl:hover:bg-btn-dangerHover'

            ]">
        <div class="animate-spin w-max absolute" v-if="isLoader">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24">
                <path stroke="#FFFFFF"
                        stroke-linecap="round"
                        stroke-width="2"
                        d="M12 3a9 9 0 1 0 9 9M19.794 7.5c.096.167.187.337.273.51"/>
            </svg>
        </div>
        <p class="text-text-base-small font-medium" :class="[
            btnType==='primary' && 'text-btn-textPrimary',
            btnType==='secondary' && 'text-btn-textSecondary',
            btnType === 'danger' && 'text-btn-textPrimary',
            isLoader && 'opacity-0'
        ]">
            <slot/>
        </p>
    </button>
</template>

<script setup>
const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    isLoader: {
        type: Boolean,
        default: false,
    },
    btnType: {
        type: String,
        default: 'primary',
    },
})
</script>

<style scoped>

</style>
