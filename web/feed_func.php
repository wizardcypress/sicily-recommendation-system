<?php

function get_user($uid) {
    global $conn;
    $rs = new RecordSet($conn);
    
    if(!$rs) return NULL;
    $rs->Query("SELECT * FROM user WHERE uid=$uid");
    if($rs->MoveNext()) {
        return $rs->Fields; 
    }
}

function sql_fetchone($sql)
{
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query($sql);
    if($rs->MoveNext()) {
        return $rs->Fields[0];
    }
    return array();
}

function recommend_sort($a, $b)
{
    return $a[1] < $b[1] ? 1 : -1;
}

function getProblemInfo($pid)
{
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("SELECT * FROM problems WHERE pid = $pid");
    if($rs->MoveNext()) return $rs->Fields;
    else return array();
}

function get_problem($lim = 10)
{
    //return array();
    global $conn;
    global $login_uid;
    global $logged;
    $rs = new RecordSet($conn);
    
    $solved = array();
    $unsolve = array();
    $all_prob = array();
    $rate = array();
    
    //select all problems
    $rs->Query("SELECT pid FROM problems");
    while($rs->MoveNext())
        $all_prob[] = $rs->Fields['pid'];
    
    //select solved problems
    $rs->Query("SELECT pid,user_rate.right,rate FROM user_rate WHERE uid=" . $login_uid);
    while($rs->MoveNext())
    {
        if($rs->Fields['right'] > 0) {
            $solved[$rs->Fields['pid']] = 1;
            $rate[$rs->Fields['pid']] = $rs->Fields['rate'];
        }
    }

    //cal unsolve problems
    foreach($all_prob as $p)
        if(!isset($solved[$p]))
            $unsolve[$p]= 0;
    
    $res = array();
    $tot = array(); 
    $sim_dir = array();
    
    //foreach($solved as $p1 => $v1) {
    //    foreach($unsolve as $p2 => &$v2) {
    //        //$rs->Query("SELECT * FROM prob_sim WHERE pid1 = $p1 and pid2 = $p2"); 
    //        //$sim = 0;
    //        //if($rs->MoveNext()) $sim = $rs->Fields['sim'];
    //        //else {
    //        //    $rs->Query("SELECT * FROM prob_sim WHERE pid1 = $p2 and pid2 = $p1");
    //        //    if($rs->MoveNext()) $sim = $rs->Fields['sim'];
    //        //}
    //        //if(!isset($tot[$p2])) $tot[$p2] = 0;
    //        //$tot[$p2] += $sim;
    //        //$v2 += ($sim * $rate[$p1]);
    //    }
    //}

    //$res = array();
    //foreach($unsolve as $p2 => &$v2) {
    //    if(isset($tot[$p2]) && $tot[$p2] > 0) {
    //        $v2 = $v2/$tot[$p2];
    //        $res[] = array($p2, $v2);
    //    }
    //} 

    //if(count($solved) < count($unsolve))  {
        foreach($solved as $p1 => $v1)
        {
            $sim_dir[$p1] = array();
            $rs->Query("SELECT * FROM prob_sim WHERE pid1 = $p1 or pid2 = $p1");
            while($rs->MoveNext())
            {
                if($rs->Fields['pid1'] == $p1) $p2 = $rs->Fields['pid2'];
                else $p2 = $rs->Fields['pid1'];
                $sim_dir[$p1][$p2] = $rs->Fields['sim'];
            }
            foreach($unsolve as $p2 => &$v2) 
            {
                if(isset($sim_dir[$p1][$p2])) $sim = $sim_dir[$p1][$p2];
                else $sim = 0;
                if(!isset($tot[$p2]))  $tot[$p2] = 0;
                $tot[$p2] += $sim;
                $v2 += ($sim*$rate[$p1]);
            }
        }
        $res = array();
        foreach($unsolve as $p2 => &$v2) {
            if(isset($tot[$p2]) && $tot[$p2] > 0) {
                $v2 = $v2/$tot[$p2];
                $res[] = array($p2, $v2);
            }
        }
    //} else {
    //    foreach($unsolve as $p1 => $v1)
    //    {
    //        $tot = 0; 
    //        $val = 0;
    //        $dir = array();
    //        $rs->Query("SELECT * FROM prob_sim WHERE pid1 = $p1 or pid2 = $p1");
    //        while($rs->MoveNext()) {
    //            if($rs->Fields['pid1'] == $p1) $p2 = $rs->Fields['pid2'];
    //            else $p2 = $rs->Fields['pid1'];
    //            $dir[$p2] = $rs->Fields['sim'];
    //        }
    //        foreach($solved as $p2 => $one)
    //        {
    //            if(isset($dir[$p2])) $sim = $dir[$p2];
    //            else $sim = 0;
    //            $tot = $tot + $sim; 
    //            $val = $val + $sim * $rate[$p2];
    //        }
    //        if($tot == 0) $val = -1;
    //        else $val = $val/$tot; 
    //        $res[]=array($p1, $val);
    //    }
    //}
    usort($res, "recommend_sort");
    $recom_probs = array_slice($res, 0, $lim);

    $myfollows = array();
    $rs->Query("SELECT uid2 FROM follows WHERE uid1 = $login_uid");
    while($rs->MoveNext())
        $myfollows[] = $rs->Fields['uid2'];
    
    $solver = array();
    foreach($recom_probs as $rp)
    {
        $slv = array();
        foreach($myfollows as $mf)  
        {
            $rs->Query("SELECT user_rate.right+user_rate.wrong FROM user_rate WHERE uid = $mf and pid = $rp[0]");
            if($rs->MoveNext()) {
                if($rs->Fields[0] > 0) {
                    $slv[] = get_user($mf); 
                }
            }
        }
        $solver [] = $slv;
    }
    $res = array();
    for($i=0; $i<count($recom_probs); $i++)
    {
        $res[] = array('problem' => getProblemInfo($recom_probs[$i][0]), 'solver' => $solver[$i]);
    }

    return $res;
}

