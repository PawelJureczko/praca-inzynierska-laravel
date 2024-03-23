<template>
    <div class="flex items-center justify-start gap-3 h-max">
        <VueDatePicker class="flex-shrink-0" v-model="date" :enable-time-picker="false"></VueDatePicker>
        <div class="flex items-center justify-start gap-3 border border-dark_grey px-4 py-2 rounded rounded-lg w-max">
            <div @click="$emit('iteratorClicked', 'dec')" class="w-8 h-8 flex items-center justify-center cursor-pointer hover:xl:bg-light_grey transition-[background-color] duration-300 rounded-[2px]">
                <ArrowHeadIcon/>
            </div>
            <div class="p-2 border border-light_grey">
                <p class="text-[11px] leading-[15px] whitespace-nowrap">{{ dates[0] }} - {{ dates[6] }}</p>
            </div>
            <div @click="$emit('iteratorClicked', 'inc')" class="w-8 h-8 flex items-center justify-center cursor-pointer hover:xl:bg-light_grey transition-[background-color] duration-300 rounded-[2px]">
                <ArrowHeadIcon class="rotate-180"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import ArrowHeadIcon from "@/Components/Icons/ArrowHeadIcon.vue";

const props = defineProps({
    dates: {
        type: Array,
        default() {
            return []
        }
    }
})

const emit = defineEmits([
    'dateChanged'
])

const date = ref(null);

watch(date, () => {
    emit('dateChanged', date);
})
</script>

<style lang="scss" scoped>
:deep(.dp__main) {
    max-width: 38px;

    .dp__input_wrap {
        max-width: 38px;
        height: 38px;
        padding: 0;

        .dp__clear_icon {
            display: none;
        }

        .dp__input_icon_pad {
            padding-inline-start: 0;
        }

        .dp__pointer {
            height: 38px;
            font-size: 0;
        }
    }
}
</style>
