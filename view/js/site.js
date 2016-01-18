
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
var token = "";//used for site authentication

function Site(){
		this.baseURL = "/";

		this.registerUserURL = this.baseURL + "index.php?s=login&action=register";
		this.loginURL = this.baseURL + "index.php?s=login";
		this.userListURL =  this.baseURL + "index.php?s=chat&action=getUserList";
		this.initChatURL =  this.baseURL + "index.php?s=chat&action=initChat&type=json";
		this.sendMessageURL = this.baseURL + "index.php?s=chat&action=sendMessage&type=json";
		this.getMessagesURL = this.baseURL + "index.php?s=chat&action=getMessages&type=json";
		this.challengeURL = this.baseURL + "index.php?s=challenge&type=json";
		this.challengeDetail = this.baseURL + "index.php?s=challenge&action=getChallengeDetails&type=json";
		this.cancelChallengeURL  = this.baseURL + "index.php?s=challenge&action=cancelChallenge&type=json";
		this.getChallengeStatusURL  = this.baseURL + "index.php?s=challenge&action=getChallengeStatus&type=json";
		this.acceptChallengeURL  = this.baseURL + "index.php?s=challenge&action=acceptChallenge&type=json";
		this.getGameURL  = this.baseURL + "index.php?s=game&action=getGame&type=json";
		this.getGameStatusURL =  this.baseURL + "index.php?s=game&type=json";
		this.updateGameURL =  this.baseURL + "index.php?s=game&action=updateGame&type=json";
		this.resetGameURL =  this.baseURL + "index.php?s=game&action=resetGame&type=json";
		this.scoreURL =  this.baseURL + "index.php?s=score&type=json";
};

Site.prototype = {

	/*
	* function to create user account
	*/
	createUserAccount:function(formObj){

		if( this.validateRegisterForm(formObj) ){

			var formToProcess = "#register";

			 $('#ajaxProgress').removeClass('hide');
			 $(formToProcess).addClass('hide');

			 this.makeAjaxCall(getLoginData(formToProcess,formObj,this.registerUserURL));
		}
	},
	//function to login the user
	login:function(formObj){

		if( this.validateLogin(formObj) ){

			var formToProcess = "#login";

			 $('#ajaxProgress').removeClass('hide');
			 $(formToProcess).addClass('hide');

			 this.makeAjaxCall(getLoginData(formToProcess,formObj,this.loginURL));
		}
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
	//get user list execpt for online users
	getUserList:function(callBack){
		this.makeAjaxCall({ type: "GET",
							   path: this.userListURL,
								 success:callBack});
	},
	//initalize chat window
	initChat:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.initChatURL,
							message:data,
							success:callBack
						 });

	},
	//initalize chat window
	sendMessage:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.sendMessageURL,
							message:data,
							success:callBack
						 });

	},
	//get latest message
	getMessages:function(callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.getMessagesURL,
							message:"",
							success:callBack
						 });
	},
	//get latest message
	challengePlayer:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.challengeURL,
							message:data,
							success:callBack
						 });
	},
	//get challenges for the users
	getChallenges:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.challengeDetail,
							message:data,
							success:callBack
						 });
	},
	//cancel the challenge
	cancelChallenge:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.cancelChallengeURL,
							message:data,
							success:callBack
						 });
	},

	//get the challenge status
	getChallengeStatus:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.getChallengeStatusURL,
							message:data,
							success:callBack
						 });
	},
	acceptChallenge:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.acceptChallengeURL,
							message:data,
							success:callBack
						 });
	},
	//get the challenge status
	getGame:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.getGameURL,
							message:data,
							success:callBack
						 });
	},
	getGameStatus:function(data, callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.getGameStatusURL,
							message:data,
							success:callBack
						 });
	},
	updateGame:function(data,callBack){
		this.makeAjaxCall({ type: "POST",
							path: this.updateGameURL,
							message:data,
							success:callBack
						 });
	},
	resetGame:function(data,callBack){

		this.makeAjaxCall({ type: "POST",
							path: this.resetGameURL,
							message:data,
							success:callBack
						 });

	},
	getScore:function(data,callBack){
		this.makeAjaxCall({ type: "POST",
							path: this.scoreURL,
							message:data,
							success:callBack
						 });

	},

	//function to make ajax call
	makeAjaxCall:function(data){

		var errorCallBack = (data.error) ? data.error: this.errorHandler;
		if(token){
			if(data.message){
				data.message["token"] = token;
			}
			else{
				data.path += "&token=" + token;
			}
		}

		$.ajax({
			type:data.type,
			async:true,
			cache:false,
			data:data.message,
			url:data.path,
			dataType:'json',
			success:data.success,
			error:errorCallBack
		});
	},
	restoreHome:function(){
				$("#gameWindow").addClass("hide");
				$("#online_users").addClass("hide");
				$("#game_list").removeClass("hide");
				$("#game_list").animate({
						left:"20%"
					},500);
					$(".piece").empty();
				$("#online_users").animate({
											left: "20%"
										},500,function(){

										});
	},
	//show game window
	showGameWindow:function(){
				$("#gameWindow").removeClass("hide");
				$("#online_users").addClass("hide");
				$("#game_list").addClass("hide");
				$("#game_list").animate({
						left:"20%"
					},500);
				$(".piece").empty();
				$("#online_users").animate({
											left: "20%"
										},500,function(){
										});
			$("section").find("section").slideUp('fast');
			$("#mainSection").slideDown('slow');

			$('.sidenav').find('.active').removeClass("active").end().find("li").first().addClass("active")

	},
	setToken:function(value){
		token = value;
	},
	getToken:function(value){
		return token;
	},
	//function to handle error for the whole site
	errorHandler:function(jqXHR, textStatus, errorThrown){
			//Initialize error to default message
			var errorMessage = "error";

			if(textStatus){
					errorMessage = textStatus;
			}
			else{

				errorMessage = jqXHR.statusText;
			}
			//scope changes when its called from different namespace
			new Site().showError(errorMessage);

	},
	//Show error panel with the error message in it
	showError:function(errorMessage){

		$("#error_message").text(errorMessage);
		$("#errorModal").modal('show');
	}
}
