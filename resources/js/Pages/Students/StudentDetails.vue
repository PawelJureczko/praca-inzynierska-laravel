<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :desc="studentData.first_name + ' ' + studentData.last_name" :isSearch="false"/>
            <Btn class="w-max" @click="router.visit(route('messages.single', {id: props.studentData.id}))">Otwórz chat</Btn>
        </div>

        <SubtitleComponent desc="Dane ucznia:" class="mt-12 "/>
        <div class="border border-main rounded-lg" >
            <div class="p-4 border-b border-main last-of-type:border-transparent" v-for="item in preparedStudentData">
                <span class="text-[12px] leading-[14px] font-bold">{{item.desc}}:</span>
                <p>
                    {{item.value}}
                </p>
            </div>
        </div>

        <p>@TODO historia zajęć</p>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import {router} from "@inertiajs/vue3";
import Btn from "@/Components/Buttons/Btn.vue";
import {getStringFromDate} from "../../Helpers/helpers.js";
import SubtitleComponent from "@/Components/Views/SubtitleComponent.vue";

const props = defineProps({
    studentData: {
        type: Object,
        default() {
            return {

            }
        }
    }
})

const preparedStudentData = [
    {
        desc: 'Imię',
        value: props.studentData.first_name
    },
    {
        desc: 'Nazwisko',
        value: props.studentData.last_name
    },
    {
        desc: 'Email',
        value: props.studentData.email
    },
    {
        desc: 'Numer telefonu',
        value: props.studentData.phone_number
    },
    {
        desc: 'Data stworzenia konta:',
        value: getStringFromDate(new Date(props.studentData.created_at)).split(', ')[0]
    },

]
</script>

<style scoped>

</style>
