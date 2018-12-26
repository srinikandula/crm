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
            //'filter' => $model,
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
                array('name' => 'id_order', 'header' => 'Order Id', 'value' => '$data->id_order'),
				array('name' => 'fullname', 'header' => 'Customer', 'value' => '$data->fullname'),
				array('name' => 'posts', 'header' => 'Cases', 'value' => '$data->posts'),
				array('name' => 'dc', 'header' => 'Date Created', 'value' => '$data->dc'),
				array('name'=>'status','header'=>'Status',
				'filter'=>CHtml::dropDownList('Ordercustomersupport[status]', $_GET['Ordercustomersupport']['status'],  
				array(''=>'All','1'=>'Closed','0'=>'Open',)
				), 'value'=>'$data->status==1?"Closed":"Open"'),

array('class'=>'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions'=>array('style'=>'min-width:50px;'),
                    'template'=>$this->gridPerm['template'],
                    'buttons'=>array('update'=>array('label'=>$this->gridPerm[buttons][update][label], 'url'=>'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->id_order,"idc"=>$data->id_customer))'),'delete'=>array('url'=>' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->id_order,"idc"=>$data->id_customer))')),
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