<div class="span12 top_box_fixed">
    <?php    $this->widget('ext.Flashmessage.Flashmessage'); ?>
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
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div>Sms Credit Balance:<?php echo Library::getSmsBalace();?></div>
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
                array('name' => 'accountID', 'header' => 'accountID', 'value' => '$data->accountID'),
                array('name' => 'contactEmail', 'header' => 'Contact Email', 'value' => '$data->contactEmail'),
                array('name' => 'contactPhone', 'header' => 'Contact Phone', 'value' => '$data->contactPhone'),
                array('name' => 'password', 'header' => 'Password', 'value' => '$data->password'),
				array('name' => 'no_of_vehicles', 'header' => 'No Of Vehicles', 'value' => '$data->no_of_vehicles','filter'=>false),
                array('name' => 'vehicleType', 'header' => 'Vehicle Type',
                    'filter' => CHtml::dropDownList('GpsAccount[vehicleType]',
                            $_GET['GpsAccount']['vehicleType'],
                            array('' => 'All', 'TRUCK' => 'TRUCK', 'NONTRUCK' => 'NONTRUCK','BOTH' => 'BOTH')
                    ), 'value' => '$data->vehicleType=="TRUCK"?"TRUCK":($data->vehicleType=="NONTRUCK"?"NONTRUCK":"BOTH")'),
                array('name' => 'smsEnabled', 'header' => 'SMS',
                    'filter' => CHtml::dropDownList('GpsAccount[smsEnabled]',
                            $_GET['GpsAccount']['smsEnabled'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->smsEnabled==1?"Enabled":"Disabled"'),
                array('name' => 'creationTime', 'header' => 'Date Created', 'value' => array($this,'grid')),
                array('name' => 'isActive', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('GpsAccount[isActive]',
                            $_GET['GpsAccount']['isActive'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->isActive==1?"Enabled":"Disabled"'),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label],
                            'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("ids"=>$data->primaryKey,"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("ids"=>$data->primaryKey,"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>
