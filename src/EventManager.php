<?php

/*
 * Copyright 2018 Andreas Prucha, Abexto - Helicon Software Development.
 */

namespace abexto\amylian\yii\doctrine\common;

/**
 * Description of EventManager
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class EventManager extends \abexto\amylian\yii2\doctrine\base\AbstractDoctrineInstWrapperComponent
{
    public $instClass = \Doctrine\Common\EventManager::class;
}
