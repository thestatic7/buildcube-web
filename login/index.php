<?php
	if(isset($_SESSION["userid"])) {
		header("location: /panel/");
		exit;
	}
	require($_SERVER['DOCUMENT_ROOT'].'/auth/auth-script.php');
?>

<!DOCTYPE html>
<html>
	<head>		
		<title>BuildCube</title>
		<title id="title">index</title>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/head.php') ?>
			<!-- grid body -->
			<div class="griMB" id="griMB">
				<div class="bgpic">
                    <div class="mainpage">
                        <div class="vkwidnoflex">
                            <div class="regformwrap">
                                <h1>Авторизация</h1>
                                <div class="regform">
                                    <span>Войдите в систему, используя ваши данные с сервера:</span>
                                    <form action="#" method="post" class="regform">
                                        <label for="usernickname">Никнейм</label><br>
                                        <input name="usernickname" id="usernickname" type="text"><br><br>			
                                        <label for="usernickname">Пароль</label><br>
                                        <input name="userpassword" id="userpassword" type="password"><br><br><br>	
										<input class="checkbox" type="checkbox" name="remember" id="remember"
										<?php if(isset($_COOKIE["member_login"])) { ?> checked
										<?php } ?> /> <label for="remember-me">Запомнить меня</label><br>
                                        <input type="submit" name="submit_btn" value="Войти" class="loginbtn"><br>
                                        <?php echo $message; ?>
                                    </form>
                                    <a href="/register/">Зарегистрироваться</a><br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				</div>
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php') ?>
	</body>
</html>