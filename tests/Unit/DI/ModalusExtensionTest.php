<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Modalus\Unit\DI;

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use PHPUnit\Framework\TestCase;
use Tlapnet\Modalus\DI\ModalusExtension;
use Tlapnet\Modalus\Hook\HookManagerContextAware;
use Tlapnet\Modalus\Hook\IHookManager;
use Tlapnet\Modalus\Model\IModalContainer;
use Tlapnet\Modalus\Model\ModalContainerContextAware;
use Tlapnet\Modalus\UI\IModalsControlFactory;

class ModalusExtensionTest extends TestCase
{

	public function testRegister(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../../temp/tests', true);
		$class = $loader->load(function (Compiler $compiler): void {
			$compiler->addExtension('modalus', new ModalusExtension());
		});
		/** @var Container $container */
		$container = new $class();

		$this->assertInstanceOf(ModalContainerContextAware::class, $container->getByType(IModalContainer::class));
		$this->assertInstanceOf(HookManagerContextAware::class, $container->getByType(IHookManager::class));
		$this->assertInstanceOf(IModalsControlFactory::class, $container->getByType(IModalsControlFactory::class));
	}

}
