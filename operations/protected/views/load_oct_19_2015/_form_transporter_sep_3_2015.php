<?php //echo Yii::app()->controller->id." and ".Yii::app()->controller->action->id."<br/>";exit;
$action = Yii::app()->controller->action->id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
                <?php /*
        if($_SESSION['id_admin_role']==8){
        echo $form->textFieldRow(
                                    $model['ltr'], 'title', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'value'=>$records['customer'][0]->idprefix.",".$records['customer'][0]->fullname.",".$records['customer'][0]->type.",".$records['customer'][0]->mobile.",".$records['customer'][0]->email)
                            );}*/?>
        <div class="span<?php echo $_SESSION['id_admin_role']==10?'6':'12';?>"  >
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
                        <?php if ($_SESSION['id_admin_role'] != 8) { ?>
                        <div class="span5">
                            <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'title', array('onkeydown' => 'fnKeyDown("Loadtruckrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?>
                        </div>
                        <?php }else{
                            if ($action == 'create') {
                            echo $form->textFieldRow(
                                    $model['ltr'], 'title', array('readonly'=>true,'onkeydown' => 'fnKeyDown("Loadtruckrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'value'=>$records['customer'][0]->idprefix.",".$records['customer'][0]->fullname.",".$records['customer'][0]->type.",".$records['customer'][0]->mobile.",".$records['customer'][0]->email)
                            );}
                            ?>
                        <?php }?>
                        <?php if ($action != 'create') { ?>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'destination_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">   <?php
                        $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span5">   <?php
                        $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                        ?> </div>


                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'tracking', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                        <div class="span5">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'date_required', array('class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );?></div>
                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'insurance', array('1' => 'Yes', '0' => 'No'));
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'status', Library::getLTRStatuses());
                            ?></div>

    <?php if (!$model['ltr']->approved) { ?>
                            <div class="span5">  <?php
        echo $form->radioButtonListRow($model['ltr'], 'approved', array('1' => 'Yes', '0' => 'No'));
        ?></div>
    <?php }
} ?></div>
            </fieldset>
        </div>

<?php if ($action != 'create') { echo $this->renderPartial('_form_comments_block', array('form'=>$form,'model'=>$model),true);}?>
<?php //if ($action != 'create') { echo $this->renderPartial('_form_search_block', array('form'=>$form,'model'=>$model),true);}?>
<?php if ($action != 'create') { echo $this->renderPartial('_form_quotes_block', array('form'=>$form,'model'=>$model),true);}?>
        <?php /*if ($action != 'create') {
            Yii::app()->runController('load/searchResults'); 
            }*/?>
<?php if ($action == 'create') {echo $this->renderPartial('_form_truck_block', array('form'=>$form,'model'=>$model),true);}?>
    </div></div>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
    <script type='text/javascript'>
        var input = document.getElementById('Loadtruckrequest_source_address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        var input1 = document.getElementById('Loadtruckrequest_destination_address');
        var autocomplete = new google.maps.places.Autocomplete(input1);
        
        var input = document.getElementById('search_source_address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        var input1 = document.getElementById('search_destination_address');
        var autocomplete = new google.maps.places.Autocomplete(input1);
        </script>

<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$('.datetimepicker').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
                        format: 'Y-m-d H:i',
                        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                    });
</script>