<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Modalus\Unit\Hook;

use Nette\InvalidStateException;
use PHPUnit\Framework\TestCase;
use Tlapnet\Modalus\Hook\HookManager;

class HookManagerTest extends TestCase
{

	public function testNoHooks(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Invalid optional value');

		$manager = new HookManager();

		$optional = $manager->apply('missing');
		$optional->get();
	}

	public function testHookReturnNull(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Invalid optional value');

		$manager = new HookManager();

		$manager->hook('null', function () {
			return null;
		});

		$optional = $manager->apply('null');
		$optional->get();
	}

	public function testOk(): void
	{
		$manager = new HookManager();

		$manager->hook('ok', function () {
			return null;
		});
		$manager->hook('ok', function ($arg) {
			return $arg .= 'foo';
		});

		$optional = $manager->apply('ok', 'a_');
		$this->assertSame('a_foo', $optional->get());
	}

}
