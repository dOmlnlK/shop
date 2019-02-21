<?php
namespace Home\Model;
use Think\Model;
class CommentReplyModel extends Model
{
	protected $insertFields = array('comment_id','content');

	protected $_validate = array(
		array('comment_id', 'require', '被回复的评论id不能为空！', 1),
        array('content', '1,200', '评论必须在1-200个字符之间！', 1, 'length', 3),
	);


    protected function _before_insert(&$data, $options)
    {
        //判断是否登录
        if(!session('member')){

            $this->error = '请先登录在进行评论！';
            return false;
        }

        $data["member_id"] = session('mid');
        $data['addtime'] = date('Y-m-d H:i:s');


    }

}

