var game = (function() {
  var game = {
    // this will naively create new properties on game object
    provide: function(name, fn) {
      this[name] = fn;
    }
  };
  var gameId = 0;
  
  // create mylib.foo
  game.provide('displayUserList', function(data) {
		getOnlineUserList();
	
  });

  // create mylib.bar
  game.provide('getGameId', function() {
	return gameId;
  });
	
   // create mylib.bar
  game.provide('setGameId', function(id) {
    gameId = id;
  });
	/****** private functions *****/
	function getOnlineUserList(){
		var site = new Site();
		site.getUserList( populateUserList);
	}
	//populate User list call back function from ajax call
	function populateUserList(data){
		console.log(data);
		var userList = $("#online_users").find("ul");
		userList.find('.users').remove();
		
		if(data.data){
				
				$.each(data.data,function(index){
					userList.append(createListItem(this));
				});
				
				 $("#online_users").removeClass("hide");
				 $("#game_list").animate({
									left: "-100%"
								},500,function(){
								
								$("online_users").animate({
									left:"500"
									},500);
									});
			}else if(data.error){
			
				if(data.error.isLoginRequired){
					showLoginModal();
				}			
			}
	}
	
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
					link.addEventListener('click', function(){chat.sendInvite(this)}, false); 
			} else if (link.attachEvent)  {
					link.attachEvent('onclick', function(){chat.sendInvite(this)});
			}
		var span = document.createElement('span');
			span.className = item.online_status;
		var li = document.createElement('li');
			li.className = "users";
			li.appendChild(span);
			li.appendChild(link);
			
		return li;
	};
  return game;
  
})();
