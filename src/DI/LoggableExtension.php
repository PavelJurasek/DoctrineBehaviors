<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\ORM\Loggable\LoggableSubscriber;
use Nette\DI\Config\Helpers;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use Zenify\DoctrineBehaviors\Loggable\LoggerCallable;


final class LoggableExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $default = [
		'isRecursive' => TRUE,
		'loggerCallable' => LoggerCallable::class
	];


	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = Helpers::merge($this->getConfig(), $this->default);
		$this->validateConfigTypes($config);
		$builder = $this->getContainerBuilder();

		$loggerCallable = $this->buildDefinitionFromCallable($config['loggerCallable']);

		$builder->addDefinition($this->prefix('listener'))
			->setType(LoggableSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['isRecursive'],
				'@' . $loggerCallable->getType()
			])
			->setAutowired(FALSE)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	/**
	 * @throws AssertionException
	 */
	private function validateConfigTypes(array $config)
	{
		Validators::assertField($config, 'isRecursive', 'bool');
		Validators::assertField($config, 'loggerCallable', 'type');
	}

}
