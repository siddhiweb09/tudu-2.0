<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />

    <!-- Core Css -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/buttons.css" />
    <link rel="stylesheet" href="assets/css/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <title>Make Your Tasks Easy</title>
</head>

<body class="loading">

    <!-- Preloader -->
    <div class="preloader">
        <div class="scene">
            <div class="shadow-preloader"></div>
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
                            <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="assets/images/logo.png" class="rounded-circle profile-pic" />
                            </button>
                            <ul class="dropdown-menu profile-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
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
                                <a class="nav-link" href="../../">
                                    <i class="ti ti-home-2 menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-list-details menu-icon"></i>
                                    <span class="menu-title">My Tasks</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('tasks.allTasks') }}">Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tasks.pendingTasks') }}">Pending Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tasks.inProcessTasks') }}">In Process Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tasks.inReviewTasks') }}">In Review Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tasks.overdueTasks') }}">Overdiew Tasks</a></li>
                                    <li><a class="dropdown-item" href="{{ route('personal-tasks.index') }}">To Do
                                            List</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../projects">
                                    <i class="ti ti-presentation-analytics menu-icon"></i>
                                    <span class="menu-title">Projects</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('team.viewTeams') }}">
                                    <i class="ti ti-user-shield menu-icon"></i>
                                    <span class="menu-title">Teams</span>
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
        <div class="page-wrapper">
            @yield('main')
        </div>
    </div>

    <div id="addtask-trigger">
        <button class="btn" type="button" class="open-modal-btn" data-bs-toggle="modal" data-bs-target="#add-task">
            <i class="ti ti-plus"></i>
        </button>
    </div>

    @extends('modalCreateTask')

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- include summernote css/js -->
    <link href="assets/summernote/summernote.min.css" rel="stylesheet">
    <script src="assets/summernote/summernote.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="assets/js/task-form.js"></script>
    <script src="assets/js/task-update-form.js"></script>

    <script>
        window.addEventListener('load', function () {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                preloader.style.transition = 'opacity 0.5s ease';
            }
            $(".summernote").summernote({
                placeholder: "Describe your task here....",
                tabsize: 2,
                height: 100,
            });
            $('.js-example-basic-single').select2({
                placeholder: 'Select an option'
            });
        });
    </script>
    @yield('customJs')

</body>

</html>