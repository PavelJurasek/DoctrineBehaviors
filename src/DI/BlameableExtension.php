<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\ORM\Blameable\BlameableSubscriber;
use Nette\DI\Config\Helpers;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use Zenify\DoctrineBehaviors\Blameable\UserCallable;


final class BlameableExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $default = [
		'isRecursive' => TRUE,
		'trait' => Blameable::class,
		'userCallable' => UserCallable::class,
		'userEntity' => NULL
	];


	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = Helpers::merge($this->getConfig(), $this->default);
		$this->validateConfigTypes($config);
		$builder = $this->getContainerBuilder();

		$userCallable = $this->buildDefinitionFromCallable($config['userCallable']);

		$builder->addDefinition($this->prefix('listener'))
			->setType(BlameableSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['isRecursive'],
				$config['trait'],
				'@' . $userCallable->getType(),
				$config['userEntity']
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
		Validators::assertField($config, 'trait', 'type');
		Validators::assertField($config, 'userCallable', 'string');
		Validators::assertField($config, 'userEntity', 'null|string');
	}

}
