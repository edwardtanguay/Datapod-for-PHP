<?php
include_once('config.php');
$currentPageIdCode = $datapodManager->getCurrentPageIdCode();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<link rel="stylesheet" type="text/css" href="css/main-notepad.css" />


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php echo Config::getSiteTitle(); ?></title>

</head>

<body>

<div id="box">

<div id="header">
<h1><?php echo Config::getSiteTitle(); ?></h1>
</div>

<!--TOP NAVIGATION-->

<?php
$pageItems = $datapodManager->getItems('pageItems');
echo $pageItems->getMenuHtmlDiv(); 
?>


<!--MAIN CONTENT-->


<div id="content">


<?php include_once('includes/pageDatapodIntroduction.php'); ?>	
</div>


<!--FOOTER-->

<div id="footer">
<p>this website was created with <a
	href="http://www.tanguay.info/datapod">datapod</a>, original design by <a
	href="#">Kevin Cannon</a></p>
</div>

</div>

</body>

</html>
