<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import {ref} from "vue";


const props = defineProps({
    lessonData: {
        type: Object,
        default() {
            return null
        }
    },
    scheduleData: {
        type: Object,
        default() {
            return null
        }
    },
})

const type = (props.lessonData===null ? 'new' : 'edit');

</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <TitleComponent :desc="type==='edit' ? 'Edytuj lekcje' : 'Stwórz lekcje'" :isSearch="false"/>

        <p>dodac jakis middleware do sprawdzania czy na pewno lekcja jest miedzy tym nauczycielem i uczniem</p>
    </Layout>
</template>

<style scoped>

</style>
