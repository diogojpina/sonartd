<?php

namespace DoctrineORMModule\Proxy\__CG__\Sonar\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class TechnicalDebt extends \Sonar\Entity\TechnicalDebt implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'id', 'issue', '' . "\0" . 'Sonar\\Entity\\TechnicalDebt' . "\0" . 'measures', 'sonarTD', 'modelTD', 'regressionTD', 'realTD');
        }

        return array('__isInitialized__', 'id', 'issue', '' . "\0" . 'Sonar\\Entity\\TechnicalDebt' . "\0" . 'measures', 'sonarTD', 'modelTD', 'regressionTD', 'realTD');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (TechnicalDebt $proxy) {
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
    public function getIssue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIssue', array());

        return parent::getIssue();
    }

    /**
     * {@inheritDoc}
     */
    public function setIssue($issue)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIssue', array($issue));

        return parent::setIssue($issue);
    }

    /**
     * {@inheritDoc}
     */
    public function getMeasures()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMeasures', array());

        return parent::getMeasures();
    }

    /**
     * {@inheritDoc}
     */
    public function setMeasures($measures)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMeasures', array($measures));

        return parent::setMeasures($measures);
    }

    /**
     * {@inheritDoc}
     */
    public function getSonarTD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSonarTD', array());

        return parent::getSonarTD();
    }

    /**
     * {@inheritDoc}
     */
    public function setSonarTD($sonarTD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSonarTD', array($sonarTD));

        return parent::setSonarTD($sonarTD);
    }

    /**
     * {@inheritDoc}
     */
    public function getModelTD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getModelTD', array());

        return parent::getModelTD();
    }

    /**
     * {@inheritDoc}
     */
    public function setModelTD($modelTD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setModelTD', array($modelTD));

        return parent::setModelTD($modelTD);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegressionTD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegressionTD', array());

        return parent::getRegressionTD();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegressionTD($regressionTD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegressionTD', array($regressionTD));

        return parent::setRegressionTD($regressionTD);
    }

    /**
     * {@inheritDoc}
     */
    public function getRealTD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRealTD', array());

        return parent::getRealTD();
    }

    /**
     * {@inheritDoc}
     */
    public function setRealTD($realTD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRealTD', array($realTD));

        return parent::setRealTD($realTD);
    }

    /**
     * {@inheritDoc}
     */
    public function getTechnicalDebt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTechnicalDebt', array());

        return parent::getTechnicalDebt();
    }

    /**
     * {@inheritDoc}
     */
    public function getTechnicalDebtFormated($workHours = 8)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTechnicalDebtFormated', array($workHours));

        return parent::getTechnicalDebtFormated($workHours);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetrics()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetrics', array());

        return parent::getMetrics();
    }

}
