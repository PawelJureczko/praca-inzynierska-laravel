<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <p>Powiadomienia</p>
        <div>
            <p v-for="invitation in localInvitations" @click="accept(invitation.id)" :key="invitation.id">
                {{ invitation }}
            </p>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useMainStore} from "@/Store/mainStore.js";
import {ref} from "vue";

const store = useMainStore();

const props = defineProps({
    invitations: {
        type: Array,
        default: null
    }
})

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
