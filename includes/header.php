<?php
$truetime = time();
$cooldown = 60;

$timemodulus = $truetime % $cooldown;
$time = $truetime - $timemodulus;

$lastcachedtime = file_get_contents($_SERVER['DOCUMENT_ROOT']."/api_connect/cache_time.txt");
$querydiff = $truetime - $lastcachedtime;

$cachetime = fopen($_SERVER['DOCUMENT_ROOT']."/api_connect/cache_time.txt", "w");
fwrite($cachetime, $time);
fclose($cachetime);

if ($querydiff >= $cooldown) {
	updateOnline();
}

function updateOnline() {
	$cachedonline = fopen($_SERVER['DOCUMENT_ROOT']."/api_connect/cached_online.txt", "w");
	$txt = json_decode(file_get_contents("http://95.217.92.207:25670/api/method/server.getonline"))->response->online;
	fwrite($cachedonline, $txt);
	fclose($cachedonline);
};

$online = file_get_contents($_SERVER['DOCUMENT_ROOT']."/api_connect/cached_online.txt");
if ($online == "Connection refused") {
	$online = 'Ошибка';
}
?>
<div class="griH">
	<nav class="navbar navbar-expand-lg navbar-dark header">
		<div class="container-fluid" id="topmenu">
			<a href="/index.php" title="Главная">			
				<img class="main d-flex flex-grow-1" src="/logo2.png">
			</a>
			<div class="main-info ip" onclick="ipcopy();"><span tabindex="0" data-toggle="popover" data-content="Скопировано!" data-trigger="focus">IP: mc.buildcube.ru</span></div>
			<div class="main-info">Онлайн: <?php echo $online; ?></div>
			<button class="navbar-toggler" type="button" id="dabutton" data-toggle="collapse" data-target="#collapsibleNavbar" aria-expanded="false">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
				<ul class="navbar-nav">
					<li class="nav-item" id="indexlink"><a class="nav-link" href="/" title="Главная" id="indexlink">Главная</a></li>
					<li class="nav-item" id="shoplink"><a class="nav-link" href="/shop/" title="Магазин" id="shoplink">Магазин</a></li>
					<li class="nav-item" id="ruleslink"><a class="nav-link" href="/rules/" title="Правила" id="ruleslink">Правила</a></li>
					<li class="nav-item" id="forumlink"><a class="nav-link" href="/forum/" title="Форум" id="forumlink" target="_blank">Форум</a></li>
				</ul>
			</div>
		</div>
	</nav>
</div>
<div class="griTM"></div>