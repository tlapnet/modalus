<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Modalus\Fixtures\Hook;

use Exception;
use Tlapnet\Modalus\Annotation\Hook;
use Tlapnet\Modalus\Hook\IHookInvoker;

/**
 * @Hook("hookName")
 */
class ErrorHookInvoker implements IHookInvoker
{

	public function __invoke(): void
	{
		throw new Exception('Me fail English? That\'s unpossible.');
	}

}
