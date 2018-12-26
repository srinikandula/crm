
<div class="span6" style="margin-left:0px">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Comments</div>
                    <div class="clearfix"></div>	
                </div>
                <?php //if($model['0']->id_truck_attachment_policy==1){?>
                <div style="min-height:143px;max-height: 143px;overflow: auto;">
                <div class="portlet-content" id="hide_box_line7">
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Admin</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="customer_amount_P">
                            <?php
							$rows=Yii::app()->db->createCommand("select oc.*,DATE_FORMAT(oc.date_created,'%d-%m-%Y %h:%i %p') as date_created,concat(a.first_name,' ',a.last_name) as admin_name from eg_order_comment oc,eg_admin a where oc.id_order='".(int)$_GET['id']."' and a.id_admin=oc.id_admin order by oc.date_created desc")->queryAll();
                            foreach ($rows as $orderpayment):?>
                                <tr>
                                    <td><?php echo $orderpayment[date_created]; ?></td>
                                    <td><?php echo $orderpayment[comment]; ?></td>
                                    <td><?php echo $orderpayment[admin_name]; ?></td>
                                    <td ><a onclick="fnDelete('<?php echo $orderpayment[id_order_comment];?>','comment')"><i class="delete-icon-block"></i></a></td>
                                </tr>
                                <?php   endforeach;
								if(!sizeof($rows)){ echo '<tr><td colspan="4">No records found!!</td></tr>';}
								?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>
                </div>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
							<td><textarea name="Ordercomment[comment]"  placeholder="Comment" rows="3" cols="50"></textarea></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/addComments', array('id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
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
