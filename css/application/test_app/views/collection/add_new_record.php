<h2>Add Record</h2><a href="http://localhost/codeigniter/index.php/main/list_records/1">Back to list</a>
<pre>TO DO:- ALLOW MULTIPLE MEDIA ITEMS (JQUERY)- ADD CORRECT LABELS- MODIFY CONTROLLER/MODEL SO THAT THIS CAN WORK FOR BULK INSERT- VALIDATION</pre>
<?php echo validation_errors(); ?><!-- have to use this way because the Helper adds .HTML to the end of the Action URL --><form method="post" action="http://localhost/codeigniter/index.php/main/add_new_record/">
<table>    <tbody>        <tr>            <td>Record Type</td>            <td><?php echo form_dropdown('record_type', $record_types);?></td>        </tr>        <tr>            <td>Date String</td>            <td><input type="text" id="date_string" name="date_string" /></td>        </tr>        <tr>            <td>Sort Date</td>            <td><input type="text" id="sort_date" name="sort_date" /></td>        </tr>        <tr>            <td>Title</td>            <td><input type="text" id="title" name="title" /></td>        </tr>        <tr>            <td>Author</td>            <td><input type="text" id="author" name="author" /></td>        </tr>        <tr>            <td>Description</td>            <td><input type="text" id="description" name="description" /></td>        </tr>        <tr>            <td>Provenance</td>            <td><input type="text" id="provenance" name="provenance" /></td>        </tr>        <tr>            <td>Tags</td>            <td><input type="text" id="tags" name="tags" /></td>        </tr>     </tbody></table>
<h2>Media</h2><h3>New Item:</h3><?php	$i=0;	$css = 'class="f-small"';		$media_newitem = array(		'item'		=> array ('name'	=> 'item_' . $i,							  'value' => -1),		'filename'	=> array ('name'	=> 'filename_' . $i,							  'value' => '',							  'class' => 'f-small'),		'notes'		=> array ('name'	=> 'notes_' . $i,							  'value' => ''),		'order'		=> array ('name'	=> 'order_' . $i,							  'value' => '1',									  							  'class' => 'f-tiny')									  	);	echo "<div id=\"media_item_". $i ."\">\n";	echo "<span class=\"row-title\">" . $i . "</span>\n" ;	echo form_hidden($media_newitem['item']['name'],$media_newitem['item']['value']);	print (form_input($media_newitem['filename']) . "\n" );	print (form_dropdown('media_type_'.$i, $media_types, '', $css). "\n" );	print (form_input($media_newitem['order']) . "\n" );	print (form_input($media_newitem['notes']) . "\n" );	echo $media_newitem['item']['value'];	echo "</div>\n\n";	?>	<br />	<br /><?php	$js = 'onclick="location.replace(\'http://localhost/codeigniter/index.php/main/\');return false;"';	echo form_submit('submit', 'Save Changes');	echo form_button('mybutton', 'Cancel', $js); 	echo form_close(''); ?>