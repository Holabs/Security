<?php


namespace Holabs\Security;

use Nette\Security\Identity as NetteIdentity;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Identity extends NetteIdentity implements IIdentity {

	/** @var mixed|null */
	private $secure = NULL;

	/**
	 * @inheritDoc
	 */
	public function __construct($id, $roles = NULL, $data = NULL, $secure = NULL) {
		parent::__construct($id, $roles, $data);
		$this->setSecure($secure);
	}


	/**
	 * @return mixed|null
	 */
	public function getSecretCode() {
		return $this->secure;
	}

	/**
	 * @param mixed|null $secure
	 * @return Identity
	 */
	public function setSecure($secure = null): self {
		$this->secure = $secure;

		return $this;
	}
}