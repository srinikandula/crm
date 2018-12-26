<?php
return
array(
        'elements' => array(
	'store'=>array('type'=>'form',
        'title'=>
        '<div class="span4 pull-left">
            <div class="span12">
                <fieldset class="portlet " >
                    <div class="portlet-decoration">
                        <div class="span11">Details </div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" id="hide_box_btn1" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line1">',
			'elements'=>array(
                            'CONFIG_STORE_NAME' => array(
                                        'type' => 'text',
					'maxlength' => '80',
                                        'data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="name"',
					),
				'CONFIG_STORE_OWNER' => array(
					'type' => 'email','hint' => 'If you want a reply, you must...','maxlength' => '80'
					),
                            	),
			),'</div>
                </fieldset>
            </div>   
</div>',
        'settings'=>array('type'=>'form','title'=>'<div class="span4 pull-left">
            <div class="span12">
                <fieldset class="portlet " >
                    <div class="portlet-decoration">
                        <div class="span11">Details </div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" id="hide_box_btn1" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line1">',
			'elements'=>array(
                            'CONFIG_STORE_NAME' => array(
                                        'type' => 'text',
					'maxlength' => '80',
                                        'data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="name"',
					),
				'CONFIG_STORE_OWNER' => array(
					'type' => 'email','hint' => 'If you want a reply, you must...','maxlength' => '80'
					),
                            	),
			),'</div>
                </fieldset>
            </div>   
</div>',
    ),
);

?>