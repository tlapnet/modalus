<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Security
{

	/** @var string[]|null */
	private $roles;

	/**
	 * @param mixed[] $values
	 */
	public function __construct(array $values)
	{
		if (isset($values['roles'])) {
			$this->roles = $values['roles'];
		}
	}

	/**
	 * @return string[]|null
	 */
	public function getRoles(): ?array
	{
		return $this->roles;
	}

}
