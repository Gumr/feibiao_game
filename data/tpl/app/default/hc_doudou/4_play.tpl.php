<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<!-- saved from url=(0122)https://wxopen.yiszh.com/app/index.php?i=2&c=entry&do=game&m=wn_ttrouges&openid=&game_id=undefined&hykj=wnkj6142228&title= -->
<html lang="en" style="font-size: 79.5424px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title></title>
    <link rel="stylesheet" href="../addons/hc_doudou/template/mobile/css/game.css">
    <script type="text/javascript" src="../addons/hc_doudou/template/mobile/js/bodymovin.js"></script>
    <script type="text/javascript" src="../addons/hc_doudou/template/mobile/js/jweixin-1.3.2.js"></script>
    <script typet="text/javascript" src="../addons/hc_doudou/template/mobile/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../addons/hc_doudou/template/mobile/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="../addons/hc_doudou/template/mobile/js/JicemoonMobileTouch.js"></script>
    <script type="text/javascript" src="../addons/hc_doudou/template/mobile/js/HardestGame.js"></script>
    <script type="text/javascript" src="../addons/hc_doudou/template/mobile/js/index.js"></script>
</head>
<body>
<audio id="back_music" preload="" src="../addons/hc_doudou/template/mobile/audio/bg_audio.mp3" loop="true"></audio>
<audio id="split_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/split_audio.mp3"></audio>
<audio id="collision_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/collision_audio.mp3"></audio>
<audio id="Countdown_10s_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/Countdown_10s_audio.mp3"></audio>
<audio id="gameFail_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/gameFail_audio.mp3"></audio>
<audio id="gameSuccess_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/gameSuccess_audio.mp3"></audio>
<audio id="insert_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/insert_audio.mp3"></audio>
<audio id="success_audio" preload="" src="../addons/hc_doudou/template/mobile/audio/success_audio.mp3"></audio>

<div class="levelSwitchBox" id="levelSwitchBox" style="display: block;">
    <img id="levelSwitchBoxMain" class="levelSwitchBoxMain" src="../addons/hc_doudou/template/mobile/img/level_1_main.jpg">

</div>
<div class="PopupBox" id="gameOverBox" style="display: none;">
    <!--<div id="gameOverClose" class="close"><img src="https://wxopen.yiszh.com/addons/wn_ttrouges/style/img/close_btn.jpg"></div>-->
    <!--<div class="gameOverIcon"></div>-->
    <div id="gameOverBoxTitle">闯关失败</div>
    <div class="PopupBoxBtn" id="gameOverBoxBtn">重新闯关</div>
</div>
<div class="PopupBox" id="gameSuccessBox" style="display: none;">
    <!--<div id="gameSuccessClose" class="close"><img src="https://wxopen.yiszh.com/addons/wn_ttrouges/style/img/close_btn.jpg"></div>-->
    <div id="gameSuccessBoxText">恭喜您，闯关成功</div>
    <div class="PopupBoxBtn" id="gameSuccessBoxBtn" >点击领取奖品</div>
</div>
<!-- 闯关失败 能量币复活弹窗 -->
<div class="PopupBox" id="gameResurgence" style="display: none;">
    <div id="gameOverBoxTitle">很遗憾，闯关失败</div>
    <div class="info">
        <div class="head"></div>
        <span class="nick" style="margin-top:10px;">小旭</span>
        <div class="fail-level">第<span>一</span>关，闯关失败</div>
    </div>
    <div class="PopupBoxBtn" id="gameOverBoxBtn">立即复活(<i style="font-style:normal">30</i>能量币)</div>
    <div class="jump">跳过>></div>
</div>

<!-- 是否消耗能量币复活弹窗 -->
<div class="isAlive">
    <div class="ahead">
        <span>温馨提示</span>
        <p>1、本次参与将扣除您天天爱走步中的能量币</p>
    </div>
    <div class="down">
        <span>能量币：<i>1个</i></span>
        <div class="take">立即参与</div>
    </div>
    <div class="closeDialog">X</div>
</div>

<!-- 闯关失败 观看视频免费复活弹窗 -->
<div class="PopupBox" id="freeAlive" style="display: none;">
    <div id="gameOverBoxTitle">很遗憾，闯关失败</div>
    <div class="info">
        <div class="head"></div>
        <span class="nick" style="margin-top:10px;">小旭</span>
        <div class="fail-level">第<span>一</span>关，闯关失败</div>
    </div>
    <div class="PopupBoxBtn" id="gameOverBoxBtn">免费复活</div>
    <div class="jump">再来一次>></div>
