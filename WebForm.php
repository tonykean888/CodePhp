<?php
class WebForm {
	private $form;
	private $fields;
	private $data;
	private $not_required;
	
	function __construct($form,$filed,$data=NULL) {
		if(is_string($form) && is_string($fileds)){
			$this->form =  $form;
			$this->fieles = $fields;
						
		}else{
			throw new Exception("กรุณาระบุ ชื่อไฟล์ทั้ง 2 ให้ถูกต้อง");
			
		}
		
		if($data==NULL || is_array($data)){
			$this->data = $data;
		}else{
			throw new Exception("ชนิดของข้อมูลไม่ถูกต้อง กรุณาลองใหม่");
		}
	}
	
	function setFieldsNotRequired($not_required){
		if(!is_array($not_required)){
			throw new Exception("ชนิดของข้อมูลไม่ถูกต้อง กรุณาลองใหม่");
		}else{
			$this->not_required = $not_required;
		}
	}
	
	function displayForm(){
		@extract($this->data);
		include($this->fields);
		include($this->form);
	}
	
	function getAllFields(){
		return $this->data;
	}
	
	function checkForBlanks(){
		if(sizeof($this->data)<1)
			throw new Exception("ไม่พบข้อมูล");
		foreach($this->data as $key => $value){
			if($value==""){
				$match = FALSE;
				if(is_array($this->not_required)){
					foreach($this->not_required as $field){
						if($field == $key){
							$match = TRUE;
						}
					}
				}
				if($match == FALSE){
					$blanks[] = $key;
				}	
			}
		}
		if(isset($blanks))
			return $blanks;
		else 
			return TRUE;
	}
	
	function verifyData() {
		if(sizeof($this->data)<1)
			throw new Exception("No from data available.");
		foreach($this->data as $key => $value){
			if(empty($value)){
				if(preg_match("name",$key) && !preg_match("log",$key) && !preg_match("user",$key)){
					$result = $this->checkName($value);
					if(is_string($result))
						$errors[$key] = $result;
				}
				if(preg_match("addr",$key) || preg_match("street",$key) ||preg_match("city",$key)){
					$result = $this->checkAddress($value);
					if(is_string($result))
						$errors[$key]=$result;
				}
				if(preg_match("email",$key)){
					$result =$this->checkEmail($value);
					if(is_string($result))
						$errors[$key]=$result;
				}
				if(preg_match("phone",$key) || ereg("fax",$key)){
					$result = $this->checkPhone($value);
					if(is_string($result))
						$errors[$key]=$result;
				}
				if(preg_match("zip",$key)){
					$result=$this->checkZip($value);
					if(is_string($result))
						$errors[$key]=$result;
				}
				if(preg_match("state", $key)){
					$result=$this->checkState($value);
					if(is_string($result))
						$errors[$key]=$result;
				}
				
			}	
			
		}
		if(isset($errors))
			return $errors;
		else 
			return TRUE;
		
	}
	
	function trimData(){
		foreach($this->data as $key => $value){
			$data[$key] = trim($value);
			
		}
		$this->data = $data;
	}
	
	function stripTagsFromData(){
		foreach($this->data as $key=>$value){
			$data[$key] = strip_tags($value);
		}
		$this->data = $data;
	}
	
	function checkName($field){
		if(!preg_match("^[A-Za-z' -]{1-50}$", $field)){
			return "$field ชื่อไม่ถูกต้อง กรุณาลองใหม่";
		}else{
			return TRUE;
		}
	}
	
	function checkAddress($field){
		if(!preg_match("^[A-Za-z0-9.,' -]{1,50}$", $field)){
			return "$field ที่อยู่ไม่ถูกต้อง กรุณาลองใหม่ ";
		}else{
			return TRUE;
		}
	}
	
	function checkZip($field){
		if(!preg_match("^[0-9]{5}(\-[0-9]{4})?",$field)){
			return "$field รหัสไปรษณ์ไม่ถูกต้อง" 
		}else{
			return TRUE;	
		}
		
	}
	
	function checkPhone($field){
		if(!preg_match("^[0-9](Xx -]{7,20}$", $field)){
			return "$field เบอร์โทรศัพท์  ไม่ถูกต้องกรุณาลองใหม่";
		}else{
			return TRUE;	
		}
	}
	
	function checkEmail($field){
		if(preg_match("^.+@.+\\..+$",$field)){
			return "$field อีเมลล์ไม่ถูกต้อง กรุณาลองใหม่";
		}else{
			return TRUE;
		}
	}
	
	function checkProvince{
		if(!preg_match("^[A-Za-z]", $field)){
			return "$field จังหวัดไม่ถูกต้อง กรุณาลองใหม่";
		}else {
			return TRUE;
		}
	}
}

?>
