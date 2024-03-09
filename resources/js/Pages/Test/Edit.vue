<template>
    <p>Edit</p>
    <input v-model="form.name" />
    <input v-model="form.surname" />

    <button @click="update">update</button>
</template>

<script setup>
import {ref} from "vue";
import {useMainStore} from "@/Store/mainStore.js";
const store = useMainStore();

const props = defineProps({
    record: {
        type: Object
    }
})

const form = ref({
    name: props.record.name,
    surname: props.record.surname
})

function update() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        axios.put(route('test.update', props.record.id), form.value)
            .then(response => {
                // Obsługa odpowiedzi z backendu
                console.log(response.data);
            })
            .catch(error => {
                // Obsługa błędu
                console.error(error);
            }).finally(() => {
                store.setIsLock(false);
        });
    }
}
</script>

<style scoped>

</style>
