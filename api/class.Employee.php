<?
	class Employee{
		
		public function setEmpId($emp_id)
		{
			$this->emp_id = $emp_id;
			return $this;
		}
		
		public function setLocationId($location_id)
		{
			$this->location_id = $location_id;
			return $this;
		}
		
		public function setStatus($status)
		{
			$this->status = $status;
			return $this;
		}
		
		public function setPassword($password)
		{
			$this->password = $password;
			return $this;
		}
		
		public function setFirstName($first_name)
		{
			$this->first_name = $first_name;
			return $this;
		}
		
		public function setLastName($last_name)
		{
			$this->last_name = $last_name;
			return $this;
		}
		
		public function setEmail($email)
		{
			$this->email = $email;
			return $this;
		}
		
		public function setTelephone($telephone)
		{
			$this->telephone = $telephone;
			return $this;
		}
		public function setCountry($country)
		{
			$this->country = $country;
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
				
		
		public function setDateStarted($date_started)
		{
			$this->date_started = $date_started;
			return $this;
		}
		
		public function setNeighborhood($department)
		{
			$this->department = $department;
			return $this;
		}
		
				
		public function setPosition($position)
		{
			$this->position = $position;
			return $this;
		}
		
		public function setSupervisor($supervisor)
		{
			$this->supervisor = $supervisor;
			return $this;
		}
		
		public function setPayrollPeriod($payroll_period)
		{
			$this->payroll_period = $payroll_period;
			return $this;
		}
		
		public function setHourlyRate($hourly_rate)
		{
			$this->hourly_rate = $hourly_rate;
			return $this;
		}
		
		public function setMonthlyRate($monthly_rate)
		{
			$this->monthly_rate = $monthly_rate;
			return $this;
		}
		
		public function setAnnualRate($annual_rate)
		{
			$this->annual_rate = $annual_rate;
			return $this;
		}
		
		public function setSex($sex)
		{
			$this->sex = $sex;
			return $this;
		}
		
		public function setDob($dob)
		{
			$this->dob = $dob;
			return $this;
		}
		
		public function setIdType($id_type)
		{
			$this->id_type = $id_type;
			return $this;
		}
		
		public function setIdNumber($id_number)
		{
			$this->id_number = $id_number;
			return $this;
		}
		
		public function setImage($image)
		{
			$this->image = $image;
			return $this;
		}
		
		public function setContract($contract)
		{
			$this->contract = $contract;
			return $this;
		}
		
		public function setContractType($contract_type)
		{
			$this->contract_type = $contract_type;
			return $this;
		}
		
		public function fetchAll()
		{
			try{
				$sql="select * from employees WHERE 1";
				if(isset($this->emp_id))
				$sql .=" AND emp_id='".$this->emp_id."'";
				if(isset($this->location_id))
				$sql .=" AND location_id='".$this->location_id."'";
				if(isset($this->email))
				$sql .=" AND email='".$this->email."'";
				$sql .=" ORDER BY id";
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
				$sql="select * from employees where id='$id' ";
				$res=mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$this->id=$row['id'];
					$this->emp_id=$row['emp_id'];
					$this->location_id=$row['location_id'];
					$this->status=$row['status'];
					$this->email=$row['email'];
					$this->first_name=$row['first_name'];
					$this->last_name=$row['last_name'];
					$this->country=$row['country'];
					$this->address=$row['address'];
					$this->address2=$row['address2'];
					$this->city=$row['city'];
					$this->state=$row['state'];
					$this->zip=$row['zip'];
					$this->date_started=$row['date_started'];
					$this->department=$row['department'];
					$this->telephone=$row['telephone'];
					$this->position=$row['position'];
					$this->supervisor=$row['Mobile'];
					$this->payroll_period=$row['payroll_period'];
					$this->hourly_rate=$row['hourly_rate'];
					$this->image=$row['image'];
					$this->resume=$row['resume'];
					$this->monthly_rate=$row['monthly_rate'];
					$this->annual_rate=$row['annual_rate'];
					$this->sex=$row['sex'];
					$this->dob=$row['dob'];
					$this->id_type=$row['id_type'];
					$this->id_number=$row['id_number'];
					$this->image=$row['image'];
					$this->contract=$row['contract'];
					$this->contract_type=$row['contract_type'];
					
					
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
					$sql="update employees set status='".$this->status."', first_name='".$this->first_name."', last_name='".$this->last_name."',  salutation='".$this->salutation."', country='".$this->country."', address='".$this->address."', address2='".$this->address2."', city='".$this->city."', state='".$this->state."', zip='".$this->zip."', date_started='".$this->date_started."', department='".$this->department."', telephone='".$this->telephone."', position='".$this->position."', Mobile='".$this->supervisor."', payroll_period='".$this->payroll_period."', hourly_rate='".$this->hourly_rate."', monthly_rate='".$this->monthly_rate."', annual_rate='".$this->annual_rate."', sex='".$this->sex."', dob='".$this->dob."', id_type='".$this->id_type."', id_number='".$this->id_number."', image='".$this->image."', contract='".$this->contract."', contract_type='".$this->contract_type."' where id='$id'";
					$res=mysql_query($sql);
					return true;
				}
				else
				{
					$sql="insert into employees set status='".$this->status."', first_name='".$this->first_name."', last_name='".$this->last_name."', email='".$this->email."', password='".$this->password."'";
					$res=mysql_query($sql);
					return true;	
				}
				
				
			}catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");		
			}
			
		}
		
		public function updatePassword($id)
		{
			try{
				if($id>0)
				{
					$sql="update employees set password='".$this->password."' where id='$id'";
					$res=mysql_query($sql);
					return true;
				}
				else
				{
					throw new Exception('Invalid id');
					return false;	
				}
				
				
			}catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");		
			}
			
		}
		
		public function delete($id)
		{
			try{
				$sql="delete from employees where id='$id' ";
				$res=mysql_query($sql);	
			}
			catch(Exception $e)
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
			
		public function isEmailExists($email)
		{
			try {
				$sql="select count(*) as Cnt from employees where email='$email' ";
				$res=mysql_query($sql);
			    $result=mysql_fetch_array($res);
			
				if (0 == $result['Cnt']) {
					return true;
				}
				else
					return false;
			}
		   catch(Exception $e)  // Generate exception if there is any error in Query
			{
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
				return;
			}
			
		}
	}
?>