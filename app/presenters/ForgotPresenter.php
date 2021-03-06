<?php


/** Presenter for password resetting */
final class ForgotPresenter extends BasePresenter
{

	/** @var MailFactory */
	protected $mailFactory = NULL;
	/** @var Model\UserDAO */
	protected $users;


	/** @return void */
	public function injectMailFactory(MailFactory $mailFactory)
	{
		$this->mailFactory = $mailFactory;
	}


	/** @return void */
	public function injectUsers(Model\UserDAO $users)
	{
		$this->users = $users;
	}


	/** @return void */
	public function startup()
	{
		parent::startup();
		if ($this->user->loggedIn)
			$this->redirect(':Homepage:');
	}


	/** @return void */
	public function actionDefault($code = NULL)
	{
		if ($code !== NULL)
			$this->processCode($code);
	}


	/** @return Nette\Application\UI\Form */
	protected function createComponentForgotForm()
	{
		$form = $this->createForm();
		$form->addText('login','E-mail')
			->setRequired();
		$form->addSubmit('send');
		$form->onSuccess[] = $this->forgotFormSucceeded;
		return $form;
	}


	/** @return void */
	public function forgotFormSucceeded($form)
	{
		$user = $this->users->table()->where((array) $form->values)->fetch();
		if (!$user) {
			$form['login']->addError('No user with this e-mail exists.');
			return;
		}

		do {
			$password = $user->resetPassword();
		} while ($this->users->table()->where('passwordNewCode', $user->passwordNewCode)->fetch());

		$this->users->save($user);

		$this->mailFactory->create('forgot', $this->createTemplate(), array( 'user' => $user, 'password' => $password ))
			->addTo($user->email, $user->fullName)
			->send();

		$this->flashMessage("E-mail with a new password has been sent to $user->email. Enter the code from the e-mail to change your password.");
		$this->redirect('this#code');
	}


	/** @return Nette\Application\UI\Form */
	protected function createComponentForgotCodeForm()
	{
		$form = $this->createForm();
		$form->addText('controlCode','Control code')
			->setRequired();
		$form->addSubmit('send');
		$form->onSuccess[] = $this->forgotCodeFormSucceeded;
		return $form;
	}


	/** @return void */
	public function forgotCodeFormSucceeded($form)
	{
		$this->processCode($form['controlCode']->value);
	}


	/** @return void */
	protected function processCode($code)
	{
		$user = $this->users->table()->where('passwordNewCode', $code)->fetch();
		if (!$user) {
			$this['forgotCodeForm']['controlCode']->addError('No user with this code exist.');
			return;
		}
		$user->resetPasswordConfirm();
		$this->users->save($user);
		$this->flashMessage('The password was successfully changed. You can log in now.');
		$this->redirect('this', array('code' => NULL));
	}

}
