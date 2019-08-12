<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\UI;

use Tlapnet\Modalus\UI\Modal\BaseModalControl;

interface IModalControlFactory
{

	/**
	 * @param mixed[]|null $args
	 */
	public function create(?array $args): BaseModalControl;

}
