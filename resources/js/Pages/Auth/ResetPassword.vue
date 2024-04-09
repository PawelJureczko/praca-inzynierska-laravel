<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import TextField from "@/Components/Inputs/TextField.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import {useMainStore} from "@/Store/mainStore.js";
import {ref} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = ref({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

// const submit = () => {
//     form.post(route('password.store'), {
//         onFinish: () => form.reset('password', 'password_confirmation'),
//     });
// };
const isBtnLoader = ref(false);
const store = useMainStore();
function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('password.store'), form.value)
            .then(response => {
                router.visit(route('login'));
                store.showSnackbar('Hasło zmienione pomyślnie!', 'success', 10000);
                // if (response.data.status === 'ok') {
                // }
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
}
</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null">
        <div class="max-w-[500px] mx-auto">
            <TitleComponent desc="Zmiana hasła" :isSearch="false" />
            <div>
                <TextField
                    type="text"
                    errorName="email"
                    v-model="form.email"
                    label="Email"
                />
            </div>
            <input type="password" class="w-0 h-0 p-0 absolute pointer-events-none opacity-0" tabindex="-1"/>
            <div class="mt-4">
                <TextField
                    type="password"
                    errorName="password"
                    v-model="form.password"
                    label="Nowe hasło"/>
            </div>
            <div class="mt-4">
                <TextField
                    type="password"
                    errorName="password_confirmation"
                    v-model="form.password_confirmation"
                    label="Powtórz nowe hasło"/>
            </div>
            <Btn btnType="primary" class="w-max mt-8" @click="submit" :isLoader="isBtnLoader">
                Zmień hasło
            </Btn>
        </div>
    </Layout>

<!--    <GuestLayout>-->
<!--        <Head title="Reset Password" />-->

<!--        <form @submit.prevent="submit">-->
<!--            <div>-->
<!--                <InputLabel for="email" value="Email" />-->

<!--                <TextInput-->
<!--                    id="email"-->
<!--                    type="email"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.email"-->
<!--                    required-->
<!--                    autofocus-->
<!--                    autocomplete="username"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.email" />-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="password" value="Password" />-->

<!--                <TextInput-->
<!--                    id="password"-->
<!--                    type="password"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.password"-->
<!--                    required-->
<!--                    autocomplete="new-password"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.password" />-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="password_confirmation" value="Confirm Password" />-->

<!--                <TextInput-->
<!--                    id="password_confirmation"-->
<!--                    type="password"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.password_confirmation"-->
<!--                    required-->
<!--                    autocomplete="new-password"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.password_confirmation" />-->
<!--            </div>-->

<!--            <div class="flex items-center justify-end mt-4">-->
<!--                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">-->
<!--                    Reset Password-->
<!--                </PrimaryButton>-->
<!--            </div>-->
<!--        </form>-->
<!--    </GuestLayout>-->
</template>
