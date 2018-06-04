<?php


namespace Holabs\Security;

use Nette\Security\IIdentity as INetteIdentity;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IIdentity extends INetteIdentity {

	/**
	 * @return mixed|null
	 */
	public function getSecretCode();

}