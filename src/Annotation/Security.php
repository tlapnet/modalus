<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Security
{

	/** @var string|null */
	private $role;

	/**
	 * @param mixed[] $values
	 */
	public function __construct(array $values)
	{
		if (isset($values['role'])) {
			$this->role = $values['role'];
		}
	}

	public function getRole(): ?string
	{
		return $this->role;
	}

}
