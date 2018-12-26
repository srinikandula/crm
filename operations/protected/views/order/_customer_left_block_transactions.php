
<div class="span6" style="margin-left:0px">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Truck Owner Transactions</div>
                    <div class="clearfix"></div>	
                </div>
                <?php //if($model['0']->id_truck_attachment_policy==1){?>
                <div style="min-height:143px;max-height: 143px;overflow: auto;">
                <div class="portlet-content" id="hide_box_line7">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Transaction Date</th>
                                <th>Comment</th>
                                <th>Prefix</th>
                                <th>Payment By</th>
								<th>Payment Type</th>
								<th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="customer_amount_P">
                            <?php
                            $transaction_total=0;
							//and id_customer='.$model['0']->id_customer.'
                            foreach (Ordertransactionhistory::model()->findAll(array("select"=>"*,DATE_FORMAT(transaction_datetime,'%d-%m-%Y') as transaction_datetime",'condition' => 'customer_type="T"  and id_order=' . (int) $_GET['id'], 'order' => 'id_order_transaction_history desc')) as $orderpayment):
                                $transaction_total=$orderpayment->amount_prefix=="+"?$transaction_total+$orderpayment->amount:$transaction_total-$orderpayment->amount;
								$transaction_desc=$orderpayment->transaction_desc!=""?" #".$orderpayment->transaction_desc:"";
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->transaction_datetime; ?></td>
                                    <td><?php echo $orderpayment->comment; ?></td>
                                    <td><?php echo $orderpayment->amount_prefix; ?></td>
									<td><?php echo $this->adminUsers[$orderpayment->transaction_by].$orderpayment->bank; ?></td>
									<td><?php echo $orderpayment->payment_type.$transaction_desc; ?></td>
                                    <td><?php echo number_format($orderpayment->amount, 2); ?></td>
                                    <td ><a onclick="fnDelete('<?php echo $orderpayment->id_order_transaction_history;?>','transaction')"><i class="delete-icon-block"></i></a></td>
                                
                                </tr>
                                <?php

                            endforeach;
                            $balance=$_SESSION[$_GET['id']]['truck_billing_total']-$transaction_total;
                            $style=$balance==0?'style="border: 1px solid green; color: green; font-weight: bold;"':'style="border: 1px solid red; color: red; font-weight: bold;"';
                            ?>
                            <tr <?php echo $style;?> ><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td> <td><b>Balance</b></td><td><?php echo number_format($balance, 2); ?></td><td>&nbsp;</td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>
                </div>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
                            <td><?php echo Chtml::dropdownlist('Ordertransactionhistory[status_T]','',Library::getTransactionCommentsTO(),array('prompt'=>'Comment')); ?></td>
                            <!--<td><?php //echo Chtml::dropdownlist('Ordertransactionhistory[prefix_T]','',array("+"=>"+","-"=>"-"),array('prompt'=>'Prefix')); ?></td>-->

                            <td><input type="text" name="Ordertransactionhistory[amount_T]"  placeholder="Amount" ></td>
							
							<td><?php echo Chtml::dropdownlist('Ordertransactionhistory[payment_type_T]','',array('Cheque'=>'Cheque','Cash'=>'Cash','Account_Transfer'=>'Account Transfer'),array('prompt'=>'Payment Type')); ?></td>
							
							<td id="Ordertransactionhistory_transaction_by_T" style="display:none;"><?php echo Chtml::dropdownlist('Ordertransactionhistory[transaction_by_T]','',$this->adminUsers,array('prompt'=>'Payment BY')); ?></td>
							
							<td id="Ordertransactionhistory_bank_T" style="display:none;"><?php echo Chtml::dropdownlist('Ordertransactionhistory[bank_T]','',Library::getBanks(),array('prompt'=>'Select Bank')); ?></td>

							<td id="transaction_desc_T" style="display:none" ><input  type="text" name="Ordertransactionhistory[transaction_desc_T]"  placeholder="Cheque No" ></td>
							<td><input id="transaction_datetime_T" type="text" name="Ordertransactionhistory[transaction_datetime_T]"  placeholder="Transaction Date" ></td>
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
						<tr><td colspan="4">+:You pay Money to truck owner,-:you receive Money from truck owner</td></tr>
                    </table>
                </div><?php //}else { echo "******Monthly Payment Only******";}?>
            </fieldset>
        </div>
<script>
        $(function() {
            $("#transaction_datetime_T").datepicker({dateFormat: 'yy-mm-dd'});
		 });
		 $('#Ordertransactionhistory_payment_type_T').change(function(){
			//alert($(this).val());
			var fieldVal=$(this).val();
			if(fieldVal=='Cheque'){
				$("#transaction_desc_T").show();
			}else{
				$("#transaction_desc_T").hide();	
			}

			if(fieldVal=='Cash'){
				$("#Ordertransactionhistory_transaction_by_T").show();
			}else{
				$("#Ordertransactionhistory_transaction_by_T").hide();	
			}
			
			if(fieldVal=='Account_Transfer'){
				$("#Ordertransactionhistory_bank_T").show();
			}else{
				$("#Ordertransactionhistory_bank_T").hide();	
			}

		 });
</script>