<?php

class Mail {

    public static function send($data = array()) {

        $configDetails = array('%site_name%' => Yii::app()->config->getData('CONFIG_WEBSITE_NAME'),
            '%site_logo%' => Library::getMiscUploadLink()."logo.png",
            '%site_url%' => Yii::app()->params['config']['site_url'],
            '%site_owner%' => Yii::app()->config->getData('CONFIG_WEBSITE_OWNER'),
            '%site_address%' => Yii::app()->config->getData('CONFIG_WEBSITE_ADDRESS'),
            '%site_telephone%' => Yii::app()->config->getData('CONFIG_WEBSITE_TELEPHONE_NUMBER'),
            '%site_support_email%' => Yii::app()->config->getData('CONFIG_WEBSITE_SUPPORT_EMAIL_ADDRESS'),
            '%site_customer_login_url%' => Yii::app()->createAbsoluteUrl('account/login'),
            '%site_customer_register_url%' => Yii::app()->createAbsoluteUrl('account/register'),
        );
		
      
        $from = $reply = array();
        //$mailType = Yii::app()->config->getData('CONFIG_WEBSITE_MAIL_PROTOCOL'); //This is mail type either SMTP ot Normal Mail Type 
		$mailType ="mail";
        $templateId = $data['id'];
        $toEmails = $data['mail']['to'];
        $ccEmails = $data['mail']['cc'];
        $bccEmails = $data['mail']['bcc'];
        $replaceKeys = $data['replace'];
        
		if(isset($templateId)){
		$template = Yii::app()->db->createCommand("select * from {{email_template}} where id_email_template='" . $templateId . "'")->queryRow();
			$description = $template['description'];
			$subject = $template['subject'];
		}else
		{
			$description = $data['description'];
			$subject = $data['subject'];
		}

        foreach ($replaceKeys as $replaceDescKey => $replaceDescValue) {
            $description = str_replace($replaceDescKey, $replaceDescValue, $description);
            $subject = str_replace($replaceDescKey, $replaceDescValue, $subject);
        }

        foreach ($configDetails as $cDesckey => $cDescvalue) {
            $description = str_replace($cDesckey, $cDescvalue, $description);
            $subject = str_replace($cDesckey, $cDescvalue, $subject);
        }
       
        if (isset($data['mail']['from'])) {
            $from['name'] = $data['mail']['from'][key($data['mail']['from'])];
            $from['email'] = key($data['mail']['from']);
        } else {
            $from['name'] = Yii::app()->config->getData('CONFIG_WEBSITE_NAME');
            $from['email'] = Yii::app()->config->getData('CONFIG_WEBSITE_SUPPORT_EMAIL_ADDRESS');
        }

        if (isset($data['mail']['reply'])) {
            $reply['name'] = $data['mail']['reply'][key($data['mail']['reply'])];
            $reply['email'] = key($data['mail']['reply']);
        } else {
            $reply['name'] = Yii::app()->config->getData('CONFIG_WEBSITE_NAME');
            $reply['email'] = Yii::app()->config->getData('CONFIG_WEBSITE_REPLY_EMAIL');
        }
//exit(Yii::app()->params['config']['document_root'].'protected/extensions/phpmailer/JPhpMailer.php');
        /*
         * propeties for mail function
         */
        //Yii::import('application.extensions.phpmailer.JPhpMailer');
        //exit('hree'.Yii::app()->params['config']['document_root'].'protected/extensions/phpmailer/JPhpMailer.php');
		Yii::import('application.extensions.phpmailer.JPhpMailer');
		//require_once '../extensions/phpmailer/JPhpMailer.php';
        //exit('here');
		$mail = new JPhpMailer;
        //$mailType='smtp';
        if ($mailType == 'smtp') {
            $host = Yii::app()->config->getData('CONFIG_WEBSITE_SMTP_HOST') . ':' . Yii::app()->config->getData('CONFIG_WEBSITE_SMTP_PORT'); //'smtp.googlemail.com:465';
            $smtpUserName = Yii::app()->config->getData('CONFIG_WEBSITE_SMTP_USERNAME');
            $smtpPassword = Yii::app()->config->getData('CONFIG_WEBSITE_SMTP_PASSWORD');
            
            $mail->IsSMTP();          // Set mailer to use SMTP
            $mail->Host = $host;         // Specify main and backup server
            $mail->SMTPSecure = "ssl";       // Enable encryption, 'ssl' also accepted
            $mail->SMTPAuth = true;        // Enable SMTP authentication
            $mail->Username = $smtpUserName;      // SMTP username
            $mail->Password = $smtpPassword;      // SMTP password
            if(Yii::app()->config->getData('CONFIG_WEBSITE_SMTP_TIMEOUT')){
            $mail->Timeout =Yii::app()->config->getData('CONFIG_WEBSITE_SMTP_TIMEOUT');      //SMTP Timeout
            }
        }

        //start common for both
        if ($template->html == 1) {
            $mail->IsHTML(true);       // Set email format to HTML
        }
        $mail->Subject = $subject;
        $mail->SetFrom($from['email'], $from['name']);
        foreach ($toEmails as $toEmail => $toName) {
            $mail->AddAddress($toEmail, $toName); // Add a recipient
        }

        if (isset($ccEmails) && sizeof($ccEmails) > 0) {
            foreach ($ccEmails as $ccEmail => $ccName) {
                $mail->AddCC($ccEmail, $ccName);
            }
        }
        if (isset($bccEmails) && sizeof($bccEmails) > 0) {
            foreach ($bccEmails as $bccEmail => $bccName) {
                $mail->AddBCC($bccEmail, $bccName);
            }
        }
        
        if($reply['email']!='')
        {
            $mail->AddReplyTo($reply['email'], $reply['name']);
        }
        
        $mail->MsgHTML($description);
        //end common for both

        if (!$mail->Send()) {
            $return = array('status' => '0', 'error' => $mail->ErrorInfo); //failure
        } else {
            $return = array('status' => '1', 'error' => $mail->ErrorInfo); //success
        }

		//echo Yii::app()->config->getData('CONFIG_WEBSITE_NAME').'<pre>';print_r($from);print_r($data);print_r($return);exit;
        return $return;
    }
}
