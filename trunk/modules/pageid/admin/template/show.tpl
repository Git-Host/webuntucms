<h3><?php echo $this->description['title'] ?></h3>
<div id="login" title="<?php echo $this->description['description'] ?>">
	<form action="" method="post" name="loginForm">
		<label>Nick</label><input type="text" id="login" name="login" value="user" />
		<label>Password</label><input type="password" id="pass" name="pass" value="pass" />
		<input type="submit" id="submitLogin" class="button login" value="Login"/>
	</form>
</div>
<a href="/">Odkaz na root webu</a>