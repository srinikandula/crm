<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
            <div class="span6" id="pull_top">
                <fieldset class="portlet " >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Post Document Collection Message</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5">
                        <table class="table uploading-status" border='0'>
                            <thead>
                                <tr>
                                    <th>Assign To</th>
                                    <th>Contact Name</th>
                                    <th>Mobile</th>
                                    <th>Meeting Date/time</th>
                                    <th>Meeting Address</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="grand_total">
                                <tr>
                                    <td><?php echo CHtml::dropDownList('Customerleadassignment[id_admin_to]', '', CHtml::listData($model['field'], 'id_admin', 'first_name'),array('prompt'=>'Select')); ?></td>
                                    <td><input type='text' name='Customerleadassignment[name]' id='Customerleadassignment_name' ></td>
                                    <td><input type='text' name='Customerleadassignment[mobile]' id='Customerleadassignment_mobile' ></td>
                                    <td><input type='text' name='Customerleadassignment[meeting_date_time]' id='Customerleadassignment_meeting_date_time'  class="datetimepicker"></td>
                                    <td>
                                        <textarea id="Customerleadassignment_message" name="Customerleadassignment[message]" cols="30" rows="3"></textarea></td>
                                    <td><?php
                            if($_GET['id']){            echo CHtml::ajaxButton("Submit", $this->createUrl('leads/updateLeadAssigment', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
//location=window.location.href;	
$("#notification").after("<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button><i class=\"icon-success icon-white\"></i> <strong>Updated Successfully!!</strong></div>");
                            }',), array('confirm' => 'Are you sure.you want to proceed??'));}
                                        ?></td>
                                </tr>
                                <tr><td colspan="6"><div>Note:Message should contain doc list to be collected and address.</div></td></tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table> </div>
                </fieldset>
            </div>    
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
    $('#Customer_mobile').bind('keyup',function(){
        $('#Customerleadassignment_mobile').val($('#Customer_mobile').val());
    });
    $('#Customer_fullname').bind('keyup',function(){
        $('#Customerleadassignment_name').val($('#Customer_fullname').val());
    });
$('.datetimepicker').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
                        format: 'Y-m-d H:i',
                        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                    });
</script>