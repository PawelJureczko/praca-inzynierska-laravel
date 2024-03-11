<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Link, router, useForm, usePage} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import TextField from "@/Components/Inputs/TextField.vue";
import {ref} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const userForm = ref({
    first_name: user.first_name,
    last_name: user.last_name,
    phone_number: user.phone_number,
    email: user.email,
})

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    phone_number: user.phone_number,
    email: user.email,
});

const isBtnLoader = ref(false);
const store = useMainStore();

function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.patch(route('profile.update'), userForm.value)
            .then(response => {
                store.showSnackbar(response.data.message, 'success');
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response.data.errors)
                store.setErrors(error.response.data.errors);
            }).finally(() => {
            store.setIsLock(false);
            isBtnLoader.value=false;
        })}
}
</script>

<template>
        <div class="w-[calc(100%_-_32px)] max-w-[500px] mx-auto flex flex-col gap-4">
            <TextField
                v-model="userForm.first_name"
                type="text"
                label="Imię"
                errorName="first_name"
            />
            <TextField
                v-model="userForm.last_name"
                type="text"
                label="Nazwisko"
                errorName="last_name"
            />
            <TextField
                v-model="userForm.email"
                type="email"
                label="Email"
                errorName="email"
            />
            <TextField
                v-model="userForm.phone_number"
                type="phone"
                label="Numer telefonu"
                errorName="phone_number"
            />

            <div>
                <Btn class="mx-auto mt-6 w-[calc(100%_-_64px)]" btnType="primary" @click="submit" :isLoader="isBtnLoader">
                    Potwierdź
                </Btn>
            </div>
        </div>
</template>
