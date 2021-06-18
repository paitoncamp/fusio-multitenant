<?php 

declare(strict_types = 1);
namespace App\Model\Tenancy;

class Tenant implements \JsonSerializable
{
	
	/**
     * @var int|null
     */
    protected $id;
	
	/**
     * @var string|null
     */
    protected $name;
	
	/**
     * @var string|null
     */
    protected $firstName;
	
	/**
     * @var string|null
     */
    protected $lastName;
	
	/**
     * @var string|null
     */
    protected $email;
	
	/**
     * @var string|null
     */
    protected $tenantUId;
	

	/**
     * @return string|null
     */
    public function getId(): ?int{
        return $this->id;
    }


	/**
     * @param int|null $id
     */
    public function setId(?int $id): void{
        $this->id = $id;
    }


	/**
	* @return string|null
	*/
    public function getName(): ?string{
        return $this->name;
    }


	/**
	* @param string|null $name
	*/
    public function setName(?string $name): void{
        $this->name = $name;
    }
	
	


	/**
	* @return string|null
	*/
    public function getEmail(): ?string{
        return $this->email;
    }


	/**
	* @param string|null $email
	*/
    public function setEmail(?string $email): void{
        $this->email = $email;
    }


	/**
	* @return string|null 
	*/
    public function getFirstName(): ?string{
        return $this->first_name;
    }


	/**
	* @param string|null $firstName
	*/
    public function setFirstName(?string $firstName): void{
        $this->firstName = $firstName;
    }
	
	
	
	/**
	* @return string|null
	*/
    public function getLastName(): ?string{
        return $this->lastName;
    }


	/**
	* @param string|null $lastName
	*/
    public function setLastName(?string $lastName): void{
        $this->lastName = $lastName;
    }
	
	/**
	* @return string|null
	*/
    public function getTenantUId(): ?string{
        return $this->tenantUId;
    }


	/**
	* @param string|null $tenantUId
	*/
    public function setTenantUID(?string $tenantUId): void{
        $this->tenantUId = $tenantUId;
    }

	public function jsonSerialize()
    {
        return (object) array_filter(array('id' => $this->id, 'name' => $this->name,'firstName' => $this->firstName, 'lastName' => $this->lastName, 'email' => $this->email, 'tenantUId' => $this->tenantUId), static function ($value) : bool {
            return $value !== null;
        });
    }
}