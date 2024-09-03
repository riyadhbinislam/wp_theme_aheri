
jQuery(document).ready(function($) {
    $('.add-to-cart-form').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var product_id = form.find('input[name="product_id"]').val();
        var quantity = form.find('input[name="quantity"]').val();

        $.ajax({
            type: 'POST',
            url: aheri_ajax.ajax_url, // Use the correct URL for AJAX
            data: {
                action: 'aheri_add_to_cart',
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    showNotification(response.data, 'success');
                    updateCartCount();
                } else {
                    showNotification(response.data, 'error');
                }
            }
        });
    });

    function updateCartCount() {
        $.ajax({
            type: 'POST',
            url: aheri_ajax.ajax_url,
            data: {
                action: 'aheri_get_cart_count'
            },
            success: function(response) {
                if (response.success) {
                    $('.fa-shopping-cart').next('.badge').text(response.data.cart_count);
                } else {
                    $('.fa-shopping-cart').next('.badge').text('');
                }
            }
        });
    }

    function showNotification(message, type) {
        var notification = $('<div class="notification"></div>').text(message);
        if (type === 'error') {
            notification.addClass('error');
        }

        $('#notification-container').append(notification);

        // Fade in the notification
        setTimeout(function() {
            notification.css('opacity', 1);
        }, 100);

        // Fade out and remove the notification after 3 seconds
        setTimeout(function() {
            notification.css('opacity', 0);
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
});


