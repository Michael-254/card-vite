<template>
    <BreezeGuestLayout>

        <Head title="Forgot Password" />

         <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-success">
                <div class="card-header text-center">
                    <div class="flex justify-center">
                        <img :src="'../storage/logo.png'" alt="" class="w-16 h-16">
                    </div>
                </div>
                <div class="card-body">
                    <p class="login-box-msg text-green-700 text-sm">You forgot your password? Here you can easily retrieve a new password.</p>

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

                        <div class="row">
                            <div class="col-12">
                                <button type="submit"
                                    class="w-full text-xs bg-green-800 py-2 px-3 text-center uppercase rounded text-white font-bold hover:bg-green-600"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Send Password Resent Link
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <p class="mb-1 pt-4">
                        <Link v-if="canLogin" :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">Login</Link>
                    </p>
                    <p class="mb-1">
                        <Link v-if="canRegister" :href="route('register')" class="underline text-sm text-gray-600 hover:text-gray-900">Register a new membership</Link>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
    </BreezeGuestLayout>
</template>

<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
    canLogin: Boolean,
    canRegister: Boolean,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>
