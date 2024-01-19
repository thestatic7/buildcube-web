<?php
	// require($_SERVER['DOCUMENT_ROOT'].'/auth/session.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title id="actualtitle">Магазин BuildCube</title>
		<aside id="title">shop</aside>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/head.php') ?>
			<!-- grid body -->
			<div class="griMB" id="griMB">
				<div class="mainpage">
					<div class="vkwid" style="display: block;">
					<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/shop/panel.php') ?>	
					</div>
				</div>
			</div>
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php') ?>
			<script type="text/javascript" src="/scripts/crypto.js"></script>	
			<script type="text/javascript" src="/scripts/shop.js"></script>
	</body>
</html>