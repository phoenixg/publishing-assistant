<?php 
/**
 * User interface for alertMaster. 
 * @see controllers/alertMaster
 *
 */
?>
              <div class="tab-pane <?php if ($tab=="tab1") { echo "active";}?>" id="<?php echo $tab; ?>">

              <h2><?php echo $alert_title ?></h2>
              
              <br />
                
                <div id="content-science" class="row">
                  <div class="span2 hidden-phone">
                    <img class="well" src="<?php print $alert['cover_link']; ?>" height="101" width="79" alt="Cover" />
                  </div>
                  <div class="span7">
                  <form id="alerts-science-<?php echo $tab?>">
                    <?php	print $alert['line']; ?>

                      <br />
                    <div class="controls">
                      <div class="input-prepend input-append">
                        <span class="add-on "><i class="icon-lock"></i></span><input class="span2 pass-field" id="pass-science" size="16" type="password"><button class="btn btn-inverse" type="button" disabled="disabled" value="create_email">Put</button><button class="btn btn-info" type="button" disabled="disabled" value="test_email">Put, Go Test</button><button class="btn btn-info" type="button" disabled="disabled" value="live_email">Put, Go Live</button>
                      </div>
                    </div>
                    <br />
                    <p class="quiet"><span class="label label-warning">Attention</span> Review all alerts.  Enter password to unlock controls.</p>
                    </form>
                  </div>
                </div>             
              
              </div>
