    <?php    $this->widget('ext.Flashmessage.Flashmessage'); ?>
    
    <?php $this->renderPartial('_search',array('block'=>$block,'model'=>$model,'form'=>$form)); ?>
<div class="span12 top_box_fixed">
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
            <?php Library::addButton(array('label'=>'Create','url'=> $this->createUrl('create')));  ?>
            <?php Library::buttonBulk(array('label'=>'Delete','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/delete")));?>
            </div>
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-left">
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
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
            'dataProvider' => $dataSet,
            //'filter' => $model,
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                //array('name' => 'title', 'header' => 'ids', 'value' => '$data->title'),
                array('name' => 'date_created_from', 'header' => 'Date Start', 'value' => '$data->date_created_from'),
                array('name' => 'date_created_to', 'header' => 'Date End', 'value' => '$data->date_created_to'),
                array('name' => 'id_load_truck_request', 'header' => 'No Of Requests', 'value' => array($this,'grid'),'type'=>'raw'),
            ),
        ));
        ?>
        

    </div>
    <?php //$this->renderPartial('_preview_block'); ?>
    <?php if($_GET['data']!="" ){ ?> <div class="span12 top_box_margin" id="request_design"> <?php Yii::app()->runController('truckrequestreport/update/1');?></div>
   <?php } else { ?> <div class="preview"> <?php echo 'Preview'; ?></div> <?php }?>
</div>
<div class="clearfix"></div>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
<script type="text/javascript">
jQuery('#date_created_from').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
jQuery('#date_created_to').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
</script>
<!-- <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Library::getGoogleMapsKey();?>&libraries=places"></script>
<script type="text/javascript">
function initialize() {
    var input = document.getElementById('source_address');
    var autocomplete = new google.maps.places.Autocomplete(input);

    var input1 = document.getElementById('destination_address');
    var autocomplete = new google.maps.places.Autocomplete(input1);
}
google.maps.event.addDomListener(window, 'load', initialize);

</script>