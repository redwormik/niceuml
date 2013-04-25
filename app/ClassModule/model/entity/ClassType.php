<?php

namespace ClassModule\Model\Entity;


class ClassType extends \Model\Entity\ElementChild {

	public function getAttributes() {
		return $this->related('class_attribute')->select('class_attribute.*');
	}


	public function getOperations() {
		return $this->related('class_operation')->select('class_operation.*');
	}


	public function setAttributes($attributes) {
		$this->related('class_attribute')->delete();
		foreach ($attributes as $attr)
			$this->related('class_attribute')->insert($attr);
	}


	public function setOperations($operations) {

		$this->related('class_operation')->delete();
		foreach ($operations as $oName => $op) {
			$this->related('class_operation')->insert($op);
		}
	}


}
