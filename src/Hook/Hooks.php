<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Hook;

interface Hooks
{

	public const HOOK_UI_FACTORY_FOUND = 'ui.factory.found';
	public const HOOK_UI_FACTORY_NOT_FOUND = 'ui.factory.not_found';
	public const HOOK_UI_MODAL_CREATED = 'ui.modal.created';

}
