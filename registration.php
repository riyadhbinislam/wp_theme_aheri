<?php
/**
 * Template Name: Registration Page
 *  @package Aheri
 */

get_header();?>

<?php
function handle_registration_form() {
    $output = '';

    if ( isset($_POST['submit']) ) {
        global $wpdb;
        $username = sanitize_text_field( $_POST['username'] );
        $email = sanitize_email( $_POST['email'] );
        $password = sanitize_text_field( $_POST['password'] );
        $first_name = sanitize_text_field( $_POST['first_name'] );
        $last_name = sanitize_text_field( $_POST['last_name'] );
        $address = sanitize_text_field( $_POST['address'] );
        $city = sanitize_text_field( $_POST['city'] );
        $postcode = sanitize_text_field( $_POST['postcode'] );
        $phone = sanitize_text_field( $_POST['phone'] );

        $errors = new WP_Error();

        if ( empty( $username ) || empty( $email ) || empty( $password ) || empty( $first_name ) || empty( $last_name ) || empty( $address ) || empty( $city ) || empty( $postcode ) || empty( $phone ) ) {
            $errors->add('field', __('Please fill all the required fields', 'aheri'));
        }

        if ( ! is_email( $email ) ) {
            $errors->add('email_invalid', __('Email is not valid', 'aheri'));
        }

        if ( email_exists( $email ) ) {
            $errors->add('email_exists', __('Email already registered', 'aheri'));
        }

        if ( username_exists( $username ) ) {
            $errors->add('username_exists', __('Username already registered', 'aheri'));
        }

        if ( ! $errors->has_errors() ) {
            // Insert new user
            $user_id = wp_insert_user( array(
                'user_login'    => $username,
                'user_pass'     => $password,
                'user_email'    => $email,
                'first_name'    => $first_name,
                'last_name'     => $last_name,
            ));

            if ( ! is_wp_error( $user_id ) ) {
                // Update the custom fields in the wp_users table
                $wpdb->update(
                    $wpdb->users,
                    array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'address' => $address,
                        'city' => $city,
                        'postcode' => $postcode,
                        'phone' => $phone
                    ),
                    array( 'ID' => $user_id )
                );

                $output = '<p>' . __('Registration complete. Please '. '<a href=/custom-login>'. 'log In' . '</a>' , 'aheri') . '</p>';
            } else {
                $output = '<p>' . $user_id->get_error_message() . '</p>';
            }
        } else {
            foreach ( $errors->get_error_messages() as $error ) {
                $output .= '<p>' . $error . '</p>';
            }
        }
    }

    return $output;
}

$message = handle_registration_form();

if ( ! is_user_logged_in() ) {
    ?>

    <section class="mt-2 mb-3" >
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                <h2><?php _e('Register', 'aheri'); ?></h2>

                                <?php if ( ! empty( $message ) ) : ?>
                                    <div class="alert alert-info">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>

                                <form class="mx-1 mx-md-4" method="post" id="registration_form">
                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="first_name"><?php _e('First Name', 'aheri'); ?><br />
                                            <input type="text" name="first_name" id="first_name" class="input form-control border" value="<?php echo isset($_POST['first_name']) ? esc_attr($_POST['first_name']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="last_name"><?php _e('Last Name', 'aheri'); ?><br />
                                            <input type="text" name="last_name" id="last_name" class="input form-control border" value="<?php echo isset($_POST['last_name']) ? esc_attr($_POST['last_name']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-home fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="address"><?php _e('Address', 'aheri'); ?><br />
                                            <input type="text" name="address" id="address" class="input form-control border" value="<?php echo isset($_POST['address']) ? esc_attr($_POST['address']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-city fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="city"><?php _e('City', 'aheri'); ?><br />
                                            <input type="text" name="city" id="city" class="input form-control border" value="<?php echo isset($_POST['city']) ? esc_attr($_POST['city']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-map-marker-alt fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="postcode"><?php _e('Postcode', 'aheri'); ?><br />
                                            <input type="text" name="postcode" id="postcode" class="input form-control border" value="<?php echo isset($_POST['postcode']) ? esc_attr($_POST['postcode']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-phone fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="phone"><?php _e('Phone Number', 'aheri'); ?><br />
                                            <input type="text" name="phone" id="phone" class="input form-control border" value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="username"><?php _e('Username', 'aheri'); ?><br />
                                            <input type="text" name="username" id="username" class="input form-control border" value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="email"><?php _e('Email', 'aheri'); ?><br />
                                            <input type="email" name="email" id="email" class="input form-control border" value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>" /></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <label class="form-label col-lg-12" for="password"><?php _e('Password', 'aheri'); ?><br />
                                            <input type="password" name="password" id="password" class="input form-control border" /></label>
                                        </div>
                                    </div>

                                    <div class="form-check d-flex justify-content-center mb-5">
                                        <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                                        <label class="form-check-label" for="form2Example3">
                                            I agree to all statements in <a href="#!">Terms of service</a>
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <input type="submit" name="submit" class="btn btn-primary btn-lg" value="<?php _e('Register', 'aheri'); ?>" />
                                    </div>

                                </form>

                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp" class="img-fluid" alt="Sample image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
} else {
    echo '<p>' . __('You are already registered and logged in.', 'aheri') . '</p>';
}
?>
        </div>
    </section>


<?php
get_footer();
?>