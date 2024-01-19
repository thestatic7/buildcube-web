<?php
	$balance = 0;
	require($_SERVER['DOCUMENT_ROOT'].'/auth/auth-script.php');
	if (!isset($_SESSION["userid"])) {
		$sidebar = 'auth';
	} else {
		$sidebar = 'user_sidebar';
	}
	if (isset($_REQUEST["logout"])) {
		require($_SERVER['DOCUMENT_ROOT'].'/auth/logout.php');
		session_destroy();
		exit;
	}
	include($_SERVER['DOCUMENT_ROOT'].'/api_connect/getbalance.php');
?>
<!DOCTYPE html>
<html>
	<head>		
		<title>BuildCube</title>
		<title id="title">index</title>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/head.php') ?>
			<!-- grid body -->
			<div class="griMB" id="griMB">
				<div class="flexingmain" id="flexingmain">
				<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/index/carousel-buttons.php') ?>
				</div>
				<div class="bgpic">
				<div class="mainpage">
					<div class="vkwid">
					<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/newstemplate.php') ?>
					</div>
					<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/index/team.php') ?>
				</div>
				</div>
			</div>
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php') ?>
		<script src="/scripts/pagination.js"></script>
		<script src="/scripts/scrollhandler.js"></script>
	</body>
</html>