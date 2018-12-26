<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details"> 
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'gridForm',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            //'type'=>'striped',
            'template' => "{summary}{pager}<div class='items_main_div span12 page'>{items}</div>",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            //'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataProvider,
            'filter' => $model,
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'first_name', 'header' => "First Name", 'value' => array($this,'grid'),'type'=>'raw'),
                array('name' => 'last_name', 'header' => "Last Name", 'value' => '$data->last_name'),
                array('name' => 'start_date', 'header' => "Start Date", 'value' => '$data->start_date'),
                array('name' => 'end_date', 'header' => "End Date", 'value' => '$data->end_date'),
                array('name' => 'ip_address', 'header' => "Ip Address", 'value' => '$data->ip_address'),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>
    </div>
</div>
<?php Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
<script type="text/javascript">
jQuery('#AdminLogHistory_start_date').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
jQuery('#AdminLogHistory_end_date').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});

/*$(".items tbody tr").live("click", function(){
    window.location.target="_blank";
    window.location.href = $(this).find(".grid_link").attr("href");
});*/

</script>