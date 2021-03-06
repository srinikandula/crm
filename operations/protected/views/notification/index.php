<div class="span12 top_box_fixed">
    <?php    $this->widget('ext.Flashmessage.Flashmessage');    ?> 
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
                    <?php Library::getPageList(array('totalItemCount' => $model->search()->totalItemCount)); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 top_box_margin">
        <?php

		$modPerm=$this->gridPerm['template']=="{update} {delete}"?"{delete}":"";
		//exit($this->gridPerm['template']."value of ".$modPerm);
        $this->widget('bootstrap.widgets.TbExtendedGridView',
                array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}{items}",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $model->search(),
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
                array('name' => 'fullname', 'header' => 'Customer', 'value' => '$data->fullname'),
				array('name' => 'type', 'header' => 'Type',
				'filter'=>CHtml::dropDownList('Notification[type]', $_GET['Notification']['type'],  
				array(''=>'All','T'=>'Truck','L'=>'Load','C'=>'Commission Agent','G'=>'Guest')
				), 'value' => array($this,'grid')),
				array('name' => 'action', 'header' => 'Action', 'value' => array($this,'grid'),'type'=>'raw'),
				array('name' => 'date_created', 'header' => 'Date Created', 'value' => '$data->date_created'),
				array('name'=>'verified','header'=>'Verified',
				'filter'=>CHtml::dropDownList('Notification[verified]', $_GET['Notification']['verified'],  
				array(''=>'All','1'=>'Enable','0'=>'Disable',)
				), 'value'=>array($this,'grid'),'type'=>'raw'),

                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $modPerm,
                    'buttons' => array(
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
		//echo $modPerm."value of ".$this->gridPerm['template'];
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
function fnchange(id,val){
	//alert("id "+id+" val"+val);
	$.ajax({
		//url: site_url+'index.php/checkout/cart',
		url: '<?php echo $this->createUrl("notification/verify");?>',
		type: 'post',
		data: 'id=' + id + '&val=' + val,
		dataType: 'json',
		success: function(json) {
		
			/*if (json['success']) {
				$("#total_price").html(json['total_price']);
				$("#total_qty").html(json['total_qty']);
				$('#notification').html('<div class="alert in fade alert-success" id="success">' + json['success'] + '<a style="cursor:pointer" class="close" data-dismiss="alert">�</a></div>');
				$('#success').fadeIn('slow').delay(2000).fadeOut(2000);
			}	*/
		}
	});
}
</script>