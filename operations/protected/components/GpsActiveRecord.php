<?php

 
class GpsActiveRecord  extends CActiveRecord 
{
	 public function getDbConnection() {
        return Yii::app()->db_gts;
    }
}