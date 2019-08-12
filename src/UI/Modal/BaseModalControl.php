<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\UI\Modal;

use Nette\Application\UI\Control;
use Tlapnet\Modalus\UI\Modals\ModalsControl;

abstract class BaseModalControl extends Control
{

	public function lookupModals(): ?ModalsControl
	{
		/** @var ModalsControl|NULL $component */
		$component = $this->lookup(ModalsControl::class);

		return $component;
	}

}
