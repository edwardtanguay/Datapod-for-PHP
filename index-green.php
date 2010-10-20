<?php 
include_once('config.php'); 
$currentPageIdCode = $datapodManager->getCurrentPageIdCode();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?php echo Config::getSiteTitle(); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/main-green.css" />
</head>

<body id="altbody">

<div id="wrapper-header">
	<div id="header">
		<h1><?php echo Config::getSiteTitle(); ?></h1>
		<h2><?php echo Config::getSiteSubtitle(); ?></h2>
	</div>
</div>

<div id="wrapper-menu">
	<div id="menu">
		<?php $datapodManager->display('pageItems', 'getMenuHtml'); ?>
	</div>
</div>
<div id="content">


<?php include_once('includes/pageDatapodIntroduction.php'); ?>			

</div>

<div id="footer">this website was created with <a href="http://www.tanguay.info/datapod">datapod</a> by <a href="http://www.tanguay.info">edward tanguay</a> original design by <a href="http://www.tristarwebdesign.co.uk">tri-star web design</a></div>

</body>

</html>