<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Tree\Node;
use Knp\DoctrineBehaviors\ORM\Tree\TreeSubscriber;
use Nette\DI\Config\Helpers;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


final class TreeExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $default = [
		'isRecursive' => TRUE,
		'nodeTrait' => Node::class
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
			->setType(TreeSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['isRecursive'],
				$config['nodeTrait']
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
		Validators::assertField($config, 'nodeTrait', 'type');
	}

}
