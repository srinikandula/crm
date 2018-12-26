<html><head>
<style>
body {
    font-size: 13px;
    font-family: serif;
}
table{
    margin-left: 10px;
    width:100%;
}
table,tr,td {
    text-align: center;
    height:10px;
    padding: 2px;
    line-height: 1;
    vertical-align: top;
    border: 1px solid #ddd;
}
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>
<form method="post" name="frmAssignTo" id="frmAssignTo" action="" enctype="multipart/form-data">
<table >
	<tr>
		<td>Imei</td>
		<td>From Date</td>
		<td>To Date</td>
		<td>Action</td>
	</tr>
	<tr>
		<td><input type="text" name="imei" id="imei" value="<?php echo $_POST['imei'];?>" ></td>
		<td><input type="text" class="datepicker" name="fromdate" id="fromdate" value="<?php echo $_POST['fromdate'];?>" ></td>
		<td><input type="text" class="datepicker" name="todate" id="todate" value="<?php echo $_POST['todate'];?>" ></td>
		<td><input class="btn btn-info" type="submit" name="upload" id="upload" value="Download"></td>
	</tr>
        
</table>
</form>
<script>
  $( function() {
    $( ".datepicker" ).datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
  } );
  </script>
</body>
</html>