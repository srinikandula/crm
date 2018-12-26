<?php

class WebsiteController extends Controller {

    public function actionIndex() {
        
        $model = new WebsiteForm();
        //exit("here");
        if (Yii::app()->request->isPostRequest) {
            $model->unSetAttributes();

            foreach ($_POST['WebsiteForm'] as $key => $config):
                $model->$key = $config;
            endforeach;

            if ($model->validate()) {
                foreach ($_POST['WebsiteForm'] as $key => $config):
                    Configuration::model()->updateAll(array('value' => $config), "`key`='" . $key . "'");
                endforeach;

                ConfigurationGroup::model()->updateAll(array('date_modified' => new CDbExpression('NOW()')), "`type`='CONFIG' and `code`='CONFIG'");

                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                $this->redirect('website');
            }
        }
        
        $this->render('index', array('model' => $model));
    }

}
