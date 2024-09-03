<?php
/**
 * Template Name: Custom Login
 * @package Aheri
 */
get_header(); ?>

<section class="vh-100">
  <div class="container-fluid h-custom">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5" style="background-image:url(/wp-content/uploads/2024/09/Login-Banner.webp);background-position: center center;aspect-ratio: 16/9;background-repeat: no-repeat;background-size: cover;">
        </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 border p-4">
        <form id="loginForm" method="post">
          <input type="hidden" name="action" value="custom_login">

          <!-- Username input -->
          <div class="form-outline mb-4">
            <input type="text" id="username" name="username" class="form-control form-control-lg border" placeholder="Enter your username" required />
            <label class="form-label" for="username">Username</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
            <input type="password" id="password" name="password" class="form-control form-control-lg border" placeholder="Enter your password" required />
            <label class="form-label" for="password">Password</label>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <div class="form-check mb-0">
              <input class="form-check-input me-2" type="checkbox" value="" id="remember" name="remember" />
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="text-body">Forgot password?</a>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="<?php echo esc_url(site_url('/registration')); ?>" class="link-danger">Register</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>