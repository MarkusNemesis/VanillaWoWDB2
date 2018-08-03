<?php

/*
 * UDWBase: WOWDB Web Interface
 *
 * © UDW 2009-2011
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://gnu.org).
 *
 */

require_once('includes/game.php');

/**
 *
 * @param type $type
 * @param type $typeid
 * @return int 
 */
function getcommentswh($type, $typeid) {
    global $DB;
    global $rDB;

    $rows = $DB->select('
                SELECT id, userid, post_date, commentbody, replyto
                FROM ?_comments_wh
                WHERE type=? AND typeid=?
                ORDER BY replyto, post_date
                ', $type, $typeid
    );

    $commentswh = array();

    foreach ($rows as $i => $row) {
        $commentswh[$i] = array();
        $commentswh[$i] = $rDB->selectRow('SELECT username as user, gmlevel as roles FROM ?_account_wh WHERE id=? LIMIT 1', $row['userid']);
        if (!(IsSet($commentswh[$i]['user'])))
            $commentswh[$i]['user'] = "Anonymous";
        $commentswh[$i]['number'] = $i;
        $commentswh[$i]['id'] = $row['id'];
        $commentswh[$i]['body'] = $row['commentbody'];
        $commentswh[$i]['date'] = $row['post_date'];
        $commentswh[$i]['replyto'] = $row['replyto'];
        if ($commentswh[$i]['replyto'] != $commentswh[$i]['id'])
            $commentswh[$i]['indent'] = 1;

        $commentswh[$i]['raters'] = array();
        $commentswh[$i]['raters'] = $DB->select('SELECT userid, rate FROM ?_comments_rates_wh WHERE commentid=?d', $commentswh[$i]['id']);
        $commentswh[$i]['rating'] = sum_subarrays_by_key($commentswh[$i]['raters'], 'rate');
        $commentswh[$i]['purged'] = ($commentswh[$i]['rating'] <= -50) ? 1 : 0;
        $commentswh[$i]['deleted'] = 0;
    }

    return $commentswh;
}

