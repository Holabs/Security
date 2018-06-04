<?php


namespace Holabs\Security;

use AsIS\Exceptions\VerificationException;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
interface IVerificator {

	/** Exception error code */
	const INVALID_CODE = 1;

	/**
	 * @param $secure
	 * @param $code
	 * @throws VerificationException
	 */
	public function verify($secure, $code = null);

}