<?php
class FlashMessage extends CWidget {
	public $flashMessages;
	public function run() {
		$this->flashMessages = Yii::app()->user->getFlashes();
		if ($this->flashMessages): 
		$this->render("flashmessage");   
		endif;
	}
}
?>
