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
                array('name' => 'source_address', 'header' => "Source Address", 'value' => '$data[source_address]'),
                array('name' => 'destination_address', 'header' => "Destination Address", 'value' => '$data[destination_address]'),
                array('name' => 'system_min_price', 'header' => "Min Price", 'value' => '$data[system_min_price]','filter'=>CHtml::dropDownList('Loadtruckrequest[id_truck_type]', $_GET['Loadtruckrequest']['id_truck_type'],  
				array('10'=>'Tonnes<10','11'=>'Tonnes>10')
				)),
                array('name' => 'system_avg_price', 'header' => "Avg Price", 'value' => '$data[system_avg_price]','filter'=>false),
                //array('name' => 'min_price', 'header' => "Min Price", 'value' => '$data[min_price]'),
                //array('name' => 'avg_price', 'header' => "Avg Price", 'value' => '$data[avg_price]'),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>
    </div>
</div>