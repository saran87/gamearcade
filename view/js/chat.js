/*
*   Chat related scripts are implemented in this file
*
*
*/
(function(window,undefined){
	var chat = function(){
	
	};
	chat.updateChatPanel = function (){
		
		var site = new Site();
		console.log(this);
		site.getUserList(this.updateUsers);

	};
	chat.updateUsers = function (data){

			var list = $("#chat_content").find("ul");
			list.remove('.users');
			if(data.data){
				
				$.each(data.data,function(index){
					list.append(createListItem(this));
				
				});
			}else if(data.error){
			
				if(data.error.isLoginRequired){
					showLoginModal();
				}			
			}
	};

	function createListItem(item){

		var link = document.createElement('a');
			link.className = "users";
			link.href = "#";
			link.value = item.name;
			if(document.all){
				link.innerText = item.name;
			}
			else{
				link.textContent = item.name;
			}
		var li = document.createElement('li');
			li.className = "";
		li.appendChild(link);
		return li;
	};
	
	window.chat = chat;
})(window);