<?php
/**
* Connect class
*/
//

abstract class Cn 
{

	protected $db;
	private $host = 'localhost';
	private $user = 'root';
	private $pass = '';
	private $dbname = 'sscuit_estore';

	function __construct(){
    	$db1 = $this->dbname;
    	$user = $this->user;
    	$pass = $this->pass;
    	$this->Connection($db1,$user,$pass);
	}

	public function Connection($db1,$user,$pass)
	{
		try
		{
			$this->db = new PDO('mysql:dbname='.$db1.';charset=utf8;host='.$this->host,$user,$pass);
			
		}
		catch(PDOException $e)
		{ 
			echo "connection Fail : ".$e->getMessage();
			//header("Location:setup.php");
		}
	}
}




?>