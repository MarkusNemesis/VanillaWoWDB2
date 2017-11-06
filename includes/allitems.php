<?php

require_once('includes/game.php'); // game.php is for factioninfo()
require_once('includes/allspells.php');
require_once('includes/allitemsets.php');
require_once('includes/allobjects.php');
require_once('includes/allquests.php');

// Массивы с названиями столбцов, необходимых для различных уровней вызова функций
// для allitems($level=0) - соответствия номер-иконка
$item_cols[0] = array('entry', 'patch', 'iconname', 'quality', 'name');
// для allitems($level=1) - ajax, тултип
$item_cols[1] = array('entry', 'patch', 'name', 'quality', 'iconname', 'maxcount', 'bonding', 'startquest', 'Map', 'ContainerSlots', 'class', 'InventoryType', 'subclass', 'dmg_type1', 'dmg_min1', 'dmg_max1', 'delay', 'dmg_type2', 'dmg_min2', 'dmg_max2', 'armor', 'block', 'stat_type1', 'stat_type2', 'stat_type3', 'stat_type4', 'stat_type5', 'stat_type6', 'stat_type7', 'stat_type8', 'stat_type9', 'stat_type10', 'stat_value1', 'stat_value2', 'stat_value3', 'stat_value4', 'stat_value5', 'stat_value6', 'stat_value7', 'stat_value8', 'stat_value9', 'stat_value10', 'holy_res', 'fire_res', 'nature_res', 'frost_res', 'shadow_res', 'arcane_res', 'RandomProperty', 'MaxDurability', 'AllowableClass', 'RequiredLevel', 'RequiredSkill', 'requiredspell', 'RequiredReputationFaction', 'RequiredReputationRank', 'spellid_1', 'spellid_2', 'spellid_3', 'spellid_4', 'spellid_5', 'spelltrigger_1', 'spelltrigger_2', 'spelltrigger_3', 'spelltrigger_4', 'spelltrigger_5', 'description', 'PageText', 'BagFamily', 'RequiredSkillRank');
// для iteminfo($level=0) - строчки списка
$item_cols[2] = array('name', 'patch', 'quality', 'iconname', 'InventoryType', 'ItemLevel', 'RequiredLevel', 'class', 'subclass', 'stackable', 'BuyPrice', 'armor', 'dmg_type1', 'dmg_min1', 'dmg_max1', 'delay', 'dmg_type2', 'dmg_min2', 'dmg_max2', 'ContainerSlots');
// для iteminfo($level=1)
$item_cols[3] = array('entry', 'patch', 'name', 'quality', 'iconname', 'maxcount', 'bonding', 'startquest', 'Map', 'ContainerSlots', 'class', 'InventoryType', 'subclass', 'dmg_type1', 'dmg_min1', 'dmg_max1', 'delay', 'dmg_type2', 'dmg_min2', 'dmg_max2', 'armor', 'block', 'stat_type1', 'stat_type2', 'stat_type3', 'stat_type4', 'stat_type5', 'stat_type6', 'stat_type7', 'stat_type8', 'stat_type9', 'stat_type10', 'stat_value1', 'stat_value2', 'stat_value3', 'stat_value4', 'stat_value5', 'stat_value6', 'stat_value7', 'stat_value8', 'stat_value9', 'stat_value10', 'holy_res', 'fire_res', 'nature_res', 'frost_res', 'shadow_res', 'arcane_res', 'RandomProperty', 'MaxDurability', 'AllowableClass', 'RequiredLevel', 'RequiredSkill', 'requiredspell', 'RequiredReputationFaction', 'RequiredReputationRank', 'spellid_1', 'spellid_2', 'spellid_3', 'spellid_4', 'spellid_5', 'spelltrigger_1', 'spelltrigger_2', 'spelltrigger_3', 'spelltrigger_4', 'spelltrigger_5', 'description', 'PageText', 'BagFamily', 'RequiredSkillRank', 'ItemLevel', 'stackable', 'BuyPrice', 'DisenchantID', 'SellPrice', 'displayid');

