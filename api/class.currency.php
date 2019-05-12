<?
	class currency{
		
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		
		
		public function setCode($code)
		{
			$this->code = $code;
			return $this;
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
			return $this;
		}
		
		public function setsymbol($symbol)
		{
			$this->symbol = $symbol;
			return $this;
		}
		
		
		
		
		public function fetchAll()
		{
			try{
				$sql="select * from global_currency order by id";
				$res=mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$result[]=$row;
				}
				return $result;
				
			}catch(Exception $e)
			{
				
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		public function find($id)
		{
			try{
				$sql="select * from global_currency where id='$id' ";
				$res=mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$this->id=$row['id'];
					$this->code=$row['code'];
					$this->description=$row['description'];
					$this->symbol=$row['symbol'];
					
					
				}
				
			}catch(Exception $e)
			{
				
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		public function save($id)
		{
			try{
				if($id>0)
				{
					$sql="update global_currency set code='".$this->code."', description='".$this->description."', symbol='".$this->symbol."'  where id='$id'";
					$res=mysql_query($sql);
					return true;
				}
				else
				{
					$sql="insert into global_currency set code='".$this->code."', description='".$this->description."', symbol='".$this->symbol."'";
					$res=mysql_query($sql);
					return true;	
				}
				
				
			}catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");		
			}
			
		}
		
		public function delete($id)
		{
			try{
				$sql="delete from global_currency where id='$id' ";
				$res=mysql_query($sql);	
			}
			catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		
	}
?>