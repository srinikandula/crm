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
                array('name' => 'idprefix', 'header' => "Id", 'value' => '$data[idprefix]'),
                array('name' => 'fullname', 'header' => "Name", 'value' => '$data[fullname]'),
                array('name' => 'mobile', 'header' => "Mobile", 'value' => '$data[mobile]'),
                array('name' => 'address', 'header' => "Address", 'value' => '$data[address]'),
                array('name' => 'rating', 'header' => "Rating", 'value' => array($this,'grid'),'type'=>'raw'),
                //array('name' => 'min_price', 'header' => "Min Price", 'value' => '$data[min_price]'),
                //array('name' => 'avg_price', 'header' => "Avg Price", 'value' => '$data[avg_price]'),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>
    </div>
</div>