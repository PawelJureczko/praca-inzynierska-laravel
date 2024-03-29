<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :isSearch="false" desc="Wiadomości"/>
            <Btn class="w-max" @click="router.visit(route('messages.new'))">Nowa konwersacja</Btn>
        </div>
        <div class="flex flex-col gap-4">
            <div v-if="Object.values(usersWithMessage).length===0">
                <EmptyStateView desc="Brak wiadomości."/>
            </div>
            <div v-for="message in Object.values(usersWithMessage)" :key="message.id">
                <SingleMessageLink :message="message" />
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import SingleMessageLink from "@/Components/Views/Messages/SingleMessageLink.vue";
import {onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";
import EmptyStateView from "@/Components/Views/EmptyStateView.vue";

const props = defineProps({
    usersWithMessage: {
        type: Object,
        default() {
            return {

            }
        }
    }
})

</script>

<style scoped>

</style>
