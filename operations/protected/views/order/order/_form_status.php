<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6"  style="margin-left:0px;width:51%">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line4').slideToggle();">
                    <div class="span11">Update Status </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content" id="hide_box_line4">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Notify</th>
                            </tr>
                        </thead>
                        <tbody id="order_status">
                            <?php 
                            $getOrderStatusMessages=Library::getOrderStatusMessages();
                            foreach (Orderhistory::model()->findAll(array('condition' => 'id_order=' . (int) $_GET['id'], 'order' => 'date_created desc')) as $history): ?>
                                <tr>
                                    <td><?php echo $history->date_created; ?></td>
                                    <td><?php echo $history->title; ?></td>
                                    <td> <?php echo  $getOrderStatusMessages[$history->message]!=""?$getOrderStatusMessages[$history->message]:$history->message;?></td>
                                    <td><?php echo $history->notified_by_customer == '1' ? 'Yes' : 'No'; ?></td>
                                </tr>
                            <?php endforeach; ?>    
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 

                    <div class="portlet-content" id="hide_box_line3">
                        <div class="span8"> <?php
                            echo $form->dropDownListRow($model['oh'], 'title', CHtml::listData(Orderstatus::model()->findAll(array('select' => 'concat(id_order_status,"#",title) as id_order_status,title', 'condition' => 'status=1')), 'id_order_status', 'title'));
                            ?>
                        </div>
                        <div class="span3 left_nodiv"> <?php
                            echo $form->checkboxRow(
                                    $model['oh'], 'notified_by_customer', array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                                'data-placement' => "right",)
                            );
                            ?>
                        </div>
                        
                        <div class="span8 dropdown" style="margin-left:0px;display:none">
                            <label class="control-label required" for="Truck_id_truck_type">
                               Select Message<span class="required">*</span> 
                            </label>
                            <div class="controls" style="width:250px">
                                <?php echo CHtml::dropdownlist('Orderhistory[message_dropdown]', $model['oh']->message,$getOrderStatusMessages,array('prompt' => '--------Select--------'));?>
                            </div>
                        </div>
                        <div class="span8 other" style="margin-left:0px"> 
                        <?php  
                        echo $form->textAreaRow(
                                    $model['oh'], 'message', array('rel' => 'tooltip','name'=>'Orderhistory[message]','id'=>'Orderhistory_message', 'data-toggle' => "tooltip",
                                'data-placement' => "right",)
                            );
                            ?> 
                        </div>
                        
                        <div class="span5 alrt" style="margin:5px 0px 0px 172px;padding-bottom:5px"> <?php
                            echo CHtml::ajaxButton("Update Status", $this->createUrl('order/updateStatus', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'update' => '#order_status'), array('confirm' => 'Are you sure you want to update the status', 'class' => 'btn btn-info maind_top_p'));
                            ?></div>
                    </div>
            </fieldset>
        </div>   
    </div>
        
</div>

 <script type="text/javascript">
$(document).ready(function(){
    $('select[name="Orderhistory[title]"]').click(function(){
        if($(this).attr("value")=="2#Rejected"){
            $(".dropdown").show();
            $(".other").hide();
			$(".alrt").hide();
        }
        if($(this).attr("value")!="2#Rejected"){
            $(".dropdown").hide();
            $(".other").show();
			$(".alrt").show();
           }
        
    });
});

</script>
<script type="text/javascript">
$(document).ready(function(){
$('select[name="Orderhistory[message_dropdown]"]').click(function(){
        if($(this).attr("value")!=""){
           $(".alrt").show();
        }else{
           $(".alrt").hide();
           }
        
    });
});

</script>  

