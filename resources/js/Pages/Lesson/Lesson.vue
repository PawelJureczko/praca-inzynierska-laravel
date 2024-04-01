<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {router} from "@inertiajs/vue3";
import {useMainStore} from "@/Store/mainStore.js";
import {ref} from "vue";


const store = useMainStore();
const isBtnLoader = ref(false);


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


function save() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('lesson.save'), {

        })
            .then(response => {
                if (response.data.status === 'ok') {
                    router.visit(route('schedule.index'));
                }
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response.data.errors)
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    console.log(error)
                }
            }).finally(() => {
            store.setIsLock(false);
            isBtnLoader.value=false;
        })}

    console.log('test');
    //lesson.save
}

</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <TitleComponent :desc="type==='edit' ? 'Edytuj lekcje' : 'Stwórz lekcje'" :isSearch="false"/>
        <div>
        </div>
        <p>{{lessonData}}</p>
        <p>{{scheduleData}}</p>
        <Btn @click="save">Zapisz</Btn>
    </Layout>
</template>

<style scoped>

</style>
