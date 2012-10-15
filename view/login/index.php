<?php
include(VIEW_TEMPLATE_PATH . 'head.php');
?>
<form class="form-horizontal">
  <div class="control-group">
    <label class="control-label" for="inputEmail">Email</label>
    <div class="controls">
      <input type="text" id="inputEmail" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputPassword">Password</label>
    <div class="controls">
      <input type="password" id="inputPassword" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Sign in</button>
	  <a href="#"> Register </a>
    </div>
  </div>
</form>

<?php
include(VIEW_TEMPLATE_PATH . 'footer.php');
?>