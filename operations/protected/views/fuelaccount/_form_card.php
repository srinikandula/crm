<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12 pull-right" id="truck_list_table">
            <?php
            $box = $this->beginWidget(
                'bootstrap.widgets.TbBox', array(
                'title' => 'Cards List',
                'htmlOptions' => array('class' => 'portlet-decoration	')
                )
            );
            ?>
            <table class="table" id="table_upload_truck">
                <thead>
                    <tr>
                        <th>Card No</th>
                        <th>Vehicle No</th>
                        <th>Issued Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th  width="8%">Action</th>
                    </tr>
                </thead>
                    <tbody >
                        <?php 
						//echo '<pre>';print_r($model['t']);echo '</pre>';
						$row=0;
						foreach ($model['t'] as $tObj) {  ?>
                        <tr>
                            <td><input type="text" name="Truck[<?php echo $row; ?>][card_no]" value="<?php echo $tObj->card_no; ?>"><input type="hidden" name="Truck[<?php echo $row; ?>][id_fuel_card]" value="<?php echo $tObj->id_fuel_card; ?>"></td>
                            <td><input type="text" name="Truck[<?php echo $row; ?>][vehicle_no]" value="<?php echo $tObj->vehicle_no; ?>"></td>
                            <td><input type="text" name="Truck[<?php echo $row; ?>][issue_date]" class="date" value="<?php echo $tObj->issue_date; ?>"></td>
                            <td><input type="text" name="Truck[<?php echo $row; ?>][expiry_date]" class="date" value="<?php echo $tObj->expiry_date; ?>"></td>
                            <td><?php echo CHtml::dropdownlist('Truck['.$row.'][status]', $tObj->status, array("1" => "Yes","0" => "No")); ?></td>                                
                            <td> <a onclick="deleteFuelCard(this,<?php echo $tObj->id_fuel_card; ?>);" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                        </tr>
                        <?php $row++;} ?>
                    </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12"><?php
                            $this->widget(
                                    'bootstrap.widgets.TbButton', array(
                                'label' => 'Add',
                                'type' => 'btn-info',
                                'htmlOptions' => array('onclick' => 'addTruck()'),
                                    )
                            );
                            ?></td>
                    </tr>
                </tfoot>
            </table>
<?php $this->endWidget(); ?>
        </div>
    </div>

</div>
<script type="text/javascript">
    var row=<?php echo $row;?>;
	function addTruck()
    {
		
		row++;
		
        $('#table_upload_truck tbody').append('<tr><td><input type="text" name="Truck['+row+'][card_no]"><input type="hidden" name="Truck['+row+'][id_fuel_card]"></td><td><input type="text" name="Truck['+row+'][vehicle_no]"></td><td><input type="text" class="date" name="Truck['+row+'][issue_date]"></td><td><input type="text" name="Truck['+row+'][expiry_date]"  class="date"  ></td><td><select name="Truck['+row+'][status]"><option value="1">Yes</option><option value="0">No</option></select></td><td><a onclick="$(this).parent().parent().remove();" class="btn btn-danger"><i class="delete-iconall"></i></a></td></tr>');
        jQuery('.date').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
    }
	jQuery('.date').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});

	function deleteFuelCard(id,id_fuel_card){
		if(confirm("Do you want to delete the fuel card?")){
			$(id).parent().parent().remove();
			$.ajax({
					url: '<?php echo $this->createUrl("fuelaccount/deletefuelcard");?>',
					type: 'post',
					data: 'id='+id_fuel_card,
					dataType: 'json',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function(json) {
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		}
	}
</script>