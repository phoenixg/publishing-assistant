<?php
/*
 * Adds a script element to the head of an application. 
 * This is a workaround because CodeIgniter doesn't have a native
 * ability to add things into the head before the application
 * compiles. 
 *
 */
?>
<script src="<?php echo base_url()."application/pm/js/${script}.js"?>"></script>
