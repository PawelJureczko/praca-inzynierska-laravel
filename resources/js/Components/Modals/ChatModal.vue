<script setup>
import ModalWrapper from "@/Components/Modals/ModalWrapper.vue";
import EmptyStateView from "@/Components/Views/EmptyStateView.vue";

const props = defineProps({
    chosenInterlocutor: {
        type: Object,
        default() {
            return {

            }
        }
    },
    users: {
        type: Array,
        default() {
            return []
        }
    }
})
</script>

<template>
    <ModalWrapper >
        <div class="w-[420px] max-w-[calc(100%_-_32px)] h-[50vh] overflow-y-auto overflow-x-hidden" :class="[users.length===0 && 'block mx-auto w-max']">
            <p v-for="user in users" @click="$emit('userPicked', user)" class="w-full p-4 cursor-pointer xl:hover:bg-[#ffcda3]  rounded-lg">
                {{user.first_name}} {{user.last_name}}
            </p>
            <EmptyStateView desc="Brak dostępnych rozmówców" v-if="users.length===0" />
        </div>
    </ModalWrapper>
</template>

<style scoped>

</style>
