<form action="index.php" method="post" target="_blank">
<h2>Export Members</h2>
<hr />
Choose your download method below.<br />
<br />
<input type="hidden" name="tp" value="exportMembers">
<input type="hidden" name="toDo" value="exportMembersListNow">
Choose the fields for CSV exports<br>
<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="2"><input type="checkbox" name="username" id="username" value="1" checked="checked"></td>
    <td width="232">Username</td>
    <td width="2"><input type="checkbox" name="fname1" id="fname1" value="1" checked="checked"></td>
    <td width="232">Name</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="email" id="email" value="1" checked="checked"></td>
    <td>Email Address</td>
    <td><input type="checkbox" name="userip" id="userip" value="1"></td>
    <td>IP Address</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="address" id="address" value="1"></td>
    <td>Address</td>
    <td><input type="checkbox" name="city" id="city" value="1"></td>
    <td>City</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="state" id="state" value="1"></td>
    <td>State</td>
    <td><input type="checkbox" name="zip" id="zip" value="1"></td>
    <td>Zip</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="country" id="country" value="1"></td>
    <td>Country</td>
    <td><input type="checkbox" name="gender" id="gender" value="1"></td>
    <td>Gender</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="age" id="age" value="1"></td>
    <td>Age</td>
    <td><input type="checkbox" name="income" id="income" value="1"></td>
    <td>Annual Income</td>
  </tr>
  <tr>
    <td><input type="checkbox" name="regdate" id="regdate" value="1"></td>
    <td>Registration Date</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<p>
  <input type="submit" name="Submit" value="CSV Comma Delimited">
&nbsp;&nbsp;&nbsp; 
  <input type="submit" name="Submit" value="XLS Spreadsheet">
&nbsp;&nbsp;&nbsp; 
  <input type="submit" name="Submit" value="TXT (Emails only)">
</p>
</form>