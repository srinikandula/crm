<?php

//static $config = NULL;
//static $log = NULL;
//Error Handler
/*
  function error_handler_for_export($errno, $errstr, $errfile, $errline) {
  global $config;
  global $log;

  switch ($errno) {
  case E_NOTICE:
  case E_USER_NOTICE:
  $errors = "Notice";
  break;
  case E_WARNING:
  case E_USER_WARNING:
  $errors = "Warning";
  break;
  case E_ERROR:
  case E_USER_ERROR:
  $errors = "Fatal Error";
  break;
  default:
  $errors = "Unknown";
  break;
  }

  if (($errors=='Warning') || ($errors=='Unknown')) {
  return true;
  }

  echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';




  return true;
  }


  function fatal_error_shutdown_handler_for_export()
  {
  $last_error = error_get_last();
  if ($last_error['type'] === E_ERROR) {
  // fatal error
  error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
  }
 */

class Export {

    function clean(&$str, $allowBlanks = FALSE) {
        $result = "";
        $n = strlen($str);
        for ($m = 0; $m < $n; $m++) {
            $ch = substr($str, $m, 1);
            if (($ch == " ") && (!$allowBlanks) || ($ch == "\n") || ($ch == "\r") || ($ch == "\t") || ($ch == "\0") || ($ch == "\x0B")) {
                continue;
            }
            $result .= $ch;
        }
        return $result;
    }

