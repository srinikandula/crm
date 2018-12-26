<html><head>
<link  type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"></link>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl;?>/js/jquery-1.7.1.min.js"></script>
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
</head>
<body>
    <?php    
	//echo '<pre>';print_r($_SESSION['gps_errors']);echo '</pre>';
	if(isset($_SESSION['gps_errors'])){
		$i=1;
		foreach($_SESSION['gps_errors'] as $k=>$v){
			$str="";
			$pre="";
			//echo '<pre>';print_r($v);echo '</pre>';exit;
			foreach($v as $vk=>$vv){
				$str.=$pre.$vv['0'];
				$pre=",";

			}
			//echo '<p>'.$i.".".$str.'</p>';
			echo '<div class="alert alert-danger"><strong>'.$i.')</strong>'.$str.'</div>';
			$i++;	
		}
		unset($_SESSION['gps_errors']);
	}//exit;
	$this->widget('ext.Flashmessage.Flashmessage'); ?>
<form method="post" name="frmAssignTo" id="frmAssignTo" action="" enctype="multipart/form-data">
<table>
	<tr><td colspan="2">Transfer Device</td></tr>
	<tr><td>ImeiNo</td><td>Field Team</td><td></td></tr>
        <tr><td><textarea name="imei" id="imei" rows="20" cols="15"></textarea><br/><b>Note:write full imeino's in separate lines with 15 digits,invalid imeino's will be ignored.ex:<br>358911020147381<br>358911020147373<br>358511020279075</b></td>
            <td><select name="assignTo" id="assignTo">
                <option value="">Select</option>
                    <?php foreach($assignToRows as $assignToRow){ echo "<option value='".$assignToRow->id_admin."@@".$assignToRow->first_name." ".$assignToRow->last_name."'>".$assignToRow->first_name." ".$assignToRow->last_name." - ".$assignToRow->account."</option>";}?>
                </select><br/><br/>
				<input type="button" name="submitbutton" id="submitbutton" class="btn btn-info" onclick="fnsubmit()" value="Submit">
			</td>
        </tr>
</table>
</form>
    <script>
function fnsubmit(){
    //alert("here")
    if(confirm("Do you want to Submit?")){
		if($('#assignTo').val()==""){
			alert("Select field person to whom you want to assign.");
			return false;
		}

		if($('#imei').val()==""){
			alert("Please enter imeino");
			return false;
		}

		document.getElementById("frmAssignTo").submit();
	}
}
</script>
</body>
</html>