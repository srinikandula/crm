<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6"  id="user_list_table" style="margin-left:0px;width:51%;">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line4').slideToggle();">
                    <div class="span11">Add Customers </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                
                <div class="portlet-content" id="hide_box_line4">
                    <table class="table uploading-status" border='0' id="table_user">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th <?php echo $deleteRow;?>></th>
                                </tr>
                        </thead>
                        <tbody id="user">
                          <?php  //$aa = Gpsalertsusers::model()->findAll(array('condition' => 'id_gps_alerts=' . (int) $_GET['id'])); ?>
                            <?php //echo '<pre>';print_r($aa);exit; ?>
                              <?php 
 
							$rows=Yii::app()->db->createCommand("select gau.id_notify_transporter_available_trucks_users
,c.fullname,c.mobile,c.gps_account_id from eg_notify_transporter_available_trucks_users
 gau,eg_customer c where (c.id_customer=gau.id_customer) and gau.id_notify_transporter_available_trucks='".(int) $_GET['id']."'")->queryAll();
                            foreach ($rows as $user):
                              ?>
                                <tr id="row_user_<?php echo $user[id_notify_transporter_available_trucks_users]; ?>">
                                    <td><?php echo $user[fullname]; ?></td>
                                    <td><?php echo $user[mobile]; ?>,<?php echo $user[gps_account_id]; ?></td>
                                    <td <?php echo $deleteRow;?>> <a onclick="fnDeleteUser('<?php echo $user[id_notify_transporter_available_trucks_users];?>')" ><i class="delete-icon-block"></i></a> </td>
                                    </tr>
                            <?php endforeach; ?>    
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>
                <div class="span8"> 
                        <?php  
                        echo $form->textFieldRow(
                                    $model['gau'], 'id_customer', array('onkeydown' => 'fnKeyDown("Gpsalertsusers_id_customer_mobile")','rel' => 'tooltip','name'=>'Gpsalertsusers[id_customer_mobile]','id'=>'Gpsalertsusers_id_customer_mobile', 'data-toggle' => "tooltip",
                                'data-placement' => "right",)
                            );
                            ?> 
                        </div>
                <div class="span5" style="margin-top:-36px;margin-left:500px"> <?php
                        echo CHtml::ajaxButton("Submit", $this->createUrl('addUser', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	    $("#table_user thead").after(data);
	
}',), array('confirm' => 'Are you sure??'));
                        ?></div>
            </fieldset>        
    </div>
    </div>
    </div>
<script>
function fnDeleteUser(id) {
    if(confirm("are you sure??")){
       // alert("in"+id)
        $('#row_user_'+id).remove();   
        $.ajax({
                url: '<?php echo $this->createUrl("deleteuser")?>',
                type: 'post',
                data: 'id=' + id,
                dataType: 'json',
        });
    }
}
</script>


