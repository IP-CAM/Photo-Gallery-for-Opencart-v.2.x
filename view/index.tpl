<!DOCTYPE html>
<html>
<head>
	<title>Photo hosting</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha256-l85OmPOjvil/SOvVt3HnSSjzF1TUMyT9eV0c2BzEGzU=" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" type="text/css" href="/css/main.css">	
</head>
<body class="home">
	<div class="notifications"></div>
	<div class="container">
		<div class="login-form">
			<div class="login-form__box">
				<form name="login" method="POST" class="form">
					<div class="form__input-group">
						<label for="login__login">Login:</label>
						<input type="text" name="login" id="login__login">
					</div>
					<div class="form__input-group">
						<label for="login__password">Password:</label>
						<input type="password" name="password" id="login__password">
					</div>
					<div class="login-form__actions">
						<a href="#" id="form__register" class="form__button login-form__button _register">Register</a>
						<a href="#" id="form__login" class="form__button login-form__button _login">Login</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script
	  src="https://code.jquery.com/jquery-3.4.1.min.js"
	  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	  crossorigin="anonymous" defer></script>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js" defer></script>
	<script type="text/javascript" src="/js/scripts.js" defer></script>

</body>
</html>