<?php

/*
 * Copyright 2018 Andreas Prucha, Abexto - Helicon Software Development.
 */

namespace abexto\amylian\yii\doctrine\common;

/**
 * Description of EventManager
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 * 
 * @property \Doctrine\Common\EventManager $inst Wrapped EventManager Instance
 */
class EventManager extends \abexto\amylian\yii\doctrine\base\AbstractDoctrineInstWrapperComponent
{

    public $instClass = \Doctrine\Common\EventManager::class;

    /**
     * Definition of EventSubscribers
     * @var array|object[] 
     */
    public $eventSubscribers = [];
    
    protected $_eventSubscriberInstances = [];

    protected function getInstPropertyMappings()
    {
        return array_merge(
                parent::getInstPropertyMappings(),
                [
            'eventSubscribers' => [$this, 'setInstPropertyEventSubscribers'],
        ]);
    }
    
    protected function createEventSubscriberInst($definition)
    {
        
    }

    protected function setInstPropertyEventSubscribers($value, $inst)
    {
        if ($value) {
            foreach ($value as $definition) {
                $this->addEventSubscriber($definition);
            }
        }
    }
    
    protected function ensureEventSubscriber($subscriberDefinition)
    {
        if (is_array($subscriberDefinition) && isset($subscriberDefinition['class']) && !is_a($subscriberDefinition['class'], \yii\base\Configurable::class)) {
            return new $subscriberDefinition['class']();
        } else {
            return \abexto\amylian\yii\doctrine\base\InstanceManager::ensure($subscriberDefinition);
        }
    }

    /**
     * Adds an event subscriber to the event manager 
     * 
     * @param object|array $subscriberDefinition
     */
    public function addEventSubscriber($subscriberDefinition, $id = null)
    {
        if (!is_object($subscriberDefinition)) {
            $subscriberInst = $this->ensureEventSubscriber($subscriberDefinition);
        } else {
            $subscriberInst = $subscriberDefinition;
        }
        if ($id === null) {
            $id = 'hash-' . spl_object_hash($subscriberInst);
        }
        $this->getInst()->addEventSubscriber($subscriberInst);
        $this->_eventSubscriberInstances[$id] = $subscriberInst;
    }
    
    /**
     * Removes an event subscriber 
     * @param object|string $subscriber The instance of the event subscriber or the id of the event subscriber
     */
    public function removeEventSubscriber($subscriber)
    {
        if (is_object($subscriber)) {
            $this->getInst()->removeEventSubscriber($subscriber);
            unset($this->_eventSubscriberInstances['hash-' . spl_object_hash($subscriberInst)]);
        } else {
            $this->getInst()->removeEventSubscriber($this->_eventSubscriberInstances[$subscriber]);
            unset($this->_eventSubscriberInstances[$subscriber]);
        }
    }

}
