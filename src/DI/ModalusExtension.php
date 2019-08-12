<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Tlapnet\Modalus\DI\Annotation\DoctrineAnnotationLoader;
use Tlapnet\Modalus\Hook\HookManager;
use Tlapnet\Modalus\Hook\HookManagerContextAware;
use Tlapnet\Modalus\Hook\IHookManager;
use Tlapnet\Modalus\Model\IModalContainer;
use Tlapnet\Modalus\Model\ModalContainer;
use Tlapnet\Modalus\Model\ModalContainerContextAware;
use Tlapnet\Modalus\UI\IModalsControlFactory;

final class ModalusExtension extends CompilerExtension
{

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('modalContainer'))
			->setType(IModalContainer::class)
			->setFactory(ModalContainerContextAware::class)
			->setArguments([new Statement(ModalContainer::class)]);

		$builder->addDefinition($this->prefix('hookManager'))
			->setType(IHookManager::class)
			->setFactory(HookManagerContextAware::class)
			->setArguments([new Statement(HookManager::class)]);

		$builder->addFactoryDefinition($this->prefix('modalsFactory'))
			->setImplement(IModalsControlFactory::class);
	}

	public function beforeCompile(): void
	{
		$this->compileAnnotations();
	}

	protected function compileAnnotations(): void
	{
		$builder = $this->getContainerBuilder();
		$loader = new DoctrineAnnotationLoader($builder);

		$modalContainerDef = $builder->getDefinition($this->prefix('modalContainer'));
		assert($modalContainerDef instanceof ServiceDefinition);
		$modals = $loader->loadModals();

		foreach ($modals as $modal) {
			$modalContainerDef->addSetup('register', [
				$modal['metadata']['name'],
				$modal['service'],
				$modal['metadata'],
			]);
		}

		$hookManagerDef = $builder->getDefinition($this->prefix('hookManager'));
		assert($hookManagerDef instanceof ServiceDefinition);
		$hooks = $loader->loadHooks();

		foreach ($hooks as $hook) {
			$hookManagerDef->addSetup('hook', [
				$hook['metadata']['name'],
				$hook['service'],
			]);
		}
	}

}
