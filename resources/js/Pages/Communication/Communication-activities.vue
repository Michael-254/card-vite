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
                                Job Card
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">Job Card</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
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
                                            <div v-if="success"
                                                class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800"
                                                role="alert">
                                                {{ success }}
                                            </div>
                                            <div class="card-header">
                                                <h4 class="card-title">{{ data.parent_title }}</h4>
                                            </div>
                                            <div class="card-body">
                                                <!-- the events -->
                                                <div id="external-events" v-for="$item in data.childs" :key="$item.id">
                                                    <Link :href="route('Comm activity', $item.id)">
                                                    <div class="external-event mt-2 text-sm cursor-pointer bg-info">
                                                        {{ $item.child_title }}</div>
                                                    </Link>
                                                </div>
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
export default {
    components: { BreezeAuthenticatedLayout, Head, Link },
    props: {
        data: Object,
        success: String,
    },
}
</script>