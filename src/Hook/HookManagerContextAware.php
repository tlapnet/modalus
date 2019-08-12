<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Hook;

use Contributte\Utils\Monad\Optional;
use Nette\DI\Container;

class HookManagerContextAware implements IHookManager
{

	/** @var IHookManager */
	private $inner;

	/** @var Container */
	private $context;

	/** @var string[][] */
	private $map = [];

	public function __construct(IHookManager $inner, Container $context)
	{
		$this->inner = $inner;
		$this->context = $context;
	}

	/**
	 * @param string $service
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function hook(string $hook, $service): void
	{
		if (!isset($this->map[$hook])) {
			$this->map[$hook] = [];
		}

		$this->map[$hook][] = $service;
	}

	/**
	 * @param array<int,mixed> $args
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function apply(string $hook, ...$args): Optional
	{
		if (isset($this->map[$hook])) {
			foreach ($this->map[$hook] as $service) {
				$this->inner->hook($hook, $this->context->getService($service));
			}

			$this->map[$hook] = [];
		}

		return $this->inner->apply($hook, ...$args);
	}

}
