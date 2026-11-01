 <?php
/**
* Database Class
*/
class Functions extends cn
{
    use operation;
	public $lastid;
	public $rowcount;
	public $secured;

	 
	function __construct()
	{
		parent:: __construct();
	}
	public function insert($sql)
	{

		$ok = 1;
		if ($ok == 1) {
			$stmt = $this->db->prepare($sql);
			 $cond =  $stmt->execute();
			 $this->lastid = $this->db->lastInsertid();
			 if($cond == 1){
			 	return true;
			 }
			 return false;
		}else{
			return false;
		}
		
	}
	public function edit($sql)
	{
		$stmt= $this->db->prepare($sql);
		return $stmt->execute();
	}

	public function getdata($sql)
	{
		$stmt= $this->db->prepare($sql);
	    $stmt->execute();
		$this->rowcount = $stmt->rowCount();
		return $stmt->fetchAll();
	}
	public function row_count($sql)
	{
		$stmt= $this->db->prepare($sql);
	  $stmt->execute();
		return $stmt->rowCount();
	}
	public function qgetdata($sql,$col="",$value=0,$value_return = '')
	{

		$sql = "select * from $sql";
		if ($col != "") {
			$sql .= "  where $col = $value";
		}
		
		
		$stmt= $this->db->prepare($sql);
	    $stmt->execute();
		$this->rowcount = $stmt->rowCount();
		if ($value_return != '') {
			$d = $stmt->fetchAll();

			if (isset($d[0])) {
				return $d[0][$value_return];
			}


		}else{
			return $stmt->fetchAll();
		}
		
	}



