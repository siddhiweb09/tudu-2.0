@section('authenticator-main')

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper p-0">
      <div class="content-wrapper d-flex align-items-center auth p-0">
        <div class="row w-100 h-100 mx-0">
          <div class="col-lg-7 mx-auto p-0">
            <img src="images/login.jpg" class="login-banner">
          </div>
          <div class="col-lg-5 col-md-12 auth-form-light mx-auto p-0">
            <div class="auth-form text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="images/logo.png" alt="logo">
              </div>
              <h4>Hello! Let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" action="dbFiles/loginSQL.php" method="POST">
                
                <!-- Username Field -->
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="username" id="employee_code"
                    placeholder="Username" >
                </div>

                <!-- Password Field -->
                <div class="form-group position-relative">
                  <input type="password" class="form-control form-control-lg" name="password" id="password"
                    placeholder="Password">
                    
                </div>

                <!-- Sign-in Button -->
                <div class="mt-3">
                  <button type="submit" name="submit" id="submit-btn"
                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>

                <!-- Forgot Password -->
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- JavaScript to Show Chat ID Input When Needed -->
<script>

</script>
@endsection