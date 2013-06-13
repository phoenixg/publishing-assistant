<?php
  /*
   * Outer wrapper for the tabs.
   *
   */
?>
<div class="tab-pane <?php if ($tab=="tab1") { echo "active";}?>" id="<?php echo $tab; ?>">

  <h2><?php echo $journal ?></h2>
  <br />
  <div id="content-science" class="">
    <?php
    echo form_fieldset('',array('class' => 'well'));
    foreach ($lines as $line) {
    echo "{$line}";
    }
    echo "<hr /><span class='btn  btn-mini btn-primary checkbox_all'>ALL</span>&nbsp;<span class='btn  btn-mini btn-primary checkbox_none'>NONE</span>";
    echo form_fieldset_close();
    ?>
    <br />
    <div>
      <?php 
        if (!empty($html_preview)) { 
          foreach($html_preview as $textbox) { echo $textbox; } 
        }
      ?>
    </div>

  </div>             

</div>

