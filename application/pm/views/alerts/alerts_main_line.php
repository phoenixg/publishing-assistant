<?php 
/**
 * Displays a newsletter name and preview button in the AlertMaster app
 *
 * @see controllers/alertMaster
 *
 */
?>
<label class="checkbox">
<input type="checkbox" id="<?php echo $code; ?>" value="<?php echo $code; ?>" name="<?php echo $pub.'[]'; ?>"><span><?php echo $title;?></span> <a href="<?php echo $link ?>" class="btn btn-mini" title="<?php echo $title;?>">Preview</a>
</label>
