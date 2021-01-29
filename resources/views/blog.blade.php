<html>

<head>
    <title>{{ $blog->title }}</title>
    <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
    <!--采用模块化方式-->
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
    <!-- jQuery (necessary JavaScript plugins) -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>

<body>
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo">Bin</div>
            <!-- 头部区域（可配合layui已有的水平导航） -->
            <ul class="layui-nav layui-layout-left">
                <!-- <li class="layui-nav-item"><a href="">控制台</a></li>
      <li class="layui-nav-item"><a href="">商品管理</a></li>
      <li class="layui-nav-item"><a href="">用户</a></li>
      <li class="layui-nav-item"> -->
                <!-- <a href="javascript:;">其它系统</a> -->
                <!-- <dl class="layui-nav-child"> -->
                <!-- <dd><a href="">邮件管理</a></dd>
          <dd><a href="">消息管理</a></dd>
          <dd><a href="">授权管理</a></dd> -->
                <!-- </dl> -->
                <!-- </li> -->
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
                        进制
                    </a>
                    <!-- <dl class="layui-nav-child"> -->
                    <!-- <dd><a href="">基本资料</a></dd> -->
                    <!-- <dd><a href="">安全设置</a></dd> -->
                    <!-- </dl> -->
                </li>
                <!-- <li class="layui-nav-item"><a href="">退了</a></li> -->
            </ul>
        </div>

        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="" href="{{asset('')}}">文章列表</a>
                        <!-- <dl class="layui-nav-child">
            <dd><a href="{{asset('blogs/1')}}">一</a></dd>
            <dd><a href="{{asset('blogs/2')}}">二</a></dd>
            <dd><a href="{{asset('blogs/3')}}">三</a></dd> -->
                        <!-- <dd><a href="">超链接</a></dd> -->
                        <!-- </dl> -->
                    </li>
                    <!-- <li class="layui-nav-item">
          <a href="javascript:;">解决方案</a>
          <dl class="layui-nav-child">
            <dd><a href="javascript:;">列表一</a></dd>
            <dd><a href="javascript:;">列表二</a></dd>
            <dd><a href="">超链接</a></dd>
          </dl>
        </li>
        <li class="layui-nav-item"><a href="">云市场</a></li>
        <li class="layui-nav-item"><a href="">发布商品</a></li> -->
                </ul>
            </div>
        </div>

        <div class="layui-body">
            <!-- 内容主体区域 -->
            <div style="padding: 15px;">内容主体区域</div>
            <div class="container">
                <h1>{{ $blog->title }}</h1>
                <h5>{{ $blog->created_at }}</h5>

                <!-- <img src="{{ URL::asset($blog->album_url) }}" id="img"/> -->
                <hr>
                {!! $blog->content !!}
                <hr>
                <button class="btn btn-primary" onclick="history.go(-1)">
                    « Back
                </button>
            </div>
        </div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © layui.com - 底部固定区域
    </div>
    </div>
    <script>
        //JavaScript代码区域
        // layui.use('element', function(){
        //   var element = layui.element;

        // });
        // layui.use('layim', function(layim){
        //   //先来个客服模式压压精
        //   layim.config({
        //     brief: true //是否简约模式（如果true则不显示主面板）
        //   }).chat({
        //     name: '客服姐姐'
        //     ,type: 'friend'
        //     ,avatar: 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1'
        //     ,id: -2
        //   });
        // });
        layui.use('layim', function(layim) {
            var socket = new WebSocket('ws://nova8.com//ws');
            console.log(socket.readyState);
            //== WebSocket.OPEN
            //   socket.send('Hi Server, I am LayIM!');
            //连接成功时触发
            socket.onopen = function() {
                // socket.send(JSON.stringify({
                //     event: "init",
                //     uid: 1
                // }));
            };

            let user_id;
            socket.onmessage = function(res) {
                res = JSON.parse(res.data);
                console.log(res);
                if (res.event == 'user') {
                    user_id = res.id;
                    layim.config({
                        init: {

                            url: '/getuser' //接口地址（返回的数据格式见下文）
                                ,
                            type: 'get' //默认get，一般可不填
                                ,
                            data: {
                                id: user_id
                            }, //额外参数

                        } //获取主面板列表信息，下文会做进一步介绍

                        //获取群员接口（返回的数据格式见下文）
                        ,
                        members: {
                            url: '/get' //接口地址（返回的数据格式见下文）
                                ,
                            type: 'get' //默认get，一般可不填
                                ,
                            data: {} //额外参数
                        }

                        //上传图片接口（返回的数据格式见下文），若不开启图片上传，剔除该项即可
                        ,uploadImage: {
                          url: '' //接口地址
                          ,type: 'post' //默认post
                        }

                        //上传文件接口（返回的数据格式见下文），若不开启文件上传，剔除该项即可
                        ,uploadFile: {
                          url: '' //接口地址
                          ,type: 'post' //默认post
                        }
                        //扩展工具栏，下文会做进一步介绍（如果无需扩展，剔除该项即可）
                        ,tool: [{
                          alias: 'code' //工具别名
                          ,title: '代码' //工具名称
                          ,icon: '&#xe64e;' //工具图标，参考图标文档
                        }]

                        ,msgbox: layui.cache.dir + 'css/modules/layim/html/msgbox.html' //消息盒子页面地址，若不开启，剔除该项即可
                        ,find: layui.cache.dir + 'css/modules/layim/html/find.html' //发现页面地址，若不开启，剔除该项即可
                        ,chatLog: layui.cache.dir + 'css/modules/layim/html/chatlog.html' //聊天记录页面地址，若不开启，剔除该项即可

                    });
                } else if ('getMessage' == res.event) {
                    console.log(res.data);
                    // layim.getMessage(res.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
                    layim.getMessage({
                        username: res.data.username //消息来源用户名
                            ,
                        avatar: res.data.avatar //消息来源用户头像
                            ,
                        id: res.data.id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                            ,
                        type: res.data.type //聊天窗口来源类型，从发送消息传递的to里面获取
                            ,
                        content: res.data.content //消息内容
                            ,
                        cid: 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
                            ,
                        mine: false //是否我发送的消息，如果为true，则会显示在右方
                            ,
                        fromid: "100000" //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                            ,
                        timestamp: (new Date()).getTime() //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000}); //res.data即你发送消息传递的数据（阅读：监听发送的消息）

                    });
                }
                //基础配置
                socket.onclose = function(e) {
                    console.log('websocket 断开: ' + e.code + ' ' + e.reason + ' ' + e.wasClean);
                };
                var interval_timer = null; //计时器
                var timer_count = 0;
                // 开启定时器
                init_start_timer();
                /**
                 * 设置一个 30秒的轮询监听方法，避免页面关闭
                 */
                function init_start_timer() {
                    //重置计数器
                    timer_count = 0;
                    if (interval_timer != null) {
                        clearInterval(interval_timer);
                        interval_timer = null;
                    }
                    interval_timer = setInterval(function() {
                        myTimer()
                    }, 30000);
                }
                /**
                 *定时器具体实现方法
                 */
                function myTimer() {
                    //TODO 如果超过半小时没有交互，则关闭计时器
                    if (timer_count >= 1800) {
                        clearInterval(interval_timer);
                    } else {
                        timer_count += 30;
                        var online = '{"type":"timer","from_id":"' + 342 + '","to_id":"' + 432 + '"}';
                        socket.send(online);
                        console.log('timer_count', timer_count);
                    }
                }
            };

            // layim.on('ready', function(res) {
            //     //监听添加列表的socket事件，假设你服务端emit的事件名为：addList
            //     socket.onmessage = function(res) {
            //         layim.addList({
            //             type: 'friend' //列表类型，只支持friend和group两种
            //                 ,
            //             avatar: "{{ URL::asset($blog->album_url) }}" //好友头像
            //                 ,
            //             username: '冲田杏梨' //好友昵称
            //                 ,
            //             groupid: 1 //所在的分组id
            //                 ,
            //             id: "1233333312121212" //好友id
            //                 ,
            //             sign: "本人冲田杏梨将结束AV女优的工作" //好友签名
            //         })
            //         if (res.emit === 'addList') {
            //             layim.addList(res.data); //如果是在iframe页，如LayIM设定的add面板，则为 parent.layui.layim.addList(data);
            //         }
            //     };
            // });
            // layim.add({
            //     type: 'friend' //friend：申请加好友、group：申请加群
            //         ,
            //     username: 'xxx' //好友昵称，若申请加群，参数为：groupname
            //         ,
            //     avatar: 'a.jpg' //头像
            //         ,
            //     submit: function(group, remark, index) { //一般在此执行Ajax和WS，以通知对方
            //         // console.log(group); //获取选择的好友分组ID，若为添加群，则不返回值
            //         // console.log(remark); //获取附加信息
            //         layer.close(index); //关闭改面板
            //     }
            // });
            // layim.setFriendGroup({
            //   type: 'friend'
            //   ,username: 'xxx' //好友昵称，若申请加群，参数为：groupname
            //   ,avatar: 'a.jpg' //头像
            //   ,group: layim.cache().friend //获取好友列表数据
            //   ,submit: function(group, index){
            //     //一般在此执行Ajax和WS，以通知对方已经同意申请
            //     //……

            //     //同意后，将好友追加到主面板
            //     layim.addList({
            //   type: 'friend' //列表类型，只支持friend和group两种
            //   ,avatar: "{{ URL::asset($blog->album_url) }}" //好友头像
            //   ,username: '冲田' //好友昵称
            //   ,groupid: 1 //所在的分组id
            //   ,id: "123333321212" //好友id
            //   ,sign: "本人冲田杏梨将结束AV女优的工作" //好友签名
            // }); //见下文
            //   }
            // });
            layim.on('sendMessage', function(res) {
                socket.send(JSON.stringify({
                    type: 'chatMessage' //随便定义，用于在服务端区分消息类型
                        ,
                    data: res
                }));

            });
        });
    </script>

</body>

</html>
