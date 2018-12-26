<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 

<div><b>#Ord<?php 
$arr=explode("-",substr($model['0']->date_ordered,0,10));
$ord=$arr[0].$arr[1].$arr[2].$model['0']->id_order;
echo $ord;
//echo $_GET['id'];?></b></div>
<div class="row-fluid">
<div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line3').slideToggle();">
                    <div class="span11">Details</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line3">

                    <div class="span8" style="margin-left:45px">  <?php
                        echo $form->textFieldRow(
                                $model['0'], 'truck_source_start_date_time', array('class' => 'datetimepicker','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span8" style="margin-left:45px">  <?php
                        echo $form->textFieldRow(
                                $model['0'], 'truck_destination_reach_date_time', array('class' => 'datetimepicker','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    
                    <div class="span8" style="margin-left:45px">  <?php
                        echo $form->textFieldRow(
                                $model['0'], 'truck_route_run_time', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
               </div>
            
            </fieldset>
            </div>
        <?php
        echo $this->renderPartial('_location', array('form' => $form, 'model' => $model), true);
        ?>

        
</div>

    <!-- <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> -->
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Library::getGoogleMapsKey();?>&libraries=places"></script>
    <script type='text/javascript'>
        var input = document.getElementById('Ordertruckroutehistory_location_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
    </script>

