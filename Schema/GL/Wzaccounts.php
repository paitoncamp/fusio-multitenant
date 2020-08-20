
<?php 
namespace App\Schema\AppName;

class Wzaccounts{
	
	
	/**
	* @Key("Label")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $label;
	
	/**
	* @Key("Dbdatasource")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbDatasource;
	
	/**
	* @Key("Dbdatabase")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbDatabase;
	
	/**
	* @Key("Dbhost")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbHost;
	
	/**
	* @Key("Dbport")
	* @Type("int")
	* @var int
	*/
    protected $dbPort;
	
	/**
	* @Key("Dblogin")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbLogin;
	
	/**
	* @Key("Dbpassword")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbPassword;
	
	/**
	* @Key("Dbprefix")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbPrefix;
	
	/**
	* @Key("Dbpersistent")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbPersistent;
	
	/**
	* @Key("Dbschema")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbSchema;
	
	/**
	* @Key("Dbunixsocket")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbUnixsocket;
	
	/**
	* @Key("Dbsettings")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $dbSettings;
	
	/**
	* @Key("Sslkey")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $sslKey;
	
	/**
	* @Key("Sslcert")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $sslCert;
	
	/**
	* @Key("Sslca")
	* @Type("string")
	* @MaxLength("255")
	* @var string
	*/
    protected $sslCa;

	/**
	* @return string
	*/
    public function getLabel(): ?string{
        return $this->label;
    }


	/**
	* @param string $label
	*/
    public function setLabel(?string $label): void{
        $this->label = $label;
    }


	/**
	* @return string
	*/
    public function getDbDatasource(): ?string{
        return $this->dbDatasource;
    }


	/**
	* @param string $dbDatasource
	*/
    public function setDbDatasource(?string $dbDatasource): void{
        $this->dbDatasource = $dbDatasource;
    }


	/**
	* @return string
	*/
    public function getDbDatabase(): ?string{
        return $this->dbDatabase;
    }


	/**
	* @param string $dbDatabase
	*/
    public function setDbDatabase(?string $dbDatabase): void{
        $this->dbDatabase = $dbDatabase;
    }


	/**
	* @return string
	*/
    public function getDbHost(): ?string{
        return $this->dbHost;
    }


	/**
	* @param string $dbHost
	*/
    public function setDbHost(?string $dbHost): void{
        $this->dbHost = $dbHost;
    }


	/**
	* @return int
	*/
    public function getDbPort(): ?int{
        return $this->dbPort;
    }


	/**
	* @param int $dbPort
	*/
    public function setDbPort(?int $dbPort): void{
        $this->dbPort = $dbPort;
    }


	/**
	* @return string
	*/
    public function getDbLogin(): ?string{
        return $this->dbLogin;
    }


	/**
	* @param string $dbLogin
	*/
    public function setDbLogin(?string $dbLogin): void{
        $this->dbLogin = $dbLogin;
    }


	/**
	* @return string
	*/
    public function getDbPassword(): ?string{
        return $this->dbPassword;
    }


	/**
	* @param string $dbPassword
	*/
    public function setDbPassword(?string $dbPassword): void{
        $this->dbPassword = $dbPassword;
    }


	/**
	* @return string
	*/
    public function getDbPrefix(): ?string{
        return $this->dbPrefix;
    }


	/**
	* @param string $dbPrefix
	*/
    public function setDbPrefix(?string $dbPrefix): void{
        $this->dbPrefix = $dbPrefix;
    }


	/**
	* @return string
	*/
    public function getDbPersistent(): ?string{
        return $this->dbPersistent;
    }


	/**
	* @param string $dbPersistent
	*/
    public function setDbPersistent(?string $dbPersistent): void{
        $this->dbPersistent = $dbPersistent;
    }


	/**
	* @return string
	*/
    public function getDbSchema(): ?string{
        return $this->dbSchema;
    }


	/**
	* @param string $dbSchema
	*/
    public function setDbSchema(?string $dbSchema): void{
        $this->dbSchema = $dbSchema;
    }


	/**
	* @return string
	*/
    public function getDbUnixsocket(): ?string{
        return $this->dbUnixsocket;
    }


	/**
	* @param string $dbUnixsocket
	*/
    public function setDbUnixsocket(?string $dbUnixsocket): void{
        $this->dbUnixsocket = $dbUnixsocket;
    }


	/**
	* @return string
	*/
    public function getDbSettings(): ?string{
        return $this->dbSettings;
    }


	/**
	* @param string $dbSettings
	*/
    public function setDbSettings(?string $dbSettings): void{
        $this->dbSettings = $dbSettings;
    }


	/**
	* @return string
	*/
    public function getSslKey(): ?string{
        return $this->sslKey;
    }


	/**
	* @param string $sslKey
	*/
    public function setSslKey(?string $sslKey): void{
        $this->sslKey = $sslKey;
    }


	/**
	* @return string
	*/
    public function getSslCert(): ?string{
        return $this->sslCert;
    }


	/**
	* @param string $sslCert
	*/
    public function setSslCert(?string $sslCert): void{
        $this->sslCert = $sslCert;
    }


	/**
	* @return string
	*/
    public function getSslCa(): ?string{
        return $this->sslCa;
    }


	/**
	* @param string $sslCa
	*/
    public function setSslCa(?string $sslCa): void{
        $this->sslCa = $sslCa;
    }

}