
/* *************************************************
   Class: 
			Site										
   Description: 
			   Using the javascript prototype, you 
			   can make site classes. This allows objects to be 
			   made to perform the major common operations     
			   in the site.

   Operations:
			
			1. CreateUserAccount.
			2. Login user
			3. Make Ajax call
			4. Validate Email
			5. Validate Password
			6. Error handle

******************************************************/	
   
    
function Site(){
		this.baseURL = "http://localhost/connect4/";
		
		this.registerUserURL = this.baseURL + "index.php?s=login&action=register";
};
	
Site.prototype = {

	//function to create user account
	createUserAccount:function(formObj){
		
		if( this.validateRegisterForm(formObj) ){
			
			var formToProcess = "#register";
			
			 $('#ajaxProgress').removeClass('hide');
			 $(formToProcess).addClass('hide');
			
			 this.makeAjaxCall({
							   type: "POST",
							   path: this.registerUserURL,
							   message: formObj.serialize(), // serializes the form's elements.
							   success: function(data)
							   {
								   console.log(data); // show response from the php script.
								   $('#ajaxProgress').addClass('hide');	
								   $(formToProcess).removeClass('hide');
								   //if call success
								   if(data){
									   if(data.error){
											//error occured in server, display the message to the user
											$('#login_message').text(data.error).removeClass('hide');
											
									   }else{
											//on success
											
										}
									}
									else{
										//error occured in server, display the message to the user
											$('#login_message').text("Server error occurred").removeClass('hide');
									}
							   },
							   error:function(jqXHR, textStatus, errorThrown)
							   {
									//what to do while error occurred making ajax call
									$('#ajaxProgress').addClass('hide');	
									$(processForm).removeClass('hide');
									$('#login_message').text(jqXHR.statusText).removeClass('hide');
							   }
					});
		}

	},
	//function to login the user
	login:function(formObj){


	},
	//validate to validate login form
	validateLogin:function(formObj){
		
		var isValid = false;
		
		//get the form input values
		var email    = formObj.find('.email').val();
		var password = formObj.find('.password').val();
		
		//validate email
		if(this.validateEmail(email)){

			//validate password
			if(this.validatePassword(password)){
				isValid = true;//validation successfull
				//restore the error messages
				formObj.find('.password').removeClass('input-error').next().addClass('hide');
				formObj.find('.email').removeClass('input-error').next().addClass('hide');	
			}
			else{
				//show error messages
				formObj.find('.email').removeClass('input-error').next().addClass('hide');
				formObj.find('.password').addClass('input-error').next().removeClass('hide');				
			}		
		}
		else{
			formObj.find('.email').addClass('input-error').next().removeClass('hide');
		}

		return isValid;
	},
	//function to validate user register form
	validateRegisterForm:function(formObj){
		
		var isValid = false;
		//get the form input values
		var name     = formObj.find('.name').val()
		var email    = formObj.find('.email').val();
		var password = formObj.find('.password').val();

		
		//validate name
		if(this.validateAlphaString(name)){
			
			//validate email
			if(this.validateEmail(email)){
			
				//validate password
				if(this.validatePassword(password)){
					isValid = true;//validation successfull
					//restore the error messages
					formObj.find('.password').removeClass('input-error').next().addClass('hide');	
					formObj.find('.name').removeClass('input-error').next().addClass('hide');
					formObj.find('.email').removeClass('input-error').next().addClass('hide');	
				}
				else{
					//show error messages
					formObj.find('.name').removeClass('input-error').next().addClass('hide');
					formObj.find('.email').removeClass('input-error').next().addClass('hide');
					formObj.find('.password').addClass('input-error').next().removeClass('hide');				
				}		
			}
			else{
				formObj.find('.name').removeClass('input-error').next().addClass('hide');
				formObj.find('.email').addClass('input-error').next().removeClass('hide');
			}
		}
		else{
			formObj.find('.name').addClass('input-error').next().removeClass('hide');
		}

		return isValid;
	},
	//validate the alpha string
	validateAlphaString:function(name){

		var alphaReg = /^[A-Za-z]+\s*[A-Za-z]+$/
		
		return alphaReg.test(name);
	},
	//validate the site password
	validatePassword:function(password){

		 var isValid = false;
		 
		if(password != '' && password.length >= 8){
			
			isValid = true;
		}
		
		return isValid;
	},
	//function to validate email address
	validateEmail: function(email){

		var emailReq = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

		return emailReq.test(email);
	},
	//function to make ajax call
	makeAjaxCall:function(data){
		
		errorHandler = (data.error === 'undefined') ? data.error: this.errorHandler;

		$.ajax({
			type:data.type,
			async:true,
			cache:false,
			data:data.message,
			url:data.path,
			dataType:'json',
			success:data.success,
			error:errorHandler
		});
	},
	//function to handle error for the whole site
	errorHandler:function(jqXHR, textStatus, errorThrown){	

			var errorMessage = "error";
			if( typeof jqXHR === 'undefined'){
				
				if(textStatus){
					errorMessage = textStatus;
				}
			}
			else{
				errorMessage = jqXHR.statusText;
			}
			$("#error_message").text(errorMessage);
			$("#errorModal").modal('show');
	}		
}