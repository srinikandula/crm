<link rel='stylesheet' type='text/css' href='http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css'/>
<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Details' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                <?php if($_GET['id']==""){?>
                    <div class="span5">
                        <?php echo $form->textFieldRow($model['t'], 'id_customer', array("onkeydown"=>"fnKeyDown('Truck_id_customer')",'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?>
                    </div>
                    <?php }?>

                        <div class="span5">
                            <?php echo $form->textFieldRow($model['t'], 'truck_reg_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?>
                        </div>

                        <div class="span5">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1 order by title asc')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model['t'], 'id_truck_type', $list); ?>
                        </div>



                        <div class="span5">
                            <label class="control-label required" for="Truck_id_truck_type">
                                Model Year/Month <span class="required">*</span>
                            </label>
                            <div class="controls">
                                <?php echo CHtml::dropdownlist('Truck[make_year]', $model['t']->make_year, Library::getMakeYears(),array('prompt'=>'Year'));
				echo CHtml::dropdownlist('Truck[make_month]', $model['t']->make_month, Library::getMakeMonths(),array('prompt'=>'Month'));?>
                            </div>
                        </div>



                        <div class="span5">
                            <?php echo $form->radioButtonListRow($model['t'], 'tracking_available',
                            array('1' => 'Yes', '0' => 'No'));
                    ?>
                        </div>

                        <div class="span5">
                            <?php echo $form->radioButtonListRow($model['t'], 'insurance_available',
                            array('1' => 'Yes', '0' => 'No'));
                    ?>
                        </div>

                        <div class="span5">
                            <?php echo $form->radioButtonListRow($model['t'], 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?>
                        </div>

                        <div class="span5">
                            <?php echo $form->radioButtonListRow($model['t'], 'approved',
                            array('1' => 'Yes', '0' => 'No'));
                    ?>
                        </div>

                        <div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'fitness_certificate',
                                array('name' => 'fitness_certificate', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img2"><img src="' . Library::getTruckUploadLink() . $model['t']->fitness_certificate . '"><input type="hidden" name="prev_fitness_certificate" value="' . $model['t']->fitness_certificate . '"></div>')
                        );
                        echo '<p class="image-name-display2">' . $model['t']->fitness_certificate . '</p>';?>
                            
                        </div>
                
                <div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model['t'], 'fitness_certificate_expiry_date', array(
                            'options' => array('dateFormat' => 'yy-mm-dd',
                            'altFormat' => 'dd-mm-yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                        ),
                            'htmlOptions' => array(
                            )
                                ), array(
                            'prepend' => '<i class="icon-calendar"></i>'
                                )
                        );
                           ?></div>
                
                
                    <div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'vehicle_insurance',
                                array('name' => 'vehicle_insurance', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img1"><img src="' . Library::getTruckUploadLink() . $model['t']->vehicle_insurance . '"><input type="hidden" name="prev_vehicle_insurance" value="' . $model['t']->vehicle_insurance . '"></div>')
                        );
                        echo '<p class="image-name-display1">' . $model['t']->vehicle_insurance . '</p>';?>
                        </div>
                <div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model['t'], 'vehicle_insurance_expiry_date', array(
                            'options' => array('dateFormat' => 'yy-mm-dd',
                            'altFormat' => 'dd-mm-yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                        ),
                            'htmlOptions' => array(
                            )
                                ), array(
                            'prepend' => '<i class="icon-calendar"></i>'
                                )
                        );
                           ?></div>
                        <div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'vehicle_rc',
                                array('name' => 'vehicle_rc', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img3"><img src="' . Library::getTruckUploadLink() . $model['t']->vehicle_rc . '"><input type="hidden" name="prev_vehicle_rc" value="' . $model['t']->vehicle_rc . '"></div>')
                        );
                        echo '<p class="image-name-display3">' . $model['t']->vehicle_rc . '</p>';?>
                        </div>



						<div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'front_pic',
                                array('name' => 'front_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img4"><img src="' . Library::getTruckUploadLink() . $model['t']->front_pic . '"><input type="hidden" name="prev_front_pic" value="' . $model['t']->front_pic . '"></div>')
                        );
                        echo '<p class="image-name-display4">' . $model['t']->front_pic . '</p>';?>
                        </div>

						<div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'back_pic',
                                array('name' => 'back_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img5"><img src="' . Library::getTruckUploadLink() . $model['t']->back_pic . '"><input type="hidden" name="prev_back_pic" value="' . $model['t']->back_pic . '"></div>')
                        );
                        echo '<p class="image-name-display5">' . $model['t']->back_pic . '</p>';?>
                        </div>

						<div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'right_pic',
                                array('name' => 'right_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img6"><img src="' . Library::getTruckUploadLink() . $model['t']->right_pic . '">'
                                    . '<input type="hidden" name="prev_right_pic" value="' . $model['t']->right_pic . '"></div>')
                        );
                        echo '<p class="image-name-display6">' . $model['t']->right_pic . '</p>';?>
                        </div>

						<div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'left_pic',
                                array('name' => 'left_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img7"><img src="' . Library::getTruckUploadLink() . $model['t']->left_pic . '"><input type="hidden" name="prev_left_pic" value="' . $model['t']->left_pic . '"></div>')
                        );
                        echo '<p class="image-name-display7" ' . $model['t']->left_pic . '</p>';?>
                        </div>

						<div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model['t'], 'top_pic',
                                array('name' => 'top_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img8"><img src="' . Library::getTruckUploadLink() . $model['t']->top_pic . '"><input type="hidden" name="prev_top_pic" value="' . $model['t']->top_pic . '"></div>')
                        );
                        echo '<p class="image-name-display8">' . $model['t']->top_pic . '</p>';?>
                        </div>

                        <div class="span12">

                            <?php
                        //echo $form->labelEx($model[0],'description').'<div class="controls">';
                        $this->widget(
                                'bootstrap.widgets.TbCKEditor',
                                array(
                            'editorOptions' => array('height' => '80px',
							'plugins' => 'basicstyles,toolbar,enterkey,entities,floatingspace,wysiwygarea,indentlist,link,list,dialog,dialogui,button,indent,fakeobjects',
                            ),
                            'model' => '$model[t]',
                            'name' => 'Truck[description]',
                            'id' => 'Truck_description',
                            'value' => $model[t]->description
                                )
                        );
                        echo $form->error($model[t], 'description');?>
                        </div>

            </div>



            <div class="span12 pull-right" id="region_list">

                <?php
                        $box = $this->beginWidget(
                                'bootstrap.widgets.TbBox',
                                array(
                            'title' => 'Add/Edit Images',
                            'htmlOptions' => array('class' => 'portlet-decoration	')
                                )
                        );
                        ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Upload</th>
                                <th>File</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>

                        <?php
                            $row = 0;
                            if($_GET['id']!=''){
                            foreach ($model['f'] as $fileRow):
                                ?>
                            <tbody id='row-<?php echo $row; ?>'>
                                <tr>
                                    <?php echo CHtml::activeHiddenField($fileRow,
                                        'prev_image',
                                        array('name' => 'TruckDoc[upload][' . $row . '][prev_image]',
                                    'value' => $fileRow->file)); ?>
                                        <td>
                                            <div class="span5 uploading-img-main">
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <input type="file" id="TruckDoc[upload][<?php echo $row;?>][image]?>" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip" name="TruckDoc[upload][<?php echo $row;?>][image]" data-original-title="Upload category logo from your computer">
                                                        <div style="display:none" id="TruckDoc[upload][<?php echo $row;?>][image]" class="help-inline error"></div>
                                                        <p class="help-block"></p>


                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                        <td>
                                            <div id="image-name-display-id">
                                                <?php echo $fileRow->file;?><img src="<?php echo Library::getTruckUploadLink().$fileRow->file;?>" width="200px" height="200px">
                                                    <div class="logo-img">
                                                        <img src="<?php echo Library::getTruckUploadLink().$fileRow->file;?>">
                                                    </div>
                                            </div>
                                        </td>

                                        <td><a onclick="$('#row-<?php echo $row; ?>').remove()" class="btn btn-danger"><i class="delete-iconall"></i></a> </td>
                                </tr>
                            </tbody>
                            <?php
                                            $row++;
                                        endforeach;
                                        }
                                        ?>


                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <?php
                                        $this->widget(
                                            'bootstrap.widgets.TbButton',
                                                array(
                                            'label' => 'Add',
                                            'type' => 'btn-info',
                                            'htmlOptions' => array('onclick' => 'addOption()'),
                                                )
                                        );
                                        ?>
                                        </td>
                                    </tr>
                                </tfoot>
                    </table>
                    <?php $this->endWidget(); ?>

            </div>
        </fieldset>
    </div>