	public function delete($sql)
	{
		$stmt= $this->db->prepare($sql);
		return $stmt->execute();
	}
	public function aut($sql,$value="")
	{
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		if ($value == "") {
			return $stmt->rowCount();
		}
		else
		{
			return true;
		}
	}
	public function qaut($table,$colm,$value)
	{
		$sql = 'select * from $table where $colm ='.$value;
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->rowCount();

	}

	
	public function adddata($tablename,$data){
		$ok = 1;
		if ($ok == 1) {
			$keys = implode(",", array_keys($data));
			$k = ':'.implode(", :",array_keys($data));

			$sql = "insert into $tablename($keys) values($k)";
			$stmt = $this->db->prepare($sql);
			foreach ($data as $key => $value) {
				$stmt->bindValue($key,$value);
			}
			$cond = $stmt->execute();
			$this->lastid = $this->db->lastInsertId();
			if($cond == 1){
				return true;
			}
			else{
				$this->Error = $stmt->errorInfo();
			}
		}else{
			return false;
		}
		


	}
	public function qedit($tablename,$array,$idtype,$val,$oqury=''){
		
		$keys = null;
		if (!is_numeric($val)) {
			$val = '"'.$val.'"';
		}
		foreach ($array as $key=>$value) {
			$keys .= ", $key = :$key";
		}
		$keyss = ltrim($keys, ",");
		$sql = "update $tablename set $keyss where ".$idtype." =".$val;
		if ($oqury != '') {
			$sql .= $oqury;
		}
		$stmt = $this->db->prepare($sql);
		foreach ($array as $key => $value) {
			$stmt->bindValue($key,$value);
		}
		return $stmt->execute();
	}




public function rpost($value,$rep='')
{
	$ar = array();
	foreach ($value as $key => $val) {
		if (isset($_POST[$val])) {
			$pkey = $val;
			if ($rep != '') {
				$pkey = str_replace($rep, '', $val);
			}
			$ar[$pkey] = $_POST[$val];
		}
	}
	return $ar;
}

public function st($starts,$ends,$v,$fil=0){

  if ($fil == 0) {
  	$starts = 'data-spec="'.$starts.'">';
  	$ends = '</'.$ends.'>';
  }
  if (strpos($v, $starts) !== false) {
  	  $start = strpos($v, $starts);
	  $end = strpos($v, $ends, $start);
	  $length = $end-$start;
	  $output = substr($v, $start, $length);
	  
	  if ($fil == 0) {
	  	return strip_tags(str_replace($starts, '', $output));
	  }
	  else
	  {
	  	return $output;
	  }
  }
  
}


public function check_token()
{
	if (isset($_POST['token']) and isset($_SESSION['token'])) {
		if ($_SESSION['token'] == $_POST['token']) {
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}
public function unset_ses($value)
{
	if (isset($_SESSION[$value])) {
		unset($_SESSION[$value]);
	}
}


public function dom($code,$tag)
{
   $dom = new DOMDocument;
   @$dom->loadHTML($code);
  return $tag = $dom->getElementsByTagName($tag);
}
public function getbetweenall($str, $startDelimiter, $endDelimiter) {
  $contents = array();
  $startDelimiterLength = strlen($startDelimiter);
  $endDelimiterLength = strlen($endDelimiter);
  $startFrom = $contentStart = $contentEnd = 0;
  while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
    $contentStart += $startDelimiterLength;
    $contentEnd = strpos($str, $endDelimiter, $contentStart);
    if (false === $contentEnd) {
      break;
    }
    $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
    $startFrom = $contentEnd + $endDelimiterLength;
  }
  return $contents;
}



public function replace_between($str, $needle_start, $needle_end, $replacement) {
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);

    $pos = strpos($str, $needle_end, $start);
    $end = $pos === false ? strlen($str) : $pos;

    return substr_replace($str, $replacement, $start, $end - $start);
}



public function paginsl($totalrow,$nextvalue,$staypost,$page)
{
$paginsl = '';
if (is_numeric($totalrow) and  is_numeric($staypost) and is_numeric($nextvalue)) {

$tpage = 0;$i = 1;
$paginsl .= '<div class="pagination">';




$paginsl .= '<p style="float:left;margin:9px 6px 9px 0;">';

if ($nextvalue == 0) {
  if ($totalrow == 0) {
    $paginsl .= 0;
  }
  else
  {
    $paginsl .= 1;
  }
  
}
else
{
  $paginsl .= ($nextvalue * $staypost);
}

  $paginsl .= '-';
if ($totalrow >= $staypost) {
  
  if (($staypost + $staypost * $nextvalue) >= $totalrow) {
    $paginsl .= $totalrow;
  }
  else
  {
    $paginsl .= $staypost + $staypost * $nextvalue;
  }

}
else
{
  $paginsl .= $totalrow;
}

$paginsl .= '/'.floor($totalrow).'</p>';


if ($nextvalue < 1) {
	$paginsl .= '<a style="background:#e1e1e1d1;">&laquo;</a>';
  }
  else
  {
    
    if (($nextvalue - 1) == 0) {
      $next = '';
    }
    else
    {
      $next = ($nextvalue - 1);
    }

    $prepage = $page.'/'.$next;
    if ($this->getm('start') and $this->getm('end')) {
    	$prepage = $prepage.'?start='.$this->getm('start').'&end='.$this->getm('end').'&cus='.$this->getm('cus');
    }
    $paginsl .= '<a href="'.appsConfig::Link($prepage).'">&laquo;</a>';

  }





if (($staypost * ($nextvalue + 1)) < $totalrow) {
	$npage = $page.'/'.($nextvalue + 1);
	if ($this->getm('start') and $this->getm('end')) {
    	$npage = $npage.'?start='.$this->getm('start').'&end='.$this->getm('end').'&cus='.$this->getm('cus');
    }

  $paginsl .= '<a href="'.appsConfig::Link($npage).'">&raquo;</a>';
  
}else{
	$paginsl .= '<a style="background:#e1e1e1d1;">&raquo;</a>';
}







$paginsl .= '</ul></div>';
}

echo preg_replace('~[\r\n]+~', '', $paginsl);
}


 

public function str_replace_first($from, $to, $content)
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}


