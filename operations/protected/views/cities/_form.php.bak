<div class="tab-pane active" id="Information">
	<div class="span12">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo Yii::t('countries','heading_sub_title') ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                    
                    <div class="span5">   <?php $list = CHtml::listData(Zone::getZones(), 'id_zone', 'name'); 
					echo $form->dropDownListRow($model, 'id_zone', $list); ?> </div>
                    
                    <div class="span5">  <?php echo $form->textFieldRow( $model, 'call_prefix',  array('rel' => 'tooltip','data-toggle' => "tooltip", 'data-placement' => "right",), array('hint' => 'An international call prefix or dial out code') );
                        ?> </div>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model, 'iso_code_2',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>
                    <div class="span5"> <?php
                        echo $form->textFieldRow(
                                $model, 'iso_code_3',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>
                    <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?></div>
                    <div class="span11">  <?php
                    echo $form->textAreaRow($model, 'address_format', array('rows' => 5),
                            array('hint' => 'Note : firstname,lastname,company,address_1,address_2,city,postcode,state,country')
                    );
                    ?> </div>
                </div>
            </fieldset>
        </div>  
</div>