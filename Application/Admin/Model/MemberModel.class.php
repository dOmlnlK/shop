<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model
{
	protected $insertFields = array('username','password','cpassword','check_code','must_click');
	protected $updateFields = array('id','username','password','cpassword');
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
        array('password', '6,20', '密码的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('cpassword', 'password', '两次密码输入不一致!', 1, 'confirm', 3),
        array('check_code', 'require', '验证码不能为空！', 1),
        array('check_code', 'check_verify', '验证码不正确！', 1,'callback')
	);

	//创建为登录表单验证的验证规则
    public  $_login_validate = array(
        array('username', 'require', '用户名不能为空！', 1),
        array('password', 'require', '密码不能为空！', 1),
        array('check_code', 'require', '验证码不能为空！', 1),
        array('check_code', 'check_verify', '验证码不正确！', 1,'callback'),
    );
    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    protected function _before_insert(&$data, $option)
    {
        $data['password'] = md5($data['password']);
    }


    public function login(){
        //从模型中取出账号密码

        $username = I('post.username');
        $password = I('post.password');
        //判断是否存在该用户
        $member = $this->where(array('username'=>array('eq',$username)))->find();
        //获取当前会员级别id
        $ml_model = D('Admin/member_level');
        $jifen = $member['jifen'];
        $ml = $ml_model->field('id')->where(array(
            'jifen_bottom'=>array('elt',$jifen),
            'jifen_top'=>array('egt',$jifen)
        ))->find();


        if($member){
            if($member['password'] == md5($password)){
                //说明登录成功，设置session
                session('mid',$member['id']);
                session('member',$member['username']);
                session('level_id',$ml['id']);

                //将cookie中的购物车迁移到数据库
                D('Home/cart')->moveCart();
                return True;

            }
            else{
                $this->error = '密码输入错误！';
                return false;
            }

        }else{
            $this->error = '用户名不存在！';
            return false;

        }



    }




}

