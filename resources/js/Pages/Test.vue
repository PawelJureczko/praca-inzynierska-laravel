<template>
    <Layout>
        <Head>
            <title>Testowy page</title>
        </Head>
        <div class="">
            <p>{{testowy_props}}</p>
            <p @click="$inertia.visit(route('test.create'))">goto create</p>

            <p v-for="item in testdata" @click="$inertia.visit(route('test.edit', item.id))">
                {{item.id}} {{item['name']}} {{item['surname']}}
            </p>

        </div>
    </Layout>
</template>

<script setup>
import {Head} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";

const props = defineProps({
    testowy_props: {
        type: String,
        default: ''
    },
    testdata: {
        type: Array,
        default() {
            return []
        }
    }
})

</script>

<style scoped>

</style>
