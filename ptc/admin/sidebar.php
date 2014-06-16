		<div id="sidebar">
			<div class="side-col ui-sortable">
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header">Website Information</div>
					<div class="portlet-content">
						<div id="accordion">
                        <div>
                            <h3><a href="#" title="View your recent website statistics" class="tooltip">Website Statistics</a></h3>
							<div>
							  <table width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
							    <tbody>
							      <tr>
							        <td bgcolor="#ffffff"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td>Total Members</td>
                                        <td width="48" colspan="2"><?php echo getValue("SELECT COUNT(fid) FROM users"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Payments Received</td>
                                        <td colspan="2"><?php echo $setupinfo['currency']; ?><?php echo number_format(getValue("SELECT SUM(orderTotal) FROM orders WHERE orderPaid = '1'"),4); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Active Links</td>
                                        <td colspan="2"><?php echo getCount("SELECT COUNT(fn) FROM tasks WHERE fsize > fvisits"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Clicks Today</td>
                                        <td colspan="2"><?php echo getValue("SELECT COUNT(fnum) FROM taskactivity WHERE DATE(fdate) = ".quote_smart(date("Y-m-d")).""); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Clicks</td>
                                        <td colspan="2"><?php echo getValue("SELECT COUNT(fnum) FROM taskactivity"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Members Today</td>
                                        <td colspan="2"><?php echo getValue("SELECT COUNT(fid) FROM users WHERE DATE(regdate) = ".quote_smart(date("Y-m-d")).""); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Members</td>
                                        <td colspan="2"><?php echo getValue("SELECT COUNT(fid) FROM users"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Premium Members</td>
                                        <td colspan="2"><?php echo getValue("SELECT COUNT(fid) FROM users WHERE username IN (SELECT username FROM memberships WHERE startDate < NOW() AND endDate > NOW() AND active = '1')"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Website Hits Today </td>
                                        <td colspan="2"><?php echo getValue("SELECT SUM(visits) FROM websitevisits WHERE visitDate = DATE(NOW())"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Website Hits</td>
                                        <td colspan="2"><?php echo getValue("SELECT SUM(visits) FROM websitevisits"); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Orphan Count</td>
                                        <td colspan="2"><?php echo orphanCount(); ?></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="1" bgcolor="#666666"></td>
                                      </tr>
                                      <tr>
                                        <td>Total Link Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'links'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'links'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Banner Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'banner'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'banner'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Featured Banner Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'fbanner'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'fbanner'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Featured Ad Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'fad'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'fad'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Paid Signup Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'signup'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'signup'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Paid Email Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'email'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'email'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Total Featured Link Credits</td>
                                        <td colspan="2"><?php echo number_format(getValue("SELECT SUM(credits) FROM creditadditions WHERE creditsFor = 'flinks'")-getValue("SELECT SUM(credits) FROM creditdebits WHERE creditsFor = 'flinks'"),0); ?></td>
                                      </tr>
                                      <tr>
                                        <td>Members Balance</td>
                                        <td colspan="2"><?php echo $setupinfo['currency']; ?><?php 

				  

				  $memberBalance = abs(getValue("SELECT SUM(ftmclicks + ftmreads + ftmregs + ftmptrad + ftmsurveys) AS totalEarnings FROM users"));

				  

				  $sql = "SELECT SUM(famount) FROM debit WHERE `type` = 'usd' AND famount > 0";

				  $debits = abs(getValue($sql));

				  

				  $sql = "SELECT SUM(famount) FROM debit WHERE `type` = 'usd' AND famount < 0";

				  $credits = abs(getValue($sql));

				  

				  $balance = ($memberBalance + $credits) - $debits;

				  echo number_format($balance,5);

				  ?></td>
                                      </tr>
                                      <tr>
                                        <td>Members Points</td>
                                        <td colspan="2"><?php 

				  	$sql = "SELECT SUM(ftotalregs + ftotalreads + ftotalclicks + ftotalsurveys + ftotalptrad) AS points FROM users";

					$points = getValue($sql);

					$sql = "SELECT SUM(famount) FROM debit WHERE `type` = 'points'";

					$debits = getValue($sql);

					echo number_format($points - $debits);

				  

				   ?></td>
                                      </tr>
                                    </table></td>
						          </tr>
                                </tbody>
						      </table>
</div>
						</div>
                        
                        <div>
							<h3><a href="#" title="View multiple accounts created with the same ip (Cheater Control)" class="tooltip">Cheater Control</a></h3>
							<div>
							  <table width="100%" border="0" cellpadding="2" cellspacing="0">
                                <?php 

		//$query = mysql_query("SELECT COUNT(username) AS accounts, username, fip FROM taskactivity WHERE fdate = '2008-01-19' GROUP BY fip HAVING COUNT(username) > 1  ORDER BY COUNT(username) DESC LIMIT 0, 5");

		$query = mysql_query("SELECT COUNT(username) AS accounts, username, userip as fip FROM users WHERE userip != '' GROUP BY userip HAVING accounts > 1 ORDER BY accounts DESC LIMIT 0, 5");

		$count = mysql_num_rows($query);

		if($count > 0) {

			for($i = 0;$i < $count;$i++) {

				mysql_data_seek($query, $i);

				$arr = mysql_fetch_array($query);

				?>
                                <tr>
                                  <td><a href="index.php?tp=cheaterReport&userip=<?php echo $arr['fip']; ?>"><?php echo $arr['username']."</a>(".$arr['accounts']." accounts with the same ip.)"; ?></a></td>
                                </tr>
                                <?php

			}

		} else {

			echo "<tr><td width=\"158\">There are currently no users with multiple accounts on the same ip.</td></tr>";

		}

		?>
                              </table>
							</div>
					  </div>
						<div>
							<h3><a href="#" title="View the top clickers" class="tooltip">Top Clickers</a></h3>
							<div><table width="100%" border="0" cellspacing="0" cellpadding="2">

                <?php 

		$query = mysql_query("SELECT COUNT(fnum) AS clicks, username FROM taskactivity WHERE DATE(fdate) = ".quote_smart(date("Y-m-d"))." GROUP BY username HAVING clicks > 1  ORDER BY clicks DESC LIMIT 0, 5");

		$count = mysql_num_rows($query);

		if($count > 0) {

			for($i = 0;$i < $count;$i++) {

				mysql_data_seek($query, $i);

				$arr = mysql_fetch_array($query);

				?>

                <tr>

                  <td><a href="index.php?tp=userview&uid=<?php echo $arr['username']; ?>"><?php echo $arr['username']."</a>(".$arr['clicks'].")"; ?></a></td>

                </tr>

                <?php

			}

		} else {

			echo "<tr><td width=\"158\">There are currently no active clicks for today.</td></tr>";

		}

		?>

            </table></div>
					  </div>
						<div>
							<h3><a href="#" title="View statistics for the past 30 days of how many members signed up." class="tooltip">Recent Member Stats</a></h3>
							<div>
							  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <?php 

		$query = mysql_query("SELECT COUNT(fid) AS totalUsers, regdate FROM users GROUP BY DATE(regdate) HAVING totalUsers > 0 ORDER BY regdate DESC LIMIT 0, 30");

		$count = mysql_num_rows($query);

		if($count > 0) {

				for($i = 0;$i < $count;$i++) {

				mysql_data_seek($query, $i);

				$arr = mysql_fetch_array($query);

				?>
                                <tr>
                                  <td><?php echo $arr['regdate']; ?></td>
                                  <td colspan="2"><?php echo $arr['totalUsers']; ?></td>
                                </tr>
                                <?php

			}

		} else {

			echo "<tr><td colspan=\"2\" width=\"342\">There are currently no visits to display.<BR><BR></td></tr>";

		}

		?>
                              </table>
							</div>
					  </div>
						<div>
							<h3><a href="#" title="View your member count for the past 30 days." class="tooltip">Website Hits Details</a></h3>
							<div>
							  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <?php 

		$query = mysql_query("SELECT visits, visitDate FROM websitevisits WHERE type = 'unique' ORDER BY visitDate DESC LIMIT 0, 30");

		$count = mysql_num_rows($query);

		for($i = 0;$i < $count;$i++) {

			mysql_data_seek($query, $i);

			$arr = mysql_fetch_array($query);

			?>
                                <tr>
                                  <td><?php echo $arr['visitDate']; ?></td>
                                  <td colspan="2"><?php echo $arr['visits']; ?></td>
                                </tr>
                                <?php

		}

		?>
                              </table>
							</div>
					  </div>
                        
                        
                        
                        
						</div>
					</div>
				</div>
                
					<div class="portlet-content">
						<div id="datepicker"></div>
					</div>
			</div>
			<div class="clearfix"></div>
		</div>