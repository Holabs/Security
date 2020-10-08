<?php


namespace Holabs\Security;

use Nette\Utils\DateTime;
use Nette\Http\UserStorage as NetteUserStorage;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      asis/security
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class UserStorage extends NetteUserStorage implements IUserStorage {

	/**
	 * Sets the verified status of this user.
	 * @param  bool
	 * @return static
	 */
	public function setVerified(bool $state) {
		$section = $this->getSessionSection(TRUE);
		$section->verified = $state;

		$section->verifyTime = $state ? time() : NULL; // informative value

		return $this;
	}


	/**
	 * Is this user verified?
	 * @return bool
	 */
	public function isVerified(): bool {
		$session = $this->getSessionSection(FALSE);

		return $session && $session->verified;
	}


	/**
	 * Enables log out after inactivity.
	 * @param  string|int|\DateTimeInterface Number of seconds or timestamp
	 * @param  int  flag IUserStorage::CLEAR_IDENTITY
	 * @return static
	 */
	public function setVerifyExpiration($time, $flags = 0) {
		$section = $this->getSessionSection(TRUE);
		if ($time) {
			$time = DateTime::from($time)->format('U');
			$section->expireVerifyTime = $time;
			$section->expireVerifyDelta = $time - time();
		} else {
			unset($section->expireVerifyTime, $section->expireVerifyDelta);
		}

		$section->setExpiration($time, 'bar'); // time check

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	protected function getSessionSection(bool $need): ?SessionSection {
		$session = parent::getSessionSection($need);

		if ($session !== NULL && (!$session->authenticated || !$session->verified)) {
			unset($session->expireVerifyTime, $session->expireVerifyDelta, $session->verifyTime);
		}

		return $session;
	}


}
