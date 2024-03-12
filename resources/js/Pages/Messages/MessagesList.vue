<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :isSearch="false" desc="Wiadomości"/>
            <Btn>Nowa konwersacja</Btn>
        </div>
        <div class="flex flex-col gap-4">
            <div v-for="message in usersWithMessage" :key="message.id">
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

const props = defineProps({
    usersWithMessage: {
        type: Array,
        default() {
            return []
        }
    }
})
</script>

<style scoped>

</style>
