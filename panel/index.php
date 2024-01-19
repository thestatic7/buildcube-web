<?php
// start the session
require($_SERVER['DOCUMENT_ROOT'].'/auth/session.php');
session_start();

// Check if the user is not logged in, then redirect the user to login page
if (!isset($_SESSION["userid"])) {
    header("location: /login/");
    exit;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Личный кабинет</title>
		<title id="title"></title>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/head.php') ?>
			<!-- grid body -->
			<div class="griMB" id="griMB">
                    <div class="mainpage">
                        <div class="vkwidnoflex" id="panel-content">
                            <div class="moreflex">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <?php echo $error; ?>
                    </div>
                </div>
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php') ?>
        <script type="text/javascript" src="/scripts/panel.js"></script>
	</body>
</html>
