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

    //手机端登录页面
    public function alogin(){
        $recurl=$_GET["recurl"] ?? env("SHOP_URL");
        $data=[
            "recurl"=>$recurl
        ];
        return view("user.login",$data);
    }
    public function apilogin(Request $request){
        $name =$request->input('uname');
        $pwd=$request->input('pwd');
        $recur=$request->input('recurl') ?? env('SHOP_URL');
        $recurl=urldecode($recur);
        $data=[
            'name'=>$name
        ];
        $info=UserModel::where($data)->first();
        $pwd2=password_verify($pwd,$info->pwd);
        if(empty($info)){
            echo 'Login failed';
        }else if($pwd2===false){
            echo 'Login failed';
        }else {
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('uid',$info->uid,time()+86400,'/','shop.com',false,true);
            setcookie('token',$token,time()+86400,'/','shop.com',false,true);
            $redis_key="h:u:s".$info->id;
            Redis::set($redis_key,$token);
            Redis::expire($redis_key,86400);
            //echo '1';
            echo 'Login successful';
            header("refresh:1;$recurl");
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
        //var_dump($name);die;
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
                $response=[
                    "error"=>0,
                    "msg"=>'success',
                    "token"=>$token
                ];
                $response_str=json_encode($response);
                return $response_str;

            }else{
                $response=[
                    "msg"=>'fail',
                ];
                $response_str=json_encode($response);
                return $response_str;
            }
        }else{
            $response=[
                "msg"=>'fail',
            ];
            $response_str=json_encode($response);
            return $response_str;
        }

    }

}
