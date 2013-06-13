<html>
<head>
<title>Manage Uploaded Data</title>
</head>
<body>


<h2>Upload New File</h2>

<?php echo $error;?>

<?php echo form_open_multipart('upload/do_upload');?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>

</body>
</html>