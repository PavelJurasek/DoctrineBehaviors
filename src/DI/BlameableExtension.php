<?php declare(strict_types=1);

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\ORM\Blameable\BlameableSubscriber;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Zenify\DoctrineBehaviors\Blameable\UserCallable;


final class BlameableExtension extends AbstractBehaviorExtension
{

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = (array) $this->getConfig();
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


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'isRecursive' => Expect::bool(TRUE),
			'trait' => Expect::string(Blameable::class),
			'userCallable' => Expect::string(UserCallable::class),
			'userEntity' => Expect::string(),
		]);
	}

}
