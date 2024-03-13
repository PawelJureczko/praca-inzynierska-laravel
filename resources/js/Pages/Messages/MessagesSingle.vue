<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :isSearch="false" :desc="type==='edit' ? (userData[0].first_name + ' ' +userData[0].last_name)  : 'Pojedyncza wiadomosc'"/>
        </div>
        <div>
            <div class="flex flex-col gap-4 max-h-[50vh] overflow-auto border border-dark_grey p-8">
                <SingleMessage v-for="message in messages" :accountOwnerId="id" :message="message" :id="message.id" :receiverData="userData[0].first_name + ' ' +userData[0].last_name"/>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import SingleMessage from "@/Components/Views/Messages/SingleMessage.vue";

const props = defineProps({
    id: {
        type: String,
        default: ''
    },
    messages: {
        type: Array,
        default() {
            return []
        }
    },
    type: {
        type: String,
        default: ''
    },
    userData: {
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
