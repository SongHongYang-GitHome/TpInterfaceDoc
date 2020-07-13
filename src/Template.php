<?php
require_once "../vendor/autoload.php";
$tphp = new \TpInterfaceDoc\Scan();
$nodes = $tphp->doScan();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>API在线文档</title>
    <link href="source/api.css?version=111" rel="stylesheet" type="text/css" />
    <script language="javascript" src="source/jquery.min.js"></script>
    <script language="javascript" src="source/jquery.dimensions.js"></script>
</head>
<body>
<div class="tit">
    <div id="titcont">
        API在线文档<span class="sma"></span>
    </div>
</div>
<div id="cont">

    <div class='fun'>


        <?php foreach ($nodes as $node) { ?>
        <div class='lineface le'>#. <?php echo $node['title']; ?></div>
        <?php foreach ($node['interfaces'] as $interface) { ?>
        <a name="<?php echo $interface['title']; ?>"></a>
        <span class='le'><em><?php echo $interface['title']; ?></em> <b>描述:<?php echo $interface['desc']; ?></b> </span>
        <span class='ri'>方式:<em><?php echo $interface['method']; ?></em></span>
        <span class='ri'>URL:<em> <a><?php echo $interface['addr']; ?></a> </em></span>

        <div class='says'>请求说明：
            <?php if(isset($interface['request']) && is_array($interface['request']) && !empty($interface['request'])) { ?>
            <table class="table" style="width: 100%;">
                <thead><tr><th>参数名称</th><th>参数类型</th><th>是否必传</th><th>参数说明</th></tr></thead>
                <?php foreach ($interface['request'] as $request) { ?>
                <tr><td><?php echo $request[0]; ?></td><td><?php echo $request[1]; ?></td><td><?php echo $request[2]; ?></td><td><?php echo $request[3]; ?></td></tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                无
            <?php } ?>
        </div>

        <div class='says'>响应说明：
            <?php if(isset($interface['response']) && is_array($interface['response']) && !empty($interface['response'])) { ?>
            <table class="table" style="width: 100%;">
                <thead><tr><th>参数名称</th><th>参数类型</th><th>是否必传</th><th>参数说明</th></tr></thead>
                <?php foreach ($interface['response'] as $response) { ?>
                    <tr><td><?php echo $response[0]; ?></td><td><?php echo $response[1]; ?></td><td><?php echo $response[2]; ?></td><td><?php echo $response[3]; ?></td></tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                无
            <?php } ?>
        </div>

        <?php if(isset($interface['responsetpl']) && !empty($interface['responsetpl'])) { ?>
        <div class='says'>响应示例：
<pre class="intersays">
<?php echo $interface['responsetpl']; ?>
</pre>
        </div>
                <?php } ?>
         <?php } ?>

        <?php } ?>
    </div>

</div>


<!--浮动接口导航栏-->
<div id="floatMenu">
    <ul class="menu"></ul>
</div>

<script language="javascript">
    var name = "#floatMenu";
    var menuYloc = null;
    $(document).ready(function(){
        $(".le > em").each(function(index, element){
            $(".menu").append(" <li><a href='#"+ $(this).text() +"'>"+ $(this).text()+"</a></li>");
        });
        menuYloc = parseInt($(name).css("top").substring(0,$(name).css("top").indexOf("px")))
        $(window).scroll(function () {
            offset = menuYloc+$(document).scrollTop()+"px";
            $(name).animate({top:offset},{duration:500,queue:false});
        });
    });

</script>
</body>
</html>