public function readjson($loc,$con='')
{
	$db = new Database();
	if ($loc != '' and file_exists($loc) and filesize($loc) > 0) {
		$file = fopen($loc, "r") or die("Unable to open file!");
		$data = fread($file,filesize($loc));
		fclose($file);
		if ($con != '') {
			$decode = $db->basesec($data,'decode');
		}else{
			$decode = json_decode($data,true);
		}
		
		return $decode;
	}else{
		return false;
	}
}



public function texttofile($loc='',$val='')
{
	if ($loc != '' and $val != '') {
		$file = fopen($loc, "w") or die("Unable to open file!");
		$write = fwrite($file, $val);
		fclose($file);
		return true;
	}else{
		return false;
	}
}

public function filetotext($loc='')
{
	if ($loc != '') {
		$file = fopen($loc, "r") or die("Unable to open file!");
		if(filesize($loc) > 0){
			$data = fread($file,filesize($loc));
		fclose($file);
		return $data;
	}else{
		return false;
	}
		
	}else{
		return false;
	}
}



public function writejson($loc='',$array=array())
{
	$lv = strstr($loc, '/',true);
	if ($loc != '' and file_exists($loc) and $lv == 'idata') {
		$file = fopen($loc, "w") or die("Unable to open file!");

		if (!empty($array) and is_array($array)) {
			$en = json_encode($array);
		}else{
			if (is_array($array)) {
				$en = '';
			}else{
				$en = $array;
			}
			
		}
		$write = fwrite($file, $en);
		fclose($file);
		return true;
	}else{
		return false;
	}
}

	
public function getfile($loc='')
{
	$ar = array();
	$ar = scandir($loc);
	$ar = array_slice($ar, 2);
	return $ar;
	
}



public function getm($mname,$url='')
{
	if ($url == '') {
		$url = $_SERVER['REQUEST_URI'];
	}
	$parts = parse_url($url);
	if (isset($parts['query'])) {
		parse_str($parts['query'], $query);
		if (isset($query[$mname])) {
			return $query[$mname];
		}else{
			return false;
		}
	}else{
		return false;
	}
	
	
}
public function getip(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}




public function getid($url='',$sym='_')
{
	$pos = strrpos($url, $sym);
	$id = $pos === false ? $url : substr($url, $pos + 1);
	return $id;
}


public function logout()
{
	if (isset($_SESSION)) {
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$value]);
		}
	}
}



public function render_body($db){
    $pg = 'dashboard';
    if(isset($_GET['e'])){
		$url = $_GET['e'];
		$url = rtrim($url,"/");
		$url = explode("/", $url);
        if(file_exists('page/'.$url[0].'.php')){
            $pg = $url[0];
        }else{
             $pg = '404';
        }
    }
    $permission = explode(',',$this->getonecol('access', 'staff', 'id', $_SESSION['user_id']));

    include_once 'page/header.php';
    include_once 'page/'.$pg.'.php';
    include_once 'page/footer.php';
    
}




