<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Model;

use Nette\DI\Container;
use Tlapnet\Modalus\UI\IModalControlFactory;

class ModalContainerContextAware implements IModalContainer
{

	/** @var IModalContainer */
	private $inner;

	/** @var Container */
	private $context;

	/** @var string[] */
	private $map = [];

	/** @var mixed[] */
	private $metadata = [];

	public function __construct(IModalContainer $inner, Container $context)
	{
		$this->inner = $inner;
		$this->context = $context;
	}

	/**
	 * @param string $service
	 * @param mixed[] $metadata
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function register(string $name, $service, array $metadata = []): void
	{
		$this->map[$name] = $service;
		$this->metadata[$name] = $metadata;
	}

	public function getModal(string $name): ?IModalControlFactory
	{
		if (isset($this->map[$name])) {
			$this->inner->register($name, $this->context->getService($this->map[$name]), $this->metadata[$name]);
			unset($this->map[$name]);
		}

		return $this->inner->getModal($name);
	}

	/**
	 * @inheritdoc
	 */
	public function getMetadata(string $name): array
	{
		if (isset($this->metadata[$name])) {
			return $this->metadata[$name];
		}

		return [];
	}

}
