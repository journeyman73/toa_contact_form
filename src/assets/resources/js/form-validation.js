jQuery(document).ready(function($) {

    $("#contact-form").validate({
        ignore: ".ignore",
        rules: {
            contact_name: "required",
            contact_enquiry: "required",
            contact_email_address: {
                required: true,
                email: true
            },
            contact_telephone_number: {
                pattern: /^[\d\s]+$/,
                minlength: 6
            },
            contact_recaptcha: {
                required: function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        },
        messages: {
            contact_name: "Please enter your name",
            contact_email_address: "Please enter a valid email address",
            contact_enquiry: "Please enter an enquiry",
            contact_telephone_number: "Please enter a valid phone number",
            contact_recaptcha: "Please click on the reCAPTCHA box"
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

});

