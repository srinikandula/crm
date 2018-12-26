
        <div class="span6">
            <fieldset class="portlet ">
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Truck Owner Billing (<span style="color:green"><?php echo $model['0']->truck_attachment_policy_title;?></span>)</div>
                    <div class="clearfix"></div>	
                </div>
                <?php //if($model['0']->id_truck_attachment_policy==1){?>
                <div style="min-height:100px;max-height: 100px;overflow: auto;">
                <div class="portlet-content" id="hide_box_line7">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Prefix</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="truck_owner_billing">
                            <?php
                            $billing_total=0;
                            foreach (Orderbillinghistory::model()->findAll(array('condition' =>'customer_type="T" and id_customer='.$model['0']->id_customer.' and id_order=' . (int) $_GET['id'], 'order' => 'id_order_billing_history desc')) as $orderpayment):
                                $billing_total=$orderpayment->amount_prefix=="+"?$billing_total+$orderpayment->amount:$billing_total-$orderpayment->amount;
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->date_created; ?></td>
                                    <td><?php echo $orderpayment->comment; ?></td>
                                    <td><?php echo $orderpayment->amount_prefix; ?></td>
                                    <td><?php echo number_format($orderpayment->amount, 2); ?></td>
                                    <td id="row_<?php echo $orderpayment->id_order_billing_history;?>_billing"><a onclick="fnDelete('<?php echo $orderpayment->id_order_billing_history;?>','billing')"><i class="delete-icon-block"></i></a></td>
                                </tr>
                                <?php
                            endforeach;
                            $_SESSION[$_GET['id']]['truck_billing_total']=$billing_total;
                            ?>
                                <tr><td>&nbsp;</td><td>&nbsp;</td> <td> Grand Total </td><td><b><?php echo number_format($billing_total, 2); ?></b></td><td>&nbsp;</td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                </div>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
                            <td><?php echo Chtml::dropdownlist('Orderbillinghistory[status_T]','',Library::getBookingComments(),array('prompt'=>'Comment')); ?></td>
                            <td><?php echo Chtml::dropdownlist('Orderbillinghistory[prefix_T]','',array("+"=>"+","-"=>"-"),array('prompt'=>'Prefix')); ?></td>

                            <td><input type="text" name="Orderbillinghistory[amount_T]"  placeholder="Amount" ></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('type'=>'T','cid'=>$model['0']->id_customer,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div><?php //}else{ 
                //echo '<pre>';print_r($model['joinTable']);echo '</pre>';

                //}?>
                <?php if($model['0']->id_truck_attachment_policy!=1){?>
                <table>
                <tr><td></td><?php if($model['0']->id_truck_attachment_policy==2){ echo '<td>Per Km Charge:'.$model['0']->truck_attachment_policy_price_per_km.'</td><td>Min Km Guarenteed:'.$model['0']->truck_attachment_policy_min_kms.'</td>'; } else  if($model['0']->id_truck_attachment_policy==3){ echo '<td>Flat Payment:'.$model['0']->truck_attachment_policy_flat_rate.'</td><td>Diesel Price Per Km:'.$model['0']->truck_attachment_policy_diesel_price_per_km.'</td>'; } ?></tr>
                        <tr>
                            <td>Start Meter:<input onblur="fnUpdateDetails(this)" type="text" name="Order[truck_start_meter_reading]" id="truck_start_meter_reading" placeholder="Start Meter Reading" value="<?php echo $model['0']->truck_start_meter_reading;?>"></td><td>
                Stop Meter:<input onblur="fnUpdateDetails(this)" type="text" name="Order[truck_stop_meter_reading]"  id="truck_stop_meter_reading"   placeholder="Stop Meter Reading" value="<?php echo $model['0']->truck_stop_meter_reading;?>" ></td><td>
                Current Diesel Price:<input onblur="fnUpdateDetails(this)" type="text" name="Order[current_diesel_price]"  id="truck_current_diesel_price"   placeholder="Current Diesel Price" value="<?php echo $model['0']->truck_current_diesel_price;?>" ></td></tr></table>
                    <?php }?>
            </fieldset>
        </div>

<script>
    function fnUpdateDetails(obj){
    //alert($(obj).attr('id'));
    var field=$(obj).attr('id');
    var value=$(obj).attr('value');
    var id="<?php echo (int)$_GET['id']?>";
    $.ajax({url: "<?php echo $this->createUrl('order/updateFields');?>",
            type:'POST',
            data:{field:field, val:value,id:id}, 
            success: function(result){
                if(result["status"]){
                    $('#'+field).css('border','1px solid green');
                }
            },
            dataType:'json'});
    }
</script>