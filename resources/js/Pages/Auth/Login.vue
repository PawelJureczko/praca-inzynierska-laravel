<script setup>
import {Head, router} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import TextField from "@/Components/Inputs/TextField.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import Checkbox from "@/Components/Inputs/Checkbox.vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import {onMounted, ref} from "vue";
import {useMainStore} from "@/Store/mainStore.js";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const store = useMainStore();

const form = ref({
    email: '',
    password: '',
    remember: false,
})

const isBtnLoader = ref(false);
const isLoginBtnLoader = ref(false);

function handleRegisterButtonClicked() {
    router.visit(route('register'))
    isBtnLoader.value = true;
}
function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isLoginBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('login'), form.value)
            .then(response => {
                if (response.data.status === 'ok') {
                    router.visit(route('schedule.index'));
                }
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
            isLoginBtnLoader.value = false;
        })}
}

onMounted(() => {
    store.clearErrors();
})
</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="max-w-[500px] mx-auto">
            <TitleComponent desc="Logowanie" :isSearch="false" />
                <div>
                    <TextField
                        type="email"
                        errorName="email"
                        v-model="form.email"
                        label="Adres email"
                        />

                </div>

                <div class="mt-4">
                    <TextField
                        type="password"
                        errorName="password"
                        v-model="form.password"
                        label="Hasło"/>
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox v-model="form.remember" label="Zapamiętaj mnie"/>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Btn btnType="secondary" class="w-max" @click="handleRegisterButtonClicked" :isLoader="isBtnLoader">
                        Zarejestruj się
                    </Btn>
                    <div class="flex items-center ">
                        <BorderBottomBtn v-if="canResetPassword" desc="Przypomnij hasło" @click="$inertia.visit(route('password.request'))"/>

                        <Btn btnType="primary" class="ml-4 w-max" @click="submit" :isLoader="isLoginBtnLoader">
                            Zaloguj
                        </Btn>
                    </div>
                </div>
        </div>
    </Layout>
</template>

<styles scoped>

</styles>
