<div class="tab-pane active"  id="Information">
	<div class="span12">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Details' ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'title', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                     <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?></div>
                </div>
            </fieldset>
        </div>  
</div>