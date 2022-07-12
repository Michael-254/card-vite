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
                                <li class="breadcrumb-item active">Job Card
                                </li>
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
                                                class="p-2 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800"
                                                role="alert">
                                                {{ success }}
                                            </div>
                                            <div class="card-header">
                                                <div class="flex justify-between">
                                                    <h4 class="card-title">{{ childData.child_title }}</h4>
                                                    <Link v-if="childData.child_number == 1"
                                                        :href="route('New job-card', childData.id)"
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5  focus:outline-none">
                                                    New JobCard</Link>
                                                </div>
                                            </div>
                                            <div class="card-body p-0 text-sm">
                                                <table class="table table-sm">
                                                    <thead class="font-semibold">
                                                        <tr>
                                                            <th>Job card Name</th>
                                                            <th>Site</th>
                                                            <th>Start Date</th>
                                                            <th>Planning Progress</th>
                                                            <th>End Date</th>
                                                            <th>View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="$card in currentCards" :key="$card.id">
                                                            <td>{{ $card.job_card_number }}</td>
                                                            <td>{{ $card.site }}</td>
                                                            <td>{{ $card.start_date }}</td>
                                                            <td>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar progress-bar-primary"
                                                                        :style="{ 'width': calcBar(childData.child_number) + '%' }">
                                                                    </div>
                                                                </div>
                                                                <span class="badge bg-warning">{{
                                                                        calcBar(childData.child_number)
                                                                }}%</span>
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <Link
                                                                    :href="route(`sign.stage${childData.child_number}`, $card.id)">
                                                                <i
                                                                    class="fas fa-eye text-green-500 cursor-pointer hover:text-green-700"></i>
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <div v-if="currentCards[0] == null"
                                                        class="text-xs font-serif pt-3 text-center">
                                                        No Job card in progress at this stage. check back soon
                                                    </div>
                                                </table>
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
        childData: Object,
        currentCards: Object,
        success: String,
    },
    methods: {
        calcBar(child_number) {
            return child_number * 10
        }
    }
}
</script>