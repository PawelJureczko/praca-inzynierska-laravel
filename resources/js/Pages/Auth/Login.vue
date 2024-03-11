<script setup>
import {Head, router} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import TextField from "@/Components/Inputs/TextField.vue";
import Btn from "@/Components/Buttons/Btn.vue";
import Checkbox from "@/Components/Inputs/Checkbox.vue";
import BorderBottomBtn from "@/Components/Buttons/BorderBottomBtn.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import {ref} from "vue";
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

function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('login'), form.value)
            .then(response => {
                if (response.data.status === 'ok') {
                    router.visit(route('dashboard'));
                }
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

                <div class="flex items-center justify-end mt-4">
                    <BorderBottomBtn v-if="canResetPassword" desc="Przypomnij hasło" @click="$inertia.visit(route('password.request'))"/>

                    <Btn btnType="primary" class="ml-4" @click="submit" :isLoader="isBtnLoader">
                        Zaloguj
                    </Btn>
                </div>
        </div>
    </Layout>
</template>

<styles scoped>

</styles>