public function left_menu(){
    $ar = [
        'dashboard' => [
            'name'      => 'ড্যাশবোর্ড',
            'icon'      => 'fa fa-th-large',
            'url'       => 'dashboard',
            'others'=>[
                'new_req'=>'বর্তমানে অপেক্ষাধীন চাহিদা',
                'pre_app_req'=>'অনুমোদিত চাহিদার সংখ্যা',
                'total_pro'=>'মোট অস্থায়ী পণ্যের সংখ্যা',
                'stock'=>'বর্তমান স্টক',
                'recent_req'=>'নতুন দ্রব্যাদির চাহিদা পত্র',
                'need_purchase'=>'ক্রয় করা প্রয়োজন',
                'not_received'=>'ইউজার কতৃক অগৃহীত',
                'total_delivered'=>'সর্বমোট বিতরণ সংখ্যা',
            ]
        ],
        'hr'  => [
            'name'      => 'এইচআর অ্যাডমিন',
            'icon'      => 'fa fa-user',
            'url'       => 'add_staff',
            'option'=>[
                'type_manage'=>['add','view','edit','delete'],
                'section_manage'=>['add','view','edit','delete'],
                'designation_manage'=>['add','view','edit','delete'],
                'manage_staff'=>['add','view','edit','delete'],
                'room_manage'=>['add','view','edit','delete'],
            ],
            'sub_menu'  => [
                'টাইপ ম্যানেজমেন্ট'          => 'type_manage',
                'শাখা ব্যবস্থাপনা'          => 'section_manage',
                'কক্ষ ব্যবস্থাপনা'          => 'room_manage',
                'পদবী ব্যবস্থাপনা'          => 'designation_manage',
                'কর্মচারী ব্যবস্থাপনা'       => 'manage_staff'
            ]
        ],
        // 'transfer'  => [
        //     'name'      => 'কর্মচারী পরিবর্তন',
        //     'icon'      => 'fa fa-user',
        //     'url'       => 'transfer',
        //     'option'=>[
        //         'transfer'=>['view'],
        //         'transfer_list'=>['view','delete'],
        //     ],
        //     'sub_menu'  => [
        //         'কর্মচারী পরিবর্তন করুন'          => 'transfer',
        //         'কর্মচারী পরিবর্তন তালিকা'          => 'transfer_list',
        //     ]
        // ],
        'product'  => [
            'name'      => 'পণ্য ব্যবস্থাপনা',
            'icon'      => 'fa fa-product-hunt',
            'url'       => 'add_product',
            'option'=>[
                //'add_product'=>['view'],
                'perm_pro_list'=>['add','view','edit','delete'],
                'temp_pro_list'=>['add','view','edit','delete'],
                'manage_brand'=>['add','view','edit','delete'],
                'manage_category'=>['add','view','edit','delete'],
                'unit_type'=>['add','view','edit','delete'],
                ],
            'sub_menu'  => [
                // 'পণ্য যোগ করুন'          => 'add_product',
                'স্থায়ী সম্পদের তালিকা'       => 'perm_pro_list',
                'অস্থায়ী পণ্যের তালিকা'       => 'temp_pro_list',
                'ব্র্যান্ড  ম্যানেজমেন্ট'       => 'manage_brand',
                'ক্যাটাগরির ম্যানেজমেন্ট'       => 'manage_category',
                'ইউনিট টাইপ'       => 'unit_type',
            ]
        ],
        'Requisition'  => [
            'name'      => 'চাহিদা ব্যবস্থাপনা',
            'icon'      => 'fa fa-registered',
            'url'       => 'add_requisition',
            'option'=>[
                'perm_add_requisition'=>['view'],
                'temp_add_requisition'=>['view'],
                'manage_requisition'=>['view','edit','delete'],
                ],
            'sub_menu'  => [
                'স্থায়ী পণ্যের চাহিদা যোগ করুন'          => 'perm_add_requisition',
                'অস্থায়ী পণ্যের চাহিদা যোগ করুন'          => 'temp_add_requisition',
                'বিস্তারিত চাহিদা'       => 'manage_requisition',
            ]
        ],
        'Sales'  => [
            'name'      => 'সম্পদ নিষ্পত্তি',
            'icon'      => 'fa fa-shopping-cart',
            'url'       => 'supplier_management',
            'option'=>[
                'manage_sales'=>['add','view','edit','delete'],
                'add_sales'=>['view'],
                'customer'=>['view','edit','delete'],
                ],
            'sub_menu'  => [
                'ক্রেতা ব্যবস্থাপনা'       => 'customer',
                'নিষ্পত্তি যোগ করুন'          => 'add_sales',
                'বিস্তারিত নিষ্পত্তি'          => 'manage_sales',
           
                
            ]
        ],
        'Purchase'  => [
            'name'      => 'ক্রয় ব্যবস্থাপনা',
            'icon'      => 'fa fa-shopping-cart',
            'url'       => 'supplier_management',
            'option'=>[
                'supplier_management'=>['add','view','edit','delete'],
                'add_purchase'=>['view'],
                'manage_purchase'=>['view','edit','delete'],
                ],
            'sub_menu'  => [
                // 'সরবরাহকারী ব্যবস্থাপনা'          => 'supplier_management',
                'ক্রয় যোগ করুন'          => 'add_purchase',
                'বিস্তারিত ক্রয়'       => 'manage_purchase',
                
            ]
        ],
        // 'Product Transfer'  => [
        //     'name'      => 'পণ্য স্থানান্তর',
        //     'icon'      => 'fa fa-shopping-cart',
        //     'url'       => 'product_transfer_list',
        //     'option'=>[
        //         'product_transfer'=>['view'],
        //         'product_transfer_list'=>['view'],
        //         ],
        //     'sub_menu'  => [
        //         'পণ্য স্থানান্তর করুন'          => 'product_transfer',
        //         'পণ্য স্থানান্তর তালিকা'       => 'product_transfer_list',
                
        //     ]
        // ],
        // 'Payment'  => [
        //     'name'      => 'প্রদান',
        //     'icon'      => 'fa fa-money',
        //     'url'       => 'manage_payment',
        //     'option'=>[
        //         'add_employee'=>['view'],
        //         'employee'=>['view','edit','delete'],
        //         'deleted_employee'=>['view'],
        //         ],
        //     'sub_menu'  => [
        //         'পেমেন্ট করুন'          => 'add_payment',
        //         'অর্থপ্রদান পরিচালনা করুন'       => 'manage_payment',
                
        //     ]
        // ],
        'Report'  => [
            'name'      => 'রিপোর্ট',
            'icon'      => 'fa fa-bar-chart',
            'url'       => 'report',
            'option'=>[
                'staff_report'=>['view'],
                'perm_pro_req_report'=>['view'],
                'perm_pro_pur_req_distribute'=>['view'],
                'purchase_report'=>['view'],
                'perm_req_report'=>['view'],
                'temp_req_report'=>['view'],
                'purchase_report_item_wise'=>['view'],
                'requisition_report_item_wise'=>['view'],
                'req_deliverd_report'=>['view'],
                'perm_stock_report'=>['view'],
                'temp_stock_report'=>['view'],
                'act_stock_report'=>['view'],
            ],
            'sub_menu'  => [
                'কর্মচারী শাখা অনুসারে'  => 'staff_report',
                'স্থায়ী সম্পদের আইটেমওয়ারী বিতরণ'  => 'perm_pro_req_report',
                'স্থায়ী সম্পদের মজুদ ও বিতরণ'          => 'perm_pro_pur_req_distribute',
                'ক্রয় রিপোর্ট'                      => 'purchase_report',
                'স্থায়ী সম্পদের চাহিদা রিপোর্ট'        => 'perm_req_report',
                'অস্থায়ী পণ্যের চাহিদা রিপোর্ট'        => 'temp_req_report',
                'আইটেম অনুযায়ী ক্রয় রিপোর্ট'       => 'purchase_report_item_wise',
                'পণ্য গ্রহণ রিপোর্ট'                 => 'requisition_report_item_wise',
                'পণ্য বিতরন রিপোর্ট'                 => 'req_deliverd_report',
                'স্থায়ী সম্পদের স্টক রিপোর্ট'           => 'perm_stock_report',
                'অস্থায়ী পণ্যের স্টক রিপোর্ট'         => 'temp_stock_report',
                'স্থায়ী পণ্যের প্রকৃত স্টক রিপোর্ট'      => 'act_stock_report',
            ]
        ],
        'Settings'  => [
            'name'      => 'সেটিংস',
            'icon'      => 'fa fa-cog',
            'url'       => 'report',
            'option'=>[
                'company_setting'=>['view'],
                'financial_year'=>['view'],
                'change_pass'=>['view'],
                ],
            'sub_menu'  => [
                'কোম্পানি সেটিংস'          => 'company_setting',
                'অর্থ বছর সেট করা'          => 'financial_year',
                'পাসওয়ার্ড পরিবর্তন করুন'          => 'change_pass',
            ]
        ]
    ];
    return $ar;
}


