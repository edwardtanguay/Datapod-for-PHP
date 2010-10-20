<?php 
//begin content
if($currentPageIdCode == 'home') {
?>

<h2 class="first">Welcome to <?php echo qsys::getVersionName(); ?>!</h2>
<p>To customize this site to your own:</p>
<ul>
	<li>Choose a layout you want to build on: <a href="index-predilection.php">predilection</a>, <a href="index-green.php" >green</a>, <a href="index-notepad.php" >notepad</a>, <a href="index-communication.php" >communication</a>, <a href="index-darktech.php" >darktech</a></li>
	<li>Delete all the other index files and rename your desired index file to <code>index.php</code>.</li>
	<li>Delete all unused CSS files in the <code>css</code> directory.</li>
	<li>Delete all unused layout directories under <code>images</code>.</li>
	<li>Customize the <code>config.php</code> file.</li>
	<li>Customize the <code>index.php</code> file.</li>
	<li>To create new pages, add a new entry in <code>\data\private\itemType-pageItems.txt</code></li>
	<li>To create a new item type (model), create a data file either in Google Docs or in a text file, then <a href="http://tanguay.info/dpodtools/?page=createDatapodForPHPFieldLines">use the online Datapod-for-PHP model generator</a>.</li>
	<li>For more information or if you have questions, see <a href="http://www.tanguay.info/datapod">the Datapod website</a> or write <a href="mailto:edward@tanguay.info?subject=Question about <?php echo qsys::getVersionName(); ?>">Edward Tanguay</a>.</li>
</ul>

<h2>Example of Using a Google Document as a Datasource</h2>
<p>Please <a href="https://docs.google.com/View?id=dc7gj86r_99ghpmkwvp">view the Google Document</a> containing following data.</p>
<p>Look at <code>config.php</code> to see how this data is loaded.</p>
<p>Look at <code>index.php</code> to see how this data is displayed.</p>
<p>Data dump of CSS Resources:</p>
<?php $datapodManager->display('cssResources', 'getRawDataHtml'); ?>

<?php
} else {
	$pageItem = $datapodManager->getItemWithIdCode('pageItems', $currentPageIdCode);
	?>

<h2 class="first"><?php echo $pageItem->getTitle(); ?></h2>
<p><?php echo $pageItem->getDescription(); ?></p>

<?php
}
//end content
?>	