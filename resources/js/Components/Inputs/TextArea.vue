<template>
    <div class="text_field relative flex flex-col w-full" :class="{'pointer-events-none': inactive}">
        <label :for="uuid">
            <p class="text-base" :class="{'opacity-50': inactive}">{{ label }}</p>
        </label>

        <textarea class="border  px-3 py-2.5 rounded-md text-base w-full bg-inputBox-background"
                :class="[isResizable ? 'resize' : 'resize-none', textAreaHeight, errorMessage!==undefined && errorMessage!=='' ? 'error border-general-error focus:outline-none' : 'border-inputBox-border ', minHeight, inactive &&  'opacity-50']"
                type="text"
                :rows="rows"
                :id="uuid"
                v-model="value"
                spellcheck="false"
                :resizable="true"
                  :placeholder="placeholder"
                @input="(event) => $emit('update:modelValue', event.target.value)"/>
        <p class="absolute text-[11px] leading-[14px] -bottom-[14px] text-general-error">{{ errorMessage }}</p>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {useMainStore} from "@/Store/mainStore.js";

const emits = defineEmits(['update:modelValue'])

const props = defineProps({
    id: {
        type: String,
        default: ''
    },
    label: {
        type: String,
        default: '',
    },
    errorName: {
        type: String,
        default: '',
    },
    hideError: {
        type: Boolean,
        default: false,
    },
    inactive: {
        type: Boolean,
        default: false
    },
    isResizable: {
        type: Boolean,
        default: false
    },
    minHeight: {
        type: String,
        default: ''
    },
    modelValue: {
        type: [String, Number],
        default: '',
    },
    placeholder: {
        type: String,
        default: ''
    },
    rows: {
        type: Number,
        default: 4,
    },
    textAreaHeight: {
        type: String,
        default: 'h-[66px]'
    }
})

let value = ref(props.modelValue);
let uuid = ref(props.id ? props.id : getUuid())
const store = useMainStore();

const errorMessage = computed(() => {
    let preparedError = ''
    if (store.getErrors[props.errorName]) {
        preparedError = typeof store.getErrors[props.errorName] === 'string' ? store.getErrors[props.errorName] : store.getErrors[props.errorName][0];

    }
    return store.getErrors[props.errorName] && store.getErrors[props.errorName].length ? preparedError : ''
})

function getUuid() {
    const randomNumber = Math.random();
    const hexString = (randomNumber).toString(16).slice(2, 17);
    return hexString
}

watch(() => props.modelValue, (val) => {
    value.value = val;
});


</script>

<style lang="scss" scoped>
.text_field {
  textarea {
    &:autofill {
      background-color: v-bind("preparedBgCP");
      opacity: 1;
      background-clip: text;
      -webkit-box-shadow: 0 0 0 30px #FFFFFF inset !important;
    }
  }
}
</style>
