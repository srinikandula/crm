<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6"  id="user_list_table" style="width:49%;">
            <fieldset class="portlet " >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line4').slideToggle();">
                    <div class="span11">Interested Customers</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                    <div class="clearfix"></div>	
                </div>
                
                <div class="portlet-content" id="hide_box_line4">
                    <table class="table uploading-status" border='0' id="table_user">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Expected Price</th>
                                </tr>
                        </thead>
                        <tbody id="user">
                          <?php  //$aa = Gpsalertsinterested::model()->findAll(array('condition' => 'id_gps_alert='. (int) $_GET['id'] )); ?>
                            <?php //echo '<pre>';print_r($aa);exit; ?>
                              <?php $rows=Yii::app()->db->createCommand("select gai.account_id,gai.expected_price,c.fullname,c.mobile from eg_gps_alerts_interested gai,eg_customer c where (gai.account_id = c.gps_account_id or gai.account_id = c.mobile) and (gai.id_gps_alert = '".(int) $_GET['id']."')")->queryAll();
                                foreach ($rows as $user):
                              ?>
                                <tr id="row_user_<?php echo $user[id_gps_alerts_interested]; ?>">
                                    <td><?php echo $user[fullname]?>,<?php echo $user[mobile]; ?></td>
                                    <td><?php echo $user[expected_price]; ?></td>
                                    </tr>
                            <?php endforeach; ?>    
                        </tbody>
                    </table> 
                </div>
            </fieldset>        
    </div>
    </div>
    </div>



