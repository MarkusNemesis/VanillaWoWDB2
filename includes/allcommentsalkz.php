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
function getcommentsalkz($type, $typeid) {
    global $DB;
    global $rDB;

    $rows = $DB->select('
		SELECT id, userid, post_date, commentbody, replyto
		FROM ?_comments_alkz
		WHERE type=? AND typeid=?
		ORDER BY replyto, post_date
		', $type, $typeid
    );

    $commentsalkz = array();

    foreach ($rows as $i => $row) {
        $commentsalkz[$i] = array();
        $commentsalkz[$i] = $rDB->selectRow('SELECT username as user, gmlevel as roles FROM ?_account_alkz WHERE id=? LIMIT 1', $row['userid']);
        if (!(IsSet($commentsalkz[$i]['user'])))
            $commentsalkz[$i]['user'] = "Anonymous";
        $commentsalkz[$i]['number'] = $i;
        $commentsalkz[$i]['id'] = $row['id'];
        $commentsalkz[$i]['body'] = $row['commentbody'];
        $commentsalkz[$i]['date'] = $row['post_date'];
        $commentsalkz[$i]['replyto'] = $row['replyto'];
        if ($commentsalkz[$i]['replyto'] != $commentsalkz[$i]['id'])
            $commentsalkz[$i]['indent'] = 1;

        $commentsalkz[$i]['raters'] = array();
        $commentsalkz[$i]['raters'] = $DB->select('SELECT userid, rate FROM ?_comments_rates_alkz WHERE commentid=?d', $commentsalkz[$i]['id']);
        $commentsalkz[$i]['rating'] = sum_subarrays_by_key($commentsalkz[$i]['raters'], 'rate');
        $commentsalkz[$i]['purged'] = ($commentsalkz[$i]['rating'] <= -50) ? 1 : 0;
        $commentsalkz[$i]['deleted'] = 0;
    }

    return $commentsalkz;
}
