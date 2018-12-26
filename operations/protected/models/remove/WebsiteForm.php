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
            'CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'		=> 'Rows Per Page',
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
