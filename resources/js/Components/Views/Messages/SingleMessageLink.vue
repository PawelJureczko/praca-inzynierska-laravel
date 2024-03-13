<template>
    <div class="single_message_link p-4 border border-dark_grey rounded-lg cursor-pointer" :class="[(message.read_at===null && message.user_type === 'receiver') && 'unread']" @click="handleLinkClicked">
        <div>
            <p class="font-bold">{{message.last_name}} {{message.first_name}}</p>
        </div>
        <div class="pl-4 flex justify-between items-center">
            <div class="w-[calc(100%_-_125px)]">
                <p><span v-if="message.user_type === 'sender'">Ty: </span>{{message.content}} </p>
            </div>
            <div class="w-[125px] flex justify-end">
                <p class="text-[12px] leading-[16px]">{{getStringFromDate(message.created_at)}}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import {getStringFromDate} from "@/Helpers/helpers.js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    message: {
        type: Object,
        default() {
            return {
            }
        }
    }
})

function handleLinkClicked() {
    router.visit(route('messages.single', {id: props.message.id}))
}
</script>

<style scoped lang="scss">
    .single_message_link {
        &.unread {
            background: linear-gradient(90deg, rgba(242,213,189,1) 0%, rgba(255,186,128,1) 50%, rgba(242,213,189,1) 100%);
        }

        @media screen and (min-width: 1366px) {
            &:hover {
                &:not(&.unread) {
                    background: linear-gradient(90deg, rgba(249,236,226,1) 0%, rgba(255,223,196,1) 50%, rgba(249,236,226,1) 100%);
                }
            }
        }
    }
</style>
