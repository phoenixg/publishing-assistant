<?php echo form_open('issueMaster'); ?>


<?php

	print("<p>" . $path . "</p>");
	
	$ref="";
	foreach ($sections as $name => $items) {
		$pos = strpos($ref, strtolower($name));
		if ($pos !== false || $ref==""){
			print("<h3 class=\"section\">" . $name . " (". count($items) .")</h3>\n");
			print "<div class=\"hidden\">";
			foreach ($items as $item) {
				print("<div><input type=\"checkbox\"  id=\"a-". $item['page'] . "\" name=\"a-". $item['page'] ."\" /> <label for=\"a-" . $item['page'] . "\">" . $item['title'] . "</label> | <a href=\"" . $item['url'] . "\">LINK</a></div>\n");
			}
			print "</div>";
		}
	}
?>


<input name="" id="publishButton" value="Compile Output" class="button" type="submit">
</form>		

<div class="clearing"></div>

<script type="text/javascript">
	$(function()
{

	$('.section').click(function() {
		$(this).next().slideToggle();
	});	
	
});
</script>