$resz = array('holy_res', 'fire_res', 'nature_res', 'frost_res', 'shadow_res', 'arcane_res');
$resz_desc = array (LOCALE_HOLY_RESISTANCE, LOCALE_FIRE_RESISTANCE, LOCALE_NATURE_RESISTANCE, LOCALE_FROST_RESISTANCE, LOCALE_SHADOW_RESISTANCE, LOCALE_ARCANE_RESISTANCE);
$bag_typez = array(0=>LOCALE_BAG,1=>LOCALE_BAG_QUIVER,2=>LOCALE_BAG_AMMO,4=>LOCALE_BAG_SOUL,8=>LOCALE_BAG_LEATHER,32=>LOCALE_BAG_HERB,64=>LOCALE_BAG_ENCHANT,128=>LOCALE_BAG_ENGINEER,512=>LOCALE_BAG_GEM,1024=>LOCALE_BAG_MINING);
$rep_levels = array('', '', '', LOCALE_NEUTRAL, LOCALE_FRIENDLY, LOCALE_HONORED, LOCALE_REVERED, LOCALE_EXALTED);
$bond = array('', '<br />'.LOCALE_BIND_PICKUP, '<br />'.LOCALE_BIND_EQUIP, '<br />'.LOCALE_BIND_SOULBOUND, '<br />'.LOCALE_BIND_QUEST_ITEM);
$slot = array('',LOCALE_EQUIP_HEAD,LOCALE_EQUIP_NECK,LOCALE_EQUIP_SHOULDER,LOCALE_EQUIP_SHIRT,LOCALE_EQUIP_CHEST,LOCALE_EQUIP_WAIST,LOCALE_EQUIP_LEGS,LOCALE_EQUIP_FEET,LOCALE_EQUIP_WRIST,LOCALE_EQUIP_HANDS,LOCALE_EQUIP_FINGER,LOCALE_EQUIP_TRINKET,LOCALE_EQUIP_ONEHAND,LOCALE_EQUIP_OFFHAND,LOCALE_EQUIP_RANGED,LOCALE_EQUIP_BACK,LOCALE_EQUIP_TWOHAND,LOCALE_EQUIP_UNK0,LOCALE_EQUIP_TABARD,LOCALE_EQUIP_CHEST2,LOCALE_EQUIP_MAINHAND,LOCALE_EQUIP_OFFHAND2,LOCALE_EQUIP_HELDINOFFHAND,LOCALE_EQUIP_PROJECTILE,LOCALE_EQUIP_THROWN,LOCALE_EQUIP_RANGED2,LOCALE_EQUIP_UNK1,LOCALE_EQUIP_RELIC);
$armor_type = array('',LOCALE_ARMOR_CLOTH,LOCALE_ARMOR_LEATHER,LOCALE_ARMOR_MAIL,LOCALE_ARMOR_PLATE,LOCALE_ARMOR_BUCKLER,LOCALE_ARMOR_SHIELD,LOCALE_ARMOR_LIBRAM,LOCALE_ARMOR_IDOL,LOCALE_ARMOR_TOTEM);
$weapon_type = array(LOCALE_WEAPON_AXE1H,LOCALE_WEAPON_AXE2H,LOCALE_WEAPON_BOW,LOCALE_WEAPON_GUN,LOCALE_WEAPON_MACE1H,LOCALE_WEAPON_MACE2H,LOCALE_WEAPON_POLEARM,LOCALE_WEAPON_SWORD1H,LOCALE_WEAPON_SWORD2H,LOCALE_WEAPON_OBSOLETE,LOCALE_WEAPON_STAFF,LOCALE_WEAPON_EXOTIC,LOCALE_WEAPON_EXOTIC2,LOCALE_WEAPON_FIST,LOCALE_WEAPON_MISC,LOCALE_WEAPON_DAGGER,LOCALE_WEAPON_THROWN,LOCALE_WEAPON_SPEAR,LOCALE_WEAPON_CROSSBOW,LOCALE_WEAPON_WAND,LOCALE_WEAPON_FISHINGPOLE);
$projectile_type = array(LOCALE_PROJECTILE_WAND,LOCALE_PROJECTILE_BOLT,LOCALE_PROJECTILE_ARROW,LOCALE_PROJECTILE_BULLET,LOCALE_PROJECTILE_THROWN);
$dmg_typez = array ('',LOCALE_DAMAGE_HOLY,LOCALE_DAMAGE_FIRE,LOCALE_DAMAGE_NATURE,LOCALE_DAMAGE_FROST,LOCALE_DAMAGE_SHADOW,LOCALE_DAMAGE_ARCANE);

