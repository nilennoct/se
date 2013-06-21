<?php

function updateSession($user) {
	session('uinfo', array('role' => $user[ROLE], 'seller' => $user[ISSELLER]));
	return $user;
}