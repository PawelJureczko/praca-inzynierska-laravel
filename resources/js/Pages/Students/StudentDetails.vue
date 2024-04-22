<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :desc="studentData.first_name + ' ' + studentData.last_name" :isSearch="false"/>
            <Btn class="w-max" @click="router.visit(route('messages.single', {id: props.studentData.id}))">Otwórz chat</Btn>
        </div>

        <SubtitleComponent desc="Dane ucznia:" class="mt-12 "/>
        <div class="border border-dark_grey rounded-lg" >
            <div class="p-4 border-b border-dark_grey last-of-type:border-transparent" v-for="item in preparedStudentData">
                <span class="text-[12px] leading-[14px] font-bold">{{item.desc}}:</span>
                <p>
                    {{item.value}}
                </p>
            </div>
        </div>

        <SubtitleComponent desc="Historia zajęć:" class="mt-12 "/>
        <div class="border border-dark_grey rounded-lg p-4 flex flex-col gap-4" >
            <div class="px-4 py-2 border rounded-[4px] flex" :class="[(lesson.canceled_by_teacher === 1 || lesson.canceled_by_student === 1 ? 'border-general-error bg-red-50' : 'border-[green] bg-green-50')]" v-for="lesson in studentLessons">
                <div class="w-[15%]">
                    <span class="text-[12px] leading-[14px] font-bold">Termin lekcji:</span>
                    <p>{{lesson.date.split('-').reverse().join('.')}}</p>
                </div>
                <div class="w-[15%]">
                    <span class="text-[12px] leading-[14px] font-bold">Godziny lekcji:</span>
                    <p>{{lesson.classes_time_start.slice(0, 5)}} - {{lesson.classes_time_end.slice(0, 5)}}</p>
                </div>
                <div class="w-[60%]">
                    <template v-if="(lesson.canceled_by_teacher === 1)">
                        <span class="text-[12px] leading-[14px] font-bold">Lekcja odwołana przez:</span>
                        <p>Nauczyciela</p>
                    </template>
                    <template v-else-if="lesson.canceled_by_student === 1">
                        <span class="text-[12px] leading-[14px] font-bold">Lekcja odwołana przez:</span>
                        <p>Ucznia</p>
                    </template>
                    <template v-else>
                        <span class="text-[12px] leading-[14px] font-bold">Temat:</span>
                        <p>{{lesson.topic}}</p>
                    </template>
                </div>
                <div class="w-[10%]">
                    <Btn @click="$inertia.visit(route('lesson.edit', {id: lesson.id}))">Szczegóły</Btn>
                </div>
            </div>
        </div>
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
    studentLessons: {
        type: Array,
        default() {
            return {

            }
        }
    },
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
