<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\UI;

use Tlapnet\Modalus\UI\Modals\ModalsControl;

interface IModalsControlFactory
{

	public function create(): ModalsControl;

}
