<?
	class state{
		
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function setCountryId($country_id)
		{
			$this->country_id = $country_id;
			return $this;
		}
		
		public function setName($name)
		{
			$this->name = $name;
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
		
				
		public function setStatus($status)
		{
			$this->status = $status;
			return $this;
		}
		
		public function setCountryNumcode($country_timezone)
		{
			$this->country_timezone = $country_timezone;
			return $this;
		}
		
		public function setTimezone($timezone)
		{
			$this->timezone = $timezone;
			return $this;
		}
		
		
		public function fetchAll()
		{
			try{
				$sql="select * from states WHERE 1";
				if(isset($this->country_id))
				$sql .=" AND country_id='".$this->country_id."'";
				$sql .=" ORDER BY name";
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
				$sql="select * from states where id='$id' ";
				$res=mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$this->id=$row['id'];
					$this->country_id=$row['country_id'];
					$this->name=$row['name'];
					$this->code=$row['code'];
					$this->description=$row['description'];
					$this->status=$row['status'];
					$this->country_timezone=$row['country_timezone'];
					$this->timezone=$row['timezone'];
					
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
					$sql="update states set code='".$this->code."', description='".$this->description."',  status='".$this->status."', country_timezone='".$this->country_timezone."', timezone='".$this->timezone."' where id='$id'";
					$res=mysql_query($sql);
					return true;
				}
				else
				{
					$sql="insert into states set code='".$this->code."', description='".$this->description."', name='".$this->name."'";
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
				$sql="delete from states where id='$id' ";
				$res=mysql_query($sql);	
			}
			catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		
	}
?>