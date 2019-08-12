<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\UI\Modals;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;
use Tlapnet\Modalus\Exception\Logical\InvalidStateException;
use Tlapnet\Modalus\Hook\Hooks;
use Tlapnet\Modalus\Hook\IHookManager;
use Tlapnet\Modalus\Model\IModalContainer;
use Tlapnet\Modalus\UI\IModalControlFactory;
use Tlapnet\Modalus\UI\Modal\BaseModalControl;

/**
 * @property-read Template $template
 */
class ModalsControl extends Control
{

	/** @var mixed[] */
	public $args = [];

	/** @var IModalContainer */
	protected $container;

	/** @var IHookManager */
	private $hookManager;

	public function __construct(IModalContainer $container, IHookManager $hookManager)
	{
		$this->container = $container;
		$this->hookManager = $hookManager;
	}

	/**
	 * @param mixed[] $params
	 */
	public function saveState(array &$params): void
	{
		// In AJAX mode, append args into control state (simulate @persistent)
		if ($this->presenter->isAjax()) {
			$params['args'] = $this->args;
		}

		parent::saveState($params);
	}

	/**
	 * @param mixed[] $params
	 */
	public function loadState(array $params): void
	{
		parent::loadState($params);

		// In non-AJAX mode, drain args from control state (simulate @persistent)
		if (!$this->presenter->isAjax() && isset($params['args'])) {
			$this->args = $params['args'];
		}
	}

	/**
	 * @return BaseModalControl
	 */
	protected function createComponent(string $name): ?IComponent
	{
		return $this->createModalFactory($name);
	}

	protected function createModalFactory(string $name): BaseModalControl
	{
		// Lookup for modal factory
		$factory = $this->container->getModal($name);
		$metadata = $this->container->getMetadata($name);

		if ($factory === null) {
			// Factory not found, trigger hook.
			// Return fallback(ed) modal or throw exception
			$factory = $this->hookManager->apply(Hooks::HOOK_UI_FACTORY_NOT_FOUND, $name)
				->orElseThrow(new InvalidStateException(sprintf('Unknown modal "%s"', $name)))
				->get();
		} else {
			// Factory found, trigger hook.
			$factory = $this->hookManager->apply(Hooks::HOOK_UI_FACTORY_FOUND, $factory, $metadata)
				->orElse($factory)
				->get();
		}

		return $this->createModal($factory);
	}

	protected function createModal(IModalControlFactory $factory): BaseModalControl
	{
		// Create desired modal control
		$modal = $factory->create($this->args);

		// Modal created, trigger hook.
		$modal = $this->hookManager->apply(Hooks::HOOK_UI_MODAL_CREATED)
			->orElse($modal)
			->get();

		return $modal;
	}

	/**
	 * @param mixed[]|null $args
	 */
	public function handleOpen(string $name, ?array $args = null): void
	{
		if (!$this->presenter->isAjax()) {
			throw new InvalidStateException('Modal can be opened only in AJAX mode');
		}

		$this->args = (array) $args;
		$this->presenter->payload->modalusMode = true;
		$this->presenter->payload->modalusSnippet = 'snippet-' . $this->getUniqueId() . '-modal';
		$this->presenter->payload->modalusWrapper = $this->getUniqueId();
		$this->presenter->payload->modalusModal = $this->getUniqueId() . '-' . $name;
		$this->redrawControl('modal');
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/modal.latte');
		$this->template->name = $this->getParameter('name');
		$this->template->id = $this->getUniqueId() . '-' . $this->getParameter('name', 'modal');
		$this->template->render();
	}

}
