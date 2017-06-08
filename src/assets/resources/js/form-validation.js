jQuery(document).ready(function($) {


    // jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
    //         return this.optional(element) || phone_number.length > 9 &&
    //             phone_number.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/);
    //     }, 'Please specify a valid phone number'
    // );

    $("#contact-form").validate({
        debug: true,
        rules: {
            contact_name: "required",
            contact_enquiry: "required",
            contact_email_address: {
                required: true,
                email: true
            }
            // ,contact_telephone_number: {
            //     phoneUK: true
            // }
        },
        messages: {
            contact_name: "Please enter your name",
            contact_email_address: "Please enter a valid email address",
            contact_enquiry: "Please enter an enquiry"
            // contact_telephone_number: "Please enter a valid phone number"
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});