public function rpv($value)
{
	if (isset($_POST[$value])) {
		return $_POST[$value];
	}else{
		return '';
	}
}


public function redirect($pg)
{
    header("Location:".$pg);
    exit();
}

public function refpg()
{
    return $_SERVER['HTTP_REFERER'];
}
public function en_bn($num)
{
    return str_replace(['0','1','2','3','4','5','6','7','8','9'],['০','১','২','৩','৪','৫','৬','৭','৮','৯'],$num);
}
public function bn_en($num)
{
    return str_replace(['০','১','২','৩','৪','৫','৬','৭','৮','৯'],['0','1','2','3','4','5','6','7','8','9'],$num);
}
public function domain()
{
    return 'https://estore.sscuit.com/';
}
public function setdate($date)
{
    return date('d M, Y', strtotime($date));
}


public function empty_check($values_ar,$array,$red=true)
{   
    if(is_array($values_ar)){
        foreach($values_ar as $key => $v){
            if(isset($array[$key])){
                if(trim($array[$key]) == ''){
                    $_SESSION['msg'] = $v;
                    if($red){
                        $this->redirect($this->refpg());
                    }
                    
                }
            }
        }
    }
}

public function getonecol($col, $tbl, $comCol, $comVal) {
    $sql = "SELECT $col FROM $tbl WHERE $comCol='$comVal'";
    foreach($this->getdata($sql) as $v){
        if (isset($v[$col])) {
          return $v[$col];
        }
    }
}
public function cdate($date="Y-m-d")
{
	$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
  return $dt->format($date);
}






