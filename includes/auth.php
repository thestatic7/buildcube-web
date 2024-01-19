<div class="authwindow">
    <div class="newsheader">
        <h2>Авторизация</h2>
    </div>
    <div class="logform">
        <form action="#" class="logform" method="post">
            <label for="usernickname">Никнейм</label><br>
            <input id="usernickname" type="text" name="usernickname"><br><br>
            <label for="usernickname">Пароль</label><br>
            <input id="userpassword" type="password" name="userpassword"><br><br><br>
            <input class="checkbox" type="checkbox" name="remember" id="remember"
            <?php if(isset($_COOKIE["member_login"])) { ?> checked
		    <?php } ?> /> <label for="remember-me">Запомнить меня</label><br>
            <input type="submit" value="Войти" class="loginbtn" name="submit_btn">
        </form>
        <?php echo $message; ?><br>
        <a href="/register/">Зарегистрироваться</a><br><br>
    </div>
</div>