<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Hook;

use Contributte\Utils\Monad\Optional;

interface IHookManager
{

	/**
	 * @param object|callable $invoker
	 */
	public function hook(string $hook, $invoker): void;

	/**
	 * @param array<int,mixed> $args
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function apply(string $hook, ...$args): Optional;

}
