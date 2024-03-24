<template>
    <div class="relative w-full">
        <VueDatePicker
            v-model="value"
            :enable-time-picker="enableTimePicker"
            :min-date="minDate"
            :time-picker="timePicker"
            ref="datepicker"
            @open="isOpen=true"
            @closed="isOpen=false"
        />
        <div class="border border-dark_grey relative rounded-md" :class="[store.getErrors[errorName] && 'border-general-error']">
            <div @click="handleDatepickerClicked" class="flex items-center gap-2 px-3 py-2 min-h-[44px]">
                <ClockIcon v-if="timePicker"/>
                <CalendarIcon v-else/>
                <p v-if="!value">{{timePicker ? 'Wybierz godzinę' : 'Wybierz datę'}}</p>
                <template v-else>
                    <p v-if="timePicker">{{(value.hours<10?'0':'')+(value.hours)}}:{{(value.minutes<10?'0':'')+(value.minutes)}}</p>
                    <p v-else>{{getStringFromDate(value).split(', ')[0]}}</p>
                </template>
            </div>
            <div class="absolute w-full h-full bg-transparent inset-0" v-if="isOpen"></div>
            <p class="absolute text-[11px] leading-[14px] -bottom-[14px] text-general-error whitespace-nowrap">{{ store.getErrors[errorName] }}</p>
        </div>
    </div>
</template>

<script setup>
import {getStringFromDate} from "@/Helpers/helpers.js";
import {ref} from "vue";
import ClockIcon from "@/Components/Icons/ClockIcon.vue";
import CalendarIcon from "@/Components/Icons/CalendarIcon.vue";
import {useMainStore} from "@/Store/mainStore.js";

const value = defineModel();
const datepicker = ref(null);
const store = useMainStore();

const props = defineProps({
    enableTimePicker: {
        type: Boolean,
        default: false,
    },
    errorName: {
        type: String,
        default: ''
    },
    minDate: {
        type: Date,
        default() {
            return new Date()
        }
    },
    timePicker: {
        type: Boolean,
        default: false
    }
})

function handleDatepickerClicked() {
    if (isOpen.value) {
        datepicker.value.closeMenu();
    } else {
        datepicker.value.openMenu();
    }
}

const isOpen = ref(false);

</script>

<style lang="scss" scoped>
:deep(.dp__main) {
    max-width: 1px;
    position: absolute;
    top: 40px;
    left: 50%;

    .dp__input_wrap {
        max-width: 1px;
        height: 1px;
        padding: 0;
        opacity: 0;

        .dp__clear_icon {
            display: none;
        }

        .dp__input_icon_pad {
            padding-inline-start: 0;
        }

        .dp__pointer {
            height: 1px;
            font-size: 0;
            padding: 0;
        }
        .dp__icon {
            width: 0;
            height: 0;
            padding: 0;
        }
    }
}
</style>
