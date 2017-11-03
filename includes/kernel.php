<?php
require_once('configs/config.php');
require_once('includes/allutil.php');
require_once('includes/db.php');
require_once('includes/smarty.php');

// Таблица соответствия номеров переводов
function CheckPwd($username, $shapass)
{
	// Проверка пароля пользователя
	// -1: пользователя не существует
	//  0: пароли не совпадают
	// >0: id пользователя
	require_once('includes/DbSimple/Generic.php');
	global $rDB;
	global $AoWoWconf;
	$user_row = $rDB->selectRow('SELECT id, sha_pass_hash, gmlevel FROM account WHERE username=? LIMIT 1', $username);
	if($user_row)
	{
		if($shapass == $user_row['sha_pass_hash'])
		{
			$user = array();
			$user['id'] = $user_row['id'];
			$user['name'] = $username;
			$user['roles'] = ($user_row['gmlevel']>0)? 2: 0;
			/*
				roles:
					0 - Обычный пользователь (gmlevel=0)
					1 - Tester
					2 - Администратор (синий, -25:25, 5, ред+уд)
					3 - (синий, -25:25, 5, ред+уд)
					4 - Editor (белый, ------, 1, ------)
					5 - (белый, ------, 1, ------)
					6 - (синий, -25:25, 5, ред+уд)
					7 - (синий, -25:25, 5, ред+уд)
					8 - Модератор (белый, -5:5  , 5, ред+уд)
					9 - (белый, -5:5  , 5, ред+уд)
					10 - (синий, -25:25, 5, ред+уд)
					11 - (синий, -25:25, 5, ред+уд)
					12 - Editor, Moderator (белый, -5:5  , 5, ред+уд)
					13 - (белый, -5:5  , 5, ред+уд)
					14 - (синий, -25:25, 5, ред+уд)
					15 - (синий, -25:25, 5, ред+уд)
					16 - Бюрократ (белый, -15:15, 5, ред+уд)
					17 - (белый, -15:15, 5, ред+уд)
					18 - (синий, -25:25, 5, ред+уд)
					19 - (синий, -25:25, 5, ред+уд)
					20 - (белый, -15:15, 5, ред+уд)
					21 - (белый, -15:15, 5, ред+уд)
					22 - (синий, -25:25, 5, ред+уд)
					23 - (синий, -25:25, 5, ред+уд)
					24 - (белый, -15:15, 5, ред+уд)
					25 - (белый, -15:15, 5, ред+уд)
					26 - (синий, -25:25, 5, ред+уд)
			
			*/
			$user['perms'] = 1;
			return $user;
		}
		else
			return 0;
	}
	else
		// такого пользователя не существует
		return -1;
}

function create_usersend_pass($username, $password)
{
	// Хеш-код в зависимости от имени аккаунта и пароля
	return sha1(strtoupper($username).':'.strtoupper($password));
}

function del_user_cookie()
{
	setcookie('remember_me', '', time() - 3600);
}
?>