</div>

<script type='text/javascript'>
    
    var row_no = <?php echo $row; ?>;

    function addOption() {
        row = '<tbody id="row-' + row_no + '">';
        row += '<tr>';
        row += '<input type="hidden" value="" id="TruckDoc_upload_' + row_no + '_prev_image" name="TruckDoc[upload][' + row_no + '][prev_image]">';
        row += '<td><input id="TruckDoc_upload_' + row_no + '_image" name="TruckDoc[upload][' + row_no + '][image]" type="file">\n\
    <input id="ytproductimage_upload_' + row_no + '_image" type="hidden" name="TruckDoc[upload][' + row_no + '][image]" value=""></td>';
        row += '<td></td>';
        row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('.table tfoot').before(row);
        row_no++;
    }
   

</script>
<script type="text/javascript" src="js/jquery.js"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $(".datepicker").datepicker();
            $(".datepicker1").datepicker();
         });

    </script>

<script>
$(document).ready(function(){
    $(".image-name-display1").mouseover(function(){
   $(".logo-img1").show();
});
$(".image-name-display1").mouseout(function(){
   $(".logo-img1").hide();
});
$(".image-name-display2").mouseover(function(){
   $(".logo-img2").show();
});
$(".image-name-display2").mouseout(function(){
   $(".logo-img2").hide();
});
$(".image-name-display3").mouseover(function(){
   $(".logo-img3").show();
});
$(".image-name-display3").mouseout(function(){
   $(".logo-img3").hide();
});
$(".image-name-display4").mouseover(function(){
   $(".logo-img4").show();
});
$(".image-name-display4").mouseout(function(){
   $(".logo-img4").hide();
});
$(".image-name-display5").mouseover(function(){
   $(".logo-img5").show();
});
$(".image-name-display5").mouseout(function(){
   $(".logo-img5").hide();
});
$(".image-name-display6").mouseover(function(){
   $(".logo-img6").show();
});
$(".image-name-display6").mouseout(function(){
   $(".logo-img6").hide();
});
$(".image-name-display7").mouseover(function(){
   $(".logo-img7").show();
});
$(".image-name-display7").mouseout(function(){
   $(".logo-img7").hide();
});
$(".image-name-display8").mouseover(function(){
   $(".logo-img8").show();
});
$(".image-name-display8").mouseout(function(){
   $(".logo-img8").hide();
});
});
</script>
   