<template>
    <div class="flex gap-4 items-center px-4 py-2 border-b border-dark_grey w-max">
        <div v-for="link in links" class="relative" :class="[link.type === type && `after:content-[''] after:absolute after:w-full after:h-[2px] after:bg-main after:-bottom-[9px] after:left-0`]">

            <Link class="font-semibold text-[16px] leading-[20px]" :href="route(link.route)">{{link.desc}}</Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    current: {
        type: String,
        default: ''
    },
    links: {
        type: Array,
        default() {
            return []
        }
    },
    type: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>

</style>
