    <div class="row-fluid">
        <div class="tab-pane active" id="Personal Details">
            <div class="span6">
                <fieldset class="portlet" >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                        <div class="span11">Filter </div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="portlet-content" id="hide_box_line1">



                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('value'=>$model['search']['source_address'],'name' => 'search[source_address]', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'destination_address', array('value'=>$model['search']['destination_address'],'name' => 'search[destination_address]','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">   <?php
                            $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                            echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list,array('value'=>$model['search']['id_goods_type'],'name' => 'search[id_goods_type]'));
                            ?> </div>

                        <div class="span5">   <?php
                            $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                            echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list,array('value'=>$model['search']['id_truck_type'],'name' => 'search[id_truck_type]'));
                            ?> </div>


                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'tracking', array('1' => 'Yes', '0' => 'No'),array('value'=>$model['search']['tracking'],'name' => 'search[tracking]'));
                            ?></div>
                        <!--<div class="span5">   <?php
                            /*echo $form->textFieldRow(
                                    $model['ltr'], 'date_required', array('name' => 'search[date_required]','class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );*/
                            ?></div>-->
                        <!--<div class="span5">  <?php
                           // echo $form->dropDownListRow($model['ltr'], 'insurance', array('1' => 'Yes', '0' => 'No'),array('value'=>$model['search']['insurance'],'name' => 'search[insurance]'));
                            ?></div>-->

                        <div class="span5">
                            <?php Library::saveButton(array('label'=>'Search','permission'=>$this->editPerm)); ?>
                        <!--<input type="button" name="search" value="search" id="yw2">-->
                        </div>        


                    </div>
                </fieldset>
            </div>
        </div>
    </div>