public function product_type()
{
 return [1=>'স্থায়ী',2=>'অস্থায়ী'];
}






public function get_user_id()
{
    if(isset($_SESSION['user_id'])){
        return $_SESSION['user_id'];
    }else{
        return 0;
    }
 
}


public function head($hotline='')
{
    $value = $this->getdata('select * from company_setting where id = 1')[0];
    return '
    <div class="position-relative" style="position:relative">
        <div class="h4 text-center" style="font-size: 20px;">'.$value['company_name'].'</div>
                <div class="h4 text-center" style="font-size: 15px;">শিক্ষা মন্ত্রণালয়</div>

        <div class="h4 text-center" style="font-size: 15px;">কারিগরি ও মাদ্রাসা শিক্ষা বিভাগ</div>
        <div class="h4 text-center" style="font-size: 13px;">www.tmed.gov.bd</div>
        
        <div class="text-center">'.html_entity_decode($value['address']).'</div>
        <img src="image/logo.png" style="height: 70px;position: absolute;top: 0;left: 0;bottom: 0;" alt="Logo">
    </div>';

}



public function is_login()
{
    if(isset($_SESSION['user_id'])){
        return true;
    }else{
        return false;
    }
}

public function uid()
{
    if(isset($_SESSION['user_id'])){
       return $_SESSION['user_id']; 
    }
}

