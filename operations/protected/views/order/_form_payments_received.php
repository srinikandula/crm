<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Payments Received</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content" id="hide_box_line7">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Comment</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="amount_recived">
                            <tr>
                                <td>Payable Amount</td>
                                <td><?php echo $model['0']->payable_amount; ?></td>
                            </tr>
                            <?php
                            $payableAmount = $model['0']->payable_amount;
                            foreach (Orderpaymenthistory::model()->findAll(array('condition' => 'id_order=' . (int) $_GET['id'], 'order' => 'id_order_payment_history desc')) as $orderpayment):
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->comment; ?></td>
                                    <td><?php echo $orderpayment->amount; ?></td>
                                </tr>
                                <?php
                                $payableAmount-=$orderpayment->amount;
                            endforeach;
                            ?>
                            <tr><td>Pending Amount</td><td><?php echo $payableAmount; ?></td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr><td>Comment</td><td>Pay Amount</td></tr>
                        <tr>
                            <td><input type="text" name="Orderpaymenthistory[comment]"></td>

                            <td><input type="text" name="Orderpaymenthistory[amount]"></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	res=data.split("---");
	if(res[0]==1){
	//alert("in if");	
            location=window.location.href;
	}else{
            //alert("in else");
		$("#amount_recived").html(res[1]);
	}

	//$("#uploadBox").dialog("open");
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>
</div>