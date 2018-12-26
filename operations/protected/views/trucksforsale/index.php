<div id="notification"></div>
<div class="span12 top_box_fixed">
    <?php //$this->widget('ext.Flashmessage.Flashmessage'); ?> 
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gridForm',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
                <?php Library::addButton(array('label' => 'Create', 'url' => $this->createUrl('create'))); ?>
                <?php Library::buttonBulk(array('label' => 'Delete', 'permission' => $this->deletePerm, 'url' => $this->createUrl($this->uniqueid . "/delete"))); ?>
            </div>
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 top_box_margin">
        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}{items}",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataSet,
            'filter' => $model,
            'bulkActions' => array(
                'actionButtons' => array(
                ),
                'checkBoxColumnConfig' => array(
                    'name' => 'id',
                    'id' => 'id',
                    'value' => '$data->primaryKey',
                ),
            ),
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'id_sell_truck', 'header' => 'Id', 'value' => '"#".$data->id_sell_truck'),
                array('name' => 'id_customer', 'header' => 'Created By', 'value' => '$data->customer_fullname.",".$data->customer_mobile.",".$data->idprefix'),
                array('name' => 'truck_reg_no', 'header' => 'Reg No', 'value' => '$data->truck_reg_no'),
                array('name' => 'id_truck_type', 'header' => 'Truck Type', 'value' => array($this,'grid'),'filter' => CHtml::dropDownList('Selltruck[id_truck_type]', $_GET['Selltruck']['id_truck_type'], $this->truckType)),
                array('name' => 'contact_name', 'header' => 'Contact Name', 'value' => '$data->contact_name'),
                array('name' => 'contact_mobile', 'header' => 'Contact Mobile', 'value' => '$data->contact_mobile'),
                array('name' => 'interested', 'header' => 'Interested', 'value' => array($this,'grid'),'filter'=>false,"type"=>"raw"),
                array('name' => 'truck_reg_state', 'header' => 'Reg State', 'value' => '$data->truck_reg_state'),
                array('name' => 'insurance_exp_date', 'header' => 'Insurance Exp Date', 'value' => '$data->insurance_exp_date','filter'=>false),
                array('name' => 'fitness_exp_date', 'header' => 'Fitness Exp Date', 'value' => '$data->fitness_exp_date','filter'=>false),
                array('name' => 'odometer', 'header' => 'ODO', 'value' => '$data->odometer','filter'=>false),
                array('name' => 'any_accidents', 'header' => 'Any Accidents', 'value' => '$data->any_accidents==1?"Yes":"No"','filter'=>false),
                array('name' => 'in_finance', 'header' => 'In Fin', 'value' => '$data->in_finance==1?"Yes":"No"','filter'=>false),
                array('name' => 'expected_price', 'header' => 'Exp Price', 'value' => '$data->expected_price','filter'=>false),
                array('name' => 'truck_front_pic', 'header' => 'Images', 'value' => array($this,'grid'),'filter'=>false,"type"=>"raw"),
                array('name' => 'isactive', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('Selltruck[isactive]', $_GET['Selltruck']['isactive'], array('1' => 'Active', '0' => 'Inactive', '2' => 'Sold',)
                    ), 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'date_created', 'header' => 'Date Created', 'value' => '$data->date_created'),
                array('name' => 'date_modified', 'header' => 'Date Modified', 'value' => '$data->date_modified'),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],//'{delete}',
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label],
                            'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
        <?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
function fnchange(obj,id){
    $.ajax({
				url: '<?php  echo $this->createUrl("Trucksforsale/updateField");?>',
				dataType: 'json',
                                data:'field='+obj.id+'&id='+id+'&val='+obj.value,
                                type:'post',
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
