<?php


namespace Holabs\Security;

use Nette\InvalidStateException;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2016, Tomáš Holan [www.tomasholan.eu]
 */
class Container {

	use SmartObject;

	/** @var Authenticator[]|ArrayHash */
	private $authenticators;

	/**
	 * Container constructor
	 */
	public function __construct() {
		$this->authenticators = new ArrayHash();
	}

	/**
	 * @param string $name
	 * @return Authenticator
	 */
	public function getAuthenticator($name) {
		if (!$this->getAuthenticators()->offsetExists($name)){
			throw new InvalidStateException("Udenfined authenticator '{$name}'");
		}

		return $this->getAuthenticators()->offsetGet($name);
	}

	/**
	 * @param Authenticator $authenticator
	 * @param string        $name
	 * @return self
	 */
	public function addAuthenticator(Authenticator $authenticator, $name) {
		$this->authenticators->offsetSet($name, $authenticator);

		return $this;
	}

	/**
	 * @return Authenticator[]|ArrayHash
	 */
	public function getAuthenticators() {
		return $this->authenticators;
	}

}