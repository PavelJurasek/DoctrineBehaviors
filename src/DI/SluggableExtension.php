<?php declare(strict_types=1);

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Sluggable\Sluggable;
use Knp\DoctrineBehaviors\ORM\Sluggable\SluggableSubscriber;
use Nette\Schema\Expect;
use Nette\Schema\Schema;


final class SluggableExtension extends AbstractBehaviorExtension
{

	public function loadConfiguration()
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setType(SluggableSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['isRecursive'],
				$config['trait']
			])
			->setAutowired(FALSE)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'isRecursive' => Expect::bool(TRUE),
			'trait' => Expect::string(Sluggable::class)
		]);
	}

}
