<?php 
namespace App\Schema\GL;

class Groups{
	
	
	/**
	* @Key("Parentid")
	* @Type("int")
	* @var int
	*/
    protected $parentId;
	
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
	* @Key("Affectsgross")
	* @Type("int")
	* @var int
	*/
    protected $affectsGross;

	/**
	* @return int
	*/
    public function getParentId(): ?int{
        return $this->parentId;
    }


	/**
	* @param int $parentId
	*/
    public function setParentId(?int $parentId): void{
        $this->parentId = $parentId;
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
	* @return int
	*/
    public function getAffectsGross(): ?int{
        return $this->affectsGross;
    }


	/**
	* @param int $affectsGross
	*/
    public function setAffectsGross(?int $affectsGross): void{
        $this->affectsGross = $affectsGross;
    }

}