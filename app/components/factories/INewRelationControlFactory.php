<?php


interface INewRelationControlFactory {

	/** @return Nette\ComponentModel\IComponent */
	function create(Model\Entity\Element $element);

}
