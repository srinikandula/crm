<div class="tab-pane active"  id="Information">
	<div class="span12">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Details' ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'source_city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'destination_city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'no_of_tollgates', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'amount', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                </div>
            </fieldset>
        </div>  
</div>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type='text/javascript'>
                                    var input = document.getElementById('Tollgateinfo_source_city');
                                    var autocomplete = new google.maps.places.Autocomplete(input);

                                    var input1 = document.getElementById('Tollgateinfo_destination_city');
                                    var autocomplete = new google.maps.places.Autocomplete(input1);

</script>