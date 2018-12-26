<?php
class Config
{
    private $data=array();
    
    public function getData($key)
    {
        return $this->data[$key];
    }
 
	public function init()
    {
        foreach(Configuration::model()->findAll() as $config)
        {
			
			if($config->key=='CONFIG_WEBSITE_ALLOWED_FILE_TYPES')
			{
				$type=array();
				foreach(explode("\n",$config->value) as $mimetype)
				{
					$type[]=trim($mimetype);
				}
				$config->value=$type;
			}

            $this->data[$config->key]=$config->value;	
        }

		

		$yii=Yii::app();
		$this->data['CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN']=$yii->request->getParam('page',$this->data['CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN']);
	}
}