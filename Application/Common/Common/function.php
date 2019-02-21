<?php
// 有选择性的过滤XSS --》 说明：性能非常低-》尽量少用
function removeXSS($data)
{
	require_once './HtmlPurifier/HTMLPurifier.auto.php';
	$_clean_xss_config = HTMLPurifier_Config::createDefault();
	$_clean_xss_config->set('Core.Encoding', 'UTF-8');
	// 设置保留的标签
	$_clean_xss_config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
	$_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
	$_clean_xss_config->set('HTML.TargetBlank', TRUE);
	$_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
	// 执行过滤
	return $_clean_xss_obj->purify($data);
}

//模板中显示图片
function showPic($url,$width='',$height=''){
    if($width){
        $width = "width=$width";
    }elseif ($height){
        $height = "height=$height";
    }

    $ic = C('IMAGE_CONFIG');
    $viewPath = $ic['viewPath'];
    $img = "<img $width $height src=$viewPath{$url}>";
    echo $img;

}

function deleteImage($image = array())
{
    $savePath = C('IMAGE_CONFIG');
    foreach ($image as $v)
    {
        unlink($savePath['rootPath'] . $v);
    }
}

function uploadOne($imgName, $dirName, $thumb = array())
{
    // 上传LOGO
    if(isset($_FILES[$imgName]) && $_FILES[$imgName]['error'] == 0)
    {
        $ic = C('IMAGE_CONFIG');
        $upload = new \Think\Upload(array(
            'rootPath' => $ic['rootPath'],
            'maxSize' => $ic['maxSize'],
            'exts' => $ic['exts'],
        ));// 实例化上传类
        $upload->savePath = $dirName . '/'; // 图片二级目录的名称
        // 上传文件
        // 上传时指定一个要上传的图片的名称，否则会把表单中所有的图片都处理，之后再想其他图片时就再找不到图片了
        $info   =   $upload->upload(array($imgName=>$_FILES[$imgName]));
        if(!$info)
        {
            return array(
                'ok' => 0,
                'error' => $upload->getError(),
            );
        }
        else
        {
            $ret['ok'] = 1;
            $ret['images'][0] = $logoName = $info[$imgName]['savepath'] . $info[$imgName]['savename'];
            // 判断是否生成缩略图
            if($thumb)
            {
                $image = new \Think\Image();
                // 循环生成缩略图
                foreach ($thumb as $k => $v)
                {
                    $ret['images'][$k+1] = $info[$imgName]['savepath'] . 'thumb_'.$k.'_' .$info[$imgName]['savename'];
                    // 打开要处理的图片
                    $image->open($ic['rootPath'].$logoName);
                    $image->thumb($v[0], $v[1])->save($ic['rootPath'].$ret['images'][$k+1]);
                }
            }
            return $ret;
        }
    }
}


//在模板中创建一个下拉选择框
function buildSelect($modelName,$selectName,$optionName,$optionVal,$selectVal=''){

    $model = D($modelName);
    $data = $model->field("$optionName,$optionVal")->select();

    $select = "<select name=$selectName><option value=''>请选择</option>";

    foreach ($data as $v){
        $select .= '<option value="'.$v[$optionVal].'"';
        if ($selectVal==$v[$optionVal]){
            $select .= " selected=selected";
        }
        $select .= ">".$v[$optionName]."</option>";
    }
    $select .= "</select>";
    echo $select;

}

/**
 * 为一个定单生成支付宝支付按钮
 */
function makeAlipayBtn($orderId, $btnName = '去支付宝支付')
{
    return require('./alipay/alipayapi.php');
}

function filterUrl($param)
{
    // 先取出当前的URL地址
    $url = $_SERVER['PHP_SELF'];
    // 正则去掉某个参数
    $re = "/\/$param\/[^\/]+/";
    return preg_replace($re, '', $url);
}