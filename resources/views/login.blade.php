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
        <h4 class="mb-1 text-center text-white">Welcome Back!</h4>
        <p class="text-center mb-4 text-white">Sign in to continue</p>
        <form id="loginForm" method="POST">
        @csrf
        <!-- Username Field -->
        <div class="form-group mb-3">
          <div class="input-group">
          <span class="input-group-text" id="basic-addon1"><i class="ti ti-user"></i></span>
          <input type="text" class="form-control form-control-lg" name="username" id="username"
            aria-label="employee_code" aria-describedby="basic-addon1" placeholder="Enter your username">
          </div>
        </div>

        <!-- Password Field -->
        <div class="form-group mb-3">
          <div class="input-group">
          <span class="input-group-text" id="basic-addon1"><i class="ti ti-lock"></i></span>
          <input type="text" class="form-control form-control-lg" name="password" id="password"
            aria-label="password" aria-describedby="basic-addon1" placeholder="Enter your password">
          </div>
        </div>

        <!-- Sign-in Button -->
        <div class="d-grid mb-3 spacing-margin">
          <button type="submit" name="submit" class="btn btn-warning fw-bold">Sign In</button>
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