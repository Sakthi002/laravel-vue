
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel 10 + Vue 3 | Sakthi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini text-sm">

<div class="wrapper" id="app">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

            <li class="nav-item d-none d-sm-inline-block">

                <router-link to="/admin/dashboard" class="nav-link">Home</router-link>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">

            <li class="nav-item">

                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">

                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>


    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <router-link to="/admin/dashboard" class="brand-link">

            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">

            <span class="brand-text font-weight-light">Project</span>
        </router-link>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                <div class="image">

                    <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>

                <div class="info">

                    <a href="#" class="d-block">Sakthivel</a>
                </div>
            </div>

            <nav class="mt-2">

                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">

                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">

                            <i class="nav-icon fas fa-tachometer-alt"></i>

                            <p>Dashboard</p>
                        </router-link>
                    </li>

                    <li class="nav-item">

                        <router-link to="/admin/appointments" :class="$route.path.startsWith('/admin/appointments') ? 'active' : ''" class="nav-link">

                            <i class="nav-icon fas fa-calendar"></i>

                            <p>Appointments</p>
                        </router-link>
                    </li>

                    <li class="nav-item">

                        <router-link to="/admin/users" active-class="active" class="nav-link">

                            <i class="nav-icon fas fa-users"></i>

                            <p>Users</p>
                        </router-link>
                    </li>

                    <li class="nav-item">

                        <router-link to="/admin/settings" active-class="active" class="nav-link">

                            <i class="nav-icon fas fa-cog"></i>

                            <p>Settings</p>
                        </router-link>
                    </li>

                    <li class="nav-item">

                        <form method="POST" action="{{ route('logout') }}" class="nav-link">

                            @csrf

                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">

                                <i class="nav-icon fas fa-sign-out-alt"></i>

                                <p>Logout</p>
                            </a>
                        </form>
                    </li>
                </ul>
            </nav>

        </div>

    </aside>

    <div class="content-wrapper">

        <router-view></router-view>

    </div>


    <aside class="control-sidebar control-sidebar-dark">

        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>


    <footer class="main-footer">

        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>

        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</div>
</body>
</html>
