<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
            <div class="span6">
                <fieldset class="portlet " >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Post Lead Status/Comment</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5">
                        <table class="table uploading-status" border='0'>
                            <thead>
                                <tr>
                                    <th>Lead Status</th>
                                    <th>Comment</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="grand_total">
                                <tr>
                                    <td><?php echo CHtml::dropDownList('Customerleadstatushistory[status]', '', $model['leadStatuses'],array('prompt'=>'Select')); ?></td>
                                    <td><textarea id="Customerleadstatushistory_message" name="Customerleadstatushistory[message]" cols="30" rows="3"></textarea></td>
                                    <td><?php
                                        if($_GET['id']){
                                        echo CHtml::ajaxButton("Submit", $this->createUrl('leads/updateStatus', array('id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
                                            //alert(data["status"])
if(data["status"]==1 && data["approved"]==1){
    
    location=data["url"];	
}else if(data["status"]==1){
	$("#notification").after("<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button><i class=\"icon-success icon-white\"></i> <strong>Updated Successfully!!</strong></div>");
	//location=window.location.href;
	}
}',), array('confirm' => 'Are you sure.you want to update the status??'));}
                                        ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table> </div>
                </fieldset>
            </div> 
    </div>
</div>