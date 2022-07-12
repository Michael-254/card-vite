<template>
    <BreezeGuestLayout>

        <Head title="Reset Password" />

        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-success">
                <div class="card-header text-center">
                    <div class="flex justify-center">
                        <img :src="'../storage/logo.png'" alt="" class="w-16 h-16">
                    </div>
                </div>
                <div class="card-body">
                    <p class="login-box-msg text-green-700 font-bold">Reset your password</p>

                    <BreezeValidationErrors class="mb-2" />

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit">
                        <div class="input-group mb-3">
                            <input type="email" v-model="form.email"
                                class="form-control form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                placeholder="Email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope text-green-600"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" v-model="form.password"
                                class="form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                placeholder="Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock text-green-600"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" v-model="form.password_confirmation"
                                class="form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                placeholder=" Confirm Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock text-green-600"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-4">
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit"
                                    class="w-full text-xs bg-green-800 py-2 px-3 text-center uppercase rounded text-white font-bold hover:bg-green-600"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Reset Password
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>


    </BreezeGuestLayout>
</template>


<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>