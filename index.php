<?php
include_once('config.php');
$currentPageIdCode = $datapodManager->getCurrentPageIdCode();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo Config::getSiteTitle(); ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="css/main-predilection.css" rel="stylesheet" type="text/css"
	media="screen" />
</head>
<body>
<div id="header-wrapper">
<div id="header">
<div id="menu">
<?php
$pageItems = $datapodManager->getItems('pageItems');
echo $pageItems->getMenuHtml('current_page_item', 'last', 'current-last'); 
?>
</div>
<!-- end #menu -->
<div id="search"></div>
<!-- end #search --></div>
</div>
<!-- end #header -->
<!-- end #header-wrapper -->
<div id="logo">
<h1><?php echo Config::getSiteTitle(); ?></h1>
<p><em> <?php echo Config::getSiteSubtitle(); ?></em></p>
</div>
<hr />
<!-- end #logo -->
<div id="page">
<div id="page-bgtop">

<div id="content">
	

<?php include_once('includes/pageDatapodIntroduction.php'); ?>			

</div>
<!-- end #content -->
<div id="sidebar">
<ul>
	<li>
	<h2>Information</h2>
	<p>The links below are being read from <a href="https://docs.google.com/View?id=dc7gj86r_99ghpmkwvp">this google doc</a>.</p>
	</li>
	<li>
	
	<h2>CSS Resources</h2>
<?php
$cssResources = $datapodManager->getItems('cssResources');
echo $cssResources->getListHtml();
?>

</ul>
</div>
<!-- end #sidebar -->
<div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page --></div>
<div id="footer">
<p>this website was created with <a
	href="http://www.tanguay.info/datapod">datapod</a>, original design by <a
	href="http://www.freecsstemplates.org">freecsstemplates.org</a></p>
</div>
<!-- end #footer -->
</body>
</html>
