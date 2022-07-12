<template>

    <Head title="Roles Management" />

    <BreezeAuthenticatedLayout>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                {{ route().current() }}
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">{{ route().current() }}
                                </li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content text-sm">
                <div class="container-fluid">
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-12 connectedSortable">
                            <!-- start your code here -->
                            <div class="py-6">
                                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div class="p-4 bg-white border-b border-gray-200">

                                            <div class="card card-success card-outline">
                                                <div class="card-body box-profile">
                                                    <h3 class="profile-username text-center">{{ User.name }}</h3>

                                                    <p class="text-muted text-center mt-1">{{ User.department }}
                                                        Department</p>

                                                    <form @submit.prevent="updateRoles(`${User.id}`)">
                                                        <ul class="list-group list-group-unbordered mb-3 mt-3">
                                                            <li class="list-group-item" v-for="$role in Roles"
                                                                :key="$role.id">
                                                                <b>{{ $role.role }}</b>
                                                                <a class="float-right">
                                                                    <input type="checkbox" :value="$role.id"
                                                                        v-model="form.checkedRoles"
                                                                        class="order-none focus:ring-0 focus:shadow-none ring-offset-0 text-green-500" />
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <button type="submit"
                                                            class="bg-green-500 hover:bg-green-700 text-white btn btn-block"
                                                            :class="{ 'opacity-25': form.processing }"
                                                            :disabled="form.processing">Save</button>
                                                    </form>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                            <!-- /.card -->

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
export default {
    components: { BreezeAuthenticatedLayout, Head, Link },
    props: {
        User: Object,
        Roles: Object,
        CurrentRoles: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                checkedRoles: []
            }),
        }
    },
    mounted() {
        this.form.checkedRoles = this.CurrentRoles;
    },
    methods: {
        updateRoles(id) {
            this.form.patch(`/update-roles-${id}`);
        }
    }

}
</script>