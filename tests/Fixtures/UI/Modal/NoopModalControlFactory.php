<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Modalus\Fixtures\UI\Modal;

use Tlapnet\Modalus\Annotation\Modal;
use Tlapnet\Modalus\UI\IModalControlFactory;
use Tlapnet\Modalus\UI\Modal\BaseModalControl;

/**
 * @Modal("modalName")
 */
class NoopModalControlFactory implements IModalControlFactory
{

	/**
	 * @param mixed[]|null $args
	 */
	public function create(?array $args): BaseModalControl
	{
		return new class extends BaseModalControl
		{

		};
	}

}
