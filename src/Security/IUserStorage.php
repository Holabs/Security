<?php


namespace Holabs\Security;

use Nette\Security\IUserStorage as INetteUserStorage;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IUserStorage extends INetteUserStorage {

	/**
	 * Sets the verified status of this user.
	 * @param  bool
	 * @return static
	 */
	public function setVerified(bool $state);

	/**
	 * Is this user verified?
	 * @return bool
	 */
	public function isVerified(): bool;

	/**
	 * Enables log out after inactivity.
	 * @param  string|int|\DateTimeInterface Number of seconds or timestamp
	 * @param  int  flag IUserStorage::CLEAR_IDENTITY
	 * @return static
	 */
	public function setVerifyExpiration($time, $flags = 0);

}