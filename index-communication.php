<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
include_once('config.php');
$mainIndexFileHasBeenCalled = true;
$currentPageIdCode = $datapodManager->getCurrentPageIdCode();
//echo $datapodManager->showDebugInformation();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo Config::getSiteTitle(); ?></title>
<link href="css/main-communication.css" rel="stylesheet" type="text/css"
	media="screen" />
</head>
<body>
<div id="wrapper">
<div id="header">
<div id="logo">
<h1><?php echo Config::getSiteTitle(); ?></h1>
<p><?php echo Config::getSiteSubtitle(); ?></p>
</div>
</div>
<!-- end #header -->
<div id="menu"><?php
$pageItems = $datapodManager->getItems('pageItems','dqlMainMenu');
echo $pageItems->getMenuHtml();
?></div>
<!-- end #menu -->


<div id="page">
<div id="page-bgtop">
<div id="page-bgbtm">
<div id="content">
<div class="post">
<div class="post-bgtop">
<div class="post-bgbtm">


<?php include_once('includes/pageDatapodIntroduction.php'); ?>	

</div>
</div>
</div>
<div style="clear: both;">&nbsp;</div>
</div>
<!-- end #content -->


<div id="sidebar">
<ul>
	<li>
	<h2>About This Site</h2>
	<p><?php include_once('includes/textDatapodWelcome.php'); ?></p>
	</li>
	<li><?php 

	$videoTrainings = $datapodManager->getItems('videoTrainings');
	if($videoTrainings != null) {
		echo '<h2>Video Trainings</h2>';
		echo $videoTrainings->getSummaryViewAsListHtml();
	}

	$techSalonNextMeetingDate = $datapodManager->getResultFromItemMethodWithIdCode('techSalonWebTexts', 'nextMeetingDate', 'getText');
	if(!qstr::isEmpty($techSalonNextMeetingDate)) {
		echo '<h2>Tech Salon</h2>';
		$nextDate = $datapodManager->callItemMethodWithIdCode('webTexts', 'nextMeetingDate', 'getText');
		$amountOfTimeToGoPhrase = qdat::getAmountOfTimeToGo(qdat::getCurrentDateAndTime(), $techSalonNextMeetingDate, ' in ');
		$techSalonPersons =  $datapodManager->getItems('techSalonPersons');
		echo '<p>Interested in meeting up with other developers in Berlin talking about interesting things you are doing with technology? Join ' . $techSalonPersons->getCommaListOfFirstNames() . ' at the Tech Salon, <span class="highlight"><a href="http://www.tanguay.info/techsalon">next gathering</a>' . $amountOfTimeToGoPhrase . '</span>.</p>';
	}

	?></li>

</ul>
</div>
<!-- end #sidebar -->
<div style="clear: both;">&nbsp;</div>
</div>
</div>
</div>
<!-- end #page --></div>
<div id="footer">
<p>this website was created by <a href="http://www.tanguay.info">Edward
Tanguay</a> with <a href="http://www.tanguay.info/datapod">Datapod</a>,
original design by <a href="http://www.freecsstemplates.org">freecsstemplates</a></p>
</div>
<!-- end #footer -->
</body>
</html>
