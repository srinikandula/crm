<?php
class Customerdocs extends CActiveRecord
{
 	public function tableName()
	{
		return '{{customer_docs}}';
	}

	public function rules()
	{
		return array(
			array('id_customer,file', 'required'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array();
	}
        
    public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_goods_type DESC',
				),
		));
	}

	public function setUploadMultipleImages($data) {
        $fUploadImage = "";
        if($data['id']){
        Customerdocs::model()->deleteAll('id_customer=' . $data['id']);
        }
        foreach ($data['multiImage']['name']['upload'] as $k => $v) {
            if ($data['multiImage']['name']['upload'][$k]['image'] == "" && $data['imageData'][$k]['prev_image'] == "") {
                continue;
            }

            $fUploadImage = array("name" => $data['multiImage']['name']['upload'][$k]['image'],
                "type" => $data['multiImage']['type']['upload'][$k]['image'],
                "tmp_name" => $data['multiImage']['tmp_name']['upload'][$k]['image'],
                "error" => $data['multiImage']['error']['upload'][$k]['image'],
                "size" => $data['multiImage']['size']['upload'][$k]['image'],);

            $fUploadImage['input']['prefix'] = 'customer_doc_'. '-' . $data['id'] . '_' . $k . '_';
            $fUploadImage['input']['path'] = Library::getMiscUploadPath();
            $fUploadImage['input']['prev_file'] = $data['imageData'][$k]['prev_image'];

            $uploadImage = Library::fileUpload($fUploadImage);
            $model = new Customerdocs;
            $model->id_customer = $data['id'];
            $model->file = $uploadImage['file'];
            $model->save(false);
        }
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}