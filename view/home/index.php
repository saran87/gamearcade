<?phpinclude(VIEW_TEMPLATE_PATH . 'head.php');?><div class="container-fluid">  <div class="row-fluid">    <div class="span2">      <!--Sidebar content-->	  <div class="dropdown">	  <ul class="nav  nav-list sidenav" role="menu" aria-labelledby="dropdownMenu" style="display:block">		  <li class="active"><a tabindex="-1" href="#" ><i class="icon-chevron-right pull-right"></i>Home</a>		  </li>		  <li><a tabindex="-1" href="#"><i class="icon-chevron-right pull-right"></i>Challenges</a></li>		  <li><a tabindex="-1" href="#"><i class="icon-chevron-right pull-right"></i>ScoreBoard</a></li>		  <li><a tabindex="-1" href="#"><i class="icon-chevron-right pull-right"></i>Chat</a></li>	  </ul>	  </div>    </div>    <div class="span8">      <!--Body content-->	  <section id="main_content">		  <p> hello </p>			</section>	</div>	<div class="span2">	<!--chat content-->	<div class="message_block well well-small hide" id="chat_message_template"> 						<div class="title" ><small><strong><span class="sender">Saravana Kumar </span>:</strong></small></div>							<div class="chat"> 								<small>									<p class="message">									This line of text is meant to be treated as fine print.									</p>								</small>							</div>								<div class="divider clear"></div>							<div class="timestamp"><small><span class="timeStamp"> adasdsadasdasdas </span></small></div>	</div>	<section id="chat_content">		<div id="chat_area">		<div id="chat_template" class="chatbox hide">				  <div class="chat_header"><span id="chatName">Chat Name</span> <span class="pull-right"><i class="icon-minus"></i> <i class="icon-remove closeButton"></i> </span></div>				 				  <div class="chat_messages" >									  </div>				  			<form>				  <div class="chat_input">					<input type="text" class="message" name="message"></input>				  </div>				  <button type="submit"  class="btn hide">Submit</button>			</form>		</div>		</div>			<ul class="chat_content" role="menu" style="display:block" aria-labelledby="dropdownMenu">				<!-- dropdown menu links -->			  <li class="nice_header" id="chatHeader"><a tabindex="-1" class="userName" href="#"><?php echo isset($_SESSION['name']) ?  $_SESSION['name'] :  "chat"; ?></a></li>			  <li class="divider"></li>		   </ul>	</section>	</div>  </div></div><?phpinclude(VIEW_TEMPLATE_PATH . 'footer.php');?>