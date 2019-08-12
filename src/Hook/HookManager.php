<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Hook;

use Contributte\Utils\Monad\Optional;

class HookManager implements IHookManager
{

	/** @var callable[][] */
	private $hooks = [];

	/**
	 * @param object|callable $invoker
	 */
	public function hook(string $hook, $invoker): void
	{
		if (!isset($this->hooks[$hook])) {
			$this->hooks[$hook] = [];
		}

		$this->hooks[$hook][] = $invoker;
	}

	/**
	 * @param array<int,mixed> $args
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function apply(string $hook, ...$args): Optional
	{
		if (!isset($this->hooks[$hook])) {
			return Optional::empty();
		}

		foreach ($this->hooks[$hook] as $invoker) {
			$res = $invoker(...$args);

			if ($res) {
				return Optional::of($res);
			}
		}

		return Optional::empty();
	}

}
