<?php
require("./navigation.php");
global $conn;
$rs = new RecordSet($conn);
$rs->Query("SELECT content FROM sicilychan WHERE avail = 1 ORDER BY RAND() LIMIT 1");
$rs->MoveNext();
$talk = $rs->Fields['content'];
?>
<center>
    <img src="images/cartoon/cong.jpg" />
</center>
<style>
    #sicily_chan {
        background:url(images/cartoon/sicilychan3_sprite.png) no-repeat 0px 0px;
        height:450px;
        width:300px;
        right: 150px;
        top: auto;
        bottom: 0px;
        position: fixed;
    }

    #face {
        height:450px;
        width:300px;
        position: absolute;
    }

    .face1 {
        background:url(images/cartoon/sicilychan3_sprite.png) no-repeat -300px 0px;
    }

    .face2 {
        background:url(images/cartoon/sicilychan3_sprite.png) no-repeat -600px 0px;
    }

    .face3 {
        background:url(images/cartoon/sicilychan3_sprite.png) no-repeat -900px 0px;
    }

    #boxtop {
        background-image: url("images/cartoon/box-1.png");
        background-repeat: no-repeat;
        width: 219px;
        height: 17px;
    }
    #boxbottom {
        background-image: url("images/cartoon/box-3.png");
        background-repeat: no-repeat;
        width: 219px;
        height: 31px;
    }

    #boxcnt {
        background-image: url("images/cartoon/box-2.png");
        background-repeat: repeat-y;
        padding-left: 15px;
        padding-right: 15px;
        width: 189px;
    }

    #chanbox {
        position: absolute;
        left: -100px;
    }
</style>

<script type="text/javascript">
    $(function(){
        $("#sicily_chan").draggable();
        setInterval(function(){
            $("#face").removeClass("face1").addClass("face2");
            setTimeout(function() {
                $("#face").removeClass("face2").addClass("face3");
                setTimeout(function() {
                    $("#face").removeClass("face3").addClass("face1");
                }, 100);
            }, 100);
        }, 5000);
    });
        
</script>
<div id="sicily_chan">
    <div id="face" class="face1"> </div>
    <div id="chanbox">
        <div id="boxtop"> </div>
        <div id="boxcnt"> <?= $talk ?>
        </div>
        <div id="boxbottom"> </div>

    </div>
</div>
<?php
require("./footer.php");
?>
