<h3>Thank you, the following templates have been saved:</h3><div class="info">
[ Summary: <?php echo count($output) ?> <strong>TEMPLATE(S)</strong> and <?php echo $replaced; ?> <strong>VARIABLES</strong> imported from the form. ]</div>
<hr noshade="noshade" size="1">
<ul>
<?php 
$varsReplaced = 0;
foreach ($output as $info){
	$varsReplaced += 1;
	print '<div class="good">[ Successfully Output: <a href="#" class="result" id="res_'.$varsReplaced.'">'.$info['title'].'</a> ]</div>';
	print '<div style="display: none;" class="result-panel" id="res_'.$varsReplaced.'_panel">'.$info['html'].'</div><hr noshade="noshade" size="1">';	
}
?>
