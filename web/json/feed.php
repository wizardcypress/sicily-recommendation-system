<?php

include_once("inc/global.inc.php");
require("inc/user.inc.php");

function get_user($uid) {
    global $conn;
    $rs = new RecordSet($conn);
    
    if(!$rs) return NULL;
    $rs->Query("SELECT * FROM user WHERE uid=$uid");
    if($rs->MoveNext()) {
        return $rs->Fields; 
    }
}


class Jsonfeed {
    static function get_comment($fid)
    {
        global $conn;
        $res = array();
        $rs = new RecordSet($conn);
        $rs->Query("SELECT * FROM comments WHERE fid = $fid ORDER BY time DESC");
        while($rs->MoveNext())
        {
            $comment = array();
            $user = get_user($rs->Fields['uid']);
            $comment['uid'] = $user['uid'];
            $comment['username'] = $user['username']; 
            $comment['content'] = $rs->Fields['content'];
            $comment['time'] = $rs->Fields['time'];
            $res[] = $comment;
        }
        return $res;
    }
    static function del_feed($fid)
    {
        global $login_uid;
        global $conn;
        $ret = array();
        $rs = new RecordSet($conn);
        $rs->Query("SELECT uid FROM feeds WHERE fid = $fid");
        if(!$rs->MoveNext()) {
            $ret['success'] = 0;
            return $ret; 
        }
        if($login_uid == $rs->Fields['uid']) {
            if($rs->Query("DELETE FROM feeds WHERE fid = $fid"))  {
                $ret['success'] = 1;
                //delete all relate comments
                $rs->Query("DELETE FROM comments WHERE fid =$fid");
            }
            else $ret['success'] = 0;
        } else {
            $ret['success'] = 0;
        }

        return $ret;
    }

    static function get_msg()
    {
        global $login_uid;
        global $conn;
        $rs = new RecordSet($conn);
        $rs->Query("SELECT * FROM msgbox WHERE uid = $login_uid AND status='unread' LIMIT 1");
        $res = array();
        if($rs->MoveNext())
        {
            $res['success'] = 1;
            $res['content'] = $rs->Fields['htmlcontent'];
            $mid = $rs->Fields['mid'];
            $rs->Query("UPDATE msgbox SET status='readed' WHERE mid=$mid");
        } else $res['success']  = 0;
        
       return $res; 
    }
}

?>
