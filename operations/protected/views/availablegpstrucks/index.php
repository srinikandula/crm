<div id="notification"></div>
<div class="note_box">
    <div class="clr_blue"></div>
    <div class="note">Today Available Trucks.</div>
</div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details"> 
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'gridForm',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            //'type'=>'striped',
            'template' => "{summary}{pager}<div class='items_main_div span12 page'>{items}</div>",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            //'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataProvider,
            'filter' => $model,
            'rowCssClassExpression' => '( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ). (date("Y-m-d", strtotime($data->date_available)) == date("Y-m-d")?" today":"")',
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'name', 'header' => "Name", 'value' => '$data[name]'),
                array('name' => 'mobile_no', 'header' => "Mobile", 'value' => '$data[mobile_no]'),
                array('name' => 'truck_reg_no', 'header' => "Truck Reg No", 'value' => '$data[truck_reg_no]'),
                array('name' => 'truck_type', 'header' => "Truck Type", 'value' => '$data[truck_type]'),
                array('name' => 'address', 'header' => "Address", 'value' => '$data[address]'),
                array('name' => 'date_available', 'header' => "Date Available", 'value' => '$data[date_available]'),
				array('name' => 'add_points', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('Gpstrucklocation[status]',
                            $_GET['Gpstrucklocation']['status'],
                            array('' => 'All', '1' => 'Valid', '0' => 'Invalid',)
                    ), 'value' => array($this,'grid'),'type'=>'raw'),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>
    </div>
</div>
<script>
function fnchange(field,id,aid){
    $.ajax({
				url: '<?php  echo $this->createUrl("Availablegpstrucks/update");?>',
				dataType: 'json',
                                data:'id='+id+'&aid='+field.value+'&cid='+aid,
                                type:'get',
				beforeSend: function() {
					$('#step1 a').after('<span class="wait">&nbsp;<img src="<?php echo Yii::app()->params[config][site_url];?>images/loading.gif" alt="" /></span>');
				},
				success: function(json) {
                                    if (json['status']) {					$('#notification').html('<div class="alert in fade alert-success" id="success">Update Successful!!<a style="cursor:pointer" class="close" data-dismiss="alert">×</a></div>');
                                    $('#success').fadeIn('slow').delay(2000).fadeOut(2000);
                                    }else{
                                        $('#notification').html('<div class="alert in fade alert-danger" id="success">Update Failed!!<a style="cursor:pointer" class="close" data-dismiss="alert">×</a></div>');
                                    $('#success').fadeIn('slow').delay(2000).fadeOut(2000);
                                    }
				}
			});
}
</script>