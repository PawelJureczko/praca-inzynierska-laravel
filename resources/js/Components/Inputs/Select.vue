<template>
    <div class="dropdown relative h-max" :class="{'flex items-center gap-4': inline }">
        <p class="text-base" :class="{'w-max whitespace-nowrap': inline}">{{ label }}</p>

        <div class="relative">
<!--            <p class="placeholder_desc absolute left-4 text-flotte-grey500 pointer-events-none top-1/2 -translate-y-1/2" :class="{'opacity-50 pointer-events-none': inactive}" v-if="!value || value===''">{{placeholder}}</p>-->
            <select class="border border-dark_grey p-2 rounded-md text-flotte-text w-full px-4 cursor-pointer h-[44px] bg-transparent" :class="{'error border-general-error focus:border-general-error focus:outline-none active:border-general-error active:outline-none': store.getErrors[errorName]!==undefined && store.getErrors[errorName]!=='', 'opacity-50 pointer-events-none': inactive}" :id="uuid" v-model="value"
                    @change="(event) => $emit('update:modelValue', event.target.value)">
                <option v-if="modelValue==null" :value="null" disabled>{{placeholder}}</option>
                <option v-if="modelValue==''" :value="''" disabled>{{placeholder}}</option>
                <template v-if="typeof options[0] === 'string'">
                    <option v-for="(value, key) in options" :value="keyAsValue ? key : value">{{ value }}</option>
                </template>
                <template v-else>
                    <option v-for="item in options" :value="item.key">{{ item.value }}</option>
                </template>
            </select>
        </div>

        <p class="absolute text-[11px] leading-[14px] -bottom-[14px] text-general-error whitespace-nowrap">{{ store.getErrors[errorName] }}</p>
    </div>
</template>

<script setup>
import {onMounted, ref, watch} from "vue";
import {useMainStore} from "@/Store/mainStore.js";

const store = useMainStore();

const props = defineProps({
    id: {
        type: String,
        default: ''
    },
    label: {
        type: String,
        default: ''
    },
    errorName: {
        type: String,
        default: ''
    },
    inactive: {
        type: Boolean,
        default: false
    },
    inline: {
        type: Boolean,
        default: false,
    },
    keyAsValue: {
        type: Boolean,
        default: true
    },
    modelValue: {
        type: [String, Number],
        default: '',
    },
    options: {
        type: Array,
        default() {
            return []
        }
    },
    placeholder: {
        type: String,
        default: 'no value selected'
    },
})

const value = ref(props.modelValue);
let uuid = ref(props.id ? props.id : getUuid())


function getUuid() {
    const randomNumber = Math.random();
    const hexString = (randomNumber).toString(16).slice(2, 17);
    return hexString
}

onMounted(() => {
    console.log(props.modelValue)
})

watch(() => props.modelValue, (val) => {
    value.value = val;
});

</script>

<style lang="scss" scoped>
    .dropdown {
        .placeholder_desc {
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          max-width: calc(100% - 32px);
        }
    }
</style>
