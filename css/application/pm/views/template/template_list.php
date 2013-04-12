
<h3>Select Templates to Publish:</h3>

<?php echo form_open('templatemaster/templatesave', array('name'=>"data")); ?>
<!-- we'll need to add the date function thing in here at some point. However, further though will be needed to 
figure out how this well be done. Currently selecting a date that lands on a publish date will give the NEXT publish date, 
so that is an issue that will need to be addressed. -->
<!--fieldset>
	<legend>Next Issue Details</legend>
	<div class="row">
		<label for="ISSUE_DATE">Issue Date</label>
		<input id="ISSUE_DATE" name="ISSUE_DATE" value="" class="date-pick dp-applied" type="text">
		(mm/dd/yy)
	</div>
</fieldset-->


<?php echo form_hidden('tempDirectory', $temp_direct); ?>
<p>[ <a href="#" id="allTemplates">Select All</a> |<a href="#" id="noTemplates">Select None</a> ]</p>
<ul class="simple">
<?php 
$i = 0;
foreach ($aval_templ as $templ){	
	$class ="";
	if ($templ['pattern']){
		$class="XML_needed";	
	}
	echo '<li><input name="fileArray[]" type="checkbox" value="'.$templ['template'].'" id="tpt_'.$i.'" name="tpt_'.$i.'" class="in '.$class.'"/> <label for="'.$templ['template'].'"> '.$templ['name'].' </label></li>';
	$i++;
}?>
</ul>

<?php if(in_array(true, $aval_templ)):?>
<div id="XML_select" style="display:none">
<h3>Select Data File:</h3>
<fieldset>
	<legend>Next Issue Details</legend>
	<select name="dataFile" >
		<option value="null">---------------</option>
		<?php foreach ($aval_xml as $dataFile){	
			echo '<option value="'.$dataFile.'">'.$dataFile.'</option>';
		} ?>
	</select>		
</fieldset><hr noshade="noshade" size="1">
</div>
<?php endif;?>

<!-- REPLACE WITH CODE ======================================================-->
<?php 
$testArray = array();
$i = 0;
foreach($aval_templ as $templ): ?>
<?php if(isset($templ['customFields'])): ?>
<fieldset style="display: none;" class="fieldBlock" id="set_<?php print $i;?>">
	<legend><?php print $templ['template']; ?></legend>
	<div class="row">
	<?php foreach($templ['customFields'] as $customField):?>
	
	<div class="row">
	<?php if(in_array($customField, $testArray) == false): ?>
		<label for="<?php print $customField; ?>"><?php print $customField; ?></label>
		<input class="text-long" value="" name="customData[<?php print $customField; ?>]" id="<?php print $customField; ?>" type="text">
		<?php $testArray[] = $customField;?>

	<?php else: ?>
		<label for="<?php print $customField; ?>"><?php print $customField; ?></label>
		[duplicate of field in a preceeding template] 
	
	<?php endif?>
	</div>
	
	<?php endforeach;?>
	
</fieldset>
<?php endif?>
<?php $i++;?>

<?php endforeach;?>
				
				
				
				
				


<input name="" id="publishButton" value="Compile Output" class="button" type="submit">
</form>		

<div class="clearing"></div>
