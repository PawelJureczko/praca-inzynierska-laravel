<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :isSearch="false" :desc="type==='edit' ? (userData[0].first_name + ' ' +userData[0].last_name)  : 'Pojedyncza wiadomosc'"/>
        </div>
        <div>
            <div class="flex flex-col gap-4 max-h-[50vh] overflow-auto border border-dark_grey p-8" ref="messagesWrapperRef">
                <SingleMessage v-for="message in messages" :accountOwnerId="$page.props.auth.user.id" :message="message" :id="message.id" :receiverData="userData[0].first_name + ' ' +userData[0].last_name"/>
            </div>
        </div>
        <div class="w-full h-max bg-light_greyrounded-[8px] mt-4 relative">
            <TextField v-model="message" @enter="sendMessage" inputHeight="h-14" additionalInputClasses="pr-16" placeholder="Wpisz wiadomość..."/>
            <div class="absolute right-4 top-2 w-10 h-10 rounded-full bg-[#DDDDDD] flex items-center justify-center cursor-pointer transition-[background-color] xl:hover:bg-[#BBBBBB]" @click="sendMessage"><SendIcon /></div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import SingleMessage from "@/Components/Views/Messages/SingleMessage.vue";
import {onMounted, ref} from "vue";
import TextField from "@/Components/Inputs/TextField.vue";
import SendIcon from "@/Components/Icons/SendIcon.vue";

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

const messagesWrapperRef = ref(null);
const message = ref('');

function sendMessage() {
    alert(message.value)
}

onMounted(() => {
    //zawsze po zalogowaniu zescrlluj do dołu strony
    messagesWrapperRef.value.scrollTop =  messagesWrapperRef.value.scrollHeight - messagesWrapperRef.value.clientHeight;
})

</script>

<style scoped>

</style>
