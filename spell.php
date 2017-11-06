<?php

require_once('includes/allspells.php');
require_once('includes/allitems.php');
require_once('includes/allnpcs.php');
require_once('includes/allquests.php');
require_once('includes/allcomments.php');

$smarty->config_load($conf_file, 'spell');

// номер спелла;
$id = intval($podrazdel);

$cache_key = cache_key($id);

if(!$spell = load_cache(13, $cache_key))
{
	unset($spell);

	// Данные об спелле:
	$row = $DB->selectRow('
		SELECT s.*, i.iconname,
			sct.base AS basecasttime, sd.durationBase, r.name_loc?d AS school,
			sdt.name_loc?d AS dispel, sm.name_loc?d AS mechanic
		FROM ?_spellicons i, ?_spell s
		LEFT JOIN (?_spellcasttimes sct) ON sct.id = s.spellcasttimesID
		LEFT JOIN (?_spellduration sd) ON sd.durationID = s.durationID
		LEFT JOIN (?_resistances r) ON r.id = s.resistancesID
		LEFT JOIN (?_spelldispeltype sdt) ON s.dispeltypeID > 0 AND sdt.id = s.dispeltypeID
		LEFT JOIN (?_spellmechanic sm) ON s.mechanicID > 0 AND sm.id = s.mechanicID
		WHERE
			s.spellID = ?
			AND i.id = s.spellicon
		',
		$_SESSION['locale'], // school
		$_SESSION['locale'], // dispel
		$_SESSION['locale'], // mechanic
		$id
	);

	if($row)
	{
		$spell = array();
		// Номер спелла
		$spell['entry'] = $row['spellID'];
		// Имя спелла
		$spell['name'] = $row['spellname_loc'.$_SESSION['locale']];
		// Иконка спелла
		//$spell['icon'] = $row['iconname'];
		// Стакается до
		$spell['stack'] = $row['stack'];
		// Затраты маны на сспелл
		if($row['manacost'])
			$spell['manacost'] = $row['manacost'];
		elseif($row['manacostpercent'])
			$spell['manacost'] = $row['manacostpercent'].'% '.$smarty->get_config_vars('of_base');
		// Уровень спелла
		$spell['level'] = $row['levelspell'];
		// Дальность
		// TODO: переделать дальность для новых колонок
		$RangeRow = $DB->selectRow('SELECT rangeMin, rangeMax, name_loc'.$_SESSION['locale'].' FROM ?_spellrange WHERE rangeID = ? LIMIT 1', $row['rangeID']);
		$spell['range'] = '';
		if(($RangeRow['rangeMin'] != $RangeRow['rangeMax']) and ($RangeRow['rangeMin'] != 0))
			$spell['range'] = $RangeRow['rangeMin'].'-';
		$spell['range'] .= $RangeRow['rangeMax'];
		$spell['rangename'] = $RangeRow['name_loc'.$_SESSION['locale']];
		// Время каста
		if($row['basecasttime'] > 0)
			$spell['casttime'] = ($row['basecasttime'] / 1000).' '.$smarty->get_config_vars('seconds');
		else if($row['ChannelInterruptFlags'])
			$spell['casttime'] = 'Channeled';
		else
			$spell['casttime'] = 'Instant';
		// Cooldown
		if($row['cooldown'] > 0)
			$spell['cooldown'] = $row['cooldown'] / 1000;
		// Время действия спелла
		if($row['durationBase'] > 0)
			$spell['duration'] = ($row['durationBase'] / 1000).' '.$smarty->get_config_vars('seconds');
		else
			$spell['duration'] ='<span class="q0">n/a</span>';

		// Школа
		$spell['school'] = $row['school'];
		// Диспелл
		$spell['dispel'] = $row['dispel'];
		// Механика
		$spell['mechanic'] = $row['mechanic'];

		// Информация о спелле
		$spell['info'] = allspellsinfo2($row, 2);

		// Инструменты
		$spell['tools'] = array();
		$i=0;
		for ($j=1;$j<=2;$j++)
		{
			if($row['tool'.$j])
			{
				$tool_row = allitemsinfo($row['tool'.$j], 0);
				$spell['tools'][$i] = array(
					'name'		=> $tool_row['name'],
					'quality'	=> $tool_row['quality'],
					'entry'		=> $row['tool'.$j],
				);
				$i++;
			}
		}

		// Реагенты
		$spell['reagents'] = array();
		$i=0;
		for ($j=1;$j<=8;$j++)
		{
			if($row['reagent'.$j])
			{
				$reagent_row = allitemsinfo($row['reagent'.$j], 0);
				$spell['reagents'][$i] = array(
					'name'		=> $reagent_row['name'],
					'quality'	=> $reagent_row['quality'],
					'entry'		=> $row['reagent'.$j],
					'count'		=> $row['reagentcount'.$j],
				);
				$i++;
			}
		}

		// Перебираем все эффекты:
		$i=0;
		$spell['effect'] = array();
		// Btt - Buff TollTip
		if($row['buff'])
			$spell['btt'] = spell_buff_render($row);
		for ($j=1;$j<=3;$j++)
		{
			if($row['effect'.$j.'id'] > 0)
			{
				// Название эффекта
				$spell['effect'][$i]['name'] = '('.$row['effect'.$j.'id'].') '.$spell_effect_names[$row['effect'.$j.'id']];
				// Доп информация в имени
				if($row['effect'.$j.'MiscValue'])
				{
					switch ($row['effect'.$j.'id'])
					{
						// Если эффект - создание обекта, создаем информацию о нём
						case 50: // "Summon Object"				// 103 spells, OK
						case 76: // "Summon Object (Wild)"		// 173 spells, OK
						//case 86: // "Activate Object"			// 175 spells; wrong GOs, tiny ID; skipping
						case 104: // "Summon Object (slot 1)"	// 24 spells - traps, OK
						//case 105: // "Summon Object (slot 2)"	// 2 spells: 22996, 23005; wrong GOs; skipping
						//case 106: // "Summon Object (slot 3)"	// 0 spells; skipping
						//case 107: // "Summon Object (slot 4)"	// 0 spells; skipping
						{
							$spell['effect'][$i]['object'] = array();
							$spell['effect'][$i]['object']['entry'] = $row['effect'.$j.'MiscValue'];
							$spell['effect'][$i]['object']['name'] = $DB->selectCell("SELECT name FROM gameobject_template WHERE entry = ? LIMIT 1", $spell['effect'][$i]['object']['entry']).' ('.$spell['effect'][$i]['object']['entry'].')';
							break;
						}
						// скиллы
						case 118: // "Require Skill"
						{
							$spell['effect'][$i]['name'] .= ' ('.$DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' as name FROM ?_skill WHERE skillID = ? LIMIT 1', $row['effect'.$j.'MiscValue']).')';
							break;
						}
						// ауры
						case 6:
						{
							break;
						}
						// тотемы
						case 75: // "Summon Totem"
						case 87: // "Summon Totem (slot 1)"
						case 88: // "Summon Totem (slot 2)"
						case 89: // "Summon Totem (slot 3)"
						case 90: // "Summon Totem (slot 4)"
						{
							$spell['effect'][$i]['name'] .= ' (<a href="?npc='.$row['effect'.$j.'MiscValue'].'">'.$row['effect'.$j.'MiscValue'].'</a>)';
							break;
						}
						default:
						{
							$spell['effect'][$i]['name'] .= ' ('.$row['effect'.$j.'MiscValue'].')';
						}
					}
				}
				// Если просто урон школой - добавляем подпись школы
				if($row['effect'.$j.'id'] == 2 && $spell['school'])
					$spell['effect'][$i]['name'] .= ' ('.$spell['school'].')';
				// Радиус действия эффекта
				if($row['effect'.$j.'radius'])
					$spell['effect'][$i]['radius'] = $DB->selectCell("SELECT radiusbase FROM ?_spellradius WHERE radiusID = ? LIMIT 1", $row['effect'.$j.'radius']);
				// Значение спелла (урон)
				if($row['effect'.$j.'BasePoints'] && !$row['effect'.$j.'itemtype'])
					$spell['effect'][$i]['value'] = $row['effect'.$j.'BasePoints'] + 1;
				// Интервал действия спелла
				if($row['effect'.$j.'Amplitude'] > 0)
					$spell['effect'][$i]['interval'] = $row['effect'.$j.'Amplitude'] / 1000;
				// Название ауры:
				if($row['effect'.$j.'Aura'] > 0 && IsSet($spell_aura_names[$row['effect'.$j.'Aura']]))
				{
					$spell['effect'][$i]['name'] .= ' #'.$row['effect'.$j.'Aura'];
					switch ($row['effect'.$j.'Aura'])
					{
						case 78: // "Mounted" - приписываем ссылку на нпс
						case 56: // "Transform"
						{
							$spell['effect'][$i]['name'] .= ': '.$spell_aura_names[$row['effect'.$j.'Aura']].' (<a href="?npc='.$row['effect'.$j.'MiscValue'].'">'.$row['effect'.$j.'MiscValue'].'</a>)';
							break;
						}
						default:
						{
							$spell['effect'][$i]['name'] .= ': '.$spell_aura_names[$row['effect'.$j.'Aura']];
							if($row['effect'.$j.'MiscValue'] > 0)
								$spell['effect'][$i]['name'] .= ' ('.$row['effect'.$j.'MiscValue'].')';
						}
					}
				}
				elseif($row['effect'.$j.'Aura'] > 0)
					$spell['effect'][$i]['name'] .= ': Unknown Aura ('.$row['effect'.$j.'Aura'].')';
				// Создает вещь:
				if($row['effect'.$j.'id'] == 24)
				{
					$spell['effect'][$i]['item'] = array();
					$spell['effect'][$i]['item']['entry'] = $row['effect'.$j.'itemtype'];
					$tmpRow = allitemsinfo($spell['effect'][$i]['item']['entry'], 0);
					$spell['effect'][$i]['item']['name'] = $tmpRow['name'];
					$spell['effect'][$i]['item']['quality'] = $tmpRow['quality'];
					$spell['effect'][$i]['item']['count'] = $row['effect'.$j.'BasePoints'] + 1;
					// Иконка итема, если спелл создает этот итем
					if(!IsSet($spell['icon']))
						$spell['icon'] = $tmpRow['iconname'];
				}
				// Создает спелл
				if($row['effect'.$j.'triggerspell'] > 0)
				{
					$spell['effect'][$i]['spell'] = array();
					$spell['effect'][$i]['spell']['entry'] = $row['effect'.$j.'triggerspell'];
					$spell['effect'][$i]['spell']['name'] = $DB->selectCell('SELECT spellname_loc'.$_SESSION['locale'].' FROM ?_spell WHERE spellID = ?d LIMIT 1', $spell['effect'][$i]['spell']['entry']);
					allspellsinfo($spell['effect'][$i]['spell']['entry']);
				}
				$i++;
			}
		}

		if(!IsSet($spell['icon']))
			$spell['icon'] = $row['iconname'];

		// Спеллы с таким же названием
		$seealso = $DB->select('
			SELECT s.*, i.iconname
			FROM ?_spell s, ?_spellicons i
			WHERE
				s.spellname_loc'.$_SESSION['locale'].' = ?
				AND s.spellID <> ?d
				AND (
							(s.effect1id = ?d AND s.effect1id!=0)
							OR (s.effect2id = ?d AND s.effect2id!=0)
							OR (s.effect3id = ?d AND s.effect3id!=0)
						)
				AND i.id=s.spellicon
			',
			$spell['name'],
			$spell['entry'],
			$row['effect1id'],
			$row['effect2id'],
			$row['effect3id']
		);
		if($seealso)
		{
			$spell['seealso'] = array();
			foreach($seealso as $i => $row)
				$spell['seealso'][] = spellinfo2($row);
			unset($seealso);
		}

		// Кто обучает этому спеллу
		$spell['taughtbynpc'] = array();
		// Список тренеров, обучающих нужному спеллу
		$trainers = $DB->selectCol('SELECT entry FROM npc_trainer WHERE spell = ?d', $spell['entry']);
		if($trainers)
		{
			$taughtbytrainers = $DB->select('
				SELECT ?#, c.entry
				{ , name_loc?d AS name_loc, subname_loc'.$_SESSION['locale'].' AS subname_loc }
				FROM ?_factiontemplate, creature_template c
				{ LEFT JOIN (locales_creature l) ON c.entry = l.entry AND ? }
				WHERE
					c.entry IN (?a)
					AND factiontemplateID=faction_A
				',
				$npc_cols[0],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$trainers
			);
			if($taughtbytrainers)
			{
				foreach($taughtbytrainers as $i=>$npcrow)
					$spell['taughtbynpc'][] = creatureinfo2($npcrow);
				unset($taughtbytrainers);
			}
		}

		// Список книг/рецептов, просто обучающих спеллу
		$spell['taughtbyitem'] = array();
		$taughtbyitem = $DB->select('
			SELECT ?#, c.entry
			{ , name_loc?d AS name_loc }
			FROM ?_icons, item_template c
			{ LEFT JOIN (locales_item l) ON c.entry = l.entry AND ? }
			WHERE
				(spellid_2 = ?d AND spelltrigger_2 = 6)
				AND id=displayid
			',
			$item_cols[2],
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$spell['entry']
		);
		if($taughtbyitem)
		{
			$taughtbyitem = sanitiseitemrows($taughtbyitem);
			foreach($taughtbyitem as $i=>$itemrow)
				$spell['taughtbyitem'][] = iteminfo2($itemrow, 0);
			unset($taughtbyitem);
		}

		// Список спеллов, обучающих этому спеллу:
		$taughtbyspells = $DB->selectCol('
			SELECT spellID
			FROM ?_spell
			WHERE
				(effect1triggerspell = ?d AND effect1id IN (57, 36))
				OR (effect2triggerspell = ?d AND effect2id IN (57, 36))
				OR (effect3triggerspell = ?d AND effect3id IN (57, 36))
			',
			$spell['entry'], $spell['entry'], $spell['entry']
		);

		if($taughtbyspells)
		{
			// Список петов, кастующих спелл, обучающий нужному спеллу
			/*
			$taughtbypets = $DB->select('
				SELECT ?#, c.entry
				{ , name_loc?d AS name_loc, subname_loc'.$_SESSION['locale'].' AS subname_loc }
				FROM ?_factiontemplate, creature_template c
				{ LEFT JOIN (locales_creature l) ON c.entry = l.entry AND ? }
				WHERE
					c.entry IN (SELECT entry FROM petcreateinfo_spell WHERE (Spell1 IN (?a)) OR (Spell2 IN (?a)) OR (Spell3 IN (?a)) OR (Spell4 IN (?a)))
					AND factiontemplateID=faction_A
				',
				$npc_cols[0],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$taughtbyspells, $taughtbyspells, $taughtbyspells, $taughtbyspells
			);
			// Перебираем этих петов
			if($taughtbypets)
			{
				foreach($taughtbypets as $i=>$petrow)
					$spell['taughtbynpc'][] = creatureinfo2($petrow);
				unset($taughtbypets);
			}
			*/

			// Список квестов, наградой за которые является спелл, обучающий нужному спеллу
			$taughtbyquest = $DB->select('
				SELECT c.?#
				{ , Title_loc?d AS Title_loc }
				FROM quest_template c
				{ LEFT JOIN (locales_quest l) ON c.entry = l.entry AND ? }
				WHERE
					RewSpell IN (?a) OR RewSpellCast IN (?a)
				',
				$quest_cols[2],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$taughtbyspells, $taughtbyspells
			);
			if($taughtbyquest)
			{
				$spell['taughtbyquest'] = array();
				foreach($taughtbyquest as $i=>$questrow)
					$spell['taughtbyquest'][] = GetQuestInfo($questrow, 0xFFFFFF);
				unset($taughtbyquest);
			}

			// Список НПЦ, кастующих нужный спелл, бла-бла-бла
			$taughtbytrainers = $DB->select('
				SELECT ?#, c.entry
				{ , name_loc?d AS name_loc, subname_loc'.$_SESSION['locale'].' AS subname_loc }
				FROM ?_factiontemplate, creature_template c
				{ LEFT JOIN (locales_creature l) ON c.entry = l.entry AND ? }
				WHERE
					c.entry IN (SELECT entry FROM npc_trainer WHERE spell in (?a))
					AND factiontemplateID=faction_A
				',
				$npc_cols[0],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$taughtbyspells
			);
			if($taughtbytrainers)
			{
				foreach($taughtbytrainers as $i=>$npcrow)
					$spell['taughtbynpc'][] = creatureinfo2($npcrow);
				unset($taughtbytrainers);
			}

			// Список книг, кастующих спелл, обучающий нужному спеллу
			$taughtbyitem = $DB->select('
				SELECT ?#, c.entry
				{ , name_loc?d AS name_loc }
				FROM ?_icons, item_template c
				{ LEFT JOIN (locales_item l) ON c.entry = l.entry AND ? }
				WHERE
					((spellid_1 IN (?a))
					OR (spellid_2 IN (?a))
					OR (spellid_3 IN (?a))
					OR (spellid_4 IN (?a))
					OR (spellid_5 IN (?a)))
					AND id=displayid
				',
				$item_cols[2],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$taughtbyspells, $taughtbyspells, $taughtbyspells, $taughtbyspells, $taughtbyspells
			);
			if($taughtbyitem)
			{
				$taughtbyitem = sanitiseitemrows($taughtbyitem);
				foreach($taughtbyitem as $i=>$itemrow)
					$spell['taughtbyitem'][] = iteminfo2($itemrow, 0);
				unset($taughtbyitem);
			}
		}

		// Используется NPC:
		$usedbynpc = $DB->select('
			SELECT ?#, c.entry
			{ , name_loc?d AS name_loc, subname_loc'.$_SESSION['locale'].' AS subname_loc }
			FROM ?_factiontemplate, creature_template c
			{ LEFT JOIN (locales_creature l) ON c.entry = l.entry AND ? }
			WHERE
				(spell1 = ?d
				OR spell2 = ?d
				OR spell3 = ?d
				OR spell4 = ?d)
				AND factiontemplateID=faction_A
			',
			$npc_cols[0],
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$spell['entry'], $spell['entry'], $spell['entry'], $spell['entry']
		);
		if($usedbynpc)
		{
			$spell['usedbynpc'] = array();
			foreach($usedbynpc as $i=>$row)
				$spell['usedbynpc'][] = creatureinfo2($row);
			unset($usedbynpc);
		}

		// Используется вещями:
		$usedbyitem = $DB->select('
			SELECT ?#, c.entry
			{ , name_loc?d AS name_loc }
			FROM ?_icons, item_template c
			{ LEFT JOIN (locales_item l) ON c.entry = l.entry AND ? }
			WHERE
				(spellid_1 = ?d OR (spellid_2 = ?d AND spelltrigger_2!=6) OR spellid_3 = ?d OR spellid_4 = ?d OR spellid_5 = ?d)
				AND id=displayID
			',
			$item_cols[2],
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$spell['entry'], $spell['entry'], $spell['entry'], $spell['entry'], $spell['entry']
		);
		if($usedbyitem)
		{
			$usedbyitem = sanitiseitemrows($usedbyitem);
			$spell['usedbyitem'] = array();
			foreach($usedbyitem as $i => $row)
				$spell['usedbyitem'][] = iteminfo2($row, 0);
			unset($usedbyitem);
		}

		// Используется наборами вещей:
		$usedbyitemset = $DB->select('
			SELECT *
			FROM ?_itemset
			WHERE spell1 = ?d OR spell2 = ?d OR spell3 = ?d OR spell4 = ?d OR spell5 = ?d OR spell6 = ?d OR spell7 = ?d OR spell8 = ?d
			',
			$spell['entry'], $spell['entry'], $spell['entry'], $spell['entry'], $spell['entry'], $spell['entry'], $spell['entry'], $spell['entry']
		);
		if($usedbyitemset)
		{
			$spell['usedbyitemset'] = array();
			foreach($usedbyitemset as $i => $row)
				$spell['usedbyitemset'][] = itemsetinfo2($row);
			unset($usedbyitemset);
		}

		// Спелл - награда за квест
		$questreward = $DB->select('
			SELECT c.?#
			{ , Title_loc?d AS Title_loc }
			FROM quest_template c
			{ LEFT JOIN (locales_quest l) ON c.entry = l.entry AND ? }
			WHERE
				RewSpell = ?d
				OR RewSpellCast = ?d
			',
			$quest_cols[2],
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$spell['entry'], $spell['entry']
		);
		if($questreward)
		{
			$spell['questreward'] = array();
			foreach($questreward as $i => $row)
				$spell['questreward'][] = GetQuestInfo($row, 0xFFFFFF);
			unset($questreward);
		}

		// Проверяем на пустые массивы
		if(!$spell['taughtbyitem'])
			unset($spell['taughtbyitem']);
		if(!$spell['taughtbynpc'])
			unset($spell['taughtbynpc']);

		save_cache(13, $cache_key, $spell);
	}
}

global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $spell['name'].' - '.$smarty->get_config_vars('Spells'),
	'tab' => 0,
	'type' => 6,
	'typeid' => $spell['entry'],
	'path' => path(0, 1)
);
$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));

// Количество MySQL запросов
$smarty->assign('mysql', $DB->getStatistics());
$smarty->assign('allspells', $allspells);
$smarty->assign('allitems', $allitems);

$smarty->assign('spell', $spell);
$smarty->display('spell.tpl');

?>