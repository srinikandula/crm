<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Control Panel',
	'preload'=>array('log','bootstrap'),	// preloading 'log' component
	'import'=>require(dirname(__FILE__).'/import.php'),// autoloading model and component classes
	/*'behaviors' => array(
        'onBeginRequest' => array(
            'class' => 'application.components.BeginRequestBehavior'
        ),
    ),*/
	'timeZone' => 'Asia/Calcutta',
	'language'=>'en',//set language
        //'behaviors' => array('ApplicationConfigBehavior','ApplicationLibraryBehavior'),
	//'defaultController'=>"site/login",
	'defaultController'=>"site/index",
        //'defaultAction'=>'index',
        //'theme'=>"kjhljh",
	'modules'=>array(
		// uncomment the following to enable the Gii tool
          
		
	),
	'components'=>array(// application components
        'imageSize'=>array(
        'class'=>'application.components.ThemeSizes',
        ),
        'config'=>array(
        'class'=>'application.components.Config',
        ),
		'bootstrap' => array(
                'class' => 'ext.bootstrap.components.Bootstrap',
                'responsiveCss'=>true,
			),
		'cache' => array(
                   // 'class' => 'system.caching.CFileCache',
				    'class' => 'CFileCache',
                        ),
		'session' => array(
                    /*
                    'class' => 'CDbHttpSession', //to store session in database
                    'connectionID' => 'db',
                    'sessionTableName' => 'dbsession',
                     *                      */
                    'class' => 'CCacheHttpSession', //session configuration store.
                    'timeout' => 360000,//session will time out automatically after given seconds
                    //'savePath'=>'session',//will set session path and is not used in app
                    //'useTransparentSessionId'=>true,//session id will be concatinated to all urls
                           ),
        'user'=>array(// enable cookie-based authentication
			'allowAutoLogin'=>false,
                     ),
	'request'=>array('enableCsrfValidation'=>false),
	'urlManager'=>require(dirname(__FILE__).'/routes.php'),// uncomment the following to enable URLs in path-format
	'db'=>require(dirname(__FILE__).'/db.php'),
	'db_gts'=>array(
    'connectionString' => 'mysql:host=eggps.cloudapp.net;dbname=gts',
	//'connectionString' => 'mysql:host=egcrm.cloudapp.net;port=6102;dbname=easygaadi_gts',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => '098ioplkjbNm',
    'charset' => 'utf8',
	'class' => 'CDbConnection',  
    //'tablePrefix' => 'eg_',
    'schemaCachingDuration' => 1500, //caches database schema for given seconds
    //'enableProfiling'=>true, //for query profiling
    //'enableParamLogging'=>true, //for query profiling
),
	'errorHandler'=>array('errorAction'=>'site/error',// use 'site/error' action to display errors
		),
	'log'=>require(dirname(__FILE__).'/log.php'),
	),
	'params'=>require(dirname(__FILE__).'/params.php'),	// application-level parameters that can be accessed using Yii::app()->params['paramName']
);