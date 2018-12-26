<?php
//standard
$_['date_format_short']             = "Y/m/d";//'d/m/Y";
$_['date_format_long']              = "l dS F Y";
$_['time_format']                   = "h:i:s A";
$_['text_enabled']  				= '​Enable';
$_['text_disabled']  				= "​Disable";
$_['text_top']                      = "Top";
$_['text_yes']                      = "Yes";
$_['text_no']                       = "No";
$_['text_none']                     = " --- None --- ";
$_['text_select']                   = " --- Please Select --- ";
$_['text_select_all']               = "Select All";

//button
$_['button_insert']                 = "Add Item";
$_['button_delete']                 = "Delete Item";
$_['button_save']                   = "Save";
$_['button_cancel']                 = "Cancel";
//$_['button_clear']                  = "Clear Log";
$_['button_close']                  = "Close";
//$_['button_edit']                   = "Edit";



//menu 
$_['menu_section_home']  			= "Menu";
$_['menu_section_orders'] 			= "Sales";
$_['menu_section_catalog']  		= "Manage Products";
$_["menu_section_customers"]  		= 'Manage Customers';
$_['menu_section_featured']  		= "Marketing & Promotions";
$_['menu_section_design']  			= 'Design';
$_['menu_section_reports']  		= 'Reports';
$_['menu_section_tools']  			= 'Tools';
$_['menu_section_settings']  		= "System";


//Menu
$_['menu_item_dashboard']  			= "Dashboard";
//Overview
$_['menu_item_gettingstarted']  	= "Getting Started";

//Sales
$_['menu_item_order']				= 'Orders';

//Manage Products
$_['menu_item_category']='Categories';
$_['menu_item_product']='Products';
$_['menu_item_manufacturer']="Manufacturers";
$_['menu_item_attribute']='Attributes';
$_['menu_item_productgroup']="Groups";
$_['menu_item_option']="​Options";
$_['menu_item_review']  			= "Reviews";

//Manage Customers
$_['menu_item_customer']  			= "​Customers";
$_['menu_item_customergroups']  	= "Groups";

//Marketing & Promotions
$_['menu_item_coupon']  			= "​Coupons";
$_['menu_item_page']  				= "Pages";

//Design
$_['menu_item_theme']  				= "Themes";
$_['menu_item_banner']  			= "Banners";
$_['menu_item_emailtemplate']  		= "Email Templates";

//reports
$_['menu_item_searchterm']  		= "Search Keywords";
$_['menu_item_productview']  		= "Product Views";
$_['menu_item_customerordertotal']  = "Customers order Summary";
$_['menu_item_productreport']  		= "Product Sales Summary";

//Administration
$_['menu_section_admin']  			= "Administration";
$_['menu_item_administrator']  		= "​Users";
$_['menu_item_adminrole']  			= "​Roles";
$_['menu_item_adminloghistory'] 	= "​History";

//System
$_['menu_item_website']  			= "​Website Settings";
$_['menu_item_mystore']  			= "​Store Settings";
$_['menu_item_currency']  			= "Currency";
$_['menu_item_taxclass']  			= "Tax";
$_['menu_item_zone']  				= "Zones";
$_['menu_item_country']  			= "Countries";
$_['menu_item_state']  				= "States";
$_['menu_item_region']  			= "Regions";
$_["menu_item_stockstatus"]="Stock Status";
$_['menu_item_orderstatus']  		= "Order Status";


$_['menu_item_newsletter']  		= "​Send Newsletter";
$_['menu_item_newslettertemplate']  = "Customise Newsletter Template";
$_['menu_item_export']  			= "​Catalog Import & Export";

$_['menu_item_payment']  			= "​Payment Gateways";
$_['menu_item_shipping']  			= "​Shipping Gateways";
$_['menu_item_modules']  			= "Modules";
$_['menu_item_cartrules']  			= "​Cart Rules";

//flash message
$_['message_create_success']  		= "​<strong>Well Done :</strong> Creation Successfull!!";
$_['message_create_fail']  			= "<strong>Error :</strong> Creation Failed!!";

$_['message_modify_success']  		= "<strong>Well Done :</strong> Modification Successfull!!";
$_['message_modify_fail']  			= "<strong>Error :</strong> Modification Failed!!";

$_['message_delete_success']  		= "<strong>Well Done :</strong> Deletion Successfull!!";
$_['message_delete_fail']  			= "<strong>Error :</strong> Deletion Failed!!";

$_['module_install_success']  		= "<strong>Well Done :</strong> {module} Module Installation Successfull!!";
$_['module_modify_success']  		= "<strong>Well Done :</strong> {module} Module Modification Successfull!!";
$_['module_uninstall_success']  	= "<strong>Well Done :</strong> {module} Module Uninstallation Successfull!!";
$_['module_uninstall_fail']  	= "<strong>Error :</strong> {module} Module Uninstallation Failed!!";


//Notification

$_['message_no_records_found']  = "<strong>No Results found.</strong>";
$_['message_checkboxValidation_alert']  = "<strong>Notice :</strong> No rows selected!!";

// header
$_['heading_title']  				= "Reset your password";

//menu
//$_['text_welcome']    			= "welcome %s | <a href="%s"><i class="icon-off"></i> %s</a>"; //used
$_['text_welcome']     				= "welcome {user} | <a href='{link}'><i class='icon-off'></i> {link_title} </a>"; //used

// Text
$_['text_reset']     				= "Reset your password!";
$_['text_password']  				= "Enter the new password you wish to use.";
$_['text_success']   				= "Success: Your password has been successfully updated.";

// Entry
$_['entry_password']				= "Password:";
$_['entry_confirm']  				= "Password Confirm:";

// Error
$_['error_password'] 				= "Password must be between 5 and 20 characters!";
$_['error_confirm']  				= "Password and password confirmation do not match!";
//echo "<pre>";print_r($_);exit;
return $_;