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
            'CONFIG_WEBSITE_NAME' => 'Site Name',
			'CONFIG_WEBSITE_OWNER'=>'Owner',
			'CONFIG_WEBSITE_STATE'=>'State',
			'CONFIG_WEBSITE_SUPPORT_EMAIL_ADDRESS'=>'Email Address',
            'CONFIG_WEBSITE_OWNER_EMAIL_ADDRESS' => 'Owner Email Id',
            'CONFIG_WEBSITE_REPLY_EMAIL' => 'Reply Email Id',
            'CONFIG_WEBSITE_TELEPHONE_NUMBER' => 'Telephone No',
            'CONFIG_WEBSITE_ADDRESS' => 'Address',
            'CONFIG_WEBSITE_CACHE_LIFE_TIME' => 'Cache Life Time',
            'CONFIG_WEBSITE_MAINTENANCE_MESSAGE' => 'Maintenance Message',
            'CONFIG_WEBSITE_META_TITLE' => 'Meta Title',
            'CONFIG_WEBSITE_META_KEYWORDS' => 'Meta Keywords',
            'CONFIG_WEBSITE_ITEMS_PER_PAGE' => 'Items Per Page(Front End)',
            'CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN' => 'Items Per Page(Back End)',
            'CONFIG_WEBSITE_ALLOWED_FILE_TYPES' => 'Allowed File Types',
            'CONFIG_WEBSITE_META_DESCRIPTION' => 'Meta Description',
            'CONFIG_WEBSITE_GLOBAL_COMMISSION_TYPE'=>'Global Commission Percent Type',
            'CONFIG_WEBSITE_GLOBAL_COMMISSION'=>'Global Commission Percent',
			'CONFIG_WEBSITE_SEARCH_DAYS'=>'Search Days'
        );
            
			
		}

        public function __get($name) 
        {
                if (!empty($this->_dynamicFields[$name])) {
           
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
