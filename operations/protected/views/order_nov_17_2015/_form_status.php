<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6"  style="margin-left:0px;">
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
                            <?php foreach (Orderhistory::model()->findAll(array('condition' => 'id_order=' . (int) $_GET['id'], 'order' => 'date_created desc')) as $history): ?>
                                <tr>
                                    <td><?php echo $history->date_created; ?></td>
                                    <td><?php echo $history->title; ?></td>
                                    <td><?php echo $history->message; ?></td>
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
                        <div class="span8"> <?php
                            echo $form->textAreaRow(
                                    $model['oh'], 'message', array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                                'data-placement' => "right",)
                            );
                            ?>
                        </div>
                        <div class="span3"> <?php
                            echo CHtml::ajaxButton("Update Status", $this->createUrl('order/updateStatus', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'update' => '#order_status'), array('confirm' => 'Are you sure you want to update the status', 'class' => 'btn btn-info maind_top_p'));
                            ?></div>
                    </div>
            </fieldset>
        </div>   
    </div>
</div>