<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Hook
{

	/** @var string */
	private $name;

	/**
	 * @param mixed[] $values
	 */
	public function __construct(array $values)
	{
		if (!isset($values['value'])) {
			throw new AnnotationException('No @Hook name given');
		}

		$value = $values['value'];

		if ($value === null || $value === '') {
			throw new AnnotationException('Empty @Hook name given');
		}

		$this->name = $value;
	}

	public function getName(): string
	{
		return $this->name;
	}

}
