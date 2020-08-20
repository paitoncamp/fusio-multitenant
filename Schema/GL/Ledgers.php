
<?php 
namespace App\Schema\AppName;

class Ledgers{
	
	
	/**
	* @Key("Groupid")
	* @Type("int")
	* @var int
	*/
    protected $groupId;
	
	/**
	* @Key("Name")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $name;
	
	/**
	* @Key("Code")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $code;
	
	/**
	* @Key("Opbalance")
	* @Type("float")
	* @var float
	*/
    protected $opBalance;
	
	/**
	* @Key("Opbalancedc")
	* @Type("string")
	* @MaxLength("1")
	* @var string
	*/
    protected $opBalanceDc;
	
	/**
	* @Key("Type")
	* @Type("int")
	* @var int
	*/
    protected $type;
	
	/**
	* @Key("Reconciliation")
	* @Type("int")
	* @var int
	*/
    protected $reconciliation;
	
	/**
	* @Key("Notes")
	* @Type("string")
	* @MaxLength("500")
	* @var string
	*/
    protected $notes;

	/**
	* @return int
	*/
    public function getGroupId(): ?int{
        return $this->groupId;
    }


	/**
	* @param int $groupId
	*/
    public function setGroupId(?int $groupId): void{
        $this->groupId = $groupId;
    }


	/**
	* @return string
	*/
    public function getName(): ?string{
        return $this->name;
    }


	/**
	* @param string $name
	*/
    public function setName(?string $name): void{
        $this->name = $name;
    }


	/**
	* @return string
	*/
    public function getCode(): ?string{
        return $this->code;
    }


	/**
	* @param string $code
	*/
    public function setCode(?string $code): void{
        $this->code = $code;
    }


	/**
	* @return float
	*/
    public function getOpBalance(): ?float{
        return $this->opBalance;
    }


	/**
	* @param float $opBalance
	*/
    public function setOpBalance(?float $opBalance): void{
        $this->opBalance = $opBalance;
    }


	/**
	* @return string
	*/
    public function getOpBalanceDc(): ?string{
        return $this->opBalanceDc;
    }


	/**
	* @param string $opBalanceDc
	*/
    public function setOpBalanceDc(?string $opBalanceDc): void{
        $this->opBalanceDc = $opBalanceDc;
    }


	/**
	* @return int
	*/
    public function getType(): ?int{
        return $this->type;
    }


	/**
	* @param int $type
	*/
    public function setType(?int $type): void{
        $this->type = $type;
    }


	/**
	* @return int
	*/
    public function getReconciliation(): ?int{
        return $this->reconciliation;
    }


	/**
	* @param int $reconciliation
	*/
    public function setReconciliation(?int $reconciliation): void{
        $this->reconciliation = $reconciliation;
    }


	/**
	* @return string
	*/
    public function getNotes(): ?string{
        return $this->notes;
    }


	/**
	* @param string $notes
	*/
    public function setNotes(?string $notes): void{
        $this->notes = $notes;
    }

}