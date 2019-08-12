<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Modalus\Unit\Model;

use PHPUnit\Framework\TestCase;
use Tests\Tlapnet\Modalus\Fixtures\UI\Modal\NoopModalControlFactory;
use Tlapnet\Modalus\Model\ModalContainer;

class ModalContainerTest extends TestCase
{

	public function testOk(): void
	{
		$container = new ModalContainer();
		$factory = new NoopModalControlFactory();
		$container->register('modalName', $factory, ['foo' => 'bar']);

		$this->assertSame($factory, $container->getModal('modalName'));
		$this->assertSame(['foo' => 'bar'], $container->getMetadata('modalName'));
	}

	public function testMissing(): void
	{
		$container = new ModalContainer();
		$this->assertNull($container->getModal('missing'));
	}

}
