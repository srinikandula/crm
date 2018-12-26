<?php

class WebsiteForm extends CActiveRecord //CFormModel
{
	private $_dynamicData=array();
        private $_dynamicFields = array();
        
        public function rules() 
        {
            return array(                    
                    array(implode(",",array_keys($this->_dynamicFields)), 'required'),
                    array(implode(",",array_keys($this->_dynamicFields)), 'safe'),
            );
        }
        
        public function tableName()
	{
		return '{{configuration}}';
	}
        
        public function attributeNames() 
        {
                return array_merge(
                        parent::attributeNames(),
                        array_keys($this->_dynamicFields)
                );
        }
		public function attributeLabels()
		{
	

            return array(			
            'CONFIG_WEBSITE_DEFAULT_TIME_ZONE' 			=> Yii::t('website','entry_CONFIG_WEBSITE_DEFAULT_TIME_ZONE'),
			'CONFIG_WEBSITE_COPYRIGHTS'					=> Yii::t('website','entry_CONFIG_WEBSITE_COPYRIGHTS'),
			'CONFIG_WEBSITE_DEFAULT_WEIGHT'				=> Yii::t('website','entry_CONFIG_WEBSITE_DEFAULT_WEIGHT'),
			'CONFIG_WEBSITE_DEFAULT_LENGTH'				=> Yii::t('website','entry_CONFIG_WEBSITE_DEFAULT_LENGTH'),
			'CONFIG_WEBSITE_ADDTOCART_REDIRECT'			=> Yii::t('website','entry_CONFIG_WEBSITE_ADDTOCART_REDIRECT'),
			'CONFIG_WEBSITE_DEFAULT_CUSTOMER_GROUP'		=> Yii::t('website','entry_CONFIG_WEBSITE_DEFAULT_CUSTOMER_GROUP'),
			'CONFIG_WEBSITE_COMPLETE_ORDER_STATUS'		=> Yii::t('website','entry_CONFIG_WEBSITE_COMPLETE_ORDER_STATUS'),
			'CONFIG_WEBSITE_GOOGLE_ANALYTICS'			=> Yii::t('website','entry_CONFIG_WEBSITE_GOOGLE_ANALYTICS'),
			'CONFIG_WEBSITE_CACHE_LIFE_TIME'			=> Yii::t('website','entry_CONFIG_WEBSITE_CACHE_LIFE_TIME'),
			'CONFIG_WEBSITE_MAINTENANCE_MODE'			=> Yii::t('website','entry_CONFIG_WEBSITE_MAINTENANCE_MODE'),
			'CONFIG_WEBSITE_MAINTENANCE_MESSAGE'		=> Yii::t('website','entry_CONFIG_WEBSITE_MAINTENANCE_MESSAGE'),
			'CONFIG_WEBSITE_META_TITLE'					=> Yii::t('website','entry_CONFIG_WEBSITE_META_TITLE'),
			'CONFIG_WEBSITE_META_KEYWORDS'				=> Yii::t('website','entry_CONFIG_WEBSITE_META_KEYWORDS'),
			'CONFIG_WEBSITE_META_DESCRIPTION'			=> Yii::t('website','entry_CONFIG_WEBSITE_META_DESCRIPTION'),
			'CONFIG_WEBSITE_PRODUCT_NAME_LIMIT'			=> Yii::t('website','entry_CONFIG_WEBSITE_PRODUCT_NAME_LIMIT'),
			'CONFIG_WEBSITE_PRODUCT_LISTING_LABELS'		=> Yii::t('website','entry_CONFIG_WEBSITE_PRODUCT_LISTING_LABELS'),
			'CONFIG_WEBSITE_ITEMS_PER_PAGE'				=> Yii::t('website','entry_CONFIG_WEBSITE_ITEMS_PER_PAGE'),
			'CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'		=> Yii::t('website','entry_CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
			'CONFIG_WEBSITE_ALLOWED_FILE_TYPES'			=> Yii::t('website','entry_CONFIG_WEBSITE_ALLOWED_FILE_TYPES'),
			'CONFIG_WEBSITE_CLEAN_TRASH_DURATION'		=> Yii::t('website','entry_CONFIG_WEBSITE_CLEAN_TRASH_DURATION'),
			'CONFIG_WEBSITE_DEFAULT_PRODUCT_LIST_VIEW'	=> Yii::t('website','entry_CONFIG_WEBSITE_DEFAULT_PRODUCT_LIST_VIEW'),
			'CONFIG_WEBSITE_MAIL_PROTOCOL'	=> Yii::t('website','entry_CONFIG_WEBSITE_MAIL_PROTOCOL'),
			'CONFIG_WEBSITE_SMTP_HOST'	=> Yii::t('website','entry_CONFIG_WEBSITE_SMTP_HOST'),
			'CONFIG_WEBSITE_SMTP_USERNAME'	=> Yii::t('website','entry_CONFIG_WEBSITE_SMTP_USERNAME'),
			'CONFIG_WEBSITE_SMTP_PASSWORD'	=> Yii::t('website','entry_CONFIG_WEBSITE_SMTP_PASSWORD'),
			'CONFIG_WEBSITE_SMTP_PORT'	=> Yii::t('website','entry_CONFIG_WEBSITE_SMTP_PORT'),
			'CONFIG_WEBSITE_SMTP_TIMEOUT'	=> Yii::t('website','entry_CONFIG_WEBSITE_SMTP_TIMEOUT'),
			'CONFIG_WEBSITE_TEMPLATE'	=> Yii::t('website','entry_CONFIG_WEBSITE_TEMPLATE'),
            );
			
		}

        public function __get($name) 
        {
                if (!empty($this->_dynamicFields[$name])) {
                        /*if (!empty($this->_dynamicData[$name])) {
                                return $this->_dynamicData[$name];
                        } else {
                                return null;
                        }*/
                        return $this->_dynamicData[$name];
                } else {
                        return parent::__get($name);
                }
        }
        
        public function __set($name, $val) 
        {
                if (!empty($this->_dynamicFields[$name])) {
                        $this->_dynamicData[$name] = $val;
                } else {
                        parent::__set($name, $value);
                }
        }
        
        

        public function WebsiteForm()
        {
            $configFields=array();
            foreach(Configuration::getConfigurations(array('condition'=>"code='CONFIG' and `key` like 'CONFIG_WEBSITE_%'")) as $config):
                $this->_dynamicFields[$config[key]]=1;
                $this->_dynamicData[$config[key]]=$config[value];
            endforeach;
            
        }
}
