<?php
/*
 * Creates an editable text field with an HTML preview
 * Used by the Admaster and Editors Note apps
 *
 */
echo form_fieldset($label, array('id' => $id."_fieldset", 'style' => 'display:none','class' => 'edit_preview',));
$line = array( 'name' => $id, 'id' => $id."_textarea", 'value' => $content);
$date_diff = floor( (time() - strtotime($modified)) / (60*60*24) );
echo "<span class='label last_modified_{$date_diff}'>Last modified {$modified} ({$date_diff} days ago).</span></br>";
echo form_textarea($line);
//echo $line['name'];
echo form_label("HTML Preview", $id."_preview");
echo "<iframe src='about:blank' id='".$id."_preview' width='670' class='html_preview'></iframe>\n";
echo "<script type='text/javascript'>//<![CDATA[\n";
echo "$(document).ready(function() { var d = $('#".$id."_preview')[0].contentWindow.document;\n";
echo "var content=unescape('" . rawurlencode(utf8_encode($content)) . "');\n";
echo "$('body', d).append('<div>' + content + '</div>');});\n";
echo "//]]>\n</script>\n";
//echo "<div id = '".$id."_preview' class='html_preview'>".$content."</div>";
echo form_fieldset_close();
