<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Modalus\Unit\DI\Annotation;

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use PHPUnit\Framework\TestCase;
use Tests\Tlapnet\Modalus\Fixtures\Hook\ErrorHookInvoker;
use Tests\Tlapnet\Modalus\Fixtures\UI\Modal\NoopModalControlFactory;
use Throwable;
use Tlapnet\Modalus\DI\ModalusExtension;
use Tlapnet\Modalus\Hook\IHookManager;
use Tlapnet\Modalus\Model\IModalContainer;

class DoctrineAnnotationLoaderTest extends TestCase
{

	public function testHookRegistration(): void
	{
		// Exception to check that hook is found and registered
		$this->expectException(Throwable::class);
		$this->expectExceptionMessage('Me fail English? That\'s unpossible.');

		$loader = new ContainerLoader(__DIR__ . '/../../../../temp/tests', true);
		$class = $loader->load(function (Compiler $compiler): void {
			$compiler->addConfig([
				'services' => [
					ErrorHookInvoker::class,
				],
			]);
			$compiler->addExtension('modalus', new ModalusExtension());
		}, microtime(true));
		/** @var Container $container */
		$container = new $class();

		$hookManager = $container->getByType(IHookManager::class);
		$hookManager->apply('hookName', []);
	}

	public function testModalRegistration(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../../../temp/tests', true);
		$class = $loader->load(function (Compiler $compiler): void {
			$compiler->addConfig([
				'services' => [
					NoopModalControlFactory::class,
				],
			]);
			$compiler->addExtension('modalus', new ModalusExtension());
		}, microtime(true));
		/** @var Container $container */
		$container = new $class();

		$modalContainer = $container->getByType(IModalContainer::class);
		$this->assertInstanceOf(NoopModalControlFactory::class, $modalContainer->getModal('modalName'));
	}

}
