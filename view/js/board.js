
	var xhtmlns = "http://www.w3.org/1999/xhtml";
	var svgns = "http://www.w3.org/2000/svg";
	var BOARDX = 50;				//starting pos of board
	var BOARDY = 50;				//look above
	var boardArr = new Array();		//2d array [row][col]
	var pieceArr = new Array();		//2d array [player][piece] (player is either 0 or 1)
	var BOARDWIDTH = 7;				//how many squares across
	var BOARDHEIGHT = 6;			//how many squares down
	//the problem of dragging....
	var myX;						//hold my last pos.
	var myY;						//hold my last pos.

var board = (function() {
  var board = {
    // this will naively create new properties on game object
    provide: function(name, fn) {
      this[name] = fn;
    }
  };
  var boardId;
  var playerId;
  var isMyTurn;
  var mover = "arrow";					//hold the id of the thing I'm moving
  board.setId = function(id){
	this.boardId = id;
  };
  board.getId = function(){
	return this.boardId;
  };
   board.setPlayerId = function(id){
	this.playerId = id;
  };
  board.getPlayerId = function(){
	return this.playerId;
  };
  board.setMyTurn = function(turn){
	this.isMyTurn = turn;
  };
   board.isMyTurn = function(){
	return this.isMyTurn;
  };
  board.getArrow = function(){
	return this.mover;
  };
  //start playing the game with the board Id from the response
  board.startPlaying = function(response){
	
	if(response.data){
			
			if( response.data.error){
				new Site().showError(response.data.error);
			}else{
					new Site().showGameWindow();
					console.log(response);
					var boardId = response.data.boardId;
					board.setId(boardId);
					var playerId = response.data.playerId;
					board.setPlayerId(playerId);
					//make the board visible
					$("#gameBoard").removeClass("hide");
					$("#gameInProgress").addClass("hide");
					//initialize the board
					initBoard();
					//set your turn false initially
					board.setMyTurn(false);
					board.getStatus();
			}
		
		}else if(response.error){
			
				if(response.error.isLoginRequired){
					showLoginModal();
				}else{
					new Site().showError(response.error);
				}				
		}
	};
	
	//Get game status
	
	board.getStatus = function(){
	
		var site = new Site();
		var data = {"boardId" : board.getId()};
		site.getGameStatus(data,processBoard);
	};
	
	board.dropPiece = function(item){
	
	if(board.isMyTurn){
			 var id = item.id.split("|");
			 
			 if(id[1]){
				var column = id[1];
				
				for(var i = BOARDHEIGHT-1; i>=0; i--){
					
					var piece = pieceArr[i][column]; 
					console.log(piece.isCaptured);
					if(!piece.isCaptured){
						console.log(piece);
						piece.occupy(board.getPlayerId());
						break;
					}
				}
				var data = {"boardId" : board.getId(),"challengeId" : game.getChallengeId(),"column":column}
				var site = new Site();
				site.updateGame(data,processBoard);
				board.setMyTurn(false);	
			}
		}
		else{
			alert("Wait for your opponent to finish");
		}
	
	};
	
	
	//private methods	
	
	function initBoard(){
	
		//create a parent to stick board in...
		var gEle=document.createElementNS(svgns,'g');
		var gameId = 'game_'+ board.getId();
		gEle.setAttributeNS(null,'transform','translate('+BOARDX+','+BOARDY+')');
		gEle.setAttributeNS(null,'id',gameId);
		//stick g on board
		document.getElementsByTagName('svg')[0].insertBefore(gEle,document.getElementsByTagName('svg')[0].childNodes[7]);
		//create the board...
		//var x = new Cell(document.getElementById('someIDsetByTheServer'),'cell_00',75,0,0);
		for(var i=0;i<BOARDHEIGHT;i++){
			boardArr[i]=new Array();
			for(var j=0;j<BOARDWIDTH;j++){
				boardArr[i][j]=new Cell(document.getElementById(gameId),'cell|'+j,75,j,i);
			}
		}
	
		//new Piece(board,player,cellRow,cellCol,type,num)
		//create red
	
		for(var i=0;i<BOARDHEIGHT;i++){
				pieceArr[i]=new Array();
			for(var j=0;j<BOARDWIDTH;j++){
					pieceArr[i][j]=new Piece(gameId,0,i,j,'Coin',j);
			}
		}
				

		//put the drop code on the document...
		document.getElementById(gameId).addEventListener('mouseup',board.releaseMove,false);
		//put the go() method on the svg doc.
		document.getElementById(gameId).addEventListener('mousemove',board.go,false);
		//put the player in the text
	
	
		//set the colors of whose turn it is
		/*if(turn==playerId){
			document.getElementById('youPlayer').setAttributeNS(null,'fill',"orange");
			document.getElementById('opponentPlayer').setAttributeNS(null,'fill',"black");
		}else{
			document.getElementById('youPlayer').setAttributeNS(null,'fill',"black");
			document.getElementById('opponentPlayer').setAttributeNS(null,'fill',"orange");
		}*/
		this.mover = "arrow";
	}
	
	
	function processBoard(response){
			
		if(response.data){
			
			if(response.data.error){
				new Site().showError(response.data.error);
				if(response.data.isMyTurn){
					var turn = response.data.isMyTurn;
					board.setMyTurn(turn);
				}
			}else{
			
				var turn = response.data.isMyTurn;
				board.setMyTurn(turn);
				
				if(response.data.cur_state){
						var boardArray = getBoardArray(response.data.cur_state);
						updatePieces(boardArray);
						console.log(boardArray);
				}
				if(response.data.winner_id !=0 ){
				
					new Site().showError(response.data.message);
				}
				else{
					if(board.isMyTurn){
							$("#youPlayer").attr("display","block");
							$("#nyt").attr("display","none");
						
					}else{
						$("#youPlayer").attr("display","none");
							$("#nyt").attr("display","block");
						setTimeout(board.getStatus,2000);
					}	
				}				
			}		
		}else if(response.error){
			
			if(response.error.isLoginRequired){
					showLoginModal();
			}else{
					new Site().showError(response.error);
			}				
		}
	};
	//Update the pieces with recieved board array
	function updatePieces(boardArray){
	
		for(var row = 0; row < boardArray.length; row++){
		
			for( var col = 0; col < boardArray[row].length; col++){
				
				var piece = pieceArr[row][col];
				
				if(boardArray[row][col] == 0){
				
					piece.deOccupy(boardArray[row][col]);
				}
				else if(!piece.isCaptured){
					piece.occupy(boardArray[row][col]);
				}
			}
		
		}
	
	}
	
	//get board array from the currentstate of board
	function getBoardArray(state){
	
		var boardArray = state.split("|");
		
		for(var i = 0; i<boardArray.length; i++){
			if(boardArray[i])
				boardArray[i] = boardArray[i].split("-");
			else
				boardArray.splice(i,1);
		}
		
		return boardArray;
	}
	
	////releaseMove/////
	//	clear the id of the thing I'm moving...
	////////////////
	board.releaseMove = function(evt){
		//alert(evt);
		//check hit (need the current coords)
		// get the x and y of the mouse
		if(!board.getArrow()){
		//var hit=checkHit(evt.clientX,evt.clientY,board.getArrow());
	
		}
	}
				
				
	////go/////
	//	move the thing I'm moving...
	////////////////
	board.go = function(evt){
		if(!board.getArrow()){
			var offset = document.width / 5.396;
			var x  = evt.clientX - (document.getElementById("gameBoard").offsetLeft + 10);
			
			setTransform("arrow",x,evt.clientY);
		}
	}
	
	////set Transform/////
	//	look at the id, x, y of the piece sent in and set it's translate
	////////////////
	function setTransform(which,x,y){
		document.getElementById(which).setAttributeNS(null,'transform','translate('+x+',0) scale(1.5)');
	}
  
  return board;
  
})();