// Таблица урона
function inv_dmg($min, $max, $delay, $type)
{
	global $dmg_typez;
	if($delay!=0)
		return '<table width="100%"><tr><td>'.$min.' - '.$max.LOCALE_DAMAGE_PRE.$dmg_typez[$type].LOCALE_DAMAGE_POST.'</td><th>'.LOCALE_SPEED.' '.number_format($delay,2).'</th></tr></table>';
	else
		return '+'.$min.' - '.$max.LOCALE_DAMAGE_PRE.$dmg_typez[$type].LOCALE_DAMAGE_POST.'<br />';
}

// This is from javascript
function g_convertRatingToPercent($level, $type, $val)
{
	$arr = array(
		12 => 1.5,
		13 => 12,
		14 => 15,
		15 => 5,
		16 => 10,
		17 => 10,
		18 => 8,
		19 => 14,
		20 => 14,
		21 => 14,
		22 => 10,
		23 => 10,
		24 => 0,
		25 => 0,
		26 => 0,
		27 => 0,
		28 => 10,
		29 => 10,
		30 => 10,
		31 => 10,
		32 => 14,
		33 => 0,
		34 => 0,
		35 => 25,
		36 => 10,
		37 => 2.5,
		44 => 3.756097412109376
	);

	if(in_array($type, array(14, 12, 15)) && $level < 34)
		$level = 34;

	if(!isset($arr[$type]))
		return 0;

	if($level > 70)
		$c = 82 / 52 * pow(131 / 63, ($level - 70) / 10);
	elseif($level > 60)
		$c = 82 / (262 - 3 * $level);
	elseif($level > 10)
		$c = ($level - 8) / 52;
	else
		$c = 2 / 52;

	return $val / $arr[$type] / $c;
}
function g_setRatingLevel($level, $type, $val)
{
	$result = round(g_convertRatingToPercent($level, $type, $val), 2);

	if(!in_array($type, array(12, 37)))
		$result .= '%';

	return sprintf(LOCALE_COMBAT_RATING, $result, $level);
}

function green_bonus($str, $val, $type = 0, $level = 0)
{
	$js = '';
	if($type)
	{
		$js .= '&nbsp;<small>(<a href="javascript:;" onmousedown="return false" onclick="g_setRatingLevel(this,'.$level.','.$type.','.$val.')">';
		$js .= g_setRatingLevel($level, $type, $val);
		$js .= '</a>)</small>';
	}
	return LOCALE_GBONUS_EQUIP.str_replace('%d', $val.$js, $str);
}

// Типы бонусов
function b_type($type, $value, $level)
{
	// 1-80
	$level = min(max($level, 1), 80);
	global $green;
	switch($type)
	{
		// белые статы
		case 3: return '+'.$value.LOCALE_STAT_AGILITY.'<br />';				# 3 - Agility
		case 4: return '+'.$value.LOCALE_STAT_STRENGTH.'<br />';			# 4 - Strength
		case 5: return '+'.$value.LOCALE_STAT_INTELLECT.'<br />';			# 5 - Intellect
		case 6: return '+'.$value.LOCALE_STAT_SPIRIT.'<br />';				# 6 - Spirit
		case 7: return '+'.$value.LOCALE_STAT_STAMINA.'<br />';				# 7 - Stamina
		// зеленые статы
		case 12: $green[] = green_bonus(LOCALE_GBONUS_DEFENCE, $value, $type, $level);				return;
		case 13: $green[] = green_bonus(LOCALE_GBONUS_DODGE, $value, $type, $level);				return;
		case 14: $green[] = green_bonus(LOCALE_GBONUS_PARRY, $value, $type, $level);				return;
		case 15: $green[] = green_bonus(LOCALE_GBONUS_SHIELDBLOCK, $value, $type, $level);			return;

		case 18: $green[] = green_bonus(LOCALE_GBONUS_SPELLHIT_RATING, $value, $type, $level);		return;
		case 19: $green[] = green_bonus(LOCALE_GBONUS_MELEECRIT_RATING, $value, $type, $level);		return;
		case 20: $green[] = green_bonus(LOCALE_GBONUS_RANGEDCRIT_RATING, $value, $type, $level);	return;
		case 21: $green[] = green_bonus(LOCALE_GBONUS_SPELLCRIT_RATING, $value, $type, $level);		return;

		case 30: $green[] = green_bonus(LOCALE_GBONUS_SPELLHASTE_RATING, $value, $type, $level);	return;
		case 31: $green[] = green_bonus(LOCALE_GBONUS_HIT_RATING, $value, $type, $level);			return;
		case 32: $green[] = green_bonus(LOCALE_GBONUS_CRIT_RATING, $value, $type, $level);			return;
		case 35: $green[] = green_bonus(LOCALE_GBONUS_RESILIENCE_RATING, $value, $type, $level);	return;
		case 36: $green[] = green_bonus(LOCALE_GBONUS_HASTE_RATING, $value, $type, $level);			return;
		case 37: $green[] = green_bonus(LOCALE_GBONUS_EXPERTISE_RATING, $value, $type, $level);		return;
		case 38: $green[] = green_bonus(LOCALE_GBONUS_ATTACKPOWER, $value);							return;
		case 43: $green[] = green_bonus(LOCALE_GBONUS_RESTOREMANA, $value);							return;
		case 44: $green[] = green_bonus(LOCALE_GBONUS_ARMORPENETRATION, $value, $type, $level);		return;
		case 45: $green[] = green_bonus(LOCALE_GBONUS_SPELLPOWER, $value);							return;
		default: $green[] = green_bonus(LOCALE_GBONUS_UNKNOWN, $type);								return;
	}
}

