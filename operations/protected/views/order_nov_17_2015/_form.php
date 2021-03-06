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
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $model->searchTruckBooking()->totalItemCount)); ?>
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
	 'template'=>"{summary}{pager}<div>{items}</div>",
	 'summaryText'=>'Displaying {start}-{end} of {count} Results.',
	 'enablePagination' => true,
        'pager'=>array('class'=>'CListPager', 'header' => '&nbsp;Page',  'id' => 'no-widthdesign_left' ),
	'ajaxUpdate'=>false,
	'id'=>'productinfo-grid',
	'dataProvider'=>$model->searchTruckBooking(),
	'filter'=>$model,
        /*'bulkActions' => array(
                'actionButtons' => array(
                    
                        ),
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
		array('name'=>'truck_reg_no','header'=>'Truck Reg No','value'=>'$data->truck_reg_no'),
                array('name'=>'source_address','header'=>'Source','value'=>'$data->source_address'),
		array('name'=>'destination_address','header'=>'Destination','value'=>'$data->destination_address'),
            
		array('name'=>'date_available','header'=>'Date Available','value'=>'$data->date_available'),
                array('name'=>'price','header'=>'Price','value'=>array($this,'gridSearch')),
                array('name'=>'truck_type','header'=>'Truck Type','value'=>'$data->truck_type'),
                array('name'=>'title','header'=>'Type Of Goods','value'=>'$data->title'),
                array('name' => 'tracking_available', 'header' => 'Tracking Available',
                    'filter' => CHtml::dropDownList('Truckrouteprice[tracking_available]',
                            $_GET['Truckrouteprice']['tracking_available'],
                            array('' => 'All', '1' => 'Yes', '0' => 'No',)
                    ), 'value' => '$data->tracking_available==1?"Yes":"No"'),
		array('name'=>'status','header'=>'select','value' => array($this,'gridSearch'),'type'=>'raw','filter'=>false),		
    /*array('class'=>'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions'=>array('style'=>'min-width:50px;'),
                    'template'=>$this->gridPerm['template'],
                    'buttons'=>array('update'=>array('label'=>$this->gridPerm[buttons][update][label], 'url'=>'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("tid"=>$_GET[tid],"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),'delete'=>array('url'=>' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("tid"=>$_GET[tid],"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
        ),*/
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
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript">
               function initialize() {
                       var input = document.getElementById('Truckrouteprice_source_address');
                       var autocomplete = new google.maps.places.Autocomplete(input);

					   var input1 = document.getElementById('Truckrouteprice_destination_address');
                       var autocomplete = new google.maps.places.Autocomplete(input1);
               }
               google.maps.event.addDomListener(window, 'load', initialize);

$('#Truckrouteprice_price').attr("placeholder", "from,to");
</script>