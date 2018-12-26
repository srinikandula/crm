<div class="tab-pane active" id="Information">
    <div class="span6">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Details' ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                   <div style="height:220px;"> <div class="span5">  <?php echo $form->textAreaRow($model, 'details', array('rows'=>5,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div></div>

                    <div class="span5" >
                    <?php
                    $model->status=0;
                    echo $form->radioButtonListRow($model, 'status',
                            array( '0' => 'Open','1' => 'Closed'));
                    ?></div>
                </div>
            </fieldset>

        </div>

       <?php //echo '<pre>'; print_r($rows); echo '</pre>';?><div class="span6">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Messages</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <div style="height:300px;width:108%;overflow:auto">
                    <?php

                    foreach($rows['p'] as $row){
                    $status=$row['status']==1?"Closed":"Open";
                    $exp=explode("_",$row['customer']);
                    $customer=$exp[0];
                    $type=Library::getCustomerType(trim($exp[1]));
                     if($row['id_admin']==0){ //by customer?>
                    <div class="admin" style="float:right;position:relative;">
                      <div class="message_date" style="float:right;">
                        <small><em><?php echo $row['date_created'];?></em></small>
                      </div>
                      <div class="left" style="float:right;margin-top:25px">
                      <label><?php echo $row['details'];?>Admin jalsfd dkfja fe sfsrgs gsgs gsrgrgdrg grgr grsgsrg gsrgs gsrgr grgrsg sgs gsr  </label>
                      </div>
                      <div class="right"  style="float:right;margin-top:10px">
                        <em>- <?php

                      echo $customer.",".$type.",".$row['type_of_issue'].",".$status;?><em>
                      </div>
                      </div>
                      <hr style="width:75%;border-top:1px solid darkgray;margin-top:20px;float:right">
                    <?php }else { //by admin?>
                     <div class="customer" style="float:left;position:relative;border-radius:10px">
                      <div class="message_date">
                        <small><em><?php echo $row['date_created'];?></em></small>
                      </div>
                      <div class="left" style="float:right;margin-top:15px;">
                      <label><?php echo $row['details'];?>customer jalsdkfja lskfj aslkfj alskdf ldskfj lksd fljerwpjrpwja sflkajs flaksdjf laksjfd laksdj flaks f aslkfdj asjlkdf jsdlkf ksl fj fsif fsfs fsifsiofjfs fsojfsf sfsfs fsfsf sfsf sfsf fsfsf ss </label>
                      </div>
                      <div class="right" style="float:left;margin-top:10px">
                        <em>- <?php echo $row['admin'].",".$status;?><em>
                      </div>

                     </div>
                        <hr style="width:75%;border-top:1px solid darkgray;float:left">
                    <?php }}?>

                </div>
            </fieldset>

        </div>


        <div class="span6" style="margin-left:-3px;">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Other Messages' ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                        <div class="portlet-content" id="hide_box_line1" >
                    <div style="height:300px;width:108%;overflow:auto">
                    <?php

                    foreach($rows['c'] as $row){
                    $status=$row['status']==1?"Closed":"Open";
                    $exp=explode("_",$row['customer']);
                    $customer=$exp[0];
                    $type=Library::getCustomerType(trim($exp[1]));
                     if($row['id_admin']==0){ //by customer?>
                    <div class="admin" style="float:right;position:relative;">
                      <div class="message_date" style="float:right;">
                        <small><em><?php echo $row['date_created'];?></em></small>
                      </div>
                      <div class="left" style="float:right;margin-top:25px">
                      <label><?php echo $row['details'];?></label>
                      </div>
                      <div class="right"  style="float:right;margin-top:10px">
                        <em>- <?php

                      echo $customer.",".$type.",".$row['type_of_issue'].",".$status;?><em>
                      </div>
                      </div>
                      <hr style="width:75%;border-top:1px solid darkgray;margin-top:20px;float:right">
                    <?php }else { //by admin?>
                     <div class="customer" style="float:left;position:relative;border-radius:10px">
                      <div class="message_date">
                        <small><em><?php echo $row['date_created'];?></em></small>
                      </div>
                      <div class="left" style="float:right;margin-top:15px;">
                      <label><?php echo $row['details'];?></label>
                      </div>
                      <div class="right" style="float:left;margin-top:10px">
                        <em>- <?php echo $row['admin'].",".$status;?><em>
                      </div>
                     </div>
                    <hr style="width:75%;border-top:1px solid darkgray;float:left">
                    <?php }}?>

                </div>
            </fieldset>


        </div>


</div>
