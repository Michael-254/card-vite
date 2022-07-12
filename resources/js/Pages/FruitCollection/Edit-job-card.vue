<template>

    <Head title="Operational Planning" />

    <BreezeAuthenticatedLayout>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                Job Card for Mukau Production
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">job-card
                                </li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid text-sm">
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-12 connectedSortable">
                            <!-- start your code here -->
                            <div class="py-6">
                                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div class="p-4 bg-white border-b border-gray-200">

                                            <div class="card-header flex justify-between">
                                                <h3 class="card-title">Job Card Review</h3>
                                                <p>Card No: {{ Jobcard.job_card_number }}</p>
                                                <p>Site: {{ Jobcard.site }}</p>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                
                                                <form @submit.prevent="update(Jobcard.id)">
                                                    <div class="col-sm-12 pt-8 ">
                                                        <div class="form-group">
                                                            <label>{{ Jobcard.childactivity.child_title }} Start Date:</label>
                                                            <Datepicker v-model="form.start_date" position="left" altPosition></Datepicker>
                                                            <p class="text-xs text-red-600 mt-2" v-if="form.errors.start_date">
                                                                {{ form.errors.start_date }}
                                                            </p>
                                                        </div>

                                                        <div class="flex flex-col justify-between">
                                                          
                                                            <div class="flex justify-center">
                                                                <div class="block bg-white max-w-sm text-center">
                                                                    <div class="py-2 px-6 border-b border-gray-300">
                                                                        Signature
                                                                    </div>
                                                                    <p class="text-xs text-red-600 mt-2"
                                                                        v-if="form.errors.signature">{{
                                                                                form.errors.signature
                                                                        }}</p>
                                                                    <div class="p-6">
                                                                        <p class="text-gray-700 text-xs mb-4">
                                                                            Sign to confirm below that you approve
                                                                            document
                                                                        </p>
                                                                        <div v-for="$role in Jobcard.childactivity.roles"
                                                                            :key="$role.id"
                                                                            class="flex items-center space-x-1">
                                                                            <input type="checkbox"
                                                                                v-model="form.signature"
                                                                                :value="$role.id"
                                                                                class="text-green-600 rounded-md focus:ring-0">
                                                                            <label class="mt-2 text-sm font-bold">{{
                                                                                    $role.role
                                                                            }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="flex justify-end mt-4">
                                                            <button
                                                                class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Save</button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- /.Left col -->

                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Record from '@/Components/Record.vue'
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
export default {
    components: { BreezeAuthenticatedLayout, Head, Link, Datepicker, Record },
    props: {
        Jobcard: Object,
        Signed: Object,
        BeginDate: String
    },
    data() {
        return {
            form: this.$inertia.form({
                start_date:  this.$props.BeginDate,
                signature: this.$props.Signed,
            }),
        }
    },
    methods: {
        update(id) {
            this.form.patch(`/Fruit-collection/sign/activity/${id}`, { preserveScroll: true });
        }
    }
}
</script>