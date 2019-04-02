<?php

namespace Zenify\DoctrineBehaviors\Tests\Translatable\Entities\Attributes\Source;

use Knp\DoctrineBehaviors\Model\Translatable\Translatable as KnpTranslatable;
use Zenify\DoctrineBehaviors\Entities\Attributes\Translatable as ZenifyTranslatable;


/**
 * @method string getName()
 */
final class TranslatableEntityWithNetteObject
{

	use KnpTranslatable;
	use ZenifyTranslatable;

}
