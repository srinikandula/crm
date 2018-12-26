<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return array(
            'class'=>'CLogRouter',
            'routes'=>array(
                    array(
                            'class'=>'CFileLogRoute',
                            'levels'=>'error, warning',
                            'class'=>'CProfileLogRoute' //to display query profiles
                    ),
                //array('class'=>'cProfileLogRoute'),
                    // uncomment the following to show log messages on web pages
                    /*
                    array(
                            'class'=>'CWebLogRoute',
                    ),
                    */
            ),
		);
