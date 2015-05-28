<?php

namespace DoctrineORMModule\Proxy\__CG__\Sonar\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Project extends \Sonar\Entity\Project implements \Doctrine\ORM\Proxy\Proxy
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
    public static $lazyPropertiesDefaults = array();



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
            return array('__isInitialized__', 'id', '' . "\0" . 'Sonar\\Entity\\Project' . "\0" . 'root', 'name', 'longName', 'scope', 'qualifier', 'enabled', '' . "\0" . 'Sonar\\Entity\\Project' . "\0" . 'issues', '' . "\0" . 'Sonar\\Entity\\Project' . "\0" . 'snapshots', 'uuid');
        }

        return array('__isInitialized__', 'id', '' . "\0" . 'Sonar\\Entity\\Project' . "\0" . 'root', 'name', 'longName', 'scope', 'qualifier', 'enabled', '' . "\0" . 'Sonar\\Entity\\Project' . "\0" . 'issues', '' . "\0" . 'Sonar\\Entity\\Project' . "\0" . 'snapshots', 'uuid');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Project $proxy) {
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
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
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
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', array($id));

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoot()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoot', array());

        return parent::getRoot();
    }

    /**
     * {@inheritDoc}
     */
    public function setRoot($root)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRoot', array($root));

        return parent::setRoot($root);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', array());

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', array($name));

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getLongName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLongName', array());

        return parent::getLongName();
    }

    /**
     * {@inheritDoc}
     */
    public function setLongName($longName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLongName', array($longName));

        return parent::setLongName($longName);
    }

    /**
     * {@inheritDoc}
     */
    public function getDirName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDirName', array());

        return parent::getDirName();
    }

    /**
     * {@inheritDoc}
     */
    public function getScope()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getScope', array());

        return parent::getScope();
    }

    /**
     * {@inheritDoc}
     */
    public function setScope($scope)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setScope', array($scope));

        return parent::setScope($scope);
    }

    /**
     * {@inheritDoc}
     */
    public function getQualifier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getQualifier', array());

        return parent::getQualifier();
    }

    /**
     * {@inheritDoc}
     */
    public function setQualifier($qualifier)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setQualifier', array($qualifier));

        return parent::setQualifier($qualifier);
    }

    /**
     * {@inheritDoc}
     */
    public function getEnabled()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEnabled', array());

        return parent::getEnabled();
    }

    /**
     * {@inheritDoc}
     */
    public function setEnabled($enabled)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEnabled', array($enabled));

        return parent::setEnabled($enabled);
    }

    /**
     * {@inheritDoc}
     */
    public function getIssues()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIssues', array());

        return parent::getIssues();
    }

    /**
     * {@inheritDoc}
     */
    public function setIssues($issues)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIssues', array($issues));

        return parent::setIssues($issues);
    }

    /**
     * {@inheritDoc}
     */
    public function getSnapshots()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSnapshots', array());

        return parent::getSnapshots();
    }

    /**
     * {@inheritDoc}
     */
    public function setSnapshots($snapshots)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSnapshots', array($snapshots));

        return parent::setSnapshots($snapshots);
    }

    /**
     * {@inheritDoc}
     */
    public function getSnapshot()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSnapshot', array());

        return parent::getSnapshot();
    }

}
