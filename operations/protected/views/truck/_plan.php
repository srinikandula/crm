<div class="row-fluid" >
    <div class="tab-pane active" id="Personal Details"><div class="span12">
    <fieldset class="portlet ">
        <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span11">Plan</div>
            <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
            <div class="clearfix"></div>	
        </div>
        <div>
        <div class="portlet-content" id="hide_box_line7">
           	<table class="table uploading-status" border='0' id="table_plan">
                <thead >
                    <tr>
                        <th>plan</th>
                        <th>Min kms</th>
                        <th>Price per km</th>
                        <th>Flat rate</th>
                        <th>Diesel price/km 
                        <th>Start date</th>
                        <th>End date</th>
                        <th <?php echo $deleteRow;?>></th>
                        </tr>
                </thead>
                
                <tbody >
                       <?php 
                      //echo '<pre>';print_r($model['Customertruckattachmentpolicy']);echo '</pre>';
                       $planRows= Truckattachmentpolicy::model()->findAll();
                       $planData=array();
                       foreach($planRows as $planRow){
                       $planData[$planRow->id_truck_attachment_policy]=array('title'=>$planRow->title,
                           'description'=>$planRow->description);    
                       }
                       
                       foreach ($model['ctap'] as $row): ?>
                        <tr id="row_plan_<?php echo $row->id_customer_truck_attachment_policy; ?>">
                            <td><?php echo $planData[$row->id_truck_attachment_policy]['title']; ?></td>
                            <td><?php echo $row->min_kms; ?></td>
                            <td><?php echo $row->price_per_km; ?></td>
                            <td><?php echo $row->flat_rate; ?></td>
                            <td><?php echo $row->diesel_price_per_km; ?></td>
                            <td><?php echo $row->date_start; ?></td>
                            <td><?php echo $row->date_end; ?></td>
                            <td <?php echo $deleteRow;?>> <a onclick="fnDeletePlan('<?php echo $row->id_customer_truck_attachment_policy;?>')" ><i class="delete-icon-block"></i></a> </td>
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
                    <th>Plan</th>
                    <th class="min_kms">Min kms</th>
                    <th class="min_kms">Price per km</th>
                    <th class="flat_rate">Flat rate</th>
                    <th class="flat_rate">Diesel price/km </th>
                    <th class="date">Start date</th>
                    <th class="date">End date</th>
                </tr>
                <tr>
                    <td valign="top"><?php 
                    $policyList=CHtml::listData(Truckattachmentpolicy::model()->findAll(array('select' => 'concat(id_truck_attachment_policy,"#",title) as id_truck_attachment_policy,title', 'condition' => 'status=1')), 'id_truck_attachment_policy', 'title');
echo Chtml::dropdownlist('Customertruckattachmentpolicy[title]','',$policyList);
//echo $form->dropDownListRow($model['ctap'], 'title', CHtml::listData(Truckattachmentpolicy::model()->findAll(array('select' => 'concat(id_truck_attachment_policy,"#",title) as id_truck_attachment_policy,title', 'condition' => 'status=1')), 'id_truck_attachment_policy', 'title')); ?></td>
                    <td class="min_kms"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Customertruckattachmentpolicy[min_kms]" id="Customertruckattachmentpolicy_min_kms" type="text" value=""></td>
                    <td class="min_kms"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Customertruckattachmentpolicy[price_per_km]" id="Customertruckattachmentpolicy_price_per_km" type="text" value=""></td>
                    <td class="flat_rate"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Customertruckattachmentpolicy[flat_rate]" id="Customertruckattachmentpolicy_flat_rate" type="text" value=""></td>
                    <td class="flat_rate"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Customertruckattachmentpolicy[diesel_price_per_km]" id="Customertruckattachmentpolicy_diesel_price_per_km" type="text" value=""></td>
                    <td class="date"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Customertruckattachmentpolicy[date_start]" id="Customertruckattachmentpolicy_date_start" type="text" value=""></td>
                    <td class="date"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Customertruckattachmentpolicy[date_end]" id="Customertruckattachmentpolicy_date_end" type="text" value=""></td>
                    <td><?php
                        echo CHtml::ajaxButton("Submit", $this->createUrl('Addplan', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
	    $("#table_plan thead").after(data);
	
}',), array('confirm' => 'Are you sure??'));
                        ?></td>
                </tr>
            </table>
        </div>
        <?php //echo '<pre>';print_r($model['ctap']->date_start);exit; ?>
    </fieldset>
</div>
            </div>
    </div>

<script type="text/javascript">
$(document).ready(function(){
    $('select[name="Customertruckattachmentpolicy[title]"]').click(function(){
        if($(this).attr("value")=="2#Per Km With Min Guarante"){
            $(".min_kms").show();
            $(".flat_rate").val('');
            $(".diesel_price_per_km").val('');
            }
        if($(this).attr("value")=="3#Flat Payment With Diesel Exp"){
            $(".flat_rate").show();
            $(".min_kms").val('');
            $(".price_per_km").val('');
            }    
        if($(this).attr("value")!="3#Flat Payment With Diesel Exp"){
            $(".flat_rate").hide();
            }
        if($(this).attr("value")!="2#Per Km With Min Guarante"){
            $(".min_kms").hide();
            }
        if($(this).attr("value")!="1#Per Transaction (Default)"){
            $(".date").show();
            }    
        if($(this).attr("value")=="1#Per Transaction (Default)"){
            $(".date").hide();
            }
    });
    
});
</script> 
<script>
        $(function () {
            $("#Customertruckattachmentpolicy_date_start").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#Customertruckattachmentpolicy_date_end").datepicker({ dateFormat: 'yy-mm-dd' });
        });
    </script>
    <script>
function fnDeletePlan(id) {
    if(confirm("are you sure??")){
        //alert("in"+id)
        $('#row_plan_'+id).remove();   
        $.ajax({
                url: '<?php echo $this->createUrl("truck/deleteplan")?>',
                type: 'post',
                data: 'id=' + id,
                dataType: 'json',
        });
    }
}
</script>