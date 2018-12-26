<div class="span12" style="margin-left:0px">
    <fieldset class="portlet ">
        <div class="portlet-decoration" onclick=" $('#hide_box_line7').slideToggle();">
            <div class="span12">Route Expenses </div>
            <div class="clearfix"></div>	
        </div>
        <div style="overflow: auto;">
            <div class="portlet-content" id="hide_box_line7" style="display:none">
                <div class="span4"><?php
                    echo $form->textFieldRow(
                        $model['0'], 'expenses_diesel', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
                ?></div>
                
                <div class="span4"><?php
                    echo $form->textFieldRow(
                        $model['0'], 'expenses_tollgate', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
                ?></div>
                
                <div class="span4"><?php
                    echo $form->textFieldRow(
                        $model['0'], 'expenses_loading_unloading', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
                ?></div>
                
                <div class="span4"><?php
                    echo $form->textFieldRow(
                        $model['0'], 'expenses_police_charges', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
                ?></div>

            </div>  
        </div>
    </fieldset>
</div>