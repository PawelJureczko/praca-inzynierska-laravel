<template>
    <div class="w-[calc(100%_-_32px)] max-w-[500px] mx-auto flex flex-col gap-4">
        <SubtitleComponent desc="Zmiana hasła" />

        <input type="password" tabindex="-1" class="hidden"/>
        <TextField
            v-model="userForm.current_password"
            type="password"
            label="Hasło"
            errorName="current_password"
        />
        <TextField
            v-model="userForm.password"
            type="password"
            label="Nowe hasło"
            errorName="password"
        />
        <TextField
            v-model="userForm.password_confirmation"
            type="password"
            label="Powtórz hasło"
            errorName="password_repeat"
        />

        <div>
            <Btn class="mx-auto mt-6 w-[calc(100%_-_64px)]" btnType="primary" @click="submit" :isLoader="isBtnLoader">
                Zmień hasło
            </Btn>
        </div>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextField from "@/Components/Inputs/TextField.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";
import SubtitleComponent from "@/Components/Views/SubtitleComponent.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const userForm = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const store = useMainStore()
const isBtnLoader = ref(false);

function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.put(route('password.update'), userForm.value)
            .then(response => {
                store.showSnackbar(response.data.message, 'success');
                userForm.value = {
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                }
            })
            .catch(error => {
                // Obsługa błędu
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    console.log(error)
                }
            }).finally(() => {
            store.setIsLock(false);
            isBtnLoader.value = false;
        })
    }
}
</script>
