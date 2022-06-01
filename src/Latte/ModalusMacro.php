<?php declare(strict_types = 1);

namespace Tlapnet\Modalus\Latte;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\MacroTokens;
use Latte\PhpWriter;
use Tlapnet\Modalus\Exception\Logical\InvalidStateException;

class ModalusMacro extends MacroSet
{

	/** @var string */
	private $component;

	public static function register(Compiler $compiler, string $component = 'modals'): void
	{
		$self = new static($compiler);
		$self->component = $component;

		$self->addMacro('modalus', [$self, 'macroModalus']);
	}

	public function macroModalus(MacroNode $node, PhpWriter $writer): string
	{
		if (!$node->args) {
			throw new InvalidStateException('Macro {modalus} cannot be empty');
		}

		// Get modal name
		$modal = $node->tokenizer->fetchWord();

		if ($modal === null) {
			throw new InvalidStateException('Macro {modalus} must contain modal name as 1st argument');
		}

		$params = [
			'do' => $this->component . '-open',
			$this->component . '-name' => $modal,
		];

		while ($node->tokenizer->nextToken()) {
			if ($node->tokenizer->isCurrent(MacroTokens::T_SYMBOL)) {
				$id = $node->tokenizer->currentValue();

				$node->tokenizer->nextToken('=>');

				if ($node->tokenizer->isNext(MacroTokens::T_WHITESPACE)) {
					$node->tokenizer->nextToken();
				}

				$value = $node->tokenizer->isNext(MacroTokens::T_STRING)
					? trim($node->tokenizer->joinUntil(','), '\'')
					: $node->tokenizer->joinUntil(',');

				$params['"'.$this->component . '-args[' . $id . ']"'] = trim($value);
			}
		}

		$args = '';

		foreach ($params as $key => $value) {
			$args .= $writer->write('%word=>%word,', $key, $value);
		}

		return $writer->write('echo %escape(%modify($this->global->uiPresenter->link("this", [%raw])));', $args);
	}

}
