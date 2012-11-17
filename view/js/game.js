var game = (function() {
  var game = {
    // this will naively create new properties on game object
    provide: function(name, fn) {
      this[name] = fn;
    }
  };
  var gameId = 0;
  var challengeId = -1;
  // create game display user list
  game.provide('displayUserList', function(data) {
		getOnlineUserList();
	
  });

  // create game.getGameId
  game.provide('getGameId', function() {
	return gameId;
  });
	
   // create game.setGameId
  game.provide('setGameId', function(id) {
    gameId = id;
  });
  // create game.getGameId
  game.provide('getChallengeId', function() {
	return challengeId;
  });
	
   // create game.setGameId
  game.provide('setChallengeId', function(id) {
    challengeId = id;
  });
   // create game.sendInvite
  game.provide('sendInvite', function(item) {
	
	var partnerId =  item.getAttribute("userId");
	
	var site = new Site();
	var data = { "partnerId":partnerId, "gameId" :gameId};
	site.challengePlayer(data,afterChallenge);
	
  });
   // create game.getChallenges
  game.provide('getChallenges', function(item) {
	
	var site = new Site();
	var data = {};
	site.getChallenges(data,updateChallenges);
	
  });
  // cancel a challenge
  game.provide('cancelChallenge', function(item) {
	
	var challengeId = $(item).parent().parent().attr("challenge_id");
	var site = new Site();
	var data = {"challengeId" : challengeId};
	site.cancelChallenge(data,afterCancelChallenges);
	
  });
    // cancel a challenge
  game.provide('getStatus', function() {

	var site = new Site();
	var data = {"challengeId" : game.getChallengeId()};
	site.getChallengeStatus(data,processStatus);
	
  });
    // start playing game from challenge panel
  game.provide('startGameFromChallenge', function(item) {

	var challengeId = $(item).parent().parent().attr("challenge_id");
	var site = new Site();
	var data = {"challengeId" : challengeId};
	game.setChallengeId(challengeId);
	site.acceptChallenge(data,game.getStatus);
	
  });
    // start game
  game.provide('start', function() {

	var site = new Site();
	var data = {"challengeId" : game.getChallengeId()};
	site.getGame(data,board.startPlaying);
	
  });
  game.provide("getScore",function(){
	
	var site = new Site();
	site.getScore({},processScore);
	
  });
  game.provide("reset",function(){
	
	var site = new Site();
	site.resetGame({"boardId":board.getId(),"challengeId":game.getChallengeId()},board.processBoard);
	
  });
	/****** private functions *****/
	function getOnlineUserList(){
		var site = new Site();
		site.getUserList( populateUserList);
	}
	//populate User list call back function from ajax call
	function populateUserList(response){
		
		//find the online user list
		var userList = $("#online_users").find("ul");
		userList.find('.users').remove();
		
		if(response.data){
		
				$.each(response.data,function(index){
					userList.append(createListItem(this));
				});
				
				//show online user list
				$("#online_users").removeClass("hide");
				//hide game list
				$("#game_list").animate({
									left: "-100%"
								},500,function(){
								
									});
			}else if(response.error){
			
				if(response.error.isLoginRequired){
					showLoginModal();
				}			
			}
	}
	//Process score 
	function processScore(response){
	
		if(response.data){
					var table = $("#score");
					table.empty();
				$.each(response.data,function(index){
						//	table.append(getChallengeRow(this));
						var row = getChallengeRow(this);
		
						document.getElementById("score").appendChild(row);
					});
					setTimeout(game.getScore,2000);
				}
			else if(response.error){
		
			if(response.error.isLoginRequired){
				showLoginModal();
			}			
		}
	}
	//populate User list call back function from ajax call
	function updateChallenges(response){
		
		if(response.data){
			var table = $("#challenges");
			table.empty();
			if(!response.data.error){
		
					$.each(response.data,function(index){
						//	table.append(getChallengeRow(this));
						var row = getChallengeRow(this);
						var buttonHolder = document.createElement("td");
						//create start button
						var startButton = document.createElement("button");
						startButton.setAttribute("class","btn btn-mini btn-success");
						startButton.setAttribute("type","button");
					
						//create cancel button
						var cancelButton = document.createElement("button");
						cancelButton.setAttribute("class","btn btn-mini btn-danger");
						cancelButton.setAttribute("type","button");
						
						if(document.all){
							startButton.innerText = "start";
							cancelButton.innerText = "cancel";
						}
						else{
							startButton.textContent = "start";
							cancelButton.textContent = "cancel";
						}
						
						var oki = document.createElement("i");
						oki.setAttribute("class"," icon-ok icon-white");
						startButton.appendChild(oki);
						var ci = document.createElement("i");
						ci.setAttribute("class"," icon-remove icon-white");
						cancelButton.appendChild(ci);
						
						//add start button event listener to the list object
						if (startButton.addEventListener) {
								startButton.addEventListener('click', function(){game.startGameFromChallenge(this)}, false); 
						} else if (link.attachEvent)  {
								startButton.attachEvent('onclick', function(){game.startGameFromChallenge(this)});
						}
						//add cancel button event listener to the list object
						if (startButton.addEventListener) {
								cancelButton.addEventListener('click', function(){game.cancelChallenge(this)}, false); 
						} else if (link.attachEvent)  {
								cancelButton.attachEvent('onclick', function(){game.cancelChallenge(this)});
						}
						
						buttonHolder.appendChild(startButton);
						buttonHolder.appendChild(cancelButton);
						
						row.appendChild(buttonHolder);
						document.getElementById("challenges").appendChild(row);
					});
				}
				setTimeout(game.getChallenges,2000);
			}else if(response.error){
			
				if(response.error.isLoginRequired){
					showLoginModal();
				}			
			}
	}
	/*
	* After cancel challenge call what needs to be done is defined here
	*/
	function afterCancelChallenges(response){
	
		if(response.data){
		
			if( response.data.error){
				new Site().showError(response.data.error);
			}
		
		}else if(response.error){
			
				if(response.error.isLoginRequired){
					showLoginModal();
				}else{
					new Site().showError(response.error);
				}				
		}
	}
	//Get Challenge row
	//getRow will create a row in table
	function getChallengeRow (data){
	
		var tr = document.createElement("tr");
		
		$.each(data, function(i, field){
				var td = document.createElement("td");
				if(document.all){
					td.innerText = field;
				}
				else{
					td.textContent = field;
				}
			tr.setAttribute(i,field);
			tr.appendChild(td);
		});
		
		return tr;	
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
					link.addEventListener('click', function(){game.sendInvite(this)}, false); 
			} else if (link.attachEvent)  {
					link.attachEvent('onclick', function(){game.sendInvite(this)});
			}
		var span = document.createElement('span');
			span.className = item.online_status;
		var li = document.createElement('li');
			li.className = "users";
			li.appendChild(span);
			li.appendChild(link);
			
		return li;
	};
	
	/*
	* After challenge method - does what to be done after challenged a player
	*/
	function afterChallenge(response){
	
		if(response.data){
			
			$("#gameWindow").removeClass("hide");
			$("#online_users").animate({
											left: "-100%"
										},500,function(){
								
										});
			  game.setChallengeId(response.data.challenge_id);
			  game.getStatus();
			}else if(response.error){
			
				if(response.error.isLoginRequired){
					showLoginModal();
				}			
				else{
					new Site().showError(response.error);
				}
			}
	}
	
	/*
	* processStatus
	*
	*/
	function processStatus(response){
		
		console.log(response);
		if(response.data){
			if( !response.data.error){
				 //  game.getStatus();
				 if( response.data.status != "accepted" )
					setTimeout(game.getStatus,1000);
				else{
					game.start();
				}
			}
			else{
				var site = new Site();
				site.showError(response.data.error);
				site.restoreHome();
			}
		}else if(response.error){
			
				if(response.error.isLoginRequired){
					showLoginModal();
				}			
				else{
					new Site().showError(response.error);
				}
		}
	}
	
	
  return game;
  
})();
