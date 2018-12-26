<div class="row-fluid">
    <div class="tab-pane active" id="Information">
        <div class="span1['pd']">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                    <div class="span11">General </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                <div class="portlet-content design_main_form" id="hide_box_line5">
                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_NAME', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_OWNER', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_OWNER_EMAIL_ADDRESS', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_STATE', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_SUPPORT_EMAIL_ADDRESS', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_REPLY_EMAIL', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_TELEPHONE_NUMBER', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textAreaRow($model, 'CONFIG_WEBSITE_ADDRESS', array( )
                        );
                        ?></div>

                    


                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_ITEMS_PER_PAGE', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>


                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_SEARCH_DAYS', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>
					
					<div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_GLOBAL_COMMISSION', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>
					<div class="span5">  <?php
                        echo $form->radioButtonListRow($model, 'CONFIG_WEBSITE_GLOBAL_COMMISSION_TYPE', array(
                            'P' => 'Percent',
                            'F' => 'Fixed',
                                )
                        );
                        ?></div>

<div class="span5">  <?php
                        echo $form->textFieldRow($model, 'CONFIG_WEBSITE_META_TITLE', array(
                            'rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",
                                )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textAreaRow($model, 'CONFIG_WEBSITE_META_KEYWORDS', array(  )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textAreaRow($model, 'CONFIG_WEBSITE_META_DESCRIPTION', array(  )
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textAreaRow($model, 'CONFIG_WEBSITE_ALLOWED_FILE_TYPES', array(  )
                        );
                        ?></div>

                </div>
            </fieldset>
        </div>  
    </div>
</div>