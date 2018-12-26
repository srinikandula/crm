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
    <?php    $this->widget('ext.Flashmessage.Flashmessage'); ?>
<form method="post" name="frmAssignTo" id="frmAssignTo" action="" enctype="multipart/form-data">
<table>
	<tr><td>S.No</td><td>ImeiNo</td><td>SimNo</td><td>SimId</td></tr>
        <?php for($i=1;$i<=15;$i++){?>
        <tr id="row_<?php echo $i?>"><td><?php echo $i?></td>
            <td><input type="text" name="Assign[<?php echo $i?>][imeino]" id="Assign_<?php echo $i?>_imeino"></td>
            <td><input type="text" name="Assign[<?php echo $i?>][simno]" id="Assign_<?php echo $i?>_simno" maxlength="10"></td>
            <td><input type="text" name="Assign[<?php echo $i?>][simid]" id="Assign_<?php echo $i?>_simid"></td>
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
        for(i=1;i<15;i++){
            imeino=document.getElementById('Assign_'+i+'_imeino').value;
            simno=document.getElementById('Assign_'+i+'_simno').value;
            simid=document.getElementById('Assign_'+i+'_simid').value;
            //alert(imeino+" "+simno+" "+simid)
            if(imeino=="" && simno=="" && simid==""){
               //alert("all null")   
            }else if(imeino!="" && simno!="" && simid!=""){
               //alert("all full") 
            }else{
                if(imeino==""){
                    document.getElementById('Assign_'+i+'_imeino').style.border="solid 1px red";
                    //document.getElementById('Assign_'+i+'_imeino').value="red";
                    flag=false;
                }
                
                if(simid==""){
                    document.getElementById('Assign_'+i+'_simid').style.border="solid 1px red";
                    //document.getElementById('Assign_'+i+'_simid').value="red";
                    flag=false;
                }
                if(simno==""){
                   document.getElementById('Assign_'+i+'_simno').style.border="solid 1px red";
                   //document.getElementById('Assign_'+i+'_simno').value="red";
                   flag=false;
                }
            }
        }
        if(flag){
            document.getElementById("frmAssignTo").submit();
        }else{
            alert("Please update missing fields");
        }
    }else{
        alert("Assign To is mandatory");
    }
    }
}
</script>
</body>
</html>