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
			<?php Library::buttonBulkApprove(array('type'=>'info','label'=>'Approve','permission'=>$this->editPerm,'url'=> $this->createUrl($this->uniqueid . "/approve")));?>
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
<div class="note_box">
    <div class="clr_red"></div>
	<div class="note">Expired Trucks.</div>
	<div class="clr_warning"></div>
	<div class="note">Trucks expires in 5 days.</div>
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
            'dataProvider' => $dataSet,
            'filter' => $model,
            'rowCssClassExpression' => '( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ).(($data->vehicle_insurance_expiry_date <= date("Y-m-d")?" alert":"") || ($data->fitness_certificate_expiry_date) <= date("Y-m-d")?" alert":"").((date("Y-m-d") <= $data->fitness_certificate_expiry_date) && ($data->fitness_certificate_expiry_date) <=(date("Y-m-d", strtotime("+5 days")))?" notapproved":"").((date("Y-m-d") <= $data->vehicle_insurance_expiry_date) && ($data->vehicle_insurance_expiry_date) <=(date("Y-m-d", strtotime("+5 days")))?" notapproved":"") ',        
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
                /*array('name' => 'idprefix', 'header' => 'Id', 'value' => '$data->idprefix','class' => 'booster.widgets.TbEditableColumn',
                'headerHtmlOptions' => array('style' => 'width:200px'),
                'editable' => array(
                    'type' => 'text',
                    'url' => '/example/editable'
                )),*/
                array('name' => 'fullname', 'header' => 'Owner', 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'truck_reg_no', 'header' => 'Truck Reg No', 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'title', 'header' => 'Truck Type', 'value' => '$data->title'),
                array('name' => 'mileage', 'header' => 'Mileage', 'value' => '$data->mileage'),
                array('name' => 'tracking_available', 'header' => 'Tracking Available',
				
                    'filter' => CHtml::dropDownList('Truck[tracking_available]',
                            $_GET['Truck']['tracking_available'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->tracking_available==1?"Yes":"No"'),
                //array('name' => 'mileage', 'header' => 'Mileage', 'value' => '$data->mileage'),
                array('name' => 'fitness_certificate_expiry_date', 'header' => 'Fitness Exp Date', 'value' => '$data->fitness_certificate_expiry_date'),
                array('name' => 'vehicle_insurance_expiry_date', 'header' => 'Vehicle Ins Exp Date', 'value' => '$data->vehicle_insurance_expiry_date'),
                array('name' => 'vehicle_insurance', 'header' => 'Missing Docs', 'value' => array($this,'grid')),
				array('name' => 'status', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('Truck[status]',
                            $_GET['Truck']['status'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->status==1?"Enabled":"Disabled"'),

				array('name' => 'approved', 'header' => 'Approved',
                    'filter' => CHtml::dropDownList('Truck[approved]',
                            $_GET['Truck']['approved'],
                            array('' => 'All', '1' => 'Yes', '0' => 'No',)
                    ), 'value' => '$data->approved==1?"Yes":"No"'),
					array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label],
                            'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("cid"=>$_GET[cid],"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("cid"=>$_GET[cid],"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>