function socket_type($type)
{
	switch($type)
	{
		case 1:
			return '<span class="socket-meta q0">'.LOCALE_SOCKET_META.'</span>';
		case 2:
			return '<span class="socket-red q0">'.LOCALE_SOCKET_RED.'</span>';
		case 4:
			return '<span class="socket-yellow q0">'.LOCALE_SOCKET_YELLOW.'</span>';
		case 8:
			return '<span class="socket-blue q0">'.LOCALE_SOCKET_BLUE.'</span>';
		default:
			return '<a class="q0">Unknown Socket('.$type.')</a>';
	}
}

function socket_bonus($bonus)
{
	global $DB;
	return $DB->selectCell('SELECT text_loc'.$_SESSION['locale'].' FROM ?_itemenchantmet WHERE itemenchantmetID=?d LIMIT 1', $bonus); 
}

function req_spell($spell_id)
{
	global $DB;
	return $DB->selectCell('SELECT spellname_loc'.$_SESSION['locale'].' FROM ?_spell WHERE spellID=?d LIMIT 1', $spell_id);
}

function spell_to_bonus($spell_id, $trigger)
{
	$tooltip = spell_desc($spell_id);
	if($tooltip == '_empty_')
		return;
	if(!$tooltip)
		return '<a href="?spell='.$spell_id.'">Error in spell_desc for spell '.$spell_id.'</a>';
	switch($trigger)
	{
		case 0:
			$t = LOCALE_GBONUS_USE;
			break;
		case 1:
			$t = LOCALE_GBONUS_EQUIP;
			break;
		case 2:
			$t = LOCALE_GBONUS_CHANCEONHIT;
			break;
		case 6:
			// Обучает
			return;
			break;
		default:
			$t = 'Error! ';
			break;
	}
	return $t.'<a href="?spell='.$spell_id.'" class="q2">'.$tooltip.'</a>';
}

function allitemsinfo2(&$Row, $level=0)
{
	// Пустая строка
	if(!isset($Row['entry']))
		return;
	// Глобальный массив с информацие о вещях
	global $allitems;
	//  Номер очередного элемента
	$num = $Row['entry'];
	// Если уже есть
	if(isset($allitems[$num]))
		return $allitems[$num];
	// Подключение к базе
	global $DB;
	// Записываем id вещи
	$allitems[$num]['entry'] = $Row['entry'];
	// Ищем иконку
	$allitems[$num]['icon'] = $Row['iconname'];
	// Качество вещи
	$allitems[$num]['quality'] = $Row['quality'];
	// Название вещи вместе с локализацией
	$allitems[$num]['name'] = localizedName($Row);
	// Заполняем инфу о вещи
	if($level > 0)
		$allitems[$num]['info'] = render_item_tooltip($Row);

	return $allitems[$num];
}

function getitemname($id)
{
	global $DB;
	global $AoWoWconf;
	$z = $DB->select('
			SELECT name {, l.name_loc?d AS name_loc}
			FROM item_template i
			{ LEFT JOIN (locales_item l) ON l.entry=i.entry AND ? }
			WHERE
				i.entry=?
				LIMIT 10
		',
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$id,
		$AoWoWconf['patch']
	);
	$z = sanitiseitemrows($z);
	return localizedName($z[0]);
}

