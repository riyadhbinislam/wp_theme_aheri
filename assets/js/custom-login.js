// Handle Custom Login From


document.addEventListener('DOMContentLoaded', function() {
    jQuery('form#loginForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = jQuery(this).serialize(); // Serialize form data
        console.log('Form data:', formData); // Debug output

        jQuery.ajax({
            url: aheri_ajax.ajax_url, // Use localized ajax_url
            method: 'POST',
            data: formData + '&action=custom_login', // Include the action parameter
            success: function(response) {
                console.log('AJAX response:', response); // Debug output
                if (response.success) {
                    window.location.href = response.data.redirect; // Redirect to URL
                } else {
                    // Handle login error
                    alert('Login failed: ' + response.data.message);
                }
            },
            error: function() {
                // Handle AJAX error
                alert('An error occurred. Please try again.');
            }
        });
    });
});