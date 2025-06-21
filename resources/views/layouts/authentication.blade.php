<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
    <!-- Core Css -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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
        <!--  Navbar End -->
        <div class="page-wrapper">
            @yield('authenticator-main')

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script>
        window.addEventListener('load', function () {
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