/*
* Contains script which perform login and user registration 
* related operation
*
*/

/*
* Shows the new account register form and hides the login 
* screen
*
*/

function showRegisterForm(){
	
	$('#login').addClass('hide');
	$('#register').removeClass('hide');
	return false;
}

/*
* Shows the Login form hiding the register form
*
*/

function showLogin(){
	
	$('#login').removeClass('hide');
	$('#register').addClass('hide');
	return false;
}
/*
* Handles the submit of register form event and do validation
* 
*/
$('#register_form').submit(function() {

	var isValid = false;
	
	//get the form jquery object reference,instead of creating jquery object each time
	var formRef = $(this);

	var site = new Site();
		
	site.createUserAccount(formRef);
	
	//return false to prevent form posting
    return false;
});