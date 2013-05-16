<?php

namespace ClassModule\Model\Entity;


class Attribute extends \Model\Entity\BaseEntity
{


	public function toUML() {
		$res = '';
		switch ($this->visibility) {
			case 'public': $res.='+'; break;
			case 'private': $res.='-'; break;
			case 'protected': $res.='#'; break;
			case 'package': $res.='~'; break;
			default: /** no sign */
		}
		if ($this->derived) $res.='/';
		if ($this->static) $res.='static ';
		$res.=$this->name;
		if ($this->type !== '')
			$res.=':'.$this->type;
		if ($this->multiplicity)
			$res.='['.$this->multiplicity.']';
		if ($this->defaultValue)
			$res.='='.$this->defaultValue;
		return $res;
	}

}
