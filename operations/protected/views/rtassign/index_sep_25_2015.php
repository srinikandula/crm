<div id="notification"></div>
<div class="span12 top_box_fixed">
    <?php    //$this->widget('ext.Flashmessage.Flashmessage'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm',
            array(
        'id' => 'gridForm',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
            <?php Library::addButton(array('label'=>'Create','url'=> $this->createUrl('create')));  ?>
            <?php Library::buttonBulk(array('label'=>'Delete','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/delete")));?>
            </div>
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $model->Assignment()->totalItemCount)); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 top_box_margin">

        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView',
                array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}{items}",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $model->Assignment(),
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
                array('name' => 'date_created', 'header' => 'Date Created', 'value' => '$data->date_created'),
                array('name' => 'id_load_truck_request', 'header' => 'Id', 'value' => '$data->id_load_truck_request','filter'=>false),
		array('name' => 'id_admin_created', 'header' => 'Created By', 'value' => array($this,'grid'),'filter'=>false),
		array('name' => 'id_admin_assigned', 'header' => 'Assigned To', 'value' => array($this,'grid'),'type'=>'raw','filter'=>false),
		array('name' => 'status', 'header' => 'Status', 'value' => '$data->status'),
                /*array('name' => 'status', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('Trucktype[status]',
                            $_GET['Trucktype']['status'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->status==1?"Enabled":"Disabled"'),*/
               
            ),
        ));
        //echo "value of ".$this->gridPerm['template'];
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
function fnchange(field,id){
    $.ajax({
				url: '<?php  echo $this->createUrl("rtassign/update");?>',
				dataType: 'json',
                                data:'id='+id+'&aid='+field.value,
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