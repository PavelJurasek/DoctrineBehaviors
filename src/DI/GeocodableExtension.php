<?php declare(strict_types=1);

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Geocodable\Geocodable;
use Knp\DoctrineBehaviors\ORM\Geocodable\GeocodableSubscriber;
use Nette\Schema\Expect;
use Nette\Schema\Schema;


final class GeocodableExtension extends AbstractBehaviorExtension
{

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

		$geolocationCallable = $this->buildDefinitionFromCallable($config['geolocationCallable']);

		$builder->addDefinition($this->prefix('listener'))
			->setType(GeocodableSubscriber::class)
			->setArguments([
				'@' . $this->getClassAnalyzer()->getType(),
				$config['isRecursive'],
				$config['trait'],
				$geolocationCallable ? '@' . $geolocationCallable->getType() : $geolocationCallable
			])
			->setAutowired(FALSE)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'isRecursive' => Expect::bool(TRUE),
			'trait' => Expect::string(Geocodable::class),
			'geolocationCallable' => Expect::type('type')->nullable(),
		]);
	}

}
