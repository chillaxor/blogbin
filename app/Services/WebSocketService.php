<?php

namespace App\Services;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Illuminate\Support\Facades\Auth;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;

/**
 * @see https://wiki.swoole.com/#/start/start_ws_server
 */
class WebSocketService implements WebSocketHandlerInterface
{
    // 声明没有参数的构造函数
    private $wsTable;
    private $fd;
    public function __construct()
    {
        $this->wsTable = app('swoole')->wsTable;
    }
    // public function onHandShake(Request $request, Response $response)
    // {
    // 自定义握手：https://wiki.swoole.com/#/websocket_server?id=onhandshake
    // 成功握手之后会自动触发onOpen事件
    // }
    public function onOpen(Server $server, Request $request)
    {
        // 在触发onOpen事件之前，建立WebSocket的HTTP请求已经经过了Laravel的路由，
        // 所以Laravel的Request、Auth等信息是可读的，Session是可读写的，但仅限在onOpen事件中。
        // \Log::info('New WebSocket connection', [$request->fd, request()->all(), session()->getId(), session('xxx'), session(['yyy' => time()])]);
        // 此处抛出的异常会被上层捕获并记录到Swoole日志，开发者需要手动try/catch
        // $server->push($request->fd, json_encode(['event' => 'welcome', 'msg' => 'Welcome to LaravelS']));
        $user = Auth::user();
        $userId = $user ? $user->id : 0; // 0 表示未登录的访客用户
        // $userId = mt_rand(1000, 10000);
        // if (!$userId) {
        //     // 未登录用户直接断开连接
        //     $server->disconnect($request->fd);
        //     return;
        // }
        // $this->wsTable->set('uid:' . $userId, ['value' => $request->fd]); // 绑定uid到fd的映射
        // $this->wsTable->set('fd:' . $request->fd, ['value' => $userId]); // 绑定fd到uid的映射
        $this->fd = $request->fd;
        // $server->push('addList', '{
        //     "type": "friend" //列表类型，只支持friend和group两种
        //     ,"avatar": "a.jpg" //好友头像
        //     ,"username": "冲田杏梨" //好友昵称
        //     ,"groupid": "2" //所在的分组id
        //     ,"id": "1233333312121212" //好友id
        //     ,"sign": "本人冲田杏梨将结束AV女优的工作" //好友签名
        //   }');
        $this->wsTable->set('uid:' . $userId, ['value' => $this->fd]); // 绑定uid到fd的映射
        $this->wsTable->set('fd:' . $this->fd, ['value' => $userId]); // 绑定fd到uid的映射
        $server->push($request->fd, json_encode(['event' => 'user', 'id' => $userId]));
    }
    public function onMessage(Server $server, Frame $frame)
    {
        dump($frame->data);
        // \Log::info('Received message', [$frame->fd, $frame->data, $frame->opcode, $frame->finish]);
        // 此处抛出的异常会被上层捕获并记录到Swoole日志，开发者需要手动try/catch
        // $server->push($frame->fd, json_encode(['event' => 'message', 'msg' => date('Y-m-d H:i:s')]));
        // 广播

        /*监听事件，需要把客户端发来的json转为数组*/
        $data = json_decode($frame->data, true);
        if (!$data) return;
        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'chatMessage':
                    //处理聊天事件
                    $msg['username'] = $data['data']['mine']['username'];
                    $msg['avatar'] = $data['data']['mine']['avatar'];
                    $msg['id'] = $data['data']['mine']['id'];
                    $msg['content'] = $data['data']['mine']['content'];
                    $msg['type'] = $data['data']['to']['type'];
                    $chatMessage['event'] = 'getMessage';
                    $chatMessage['data'] = $msg;
                    foreach ($this->wsTable as $key => $row) {
                        var_dump($row['value']);
                        if (strpos($key, 'uid:') === 0 && $server->isEstablished($row['value'])) {
                            $server->push($row['value'], json_encode($chatMessage));
                        }
                    }
                    //处理单聊
                    //  if ($data['data']['to']['type'] == 'friend') {

                    //      if (isset(self::$uuid[$data['data']['to']['id']])) {
                    //          Gateway::sendToUid(self::$uuid[$data['data']['to']['id']], json_encode($chatMessage));

                    //      } else {
                    //          //处理离线消息
                    //          $noonline['type'] = 'noonline';
                    //          Gateway::sendToClient($client_id, json_encode($noonline));
                    //      }
                    //  } else {
                    //      //处理群聊
                    //      $chatMessage['content']['id'] = $data['data']['to']['id'];
                    //      Gateway::sendToAll(json_encode($chatMessage), '', $client_id);
                    //  }
                    break;
            }
        } else {
            // switch ($data['event']) {
            //     case 'init':

            //         break;
            //     default:
            //         break;
            // }
        }
    }
    public function onClose(Server $server, $fd, $reactorId)
    {
        // 此处抛出的异常会被上层捕获并记录到Swoole日志，开发者需要手动try/catch
        $uid = $this->wsTable->get('fd:' . $fd);
        if ($uid !== false) {
            $this->wsTable->del('uid:' . $uid['value']); // 解绑uid映射
        }
        $this->wsTable->del('fd:' . $fd); // 解绑fd映射
        $server->push($fd, "Goodbye #{$fd}");
    }
}
