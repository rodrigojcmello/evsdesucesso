$(document).ready(function() {
	 $('#contactform').validate({
	 	// Set up rules for each field in your form. Reference each one by its "name" not "id"
		rules: {
	    	Your_Name: { required: true },
	    	Email_Address: { required: true, email: true },
	    	Message: { required: true }
		}
	});
	// Submit form using AJAX and clear the submitted results
	$('#contactform').ajaxForm({
		target: '#message',
		url: 'lib/Simple-Form/mail.php',
		success: successMessage,
		clearForm: true,
		resetForm: true
	});
});

// Fade in success message
function successMessage() {
	$('#message').fadeIn(500).delay(5000).fadeOut(500);
}
