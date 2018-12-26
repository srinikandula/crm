
<div class="span6" style="margin-left:0px;width:51%">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Load Owner Transactions</div>
 
                    <div class="clearfix"></div>	
                </div>
                <div style="min-height:143px;max-height: 143px;overflow: auto;">
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
                        <tbody id="customer_amount_R">
                            <?php
                            $transaction_total=0;
                            foreach (Ordertransactionhistory::model()->findAll(array('condition' => 'customer_type="L" and id_customer='.$model['0']->id_customer_ordered.' and id_order=' . (int) $_GET['id'], 'order' => 'id_order_transaction_history desc')) as $orderpayment):
                                $transaction_total=$orderpayment->amount_prefix=="+"?$transaction_total+$orderpayment->amount:$transaction_total-$orderpayment->amount;
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->date_created; ?></td>
                                    <td><?php echo $_SESSION['id_admin_role']!=8?$orderpayment->comment:'Payment Made'; //transporter cannot view transaction comments ?></td>
                                    <td><?php echo $orderpayment->amount_prefix; ?></td>
                                    <td><?php echo number_format($orderpayment->amount, 2); ?></td>
                                    <td ><a onclick="fnDelete('<?php echo $orderpayment->id_order_transaction_history;?>','transaction')"><i class="delete-icon-block"></i></a></td>
                                </tr>
                                <?php
                            endforeach;
                            $balance=$_SESSION[$_GET['id']]['load_billing_total']-$transaction_total;
                            $style=$balance==0?'style="border: 1px solid green; color: green; font-weight: bold;"':'style="border: 1px solid red; color: red; font-weight: bold;"';
                            ?>
                                                            <tr <?php echo $style;?>><td>&nbsp;</td><td>&nbsp;</td> <td><b>Balance</b></td><td><?php echo number_format($balance, 2); ?></td><td>&nbsp;</td></tr>
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
                            <td><?php echo Chtml::dropdownlist('Ordertransactionhistory[status_L]','',Library::getTransactionComments(),array('prompt'=>'Comment')); ?></td>
                            <td><?php echo Chtml::dropdownlist('Ordertransactionhistory[prefix_L]','',array("+"=>"+","-"=>"-"),array('prompt'=>'Prefix')); ?></td>

                            <td><input type="text" name="Ordertransactionhistory[amount_L]"  placeholder="Amount" ></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmountTransaction', array('type'=>'L','cid'=>$model['0']->id_customer_ordered,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
						<tr><td colspan="4">+:You receive Money from load owner,-:you pay Money to load owner</td></tr>
                    </table>
                </div><?php }?>
            </fieldset>
        </div>
  