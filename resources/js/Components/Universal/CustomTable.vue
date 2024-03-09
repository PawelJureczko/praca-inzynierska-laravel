<template>
    <div class="w-full">
        <div class="hidden lg:flex lg:px-4  bg-[#D3D3D3]">
            <div class="py-4" v-for="(header, index) in headers" :class="dimensions[index]">
                <p class="text-center">{{header}}</p>
            </div>
        </div>
        <div>
            <slot />
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    headers: {
        type: Array,
        default() {
            return []
        }
    },
    dimensions: {
        type: Array,
        default() {
            return []
        }
    },
    dataOrder: {
        type: Array,
        default() {
            return []
        }
    },
    data: {
        type: Array,
        default() {
            return []
        }
    }
})
</script>

<style scoped>

</style>
