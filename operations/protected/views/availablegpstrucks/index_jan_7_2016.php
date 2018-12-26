<div class="note_box">
    <div class="clr_blue"></div>
    <div class="note">Today Availabe Trucks.</div>
</div>
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
            'rowCssClassExpression' => '( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ). (date("Y-m-d", strtotime($data->date_available)) == date("Y-m-d")?" today":"")',
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'truck_reg_no', 'header' => "Truck Reg No", 'value' => '$data[truck_reg_no]'),
                array('name' => 'address', 'header' => "Address", 'value' => '$data[address]'),
                array('name' => 'date_available', 'header' => "Date Available", 'value' => '$data[date_available]'),
            ),
        ));
        ?>

        <?php $this->endWidget(); ?>
    </div>
</div>