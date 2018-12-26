<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return array(
            'urlFormat'=>'path',
            'showScriptName'=>true,
            'class'=>'UrlManager',
            'hostInfo' => 'http://rsoftn',
            'secureHostInfo' => 'http://rsoftn',
            'secureRoutes' => array(
                //'site/login',   // site/login action
                //'site',  // site/signup action
                //'settings',     // all actions of SettingsController
            ),
            'rules'=>array(
                   // '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                   // '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
            );
