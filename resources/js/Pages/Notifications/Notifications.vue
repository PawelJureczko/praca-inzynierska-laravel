<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <p>Powiadomienia</p>
        <div>
            <p v-for="invitation in invitations" @click="accept(invitation.id)">
                {{ invitation }}
            </p>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useMainStore} from "@/Store/mainStore.js";

const store = useMainStore();

const props = defineProps({
    invitations: {
        type: Array,
        default: null
    }
})

function accept(id) {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        axios.put(route('notification.accept'), {
            id: id
        })
            .then(response => {
                // Obsługa odpowiedzi z backendu
                console.log(response.data);
            })
            .catch(error => {
                // Obsługa błędu
                // console.error(error);
            }).finally(() => {
                store.setIsLock(false);
        })
    }
}
</script>

<style scoped>

</style>
