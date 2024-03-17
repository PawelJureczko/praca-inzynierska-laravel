<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :isSearch="false" :desc="type==='edit' ? (userData[0].first_name + ' ' +userData[0].last_name)  : 'Pojedyncza wiadomosc'"/>
        </div>
        <div>
            <div class="flex flex-col gap-4 max-h-[50vh] overflow-auto border border-dark_grey p-8" ref="messagesWrapperRef">
                <SingleMessage v-for="message in localMessages" :accountOwnerId="$page.props.auth.user.id" :message="message" :id="message.id" :receiverData="userData[0].first_name + ' ' +userData[0].last_name"/>
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
import {onBeforeUnmount, onMounted, ref} from "vue";
import TextField from "@/Components/Inputs/TextField.vue";
import SendIcon from "@/Components/Icons/SendIcon.vue";
import {useMainStore} from "@/Store/mainStore.js";

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

const store = useMainStore();

const messagesWrapperRef = ref(null);
const message = ref('');
const localMessages = ref(props.messages);
const messageInterval = ref(null);

function sendMessage() {
    if (store.getIsLock === false && message.value!=='') {
        store.setIsLock(true);
        axios.post(route('messages.send'), {
            receiver_id: props.id,
            message: message.value
        })
            .then(response => {
                localMessages.value = [];
                localMessages.value = response.data.messages;
                message.value = '';
                setTimeout(() => {
                    scrollToMessagesEnd();
                }, 1)
            })
            .catch(error => {
                store.showSnackbar('Wystąpił niespodziewany błąd', 'error');
            }).finally(() => {
            store.setIsLock(false);
        });
    }
}

const reloadMessagesInterval = () => {
    setInterval(() => {
        axios.get(route('messages.reload'), {
            params: {
                id: props.id
            }
        })
            .then(response => {
                localMessages.value = response.data.messages;
            })
            .catch(error => {
                store.showSnackbar('Wystąpił niespodziewany błąd, odśwież stronę', 'error');
            }).finally(() => {
        });
    }, 3000)
}

function scrollToMessagesEnd() {
    messagesWrapperRef.value.scrollTop = (messagesWrapperRef.value.scrollHeight - messagesWrapperRef.value.clientHeight);
    console.log(messagesWrapperRef.value.scrollTop)
}

onMounted(() => {
    //zawsze po zalogowaniu zescrlluj do dołu strony
    scrollToMessagesEnd()
    messageInterval.value = setInterval(() => {
        axios.get(route('messages.reload'), {
            params: {
                id: props.id
            }
        })
            .then(response => {
                const prevMessagesLength = localMessages.value.length;
                localMessages.value = response.data.messages;

                if (prevMessagesLength!==response.data.messages.length) {
                    scrollToMessagesEnd()
                }

            })
            .catch(error => {
                store.showSnackbar('Wystąpił niespodziewany błąd, odśwież stronę', 'error');
            }).finally(() => {
        });
    }, 3000);
})

onBeforeUnmount(() => {
    clearInterval(messageInterval.value);
})

</script>

<style scoped>

</style>
