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
          <p class="text-center mb-4 title-color">Sign in to continue</p>
          <form id="loginForm" method="POST">
            @csrf
            <!-- Username Field -->
            <div class="form-group mb-3">
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1"> <i class="ti ti-user"></i></span>
                <input type="text" class="form-control" name="employee_code" id="employee_code" aria-label="employee_code"
                  aria-describedby="basic-addon1" placeholder="Enter your username">
              </div>
            </div>

            <!-- Password Field -->
            <div class="form-group mb-3">
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1"><i class="ti ti-lock"></i></span>
                <input type="text" class="form-control" name="password" id="password" aria-label="password"
                  aria-describedby="basic-addon1" placeholder="Enter your password">
              </div>
            </div>

            <!-- Sign-in Button -->
            <div class="d-grid mb-3 spacing-margin">
              <button type="submit" class="btn btn-warning fw-bold">Sign In</button>
            </div>

          </form>
        </div>
      </div>

      <!-- Right Side - Image Banner -->
      <div class="col-lg-6 d-none d-lg-block p-0">
        <div class="h-100 w-100 position-relative">
          <div id="loginCarousel" class="carousel slide h-100 w-100" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner h-100 w-100">

              <div class="carousel-item active h-100">
                <div class="h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset('assets/images/logo-1.jpg') }}" class="img-fluid object-fit-contain" alt="Slide 1">
                </div>
              </div>

              <div class="carousel-item h-100">
                <div class="h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset('assets/images/logo-2.jpg') }}" class="img-fluid object-fit-contain" alt="Slide 2">
                </div>
              </div>

              <div class="carousel-item h-100">
                <div class="h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset('assets/images/logo-3.jpg') }}" class="img-fluid object-fit-contain" alt="Slide 3">
                </div>
              </div>

              <div class="carousel-item h-100">
                <div class="h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset('assets/images/logo-4.jpg') }}" class="img-fluid object-fit-contain" alt="Slide 4">
                </div>
              </div>

              <div class="carousel-item h-100">
                <div class="h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset('assets/images/logo-5.jpg') }}" class="img-fluid object-fit-contain" alt="Slide 5">
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('customJs')

<script>
  $(document).ready(function() {

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#loginForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: "/authenticate",
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
          if (response.status === 'success') {
            setTimeout(() => {
              window.location.href = response.redirect_url ?? "{{ route('dashboard') }}";
            }, 1000); // optional delay before redirect
          } else {
            alert("Error: " + (response.message || "Something went wrong."));
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            let errors = xhr.responseJSON.messages;
            let message = '';
            for (let key in errors) {
              message += errors[key][0] + '\n';
            }
            alert("Validation Error:\n" + message);
          } else if (xhr.status === 401) {
            alert("Login failed: Invalid credentials.");
          } else {
            alert("An unexpected error occurred.");
          }
        }
      });
    });

  });
</script>
@endsection