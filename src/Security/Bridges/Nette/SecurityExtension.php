<?php


namespace Holabs\Security\Bridges\Nette;

use Holabs\Security\Container;
use Nette\DI\Extensions\ExtensionsExtension;
use Nette\DI\Statement;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2016, Tomáš Holan [www.tomasholan.eu]
 */
class SecurityExtension extends ExtensionsExtension {

	public $defaults = [
		'authenticators' => [],
	];

	public function loadConfiguration() {
		$this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('container'))
			->setClass(Container::class);

		// Add authenticators
		$this->setup();
	}

	protected function setup() {
		$builder = $this->getContainerBuilder();
		$helper = method_exists('Nette\DI\Helpers', 'filterArguments') ? 'Nette\DI\Helpers' : 'Nette\DI\Compiler';
		$containerDefinition = $builder->getDefinition($this->prefix('container'));

		foreach ((array)$this->config['authenticators'] as $name => $item) {
			$containerDefinition->addSetup(
				'addAuthenticator',
				$helper::filterArguments([
					is_string($item) ? new Statement($item) : $item,
					$name
				]));
		}
	}


}