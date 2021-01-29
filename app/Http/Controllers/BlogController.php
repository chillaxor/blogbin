<?php
/*
 * @Author: jinzhi
 * @email: <chenxinbin@linghit.com>
 * @Date: 2021-01-07 14:07:14
 * @Description: Description
 */

namespace App\Http\Controllers;

use App\Blog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class BlogController extends Controller
{
    public function __construct()
    {
        if (empty(Auth::check())) {
            return Redirect::guest('/');
        }
    }
    public function index()
    {
        $blogs = Blog::get();
        return view('blogs', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        return view('blog', compact('blog'));
    }
    public function getUser(Request $request)
    {
        $user = User::with(['relations.friends', 'relations.group'])
            ->where('id', $request->input('id'))
            ->first();
        $result['mine'] = [
            'username' => $user['name'],
            'id' => $user['id'],
            'status' => 'online',
            'avatar' => $user['avatar'],
            'sign' => $user['sign'],
        ];
        $result['friend'] = [];
        $friends = $user['relations']->groupby('group_id');
        // $result['friends'] = $friends;
        $list_group = [];
        $result['group'] = [];
        foreach ($friends as $kk=> $vv) {
            $list = [];
            foreach ($vv as $v) {
                $list_group = [
                    'groupname' => $v['group'][0]['name'],
                    'id' => $v['group'][0]['id']
                ];
                $fr = [
                    'username' => $v['friends']['name'],
                    'id' => $v['friends']['id'],
                    'status' => 'online',
                    'avatar' => $v['friends']['avatar'],
                    'sign' => $v['friends']['sign']
                ];
                array_push($list, $fr);
                $list_group['list'] = $list;
            }
            array_push($result['friend'], $list_group);
            array_push($result['group'], [
                'groupname' => $vv[0]['group'][0]['name'],
                'id' => $kk,
                'avatar' =>  $vv[0]['group'][0]['avatar'],
            ]);
        }
        return ['code' => 0, 'msg' => '', 'data' => $result];
        if ($request->input('id') == 1) {
            $json = '{"code":0,"msg":"","data":{"mine":{"username":"纸飞机1","id":"1","status":"online","sign":"在深邃的编码世界，做一枚轻盈的纸飞机","avatar":"https://img-fe.tengzhihh.com/image/57d13982e29595-430x430.jpg"},"friend":[{"groupname":"前端码屌","id":1,"list":[{"username":"纸飞机1","id":"2","avatar":"https://img-fe.tengzhihh.com/image/57d13982e29595-430x430.jpg","sign":"这些都是测试数据，实际使用请严格按照该格式返回","status":"online"}]}],"group":[{"groupname":"前端群","id":"101","avatar":"https://img-fe.tengzhihh.com/image/57d13982e29595-430x430.jpg"}]}}';
        } else {
            $json = '{"code":0,"msg":"","data":{"mine":{"username":"纸飞机","id":"2","status":"online","sign":"在深邃的编码世界，做一枚轻盈的纸飞机","avatar":"https://img-fe.tengzhihh.com/image/57d13982e29595-430x430.jpg"},"friend":[{"groupname":"前端码屌","id":1,"list":[{"username":"纸飞机","id":"1","avatar":"https://img-fe.tengzhihh.com/image/57d13982e29595-430x430.jpg","sign":"这些都是测试数据，实际使用请严格按照该格式返回","status":"online"}]}],"group":[{"groupname":"前端群","id":"101","avatar":"https://img-fe.tengzhihh.com/image/57d13982e29595-430x430.jpg"}]}}';
        }
        return $json;
    }
    public function login(Request $request)
    {
        if (Auth::attempt(array('email' => $request->input('title'), 'password' => $request->input('password')))) {
            return Redirect::intended('/blogs');
        }
        $_ws['title'] = $request->input('title');
        $_ws['password'] = $request->input('password');
        $userId = $_ws['id'] = 1;
        app('swoole')->wsTable->set('uid:' . $userId, ['value' => $request->fd]); // 绑定uid到fd的映射
        app('swoole')->wsTable->set('fd:' . $request->fd, ['value' => $userId]); // 绑定fd到uid的映射
        return redirect("/index");
    }
}
