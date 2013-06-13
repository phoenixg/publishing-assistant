<h2>Manage Data Files</h2>

<?php
	if (isset($errors)) {
		foreach($errors as $error) {
			echo ($error);
		}
	}
?>

<div class="data">

<h3>Current Data Files</h3>

<?php
	echo ("<p><a href='" . WEBROOT . "data_files/show_excel/'>Enter Data Manually</a> | ");
	echo ("<a href='" . WEBROOT . "publish/reset_is_new/'><b>Reset 'is new' flag</b></a></p>");
	echo ("<ul class=\"doc-list\" >");
	if (isset($files)) {
		foreach($files as $file) {
			echo ("<li><span><a href='" . WEBROOT . "data_files/show_excel/" . $file . "'>" . $file . "</a></span> <a href='data_files/delete_file'>[x]</a></li>");
		}
	}
	echo ("</ul>");
?>



</div>

</body>
</html>