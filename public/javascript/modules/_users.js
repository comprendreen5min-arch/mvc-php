(function($) {
    'use strict';
    
    function checkUserEmail() {
        $('#user-email').on('change', function(){
            const userEmail = $(this).val();
            const userEmailInput = $(this);
            const userMessageFeedBack = $('#user-email-check-message');
            const addUserBtn = $('#add-user-btn');

            if (userEmail.length < 3) {
                return;
            }

            $.ajax({
                url: jsConfig.URLROOT + "/users/email_check",
                method: 'POST',
                dataType: 'json',
                data: {
                    user_email: userEmail
                },
                beforeSend: function() {
                    addUserBtn.prop('disabled', true);
                    userMessageFeedBack.html('Checking...').removeClass('text-success text-danger');
                }, 
                success: function(data) {
                    if (data.exists) {
                        userEmailInput.addClass('is-invalid').removeClass('is-valid');
                        userMessageFeedBack.html(data.message).addClass('text-danger');
                        addUserBtn.prop('disabled', true).html('Add User');
                    } else {
                        userEmailInput.addClass('is-valid').removeClass('is-invalid');
                        userMessageFeedBack.html(data.message).addClass('text-success');
                        addUserBtn.prop('disabled', false).html('Add User');
                    }
                },
                error: function() {
                    userMessageFeedBack.html('Error checking email').addClass('text-danger');
                    addUserBtn.prop('disabled', false).html('Add User');
                    alert("An error occurred while checking the email. Please try again");
                }
            });
        });
    }
    
    function checkUserEmailOnUpdate() {
        $('#user-email-on-update').on('change keyup', function(){
            const userEmail = $(this).val();
            const userId = $('#user_id_on_update').val();
            const userEmailInput = $(this);
            const userMessageFeedBack = $('#user-email-on-update-message');
            const addUserBtn = $('#save-user-btn');

            if (userEmail.length < 3) {
                return;
            }

            $.ajax({
                url: jsConfig.URLROOT + "/users/email_check_on_update",
                method: 'POST',
                dataType: 'json',
                data: {
                    user_email: userEmail,
                    user_id: userId
                },
                beforeSend: function() {
                    addUserBtn.prop('disabled', true);
                    userMessageFeedBack.html('Checking...').removeClass('text-success text-danger');
                }, 
                success: function(data) {
                    if (data.exists) {
                        userEmailInput.addClass('is-invalid').removeClass('is-valid');
                        userMessageFeedBack.html(data.message).addClass('text-danger');
                        addUserBtn.prop('disabled', true).html('Save');
                    } else {
                        userEmailInput.addClass('is-valid').removeClass('is-invalid');
                        userMessageFeedBack.html(data.message).addClass('text-success');
                        addUserBtn.prop('disabled', false).html('Save');
                    }
                },
                error: function() {
                    userMessageFeedBack.html('Error checking email').addClass('text-danger');
                    addUserBtn.prop('disabled', false).html('Save');
                    alert("An error occurred while checking the email. Please try again");
                }
            });
        });
    }
    
    window.AppModules = window.AppModules || {};
    window.AppModules.users = {
        init: function() {
            checkUserEmail();
            checkUserEmailOnUpdate();
        }
    };
    
})(jQuery);