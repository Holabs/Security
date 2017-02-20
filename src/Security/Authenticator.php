<?php


namespace Holabs\Security;

use Holabs\UI\BaseControl;
use Nette\Security\User;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2016, Tomáš Holan [www.tomasholan.eu]
 *
 * @method onSuccess(Authenticator $sender, User $user) Occures when Authorization complete.
 * @method onFail(Authenticator $sender) Occures when authorization fails
 */
abstract class Authenticator extends BaseControl {

	/** @var \Closure[]|callable[]|array */
	public $onSuccess = [];

	/** @var \Closure[]|callable[]|array */
	public $onFail = [];

}