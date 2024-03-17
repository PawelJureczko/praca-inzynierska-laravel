<template>
    <Layout :user="$page.props.auth.user" :isLogged="$page.props.auth.user!==null" :userType="$page.props.auth.user.role">
        <TitleComponent desc="Mój plan" />
        <ScheduleTable />
    </Layout>
</template>

<script setup>

import Layout from "@/Layouts/Layout.vue";
import ScheduleTable from "@/Components/Views/Schedule/ScheduleTable.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
</script>

<style scoped>

</style>
