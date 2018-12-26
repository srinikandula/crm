<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//exit($_SERVER['DOCUMENT_ROOT'].'/front_end/osadmin/');
return array(
			'config'=>array(
				'site_url'=>'http://www.easygaadi.com/operations/',//example:http://www.cartnex.org/
				'admin_url'=>'http://www.easygaadi.com/operations/osadmin/',
				'document_root'=>$_SERVER['DOCUMENT_ROOT'].'/operations/',//osadmin/',
				'upload_path'=>'uploads/',//'uploads/catalog/',//directory path to the catalog folder
				//'catalog_upload_path'=>'uploads/',//'uploads/catalog/',//directory path to the catalog folder
				//'misc_upload_path'=>'',//directory path to the misc uploads folder
				'product_version'=>'',//product version
				'template_path'=>'',//directory path to the tempaltes folder
				'ssl'=>'',//http,https
				'page_size'=>'20',
			),

	);