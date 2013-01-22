{extends file='index.html'}
{block name=content}
<div id="loginForm">
<form method="post" action="/login/" />
     username: <input type="text" name="username" /><br /><br />
     password: <input type="password" name="password" /><br /><br />
     Remember me? <input type="checkbox" name="remember" value="1" /><br /><br />
     <input type="submit" value="login" />
</form>
</div>
{/block}