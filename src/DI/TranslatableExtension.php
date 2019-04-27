<?php declare(strict_types=1);

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby;
use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Knp\DoctrineBehaviors\ORM\Translatable\TranslatableSubscriber;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Zenify\DoctrineBehaviors\Entities\Attributes\Translatable;


final class TranslatableExtension extends AbstractBehaviorExtension
{

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setType(TranslatableSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['currentLocaleCallable'],
				$config['defaultLocaleCallable'],
				$config['translatableTrait'],
				$config['translationTrait'],
				$config['translatableFetchMode'],
				$config['translationFetchMode']
			])
			->setAutowired(FALSE)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'currentLocaleCallable' => Expect::array()->nullable(),
			'defaultLocaleCallable' => Expect::string(),
			'translatableTrait' => Expect::string(Translatable::class),
			'translationTrait' => Expect::string(Translation::class),
			'translatableFetchMode' => Expect::string('LAZY'),
			'translationFetchMode' => Expect::string('LAZY'),
		]);
	}

}
