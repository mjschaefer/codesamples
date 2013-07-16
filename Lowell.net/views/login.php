<h1>Login</h1>

<form id="login-form" method="post" action="<?=url::site('admin/authorize')?>">
  <label>Username</label>
  <input class="text" type="text" name="username"/>
  
  <label>Password</label>
  <input class="text" type="password" name="password"/>
  
  <input class="button" type="submit" value="Go!"/>
  <input class="button" type="reset" value="Clear!"/>
</form>