<?php declare(strict_types=1);

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\ORM\Loggable\LoggableSubscriber;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Zenify\DoctrineBehaviors\Loggable\LoggerCallable;


final class LoggableExtension extends AbstractBehaviorExtension
{

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = (array) $this->getConfig();
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


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'isRecursive' => Expect::bool(TRUE),
			'loggerCallable' => Expect::string(LoggerCallable::class)
		]);
	}

}
