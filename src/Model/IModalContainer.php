<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Model;

use Tlapnet\Modalus\UI\IModalControlFactory;

interface IModalContainer
{

	/**
	 * @param mixed $factory
	 * @param mixed[] $metadata
	 */
	public function register(string $name, $factory, array $metadata = []): void;

	public function getModal(string $name): ?IModalControlFactory;

	/**
	 * @return mixed[]
	 */
	public function getMetadata(string $name): array;

}
