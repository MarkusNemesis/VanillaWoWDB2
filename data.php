<?php
header('Content-type: application/x-javascript');
require_once('configs/config.php');
require_once('includes/allutil.php');

switch($_GET['data'])
{
	case 'talents':
		// i - id скилла (id из aowow_talent)
		// n - название (spellname из aowow_spell для spellID=rank1)
		// m - кол-во ранков (6-количество_пустых_ранков)
		// s - спеллы для ранков (rank1, rank2, ..., rank5 из aowow_talent)
		// d - описания спеллов
		// x - столбец (col из aowow_talent)
		// y - строка (row из aowow_talent)
		// r - от чего и какого ранка зависит: "r: [r_1, r_2]", где r_1 - номер (нумерация с 0) таланта, r_2 - ранк
		$class = intval($_GET['class']);
		if(!in_array($class, array_keys($classes)))
			exit;

		if(!$p_arr = load_cache(20, $class))
		{
			unset($p_arr);

			require_once('includes/allspells.php');

			// Все "Табы" талантов заданного класса
			$tabs = $DB->select('
					SELECT `id`, name_loc?d AS `name`
					FROM ?_talenttab
					WHERE classes = ?d
					ORDER BY `order`
				',
				$_SESSION['locale'],
				pow(2, $_GET['class']-1)
			);
			
			$t_nums = array();
			$p_arr = array();
			$i = 0;
			foreach($tabs as $tab)
			{
				$p_arr[$i] = array();
				$p_arr[$i]['n'] = $tab['name'];
				$talents = $DB->select('
						SELECT t.*, s.spellname_loc?d as `spellname`, i.`iconname`
						FROM ?_talent t, ?_spell s
						{LEFT JOIN (?_spellicons i) ON i.`id`=s.`spellicon`}
						WHERE
							t.`tab`=?d AND
							s.`spellID` = t.`rank1`
						ORDER by t.`row`, t.`col`
					',
					$_SESSION['locale'],
					$tab['id']
				);
				$j = 0;
				$p_arr[$i]['t'] = array();
				// Массив ссылок на зависимости
				$dep_links = array();
				foreach($talents as $talent)
				{
					$t_nums[$talent['id']] = $j;
					$p_arr[$i]['t'][$j] = array();
					$p_arr[$i]['t'][$j]['i'] = (integer) $talent['id'];
					$p_arr[$i]['t'][$j]['n'] = (string) $talent['spellname'];
					$p_arr[$i]['t'][$j]['m'] = (integer) ($talent['rank2']==0 ? 1 : ($talent['rank3']==0 ? 2 : (($talent['rank4']==0 ? 3 : (($talent['rank5']==0 ? 4 : 5))))));
					for ($k=0;$k<=($p_arr[$i]['t'][$j]['m']-1);$k++)
					{
						$p_arr[$i]['t'][$j]['s'][] = (integer) $talent['rank'.($k+1)];
						$p_arr[$i]['t'][$j]['d'][] = (string) spell_desc($talent['rank'.($k+1)]);
					}
					$p_arr[$i]['t'][$j]['x'] = (integer) $talent['col'];
					$p_arr[$i]['t'][$j]['y'] = (integer) $talent['row'];
					if ($talent['dependsOn'])
					{
						// Если талант, от которого зависит текущий талант ещё не встречался, создадим ссылку в массив ссылок на зависимости
						if (!isset($t_nums[$talent['dependsOn']])) $dep_links[$talent['dependsOn']] = $j;
						$p_arr[$i]['t'][$j]['r'] = array($t_nums[$talent['dependsOn']], $talent['dependsOnRank']+1);
					}
					// Spell icons
					$p_arr[$i]['t'][$j]['iconname'] = (string) $talent['iconname'];
					// Если на этот талант есть ссылка, добавляем его в массив зависимого таланта
					if (isset($dep_links[$talent['id']]))
					{
						$p_arr[$i]['t'][$dep_links[$talent['id']]]['r'][0] = $j;
						unset ($dep_links[$talent['id']]);
					}
					$j++;
				}
				// Удаляем все зависимости, для которых талант так и не был найден
				foreach ($dep_links as $dep_link) unset ($p_arr[$i]['t'][$dep_link]['r']);
				$i++;
			}

			save_cache(20, $class, $p_arr);
		}
		echo '$WowheadTalentCalculator.registerClass('.$class.', '.php2js($p_arr).')';
		break;
	case 'glyphs':
		if(!$g_glyphs = load_cache(25, 'x'))
		{
			/*
				name - Имя вещи
				description - Тултип спелла
				icon - Иконка вещи
			*/

			require_once('includes/allspells.php');

			$glyphs = array();

			$glyphs = $DB->select('
					SELECT it.entry, it.name, it.subclass, it.spellid_1 AS spellid, i.iconname, gp.typeflags
					{, l.name_loc?d AS name_loc }
					FROM ?_glyphproperties gp, ?_spell s, ?_icons i, item_template it
					{ LEFT JOIN locales_item l ON (l.entry = it.entry AND ?) }
					WHERE
						it.class = 16
						AND it.displayid = i.id
						AND it.spellid_1 = s.spellid
						AND s.effect1id = ?d
						AND gp.id = s.effect1MiscValue
				',
				$_SESSION['locale'] > 0 ? $_SESSION['locale'] : DBSIMPLE_SKIP,
				$_SESSION['locale'] > 0 ? 1 : DBSIMPLE_SKIP,
				74 // SPELL_EFFECT_APPLY_GLYPH
				
			);
			$g_glyphs = array();
			foreach($glyphs as $glyph)
			{
				$name = localizedName($glyph);
				if($_SESSION['locale'] == 0)
					$name = str_replace(LOCALE_GLYPH_OF, '', $name);
				$g_glyphs[$glyph['entry']] = array(
					'name'			=> (string)$name,
					'description'	=> (string)spell_desc($glyph['spellid']),
					'icon'			=> (string)$glyph['iconname'],
					'type'			=> (int)($glyph['typeflags']&1 ? 2 : 1),	// 1 - Большой символ, 2 - Малый символ
					'classs'		=> (int)$glyph['subclass'],
					'skill'			=> (int)2  // Skill???
				);
			}

			save_cache(25, 'x', $g_glyphs);
		}
		echo 'var g_glyphs='.php2js($g_glyphs);
		break;
	case 'talent-icon':
		$iconname = strtolower($_GET['icon']);
		if(!$DB->selectCell('SELECT 1 FROM ?_spellicons WHERE iconname = ?', $iconname))
			exit;

		if($name = load_cache(21, $iconname))
		{
			header('Content-type: image/jpeg');
			imagejpeg(imagecreatefromjpeg('cache/images/'.$iconname.'.jpg'));
		}
		else
		{
			header('Content-type: image/jpeg');
			$im = @imagecreatefromjpeg('images/icons/medium/'.$iconname.'.jpg');

			if(!$im)
				exit;

			imagetograyscale($im);
			imagejpeg($im, 'cache/images/'.$iconname.'.jpg');
			imagejpeg($im);
			save_cache(21, $iconname, $iconname);
		}
		break;
	default:
		break;
}
?>