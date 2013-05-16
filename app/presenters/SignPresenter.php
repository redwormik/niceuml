<?php


final class SignPresenter extends BasePresenter {

	protected $backlink;


	public function actionOut()
	{
		if ($this->user->loggedIn) {
			$this->user->logout();
			$this->flashMessage('You have been logged out.');
		}
		$this->redirect('in');
	}


	public function actionIn($backlink = NULL)
	{
		if ($this->user->loggedIn) {
			if ($backlink !== NULL)
				$this->restoreRequest($backlink);
			$this->redirect('Homepage:');
		}
		$this->backlink = $backlink;
	}


	protected function createComponentLoginForm()
	{
		$form = $this->formFactory->create();
		$form->addText('login','E-mail')
			->setRequired();
		$form->addPassword('password','Password')
			->setRequired();
		$form->addSubmit('send', 'Log in');
		$form->onSuccess[] = $this->loginFormSucceeded;
		return $form;
	}


	public function loginFormSucceeded($form)
	{
		$values = $form->getValues();
		try {
			$this->user->login($values->login, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}
		if ($this->backlink !== NULL)
			$this->restoreRequest($this->backlink);
		$this->redirect('Homepage:');
	}

}
