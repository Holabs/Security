<?php


namespace Holabs\Security;

use Nette\InvalidStateException;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2016, Tomáš Holan [www.tomasholan.eu]
 * @method onSuccess(Container $sender, User $user) Occures when Authorization complete.
 * @method onFail(Container $sender, array $data = NULL) Occures when authorization fails
 */
class Container {

	use SmartObject;
	
	/** @var \Closure[]|callable[]|array */
	public $onSuccess = [];

	/** @var \Closure[]|callable[]|array */
	public $onFail = [];

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
		$authenticator->onSuccess[] = function($sender, $user) {
			$this->onSuccess($this, $user);
		};
		$authenticator->onFail[] = function($sender, $data = NULL) {
			$this->onFail($this, $data);
		};

		return $this;
	}

	/**
	 * @return Authenticator[]|ArrayHash
	 */
	public function getAuthenticators() {
		return $this->authenticators;
	}

}
