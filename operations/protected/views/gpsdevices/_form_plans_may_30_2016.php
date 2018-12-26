<?php $this->widget('ext.Flashmessage.Flashmessage');?> 
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12"  id="gps_plan_list_table" style="margin-left:0px;">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line4').slideToggle();">
                    <div class="span11">Add Plans </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div style="overflow: auto;">
                <div class="portlet-content" id="hide_box_line4">
                    <table>
                        <thead>
                            <tr>
                                <th>Plan Name</th>
                                <th>Update</th>
                                <th>Amount</th>    
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody><tr>
                                <td><?php  
                        $planlist=CHtml::listData(GpsDevicePlans::model()->findAll(array('select' => 'concat(id_device_plans,"#",plan_name,"#",amount,"#",duration_in_months) as id_device_plans,concat(plan_name," , ",amount) as plan_name ,duration_in_months')), 'id_device_plans', 'plan_name');
                        //echo '<pre>';print_r($planlist);exit;
echo Chtml::dropdownlist('Accountdeviceplanhistory[planName]','',$planlist,array("prompt"=>"select"));
                            ?></td>
                                <td><?php 
                        echo $form->datepickerRow(
                                $model, 'update_on', array(
                            'options' => array('dateFormat' => 'yy-mm-dd',
                            'altFormat' => 'dd-mm-yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                        ),
                            'htmlOptions' => array(
                            )
                                ), array(
                            'prepend' => '<i class="icon-calendar"></i>'
                                )
                        );
                                
                            /*echo $form->textFieldRow($model, 'update_on', array('class'=>'datepicker','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",))." Last Expiry:".$model->expiryTime;*/
                            /*echo $form->radioButtonListRow($model, 'update_on',
                            array('1' => 'Current Time', '0' => 'Last Expiry Time('.$model->expiryTime.')'));*/
                    ?><?php //echo $form->hiddenField($model, 'accountID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));echo $form->hiddenField($model, 'deviceID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?></td>
                        <td>
                            <input type="text" id="Accountdeviceplanhistory_amount" name="Accountdeviceplanhistory[amount]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="">
                        </td>        
                                <td><?php
                        echo CHtml::ajaxButton("Submit", $this->createUrl('addGpsPlan', array('id' => (int) $_GET['ids']['GpsDevice'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
                            $("#norecords").remove();
                            if(data==""){alert("please try again.invalid data!!");}
	    $("#table_gps_plan thead").after(data);
	
}',), array('confirm' => 'Are you sure??'));
                        ?></td>
                                
                                </tr></tbody></table>
                    <table class="table uploading-status" border='0' id="table_gps_plan">
                        <thead>
                                
                            <tr>
                                <tr>
                                <th>Plan Name</th>
                                <th>Plan Amount</th>
                                <th>Payable Amount</th>
                                <th>Received</th>
                                <th>Start Time</th>
                                <th>Expiry Time</th>
                                <th>Created Time</th>
                                <th <?php echo $deleteRow;?>></th>
                                </tr>
                        </thead>
                        <tbody id="gps_plan">
                          <?php  //$aa = Gpsalertsinterested::model()->findAll(array('condition' => 'id_gps_alert='. (int) $_GET['id'] )); ?>
                            <?php $bb = $_GET['ids']['deviceID']; ?>
                              <?php $rows=Yii::app()->db_gts->createCommand("select received,id,planName,planAmount,amount,creationTime,startTime,expiryTime from Accountdeviceplanhistory where deviceID='$bb' order by creationTime DESC")->queryAll();
                                
                              foreach ($rows as $user):
                              ?>
                                <tr id="row_gps_plan_<?php echo $user[id]; ?>">
                                    <td><?php echo $user[planName] ?></td>
                                    <td><?php echo $user[planAmount] ?></td>
                                    <td><?php echo $user[amount]; ?></td>
                                    <td><?php echo $user[received]==0?"No":"Yes"; ?></td>
                                    <td><?php echo $user[startTime]; ?></td>
                                    <td><?php echo $user[expiryTime]; ?></td>
                                    <td><?php echo $user[creationTime]; ?></td>        
                                    <td <?php echo $deleteRow;?>> <a onclick="fnDeleteGpsPlan('<?php echo $user['id'];?>')" ><i class="delete-icon-block"></i></a> </td>
                                    </tr>
                            <?php endforeach; if(!sizeof($rows)){ echo '<tr id="norecords"><td colspan="7"><center>No Records</center></td></tr>';} ?>    
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>
                </div>
            </fieldset>        
    </div>
    </div>
    </div>
<script>
function fnDeleteGpsPlan(id) {
    <?php if($_SESSION['id_admin_role']==1){?>
	if(confirm("are you sure??")){
       // alert("in"+id)
        $('#row_gps_plan_'+id).remove();   
        $.ajax({
                url: '<?php echo $this->createUrl("deletegpsplan")?>',
                type: 'post',
                data: 'id=' + id,
                dataType: 'json',
        });
    }
	<?php }?>
}
</script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

