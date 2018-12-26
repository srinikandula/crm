<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'App Notifications' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                <div class="span5" style="margin-left:2.12766%">
                    <?php 
					$list['All']='All';
					$list['All Gps Users']='All Gps Users';
					$rows=Yii::app()->db->createCommand('select distinct(g.username),c.fullname  from eg_customer c,eg_gps_login_device g where (g.username = c.mobile) or (g.username like c.gps_account_id)')->queryAll(); 
					foreach($rows as $row){
						$list[$row['username']]=$row['username']." ".$row['fullname'];
					}
					//echo '<pre>';print_r($list);echo '</pre>';
					echo $form->dropDownListRow($model, 'sent_to', $list,array("multiple"=>true,"disabled"=>$disableField)); ?>
                    <?php //echo $form->textFieldRow($model, 'accountID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'readonly'=>$action == 'create'?false:true));   ?>
                </div>

				<div class="span5">
					<div class="control-group success">
						<label class="control-label" for="Notificationbackend_sent_to">Ignore</label>
						<div class="controls">
							<select multiple="multiple" name="Notificationbackend[ignore][]" id="Notificationbackend_ignore">
								<option value="None">None</option>
								<option value="sriramtransport" selected >SRI RAM TRANSPORT</option>
								<option value="accounts" selected >Accounts</option>
							</select>
						</div>
					</div>
				</div>

				<div class="span12">
                    <?php echo $form->textAreaRow($model, 'info', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

			</div>
        </fieldset>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>