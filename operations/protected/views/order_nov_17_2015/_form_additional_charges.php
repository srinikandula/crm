<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                    <div class="span11">Additional Charges</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content" id="hide_box_line5">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Comment</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="grand_total">
                            <tr>
                                <td>Original Amount</td>
                                <td><?php echo $model['0']->amount; ?></td>
                            </tr>
                            <?php
                            $grandTotal = $model['0']->amount;
                            foreach (Orderamount::model()->findAll(array('condition' => 'id_order=' . (int) $_GET['id'], 'order' => 'id_order_amount desc')) as $orderamount):
                                ?>
                                <tr>
                                    <td><?php echo $orderamount->comment; ?></td>
                                    <td><?php echo $orderamount->amount_prefix . $orderamount->amount; ?></td>
                                </tr>
                                <?php
                                if ($orderamount->amount_prefix == '+') {
                                    $grandTotal+=$orderamount->amount;
                                } else {
                                    $grandTotal-=$orderamount->amount;
                                }
                            endforeach;
                            ?>
                            <tr><td>Grand Total</td><td><?php echo $grandTotal; ?></td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr><td>Comment</td><td>Add/Sub</td><td>Amount</td></tr>
                        <tr>
                            <td><input type="text" name="Orderamount[comment]"></td>
                            <td><select name="Orderamount[amount_prefix]" ><option value="+">+</option><option value="-">-</option></select></td>
                            <td><input type="text" name="Orderamount[amount]"></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/updateAmount', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	res=data.split("---");
	if(res[0]==1){
	//alert("in if");	
            location=window.location.href;
	}else{
            //alert("in else");
		$("#grand_total").html(res[1]);
	}

	//$("#uploadBox").dialog("open");
}', /* ,'update' => '#grand_total' */
                                        ), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>
</div>