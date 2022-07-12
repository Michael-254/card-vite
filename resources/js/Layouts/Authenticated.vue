<template>
    <div class="wrapper">

        <!-- Page Heading -->
        <header class="bg-white shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" :src="$page.props.app.url" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="fa fa-user fa-fw"></i>
                        {{ $page.props.auth.user.name }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <Link class="nav-link" :href="route('logout')" method="post" as="button">
                            LogOut
                            <i class="fas fa-logout"></i>
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img :src="$page.props.app.url" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">Job Card</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': route().current() == 'Dashboard' }"
                                :href="route('Dashboard')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link :href="route('OP Activities')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/operation-planning') || $page.url.startsWith('/New-Jobcard') }">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                OP Planning
                                <span class="right badge badge-success">1</span>
                            </p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('Comm Activities')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/Communication') }">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>
                                Communication
                                <span class="right badge badge-info">2</span>
                            </p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('FC Activities')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/Fruit-collection') }">
                            <i class="nav-icon fas fa-tree"></i>
                            <p>
                                Fruit Collection
                                <span class="right badge badge-warning">3</span>
                            </p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Tables
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="pages/tables/simple.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Simple Tables</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/tables/data.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>DataTables</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages/tables/jsgrid.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>jsGrid</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">USERS</li>
                        <li class="nav-item">
                            <Link :href="route('Manage roles')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/manage-roles') }">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Role Management
                                <span class="badge badge-info right">R</span>
                            </p>
                            </Link>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <slot />
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="#">Better Globe Forestry LTD</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Prosperity with purpose</b>
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: { Link },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            let SELECTOR_LOADER = '.preloader'
            setTimeout(() => {
                let $loader = $(SELECTOR_LOADER)
                if ($loader) {
                    $loader.css('height', 0)
                    setTimeout(() => {
                        $loader.children().hide()
                    }, 50)
                }
            }, 400)
        }
    }
}
</script>

