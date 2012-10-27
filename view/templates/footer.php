
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
		  chat.updateChatPanel();
		  $('#chatHeader').toggle(function() {
			  $('.chat_content').animate({
									height: "40"
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
		}
		window.addEventListener('DOMContentLoaded', onload);
		</script>
	
</body></html>