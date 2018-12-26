<div class="row-fluid" >
    <div class="tab-pane active" id="Personal Details"><div class="span6">
    <fieldset class="portlet ">
        <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span11">Comment</div>
            <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
            <div class="clearfix"></div>	
        </div>
        <div id="comment_quote_design" >
        <div class="portlet-content" id="hide_box_line7">
           	 <?php $notifyRow=$_SESSION['id_admin_role']==8?'style="display:none"':'';//hide notify for tranporters
			  $statusRow=$_SESSION['id_admin_role']==8?'style="display:none"':'';//hide status for tranporters?>
			<table class="table uploading-status" border='0' id="table_comment">
                <thead >
                    <tr>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th <?php echo $notifyRow;?>>Notified Customer</th>
                        <th <?php echo $deleteRow;?>></th>
                    </tr>
                </thead>
                <tbody >
                    <?php foreach ($model['ltrsh'] as $row): ?>
                        <tr id="row_comment_<?php echo $row->id_load_truck_request_status_history; ?>">
                            <td><?php echo $row->date_created; ?></td>
                            <td><?php echo $row->status; ?></td>
                            <td><?php echo $row->message; ?></td>
                            <td <?php echo $notifyRow;?>><?php echo $row->notify_customer==1?'Yes':'No'; ?></td>
                            <td <?php echo $deleteRow;?>> <a onclick="fnDeleteComment('<?php echo $row->id_load_truck_request_status_history;?>')" ><i class="delete-icon-block"></i></a> </td>
                        </tr>
                    <?php endforeach;?>
                    
                </tbody>
                <tfoot>
                </tfoot>
            </table> 
        </div>  
        </div>
        <div class="portlet-content" id="hide_box_line3" <?php echo $notifyRow;?> >

            <table style="width:120%">
                <tr colspan="4"  style="padding-bottom:0px;line-height: 0px">
                    <th <?php echo $notifyRow;?>>Notify</th>
                    <th <?php echo $statusRow;?> >Status</th>
                    <th>Comment</th>
                </tr>
                <tr>
                    <td valign="top" <?php echo $notifyRow;?> ><input type="checkbox" name="Loadtruckrequeststatushistory[notify_customer]" value="1"></td>
                    <td valign="top" <?php echo $statusRow;?> ><?php echo CHtml::dropdownlist('Loadtruckrequeststatushistory[status]', $model['ltr']->status, Library::getLTRStatuses()); ?></td>
                    <td><textarea name="Loadtruckrequeststatushistory[comment]" rows="2" cols="90"></textarea></td>
                    <td><?php
                        echo CHtml::ajaxButton("Submit", $this->createUrl('load/Addcomment', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	    $("#table_comment thead").after(data);
	
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                        ?></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
            </div>
    </div>
<script>
function fnDeleteComment(id) {
    if(confirm("are you sure??")){
        //alert("in"+id)
        $('#row_comment_'+id).remove();   
        $.ajax({
                url: '<?php echo $this->createUrl("load/deletecomment")?>',
                type: 'post',
                data: 'id=' + id,
                dataType: 'json',
        });
    }
}
</script>