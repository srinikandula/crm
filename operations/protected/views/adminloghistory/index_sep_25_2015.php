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

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'striped bordered condensed',
	'template'=>"{summary}{pager}<div class='items_main_div span12'>{items}</div>",
	'summaryText'=>'Displaying {start}-{end} of {count} Results.',
	'enablePagination' => true,
	'pager'=>array('class'=>'CListPager', 'header' => '&nbsp;Page',  'id' => 'no-widthdesign_left' ),
	'ajaxUpdate'=>false,
	'id'=>'productinfo-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
        /*'bulkActions' => array(
					'actionButtons' => array(	),
					'checkBoxColumnConfig' => array(
					'name' => 'id',
                    'id'=>'id',
                    'value'=>'$data->primaryKey',
                   ),
				),*/
		'columns'=>array(
		array(
        'header'=>'S.No',
        'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
		),
				array('name' => 'first_name','header'=>'First Name','value'=>'$data->first_name'),
				array('name' => 'last_name','header'=>'Last Name','value'=>'$data->last_name'),
				array('name' => 'role','header'=>'Role','value'=>'$data->role'),
				array('name' => 'access_date','header'=>'Access Date','value'=>'$data->access_date','filter'=>false),
				array('name' => 'page_accessed','header'=>'Page Accessed','value'=>array($this,'grid'),'filter'=>false,'type'=>'raw'),
				array('name' => 'action','header'=>'Action','value'=>'$data->action','filter'=>false),
				array('name' => 'ip_address','header'=>'Ip Address','value'=>'$data->ip_address','filter'=>false),
				

 
	),
)); 
?>
    <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri));?>">
<?php $this->endWidget(); ?>
</div>
</div>

<div class="clearfix"></div>	
</div>
<div class="clearfix"></div>	
</div>