function sort_count_arr($a, $b) 
{
    return $a[1] > $b[1]? -1: 1;
}
function get_hotprobs($lim = 10)
{
    global $conn;
    global $login_uid;
    $rs = new RecordSet($conn);

    $calnum = 4000;
    $count_arr = array();
    $rs->Query("SELECT count(*) FROM status");
    if(!$rs->MoveNext()) {
        return array();
    }
    $totalStatus = $rs->Fields[0];
    $totProbs = sql_fetchone("SELECT count(*) FROM problems"); 
    $users = array();
    $rs->Query("SELECT * FROM status ORDER BY sid LIMIT $calnum");
    while($rs->MoveNext()) {
        $uid = $rs->Fields['uid'];
        if(!isset($users[$uid])) {
            $users[$uid] = get_user($uid);
        }
        $user = $users[$uid];
        if(!isset($count_arr[$rs->Fields['pid']])) $count_arr[$rs->Fields['pid']] = 0;
        else $count_arr[$rs->Fields['pid']] += ($user['solved']*$user['solved']*1.0/$totProbs/$user['submissions']);
    }
    $parr = array();
    foreach($count_arr as $key => $val) {
        $parr[] = array($key, intval($val*10));
    }
    usort($parr, "sort_count_arr");
    return array_slice($parr, 0, $lim);
}

