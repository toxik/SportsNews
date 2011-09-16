<?php
	if (!defined('iNH_EXEC')) {
		header("HTTP/1.0 404 Not Found");
		header("Location: ../");
		exit('This page does not exist.');
	}
	
	$menu = new Model_Meniu(); 
	$auth = new Model_AuthBox();
	$rss  = new Model_Rss();
?><!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
	<base href="http://<?=$_SERVER['HTTP_HOST'].$basepath?>">
    <title><?=(isset($page['title'])?$page['title'].' | ':'').DEFAULT_TITLE?></title>
    <link href="/template/css/style.css?v=<?php echo filemtime('template/css/main.css'); ?>" rel="stylesheet" type="text/css" media="screen">
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/ui-lightness/jquery-ui.css" type="text/css" /> 
	<style type="text/css">.ui-widget { font-size: 10px }</style> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script> 
	<script type="text/javascript" src="/template/js/jquery.goodies.js"></script>
	<?php 
	if ($page['jsf'] && is_array($page['jsf']))
		foreach ($page['jsf'] as $script): ?>
		<script type="text/javascript" src="template/js/<?php echo $script; ?>.js"></script>';
	<?php endforeach;
	if ($page['cssf'] && is_array($page['cssf']))
		foreach ($page['cssf'] as $script): 
	?><link href="/template/css/<?php echo $script; ?>.css?v=<?php 
		echo filemtime('template/css/'.$script.'.css'); ?>" type="text/css" rel="stylesheet" media="screen"/>
	<?php endforeach; ?>
</head>
<body>
<div id="header">
	<div id="menu">
		<?php
			$menu->printMenu();
		?>
	</div>
	<div id="rightMenu">
		<?php
			$auth->printBox();
		?>
	</div>
	<!-- end #menu -->
</div>
<!-- end #header -->
<div id="logo">
	<h1><a href="/">Rezultate concursuri sportive</a></h1>
	<p><em> proiectul pentru <strong>opționalul</strong> de PHP &amp; MySQL</em></p>
</div>
<hr />
<!-- end #logo -->
<div id="page">
	<div id="page-bgtop">
		<div id="page-bgbtm">
			<div id="content">
				<?php if ($continut): ?>
				<div class="post">
					<div class="post-bgtop">
						<div class="post-bgbtm">
							<div class="entry">
								<?php echo $continut; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; /* ?>
				<!--
				<div class="post">
					<div class="post-bgtop">
						<div class="post-bgbtm stiri">
							<h2 class="title">Alte stiri</h2>
							<div class="entry" style="width: 49%; float: left">
								<?php $rss->printFeeds( array( 'Tennis' ) ); ?>
								<?php $rss->printFeeds( array( 'Soccer' ) ); ?>
							</div>
							<div class="entry" style="width: 49%; float: right">
								<?php $rss->printFeeds( array( 'Olympics' ) ); ?>
							</div>
							<br style="clear: both" />
						</div>
					</div>
				</div>
				-->
				 */ ?>
				 
				 <?php if ($_SERVER['APPLICATION_ENV'] == 'development'): ?>
				 <table align="center" border="1">
					<tr>
						<td><?php Zend_Debug::dump($_GET,' $_GET');?></td>
					</tr>
					<tr>
						<td><?php Zend_Debug::dump($_POST,' $_POST');?></td>
					</tr>
					<tr>
						<td><?php Zend_Debug::dump($_SESSION,' $_SESSION');?></td>
					</tr>
				</table>
				<?php endif; ?>
			</div>
			<!-- end #content -->
			<div id="sidebar">
				<?php 
				// print 2 random feeds
				$rss->printFeeds( $rss->pickTwoFeeds() ); 
				?>
			</div>
			<!-- end #sidebar -->
		</div>
	</div>
</div>
<div id="footer">
	<p class="left">Copyright &copy; 2011 <a href="http://php.toxik.us/">php.toxik.us</a></p>
	<div class="right">
		<?php  
			$menu->printMenu('about'); 
		?>
	</div>
</div>
<!-- end #footer -->
</body>
</html>
