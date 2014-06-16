<?php if(1 == 2) { ?><?php include("header.php"); ?><?php } ?><div class="header_text">
                                      <div class="gallery">
                                        <div id="slider2">
                                         
                                              <div class="div"><?php echo displayContent(getValue("SELECT `value` FROM `design` WHERE `name` = 'homepage'")); ?></div>
                                            
                                        </div>
                                      </div>
                                      <div class="clr"></div>
                                    </div>
                                  </div>
                                  <div class="clr"></div>
                                <?php if(1 == 2) { ?>
                                <?php include("footer.php"); ?><?php } ?>