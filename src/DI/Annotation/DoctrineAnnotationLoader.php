<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\DI\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nette\DI\ContainerBuilder;
use ReflectionClass;
use Tlapnet\Modalus\Annotation\Hook;
use Tlapnet\Modalus\Annotation\Modal;
use Tlapnet\Modalus\Annotation\Security;
use Tlapnet\Modalus\Exception\Logical\InvalidStateException;
use Tlapnet\Modalus\Hook\IHookInvoker;
use Tlapnet\Modalus\UI\IModalControlFactory;

class DoctrineAnnotationLoader
{

	/** @var ContainerBuilder */
	private $builder;

	/** @var AnnotationReader|null */
	private $reader;

	public function __construct(ContainerBuilder $builder)
	{
		$this->builder = $builder;
	}

	/**
	 * @return mixed[]
	 */
	public function loadHooks(): array
	{
		$definitions = $this->builder->findByType(IHookInvoker::class);
		$hooks = [];

		foreach ($definitions as $service => $definition) {
			if ($definition->getType() === null) {
				throw new InvalidStateException(sprintf('Invalid type defined for service "%s"', $service));
			}

			$metadata = $this->parseHookAnnotations($definition->getType());

			// Skip if class has no annotation
			if ($metadata === []) {
				continue;
			}

			$hooks[] = [
				'service' => $service,
				'metadata' => $metadata,
			];
		}

		return $hooks;
	}

	/**
	 * @return mixed[]
	 */
	public function loadModals(): array
	{
		$definitions = $this->builder->findByType(IModalControlFactory::class);
		$modals = [];

		foreach ($definitions as $service => $definition) {
			if ($definition->getType() === null) {
				throw new InvalidStateException(sprintf('Invalid type defined for service "%s"', $service));
			}

			$metadata = $this->parseModalAnnotations($definition->getType());

			// Skip if class has no annotation
			if ($metadata === []) {
				continue;
			}

			$modals[] = [
				'service' => $service,
				'metadata' => $metadata,
			];
		}

		return $modals;
	}

	/**
	 * @return mixed[]
	 */
	private function parseModalAnnotations(string $class): array
	{
		$annotations = $this->getReader()->getClassAnnotations(new ReflectionClass($class));

		$metadata = [
			'name' => null,
			'security' => [
				'roles' => null,
			],
		];

		foreach ($annotations as $annotation) {
			// Parse @Modal
			if (get_class($annotation) === Modal::class) {
				/** @var Modal $annotation */
				$metadata['name'] = $annotation->getName();

				continue;
			}

			// Parse @Security
			if (get_class($annotation) === Security::class) {
				/** @var Security $annotation */
				$metadata['security']['roles'] = $annotation->getRoles();

				continue;
			}
		}

		// No @Modal annotation used
		if ($metadata['name'] === null) {
			return [];
		}

		return $metadata;
	}

	/**
	 * @return mixed[]
	 */
	private function parseHookAnnotations(string $class): array
	{
		$annotations = $this->getReader()->getClassAnnotations(new ReflectionClass($class));

		$metadata = [
			'name' => null,
		];

		foreach ($annotations as $annotation) {
			// Parse @Hook
			if (get_class($annotation) === Hook::class) {
				/** @var Hook $annotation */
				$metadata['name'] = $annotation->getName();

				continue;
			}
		}

		// No @Hook annotation used
		if ($metadata['name'] === null) {
			return [];
		}

		return $metadata;
	}

	private function getReader(): AnnotationReader
	{
		if ($this->reader === null) {
			AnnotationRegistry::registerLoader('class_exists');
			$this->reader = new AnnotationReader();
		}

		return $this->reader;
	}

}
