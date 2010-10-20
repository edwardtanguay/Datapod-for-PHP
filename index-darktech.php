<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
include_once('config.php');
$mainIndexFileHasBeenCalled = true;
$currentPageIdCode = $datapodManager->getCurrentPageIdCode();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo Config::getSiteTitle(); ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="css/main-darktech.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<h1><?php echo Config::getSiteTitle(); ?></h1>
	<p><?php echo Config::getSiteSubtitle(); ?></p>
</div>
<?php
$pageItems = $datapodManager->getItems('pageItems');
echo $pageItems->getMenuHtmlDiv(); 
?>
<!-- end header -->
<div id="banner">&nbsp;</div>
<!-- start page -->
<div id="wrapper">
	<div id="page">
		<div class="bgtop">
			<div class="bgbtm">
				<!-- start content -->
				
				<div id="content">
				
<?php include_once('includes/pageDatapodIntroduction.php'); ?>
					
				</div>
				<!-- end content -->
				<!-- start sidebar -->
				<div id="sidebar">
					<ul>
						<li>
							<h2>Welcome</h2>
							<p>
							<?php include_once('includes/textDatapodWelcome.php'); ?>
							</p>
						</li>
						
					</ul>
				</div>
				<!-- end sidebar -->
				<div style="clear:both">&nbsp;</div>
			</div>
		</div>
	</div>
</div>
<div id="footer">
<p>this website was created by <a href="http://www.tanguay.info">Edward
Tanguay</a> with <a href="http://www.tanguay.info/datapod">Datapod</a>,
original design by <a href="http://www.freecsstemplates.org">freecsstemplates</a></p>
</div>
<!-- end #footer -->
</body>
</html>
