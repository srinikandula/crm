<?php 
$this->renderPartial('_form_status_comment_block', array('form'=>$form,'model'=>$model));
$this->renderPartial('_form_doc_collection_block', array('form'=>$form,'model'=>$model));
$this->renderPartial('_form_status_history_block', array('form'=>$form,'model'=>$model));
$this->renderPartial('_form_access_history_block', array('form'=>$form,'model'=>$model));