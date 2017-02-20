Holabs/Security
===============

Adding ability for use more authentication methods a.k.a basic form, facebook and google in same time.
THIS package contains only basic form.

Installation
------------

**Requirements:**
 - php 5.6+
 - [Holabs/UI](https://github.com/Holabs/UI)
 - [nette/di](https://github.com/nette/di)
 - [nette/utils](https://github.com/nette/utils)
 
```sh
composer require holabs/security
```

Configuration
-------------
```yaml
security: Holabs\Security\Bridges\Nette\SecurityExtension

security:
	authenticators:
		basic: Holabs\Security\Authenticators\Basic
#		auth_name: authenticator\class
```

In **authenticators** you can define all your authenticators. Name is used for component.

Using
-----
Your **SignPresenter** now can looks like this:

```php
<?php 

use Holabs\Security\Container;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Multiplier;

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>, D-Music s.r.o. [www.d-music.cz]
 * @package      holabs/security
 * @copyright    Copyright © 2016, D-Music s.r.o. [www.d-music.cz]
 */
class SignPresenter extends Presenter {
	
	/** @var Container @inject */
    public $container;
    
    public function renderIn(){
		$this->template->authenticators = $this->container->getAuthenticators();
	}
	
	/**
	 * @return Multiplier
	 */
	protected function createComponentLogin(){
		return new Multiplier(function($name) {

			return $this->container->getAuthenticator($name);

		});
	}
}
```

No you can render what you want. You can render all in `in.latte`

```latte
<div n:inner-foreach="$authenticators as $name => $authenticator">
	{control $authenticator}
	{*control $authenticators-{$name}*}
</div>
```

OR only which you want(authenticator name is same as you define in config file)

```latte
<div>
	{control $authenticators-basic}
</div>
```