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
	<tr><td>S.No</td><td>ImeiNo</td><td>SimNo</td><td>SimId</td></tr>
        <?php for($i=1;$i<=30;$i++){?>
        <tr id="row_<?php echo $i?>"><td><?php echo $i?></td>
            <!-- <td><input type="number" name="Assign[<?php echo $i?>][imeino]" id="Assign_<?php echo $i?>_imeino" min="111111111111111" max="999999999999999"></td> -->
			<td><input type="text" name="Assign[<?php echo $i?>][imeino]" id="Assign_<?php echo $i?>_imeino" ></td>
            <!-- <td><input type="number" name="Assign[<?php echo $i?>][simno]" id="Assign_<?php echo $i?>_simno" min="1111111111" max="9999999999"></td> -->
			<td><input type="text" name="Assign[<?php echo $i?>][simno]" id="Assign_<?php echo $i?>_simno" ></td>
            <!-- <td><input type="text" name="Assign[<?php echo $i?>][simid]" id="Assign_<?php echo $i?>_simid" maxlength="20"></td> -->
			<td><input type="text" name="Assign[<?php echo $i?>][simid]" id="Assign_<?php echo $i?>_simid" ></td>
        </tr>
        <?php }?>
</table>
<table >
	<tr><td>Assign To</td><td>Action</td></tr>
        <tr><td><select name="assignTo" id="assignTo">
                <option value="">Select</option>
                    <?php foreach($assignToRows as $assignToRow){ echo "<option value='".$assignToRow->id_admin."@@".$assignToRow->first_name." ".$assignToRow->last_name."'>".$assignToRow->first_name." ".$assignToRow->last_name."</option>";}?>
                </select></td>
            <td><input class="btn btn-info" type="button" name="upload" id="upload" value="Submit" onclick="fnsubmit()"></td>
        </tr>
        
</table>
</form>
    <script>
function fnsubmit(){
    //alert("here")
    if(confirm("Do you want to Submit?")){
    if(document.getElementById("assignTo").value!=""){
        var flag=true;
        var i;
        var imeino;
        var simno;
        var simid;
        for(i=1;i<30;i++){
            /*imeino=document.getElementById('Assign_'+i+'_imeino');
            simno=document.getElementById('Assign_'+i+'_simno');
            simid=document.getElementById('Assign_'+i+'_simid');
			alert("imeni "+$('#Assign_'+i+'_imeino').val()+" "+$('#Assign_'+i+'_imeino').val().length);
			alert("simno "+$('#Assign_'+i+'_simno').val()+" "+$('#Assign_'+i+'_simno').val().length);
			alert("simid "+$('#Assign_'+i+'_simid').val()+" "+$('#Assign_'+i+'_simid').val().length);*/
            //alert(imeino+" "+simno+" "+simid)
			
			imeino=$('#Assign_'+i+'_imeino').val();
            simno=$('#Assign_'+i+'_simno').val();
            simid=$('#Assign_'+i+'_simid').val();
			//alert("imeino "+imeino.length+" simno "+simno.length+" simid "+simid.length);
            if(imeino=="" && simno=="" && simid==""){
               //alert("all null")   
            }else if((imeino!="" && imeino.length==15) && (simno!="" && simno.length==10) && (simid!="" && simid.length==20)){
               //alert("all full") 
			   document.getElementById('Assign_'+i+'_imeino').style.border="solid 1px green";
			   document.getElementById('Assign_'+i+'_simno').style.border="solid 1px green";
			   document.getElementById('Assign_'+i+'_simid').style.border="solid 1px green";
            }else{
				document.getElementById('Assign_'+i+'_imeino').style.border="";
				document.getElementById('Assign_'+i+'_simno').style.border="";
				document.getElementById('Assign_'+i+'_simid').style.border="";
                if(imeino.length!=15){
					//alert("imei"+imeino.length);
                    document.getElementById('Assign_'+i+'_imeino').style.border="solid 1px red";
                    //document.getElementById('Assign_'+i+'_imeino').value="red";
                    flag=false;
                }else{
					document.getElementById('Assign_'+i+'_imeino').style.border="solid 1px green";
				}
                
                if(simid.length!=20){
					//alert("simid"+simid.length);
                    document.getElementById('Assign_'+i+'_simid').style.border="solid 1px red";
                    //document.getElementById('Assign_'+i+'_simid').value="red";
                    flag=false;
                }else{
					document.getElementById('Assign_'+i+'_simid').style.border="solid 1px green";
				}

                if(simno.length!=10){
					//alert("simno"+simno.length);
                   document.getElementById('Assign_'+i+'_simno').style.border="solid 1px red";
                   //document.getElementById('Assign_'+i+'_simno').value="red";
                   flag=false;
                }else{
					document.getElementById('Assign_'+i+'_simno').style.border="solid 1px green";
				}
            }
        }
        if(flag){
			//alert("at submit")
            document.getElementById("frmAssignTo").submit();
        }else{
            alert("Please update missing fields");
        }
    }else{
        alert("Assign To is mandatory");
    }
    }
}

/*$('#Assign_1_imeino').bind('paste', null, function(e){
    $this = $(this);

    setTimeout(function(){
        var columns = $this.val().split(/\s+/);
        $this.val(' ');
        var i;

        for(i=0; i < columns.length; i++){
            var name = columns[i].toLowerCase();
            $('#Assign_'+name+'_imeino').val(columns[i]);
        }
    }, 0);
});*/


$('#Assign_1_imeino').bind('blur',null, function(e){
	//alert("value of "+$('#Assign_1_imeino').val())
	var array=	$('#Assign_1_imeino').val();
  columns = array.split(/\s+/);
  for(i=0; i < columns.length; i++){
		var name = columns[i].toLowerCase();
		var no=parseInt(i)+1;
		$('#Assign_'+no+'_imeino').val(columns[i]);
	}
});

$('#Assign_1_simno').bind('blur',null, function(e){
	//alert("value of "+$('#Assign_1_imeino').val())
	var array=	$('#Assign_1_simno').val();
  columns = array.split(/\s+/);
  for(i=0; i < columns.length; i++){
		var name = columns[i].toLowerCase();
		var no=parseInt(i)+1;
		$('#Assign_'+no+'_simno').val(columns[i]);
	}
});

$('#Assign_1_simid').bind('blur',null, function(e){
	//alert("value of "+$('#Assign_1_imeino').val())
	var array=	$('#Assign_1_simid').val();
  columns = array.split(/\s+/);
  for(i=0; i < columns.length; i++){
		var name = columns[i].toLowerCase();
		var no=parseInt(i)+1;
		$('#Assign_'+no+'_simid').val(columns[i]);
	}
});

</script>
</body>
</html>