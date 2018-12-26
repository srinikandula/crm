
        <div class="span6" style="margin-left:0px;width:51%">
            <fieldset class="portlet " style="height:261px">
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Load Owner Billing</div>
 
                    <div class="clearfix"></div>	
                </div>
                <div style="min-height:143px;max-height: 143px;overflow: auto;">
                <div class="portlet-content" id="hide_box_line7" style="">
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
                        <tbody id="load_lower_billing">
                            <?php
                            foreach (Orderbillinghistory::model()->findAll(array('condition' => 'customer_type="L" and id_customer='.$model['0']->id_customer_ordered.' and id_order=' . (int) $_GET['id'], 'order' => 'id_order_billing_history desc')) as $orderpayment):
                                $billing_total=$orderpayment->amount_prefix=="+"?$billing_total+$orderpayment->amount:$billing_total-$orderpayment->amount;
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->date_created; ?></td>
                                    <td><?php echo $_SESSION['id_admin_role']!=8?$orderpayment->comment:'Payment Made'; //transporter cannot view transaction comments ?></td>
                                    <td><?php echo $orderpayment->amount_prefix; ?></td>
                                    <td><?php echo number_format($orderpayment->amount, 2); ?></td>
                                    <td id="row_<?php echo $orderpayment->id_order_billing_history;?>_billing"><a onclick="fnDelete('<?php echo $orderpayment->id_order_billing_history;?>','billing')"><i class="delete-icon-block"></i></a></td>
                                </tr>
                                <?php
                            endforeach;
                            $_SESSION[$_GET['id']]['load_billing_total']=$billing_total;
                            ?>
                                <tr><td>&nbsp;</td><td>&nbsp;</td> <td> Grand Total </td><td><b><?php echo number_format($billing_total, 2); ?></b></td><td>&nbsp;</td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                </div>
                <?php if($_SESSION['id_admin_role']!=8){ //truck owner payments hidden from transporter ?>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
                            <td><?php echo Chtml::dropdownlist('Orderbillinghistory[status_L]','',Library::getBookingComments(),array('prompt'=>'Comment')); ?></td>
                            <td><?php echo Chtml::dropdownlist('Orderbillinghistory[prefix_L]','',array("+"=>"+","-"=>"-"),array('prompt'=>'Prefix')); ?></td>

                            <td><input type="text" name="Orderbillinghistory[amount_L]"  placeholder="Amount" ></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('type'=>'L','cid'=>$model['0']->id_customer_ordered,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div><?php }?>
                <div class="portlet-content" id="hide_box_line3">
                <table>
                <tr>
                    <td>Date pod Submitted:</td>
                <td><input onchange="fnUpdateDetails(this)" type="text" name="Order[date_pod_submitted]" id="date_pod_submitted" placeholder="Date pod submitted" value="<?php echo $model['0']->date_pod_submitted;?>"></td>
                </tr></table>
                </div>
            </fieldset>
        </div>

<script>
        $(function() {
            $("#date_pod_submitted").datepicker({dateFormat: 'yy-mm-dd'});
         });
        
    </script>
    
  