    function import(&$database, $sql) {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                //$this->db->query($sql);
                $this->db->query($sql);
            }
        }
    }

    protected function detect_encoding($str) {
        // auto detect the character encoding of a string
        return mb_detect_encoding($str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R');
    }

    public function getTruckTypes() {
        $truckTypeRows = Trucktype::model()->findAll();
        $trucks = array();
        foreach ($truckTypeRows as $truckTypeRow) {
            $trucks[strtolower(trim($truckTypeRow->title))] = $truckTypeRow->id_truck_type;
        }
        return $trucks;
    }

    public function getGoodsTypes() {
        $goodTypeRows = Goodstype::model()->findAll();
        $goods = array();
        foreach ($goodTypeRows as $goodTypeRow) {
            $goods[strtolower(trim($goodTypeRow->title))] = $goodTypeRow->id_goods_type;
        }
        return $goods;
    }

    function storeTruckPriceDetailsIntoDatabase(&$discounts) {
        //echo '<pre>';print_r($discounts);echo '</pre>';
        foreach ($discounts as $discount) {
            $row = Yii::app()->db->createCommand("select * from {{truck}} where id_customer='" . (int) $_GET['id'] . "' and trim(lower(truck_reg_no)) like '" . strtolower(trim($discount['truck_reg_no'])) . "'")->queryRow();

            
            
            if ($row['id_truck']) {
                Yii::app()->db->createCommand('insert into {{truck_route_price}}(id_truck,id_customer,source_address,source_city,source_state,source_lat,source_lng,destination_address,destination_city,destination_state,destination_lat,destination_lng,date_available,date_created,price,id_goods_type,status) values("' . $row['id_truck'] . '","' . (int) $_GET['id'] . '","' . $discount['source']['input'] . '","' . $discount['source']['city'] . '","' . $discount['source']['state'] . '","' . $discount['source']['lat'] . '","' . $discount['source']['lng'] . '","' . $discount['destination']['input'] . '","' . $discount['destination']['city'] . '","' . $discount['destination']['state'] . '","' . $discount['destination']['lat'] . '","' . $discount['destination']['lng'] . '","' . $discount['date_available'] . '",now(),"' . $discount['price'] . '","' . $discount['id_goods_type'] . '",1)')->query();
            }else{
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Price Tab:Truck '".$discount['truck_reg_no']."' Not available.Insertion failed!!";
            }
        }

        Yii::app()->db->createCommand("COMMIT;")->query();
        return TRUE;
        //exit;
    }

    function storeTruckDetailsIntoDatabase(&$discounts) {
        //echo '<pre>';print_r($discounts);echo '</pre>';
        foreach ($discounts as $discount) {
            //echo "select * from {{truck}} where id_customer='".(int)$_GET['id']."' and trim(lower(truck_reg_no)) like '".strtolower(trim($discount['truck_reg_no']))."'<br/>";
            $row = Yii::app()->db->createCommand("select * from {{truck}} where id_customer='" . (int) $_GET['id'] . "' and trim(lower(truck_reg_no)) like '" . strtolower(trim($discount['truck_reg_no'])) . "'")->queryRow();
            if ($row['id_truck']) {
                Yii::app()->db->createCommand('update {{truck}} set id_truck_type="' . $discount['id_truck_type'] . '",tracking_available="' . $discount['tracking_available'] . '",insurance_available="' . $discount['insurance_available'] . '",source_address="' . $discount['source_address'] . '",source_city="' . $discount['source']['city'] . '",source_state="' . $discount['source_state'] . '",source_lat="' . $discount['source']['lat'] . '",source_lng="' . $discount['source']['lng'] . '",description="' . $discount['description'] . '" where id_truck="'.$row['id_truck'].'"')->query();
            } else {
                Yii::app()->db->createCommand('insert into {{truck}}(id_customer,truck_reg_no,id_truck_type,tracking_available,insurance_available,date_created,status,approved,source_address,source_city,source_state,source_lat,source_lng,description) values("' . (int) $_GET['id'] . '","' . $discount['truck_reg_no'] . '","' . $discount['id_truck_type'] . '","' . $discount['tracking_available'] . '","' . $discount['insurance_available'] . '",now(),1,0,"' . $discount['source_address'] . '","' . $discount['source']['city'] . '","' . $discount['source_state'] . '","' . $discount['source']['lat'] . '","' . $discount['source']['lng'] . '","' . $discount['description'] . '")')->query();
            }
        }

        Yii::app()->db->createCommand("COMMIT;")->query();
        return TRUE;
        //exit;        
    }

    function uploadTruckDetails(&$reader, $truckTypes) {
        //exit("uploadTruckDetails");
        $data = $reader->getSheet(0);
        $i = 0;
        $discounts = array();
        $k = $data->getHighestRow();
        $isFirstRow = TRUE;
        //echo "value of k ".$k."<br/>";
        for ($i = 0; $i < $k; $i+=1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            $Truck_Reg_No = trim($this->getCell($data, $i, 1));
            $Truck_Type = trim($this->getCell($data, $i, 2));
            $errorDetails="";
            $errorDetails="<br/>Truck Reg No:".trim($this->getCell($data, $i, 1))." | Truck Type ".$truckTypes[strtolower(trim($Truck_Type))]." | Tracking Available ".trim($this->getCell($data, $i, 3))." | Insurance Available ".trim($this->getCell($data, $i, 4))." | Source Address ".trim($this->getCell($data, $i, 5))." | Source State ".trim($this->getCell($data, $i, 6));
            
            if ($Truck_Reg_No == "") {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Details Tab:Truck Reg No  is missing!!".$errorDetails;
                continue;
            }



            if ($truckTypes[strtolower(trim($Truck_Type))] == "") {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Type Tab:Truck Type  is missing or invalid!!".$errorDetails;
                continue;
            }

            $Tracking_Available = $this->getCell($data, $i, 3, 'No');
            //echo '<br/>tracking available '.$Tracking_Available;
            if ($Tracking_Available != 'Yes' && $Tracking_Available != 'No') {
                $Tracking_Available = 'No';
            }

            $Insurance_Available = $this->getCell($data, $i, 4, 'No');
            //echo '<br/>insurance available '.$Insurance_Available;
            if ($Insurance_Available != 'Yes' && $Insurance_Available != 'No') {
                $Insurance_Available = 'No';
            }

            $src = array();
            $Source_Address = $this->getCell($data, $i, 5);
            $Source_State = $this->getCell($data, $i, 6);
            if ($Source_Address != "" || $Source_State != "") {
                if ($Source_Address != "" && $Source_State != "") {
                    $imp_src = $Source_Address . "," . $Source_State;
                } else if ($Source_Address != "") {
                    $imp_src = $Source_Address;
                } else if ($Source_Address != "" || $Source_State != "") {
                    $imp_src = $Source_State;
                }

                $src = Library::getGPDetails($imp_src);
            }

            $discounts[$i] = array();
            $discounts[$i]['truck_reg_no'] = $Truck_Reg_No;
            $discounts[$i]['id_truck_type'] = $truckTypes[strtolower(trim($Truck_Type))];
            $discounts[$i]['tracking_available'] = $Tracking_Available == "Yes" ? 1 : 0;
            $discounts[$i]['insurance_available'] = $Insurance_Available == "Yes" ? 1 : 0;
            $discounts[$i]['source'] = $src;
            $discounts[$i]['source_address'] = $Source_Address;
            $discounts[$i]['source_state'] = $Source_State;
            $discounts[$i]['description']=$this->getCell($data, $i, 7);
        }
        //echo 'here<pre>';print_r($discounts);      echo '</pre>';
        //exit;        
        return $this->storeTruckDetailsIntoDatabase($discounts);
    }

    function uploadTruckPrices(&$reader, $goodsTypes) {
        $data = $reader->getSheet(1);
        $i = 0;
        $discounts = array();
        $k = $data->getHighestRow();
        //echo '<pre>';print_r($data);echo '</pre>';
        $isFirstRow = TRUE;
        for ($i = 0; $i < $k; $i+=1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            $Truck_Reg_No = trim($this->getCell($data, $i, 1));
            
                $errorDetails="<br/>Truck Reg No:".trim($this->getCell($data, $i, 1))." | Source Address: ".trim($this->getCell($data, $i, 2))." | Source State: ".trim($this->getCell($data, $i, 3))." | Destination Address: ".trim($this->getCell($data, $i, 4))." | Destination State: ".trim($this->getCell($data, $i, 5))." | Date Available: ".trim(date('d-M', $this->excelDateToDate($this->getCell($data, $i, 6, '0000-00-00'))))." | Goods Type: ".trim($this->getCell($data, $i, 7))." | Price :".trim($this->getCell($data, $i, 8))."<br/>";
            
            if ($Truck_Reg_No == "") {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Prices Tab:Truck Reg No  is missing!!".$errorDetails;
                continue;
            }

            $Source_Address = trim($this->getCell($data, $i, 2));
            $Source_State = trim($this->getCell($data, $i, 3));
            if ($Source_Address == "" && $Source_State == "") {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Prices Tab:Source Address, State is missing!!".$errorDetails;
                continue;
            } else {
                if ($Source_Address != "" && $Source_State != "") {
                    $srcStr = $Source_Address . "," . $Source_State;
                } else if ($Source_Address != "") {
                    $srcStr = $Source_Address;
                } else if ($Source_State != "") {
                    $srcStr = $Source_State;
                }
                $src = Library::getGPDetails($srcStr);
            }

            $Destination_Address = trim($this->getCell($data, $i, 4));
            $Destination_State = trim($this->getCell($data, $i, 5));
            if ($Destination_Address == "" || $Destination_State == "") {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Prices Tab:Destination Address or State is missing!!".$errorDetails;
                continue;
            } else {
                if ($Destination_Address != "" && $Destination_State != "") {
                    $destStr = $Destination_Address . "," . $Destination_State;
                } else if ($Destination_Address != "") {
                    $destStr = $Destination_Address;
                } else if ($Destination_State != "") {
                    $destStr = $Destination_State;
                }
                $dest = Library::getGPDetails($destStr);
            }


            if ($src['status'] !== 'OK') {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Prices Tab:Invalid source address,state!!".$errorDetails;
                continue;
            }

            if ($dest['status'] !== 'OK') {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Prices Tab:Invalid destination address,state!!".$errorDetails;
                continue;
            }


            $Date_Available = $this->getCell($data, $i, 6, '0000-00-00');
            if ($Date_Available != "") {
                $Date_Available = date('Y-m-d', $this->excelDateToDate($Date_Available));
            } else {
                $Date_Available = 'now()';
            }

            if ($Tracking_Available == 'Yes' || $Tracking_Available == 'No') {
                $Tracking_Available = 'No';
            }


            $Goods_Type = $this->getCell($data, $i, 7, '1');
            if ($goodsTypes[strtolower(trim($Goods_Type))]) {
                $Goods_Type_Val = $goodsTypes[strtolower(trim($Goods_Type))];
            } else {
                $Goods_Type_Val = 1;
            }


            $Price = $this->getCell($data, $i, 8, '0');
            if ($Price == '0' || $Price == '') {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Prices Tab:Price missing!!".$errorDetails;
                continue;
            }


            $discounts[$i] = array();
            $discounts[$i]['truck_reg_no'] = $Truck_Reg_No;
            $discounts[$i]['date_available'] = $Date_Available;

            $discounts[$i]['id_goods_type'] = $Goods_Type_Val;
            $discounts[$i]['price'] = $Price;
            //$src,$dest;
            $discounts[$i]['source'] = $src;
            $discounts[$i]['destination'] = $dest;
        }
        //echo 'here<pre>';print_r($discounts);      echo '</pre>';
        //exit;        
        return $this->storeTruckPriceDetailsIntoDatabase($discounts);
    }

    function getCell(&$worksheet, $row, $col, $default_val = '') {
        $col -= 1; // we use 1-based, PHPExcel uses 0-based column index
        $row += 1; // we use 0-based, PHPExcel used 1-based row index
        return ($worksheet->cellExistsByColumnAndRow($col, $row)) ? $worksheet->getCellByColumnAndRow($col, $row)->getValue() : $default_val;
    }

    function validateHeading(&$data, &$expected) {
        $heading = array();
        $k = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
        if ($k != count($expected)) {
            return FALSE;
        }
        $i = 0;
        for ($j = 1; $j <= $k; $j+=1) {
            $heading[] = $this->getCell($data, $i, $j);
        }
        $valid = TRUE;
        for ($i = 0; $i < count($expected); $i+=1) {
            if (!isset($heading[$i])) {
                $valid = FALSE;
                break;
            }
            if (strtolower($heading[$i]) != strtolower($expected[$i])) {
                $valid = FALSE;
                break;
            }
        }
        return $valid;
    }

    function validateTrucks(&$reader) {
        $expectedCategoryHeading = array("Truck_Reg_No", "Truck_Type", "Tracking_Available", "Insurance_Available", "Source_Address", "Source_State","Truck_Description");
        $data = & $reader->getSheet(0);
        //echo '<pre>';print_r($data);exit;
        return $this->validateHeading($data, $expectedCategoryHeading);
    }

    function validatePrices(&$reader) {
        $expectedProductHeading = array("Truck_Reg_No", "Source_Address", "Source_State", "Destination_Address", "Destination_State", "Date_Available", "Goods_Type", "Price");
        $data = & $reader->getSheet(1);
        return $this->validateHeading($data, $expectedProductHeading);
    }

    function validateUpload(&$reader) {
        if ($reader->getSheetCount() != 3) {
            //echo "Work Sheet Missing.3 Work Sheets expected!!";
            $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Work Sheet Missing.2 Work Sheets expected!!";
            return FALSE;
        }

        if (!$this->validateTrucks($reader)) {
            //echo "Invalid Truck Details Header!!";
            $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Invalid Truck Details Header!!";
            return FALSE;
        }

        if (!$this->validatePrices( $reader )) {
          //echo "Invalid Price Details Header!!";
          $_SESSION['EXCEL_ERROR_MESSAGE'][]="Invalid Price Details Header!!";

          return FALSE;
        } 
        return TRUE;
    }

    function excelDateToDate($readDate) {
        $phpexcepDate = $readDate - 25569; //to offset to Unix epoch
        return strtotime("+$phpexcepDate days", mktime(0, 0, 0, 1, 1, 1970));
    }

    function upload($filename) {
        error_reporting(E_ERROR);
        unset($_SESSION['EXCEL_ERROR_MESSAGE']);

        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 1800);
        //set_time_limit( 60 );

        chdir($_SERVER['DOCUMENT_ROOT'] . '/easygaadi.com/osadmin/protected/extensions/library/PHPExcel');
        require_once('Classes/PHPExcel.php' );
        chdir('../extensions');
        $inputFileType = PHPExcel_IOFactory::identify($filename);

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($filename);

        $ok = $this->validateUpload($reader);

        if (!$ok) {
            return FALSE;
        }

        $truckTypes = $this->getTruckTypes();
        $goodsTypes = $this->getGoodsTypes();
        //echo '<pre>';print_r($truckTypes);print_r($goodsTypes);
        //exit;
         $ok = $this->uploadTruckDetails( $reader, $truckTypes );

          if (!$ok) {
          return FALSE;
          } 
          //exit;  
        $ok = $this->uploadTruckPrices($reader, $goodsTypes);

        if (!$ok) {
            return FALSE;
        }

        //echo "in import";
        //exit;
        //unset($_SESSION['exp']);

        chdir('../../..');
        return $ok;
    }

    protected function getAttributeArray() {
        $rows = $this->db->fetchAll("SELECT ag.attribute_group_id, lower(agd.name) AS `group`, ad.attribute_id, lower(ad.name) as name FROM `r_attribute_group` ag INNER JOIN `r_attribute_group_description` agd ON agd.attribute_group_id=ag.attribute_group_id AND agd.language_id=1 LEFT JOIN `r_attribute` a ON a.attribute_group_id=ag.attribute_group_id INNER JOIN `r_attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id=1");
        $attribute = array();
        //echo "<pre>";
        //print_r($rows);
        foreach ($rows as $k => $v) {
            //print_r($r);
            $attribute[trim($v[group])][trim($v[name])] = $v[attribute_id];
        }
        //echo "<pre>";
        //print_r($attribute);
        return $attribute;
    }

    /* function getStoreIdsForCategories( &$database ) {
      $sql =  "SELECT category_id, store_id FROM `r_category_to_store` cs;";
      $storeIds = array();
      $result = $this->db->query( $sql );
      foreach ($result->rows as $row) {
      $categoryId = $row['category_id'];
      $storeId = $row['store_id'];
      if (!isset($storeIds[$categoryId])) {
      $storeIds[$categoryId] = array();
      }
      if (!in_array($storeId,$storeIds[$categoryId])) {
      $storeIds[$categoryId][] = $storeId;
      }
      }
      return $storeIds;
      } */


    /* function getLayoutsForCategories( &$database ) {
      $sql  = "SELECT cl.*, l.name FROM `r_category_to_layout` cl ";
      $sql .= "LEFT JOIN `r_layout` l ON cl.layout_id = l.layout_id ";
      $sql .= "ORDER BY cl.category_id, cl.store_id;";
      $result = $this->db->query( $sql );
      $layouts = array();
      foreach ($result->rows as $row) {
      $categoryId = $row['category_id'];
      $storeId = $row['store_id'];
      $name = $row['name'];
      if (!isset($layouts[$categoryId])) {
      $layouts[$categoryId] = array();
      }
      $layouts[$categoryId][$storeId] = $name;
      }
      return $layouts;
      } */

    function populateCategoriesWorksheet(&$worksheet, &$database, $languageId, &$boxFormat, &$textFormat) {

        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, strlen('category_id') + 1);
        $worksheet->setColumn($j, $j++, strlen('parent_id') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('name'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('seo_keyword'), 16) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('description'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('image_name'), 12) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('top'), 5) + 1);
        $worksheet->setColumn($j, $j++, strlen('columns') + 1);
        $worksheet->setColumn($j, $j++, strlen('sort_order') + 1);
        ;
        $worksheet->setColumn($j, $j++, max(strlen('status'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('filters'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('meta_keywords'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('meta_description'), 32) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('date_added'), 19) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_modified'), 19) + 1, $textFormat);

        // The heading row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'category_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'parent_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'name', $boxFormat);
        $worksheet->writeString($i, $j++, 'seo_keyword', $boxFormat);
        $worksheet->writeString($i, $j++, 'description', $boxFormat);
        $worksheet->writeString($i, $j++, 'image_name', $boxFormat);
        $worksheet->writeString($i, $j++, 'top', $boxFormat);
        $worksheet->writeString($i, $j++, 'columns', $boxFormat);
        $worksheet->writeString($i, $j++, 'sort_order', $boxFormat);
        $worksheet->writeString($i, $j++, "status", $boxFormat);
        $worksheet->writeString($i, $j++, 'filters', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_keywords', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_description', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_added', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_modified', $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual categories data
        $i += 1;
        $j = 0;

        $result_rows = $this->db->query("SELECT c.* , cd.*, ua.keyword FROM `r_categories` c INNER JOIN `r_categories_description` cd ON cd.categories_id = c.categories_id AND cd.language_id='" . $languageId . "' and c.del='0' LEFT JOIN `r_url_alias` ua ON ua.id=c.categories_id and ua.query='category' ORDER BY c.`parent_id`, `sort_order`, c.`categories_id`");

        foreach ($result_rows as $row) {
            $worksheet->setRow($i, 26);
            $worksheet->write($i, $j++, $row['categories_id']);
            $worksheet->write($i, $j++, $row['parent_id']);
            $worksheet->writeString($i, $j++, html_entity_decode($row['categories_name'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
            $worksheet->writeString($i, $j++, html_entity_decode($row['categories_description'], ENT_QUOTES, 'UTF-8'));
            $worksheet->write($i, $j++, $row['categories_image']);
            $worksheet->write($i, $j++, ($row['top'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['column']);
            $worksheet->write($i, $j++, $row['sort_order']);
            $worksheet->write($i, $j++, ($row['status'] == 0) ? "false" : "true", $textFormat);
            $filters = $this->getFilters($row['filters']);
            $worksheet->writeString($i, $j++, html_entity_decode($filters, ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['meta_keywords'], ENT_QUOTES, 'UTF-8'));
            $worksheet->writeString($i, $j++, html_entity_decode($row['meta_description'], ENT_QUOTES, 'UTF-8'));
            $worksheet->write($i, $j++, $row['date_added'], $textFormat);
            $worksheet->write($i, $j++, $row['last_modified'], $textFormat);
            $i += 1;
            $j = 0;
        }
    }

    function populateProductsWorksheet(&$worksheet, &$database, &$imageNames, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, &$textFormat) {
        // Set the column widths
        $j = 0;

        $worksheet->setColumn($j, $j++, max(strlen('product_id'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('name'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('categories'), 12) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('quantity'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('minimum'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('seo_keyword'), 16) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('description'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('model'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('manufacturer'), 10) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('image_name'), 12) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('additional image names'), 24) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('additional_image_option'), 24) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('subtract'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_available'), 10) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('status'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('sort_order'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('stock_status_id'), 3) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('tax_class_id'), 2) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('downloads'), 24) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('shipping'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('related_products'), 16) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('tags'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('sku'), 10) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('upc'), 10) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('weight'), 6) + 1, $weightFormat);
        $worksheet->setColumn($j, $j++, max(strlen('unit'), 3) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('length'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('width'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('height'), 8) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('length_unit'), 3) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('points'), 5) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('meta_keywords'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('meta_description'), 32) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_added'), 19) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('date_modified'), 19) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('viewed'), 5) + 1);



        // The product headings row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'name', $boxFormat);
        $worksheet->writeString($i, $j++, 'categories', $boxFormat);
        $worksheet->writeString($i, $j++, 'quantity', $boxFormat);
        $worksheet->writeString($i, $j++, 'minimum', $boxFormat);
        $worksheet->writeString($i, $j++, 'seo_keyword', $boxFormat);
        $worksheet->writeString($i, $j++, 'description', $boxFormat);
        $worksheet->writeString($i, $j++, 'model', $boxFormat);
        $worksheet->writeString($i, $j++, 'manufacturer', $boxFormat);
        $worksheet->writeString($i, $j++, 'image_name', $boxFormat);
        $worksheet->writeString($i, $j++, 'additional image names', $boxFormat);
        $worksheet->writeString($i, $j++, 'additional_image_option', $boxFormat);
        $worksheet->writeString($i, $j++, 'price', $boxFormat);
        $worksheet->writeString($i, $j++, "subtract", $boxFormat);
        $worksheet->writeString($i, $j++, 'date_available', $boxFormat);
        $worksheet->writeString($i, $j++, "status", $boxFormat);
        $worksheet->writeString($i, $j++, 'sort_order', $boxFormat);
        $worksheet->writeString($i, $j++, 'stock_status_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'tax_class_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'downloads', $boxFormat);
        $worksheet->writeString($i, $j++, "shipping", $boxFormat);
        $worksheet->writeString($i, $j++, 'related_products', $boxFormat);
        $worksheet->writeString($i, $j++, 'tags', $boxFormat);
        $worksheet->writeString($i, $j++, 'sku', $boxFormat);
        $worksheet->writeString($i, $j++, 'upc', $boxFormat);
        $worksheet->writeString($i, $j++, 'weight', $boxFormat);
        $worksheet->writeString($i, $j++, 'unit', $boxFormat);
        $worksheet->writeString($i, $j++, 'length', $boxFormat);
        $worksheet->writeString($i, $j++, 'width', $boxFormat);
        $worksheet->writeString($i, $j++, 'height', $boxFormat);
        $worksheet->writeString($i, $j++, "length_unit", $boxFormat);
        $worksheet->writeString($i, $j++, 'points', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_keywords', $boxFormat);
        $worksheet->writeString($i, $j++, 'meta_description', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_added', $boxFormat);
        $worksheet->writeString($i, $j++, 'date_modified', $boxFormat);
        $worksheet->writeString($i, $j++, 'viewed', $boxFormat);

        $worksheet->setRow($i, 30, $boxFormat);


        $i += 1;
        $j = 0;



        //$result_rows=$this->db->fetchAll("SELECT p.del,p.products_id, pd.products_name, GROUP_CONCAT( DISTINCT CAST(pc.categories_id AS CHAR(11)) SEPARATOR ',' ) AS categories, p.sku, p.upc, p.products_quantity, p.products_model, m.manufacturers_name AS manufacturer, p.products_image AS image_name, p.shipping, p.products_price, p.products_points, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_weight, wc.unit, p.length, p.width, p.height, p.products_status, p.products_tax_class_id, p.viewed, p.sort_order, pd.language_id, ua.keyword, pd.products_description, pd.meta_description, pd.meta_keywords, p.stock_status_id, mc.unit AS length_unit, p.substract_stock, p.products_minimum_quantity, GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR ',' ) AS related, GROUP_CONCAT( DISTINCT pt.tag SEPARATOR ',' ) AS tags FROM `r_products` p INNER JOIN `r_products_description` pd ON p.products_id=pd.products_id AND p.del='0' AND pd.language_id='".$languageId."' LEFT JOIN `r_products_to_categories` pc ON p.products_id=pc.products_id LEFT JOIN `r_url_alias` ua ON ua.id=p.products_id and ua.query='product' LEFT JOIN `r_manufacturers` m ON m.manufacturers_id = p.manufacturers_id LEFT JOIN `r_weight_class_description` wc ON wc.weight_class_id = p.weight_class_id AND wc.language_id='".$languageId."' LEFT JOIN `r_length_class_description` mc ON mc.length_class_id=p.length_class_id AND mc.language_id='".$languageId."' LEFT JOIN `r_product_related` pr ON pr.product_id=p.products_id LEFT JOIN `r_product_tag` pt ON pt.products_id=p.products_id AND pt.language_id='".$languageId."' GROUP BY p.products_id ORDER BY p.products_id, pc.categories_id"); //before tags removed for langauge id

        $result_rows = $this->db->fetchAll("SELECT p.del,p.products_id, pd.products_name, GROUP_CONCAT( DISTINCT CAST(pc.categories_id AS CHAR(11)) SEPARATOR ',' ) AS categories, p.sku, p.upc, p.products_quantity, p.products_model, m.manufacturers_name AS manufacturer, p.products_image AS image_name, p.shipping, p.products_price, p.products_points, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_weight, wc.unit, p.length, p.width, p.height, p.products_status, p.products_tax_class_id, p.viewed, p.sort_order, pd.language_id, ua.keyword, pd.products_description, pd.meta_description, pd.meta_keywords, p.stock_status_id, mc.unit AS length_unit, p.substract_stock, p.products_minimum_quantity, GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR ',' ) AS related, GROUP_CONCAT( DISTINCT pt.tag SEPARATOR ',' ) AS tags FROM `r_products` p INNER JOIN `r_products_description` pd ON p.products_id=pd.products_id AND p.del='0' AND pd.language_id='" . $languageId . "' LEFT JOIN `r_products_to_categories` pc ON p.products_id=pc.products_id LEFT JOIN `r_url_alias` ua ON ua.id=p.products_id and ua.query='product' LEFT JOIN `r_manufacturers` m ON m.manufacturers_id = p.manufacturers_id LEFT JOIN `r_weight_class_description` wc ON wc.weight_class_id = p.weight_class_id AND wc.language_id='" . $languageId . "' LEFT JOIN `r_length_class_description` mc ON mc.length_class_id=p.length_class_id AND mc.language_id='" . $languageId . "' LEFT JOIN `r_product_related` pr ON pr.product_id=p.products_id LEFT JOIN `r_product_tag` pt ON pt.products_id=p.products_id GROUP BY p.products_id ORDER BY p.products_id, pc.categories_id");


        foreach ($result_rows as $row) {
            if ($row['del'] == '1') {//ignore deleted products ie del=1
                continue;
            }

            //downloads
            $ds = $this->db->fetchAll("select name from r_download_description dd,r_product_to_download pd where pd.download_id=dd.download_id and pd.product_id='" . (int) $row['products_id'] . "'");
            $dname = "";
            $pre = "";
            foreach ($ds as $k => $v) {
                $dname.=$pre . $v['name'];
                $pre = ",";
            }


            $worksheet->setRow($i, 26);
            $productId = $row['products_id'];
            $worksheet->write($i, $j++, $productId);
            $worksheet->writeString($i, $j++, html_entity_decode($row['products_name'], ENT_QUOTES, 'UTF-8'));
            $worksheet->write($i, $j++, $row['categories'], $textFormat);
            $worksheet->write($i, $j++, $row['products_quantity']);
            $worksheet->write($i, $j++, $row['products_minimum_quantity']);
            $worksheet->writeString($i, $j++, ($row['keyword']) ? $row['keyword'] : '' );
            $worksheet->writeString($i, $j++, html_entity_decode($row['products_description'], ENT_QUOTES, 'UTF-8'), $textFormat, TRUE);
            $worksheet->writeString($i, $j++, $row['products_model']);
            $worksheet->writeString($i, $j++, $row['manufacturer']);
            $worksheet->writeString($i, $j++, $row['image_name']);
            $names = "";
            $opt = "";

            if (isset($imageNames[$productId])) {
                $first = TRUE;
                foreach ($imageNames[$productId] AS $name) {
                    if (!$first) {
                        $names .= ",\n";
                        $opt .= ",\n";
                    }
                    $first = FALSE;
                    //$names .= $name;
                    $names .= $name['image'];
                    $opt .= $name['opt'];
                }
            }

            $worksheet->write($i, $j++, $names, $textFormat);
            $worksheet->write($i, $j++, $opt, $textFormat);
            $worksheet->write($i, $j++, $row['products_price'], $priceFormat);
            $worksheet->write($i, $j++, ($row['substract_stock'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['products_date_available'], $textFormat);
            $worksheet->write($i, $j++, ($row['products_status'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['sort_order']);
            $worksheet->write($i, $j++, $row['stock_status_id']);
            $worksheet->write($i, $j++, $row['products_tax_class_id']);
            $worksheet->write($i, $j++, $dname, $textFormat);
            $worksheet->write($i, $j++, ($row['shipping'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['related'], $textFormat);
            $worksheet->write($i, $j++, $row['tags'], $textFormat);
            $worksheet->writeString($i, $j++, $row['sku']);
            $worksheet->writeString($i, $j++, $row['upc']);
            $worksheet->write($i, $j++, $row['products_weight'], $weightFormat);
            $worksheet->writeString($i, $j++, $row['unit']);
            $worksheet->write($i, $j++, $row['length']);
            $worksheet->write($i, $j++, $row['width']);
            $worksheet->write($i, $j++, $row['height']);
            $worksheet->writeString($i, $j++, $row['length_unit']);
            $worksheet->write($i, $j++, $row['products_points']);
            $worksheet->write($i, $j++, html_entity_decode($row['meta_keywords'], ENT_QUOTES, 'UTF-8'), $textFormat);
            $worksheet->write($i, $j++, html_entity_decode($row['meta_description'], ENT_QUOTES, 'UTF-8'), $textFormat);
            $worksheet->write($i, $j++, $row['products_date_added'], $textFormat);
            $worksheet->write($i, $j++, $row['products_last_modified'], $textFormat);
            $worksheet->write($i, $j++, $row['viewed']);
            $i += 1;
            $j = 0;
        }
    }

    function populateOptionsWorksheet(&$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, $textFormat) {
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, max(strlen('product_id'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('option_name'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('type'), 10) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('value'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('base_option'), 5) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('required'), 5) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('quantity'), 4) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('subtract'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('price'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('points'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('points'), 5) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, max(strlen('weight'), 10) + 1, $priceFormat);
        $worksheet->setColumn($j, $j++, max(strlen('weight'), 5) + 1, $textFormat);


        // The options headings row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        $worksheet->writeString($i, $j++, 'option_name', $boxFormat);
        $worksheet->writeString($i, $j++, 'type', $boxFormat);
        $worksheet->writeString($i, $j++, 'value', $boxFormat);
        $worksheet->writeString($i, $j++, 'base_option', $boxFormat);
        $worksheet->writeString($i, $j++, 'required', $boxFormat);
        $worksheet->writeString($i, $j++, 'quantity', $boxFormat);
        $worksheet->writeString($i, $j++, 'subtract', $boxFormat);
        $worksheet->writeString($i, $j++, 'price', $boxFormat);
        $worksheet->writeString($i, $j++, "price\nprefix", $boxFormat);
        $worksheet->writeString($i, $j++, 'points', $boxFormat);
        $worksheet->writeString($i, $j++, "points\nprefix", $boxFormat);
        $worksheet->writeString($i, $j++, 'weight', $boxFormat);
        $worksheet->writeString($i, $j++, "weight\nprefix", $boxFormat);

        $worksheet->setRow($i, 30, $boxFormat);

        // The actual options data
        $i += 1;
        $j = 0;


        $result_rows = $this->db->fetchAll("SELECT po.product_id,(select ovd.name from r_option_value_description ovd where ovd.option_value_id=pov.base_option_value_id and ovd.language_id=1) as base_option,(select p.del from r_products p where p.products_id=po.product_id) as del, po.option_id, po.option_value AS default_value, po.required, pov.option_value_id, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, ovd.name AS option_value, ov.sort_order, od.name AS option_name, o.type FROM `r_products_option` po LEFT JOIN `r_option` o ON o.option_id=po.option_id LEFT JOIN `r_products_option_value` pov ON pov.product_option_id = po.product_option_id LEFT JOIN `r_option_value` ov ON ov.option_value_id=pov.option_value_id LEFT JOIN `r_option_value_description` ovd ON ovd.option_value_id=ov.option_value_id AND ovd.language_id='" . $languageId . "' LEFT JOIN `r_option_description` od ON od.option_id=o.option_id AND od.language_id='" . $languageId . "' ORDER BY po.product_id, po.option_id, pov.option_value_id");
        foreach ($result_rows as $row) {
            if ($row['del'] == '1' || $row['del'] == '') {
                continue;
            }
            $worksheet->setRow($i, 13);
            $worksheet->write($i, $j++, $row['product_id']);
            $worksheet->writeString($i, $j++, $row['option_name']);
            $worksheet->writeString($i, $j++, $row['type']);
            $worksheet->writeString($i, $j++, ($row['default_value']) ? $row['default_value'] : $row['option_value'] );
            $worksheet->write($i, $j++, strtolower($row['base_option']));
            $worksheet->write($i, $j++, ($row['required'] == 0) ? "false" : "true", $textFormat);
            $worksheet->write($i, $j++, $row['quantity']);
            if (is_null($row['option_value_id'])) {
                $subtract = '';
            } else {
                $subtract = ($row['subtract'] == 0) ? "false" : "true";
            }
            $worksheet->write($i, $j++, $subtract, $textFormat);
            $worksheet->write($i, $j++, $row['price'], $priceFormat);
            $worksheet->writeString($i, $j++, $row['price_prefix'], $textFormat);
            $worksheet->write($i, $j++, $row['points']);
            $worksheet->writeString($i, $j++, $row['points_prefix'], $textFormat);
            $worksheet->write($i, $j++, $row['weight'], $weightFormat);
            $worksheet->writeString($i, $j++, $row['weight_prefix'], $textFormat);

            $i += 1;
            $j = 0;
        }
    }

    function populateAttributesWorksheet(&$worksheet, &$database, $languageId, &$boxFormat, $textFormat) {
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, max(strlen('product_id'), 4) + 1);
        //$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
        $worksheet->setColumn($j, $j++, max(strlen('attribute_group'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('attribute_name'), 30) + 1);
        $worksheet->setColumn($j, $j++, max(strlen('text'), 30) + 1);
        //$worksheet->setColumn($j,$j++,max(strlen('sort_order'),5)+1);
        // The attributes headings row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'product_id', $boxFormat);
        //$worksheet->writeString( $i, $j++, 'language_id', $boxFormat  );
        $worksheet->writeString($i, $j++, 'attribute_group', $boxFormat);
        $worksheet->writeString($i, $j++, 'attribute_name', $boxFormat);
        $worksheet->writeString($i, $j++, 'text', $boxFormat);
        //$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat  );
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual attributes data
        $i += 1;
        $j = 0;
        /* $query  = "SELECT pa.*, a.attribute_group_id, ad.name AS attribute_name, a.sort_order, agd.name AS attribute_group ";
          $query .= "FROM `r_product_attribute_group` pa ";
          $query .= "LEFT JOIN `r_attribute` a ON a.attribute_id=pa.attribute_id ";
          $query .= "LEFT JOIN `r_attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id=$languageId ";
          $query .= "LEFT JOIN `r_attribute_group_description` agd ON agd.attribute_group_id=a.attribute_group_id AND agd.language_id=$languageId ";
          $query .= "WHERE pa.language_id=$languageId ";
          $query .= "ORDER BY pa.product_id, a.attribute_group_id, a.attribute_id;";
          $result = $this->db->query( $query ); */

        $result_rows = $this->db->fetchAll("SELECT pa.*,(select p.del from r_products p where p.products_id=pa.product_id) as del, a.attribute_group_id, ad.name AS attribute_name, a.sort_order, agd.name AS attribute_group FROM `r_product_attribute_group` pa LEFT JOIN `r_attribute` a ON a.attribute_id=pa.attribute_id LEFT JOIN `r_attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id='" . (int) $languageId . "' LEFT JOIN `r_attribute_group_description` agd ON agd.attribute_group_id=a.attribute_group_id AND agd.language_id='" . (int) $languageId . "' WHERE pa.language_id='" . (int) $languageId . "' ORDER BY pa.product_id, a.attribute_group_id, a.attribute_id");

        foreach ($result_rows as $row) {
            if ($row['del'] == '1' || $row['del'] == '') {
                continue;
            }
            $worksheet->setRow($i, 13);
            $worksheet->write($i, $j++, $row['product_id']);
            //$worksheet->write( $i, $j++, $languageId );
            $worksheet->writeString($i, $j++, $row['attribute_group']);
            $worksheet->writeString($i, $j++, $row['attribute_name']);
            $worksheet->writeString($i, $j++, $row['text']);
            //$worksheet->write( $i, $j++, $row['sort_order'] );
            $i += 1;
            $j = 0;
        }
    }
    
    function populateTruckDetailsWorksheet(&$worksheet, &$database, &$priceFormat, &$boxFormat, &$textFormat) {
        
 
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, strlen('Truck_Reg_No') + 1);
        $worksheet->setColumn($j, $j++, strlen('Truck_Type') + 1);
        $worksheet->setColumn($j, $j++, strlen('Tracking_Available') + 1);
        $worksheet->setColumn($j, $j++, strlen('Insurance_Available') + 1);
        $worksheet->setColumn($j, $j++, strlen('Source_Address') + 1);
        $worksheet->setColumn($j, $j++, strlen('Truck_Description') + 1);
        

        // The heading row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'Truck_Reg_No', $boxFormat);
        $worksheet->writeString($i, $j++, 'Truck_Type', $boxFormat);
        $worksheet->writeString($i, $j++, 'Tracking_Available', $boxFormat);
        $worksheet->writeString($i, $j++, 'Insurance_Available', $boxFormat);
        $worksheet->writeString($i, $j++, 'Source_Address', $boxFormat);
        $worksheet->writeString($i, $j++, 'Truck_Description', $boxFormat);
        $worksheet->setRow($i, 30, $boxFormat);

        // The actual product specials data
        $i += 1;
        $j = 0;

 
        $result_rows=Yii::app()->db->createCommand("select t.*,tt.title as truck_type from {{truck}} t left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type  where id_customer='".(int)$_GET['id']."' order by date_created desc")->QueryAll();
        //echo '<pre>';print_r($result_rows);exit;
        foreach ($result_rows as $row) {

            $worksheet->setRow($i, 13);
            $worksheet->write($i, $j++, $row['truck_reg_no']);
            $worksheet->write($i, $j++, $row['truck_type']);
            $worksheet->write($i, $j++, $row['tracking_available']==1?"Yes":"No");
            $worksheet->write($i, $j++, $row['insurance_available']==1?"Yes":"No");
            $worksheet->write($i, $j++, $row['source_address'], $textFormat);
            $worksheet->write($i, $j++, $row['description'], $textFormat);
            $i += 1;
            $j = 0;
        }
    }
    
    function populateTruckPricesWorksheet(&$worksheet, &$database, &$priceFormat, &$boxFormat, &$textFormat) {
        // Set the column widths
        $j = 0;
        $worksheet->setColumn($j, $j++, strlen('Truck_Reg_No') + 1);
        $worksheet->setColumn($j, $j++, strlen('Source_Address') + 1);
        $worksheet->setColumn($j, $j++, strlen('Source_State') + 1);
        $worksheet->setColumn($j, $j++, strlen('Destination_Address') + 1);
        $worksheet->setColumn($j, $j++, strlen('Destination_State') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('Date_Available'), 19) + 1, $textFormat);
        $worksheet->setColumn($j, $j++, strlen('Goods_Type') + 1);
        $worksheet->setColumn($j, $j++, max(strlen('Price'), 10) + 1, $priceFormat);
        
        // The heading row
        $i = 0;
        $j = 0;
        $worksheet->writeString($i, $j++, 'Truck_Reg_No', $boxFormat);
        $worksheet->writeString($i, $j++, 'Source_Address', $boxFormat);
        $worksheet->writeString($i, $j++, 'Source_State', $boxFormat);
        $worksheet->writeString($i, $j++, 'Destination_Address', $boxFormat);
        $worksheet->writeString($i, $j++, 'Destination_State', $boxFormat);
        $worksheet->writeString($i, $j++, 'Date_Available', $boxFormat);
        $worksheet->writeString($i, $j++, 'Goods_Type', $boxFormat);
        $worksheet->writeString($i, $j++, 'Price', $boxFormat);
        
        $worksheet->setRow($i, 30, $boxFormat);

        $i += 1;
        $j = 0;

        $result_rows=Yii::app()->db->createCommand("select trp.*,t.truck_reg_no,gt.title as goods_type from {{truck_route_price}} trp inner join {{truck}} t on trp.id_truck=t.id_truck left join {{goods_type}} gt on trp.id_goods_type=gt.id_goods_type  where t.id_customer='".(int)$_GET['id']."' order by trp.date_created desc")->QueryAll();
        foreach ($result_rows as $row) {

            $worksheet->setRow($i, 13);
            $worksheet->write($i, $j++, $row['truck_reg_no']);
            $worksheet->write($i, $j++, $row['source_address']);
            $worksheet->write($i, $j++, $row['source_state']);
            $worksheet->write($i, $j++, $row['destination_address']);
            $worksheet->write($i, $j++, $row['destination_state']);
            $worksheet->write($i, $j++, $row['date_available'], $textFormat);
            $worksheet->write($i, $j++, $row['goods_type'], $textFormat);
            $worksheet->write($i, $j++, $row['price']);
            $i += 1;
            $j = 0;
        }
    }

    protected function clearSpreadsheetCache() {
        $files = glob($_SERVER['DOCUMENT_ROOT'] . "/cache" . 'Spreadsheet_Excel_Writer' . '*');
        //$files = glob('C://xampp/htdocs/standalone' . "/cache" . 'Spreadsheet_Excel_Writer' . '*');
        
        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    @unlink($file);
                    clearstatcache();
                }
            }
        }
    }

    function exeQuery($data) {
        try {
            $this->db->query($data['query']);
        } catch (Zend_Db_Exception $e) {
            $_SESSION['EXCEL_ERROR_MESSAGE'][] = $data['warning'];
        }
    }

    
    function download() {
        ob_end_clean();
    // We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
        //chdir(@constant('DOCUMENT_ROOT') . '/library/pear');
        chdir($_SERVER['DOCUMENT_ROOT'] . '/easygaadi.com/osadmin/remove/library/pear');
        require_once "Spreadsheet/Excel/Writer.php";
        //chdir('../../application');
        chdir('../extensions');
        //exit($_SERVER['DOCUMENT_ROOT']);
        // Creating a workbook
        $workbook = new Spreadsheet_Excel_Writer();
        $workbook->setTempDir($_SERVER['DOCUMENT_ROOT'] . '/easygaadi.com/osadmin/cache');
        $workbook->setVersion(8); // Use Excel97/2000 Format
        $priceFormat = & $workbook->addFormat(array('Size' => 10, 'Align' => 'right', 'NumFormat' => '######0.00'));
        $boxFormat = & $workbook->addFormat(array('Size' => 10, 'vAlign' => 'vequal_space'));
        $weightFormat = & $workbook->addFormat(array('Size' => 10, 'Align' => 'right', 'NumFormat' => '##0.00'));
        $textFormat = & $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@"));
        // sending HTTP headers
        $workbook->send('download_'.date("Y-m-d h:i:s").'.xls');
        //$workbook->send('download.xls');
        // Creating the Truck Details worksheet
        $worksheet = & $workbook->addWorksheet('Truck_Details');
        $worksheet->setInputEncoding('UTF-8');
        //echo '<pre>';print_r($worksheet);exit;
        $this->populateTruckDetailsWorksheet($worksheet, $database, $priceFormat, $boxFormat, $textFormat);
        $worksheet->freezePanes(array(1, 1, 1, 1));
        
        
        // Creating the Truck Prices worksheet
        $worksheet = & $workbook->addWorksheet('Truck_Prices');
        $worksheet->setInputEncoding('UTF-8');
        $this->populateTruckPricesWorksheet($worksheet, $database, $priceFormat, $boxFormat, $textFormat);
        $worksheet->freezePanes(array(1, 1, 1, 1));

        // Let's send the file

        $workbook->close();

        // Clear the spreadsheet caches
        $this->clearSpreadsheetCache();
        exit;
    }
    
        function validateLead(&$reader) {
            $expectedCategoryHeading = array("Type", "FullName", "Mobile", "Email", "Company", "City","State","Address","Operating Source City","Office No","Lead Source","Alt Mobile 1","Alt Mobile 2","Alt Mobile 3");
            $data = & $reader->getSheet(0);
            //echo '<pre>';print_r($data);exit;
            return $this->validateHeading($data, $expectedCategoryHeading);
        }
	
	function validateUploadLead(&$reader) {
        if ($reader->getSheetCount() != 3) {
            //echo "Work Sheet Missing.3 Work Sheets expected!!";
            $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Work Sheet Missing.3 Work Sheets expected!!";
            return FALSE;
        }

        if (!$this->validateLead($reader)) {
            //echo "Invalid Truck Details Header!!";
            $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Invalid Lead Header!!";
            return FALSE;
        }
 
        return TRUE;
    }

function uploadLead($filename) {
        error_reporting(E_ERROR);
        unset($_SESSION['EXCEL_ERROR_MESSAGE']);

        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 1800);
        //set_time_limit( 60 );

        chdir($_SERVER['DOCUMENT_ROOT'] . '/easygaadi.com/osadmin/protected/extensions/library/PHPExcel');
        require_once('Classes/PHPExcel.php' );
        chdir('../extensions');
        $inputFileType = PHPExcel_IOFactory::identify($filename);

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($filename);

        $ok = $this->validateUploadLead($reader);

        if (!$ok) {
            return FALSE;
        }
		$type=array('Truck Owner'=>'T','Cagent'=>'C','Load Owner'=>'L','Transporter'=>'TR');
        $ok = $this->uploadLeadDetails( $reader, $type );

          if (!$ok) {
          return FALSE;
          } 
        
        chdir('../../..');
        return $ok;
}

function uploadLeadDetails(&$reader, $type) {
        $data = $reader->getSheet(0);
        $i = 0;
        $discounts = array();
        $k = $data->getHighestRow();
        $isFirstRow = TRUE;

        for ($i = 0; $i < $k; $i+=1) {
            if ($isFirstRow) {
                $isFirstRow = FALSE;
                continue;
            }
            /*$Truck_Reg_No = trim($this->getCell($data, $i, 1));
            $Truck_Type = trim($this->getCell($data, $i, 2));
            $errorDetails="";
            $errorDetails="<br/>Truck Reg No:".trim($this->getCell($data, $i, 1))." | Truck Type ".$truckTypes[strtolower(trim($Truck_Type))]." | Tracking Available ".trim($this->getCell($data, $i, 3))." | Insurance Available ".trim($this->getCell($data, $i, 4))." | Source Address ".trim($this->getCell($data, $i, 5))." | Source State ".trim($this->getCell($data, $i, 6));
            
            if ($Truck_Reg_No == "") {
                $_SESSION['EXCEL_ERROR_MESSAGE'][] = "Truck Details Tab:Truck Reg No  is missing!!".$errorDetails;
                continue;
            }


 

            $Insurance_Available = $this->getCell($data, $i, 4, 'No');
            //echo '<br/>insurance available '.$Insurance_Available;
            if ($Insurance_Available != 'Yes' && $Insurance_Available != 'No') {
                $Insurance_Available = 'No';
            }

             $Source_Address = $this->getCell($data, $i, 5);
            $Source_State = $this->getCell($data, $i, 6);*/


            $discounts[$i] = array();
            $discounts[$i]['type'] = $type[trim($this->getCell($data, $i, 1))];
            $discounts[$i]['fullname'] =trim($this->getCell($data, $i, 2));
            $discounts[$i]['mobile'] = trim($this->getCell($data, $i, 3));
            $discounts[$i]['email'] = trim($this->getCell($data, $i, 4));
            $discounts[$i]['company'] = trim($this->getCell($data, $i, 5));
            $discounts[$i]['city'] = trim($this->getCell($data, $i, 6));
            $discounts[$i]['state'] = trim($this->getCell($data, $i, 7));
            $discounts[$i]['address']=trim($this->getCell($data, $i, 8));
			$discounts[$i]['operating_source_city']=trim($this->getCell($data, $i, 9));
			$discounts[$i]['landline']=trim($this->getCell($data, $i, 10));
			$discounts[$i]['lead_source']=trim($this->getCell($data, $i, 11));
			$discounts[$i]['alt_mobile_1']=trim($this->getCell($data, $i, 12));
			$discounts[$i]['alt_mobile_2']=trim($this->getCell($data, $i, 13));
			$discounts[$i]['alt_mobile_3']=trim($this->getCell($data, $i, 14));
        }
        //echo 'here<pre>';print_r($discounts);      echo '</pre>';
        //exit;        
        return $this->storeLeadDetailsIntoDatabase($discounts);
    }

	function storeLeadDetailsIntoDatabase(&$discounts) {
        //echo '<pre>';print_r($discounts);echo '</pre>';
        foreach ($discounts as $discount) {
            $row=Admin::model()->getLeastAssigmentId();
            $cObj=new Customer;
            $cObj->attributes=$discount;
            $cObj->islead=1;
            $cObj->date_created=date('Y-m-d');
            $cObj->save(false);

            $clObj=new Customerlead();
            $clObj->id_customer=$cObj->id_customer;	
            $clObj->id_admin_created=Yii::app()->user->id;
            $clObj->id_admin_assigned=$row['id_admin'];
            $clObj->lead_status='Initiated';
            $clObj->lead_source=$discount['lead_source'];
            $clObj->save(false);

            $model['cla'] = new Customerleadassignment();
            $model['cla']->id_customer = $cObj->id_customer;
            $model['cla']->message = 'Initial Assigment';
            $model['cla']->status = 'Assigned';
            $model['cla']->id_admin_from = Yii::app()->user->id;
            $model['cla']->id_admin_to = $row['id_admin'];
            $model['cla']->save(false);
        }
        //Yii::app()->db->createCommand("COMMIT;")->query();
        return TRUE;
        //exit;        
    }

}?>