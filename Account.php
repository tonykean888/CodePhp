<?php
class Account {
	private $userID=NULL;
	private $cxn;
	private $table_name;
	private $message;
	
	
	function __construct(mysqli $cxn,$table) {
		$this->cxn =$cxn;
		if(is_string($table)){
			$sql="SHOW TABLES LIKE '$table'";
			$result=$this->cxn->query($sql);
			if($result->num_rows > 0){
				$this->table_name=$table;
			}else{
				throw new Exception("$table ไม่พบตารางนี้");
				return FALSE;
			}
		}else{
			throw new Exception("ระบุชื่อ DB ไม่ถูกต้อง");
		}
	}
	
	function selectAccount($userID){
		$userID=trim($userID);
		$sql = "SELECT user_name FROM $this->table_name 
		WHERE user_name ='$useID'";
		if(!$result = $this->cxn-query($sql)){
			throw new Exception("Couldn't execute query:".$this->cxn->error());
			return FALSE;
		}
		if($result->num_row < 1){
			$this->mewssage = "ไม่พบข้อมูลผู้ใช้ #$userID!";
			return FALSE;
		}else{
			$this->userID = $userID;
			return TRUE;
		}	
	}
	
	function comparePassword($form_password){
		if(!is_set($this->userID)){
			throw new Exception("ไม่พบข้อมูล USER ");
			exit();
		}
		$sql="SELECT user_name FROM $this->table_name WHERE user_name
		='$this->userID' AND password = md5('$form_password')";
		
		if(!$result=$this->cxn->query($sql)){
			throw new Exception("Couldn't execute query:".mysql_error());
			exit();
		}
		
		if($result->num_rows < 1){
			$this->message = "รหัสผ่านไม่ถูกต้อง # $this->userID";
			return FALSE;
			
		}else{
			return TRUE;
		}
	}
	
	function getMessage(){
		return $this->message;
	}
	
	function createNewAccount($data){
		if(!is_array($data)){
			throw new Exception("ข้อมูลไม่ถูกต้อง กรุณาลองใหม่");
			return FALSE;
		}
		foreach($data as $field => $value){
			if($field != "password" and $field != "Button"){
				$fields[]=$field;
				$values[]=addslashes($value);
			}
		}
		$str_fields = implode($fields, ",");
		$str_values = '"'.implode($values, '","');
		$today = date("Y-m-d");
		$str_fields .=",create_date";
		$str_fields .=",password";
		$str_values .="\",\"$today";
		$str_values .="\",md5(\"{data['password']}\")";
		$sql="INSERT INTO $this->table_name ($str_fields)
			VALUES ($str_values)";
		if(!$this->cxn->query($sql)){
			throw new Exception("Can't execute query:".$this->cxn->error());
			return FALSE;
		}else{
			return TRUE;
		}
		
	}
}

?>
