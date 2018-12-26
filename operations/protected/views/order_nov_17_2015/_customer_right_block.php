<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Transactions</div>
 
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content" id="hide_box_line7">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="customer_amount_R">
                            <tr>
                                <td>&nbsp;</td>
                                <td>Order Amount</td>
                                <td><?php echo $model['0']->payable_amount; ?></td>
                            </tr>
                            <?php
                            $payableAmount = $model['0']->payable_amount;
                            foreach (Orderpaymenthistory::model()->findAll(array('condition' => 'id_customer='.$model['0']->id_customer_ordered.' and id_order=' . (int) $_GET['id'], 'order' => 'id_order_payment_history desc')) as $orderpayment):
                                ?>
                                <tr>
                                    <td><?php echo $orderpayment->date_created; ?></td>
                                    <td><?php echo $_SESSION['id_admin_role']!=8?$orderpayment->comment:'Payment Made'; //transporter cannot view transaction comments ?></td>
                                    <td><?php echo $orderpayment->amount; ?></td>
                                </tr>
                                <?php
                                $payableAmount-=$orderpayment->amount;
                            endforeach;
                            ?>
                            <tr><td>&nbsp;</td><td>Pending Amount</td><td><?php echo $payableAmount; ?></td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                <?php if($_SESSION['id_admin_role']!=8){ //truck owner payments hidden from transporter ?>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr><td>Comment</td><td>Amount Received</td></tr>
                        <tr>
                            <td><input type="text" name="Orderpaymenthistory[comment_R]"></td>

                            <td><input type="text" name="Orderpaymenthistory[amount_R]"></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('type'=>'R','cid'=>$model['0']->id_customer_ordered,'id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	res=data.split("---");
	/*if(res[0]==1){
	//alert("in if");	
            location=window.location.href;
	}else{
            //alert("in else");
		$("#amount_recived").html(res[1]);
	}*/
        $("#customer_amount_"+res[0]).html(res[2]);
	//$("#uploadBox").dialog("open");
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
                        </tr>
                    </table>
                </div><?php }?>
            </fieldset>
        </div>
    </div>
</div>