<?php

namespace App\Http\Controllers\Port;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Model\UserModel;

class PortController extends Controller
{

    //注册页面
    public function reg(){
        $recurl=$_GET["recurl"] ?? env("SHOP_URL");
        $data=[
            "recurl"=>$recurl
        ];
        return view("user.reg",$data);
    }
    public function doreg(Request $request){
        $pass=$request->input("pwd");
        $pwd=password_hash($pass,PASSWORD_BCRYPT);
        $recurl=urlencode($request->input("recurl") ?? env("SHOP_URL"));
        $data=[
            'name'=>$request->input('uname'),
            'pwd'=>$pwd,
            'age'=>$request->input('age'),
            'email'=>$request->input('email'),
            'c_time'=>time()
        ];
        $where=[
            'name'=>$request->input('uname'),
        ];
        $res=UserModel::where($where)->first();
        if($res){
            echo "账号已存在不能注册";
            header("refresh:1,/reg");
        }else{
            $add=UserModel::insertGetId($data);
            setcookie('id',$add,time()+86400,'/','shops.com',false,true);
            if($add){
                echo "1";
                header("refresh:1,/login?recurl=".$recurl);
            }
        }
    }
    //登录页面
    public function login(){
        $recurl=$_GET["recurl"] ?? env("SHOP_URL");
        $data=[
            "recurl"=>$recurl
        ];
        return view("user.login",$data);
    }
    public function dologin(Request $request){
        $name=$request->input('uname');
        $pwd=$request->input('pwd');
        $recurl=$request->input("recurl") ?? env("SHOP_URL");
        $where=[
            'name'=>$name
        ];
        $res=UserModel::where($where)->first();
        if($res){
            if(password_verify($pwd,$res->pwd)){
                $token=substr(md5(time().mt_rand(1,9999)),10,10);
                setcookie('token',$token,time()+86400,'/','shop.com',false,true);
                setcookie('id',$res->id,time()+86400,'/','shop.com',false,true);
                //var_dump($_COOKIE);die;
                //存redis
                $redis_key="h:u:s".$res->id;
                Redis::set($redis_key,$token);
                Redis::expire($redis_key,86400);
                echo "登录成功";
                header("refresh:1,$recurl");
            }else{
                echo "账号或密码错误";
                header("refresh:1,/login");
            }
        }else{
            echo "账户不存在";
            header("refresh:1,/login");
        }
    }
}
