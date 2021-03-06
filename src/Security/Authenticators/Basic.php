<?php


namespace Holabs\Security\Authenticators;

use Holabs\Security\Authenticator;
use Holabs\UI\Form;
use Holabs\UI\FormFactory;
use Nette\Localization\ITranslator;
use Nette\Security\AuthenticationException;
use Nette\Security\IUserStorage;
use Nette\Security\User;
use Nette\Utils\ArrayHash;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/security
 * @copyright    Copyright © 2016, Tomáš Holan [www.tomasholan.eu]
 */
class Basic extends Authenticator {

	const DEFAULT_TEMPLATE = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'basic.latte';

	/** @var string */
	public static $DEFAULT_TEMPLATE = self::DEFAULT_TEMPLATE;

	/** @var User */
	private $user;

	/** @var FormFactory */
	private $formFactory;

	/**
	 * @param ITranslator $translator
	 * @param FormFactory $formFactory
	 * @param User        $user
	 */
	public function __construct(FormFactory $formFactory, User $user, ITranslator $translator = NULL) {
		parent::__construct($translator);
		$this->setTemplateFile(self::$DEFAULT_TEMPLATE);
		$this->formFactory = $formFactory;
		$this->user = $user;
	}

	public function render() {
		$this->template->setTranslator($this->getTranslator());
		$this->template->setFile($this->getTemplateFile());
		$this->template->render();
	}

	/**
	 * @return Form
	 */
	protected function createComponentForm() {
		$form = $this->formFactory->create($this->getTranslator());
		$form->addText('username', 'basic_authenticator.form.username.label')
			->setAttribute('placeholder', 'basic_authenticator.form.username.placeholder')
			->setRequired('basic_authenticator.form.username.required');
		$form->addPassword('password', 'basic_authenticator.form.password.label')
			->setAttribute('placeholder', 'basic_authenticator.form.password.placeholder')
			->setRequired('basic_authenticator.form.password.required');
		$form->addCheckbox('remember', 'basic_authenticator.form.remember.label');
		$form->addSubmit('submit', 'basic_authenticator.form.submit.caption');

		$form->onSuccess[] = function (Form $form, ArrayHash $values) {
			$this->formSuccess($form, $values);
		};

		return $form;
	}

	/**
	 * @param Form      $form
	 * @param ArrayHash $values
	 */
	private function formSuccess(Form $form, ArrayHash $values) {

		$user = $this->user;

		try {
			$user->login($values->username, $values->password);
			$user->setExpiration(null, IUserStorage::CLEAR_IDENTITY);
		} catch (AuthenticationException $e) {
			$this->flashMessage('basic_authenticator.login.fail', 'danger');
			$this->onFail($this, (array) $values);

			return;
		}

		$this->onSuccess($this, $this->user);
	}

}
