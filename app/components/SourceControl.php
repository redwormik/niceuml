<?php

use Model\ElementSource,
	Model\RelationSource,
	Model\DiagramSource,
	Model\Entity\Project,
	Nette\Database\Connection,
	Nette\Utils\Neon,
	Nette\Utils\NeonException;


class SourceControl extends BaseControl
{

	/** @var Model\Entity\Project */
	protected $project;

	protected $formFactory;
	protected $elementSource;
	protected $relationSource;
	protected $diagramSource;
	protected $db;


	public function __construct(FormFactory $formFactory, ElementSource $elementSource, RelationSource $relationSource, DiagramSource $diagramSource, Connection $db)
	{
		$this->formFactory = $formFactory;
		$this->elementSource = $elementSource;
		$this->relationSource = $relationSource;
		$this->diagramSource = $diagramSource;
		$this->db = $db;
	}


	public function setProject(Project $project)
	{
		$this->project = $project;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->addTextarea('source', 'Source', NULL, 10);
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}


	public function formSucceeded($form)
	{

		$source = $form['source']->value;
		try {
			$source = (array) Neon::decode($source);
		} catch (NeonException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$known = array('elements', 'relations', 'diagrams');
		if ($error = array_diff(array_keys($source), $known)) {
			$form->addError("Unknown section '" . implode("', '", $error) . "'.");
			return;
		}

		$source += array('elements' => array(), 'relations' => array(), 'diagrams' => array());

		$this->db->beginTransaction();
		try {
			$elements = $this->elementSource->load($this->project, $source['elements']);
			$relations = $this->relationSource->load($this->project, $source['relations'], $elements);
			$diagrams = $this->diagramSource->load($this->project, $source['diagrams'], $elements);
			$this->db->commit();
		} catch (SourceException $e) {
			$this->db->rollback();
			$form->addError($e->getMessage());
			return;
		} catch (Exception $e) {
			$this->db->rollback();
			Nette\Diagnostics\Debugger::log($e);
			$form->addError("Unknown error occured.");
			return;
		}

		$this->presenter->flashMessage('Source loaded.', 'success');
		$this->redirect('this');
	}


	public function render()
	{
		if ($this->project === NULL)
			throw new Nette\InvalidStateException('Missing project');

		if (!$this['form']->submitted) {
			$source = array(
				'elements' => $this->elementSource->dump($this->project),
				'relations' => $this->relationSource->dump($this->project),
				'diagrams' => $this->diagramSource->dump($this->project),
			);
			$this['form']['source']->defaultValue = Neon::encode($source, Neon::BLOCK);
		}

		$this->template->mode = NULL;
		parent::render();
	}


	public function renderScripts()
	{
		$this->template->mode = 'scripts';
		parent::render();
	}

}
