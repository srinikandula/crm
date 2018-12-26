<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet" >
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11"><?php echo 'Country Details' ?></div>
                <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1" >
                <div class="span5">  <?php echo $form->textFieldRow($model, 'truck_reg_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                <div class="span5">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1 order by title asc')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model, 'id_truck_type', $list); ?>
                        </div>
                
                <div class="span5">  <?php echo $form->textFieldRow($model, 'contact_name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                
                <div class="span5">  <?php echo $form->textFieldRow($model, 'contact_mobile', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                
                
                <div class="span5">
                        <?php 
			echo $form->dropDownListRow($model, 'truck_reg_state', Library::getStates()); ?>
                        </div>
                

                
                <div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model, 'fitness_exp_date', array(
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
                
                                <div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model, 'insurance_exp_date', array(
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
                
                <div class="span5">
                            <?php 
                            //echo "value o f ".date('Y');exit;
                            $cYear=date('Y');
                            $pYear=$cYear-15;
                            for($i=$cYear;$i>=$pYear;$i--){
                                $ymfg[$i]=$i;
                            }
                            //echo '<pre>';print_r($ymfg);exit;
			echo $form->dropDownListRow($model, 'year_of_mfg', $ymfg); ?>
                        </div>
                <div class="span5">  <?php echo $form->textFieldRow($model, 'odometer', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                
                <div class="span5" > 
                    <?php
                    echo $form->radioButtonListRow($model, 'any_accidents', array('1' => 'Yes', '0' => 'No'));
                    ?></div>
                <div class="span5" > 
                    <?php
                    echo $form->radioButtonListRow($model, 'in_finance', array('1' => 'Yes', '0' => 'Yes'));
                    ?></div>
                
                <div class="span5" > 
                    <?php
                    echo $form->radioButtonListRow($model, 'isactive', array('1' => 'Active', '0' => 'Inactive','2' => 'Sold'));
                    ?></div>
                
                <div class="span5">  <?php echo $form->textFieldRow($model, 'expected_price', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                
                
                <div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model, 'truck_front_pic',
                                array('name' => 'truck_front_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img1"><img src="' . Library::getTruckSellUploadLink() . $model->truck_front_pic . '"><input type="hidden" name="prev_truck_front_pic" value="' . $model->truck_front_pic . '"></div>')
                        );
                        echo '<p class="image-name-display1">' . $model->truck_front_pic . '</p>';?>
                </div>
                <div class="span5 uploading-img-main">
                            <?php
                        echo $form->fileFieldRow(
                                $model, 'truck_back_pic',
                                array('name' => 'truck_back_pic', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img2"><img src="' . Library::getTruckSellUploadLink() . $model->truck_back_pic . '"><input type="hidden" name="prev_truck_back_pic" value="' . $model->truck_back_pic . '"></div>')
                        );
                        echo '<p class="image-name-display2">' . $model->truck_back_pic . '</p>';?>
                </div>
                
                <div class="span5 uploading-img-main">
                            <?php
                        $field='tyres_front_left_pic';    
                        echo $form->fileFieldRow(
                                $model, $field,
                                array('name' => $field, 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img3"><img src="' . Library::getTruckSellUploadLink() . $model->$field . '"><input type="hidden" name="prev_'.$field.'" value="' . $model->$field . '"></div>')
                        );
                        echo '<p class="image-name-display3">' . $model->$field . '</p>';?>
                </div>
                
                <div class="span5 uploading-img-main">
                            <?php
                        $field='tyres_front_right_pic';
                        $cprefix=4;
                        echo $form->fileFieldRow(
                                $model, $field,
                                array('name' => $field, 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img'.$cprefix.'"><img src="' . Library::getTruckSellUploadLink() . $model->$field . '"><input type="hidden" name="prev_'.$field.'" value="' . $model->$field . '"></div>')
                        );
                        echo '<p class="image-name-display'.$cprefix.'">' . $model->$field . '</p>';?>
                </div>
                
                <div class="span5 uploading-img-main">
                            <?php
                        $field='tyres_back_left_pic';
                        $cprefix=5;
                        echo $form->fileFieldRow(
                                $model, $field,
                                array('name' => $field, 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img'.$cprefix.'"><img src="' . Library::getTruckSellUploadLink() . $model->$field . '"><input type="hidden" name="prev_'.$field.'" value="' . $model->$field . '"></div>')
                        );
                        echo '<p class="image-name-display'.$cprefix.'">' . $model->$field . '</p>';?>
                </div>
                
                <div class="span5 uploading-img-main">
                            <?php
                        $field='tyres_back_right_pic';
                        $cprefix=6;
                        echo $form->fileFieldRow(
                                $model, $field,
                                array('name' => $field, 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img'.$cprefix.'"><img src="' . Library::getTruckSellUploadLink() . $model->$field . '"><input type="hidden" name="prev_'.$field.'" value="' . $model->$field . '"></div>')
                        );
                        echo '<p class="image-name-display'.$cprefix.'">' . $model->$field . '</p>';?>
                </div>
                
                    <div class="span5 uploading-img-main">
                            <?php
                        $field='other_pic_1';
                        $cprefix=7;
                        echo $form->fileFieldRow(
                                $model, $field,
                                array('name' => $field, 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img'.$cprefix.'"><img src="' . Library::getTruckSellUploadLink() . $model->$field . '"><input type="hidden" name="prev_'.$field.'" value="' . $model->$field . '"></div>')
                        );
                        echo '<p class="image-name-display'.$cprefix.'">' . $model->$field . '</p>';?>
                </div>
                
                    <div class="span5 uploading-img-main">
                            <?php
                        $field='other_pic_2';
                        $cprefix=8;
                        echo $form->fileFieldRow(
                                $model, $field,
                                array('name' => $field, 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img'.$cprefix.'"><img src="' . Library::getTruckSellUploadLink() . $model->$field . '"><input type="hidden" name="prev_'.$field.'" value="' . $model->$field . '"></div>')
                        );
                        echo '<p class="image-name-display'.$cprefix.'">' . $model->$field . '</p>';?>
                </div>
                
                
                <div class="span5">  Date Created:                 <?php echo $model->date_created; ?> </div>
                <div class="span5">  Date Modified:                 <?php echo $model->date_modified; ?> </div>


            </div>
        </fieldset>
    </div>  
</div>

<script type="text/javascript" src="js/jquery.js"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $(".datepicker").datepicker();
         });

    </script>
<script>

$(document).ready(function(){
/*var j=8;
var i=1;
for(i;i<=j;i++){
    alert(i);
    $(".image-name-display"+i).mouseover(function(){
       $(".logo-img"+i).show();
    });
    $(".image-name-display"+i).mouseout(function(){
       $(".logo-img"+i).hide();
    });
}*/
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
    
        

});</script>