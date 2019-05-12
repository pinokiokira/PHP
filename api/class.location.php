<?
	class location{
		
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}
		
		public function setStatus($status)
		{
			$this->status = $status;
			return $this;
		}
		
		public function setAddress($address)
		{
			$this->address = $address;
			return $this;
		}
		
		public function setAddress2($address2)
		{
			$this->address2 = $address2;
			return $this;
		}
		
		public function setCity($city)
		{
			$this->city = $city;
			return $this;
		}
		
		public function setState($state)
		{
			$this->state = $state;
			return $this;
		}
		
		public function setZip($zip)
		{
			$this->zip = $zip;
			return $this;
		}
		
		public function setCountry($country)
		{
			$this->country = $country;
			return $this;
		}
		
		public function setPhone($phone)
		{
			$this->phone = $phone;
			return $this;
		}
		
		
		public function fetchAll()
		{
			try{
				$sql="select * from locations order by name ";
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
				$sql="select * from locations where id='$id' ";
				$res=mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$this->id=$row['id'];
					$this->status=$row['status'];
					$this->address=$row['address'];
					$this->address2=$row['address2'];
					$this->name=$row['name'];
					$this->city=$row['city'];
					$this->state=$row['state'];
					$this->zip=$row['zip'];
					$this->country=$row['country'];
					$this->phone=$row['phone'];
					$this->created_date=$row['created_date'];
					$this->image=$row['image'];
                                        
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
					$sql="update locations set city='".$this->city."', state='".$this->state."',  status='".$this->status."', zip='".$this->zip."', country='".$this->country."' where id='$id'";
					$res=mysql_query($sql);
					return true;
				}
				else
				{
					$sql="insert into locations set city='".$this->city."', state='".$this->state."', name='".$this->name."'";
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
				$sql="delete from locations where id='$id' ";
				$res=mysql_query($sql);	
			}
			catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		
	}
?>