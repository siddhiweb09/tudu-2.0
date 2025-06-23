<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <!-- Core Css -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/buttons.css" />

    <title>Make Your Tasks Easy</title>
</head>

<body class="loading">

    <!-- Preloader -->
    <div class="preloader">
        <div class="scene">
            <div class="shadow"></div>
            <div class="jumper">
                <div class="spinner">
                    <div class="scaler">
                        <div class="loader">
                            <div class="cuboid">
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="main-wrapper">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg navbar-light bg-blue d-flex flex-wrap p-0">
            <div class="col-12 border-bottom py-2">
                <div class="container">
                    <div class="d-flex justify-content-between">
                        <a class="navbar-brand" href="#"><img src="assets/images/logo.png" class="logo" /></a>

                        <!-- Example single danger button -->
                        <div class="dropdown-center">
                            <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="assets/images/logo.png" class="rounded-circle profile-pic" />
                            </button>
                            <ul class="dropdown-menu profile-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 py-2">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-between w-100">
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-home-2 menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-list-details menu-icon"></i>
                                    <span class="menu-title">My Tasks</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('tasks.allTasks') }}">Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Pending Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Delayed Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Completed Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('personal-tasks.index') }}">To Do List</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-presentation-analytics menu-icon"></i>
                                    <span class="menu-title">Projects</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-table-plus menu-icon"></i>
                                    <span class="menu-title">Quick Add Task </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tasks.calender') }}">
                                    <i class="ti ti-calendar-week menu-icon"></i>
                                    <span class="menu-title">Calendar View</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-tags menu-icon"></i>
                                    <span class="menu-title">Tags / Labels</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('helpAndSupport') }}">
                                   <i class="ti ti-info-square-rounded menu-icon"></i>
                                    <span class="menu-title">Support</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!--  Navbar End -->
        <div class="page-wrapper " style="min-height: 100vh;">
            @yield('main')
        </div>
    </div>


    @include('personal-tasks.partials.modals')

    @extends('modalCreateTask')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                preloader.style.transition = 'opacity 0.5s ease';
            }
        });

    </script>
    @yield('customJs')
</body>

</html>