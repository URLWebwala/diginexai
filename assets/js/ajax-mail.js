$(function() {
    // Get the form.
    var form = $('#contact-form');

    // Create message div if it doesn't exist
    if ($('.form-message').length === 0) {
        form.after('<div class="form-message" style="margin-top: 20px; padding: 15px; border-radius: 8px; display: none;"></div>');
    }
    var formMessages = $('.form-message');
    var submitButton = form.find('button[type="submit"]');
    var originalButtonText = submitButton.html();

    // Set up an event listener for the contact form.
    $(form).submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Hide previous messages
        formMessages.hide().removeClass('success error');

        // Disable submit button and show loading
        submitButton.prop('disabled', true);
        submitButton.html('<i class="fa fa-spinner fa-spin"></i> Sending...');

        // Get form values
        var name = $('#name').val().trim();
        var email = $('#email').val().trim();
        var phone = $('#phone').val().trim();
        var project = $('#project').val().trim();
        var message = $('#message').val().trim();

        // Split name into first_name and last_name
        var nameParts = name.split(' ');
        var first_name = nameParts[0] || '';
        var last_name = nameParts.slice(1).join(' ') || '';

        // Prepare data for API
        var apiData = {
            first_name: first_name,
            last_name: last_name,
            email: email,
            phone: phone,
            service: project, // Map project to service
            message: message
        };

        // API URL - Use production API base URL
        var apiUrl = 'https://api.diginexai.com/contact.php';

        // Submit the form using AJAX to API
        $.ajax({
            type: 'POST',
            url: apiUrl,
            contentType: 'application/json',
            data: JSON.stringify(apiData),
            dataType: 'json'
        })
        .done(function(response) {
            if (response.success) {
                // Show success message
                formMessages.removeClass('error');
                formMessages.addClass('success');
                formMessages.html('<i class="fa fa-check-circle"></i> ' + (response.message || 'Thank you! Your message has been sent successfully.'));
                formMessages.fadeIn();

                // Clear the form
                form[0].reset();

                // Scroll to message
                $('html, body').animate({
                    scrollTop: formMessages.offset().top - 100
                }, 500);
            } else {
                // Show error message
                formMessages.removeClass('success');
                formMessages.addClass('error');
                formMessages.html('<i class="fa fa-exclamation-circle"></i> ' + (response.message || 'Something went wrong. Please try again.'));
                formMessages.fadeIn();
            }
        })
        .fail(function(xhr) {
            // Show error message
            formMessages.removeClass('success');
            formMessages.addClass('error');
            
            var errorMessage = 'Oops! An error occurred and your message could not be sent.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    var errorData = JSON.parse(xhr.responseText);
                    if (errorData.message) {
                        errorMessage = errorData.message;
                    }
                } catch(e) {
                    // Not JSON, use default message
                }
            }
            
            formMessages.html('<i class="fa fa-exclamation-circle"></i> ' + errorMessage);
            formMessages.fadeIn();
        })
        .always(function() {
            // Re-enable submit button
            submitButton.prop('disabled', false);
            submitButton.html(originalButtonText);
        });
    });
});