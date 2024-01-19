<?php
    $err = "";
    $success = "";
    if(isset($_POST['usercode']) && !empty($_POST['usercode'])) {
        $code = trim(htmlspecialchars($_POST["usercode"]));
        $link = 'http://95.217.92.207:25670/api/method/regsite.register?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&code=' . $code;
        $info = file_get_contents($link);
        $jsonarray = json_decode($info, true);
        function display_array_recursive($json_rec){
            global $err; 
            global $success;
            if($json_rec){
                foreach($json_rec as $key=> $value){
                    if(is_array($value)){
                        display_array_recursive($value);
                    } else {
                        if($key=="error") {
                        $err = '<p style="color:red;text-align: center;">Неверный код!</p>';
                        return 0;
                        } else {
                        $success = '<p style="color:green;text-align: center;">Вы успешно зарегистрированы.</p>';
                        return 0;
                        }
                    }	
                }	
            }	
        }
        display_array_recursive($jsonarray);
    }
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
                                <h1>Регистрация на BuildCube</h1>
                                <div class="regform">
                                <span>Для того, чтобы зарегистрироваться на сайте BuildCube, вам необходимо:<br>
                                    <br>- прописать команду <code>/regsite</code> на сервере;<br>
                                    - ввести полученный в чате код в поле ниже.</span>
                                    <form action="#" class="regform" method="post">
                                        <h2>Код регистрации</h2>
                                        <input id="usercode" type="text" name="usercode"><br><br><br>
                                        <input type="submit" value="Зарегистрироваться" class="loginbtn">
                                    </form>
                                    <?php echo $err; ?>
                                    <?php echo $success; ?>
                                    <br><span>Уже есть аккаунт? <a href="/login/">Войти</a></span>
                                </div>
                            </div>  
                        </div>
                    </div>
				</div>
			</div>
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php') ?>
	</body>
</html>