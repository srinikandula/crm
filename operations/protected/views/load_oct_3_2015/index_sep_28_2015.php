<div class="span12 top_box_fixed">
    <?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
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
                <?php //Library::buttonBulkApprove(array('type'=>'info','label'=>'Approve','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/approve")));?>
                <?php Library::buttonBulk(array('label' => 'Delete', 'permission' => $this->deletePerm, 'url' => $this->createUrl($this->uniqueid . "/delete"))); ?>
                <?php //Library::buttonBulk(array('label'=>'Approve Customers','permission'=>$this->editPerm,'type'=>'info','url'=> $this->createUrl($this->uniqueid . "/approve")));?>
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
        $transporterRowVisibility=$_SESSION['id_admin_role']==8?false:true;
        $adminVisibility=$_SESSION['id_admin_role']!=8?false:true;
        
        $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}<div>{items}</div><br/>{pager}{summary}",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataSet,
            'filter'=>$model,
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
                array('name' => 'title', 'header' => 'Title', 'value' => '$data->title','visible'=>$transporterRowVisibility),
                array('name' => 'fullname', 'header' => 'Customer Name', 'value' => '$data->fullname','visible'=>$transporterRowVisibility),
                array('name' => 'type', 'header' => 'Type',
                    'filter' => CHtml::dropDownList('Loadtruckrequest[type]', $_GET['Loadtruckrequest']['type'], array('' => 'All', 'L' => 'Load Owner', 'T' => 'Truck Owner', 'G' => 'Guest', 'C' => 'Commission Agent',)
                    ), 'value' => array($this, 'grid'),'visible'=>$transporterRowVisibility),
                array('name' => 'source_city', 'header' => 'Source', 'value' => '$data->source_city." - ".$data->source_state'),
                array('name' => 'destination_city', 'header' => 'Destination', 'value' => '$data->destination_city." - ".$data->destination_state', 'type' => 'raw'),
                array('name' => 'tracking', 'header' => 'Tracking Required',
                    'filter' => CHtml::dropDownList('Loadtruckrequest[tracking]', $_GET['Loadtruckrequest']['tracking'], array('' => 'All', '1' => 'Yes', '0' => 'No',)
                    ), 'value' => '$data->tracking==1?"Yes":"No"'),
                array('name' => 'insurance', 'header' => 'Insurance Required',
                    'filter' => CHtml::dropDownList('Loadtruckrequest[insurance]', $_GET['Loadtruckrequest']['insurance'], array('' => 'All', '1' => 'Yes', '0' => 'No',)
                    ), 'value' => '$data->insurance==1?"Yes":"No"'),
                array('name' => 'date_created', 'header' => 'Date Created', 'value' => '$data->date_created'),
                array('name' => 'truck_type', 'header' => 'Truck Type', 'value' => '$data->truck_type'),
                array('name' => 'goods_type', 'header' => 'Goods Type', 'value' => '$data->goods_type'),
                array('name' => 'date_required', 'header' => 'Date Required', 'value' => '$data->date_required'),
                array('name' => 'status', 'header' => 'Status', 'value' => '$data->status'),
                array('name' => 'least_quote', 'header' => 'Least Quote', 'value' => array($this,'grid'), 'filter' => false,'visible'=>$adminVisibility),
                array('name' => 'approved', 'header' => 'Approved',
                    'filter' => CHtml::dropDownList('Loadtruckrequest[approved]', $_GET['Customer']['approved'], array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->approved==1?"Enabled":"Disabled"','visible'=>$transporterRowVisibility),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label], 'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("cid"=>$_GET[cid],"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'), 'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("cid"=>$_GET[cid],"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
        <?php $this->endWidget(); ?>
    </div>
</div>
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
                </div>
            </div>
<div class="clearfix"></div>	
<div class="clearfix"></div>	