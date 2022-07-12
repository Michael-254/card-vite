<template>
    <BreezeGuestLayout>

        <Head title="Register" />

        <div class="max-w-4xl mx-auto sm:px-6 pt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="w-full">

                            <div class="flex justify-center">
                                <img :src="'../storage/logo.png'" alt="" class="w-16 h-16">
                            </div>

                            <div class="flex justify-center mt-2">
                                 <p class="login-box-msg text-green-700 font-bold">Sign up to create an account</p>
                            </div>

                            <BreezeValidationErrors class="mb-1" />

                            <div v-if="status" class="mb-2 font-medium text-sm text-green-600">
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit" class="pt-2">
                                <div class="pt-1 sm:mt-0">
                                    <div class="md:grid md:grid-cols-6 md:gap-6">
                                        <div class="md:col-span-3">
                                            <div class="input-group mb-3">
                                                <input type="text" v-model="form.name" id="name"
                                                    class="form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                                    placeholder="Job Title" autocomplete="name">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-user text-green-500"></span>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <input type="email" v-model="form.supervisor_email"
                                                    class="form-control form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                                    placeholder="Supervisor Email" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-envelope text-green-600"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="email" v-model="form.hod_email"
                                                    class="form-control form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                                    placeholder="HOD Email" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-envelope text-green-600"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md:col-span-3">
                                            <div class="input-group mb-3">
                                                <select type="email" v-model="form.site"
                                                    class="form-control form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                                    required>
                                                    <option value="" selected>-- Select Site --</option>
                                                    <option value="Nyongoro">Nyongoro</option>
                                                    <option value="Kiambere">Kiambere</option>
                                                    <option value="Dokolo">Dokolo</option>
                                                    <option value="7 Forks">7 Forks</option>
                                                    <option value="Head Office">Head Office</option>
                                                    <option value="Kampala">Kampala</option>
                                                    <option value="Tanzania">Tanzania</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-envelope text-green-600"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <select type="email" v-model="form.department"
                                                    class="form-control form-control w-full rounded-lg border-green-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus:ring-opacity-50"
                                                    required>
                                                    <option value="" selected>-- Select Dept --</option>
                                                    <option value="IT">IT</option>
                                                    <option value="Forestry">Forestry</option>
                                                    <option value="Operations">Operations</option>
                                                    <option value="Human Resources">Human Resources</option>
                                                    <option value="Operations">Operations</option>
                                                    <option value="Communications">Communications</option>
                                                    <option value="Finance">Finance</option>
                                                    <option value="ME">Monitoring and Evaluation</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-laptop text-green-600"></span>
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
                                        </div>
                                    </div>

                                    <div class="pt-3 flex justify-between">
                                        <Link :href="route('login')"
                                            class="underline text-sm text-gray-600 hover:text-gray-900">
                                        Already registered?
                                        </Link>
                                        <button type="submit"
                                            class="text-xs bg-green-800 py-2 px-3 text-center uppercase rounded text-white font-bold hover:bg-green-600"
                                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </BreezeGuestLayout>
</template>

<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    name: '',
    email: '',
    supervisor_email: '',
    hod_email: '',
    site: '',
    department: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