function feed_sort($a, $b)
{
    return $a['time'] > $b['time'] ? -1 : 1;
}
function get_feed()
{
    global $conn;
    global $login_uid;
    $rs = new RecordSet($conn);     
    
    $rs->Query("SELECT uid2 FROM follows WHERE uid1 = $login_uid"); 
    $follows = array();   
    while($rs->MoveNext()) 
    {
        $follows[$rs->Fields['uid2']] = 1;
    }
    
    $rs->Query("SELECT * FROM feeds");
    
    $res = array();
    while($rs->MoveNext())
    {
        if(isset($follows[$rs->Fields['uid']])) $res[] = $rs->Fields;
    }

    usort($res, "feed_sort"); 
    $pageSize = 10;
    if(!isset($_GET['page'])) {
        $page = 1;
    } else $page = intval($_GET['page']);
    $count = 0; 
    $ret = array();
    $totalPage = intval(count($res) / $pageSize);
    if(count($res) % $pageSize > 0) $totalPage += 1; 

    foreach($res as &$f) {
        if($count >= ($page-1)*$pageSize && $count < $page*$pageSize) {
            $user = get_user($f['uid']);
            $f['username'] = $user['username'];
            $ret[] = $f;
        }
        $count += 1;
    }

    $allPages = array();
    for($i=1; $i<=$totalPage; $i++) {
        $allPages[] = $i;
    }
    return array($allPages, $ret);
}
function add_feed()
{
    global $login_uid;
    if(isset($_POST['content']) && $_POST['content'] != "")  {
        $rs = new RecordSet($conn);
        $content = $_POST['content'];
        $rs->Query("INSERT INTO feeds(uid,content) values($login_uid,'$content')");
    }
}

function add_reply()
{
    global $login_uid;
    global $conn;

    if(isset($_POST['reply-content']) && isset($_POST['reply-fid'])) {
        $rs = new RecordSet($conn);
        $reply_fid = $_POST['reply-fid'];
        $rs->Query("SELECT * FROM feeds WHERE fid = $reply_fid");
        if(!$rs->MoveNext()) return;
        $old_content = $rs->Fields['content'];
        $re_uid = $rs->Fields['uid'];
        $new_content = $_POST['reply-content'];
        
        $user = get_user($re_uid);
        if(!$user) return ;
        $new_content = $new_content . ' //@' .  $user['username'] . ": " . $old_content;
            
        $rs->Query("INSERT INTO feeds(uid,content,reply_fid) values($login_uid, '$new_content', $reply_fid)");
        
        $last_fid = $reply_fid;
        while($last_fid != 0) {
            $rs->Query("UPDATE feeds SET reply=reply+1 WHERE fid = $last_fid"); 
            $last_fid = sql_fetchone("SELECT reply_fid FROM feeds WHERE fid = $last_fid");
        }

        //add msg
        $me = get_user($login_uid);
        $myname = $me['username'];
        $uid = $user['uid'];
        $username = $user['username'];
        $content = "<a href='/user.php?id=$uid'>$username</a>" . " 转发： ". $_POST['reply-content'] . "//@$myname: $old_content";
        $rs->Query("INSERT INTO msgbox(uid, htmlcontent) VALUES($uid, \"$content\")");
    }
}

function add_comment()
{
    global $conn;
    global $login_uid;
    if(isset($_POST['comment-content']) && isset($_POST['comment-fid'])) {
        $rs = new RecordSet($conn); 
        $comment_fid = $_POST['comment-fid'];
        $content = $_POST['comment-content'];
        
        $rs->Query("SELECT uid FROM feeds WHERE fid = $comment_fid");
        $rs->MoveNext();
        $user = get_user($rs->Fields['uid']);

        $rs->Query("INSERT INTO comments(fid, uid, content) values($comment_fid, $login_uid, '$content')");
        //add comment num
        $rs->Query("UPDATE feeds set comment=comment+1 WHERE fid = $comment_fid");
        //add message
        $uid = $user['uid'];
        $username = $user['username'];
        $msg = "收到 <a href='/user.php?id=$uid'>$username</a> 的评论: $content"; 
        $rs->Query("INSERT INTO msgbox(uid, htmlcontent) values($uid, \"$msg\")");
    }
}

function get_ifollow()
{
    global $conn;
    global $login_uid;
    $rs = new RecordSet($conn);
    $rs2 = new RecordSet($conn);
    $rs->Query("SELECT uid2 FROM follows WHERE uid1 = $login_uid ORDER BY time DESC");
    $res = array();
    while($rs->MoveNext())
    {
        $uid = $rs->Fields['uid2'];
        $user = get_user($uid);
        $rs2->Query("SELECT * FROM status WHERE uid = $uid ORDER BY time DESC LIMIT 10");
        $touch = array();
        $count = array();
        while($rs2->MoveNext()) {
            $pid = $rs2->Fields['pid'];
            if(!isset($count[$pid])) {
                $count[$pid] = 0;
                $touch[] = $pid;
            }
            $count[$pid]++;
        }
        $user['touch'] = array();
        for($i=0; $i<min(3,count($touch)); $i++) 
        {
            $user['touch'] []= array('pid' => $touch[$i], 'count' => $count[$touch[$i]]);
        }
        $res[] = $user;
    }
    return $res;
}

