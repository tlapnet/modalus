<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Model;

use Tlapnet\Modalus\UI\IModalControlFactory;

class ModalContainer implements IModalContainer
{

	/** @var IModalControlFactory[] */
	protected $factories = [];

	/** @var mixed[] */
	protected $metadata = [];

	/**
	 * @param IModalControlFactory $factory
	 * @param mixed[] $metadata
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function register(string $name, $factory, array $metadata = []): void
	{
		$this->factories[$name] = $factory;
		$this->metadata[$name] = $metadata;
	}

	public function getModal(string $name): ?IModalControlFactory
	{
		return $this->factories[$name] ?? null;
	}

	/**
	 * @inheritdoc
	 */
	public function getMetadata(string $name): array
	{
		return $this->metadata[$name] ?? [];
	}

}
