<?php


namespace Holabs\Security;

use AsIS\Exceptions\VerificationException;
use Nette\Application\ForbiddenRequestException;
use Nette\InvalidStateException;
use Nette\Security\IAuthenticator;
use Nette\Security\IAuthorizator;
use Nette\Security\User as NetteUser;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      asis/security
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 *
 * @method onVerify(User $user)
 */
class User extends NetteUser {

	/** @var callable[]  function (User $sender); Occurs when the user is successfully self-verify */
	public $onVerify;

	/** @var IVerificator */
	private $verificator;

	/**
	 * User constructor.
	 * @param IUserStorage        $storage
	 * @param IAuthenticator|null $authenticator
	 * @param IVerificator|null   $verificator
	 * @param IAuthorizator|null  $authorizator
	 */
	public function __construct(
		IUserStorage $storage,
		IAuthenticator $authenticator = NULL,
		IVerificator $verificator = NULL,
		IAuthorizator $authorizator = NULL
	) {
		parent::__construct($storage, $authenticator, $authorizator);
		$this->verificator = $verificator;
	}

	/**
	 * @param mixed $code
	 * @throws ForbiddenRequestException
	 * @throws VerificationException
	 */
	public function verify($code) {

		$identity = $this->getIdentity();

		if (!$identity) {
			throw new ForbiddenRequestException('User is not authenticated');
		}

		assert($identity instanceof IIdentity, "Identity have to be instace of" . IIdentity::class);

		if (is_bool($code) && func_num_args() === 1) {
			$this->getStorage()->setVerified($code);
		} else {
			$this->getVerificator()->verify($identity->getSecretCode(), ... func_get_args());
			$this->getStorage()->setVerified(TRUE);
		}

		if ($this->isVerified()) {
			$this->onVerify($this);
		}
	}

	/**
	 * @return bool
	 */
	public function isVerified(): bool {
		return $this->getStorage()->isVerified();
	}

	/**
	 * Sets authentication handler.
	 * @param IVerificator $handler
	 * @return static
	 */
	public function setVerificator(IVerificator $handler) {
		$this->verificator = $handler;

		return $this;
	}


	/**
	 * Returns authentication handler.
	 * @param bool $throw
	 * @return IVerificator|null
	 */
	public function getVerificator(bool $throw = TRUE) {
		if ($throw && !$this->verificator) {
			throw new InvalidStateException('Verificator has not been set.');
		}

		return $this->verificator;
	}

	/**
	 * Enables log out after inactivity.
	 * @param  string|int|\DateTimeInterface number of seconds or timestamp
	 * @param  int|bool  flag IUserStorage::CLEAR_IDENTITY
	 * @return static
	 */
	public function setVerificationExpiration($time, $flags = 0) {
		$this->getStorage()->setVerifyExpiration($time, $flags);

		return $this;
	}

}