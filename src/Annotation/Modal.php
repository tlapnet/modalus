<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Modal
{

	/** @var string */
	private $name;

	/**
	 * @param mixed[] $values
	 */
	public function __construct(array $values)
	{
		if (!isset($values['value'])) {
			throw new AnnotationException('No @Modal name given');
		}

		$value = $values['value'];

		if (strlen((string) $value) <= 0) {
			throw new AnnotationException('Empty @Modal name given');
		}

		$this->name = $value;
	}

	public function getName(): string
	{
		return $this->name;
	}

}
