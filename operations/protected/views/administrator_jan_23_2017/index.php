<?php //echo '<pre>';print_r($model);echo '</pre>';
//exit;
?>
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
            <?php 
			if($_SESSION['id_admin_role']==1){
			Library::addButton(array('label'=>'Create','url'=> $this->createUrl('create')));  }?>
            <?php Library::buttonBulk(array('label'=>'Delete','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/delete")));?>
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
		<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	 'type'=>'striped bordered condensed',
	 'template'=>"{summary}{pager}<div class='items_main_div span12'>{items}</div>",
	 'summaryText'=>'Displaying {start}-{end} of {count} Results.',
	 'enablePagination' => true,
        'pager'=>array('class'=>'CListPager', 'header' => '&nbsp;Page',  'id' => 'no-widthdesign_left' ),
	'ajaxUpdate'=>false,
	'id'=>'productinfo-grid',
	'dataProvider'=>$dataSet,
	'filter'=>$model,
        'bulkActions' => array(
                'actionButtons' => array(),
                'checkBoxColumnConfig' => array(
                    'name' => 'id',
                    'id'=>'id',
                    'value'=>'$data->primaryKey',
                        ),
        ),
	'columns'=>array(
     array(
        'header'=>'S.No',
        'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
      ),
				array('name'=>'first_name', 'header'=>'First Name','value'=>'$data->first_name'),
				array('name'=>'last_name', 'header'=>'Last Name','value'=>'$data->last_name'),
				array('name'=>'email', 'header'=>'Email','value'=>'$data->email'),
				array('name'=>'phone', 'header'=>'Mobile','value'=>'$data->phone'),
				array('name'=>'city', 'header'=>'City','value'=>'$data->city'),
				array('name'=>'state', 'header'=>'State','value'=>'$data->state'),
				array('name'=>'admin_role', 'header'=>'Role','value'=>'$data->adminrole->role'),
                array(
                'name'=>'status','header'=>'Status',
                'filter'=>CHtml::dropDownList('Admin[status]', $_GET['Admin']['status'],  
                array(''=>'All','1'=>'Enable','0'=>'Disable',)
                ), 'value'=>'$data->status==1?"Enabled":"Disabled"'),

                array('class'=>'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions'=>array('style'=>'min-width:50px;'),
                    'template'=>$this->gridPerm['template'],
                    'buttons'=>array('update'=>array('label'=>$this->gridPerm[buttons][update][label], 'url'=>'Yii::app()->		 createUrl(Yii::app()->controller->id."/update/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),'delete'=>array('url'=>' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
        ),
	),
)); 
?>
    <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri));?>">
<?php $this->endWidget(); ?>
			</div>
		  </div>

