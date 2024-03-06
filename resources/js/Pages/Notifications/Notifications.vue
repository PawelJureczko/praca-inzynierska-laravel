<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <p>Powiadomienia</p>

        <div>
            <p v-for="invitation in invitations">
                {{invitation}}
            </p>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";

const props = defineProps({
    invitations: {
        type: Array,
        default: null
    }
})
</script>

<style scoped>

</style>
