
<?php echo $pageHeader; ?>      <h2><?php echo __('ORDER HISTORY'); ?></h2><table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="#FFFFFF">
              <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td><table width="100%"border="0" cellspacing="0" cellpadding="5" class="fullwidth">
                    <thead>
                    <tr>
                      <td width="12%"><strong><?php echo __('Invoice'); ?></strong></td>
                      <td width="18%"><strong><?php echo __('Date'); ?></strong></td>
                      <td width="53%"><strong><?php echo __('Order'); ?></strong></td>
                      <td width="8%"><div align="right"><strong><?php echo __('Amount'); ?></strong></div></td>
					  <td width="9%"><div align="right"><strong><?php echo __('Status'); ?></strong></div></td>
                    </tr>
                    </thead><tbody>
                    <?php
					$query = mysql_query("SELECT orders.id AS orderID, orderDate, orderTotal, orderFor, orderPaid FROM orders WHERE username = ".quote_smart($_SESSION['login'])." ORDER BY id DESC") or die(mysql_error());
					$count = mysql_num_rows($query);
					if($count > 0) {
					for($i = 0;$i < $count;$i++) {
						mysql_data_seek($query, $i);
						$arr = mysql_fetch_array($query);
					?><tr>
                      <td><?php echo $arr['orderID']; ?></td>
                      <td><?php echo $arr['orderDate']; ?></td>
                      <td><?php echo $arr['orderFor']; ?></td>
                      <td><div align="right"><?php echo $setupinfo['currency'].number_format($arr['orderTotal'],2); ?></div></td>
					  <td align="right"><?php if($arr['orderPaid'] == 1) { echo 'Completed'; } else if($arr['orderPaid'] == '2') { echo "Refunded"; } else if($arr['orderPaid'] == '3') { echo "Void"; } else { echo 'Pending'; } ?></td>
                    </tr>
					<?php
					}
					} else {
						?><tr><td colspan="5"><?php echo __('There are no completed orders in your order history.'); ?><BR></td></tr><?php
					}
					?></tbody>
                  </table>
                  <br>
                  <?php echo __('If you have made a purchase more than 24 hours ago and you do not see it listed above, please'); ?> <a href="index.php?tp=contacts"><?php echo __('Contact Support.'); ?></a> </td>
                </tr>
          </table></td>
        </tr>
    </table>

<?php echo $pageFooter; ?>