<?php

namespace Proxies\__CG__\App\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Player extends \App\Entity\Player implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'id', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'nameshort', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'namelong', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'birthdate', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'level', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'phone', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'email', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'avatar', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'details', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'username', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'password', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'roles', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'active', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'idcountry', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'initialrating'];
        }

        return ['__isInitialized__', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'id', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'nameshort', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'namelong', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'birthdate', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'level', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'phone', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'email', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'avatar', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'details', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'username', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'password', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'roles', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'active', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'idcountry', '' . "\0" . 'App\\Entity\\Player' . "\0" . 'initialrating'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Player $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId(): ?int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getNameshort(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNameshort', []);

        return parent::getNameshort();
    }

    /**
     * {@inheritDoc}
     */
    public function setNameshort(?string $nameshort): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNameshort', [$nameshort]);

        return parent::setNameshort($nameshort);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamelong(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNamelong', []);

        return parent::getNamelong();
    }

    /**
     * {@inheritDoc}
     */
    public function setNamelong(?string $namelong): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNamelong', [$namelong]);

        return parent::setNamelong($namelong);
    }

    /**
     * {@inheritDoc}
     */
    public function getBirthdate(): ?\DateTimeInterface
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBirthdate', []);

        return parent::getBirthdate();
    }

    /**
     * {@inheritDoc}
     */
    public function setBirthdate(?\DateTimeInterface $birthdate): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBirthdate', [$birthdate]);

        return parent::setBirthdate($birthdate);
    }

    /**
     * {@inheritDoc}
     */
    public function getLevel(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLevel', []);

        return parent::getLevel();
    }

    /**
     * {@inheritDoc}
     */
    public function setLevel(?string $level): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLevel', [$level]);

        return parent::setLevel($level);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone', []);

        return parent::getPhone();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone(?string $phone): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone', [$phone]);

        return parent::setPhone($phone);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail(?string $email): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getAvatar(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAvatar', []);

        return parent::getAvatar();
    }

    /**
     * {@inheritDoc}
     */
    public function setAvatar(?string $avatar): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAvatar', [$avatar]);

        return parent::setAvatar($avatar);
    }

    /**
     * {@inheritDoc}
     */
    public function getDetails(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDetails', []);

        return parent::getDetails();
    }

    /**
     * {@inheritDoc}
     */
    public function setDetails(?string $details): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDetails', [$details]);

        return parent::setDetails($details);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsername', []);

        return parent::getUsername();
    }

    /**
     * {@inheritDoc}
     */
    public function setUsername(?string $username): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsername', [$username]);

        return parent::setUsername($username);
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', []);

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword(?string $password): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', [$password]);

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoles', []);

        return parent::getRoles();
    }

    /**
     * {@inheritDoc}
     */
    public function setRoles(?string $roles): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRoles', [$roles]);

        return parent::setRoles($roles);
    }

    /**
     * {@inheritDoc}
     */
    public function getActive(): ?bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getActive', []);

        return parent::getActive();
    }

    /**
     * {@inheritDoc}
     */
    public function setActive(bool $active): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setActive', [$active]);

        return parent::setActive($active);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountry(): ?\App\Entity\Country
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountry', []);

        return parent::getCountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setCountry(?\App\Entity\Country $idcountry): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCountry', [$idcountry]);

        return parent::setCountry($idcountry);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdcountry(): ?\App\Entity\Country
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIdcountry', []);

        return parent::getIdcountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setIdcountry(?\App\Entity\Country $idcountry): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIdcountry', [$idcountry]);

        return parent::setIdcountry($idcountry);
    }

    /**
     * {@inheritDoc}
     */
    public function getInitialrating(): ?int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInitialrating', []);

        return parent::getInitialrating();
    }

    /**
     * {@inheritDoc}
     */
    public function setInitialrating(int $initialrating): \App\Entity\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInitialrating', [$initialrating]);

        return parent::setInitialrating($initialrating);
    }

}
