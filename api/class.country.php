<?
	class country{
		
		public function setId($id)
		{
			$this->id = $id;
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
		
		public function setIsDefault($is_default)
		{
			$this->is_default = $is_default;
			return $this;
		}
		
		public function setStatus($status)
		{
			$this->status = $status;
			return $this;
		}
		
		public function setIso3($iso3)
		{
			$this->iso3 = $iso3;
			return $this;
		}
		
		public function setNumcode($numcode)
		{
			$this->numcode = $numcode;
			return $this;
		}
		
		
		public function fetchAll()
		{
			try{
				$sql="select * from countries order by name ";
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
				$sql="select * from countries where id='$id' ";
				$res=mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$this->id=$row['id'];
					$this->name=$row['name'];
					$this->code=$row['code'];
					$this->description=$row['description'];
					$this->is_default=$row['is_default'];
					$this->status=$row['status'];
					$this->iso3=$row['iso3'];
					$this->numcode=$row['numcode'];
					
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
					$sql="update countries set code='".$this->code."', description='".$this->description."', is_default='".$this->is_default."',  status='".$this->status."', iso3='".$this->iso3."', numcode='".$this->numcode."' where id='$id'";
					$res=mysql_query($sql);
					return true;
				}
				else
				{
					$sql="insert into countries set code='".$this->code."', description='".$this->description."', is_default='".$this->is_default."', name='".$this->name."'";
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
				$sql="delete from countries where id='$id' ";
				$res=mysql_query($sql);	
			}
			catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		
	}
?>