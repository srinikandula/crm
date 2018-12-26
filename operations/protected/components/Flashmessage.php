<?php

 
class Flashmessage  extends CComponent
{
	public static function getMessage()
	{
		$message="hello how are you";
		$this->render("Flashmessage",array("message"=>$message));
	} 

	 
}