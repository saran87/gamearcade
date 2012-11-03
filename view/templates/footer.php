
<div>

  <footer>
        <p>Connect4 2012</p>
  </footer>
</div>
 <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<!-- dont change the order bootstrap needs jquery -->
	<script src="view/js/jquery-1.8.2.min.js"></script>
    <script src="view/js/bootstrap.min.js"></script>
    <script src="view/js/login.js"></script>
    <script src="view/js/site.js"></script>
    <script src="view/js/chat.js"></script>
		<script>
		function onload () {
			
		//start updating the chat panel
		  chat.updateChatPanel();
		 //start getting the messages for the user
		  chat.getMessages();
		  //Add animation method to chat header
		  $('#chatHeader').toggle(function() {
			  $('.chat_content').animate({
									height: "30"
								}, 500, function() {
									// Animation complete.
								});
			}, function() {
			   $('.chat_content').animate({
									height: "250px"
								}, 500, function() {
									// Animation complete.
								});
			});
			//side nav scripts
			$('.sidenav').find('li').click(function(){
				$(this).parent().find('.active').removeClass('active');
				$(this).addClass('active');
				if($(this).find('a').attr("targetSection")){
					var section = $(this).find('a').attr("targetSection");
					var targetSection = $(document.getElementById(section));
					targetSection.parent().find('section').slideUp('slow');
					targetSection.slideDown("slow");
				}
			});
		}
		
		window.addEventListener('DOMContentLoaded', onload);
		</script>
	
</body></html>