(function($) {
    'use strict';
    
    function passwordConfirmValidation() {
        $('.new-password, .confirm-password').on('change keyup', function(){
            const newPassword = $('.new-password').val();
            const confirmPassword = $('.confirm-password').val();
            const updatePasswordBtn = $('.update-password-btn');  
            const newPasswordInput = $('.new-password');
            const confirmPasswordInput = $('.confirm-password');
            const confimPasswordFeedback = $('.confirm-password-feedback');

            if(newPassword === confirmPassword){
                confimPasswordFeedback.text('');
                newPasswordInput.removeClass('form-border-danger').addClass('form-border-success');
                confirmPasswordInput.removeClass('form-border-danger').addClass('form-border-success');
                
                if($(this).hasClass('confirm-password')) {
                    updatePasswordBtn.prop('disabled', false);
                }
            } else {
                confimPasswordFeedback.text('The Password is not match !!');
                newPasswordInput.removeClass('form-border-success').addClass('form-border-danger');
                confirmPasswordInput.removeClass('form-border-success').addClass('form-border-danger');
                updatePasswordBtn.prop('disabled', true);
            }
        });
    }
    
    function oldPasswordCheck() {
        $('.old-password').on('change keyup', function(){
            const oldPassword = $('.old-password').val();
            const userID = $('.user-password-id').val();
            const oldPasswordFeedback = $('.old-password-feedback');
            const oldPasswordInput = $(this);
            const newPasswordInput = $('.new-password');
            const confirmPasswordInput = $('.confirm-password');
            const updatePasswordBtn = $('.update-password-btn');

            $.ajax({
                url: jsConfig.URLROOT + "/users/password_check",
                method: 'POST',
                data: {
                    oldPassword: oldPassword,
                    userID: userID
                },
                success: function(data){
                    if(data == "correct"){
                        oldPasswordInput.removeClass('form-border-danger').addClass("form-border-success");
                        oldPasswordFeedback.text('');
                        newPasswordInput.prop('disabled', false);
                        confirmPasswordInput.prop('disabled', false);
                    } else {
                        oldPasswordInput.removeClass('form-border-success').addClass("form-border-danger");
                        oldPasswordFeedback.text('Your old password is not correct!!');
                        newPasswordInput.prop('disabled', true);
                        confirmPasswordInput.prop('disabled', true);
                        updatePasswordBtn.prop('disabled', true);
                    }
                },
                error: function() {
                    alert("An error occurred while checking the password, Please try again");
                }
            });
        });
    }
    
    window.AppModules = window.AppModules || {};
    window.AppModules.password = {
        init: function() {
            passwordConfirmValidation();
            oldPasswordCheck();
        }
    };
    
})(jQuery);