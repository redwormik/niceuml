<?php

namespace Model;

use Nette\Security\User;
use NiceDAO\Selection;



class Project extends Base {


	public function filterAllowed(User $user, Selection $table, $action = NULL) {
		$table->where('user_user_project:user_id', $user->id);
	}

}
