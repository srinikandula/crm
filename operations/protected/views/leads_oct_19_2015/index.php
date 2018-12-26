<?php
if(isset($_SESSION['EXCEL_ERROR_MESSAGE'])){
    echo '<p>Warning:Below records insertion failed because of missing values!!</p>';
    foreach($_SESSION['EXCEL_ERROR_MESSAGE'] as $k=>$v){
        echo '<p style="color:red">'.$v.'</p>';
    }
    unset($_SESSION['EXCEL_ERROR_MESSAGE']);
}
?>
<div class="span12 top_box_fixed">
    <?php    $this->widget('ext.Flashmessage.Flashmessage');    ?> 
    <?php
    $form = $this->beginWidget('CActiveForm',
            array(
        'id' => 'gridForm',
        'enableAjaxValidation' => false,
                'htmlOptions'=>array('enctype' => 'multipart/form-data'),
    ));
    ?>
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
            <?php Library::addButton(array('label'=>'Create','url'=> $this->createUrl('create')));  ?>
            <?php //Library::buttonBulkApprove(array('type'=>'info','label'=>'Approve','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/approve")));?>
			<?php Library::buttonBulk(array('label'=>'Delete','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/delete")));?>
            <?php //Library::buttonBulk(array('label'=>'Approve Customers','permission'=>$this->editPerm,'type'=>'info','url'=> $this->createUrl($this->uniqueid . "/approve")));?>
            </div>
        </div>
            <?php if($this->addPerm){?>
            <div style="height:30px">
    <input style="margin-top:-50px;margin-left:100px" type="file" name="import">
    <input style="margin-left:-88px;margin-top:-50px" type="submit" class="btn btn-info" name="submit" value="Import Excel"></div>
    <div >
        <a href="<?php echo Yii::app()->params['config']['admin_url']."customer_lead.xlsx ";?>">
            <div style="margin-left:395px;margin-top:-50px" class="btn btn-info">Download Excel</div>
        </a>
    </div>
    <?php }?>

            <div class="span2 dropdown_cut_main pull-right" style="margin-top:-40px">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
                </div>
            </div>
        
    </div>
</div>

<div class="row-fluid">
<div class="span12 top_box_margin" style="margin-top:-16px">

   
    
<?php

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	 'type'=>'striped bordered condensed',
	 'template'=>"{summary}{pager}<div>{items}</div>",
	 'summaryText'=>'Displaying {start}-{end} of {count} Results.',
	 'enablePagination' => true,
        'pager'=>array('class'=>'CListPager', 'header' => '&nbsp;Page',  'id' => 'no-widthdesign_left' ),
	'ajaxUpdate'=>false,
	'id'=>'productinfo-grid',
	'dataProvider'=>$dataSet,
	'filter'=>$model,
        'bulkActions' => array(
                'actionButtons' => array(
                    
                        ),
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
                array('name'=>'fullname','header'=>'Customer Name','value'=>'$data->fullname'),
				array('name'=>'company','header'=>'Company','value'=>'$data->company'),
				//array('name'=>'id_customer','header'=>'Upload/Download','value'=>array($this,'grid'),'type'=>'raw'),
				//array('name'=>'no_of_trucks','header'=>'No Of Trucks','value' => array($this,'grid'),'type'=>'raw','filter'=>false),
				array('name'=>'type','header'=>'Type','value'=>array($this,'grid')),
				array('name'=>'mobile','header'=>'Mobile','value'=>'$data->mobile'),
 				array('name'=>'landline','header'=>'Office No','value'=>'$data->landline'),
                                array('name'=>'email','header'=>'Email','value'=>'$data->email'),
				array('name'=>'city','header'=>'City','value'=>'$data->city'),
				array('name'=>'state','header'=>'State','value'=>'$data->state'),
				array('name'=>'lead_status','header'=>'Lead Status','value'=>'$data->lead_status'),
				array('name'=>'lead_source','header'=>'Lead Source','value'=>'$data->lead_source'),
				array('name'=>'enable_sms_email_ads','header'=>'Sms/Email','value'=>'$data->enable_sms_email_ads==1?"Enabled":"Disabled"','filter'=>false),
				array('name'=>'rating','header'=>'Rating','value'=>'$data->rating','filter'=>false),
                                array('name'=>'date_created','header'=>'Date Registered','value'=>'$data->date_created','filter'=>false),
				array('name'=>'date_created','header'=>'Date Registered','value'=>'$data->date_created'),
				array('name'=>'status','header'=>'Status',
				'filter'=>CHtml::dropDownList('Customer[status]', $_GET['Customer']['status'],  
				array(''=>'All','1'=>'Enable','0'=>'Disable',)
				), 'value'=>'$data->status==1?"Enabled":"Disabled"'),
                                array('name'=>'approved','header'=>'Approved',
				'filter'=>CHtml::dropDownList('Customer[approved]', $_GET['Customer']['approved'],  
				array(''=>'All','1'=>'Enable','0'=>'Disable',)
				), 'value'=>'$data->approved==1?"Enabled":"Disabled"'),
				
			
				
				//array('name'=>'approved','header'=>'Approved','filter'=>CHtml::dropDownList('Customer[approved]', $_GET['Customer']['approved'],array(''=>'All','1'=>'Approved','0'=>'Un-Approved',)), 'value'=>'$data->approved==1?"Approved":"Un-Approved"'),

		array('class'=>'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions'=>array('style'=>'min-width:50px;'),
                    'template'=>$this->gridPerm['template'],
                    'buttons'=>array('update'=>array('label'=>$this->gridPerm[buttons][update][label], 'url'=>'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),'delete'=>array('url'=>' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
        ),
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