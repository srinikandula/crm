<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12 ">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
                
                <div class="span5">  <div class="control-group"><label for="Truckrouteprice_source_city" class="control-label required">Source City <span class="required">*</span></label><div class="controls">
         <?php 
  $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
      'attribute'=>'source_city',
        
        'model'=>$model['trp'],
        'sourceUrl'=>array('cities/AutocompleteCity/type/source'),
        'name'=>'Truckrouteprice[source_city]',
        
      'options'=>array(
          'minLength'=>'2',
        ),
        'htmlOptions'=>array(
          'value'=>$model['trp']->source_city.' - '.$model['trp']->source_state,
            'size'=>45,
          'maxlength'=>45,
        ),
  )); ?>   
                                </div></div></div>
        
    <div class="span5">  <div class="control-group"><label for="Truckrouteprice_destination_city" class="control-label required">Destination City <span class="required">*</span></label><div class="controls">
         <?php 
  $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
      'attribute'=>'destination_city',
        //'value'=>$model['trp']->source_city.' - '.$model['trp']->source_state,
        'model'=>$model['trp'],
        'sourceUrl'=>array('cities/AutocompleteCity/type/destination'),
        'name'=>'Truckrouteprice[destination_city]',
        'options'=>array(
          'minLength'=>'2',
        ),
        'htmlOptions'=>array(
          'value'=>$model['trp']->destination_city.' - '.$model['trp']->destination_state,
            'size'=>45,
          'maxlength'=>45,
        ),
  )); ?>   
                                </div></div></div>
                    
                    
                    <!--<div class="span5">  <?php
                        /*echo $form->textFieldRow(
                                $model['trp'], 'source_state', array('value' => $model['trp']->destination_city . " - " . $model['trp']->destination_state, 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );*/
                        ?></div>-->

                    <div class="span5">   <?php $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                        echo $form->dropDownListRow($model['trp'], 'id_goods_type', $list);
                        ?> </div>

                    <div class="span5">   <?php $list = CHtml::listData(Loadtype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_load_type', 'title');
                        echo $form->dropDownListRow($model['trp'], 'id_load_type', $list);
                        ?> </div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['trp'], 'price', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->radioButtonListRow($model['trp'], 'status', array('1' => 'Enable', '0' => 'Disable'));
                        ?></div>
                </div>
            </fieldset>
        </div>
    </div>
</div>