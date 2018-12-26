<?php
if($return){
    echo "<p style='color:green'>Upload Successful!!</p>";
}
if($error!=""){
    echo "<p style='color:red'>".$error."</p>";
}
?>
<table>
    <tr><td>Full Name</td><td>Mobile</td><td>Email</td><td>Company</td><td>Address</td></tr>
    <tr><td><?php echo $model->fullname;?></td><td><?php echo $model->mobile;?></td><td><?php echo $model->email;?></td><td><?php echo $model->company;?></td><td><?php echo $model->address;?></td></tr>
</table>
<form method="post" action="<?php echo $this->createUrl('cagent/export',array('id'=>$_GET['id']));?>" enctype="multipart/form-data">
    <table>
        <tr><td>Upload:<input type="file" name="import"></td>
            <td><input type="submit" name="submit" value="Submit"></td>
			<td><a href="<?php echo Yii::app()->params['config']['admin_url']?>easygaadi_truck.xlsx">Download Sample File</a></td>
        </tr>
    </table>  
</form>
<?php
if(isset($_SESSION['EXCEL_ERROR_MESSAGE']) && $_GET['return']!=""){
    echo '<p>Warning:Below records insertion failed because of missing values!!</p>';
    foreach($_SESSION['EXCEL_ERROR_MESSAGE'] as $k=>$v){
        echo '<p style="color:red">'.$v.'</p>';
    }
}
?>