</div>
<!-- blur -->
<div class="layoutRoot " id="app" data-game_id="undefined" data-openid="">
    <div class="game" id="game" style="width: 596px; height: 938px;">
        <div class="account">
            <!--<img class="avatar" src="https://h5.lipstick.lemiao.xyz/play/trail/?h5=1&amp;unionid=orLqYwz20BqFmMvAjg97kcEykaTo">-->
            <span></span>
        </div>
        <div class="bulletsNumBox">
            <img class="bulletsNum" id="bulletsNum1" src="../addons/hc_doudou/template/mobile/img/6.png">
            <!--<img class="bulletsNum" id="bulletsNum2" src="https://h5.lipstick.lemiao.xyz/play/trail/?h5=1&amp;unionid=orLqYwz20BqFmMvAjg97kcEykaTo" style="display: none;">-->
        </div>
        <canvas style="position: relative;z-index: 3" id="gameStage" width="596" height="938"></canvas>
        <div id="bm" style="width: 100%; height: 100%;position: fixed;background-color: rgba(0,0,0,0);top: 5.3rem; transform: translate(-5%,-1%); z-index: 2">
        </div>
        <div class="tips">
            <p id="currentLevel">当前关数: <span>1</span></p>
            <p id="gameTip"></p>
        </div>

        <div class="levelbox" id="levelbox">
            <div class="level"><img id="level_1" src="../addons/hc_doudou/template/mobile/img/level_icon_1_active.png"></div>
            <div class="level"><img id="level_2" src="../addons/hc_doudou/template/mobile/img/level_icon_2.png"></div>
            <div class="level"><img id="level_3" src="../addons/hc_doudou/template/mobile/img/level_icon_3.png"></div>
        </div>
        <div id="timebox">0</div>
    </div>
</div>
<script type="text/javascript">
    var loadedMusic = false;
    var game_id=$("#app").attr("data-game_id");
    // var openid=1;

    // var h5Host = "https://wopen.wunengkeji.com/";
    document.body.addEventListener('touchmove', function (e) {
        e.preventDefault(); //阻止默认的处理方式(阻止下拉滑动的效果)
    }, {passive: false});
    var baseUrl = function GetRequest() {

       var url = location.search;  //获取url中"?"符后的字符串

        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            url = url.split("?")[1];
            strs = url.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = (strs[i].split("=")[1]);
            }
        }
        return theRequest;

    }
    var jsonParamsAlias = baseUrl();
    // var jsonParams = {
    //     "game_id" : jsonParamsAlias.gid,
    //     "game_pay" : jsonParamsAlias.pay,
    //     "product_id" : jsonParamsAlias.pid,
    //     "randomNum" : jsonParamsAlias.rand,
    //     "forecast_result": jsonParamsAlias.res,
    //     "user_id" : jsonParamsAlias.uid
    // }
    var jsonParams = {
        "game_id" : "undefined",
        "game_pay" : "132",
        "product_id" : "465",
        "randomNum" : "4541",
        "forecast_result": "1321",
        "openid" : jsonParamsAlias.userId
    }

    var openid= jsonParamsAlias.userId
    var orderId= jsonParamsAlias.orderId
    if (jsonParamsAlias.slient) {
        $('audio').prop('muted', true);
    }
    if (jsonParamsAlias.h5 && jsonParamsAlias.h5 == "1") {
        window.isH5 = true;
    }
    var cookieDelTime = new Date(Math.floor(new Date(new Date().getTime()+150000)));
    $.cookie('game_cookie', null);
    $.cookie('game_cookie', JSON.stringify(jsonParams), { expires: cookieDelTime });
    var anim = bodymovin.loadAnimation({
        wrapper: document.querySelector('#bm'),
        animType: 'svg',
        loop: false,
        autoplay: false,
        prerender: true,
        path: '../addons/hc_doudou/template/mobile/data.json'
    });
    function play(){
        anim.goToAndStop(0, true)
        anim.play()
    }
    document.addEventListener('DOMContentLoaded', function () {
        function audioAutoPlay() {
            var audio = document.getElementById('back_music');
            audio.play();
            document.addEventListener("WeixinJSBridgeReady", function () {
                audio.play();
            }, false);
        }
        audioAutoPlay();
    });

    document.addEventListener('visibilitychange', function(e) {
        function audioStop() {
            var audio = document.getElementById('back_music');
            document.hidden ? audio.pause() : audio.play();
            document.addEventListener("WeixinJSBridgeReady", function () {
                document.hidden ? audio.pause() : audio.play();
            }, false);
        }
        audioStop();
    });
</script>