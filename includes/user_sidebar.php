<div class="authwindow">
    <div class="newsheader">
        <h2>Авторизация</h2>
    </div>
    <div class="sidebar_wrap">
        <span>Вы авторизованы как <b><?php echo $_SESSION["user"] ?></b></span><br />
        <img src="/logo2.png" class="sidebarimg"><br>
        <form action="/panel/">
        <input type="submit" value="Личный кабинет" class="loginbtn widebtn">
        </form>
        <span>На вашем счёте <b><?php echo $balance; ?></b> кубов<br /><br /><br />
        <form method="post" action="#">
             <input class="logoutbtn" name="logout" type="submit" value="Выйти">
        </form>
    </div>
</div>