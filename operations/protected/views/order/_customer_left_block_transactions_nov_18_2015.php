<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Truck Owner Transactions</div>
                    <div class="clearfix"></div>	
                </div>
                <?php if($model['0']->id_truck_attachment_policy==1){?>
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
                        <tbody id="customer_amount_P">
                            <?php
                            $transaction_total=0;
                            foreach (Ordertransactionhistory::model()->findAll(array('condition' => 'customer_type="T" and id_customer='.$model['0']->id_customer.' and id_order=' . (int) $_GET['id'], 'order' => 'id_order_transaction_history desc')) as $orderpayment):
                                $transaction_total=$orderpayment->amount_prefix=="+"?$transaction_total+$orderpayment->amount:$transaction_total-$orderpayment->amount;
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->date_created; ?></td>
                                    <td><?php echo $orderpayment->comment; ?></td>
                                    <td><?php echo $orderpayment->amount_prefix; ?></td>
                                    <td><?php echo number_format($orderpayment->amount, 2); ?></td>
                                    <td ><a onclick="fnDelete('<?php echo $orderpayment->id_order_transaction_history;?>','transaction')"><i class="delete-icon-block"></i></a></td>
                                
                                </tr>
                                <?php

                            endforeach;
                            $balance=$_SESSION[$_GET['id']]['truck_billing_total']-$transaction_total;
                            $style=$balance==0?'style="border: 1px solid green; color: green; font-weight: bold;"':'style="border: 1px solid red; color: red; font-weight: bold;"';
                            ?>
                            <tr <?php echo $style;?> ><td>&nbsp;</td><td>&nbsp;</td> <td><b>Balance</b></td><td><?php echo number_format($balance, 2); ?></td><td>&nbsp;</td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
                            <td><?php echo Chtml::dropdownlist('Ordertransactionhistory[status_T]','',Library::getTransactionComments(),array('prompt'=>'Comment')); ?></td>
                            <td><?php echo Chtml::dropdownlist('Ordertransactionhistory[prefix_T]','',array("+"=>"+","-"=>"-"),array('prompt'=>'Prefix')); ?></td>

                            <td><input type="text" name="Ordertransactionhistory[amount_T]"  placeholder="Amount" ></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmountTransaction', array('type'=>'T','cid'=>$model['0']->id_customer,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div><?php }else { echo "******Monthly Payment Only******";}?>
            </fieldset>
        </div>
    </div>
</div>