public function is_super()
{
    if(isset($_SESSION['type_id'])){
        if($_SESSION['type_id'] == 1){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

public function is_section()
{
    if(isset($_SESSION['user_section_id'])){
        if($_SESSION['user_section_manager'] == 1){
            return $_SESSION['user_section_id'];
        }else{
            return false;
        }
    }else{
        return false;
    }
}




public function is_staff(){
    if($this->is_section() or $this->is_super() or $this->is_stock()){
        return false;
    }else{
        return true;
    }
}



public function get_financial_id($date){
 $sql = 'select * from financial_year where start <= "'.$date.'" and end >= "'.$date.'" limit 0,1';
 foreach($this->getdata($sql) as $v){
     return $v['id'];
 }
}


public function req_status($status)
{
   $d = '';
   if($status == 1){
       $d = '<span class="badge rounded-pill bg-success text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">বিতরনকৃত</span>';
   }elseif($status == 2){
       $d = '<span class="badge rounded-pill bg-warning text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;color: black !important;">চূড়ান্ত অনুমোদনের  অপেক্ষায়</span>';
   }elseif($status == 3){
       $d = '<span class="badge rounded-pill bg-info text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">অনুমোদিত</span>';
   }elseif($status == 5){
       $d = '<span class="badge rounded-pill bg-danger text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">বাতিল</span>';
   }else{
       $d = '<span class="badge rounded-pill bg-danger text-light px-2 py-1" style="padding: 2px 6px;border-radius: 6px;">প্রক্রিয়াধীন</span>';
   }
   
   return $d;
}


public function get_financial_year($date){
    $fid = $this->get_financial_id($date);
    foreach($this->getdata('select * from financial_year where id ='.$fid) as $v){
        return date('Y', strtotime($v['start'])).'-'.date('Y', strtotime($v['end']));
    }
}


public function get_req_id($cus_id,$finance){
    return $cus_id.'-'.$finance;
}

public function is_stock(){
    if(isset($_SESSION['type_id'])){
        if($_SESSION['type_id'] == 3){
            return true;
        }
    }
}

public function image_upload($name,$loc,$limit=9900000)
{
// 	$im = 0;$image = '';
// 	if (isset($_POST[$name])) {
// 	 	$image = $_POST[$name];
// 	}
// 	if (isset($_FILES[$name]["name"])) {
// 	    if ($_FILES[$name]["name"] != '') {
// 	      if ($_FILES[$name]["size"] < $limit) {
// 	        $image = strtotime(date('H:i:s')).'_'.$_FILES[$name]["name"];
// 	        $image = str_replace(array(' ','&','"',"'",'/'),array('-',''), $image);
// 	        $sp = $_FILES[$name]['tmp_name'];
// 	        $dp = $loc.$image;
// 	        if (file_exists($loc)) {
// 	       //   move_uploaded_file($sp, $dp);
// 	          $im = 1;
// 	        }
// 	      }else{
// 	        $msg = 'Image size too high. Maximum image size 150k';
// 	        $im = 0;
// 	        if (isset($_POST[$name])) {
// 			 	$image = $_POST[$name];
// 			}
// 	      }
// 	    }
// 	 }
	 
// 	return $image;
}

public function get_stock($pro)
{
$sql = 'select sum(
                CASE 
                    WHEN type = "pur" then qty
                    WHEN type = "opening_stock" then qty
                    WHEN status = 1 and type = "req" then - app_qty
                END
                ) as stock
    from invoice_details where product_id ='.$pro;
    foreach($this->getdata($sql) as $v){
        return $v['stock'];
    }
    
}

public function get_digit($v,$num='2'){
    if($v <= 9){
        $v = '0'.$v;
    }
    return $v;
}

public function idtoarray($v){
    return explode('-',$v);
}

public function method(){
    return ['নগদ ক্রয়','RFQ','OTM','DTM','LTM'];
}


public function gen_bitron_code($code,$qty,$year,$start=1,$sec=0){
    $ar = [];
    for ($i = $start; $i < $qty; $i++) {
        $final_code = $code.'-'.$this->get_digit($i).'/'.$year;
        $ar[] = ['code'=>$final_code,'staff'=>$sec];
    }
    
    return $ar;
}




public function get_type($v){
    return str_replace(['perm','temp','1','0'],['স্থায়ী','অস্থায়ী','স্থায়ী','অস্থায়ী'],$v);
}





}
?>