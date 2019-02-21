<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - <?php echo $_page_title;?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $_page_btn_link?>"><?php echo $_page_btn_name?></a>
    </span>
    <span class="action-span1"><a href="<?php echo U('lst')?>">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title?> </span>
    <div style="clear:both"></div>
</h1>

<!--内容-->




<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <?php foreach($data as $attr_name=>$attr):?>
                <th><?php echo ($attr_name); ?></th>
                <?php endforeach?>

                <th>库存量</th>
                <th>操作</th>
            </tr>

            <?php if(!$gn_list):?>
            <tr class="tron">
                <?php foreach($data as $attr):?>
                <td align="center">
                    <select name="attr_ids[]">
                        <option>请选择...</option>
                        <?php foreach($attr as $v): $_attr = explode(',',$gn['goods_attr_id']); if(in_array($v['id'],$_attr)){ $select = 'selected=selected'; }else{ $select = ''; } ?>
                        <option value="<?php echo ($v["id"]); ?>" <?php echo ($select); ?>><?php echo ($v["attr_value"]); ?></option>
                        <?php endforeach?>
                    </select>
                </td>
                <?php endforeach?>
                <td align="center">
                    <input type="text" name="goods_num[]" value="<?php echo ($gn["goods_number"]); ?>">
                </td>
                <td align="center">
                    <input type="button" value="+" onclick="addNewTr(this)">
                </td>

            </tr>
            <?php endif?>

            <?php foreach($gn_list as $key=>$gn):?>
            <tr class="tron">
                <?php foreach($data as $attr):?>
                <td align="center">
                    <select name="attr_ids[]">
                        <option>请选择...</option>
                    <?php foreach($attr as $v): $_attr = explode(',',$gn['goods_attr_id']); if(in_array($v['id'],$_attr)){ $select = 'selected=selected'; }else{ $select = ''; } ?>
                        <option value="<?php echo ($v["id"]); ?>" <?php echo ($select); ?>><?php echo ($v["attr_value"]); ?></option>
                    <?php endforeach?>
                    </select>
                </td>
                <?php endforeach?>
                <td align="center">
                    <input type="text" name="goods_num[]" value="<?php echo ($gn["goods_number"]); ?>">
                </td>
                <td align="center">
                    <input type="button" value="<?php echo $key==0?'+':'-';?>" onclick="addNewTr(this)">
                </td>

            </tr>
            <?php endforeach?>

            <tr id="btn_submit">
                <?php $count=count($data)+2?>
                <td colspan="<?php echo ($count); ?>" align="center"><input type="submit" value="提交"></td>
            </tr>

        </table>


    </div>
</form>


<script src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<script>
    function addNewTr(ele){
        var tr = $(ele).parent().parent();
        var opt = $(ele).val();
        if(opt=="+"){
            var newtr = tr.clone();
            newtr.find(":button").val('-')

            $("#btn_submit").before(newtr);
        }else {
            tr.remove()
        }

    }





</script>




</html>


<div id="footer">
    共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>