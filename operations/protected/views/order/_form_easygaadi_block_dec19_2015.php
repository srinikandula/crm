<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">EasyGaadi Profit/Loss</div>
                    <div class="clearfix"></div>	
                </div>
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
                            foreach (Orderbillinghistory::model()->findAll(array('condition' =>'customer_type="E" and id_order=' . (int) $_GET['id'], 'order' => 'id_order_billing_history desc')) as $orderpayment):
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
                            $style=$billing_total>0?'style="border: 1px solid green; color: green; font-weight: bold;"':'style="border: 1px solid red; color: red; font-weight: bold;"';
                            ?>
                                <tr <?php echo $style;?> ><td>&nbsp;</td><td>&nbsp;</td> <td><b>Balance</b></td><td><?php echo number_format($billing_total, 2); ?></td><td>&nbsp;</td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
                            <td><?php echo Chtml::textField('Orderbillinghistory[status_E]','',array('placeholder'=>'Comment')); ?></td>
                            <td><?php echo Chtml::dropdownlist('Orderbillinghistory[prefix_E]','',array("+"=>"Profit","-"=>"Loss"),array('prompt'=>'Prefix')); ?></td>

                            <td><input type="text" name="Orderbillinghistory[amount_E]"  placeholder="Amount" ></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('type'=>'E','cid'=>0,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>
</div>