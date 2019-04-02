<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby;
use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletable;
use Knp\DoctrineBehaviors\ORM\SoftDeletable\SoftDeletableSubscriber;
use Nette\DI\Config\Helpers;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


final class SoftDeletableExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $default = [
		'isRecursive' => TRUE,
		'trait' => SoftDeletable::class
	];


	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = Helpers::merge($this->getConfig(), $this->default);
		$this->validateConfigTypes($config);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setType(SoftDeletableSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['isRecursive'],
				$config['trait']
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
	}

}
