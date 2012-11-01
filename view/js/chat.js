/*
*   Chat related scripts are implemented in this file
*
*
*/
(function(window,undefined){
	//chat object
	var chat = function(){
	
	};
	//function to update chat panel, which displays current logged in users
	chat.updateChatPanel = function (){
		
		var site = new Site();
		site.getUserList(chat.updateUsers);
		
	};
	//call back function to handle to update user list in the chat panel
	chat.updateUsers = function (data){

			var list = $("#chat_content").find("ul");
			list.find('.users').remove();
			if(data.data){
				
				$.each(data.data,function(index){
					list.append(createListItem(this));
				
				});
				//update chat panel every 20 secs
				//setTimeout(chat.updateChatPanel,2000);
			}else if(data.error){
			
				if(data.error.isLoginRequired){
					showLoginModal();
				}			
			}
	};
	chat.initializeChat = function(response){
		
		if(!response.error){
			//get the chat window id from the response
			var chatWindowId = response.data.chatWindowId;
		
			//get the chat window from the dom
			var chatWindow = document.getElementById(chatWindowId);
				chatWindow = $(chatWindow);//get jquery object
			chatWindow.find("form").attr(response.data);
			chatWindow.find(".chat_messages").attr("id",response.data.chatId);
			var data = {"message":chatWindow.find('.message').val(),"chatid":response.data.chatId};
			var site = new Site();
			site.sendMessage(data,chat.updateMessage);
		}else if(data.error){
			
				if(data.error.isLoginRequired){
					showLoginModal();
				}			
		}
	};
	chat.updateMessage = function(response){
		if( !response.error && response.data){
			//get the chat window from the dom
			var messagePanel = $("#chat_message_template").clone().removeClass('hide');
				messagePanel.find('.name').text(response.data.name);
				messagePanel.find('.message').text(response.data.message);
				messagePanel.find('.timeStamp').text(response.data.timestamp);
				
				messagePanel.appendTo($(document.getElementById(response.data.chatId)));
				messagePanel.focus();
				messagePanel.parent().scrollTop(messagePanel.parent().scrollHeight - messagePanel.parent().clientHeight);
				console.log(response);
		}else if(data.error){
			
			if(data.error.isLoginRequired){
				showLoginModal();
			}			
		}
	};
	//private methods
	//Creat list item will create a single li it with users name and online status
	function createListItem(item){

		var link = document.createElement('a');
			link.href = "#";
			link.value = item.name;

			if(document.all){
				link.innerText = item.name;
			}
			else{
				link.textContent = item.name;
			}
			link.setAttribute("userId",item.id_users);
			//add start chat event listener to the list object
			if (link.addEventListener) {
					link.addEventListener('click', function(){startChat(this)}, false); 
			} else if (link.attachEvent)  {
					link.attachEvent('onclick', function(){startChat(this)});
			}
		var span = document.createElement('span');
			span.className = item.online_status;
		var li = document.createElement('li');
			li.className = "users";
			li.appendChild(span);
			li.appendChild(link);
			
		return li;
	};
	
	function startChat(item){
	
		userId = item.getAttribute("userId");
		//see if the chat already exists
		var chatWindow = document.getElementById(userId);
		if(!chatWindow){
			var name = $(item).val()
			chatWindow = $('#chat_template').clone().attr('id',userId).removeClass("hide").prependTo('#chat_area');
			chatWindow.find("#chatName").text(name);
			chatWindow.find(".chat_header").toggle(function() {
							  $(this).parent(".chatbox:first").animate({
													height: "30"
												}, 500, function() {
													// Animation complete.
													console.log(this);
												});
							}, function() {
							   $(this).parent(".chatbox:first").animate({
													height: "220"
												}, 500, function() {
													// Animation complete.
													console.log(this);
												});
							}).find('.closeButton').click(function(event){
							
								 $(this).parentsUntil('.chatbox').parent().parent(".chatbox:first").addClass('hide');
								 event.stopPropagation();
							
							});
							
					//Attach form submit event to chat form 
					chatWindow.find("form").submit(function() {

						chatId = this.getAttribute("chatId");
						if(chatId){
							
							var data = {"message":$(this).find('.message').val(),"chatid":chatId};
							var site = new Site();
							site.sendMessage(data,chat.updateMessage);
						}
						else{
						
							//get the form jquery object reference,instead of creating jquery object each time
							var formObj= $(this);
								
							var site = new Site();
							var data = {"partnerId":userId};
								
							site.initChat(data,chat.initializeChat);
					}	
					
					//return false to prevent form posting
					return false;
				});
			
		}else{
			$(chatWindow).removeClass('hide');
		}
		 event.stopPropagation();
		return false;
	}
	
	window.chat = chat;
})(window);