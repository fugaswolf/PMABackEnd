<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>TeamTrack | Dashboard</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/dashboard" class="brand-link">
                <div class="image">
                    <img src="./img/logo.png" alt="TeamTrack Logo" class="brand-image img-circle elevation-3"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">TeamTrack</span>
                </div>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="./img/profile.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="/profile" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <router-link to="/dashboard" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/timer" class="nav-link">
                                <i class="nav-icon fas fa-stopwatch"></i>
                                <p>
                                    Timer
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/profile" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Profile
                                </p>
                            </router-link>
                        </li>
                        @hasanyrole('admin|hr|manager')
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/projects" class="nav-link">
                                        <i class="nav-icon fa fa-folder-open"></i>
                                        <p>Projects</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/tasks" class="nav-link">
                                        <i class="nav-icon fa fa-tasks"></i>
                                        <p>Tasks</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/customers" class="nav-link">
                                        <i class="nav-icon fa fa-handshake"></i>
                                        <p>Customers</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/users" class="nav-link">
                                        <i class="nav-icon fa fa-users"></i>
                                        <p>Users</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        @endhasanyrole
                        @hasrole('admin')
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Developer
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/TokenManager" class="nav-link">
                                        <i class="nav-icon fa fa-universal-access"></i>
                                        <p>Token Manager</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        @endhasrole
                        <li class="nav-item">

                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-power-off"></i>
                                <p>
                                    {{ __('Logout') }}
                                </p>

                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <router-view></router-view>
                    <vue-progress-bar></vue-progress-bar>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                version 1.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2019 <a href="https://codewolf.be">Alvi Istamalov</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="/js/app.js"></script>
  
</body>

</html>
