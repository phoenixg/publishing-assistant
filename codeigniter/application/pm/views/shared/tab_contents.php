<?php
/*
 * A wrapper for the AlertMaster application. 
 *  The journals are hard-coded. It would be nice to make this dynamic in the future, 
 *  but a solution would need a way to display shortened names (e.g., STM)
 */
?>
<div class="tabbable"> <!-- Only required for left/right tabs -->
 
 <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab"><em>Science</em></a></li>
    <li><a href="#tab2" data-toggle="tab"><em><span class="hidden-phone">Science Translational Medicine</span><span class="visible-phone">STM</span></em></a></li>
    <li><a href="#tab3" data-toggle="tab"><em><span class="hidden-phone">Science</span> Signaling</em></a></li>
    <li><a href="#tab4" data-toggle="tab"><em><span class="hidden-phone">Science</span> News</em></a></li>
  </ul>

  <div class="tab-content">
  <?php print $tabcontents ?>
  </div>
</div>
