<?php
include(VIEW_TEMPLATE_PATH . 'head.php');
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
      <!--Sidebar content-->
	  <div class="dropdown">
	  <ul class="nav  nav-list sidenav" role="menu" aria-labelledby="dropdownMenu" style="display:block">
		  <li class="active"><a tabindex="-1" targetSection="mainSection" href="#" ><i class="icon-chevron-right pull-right"></i>Home</a>
		  </li>
		  <li><a tabindex="-1" targetSection="challengeSection" href="#"><i class="icon-chevron-right pull-right"></i>Challenges</a></li>
		  <li><a tabindex="-1" targetSection="scoreSection" href="#"><i class="icon-chevron-right pull-right"></i>ScoreBoard</a></li>
		  <li><a tabindex="-1" targetSection="public_chat" href="#"><i class="icon-chevron-right pull-right"></i>Public Chat</a></li>
	  </ul>
	  </div>
    </div>
    <div class="span8">
      <!--Body content-->
	  <section id="main_content">
		  <section id="mainSection" >
		  <div id="game_list">
			<div class="tile" gameId="1" >
					<div class="front">	<img src="view/img/connect_four.gif" width="75" height="75" />					
							Connect Four					
						<div class="note"> 
							Four Up, Plot Four, Find Four, Fourplay, Four in a Row and Four in a Line
						</div>
					</div>
			</div>
		</div>
		<div id="online_users" class="hide">
			<ul class="userlist"  role="menu" style="display:block" aria-labelledby="dropdownMenu">
				<!-- dropdown menu links -->
			  <li class="nice_header" id="userHeader"><a tabindex="-1" class="userName" href="#">Online Users <i class="icon-remove icon-white pull-right" id="closeUserList"></i></a></li>
			  <li class="divider"></li>
			</ul>
		</div>
		<div id="gameWindow" class="hide">
			<div id="gameBoard" class="hide">
			<button id="resetGame" class="btn" onClick="game.reset()"> Reset Game</button>
			<svg xmlns="http://www.w3.org/2000/svg" version="1.1"  width="900px" height="700px">
				<defs>
				   <radialGradient id="bgGrad" cx="0.5" cy="0.5" r="1.15">
							<stop offset="0" stop-color="#FFF" stop-opacity="1"/>
						  <stop offset="1" stop-color="#000" stop-opacity="1"/>
				   </radialGradient>
					<linearGradient id="bbgGrad" x1="0" y1="0" x2="1" y2="0">
						<stop offset="0" stop-color="#999"/>
						<stop offset="0.6" stop-color="#111"/>
					 </linearGradient>
					<g id="player">
					  <rect x="10" y="36" width="40" height="20" rx="20" ry="10"/>
					  <circle cy="24" cx="30" r="14"/>
					</g>
					<path id="logo2" d="M40.8,10.5C24.3,10.5,11,23.8,11,40.3S24.3,70,40.8,70s29.8-13.3,29.8-29.8S57.2,10.5,40.8,10.5z M50.9,52.1
					  h-3.7v7.5c0,1.9,0,1.9-5.8,1.9v-9.4h-13c-1.4-1.4-1.9-3.5-1.9-5.2c0-3.5,9.5-23.4,15-28.1c0.9-0.6,1.7-1,2.7-1
					  c1.4,0,2.6,0.4,3.3,0.9l-0.2,27.9c2.8,0,5.3-0.2,5.3-0.2s0.3,0.6,0.3,2.1C52.8,50.4,52.3,52.1,50.9,52.1z M41.4,46.7l0.8-20.7
					  c-3.5,4-9.1,14.3-10.7,20.7H41.4z"/>
				</defs>
				 <g id="arrow" fill="#111">
					<path d="M12 30 L 22 10 L 12 14 L 2 10 Z"/>
				  </g>
			 <g >
				 <text x="270px" y="20px" id="youPlayer" display="none" >
					Your TURN:
				</text>
				<text x="270px" y="20px" id="nyt" fill="red"  display="none">
					NOT YOUR TURN!
				</text>
				</g>
				 <g transform="translate(400 500)">
				  <use fill="#111" xlink:href="#player" x="270" transform="scale(0.3)"/>
				  <text fill="#111" id="player0" x="99" y="15.5" font-weight="bold">0</text>
				  <use fill="#B59EC7" xlink:href="#player" x="365" transform="scale(0.3)"/>
				  <text fill="#B59EC7" id="player1" x="127" y="15.5" font-weight="bold">0</text>				 
				</g>
			</svg>
			</div>
			<div id="gameInProgress">
			<p> waiting...</p>
				<div id="gameLoader"></div>
			</div>
		</div>
	</section>
	   <section id="challengeSection" class="hide">
		<div id="challengeWindow" >
				<table  class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Game</th>
							<th>Player1</th>
							<th>Player2</th>
							<th>TimeStamp</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="challenges">
					</tbody>
				</table>
		</div>
	  </section>
	   <section id="scoreSection" class="hide">
	   <div id="scoreWindow" >
				<table  class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Player</th>
							<th>Score</th>
							<th>Total Games</th>
							<th>Wins</th>
							<th>BestTime</th>
						</tr>
					</thead>
					<tbody id="score">
					</tbody>
				</table>
		</div>
	  </section>
		<section id="public_chat" class="hide" >
				<div id="public_chat_area">
					<div class="chatbox publicbox">
						<div class="chat_header"><span id="chatName">PUBLIC CHAT</span> 
						</div>
						<div class="chat_messages" id="public" >
					 </div>
					<form onsubmit="return chat.chatMessageHandler(this)" chat_id ="public">
						  <div class="chat_input">
							<input type="text" class="message" name="message"></input>
						  </div>
						  <button type="submit"  class="btn hide">Submit</button>
					</form>
					</div>
				</div>
			</section>
	 </section>
	</div>
	<div class="span2">
	<!--chat content-->
	<div class="message_block well well-small hide" id="chat_message_template"> 
						<div class="title" ><small><strong><span class="sender">Saravana Kumar </span></strong></small></div>
							<div class="chat"> 
								<small>
									<p class="message">
									This line of text is meant to be treated as fine print.
									</p>
								</small>
							</div>
								<div class="divider clear"></div>
							<div class="timestamp">
								<small>
									<span class="timeStamp"> adasdsadasdasdas </span>
								</small>
							</div>
	</div>
	<section id="chat_content">
		<div id="chat_area">
		<div id="chat_template" class="chatbox hide">
				  <div class="chat_header"><span id="chatName">Chat Name</span> 
				  <span class="chat_header_buttons pull-right" ><i class="icon-minus icon-white"></i> <i class="icon-remove icon-white closeButton"></i> </span>
				  </div>
				 
				  <div class="chat_messages" >
					
				  </div>
				  
			<form >
				  <div class="chat_input">
					<input type="text" class="message" name="message"></input>
				  </div>
				  <button type="submit"  class="btn hide">Submit</button>
			</form>
		</div>
		</div>
			<ul class="chat_content" role="menu" style="display:block" aria-labelledby="dropdownMenu">
				<!-- dropdown menu links -->
			  <li class="nice_header" id="chatHeader"><a tabindex="-1" class="userName" href="#"><?php echo isset($_SESSION['name']) ?  $_SESSION['name'] :  ""; ?></a></li>
			  <li class="divider"></li>
		   </ul>
	</section>
	</div>
  </div>
</div>

<?php
include(VIEW_TEMPLATE_PATH . 'footer.php');
?>