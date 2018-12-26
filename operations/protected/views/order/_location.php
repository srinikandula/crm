<div class="row-fluid" >
    <div class="tab-pane active" id="Personal Details"><div class="span6">
    <fieldset class="portlet ">
        <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span11">Location</div>
            <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
            <div class="clearfix"></div>	
        </div>
            <div style="min-height:69px;max-height: 69px;overflow: auto;">
        <div class="portlet-content" id="hide_box_line7">
           	<table class="table uploading-status" border='0' id="table_location">
                <thead >
                    <tr>
                        <th>Address</th>
                        <th>Date Time</th>
                        <th <?php echo $deleteRow;?>></th>
                        </tr>
                </thead>
                
                <tbody >
                       <?php 
                      
                       
                       foreach ($model['otrh'] as $row): ?>
                        <tr id="row_location_<?php echo $row->id_order_truck_route_history; ?>">
                            <td><?php echo $row->location_address; ?></td>
                            <td><?php echo $row->date_time; ?></td>
                            <td <?php echo $deleteRow;?>> <a onclick="fnDeleteLocation('<?php echo $row->id_order_truck_route_history;?>')" ><i class="delete-icon-block"></i></a> </td>
                            </tr>
                    <?php endforeach;?>
                            
                </tbody>
                <tfoot>
                </tfoot>
            </table> 
        </div>
        </div>
        <div class="portlet-content" id="hide_box_line3">

            <table style="width:120%">
                <tr colspan="4"  style="padding-bottom:0px;line-height: 4px">
                    <th>Location Address</th>
                    <th>Date Time</th>
                </tr>
                <tr>
                    <td><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Ordertruckroutehistory[location_address]" id="Ordertruckroutehistory_location_address" type="text" value=""></td>
                    <td><input class="datetimepicker" placeholder="Date Time" rel="tooltip" data-toggle="tooltip" data-placement="right" name="Ordertruckroutehistory[date_time]" id="Ordertruckroutehistory_date_time" type="text" value=""></td>
                    <td><?php
                        echo CHtml::ajaxButton("Submit", $this->createUrl('Addlocation', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	    $("#table_location thead").after(data);
	
}',), array('confirm' => 'Are you sure??'));
                        ?></td>
                </tr>
            </table>
        </div>
        
    </fieldset>
</div>
            </div>
    </div>
 <script>
function fnDeleteLocation(id) {
    if(confirm("are you sure??")){
        //alert("in"+id)
        $('#row_location_'+id).remove();   
        $.ajax({
                url: '<?php echo $this->createUrl("order/deletelocation")?>',
                type: 'post',
                data: 'id=' + id,
                dataType: 'json',
        });
    }
}
</script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
                                    $('.datetimepicker').datetimepicker({
                                        dayOfWeekStart: 1,
                                        lang: 'en',
                                        format: 'Y-m-d H:i',
                                        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                                    });
</script>
