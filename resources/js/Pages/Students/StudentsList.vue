<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
    <p>Lista uczniów</p>
</Layout>
</template>

<script>
import Layout from "@/Layouts/Layout.vue";
export default {
    name: "StudentsList",
    components: {Layout}
}
</script>

<style scoped>

</style>