function allitemsinfo($id, $level=0)
{
	global $DB;
	global $allitems;
	global $item_cols;
	global $AoWoWconf;

	if(isset($allitems[$id]))
	{
		return $allitems[$id];
	} else {
		$row = $DB->selectRow('
		SELECT a.* FROM 
		(
			SELECT i.?#
			{
				, l.name_loc?d AS name_loc
				, l.description_loc?d AS description_loc
				, ?
			}
			FROM ?_icons, item_template i
			{
				LEFT JOIN (locales_item l)
				ON l.entry=i.entry AND ?
			}
			WHERE
				i.entry=?
				AND id=displayid
		) a
		INNER JOIN (
			SELECT *, MAX(patch) patchno
			FROM item_template
			WHERE patch <= ?d
			GROUP BY entry
		) b ON a.entry = b.entry AND a.patch = b.patchno
			LIMIT 1
			',
			$item_cols[$level],
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$id,
			$AoWoWconf['patch']
		);
		return allitemsinfo2($row, $level);
	}
}

function render_item_tooltip(&$Row)
{
	// БД
	global $DB;
	// Строковые константы
	global $resz, $resz_desc, $bag_typez, $bond, $slot, $armor_type, $weapon_type, $projectile_type;
	// Зеленый текст
	global $green;
	// Столбцы для извлечения
	global $itemset_col;
	
	$green = array();

	$x = '';
	// Начальный тег таблицы
	$x .= '<table><tr><td>';
	// Название и цвет названия
	$x .= '<b class="q'.$Row['quality'].'">'.localizedName($Row).'</b>';
	// Биндинг вещи
	$x .= $bond[$Row['bonding']];

	// Уникальность вещи
	if($Row['maxcount']==1)
		$x .= '<br />'.LOCALE_UNIQUE;

	if($Row['maxcount']>1)
		$x .= ' ('.$Row['maxcount'].')';

 	if($Row['startquest'])
		$x .= '<br /><a class="q1" href="?quest='.$Row['startquest'].'">'.LOCALE_START_QUEST.'</a>';

	// Локация, для которой предназначен этот предмет
	if($Row['Map'])
		$x .= '<br />'.$DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_zones WHERE mapid=?d LIMIT 1', $Row['Map']);;

	// Теперь в зависимости от типа предмета
	if($Row['ContainerSlots'] > 1)
		$x .= '<br />'.$Row['ContainerSlots'].LOCALE_SLOT.$bag_typez[$Row['BagFamily']];
	if(in_array($Row['class'], array(4, 2, 6, 7)))
	{
		// Броня (4), Оружие(2), Патроны(6)
		// Начало таблицы св-в брони
		$x .= '<table width="100%">';
		$x .= '<tr>';
		// Слот
		$x .= '<td>'.$slot[$Row['InventoryType']].'</td>';
		// Тип брони
		if($Row['class'] == 4)
			$x .= '<th>'.$armor_type[$Row['subclass']].'</th>';
		elseif($Row['class'] == 2)
			$x .= '<th>'.$weapon_type[$Row['subclass']].'</th>';
		elseif($Row['class'] == 6)
			$x .= '<th>'.$projectile_type[$Row['subclass']].'</th>';
		$x .= '</tr></table>';
	} else {
		$x .= '<br />';
	}

	// Урон
	$dps=0;
	for($j=1;$j<=2;$j++)
	{
		$d_type = $Row['dmg_type'.$j];
		$d_min = $Row['dmg_min'.$j];
		$d_max = $Row['dmg_max'.$j];
		if(($d_max>0) and ($Row['class']!=6))
		{
			$delay = $Row['delay'] / 1000;
			if($delay>0) {$dps = $dps+round(($d_max+$d_min)/(2*$delay),1);}
			if($j>1) {$delay=0;}
			$x .= inv_dmg($d_min, $d_max, $delay, $d_type);
		} elseif(($d_max>0) and ($Row['class']==6))
		{
			$x .= LOCALE_DPS_ADDS.' '.number_format((($d_max+$d_min)/2),1).' '.LOCALE_DPS2.'<br />';
		}
	}
	if($dps>0)
		$x .= '('.number_format($dps,1).' '.LOCALE_DPS.')<br />';
	// Кол-во брони
	if($Row['armor'])
		$x .= $Row['armor'].' '.LOCALE_ARMOR.'<br />';
	if($Row['block'])
		$x .= $Row['block'].' '.LOCALE_BLOCK.'<br />';
	
	// Различные бонусы
	for($j=1;$j<=10;$j++)
		if(($Row['stat_type'.$j]!=0) and ($Row['stat_value'.$j]!=0))
			$x .= b_type($Row['stat_type'.$j], $Row['stat_value'.$j], $Row['RequiredLevel']);

	// Бонусы к сопротивлениям магий
	foreach($resz as $j => $RowName)
	{
		if($Row[$RowName]!=0)
		{
			$x .= '+'.$Row[$RowName].' '.$resz_desc[$j].'<br />';
		}
	}

	// Случайные бонусы
	if($Row['RandomProperty'])
		$green[] = 'Random Bonuses';

	// Состояние
	if($Row['MaxDurability'])
		$x .= LOCALE_DURABILITY.' '.$Row['MaxDurability'].' / '.$Row['MaxDurability'].'<br />';
	// Требуемые классы
	if(classes($Row['AllowableClass']))
		$x .= LOCALE_CLASSES.': '.classes($Row['AllowableClass']).'<br />';

	// Требуемый уровень
	if($Row['RequiredLevel']>1)
		$x .= LOCALE_REQUIRES_LEVEL.' '.$Row['RequiredLevel'].'<br />';

	// Требуемый скилл (755 - Jewecrafting)
	if(($Row['RequiredSkill']) and ($Row['RequiredSkill']!=755))
	{
		$x .= LOCALE_REQUIRES.' '.$DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_skill WHERE skillID=?d LIMIT 1', $Row['RequiredSkill']);
		if($Row['RequiredSkillRank'])
			$x .= ' ('.$Row['RequiredSkillRank'].')';
		$x .= '<br />';
	}

	// Требуемый спелл
	if($Row['requiredspell'])
		$x .= LOCALE_REQUIRES.' '.req_spell($Row['requiredspell']).'<br />';

	// Требуемая репутация
	if($Row['RequiredReputationFaction'])
	{
		require_once ('includes/game.php');
		global $rep_levels;
		$row = factioninfo($Row['RequiredReputationFaction']);
		$x .= LOCALE_REQUIRES.' '.$row['name'].' - '.$rep_levels[$Row['RequiredReputationRank']];
	}

	$x .= '</td></tr></table>';

	// Спеллы
	for($j=1;$j<=5;$j++)
	{
		if($Row['spellid_'.$j])
			$green[]=spell_to_bonus($Row['spellid_'.$j], $Row['spelltrigger_'.$j]);
	}

	// Перебираем все "зеленые" бонусы
	$x .= '<table><tr><td>';
	if($green)
	{
		foreach($green as $j => $bonus)
			if($bonus)
				$x .= '<span class="q2">'.$bonus.'</span><br />';
	}

	if($Row['description'])
	{
		if($Row['spelltrigger_2']==6)
			$x .= '<span class="q2">'.LOCALE_GBONUS_USE.' <a href="?spell='.$Row['spellid_2'].'">'.localizedName($Row, 'description').'</a></span>';
		else
			$x .= '<span class="q">"'.localizedName($Row, 'description').'"</span>';
	}
	if($Row['PageText'])
		$x .= '<br /><span class="q2">&lt;Right Click To Read&gt;</span>'; // TODO: locale

	// Item Set
	// Временное хранилище всех вещей;
	$x_tmp = '';
	$row = $DB->selectRow('SELECT ?# FROM ?_itemset WHERE (item1=?d or item2=?d or item3=?d or item4=?d or item5=?d or item6=?d or item7=?d or item8=?d or item9=?d or item10=?d) LIMIT 1', $itemset_col[1], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry'], $Row['entry']);
	if($row)
	{
		//$x .= '<br />';
		$num = 0; // Кол-во вещей в наборе
		for($i=1;$i<=10;$i++)
		{
			if($row['item'.$i] >0)
			{
				$num++;
				$name = getitemname($row['item'.$i]);
				$x_tmp .= '<span><a href="?item='.$row['item'.$i].'">'.$name.'</a></span><br />';
			}
		}
		$x .= '<br /><span class="q"><a href="?itemset='.$row['itemsetID'].'" class="q">'.$row['name_loc'.$_SESSION['locale']].'</a> (0/'.$num.')</span><br />';
		// Если требуется скилл
		if($row['skillID'])
		{
			$name = $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_skill WHERE skillID=?d LIMIT 1', $row['skillID']);
			$x .= LOCALE_REQUIRES.' <a href="?spells=11.'.$row['skillID'].'" class="q1">'.$name.'</a>';
			if($row['skilllevel'])
				$x .= ' ('.$row['skilllevel'].')';
			$x .= '<br />';
		}
		// Перечисление всех составляющих набора
		$x .= '<div class="q0 indent">'.$x_tmp.'</div><br />';
		// Перечисление всех бонусов набора
		$x .= '<span class="q0">';
		$num = 0;
		for($j=1;$j<=8;$j++)
			if($row['spell'.$j])
			{
				$itemset['spells'][$num]['entry'] = $row['spell'.$j];
				$itemset['spells'][$num]['tooltip'] = spell_desc($row['spell'.$j]);
				$itemset['spells'][$num]['bonus'] = $row['bonus'.$j];
				$num++;
			}
		// Сортировка бонусов
		$x .= '<span class="q0">';
		for($i=0;$i<$num;$i++)
		{
			for($j=$i;$j<=$num-1;$j++)
				if($itemset['spells'][$j]['bonus'] < $itemset['spells'][$i]['bonus'])
				{
					UnSet($tmp);
					$tmp = $itemset['spells'][$i];
					$itemset['spells'][$i] = $itemset['spells'][$j];
					$itemset['spells'][$j] = $tmp;
				}
			$x .= '<span>('.$itemset['spells'][$i]['bonus'].') Set: <a href="?spell='.$itemset['spells'][$i]['entry'].'">'.$itemset['spells'][$i]['tooltip'].'</a></span><br />';
		}
		$x .= '</span></span>';
	}
	$x .= '</td></tr></table>';
	return $x;
}

// Функция информации о вещи
function iteminfo2(&$Row, $level=0)
{
	global $DB;
	global $allitems;
	global $spell_cols;
	global $object_cols;

	$item = array();
	// Номер вещи
	$item['entry'] = $Row['entry'];
	// Название вещи
	$item['name'] = localizedName($Row);
	// Тип вещи
	$item['type'] = $Row['InventoryType'];
	$item['displayid'] = $Row['displayid'];
	// Уровень вещи
	$item['level'] = $Row['ItemLevel'];
	// Качество вещи...
	$item['quality'] = $Row['quality'];
	$item['quality2'] = 7 - $Row['quality'];
	// Требуемый уровень вещи:
	$item['reqlevel'] = $Row['RequiredLevel'];
	// Класс и подкласс вещи
	// TODO: немного неверное определение
	$item['classs'] = $Row['class'];
	$item['subclass'] = $Row['subclass'];
	// Иконка вещи
	$item['iconname'] = $Row['iconname'];
	// Кол-во вещей в пачке
	$item['stackable'] = $Row['stackable'];
	// Стоимость вещи для покупки
	// DPS
	$dps = 0;
	if($Row['class']==2)
	{
		for($i=1;$i<=2;$i++)
		{
			$d_type = $Row['dmg_type'.$i];
			$d_min = $Row['dmg_min'.$i];
			$d_max = $Row['dmg_max'.$i];
			if(($d_max>0) and ($Row['class']!=6))
			{
				$delay = $Row['delay'] / 1000;
				if($delay>0) {$dps = $dps+round(($d_max+$d_min)/(2*$delay),1);}
			}
		}
		$item['dps'] = $dps;
		$item['speed'] = $Row['delay']/1000;
		if(!$item['speed']) $item['speed'] = -1;
	}
	// Armor
	$item['armor'] = $Row['armor'];
	$item['slot'] = $Row['InventoryType'];
	// Bag
	if($Row['class']==1)
		$item['slots'] = $Row['ContainerSlots'];
	// Добавляем в глобальный массив allitems
	allitemsinfo2($Row, 0);
	if($level>0)
	{
		$item['BuyPrice'] = $Row['BuyPrice'];
		//
		$item['BagFamily'] = $Row['BagFamily'];
		$item['ContainerSlots'] = $Row['ContainerSlots'];
		$item['DisenchantID'] = $Row['DisenchantID'];
		// Цена на продажу
		$item['sellgold'] = floor($Row['SellPrice']/10000);
		$item['sellsilver'] = floor($Row['SellPrice']%10000/100);
		$item['sellcopper'] = floor($Row['SellPrice']%100);
		// Цена за покупку
		$item['buygold'] = floor($Row['BuyPrice']/10000);
		$item['buysilver'] = floor($Row['BuyPrice']%10000/100);
		$item['buycopper'] = floor($Row['BuyPrice']%100);
		// Начинает квест
		if($Row['startquest'])
			$item['starts'] = array(GetDBQuestInfo($Row['startquest'], 0xFFFFFF));
		// Информационное окно
		$item['info'] = render_item_tooltip($Row);
		// Обучает
		$teaches = array();
		for($j=1;$j<=4;$j++)
			if($Row['spellid_'.$j]==483)
				$teaches[] = spellinfo($Row['spellid_'.($j+1)]);
		if($teaches)
		{
			$item['teaches'] = $teaches;
			unset($teaches);
			unset($spellrow);
		}
		// Открывает:
		// Тип замков, для которых этот предмет является ключем:
		$locks_row = $DB->selectCol('
			SELECT lockID
			FROM ?_lock
			WHERE
				(type1=1 AND lockproperties1=?d) OR
				(type2=1 AND lockproperties2=?d) OR
				(type3=1 AND lockproperties3=?d) OR
				(type4=1 AND lockproperties4=?d) OR
				(type5=1 AND lockproperties5=?d)
			',
			$item['entry'], $item['entry'], $item['entry'], $item['entry'], $item['entry']
		);
		if($locks_row)
		{
			// Игровые объекты с таким типом замка:
			$item['unlocks'] = $DB->select('
				SELECT ?#
				FROM gameobject_template
				WHERE
					(
						((type IN (?a)) AND (data0 IN (?a)))
					OR
						((type IN (?a)) AND (data0 IN (?a)))
					)
				',
				$object_cols[0],
				array(GAMEOBJECT_TYPE_QUESTGIVER, GAMEOBJECT_TYPE_CHEST, GAMEOBJECT_TYPE_TRAP, GAMEOBJECT_TYPE_GOOBER, GAMEOBJECT_TYPE_CAMERA, GAMEOBJECT_TYPE_FLAGSTAND, GAMEOBJECT_TYPE_FLAGDROP),
				$locks_row,
				array(GAMEOBJECT_TYPE_DOOR, GAMEOBJECT_TYPE_BUTTON),
				$locks_row
			);
			if(!$item['unlocks'])
				unset($item['unlocks']);
		}
		unset($locks_row);
	}
	return $item;
}

// Функция информации о вещи
function iteminfo($id, $level = 0)
{
	global $item_cols;
	global $DB;
	$row = $DB->select('
		SELECT i.?#, i.entry
		{
			, l.name_loc?d AS name_loc
			, l.description_loc?d AS description_loc
		}
		FROM ?_icons, item_template i
		{ LEFT JOIN (locales_item l) ON l.entry=i.entry AND ? }
		WHERE
			(i.entry=?d and id=displayid)
		LIMIT 5
		',
		$item_cols[2+$level],
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$id
	);
	$row = sanitiseitemrows($row);
	return iteminfo2($row[0], $level);
}

// Sanitise item rows for progressive data
/**
 * @param type $rows
 * @return type array of rows
 */
 function sanitiseitemrows($rows)
 {
	//echo "Sanitising!";
	global $AoWoWconf;
	if (empty($rows))
		return $rows;
	 // Remove items that are of a higher patch
	foreach ($rows as $i => $row) {
		// Check if the patch number is valid
		if ($row['patch'] > $AoWoWconf['patch'])
		{
			//echo "Too new! - Removing " . $row['name'] . ' from patch ' . $row['patch'] . ' From index ' . $i . "! <br>";
			unset($rows[$i]);
		}
	};
	// Check for duplicates, and if there are, find the highest patched and delete the others.
	foreach ($rows as $i => $row) {
		foreach ($rows as $j => $jrow) {
			if ($row['entry'] == $jrow['entry']) {
				if ($row['patch'] > $jrow['patch']) {
					//echo "Too old! - Removing " . $jrow['name'] . ' from patch ' . $jrow['patch'] . ' From index ' . $j . "! <br>";
					unset($rows[$j]);
				}
			}
		}
	};
	/*
	foreach ($rows as $i => $row) {
		echo "Remaining Items: " . $row['name'] . " of patch " . $row['patch'] . " at index " . $i . "<br>";
	}
	*/
	return array_values($rows);
 }

?>