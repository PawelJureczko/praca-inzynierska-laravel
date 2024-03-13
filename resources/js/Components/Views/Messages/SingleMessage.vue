<template>
    <div class="w-full h-max p-4 rounded-lg max-w-[90%]" :class="[accountOwnerId == message.sender_id ? 'bg-messages-primaryBg mr-0 ml-auto' : 'bg-messages-secondaryBg mr-auto ml-0']">
        <p class="font-bold text-[14px] leading-[18px]">{{accountOwnerId == message.sender_id ? 'Ja' : receiverData}}</p>
        <p class="mt-4">
            {{message.content}}
        </p>
        <div class="w-max mr-0 ml-auto mt-4">
            <p class="text-[10px] leading-[14px]">{{getStringFromDate(new Date(message.created_at))}}</p>
        </div>
    </div>
</template>

<script setup>
import {getStringFromDate} from "@/Helpers/helpers.js";

const props = defineProps({
    accountOwnerId: {
        type: String,
        default: ''
    },
    message: {
        type: Object,
        default() {
            return {

            }
        }
    },
    receiverData: {
        type: String,
        default: ''
    }
})
</script>

<style scoped>

</style>
