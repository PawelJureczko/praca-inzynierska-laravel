<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <TitleComponent :desc="title" v-model="searchValue"/>
        <TabComponent class="ml-auto mr-0" :links="links" :current="title" :type="type"/>
        <CustomTable class="mt-6" :headers="pageType[type].headers" :dimensions="pageType[type].dimensions">
            <template v-if="studentsCp.length>0">
                <div v-for="(student, index) in studentsCp" class="p-4 flex flex-col gap-2 lg:gap-0 lg:flex-row lg:items-center" :class="[index%2===0 ? 'bg-[#f7f6f2]' : 'bg-[#FFFFFF]']">
                    <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[0]">
                        <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[0]}}: </span>{{ student.id }}</p>
                    </div>
                    <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[1]">
                        <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[1]}}: </span>{{ student.first_name }}</p>
                    </div>
                    <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[2]">
                        <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[2]}}: </span>{{ student.last_name }}</p>
                    </div>
                    <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[3]">
                        <a :href="'mailto:'+student.email" class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[3]}}: </span>{{ student.email }}</a>
                    </div>
                    <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[4]">
                        <a :href="'tel:'+student.phone_number" class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{pageType[type].headers[4]}}: </span>{{ student.phone_number }}</a>
                    </div>
                    <div class="flex xl:justify-center xl:px-4 lg:py-2" :class="pageType[type].dimensions[5]" >
                        <Btn class="min-w-max" btnType="primary" @click="handleButtonClicked(student.id)" v-if="type!=='invited'" :isLoader="isBtnLoader && student.id === chosenStudentId">{{pageType[type].btnDesc}}</Btn>
                        <p class="text-[12px] xl:text-base leading-[16px]" v-if="type==='invited'"><span class="font-bold lg:hidden">{{pageType[type].headers[5]}}: </span>{{ getStringFromDate(new Date(student.created_at)) }}</p>
                    </div>
                </div>
            </template>
            <EmptyStateView class="mt-12" :desc="pageType[type].emptyStateDesc" v-else/>

        </CustomTable>
    </Layout>
</template>
<script setup>
import Layout from "@/Layouts/Layout.vue";
import {computed, ref} from "vue";
import {useMainStore} from "@/Store/mainStore.js";
import Btn from "@/Components/Buttons/Btn.vue";
import CustomTable from "@/Components/Universal/CustomTable.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import TabComponent from "@/Components/Views/TabComponent.vue";

import {getStringFromDate} from "@/Helpers/helpers.js";
import EmptyStateView from "@/Components/Views/EmptyStateView.vue";
import {router} from "@inertiajs/vue3";

const store = useMainStore();

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: ''
    },
    users: {
        type: Array,
        default() {
            return []
        }
    },
})

const searchValue = ref('');

const students = ref(props.users);

const isBtnLoader = ref(false);

const chosenStudentId = ref(null);

const pageType = {
    'other': {
        'dimensions': ['lg:w-[10%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[15%]', 'lg:w-[15%]'],
        'headers': ['LP', 'Imię', 'Nazwisko', 'Email', 'Numer telefonu', ''],
        'btnDesc': 'Zaproś',
        'emptyStateDesc': 'Brak wyników wyszukiwania'
    },
    'invited': {
        'dimensions': ['lg:w-[10%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[15%]', 'lg:w-[15%]'],
        'headers': ['LP', 'Imię', 'Nazwisko', 'Email', 'Numer telefonu', 'Data zaproszenia'],
        'btnDesc': 'Zaproś',
        'emptyStateDesc': 'Brak wysłanych zaproszeń'
    },
    'myGroup': {
        'dimensions': ['lg:w-[10%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[15%]', 'lg:w-[15%]'],
        'headers': ['LP', 'Imię', 'Nazwisko', 'Email', 'Numer telefonu', ''],
        'btnDesc': 'Szczegóły',
        'emptyStateDesc': 'Brak uczniów w grupie'
    }
}

function isIncluding(val) {
    return val.toLowerCase().includes(searchValue.value.toLowerCase());
}

const studentsCp = computed(() => {
    console.log(students.value)
    return students.value.filter(item => {
        return isIncluding(item.email) || isIncluding(item.first_name) || isIncluding(item.last_name) || isIncluding(item.phone_number)
    });
})

const links = [
    {
        desc: 'Moja grupa',
        route: 'students.index',
        type: 'myGroup'
    },
    {
        desc: 'Zaproszeni',
        route: 'students.invited',
        type: 'invited'
    },
    {
        desc: 'Pozostali',
        route: 'students.other',
        type: 'other'
    },
]

function handleButtonClicked(id) {
    if (props.type === 'other') {
        if (store.getIsLock === false) {
            store.setIsLock(true);
            axios.post(route('students.invite'), {
                student_id: id,
            })
                .then(response => {
                    // Obsługa sukcesu
                    store.showSnackbar(response.data.message, 'success');
                    students.value = response.data.students;

                })
                .catch(error => {
                    // Obsługa błędu
                    console.error(error);
                    store.showSnackbar(error.response.data.error, 'error');
                }).finally(() => {
                store.setIsLock(false);
            });
        }
    } else if (props.type === 'myGroup') {
        isBtnLoader.value=true;
        chosenStudentId.value = id;
        router.visit(route('student.details', {id: id}))
    }
}
</script>