function get_myfollow($uid)
{
    $rs = new RecordSet($conn);
    $rs->Query("SELECT uid2 FROM follows WHERE uid1 = $uid");
    $res = array();
    while($rs->MoveNext())
    {
        $res[$rs->Fields['uid2']] = 0;
    }
    return $res;
}
function merge_array(&$arr1, &$arr2)
{
    $res = array();
    foreach($arr1 as $a1 => $val) 
        $res[$a1] = 0;
    foreach($arr2 as $a2 => $val)
        $res[$a2] = 0;
    return $res;
}

function get_recomuser()
{
    global $conn;
    global $login_uid;

    $rs = new RecordSet($conn);
    $rs->Query("SELECT uid2 FROM follows WHERE uid1 = $login_uid");

    $follows = get_myfollow($login_uid);
    $will_be_follow = array('-1' => 0);
    foreach($follows as $f => $val)  
    {
        $their_follows = get_myfollow($f);
        $will_be_follow = merge_array($will_be_follow, $their_follows);
    }
    
    $res = array(); 
    foreach($will_be_follow as $key => &$val)  
    {
        if(isset($follows[$key])) continue;
        $their_follows = get_myfollow($key); 
        $tmp = array_intersect_key($follows, $their_follows);
        $val = count($tmp); 
        foreach($tmp as $t => &$value) $value = get_user($t); 
        if($val > 0) {
            $user = get_user($key);
            $res[] = array('user' => $user ,'count' => $val, 'common' => $tmp);
        } 
    }
    shuffle($res);
    return array_slice($res, 0, 5);
}

function handle_ajax()
{
    global $conn;
    global $login_uid;
    
    $rs = new RecordSet($conn);
    
    $res = "";
    if(isset($_GET['getcomment'])) {
        $fid = $_GET['fid'];
        $rs->Query("SELECT uid,content,time FROM comments WHERE fid = $fid ORDER BY time DESC");
        while($rs->MoveNext()) {
            $user = get_user($rs->Fields['uid']);
            $content = $rs->Fields['content'];
            $time = $rs->Fields['time'];
            $res = $res . $user['username'] . $time . $content;
        }
        echo $res;
    }
}

function show_feeds()
{
    global $tpl; 
    global $login_uid;
    // get recommend problems
    $probs = get_problem(10);
    // get hot problems
    $hotprobs = get_hotprobs(10);
    // get feeds
    $feed_pair = get_feed();
    $feeds = $feed_pair[1];
    $allPages = $feed_pair[0];
    if(isset($_GET['page'])) $curPage = $_GET['page'];
    else $curPage = 1;
    //get users
    $users = get_recomuser();

    $tpl->loadTemplate("feed.html");
    echo $tpl->render(array(
        "login_uid" => $login_uid,
        "probs" => $probs,

        "feeds" => $feeds,
        "allPages" => $allPages,
        "curPage" => $curPage,
            
        "hotprobs" => $hotprobs,
        "ifollow" => get_ifollow(),
        "recom_users" => $users
    ));

}

global $tpl;
global $logged;
global $login_uid;

if(!$logged) 
{
    echo "Please login first";    
} else {
    if($_SERVER['REQUEST_METHOD'] == 'POST')  {
        if(isset($_POST['feed'])) {
            add_feed();
        } else if(isset($_POST['reply-fid'])) {
            add_reply();
        } else if (isset($_POST['comment-fid'])) {
            add_comment();
        }
        header("Location: /feed.php");
    } else {
        if(isset($_GET['ajax'])) {
            handle_ajax();
        } else {
            show_feeds();
        }
    }
}
?>
