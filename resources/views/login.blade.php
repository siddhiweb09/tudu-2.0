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
                    placeholder="Username" value="<?php echo htmlspecialchars($employee_code); ?>"
                    <?php echo $showChatId ? 'readonly' : ''; ?> required>
                </div>

                <!-- Password Field -->
                <div class="form-group position-relative">
                  <input type="password" class="form-control form-control-lg" name="password" id="password"
                    placeholder="Password" value="<?php echo htmlspecialchars($password); ?>"
                    <?php echo $showChatId ? 'readonly' : ''; ?> required>
                </div>

                <!-- Chat ID Field (Only Visible When Needed) -->
                <div class="mb-3 position-relative" id="chat_id_field" style="display: <?php echo $showChatId ? 'block' : 'none'; ?>;">
                  <label for="chat_id" class="form-label">
                    Scan the QR code and send a message to the bot. You will receive a Chat ID—enter it below.
                  </label>
                  <div class="d-flex align-items-center">
                    <input type="text" id="chat_id" name="chat_id" class="form-control" placeholder="Enter your Chat ID"
                      <?php echo $showChatId ? 'required' : 'disabled'; ?>>
                    <a href="https://t.me/UnicoreErp_bot" target="_blank" class="ms-2">
                      <img id="telegram-qr" src="" alt="Scan QR" width="50" height="50"
                        class="rounded border border-primary p-1">
                    </a>
                  </div>
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