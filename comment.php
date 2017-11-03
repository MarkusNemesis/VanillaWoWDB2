<?php

require_once('includes/allutil.php');

switch($_REQUEST['comment'])
{
	case 'add':
		// Добавление комментария
		// $_GET['type'] - тип страницы
		// $_GET['typeid'] - номер страницы
		// $_POST['commentbody'] - текст комментария
		// $_POST['replyto'] - номер поста, на который отвечает
		// $_SESSION['userid'] - номер пользователя
		$type	= intval($_GET['type']);
		$id		= intval($_GET['typeid']);
		$parent	= intval($_POST['replyto']);
		$text	= @substr($_POST['commentbody'], 0, 15000);

		$url = $types[$type][0].'='.$id.'#comments';

		if(strlen($text) < 10)
			redirect($url);

		// Нет такой категории или записи в категории
		if(!$types[$type] || !$DB->selectCell('SELECT 1 FROM ?# WHERE ?# = ?', $types[$type][1], $types[$type][2], $id))
			redirect($url);

		if($parent && !$DB->selectCell('SELECT 1 FROM ?_comments WHERE id = ?', $parent))
			redirect($url);

		// TODO
		//$diff = $DB->selectCell('SEECT UNIX_TIMESTAMP() - UNIX_TIMESTAMP(post_date) AS diff FROM aowow_comments WHERE userid = ?d ORDER BY post_date DESC LIMIT 1', $_SESSION['userid']);

		$newid = $DB->query('
				INSERT INTO ?_comments (type, typeid, userid, commentbody, post_date, replyto)
				VALUES (?d, ?d, ?d, ?, NOW(), ?d)
			',
			$type,
			$id,
			$_SESSION['userid'] ? $_SESSION['userid'] : 0,
			stripslashes($text),
			$parent ? $parent : 0
		);

		// рейтинг
		if($_SESSION['userid'])
			$DB->query('INSERT INTO ?_comments_rates (commentid, userid, rate) VALUES (?, ?, 1)', $newid, $_SESSION['userid']);

		if(!$parent)
			$DB->query('UPDATE ?_comments SET replyto = id WHERE id = ?d', $newid);

		redirect($url);
		break;
	case 'delete':
		// Удаление комментарий (Ajax)
		// Номер комментария: $_GET['id']
		// Имя пользователя, удаляющего комментарий: $_GET['username']
		$id = $_GET['id'];
		if(!$DB->selectCell('SELECT 1 FROM ?_comments WHERE id = ?', $id))
			exit;

		$DB->query('
				DELETE FROM ?_comments
				WHERE
					id = ?d
					{AND userid = ?d}
			',
			$id,
			$_SESSION['roles'] > 0 ? DBSIMPLE_SKIP : $_SESSION['userid']
			);
		break;
	case 'edit':
		// Редактирование комментария
		// Номер комментария: $_GET['id']
		// Новое содержание комментария: $_POST['body']
		// Номер пользователя: $_SESSION['userid']
		$text = @substr($_POST['commentbody'], 0, 15000);
		if(!$text)
			exit;

		if(!$_SESSION['userid'])
			exit;

		$DB->query('
				UPDATE ?_comments
				SET commentbody = ?, edit_userid = ?, edit_date = NOW()
				WHERE
					id = ?d
					{ AND userid = ?d }
				LIMIT 1
			',
			stripslashes($text),
			$_SESSION['userid'],
			$_GET['id'],
			$_SESSION['roles'] > 0 ? DBSIMPLE_SKIP : $_SESSION['userid']
		);
		echo $text;
		break;
	case 'rate':
		/*
		* Установка собственоого рейтинга (модераторы и т.п.)
		* Номер комментария: $_GET['id']
		* Новое значение рейтинга: $_GET['rating']
		* Номер пользователя: $_SESSION['userid']
		*/
		$id = intval($_GET['id']);
		$rate = intval($_GET['rating']);

		if(!$_SESSION['userid'] || !$id || !$rate)
			exit;

		if($_SESSION['roles'] == 0)
			$rate = sign($rate);

		// Проверка на хоть какое то значение рейтинга, и на то, что пользователь за этот коммент не голосовал
		if($DB->selectCell('SELECT 1 FROM ?_comments_rates WHERE userid = ?d AND commentid = ?d', $_SESSION['userid'], $id))
			exit;

		$DB->query('INSERT INTO ?_comments_rates (commentid, userid, rate) VALUES (?d, ?d, ?d)', $id, $_SESSION['userid'], $rate);
		break;
	case 'undelete':
		// Восстановление комментария
		// Номер комментария: $_GET['id']
		// Имя пользователя, удаляющего комментарий: $_GET['username']
	default:
		break;
}

?>