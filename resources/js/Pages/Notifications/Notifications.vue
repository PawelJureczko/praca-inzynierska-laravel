<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <TitleComponent :desc="pageType[type].title" v-model="searchValue" :isSearch="false"/>
        <TabComponent class="ml-auto mr-0" :links="links" :type="type"/>
        <CustomTable class="mt-6" :headers="pageType[type].headers" :dimensions="pageType[type].dimensions">
            <div v-for="(invitation, index) in invitations" class="p-4 flex flex-col gap-2 lg:gap-0 lg:flex-row lg:items-center" :class="[index%2===0 ? 'bg-[#f7f6f2]' : 'bg-[#FFFFFF]']">
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[0]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[0]}}: </span>{{  getStringFromDate(new Date(invitation.created_at))}}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[1]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[1]}}: </span>{{ invitation.teacher.first_name }}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[2]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[2]}}: </span>{{ invitation.teacher.last_name }}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[3]">
                    <a :href="'mailto:'+invitation.teacher.email" class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[3]}}: </span>{{ invitation.teacher.email }}</a>
                </div>
                <div class="flex xl:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[5]" >
                    <Btn class="min-w-max" btnType="primary" @click="accept(invitation.id)" v-if="type!=='invited'">{{pageType[type].btnDesc}}</Btn>
                </div>
            </div>
        </CustomTable>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useMainStore} from "@/Store/mainStore.js";
import {ref} from "vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import CustomTable from "@/Components/Universal/CustomTable.vue";
import {getStringFromDate} from "@/Helpers/helpers.js";
import Btn from "@/Components/Buttons/Btn.vue";
import TabComponent from "@/Components/Views/TabComponent.vue";

const store = useMainStore();
const searchValue = ref('');

const props = defineProps({
    invitations: {
        type: Array,
        default: null
    },
    type: {
        type: String,
        default: 'invitations'
    }
})

const pageType = {
    'invitations': {
        'title': 'Otrzymane zaproszenia',
        'btnDesc': 'Zaakceptuj',
        'headers': ['Otrzymano', 'Imię nauczyciela', 'Nazwisko nauczyciela', 'Adres email', ''],
        'dimensions': ['lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[25%]', 'lg:w-[15%]'],
    },
    'homeworks': {
        'title': 'Zadania domowe',
        'btnDesc': 'Zaakceptuj',
        'headers': ['Otrzymano', 'Imię nauczyciela', 'Nazwisko nauczyciela', 'Adres email', ''],
        'dimensions': ['lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[25%]', 'lg:w-[15%]'],
    }
}

const links = [
    {
        desc: 'Otrzymane zaproszenia',
        route: 'notifications.invitations',
        type: 'invitations'
    },
    {
        desc: 'Zadania domowe TODO',
        route: 'notifications.homeworks',
        type: 'homeworks'
    },
]

const localInvitations = ref(props.invitations);

function accept(id) {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        axios.put(route('notification.accept'), {
            id: id
        })
            .then(response => {
                store.showSnackbar(response.data.message, 'success');
                localInvitations.value = response.data.invitations;
            })
            .catch(error => {
                store.showSnackbar(error.response.data.error, 'error');
                console.error(error.response.data.error);
            }).finally(() => {
                store.setIsLock(false);
        })
    }
}
</script>

<style scoped>

</style>
