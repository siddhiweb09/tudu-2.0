@extends('layouts.authentication')

@section('authenticator-main')
<div class="container-scroller">
  <div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light px-0">
    <div class="row w-100 h-100">
      <!-- Left Side - Login Form -->
      <div class="col-lg-6 d-flex align-items-center justify-content-center bg-blue">
        <div class="w-100 p-4 p-md-5" style="max-width: 400px;">
          <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
          </div>
          <h4 class="mb-1 text-center">Welcome Back!</h4>
          <p class="text-muted text-center mb-4">Sign in to continue</p>
          <form id="loginForm" method="POST">
            @csrf
            <!-- Username Field -->
            <div class="form-group mb-3">
              <label for="employee_code" class="form-label">Username</label>
              <input type="text" class="form-control form-control-lg" name="username" id="employee_code"
                placeholder="Enter your username">
            </div>

            <!-- Password Field -->
            <div class="form-group mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control form-control-lg" name="password" id="password"
                placeholder="Enter your password">
            </div>

            <!-- Sign-in Button -->
            <div class="d-grid mb-3">
              <button type="submit" name="submit" class="btn btn-primary">Sign In</button>
            </div>

            <!-- Forgot Password -->
            <div class="text-end">
              <a href="#" class="text-decoration-none">Forgot password?</a>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Side - Image Banner -->
      <div class="col-lg-6 d-none d-lg-block p-0">
        <div class="h-100 w-100 position-relative">
          <div id="loginCarousel" class="carousel slide h-100 w-100" data-bs-ride="carousel">
            <div class="carousel-inner h-100 w-100">

              <div class="carousel-item active">
                <div class="h-100">
                  <img src="{{ asset('assets/images/logo-1.jpg') }}" class="d-flex img-fluid object-fit-cover"
                    alt="Slide 1">
                </div>
              </div>

              <div class="carousel-item">
                <img src="{{ asset('assets/images/logo-2.jpg') }}" class="d-flex img-fluid object-fit-cover"
                  alt="Slide 2">
              </div>

              <div class="carousel-item">
                <img src="{{ asset('assets/images/logo-3.jpg') }}" class="d-flex img-fluid object-fit-cover"
                  alt="Slide 3">
              </div>

              <div class="carousel-item">
                <img src="{{ asset('assets/images/logo-4.jpg') }}" class="d-flex img-fluid object-fit-cover"
                  alt="Slide 4">
              </div>

              <div class="carousel-item">
                <img src="{{ asset('assets/images/logo-5.jpg') }}" class="d-flex img-fluid object-fit-cover"
                  alt="Slide 5">
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>

@endsection