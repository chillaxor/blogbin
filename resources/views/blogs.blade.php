<!DOCTYPE html>
<html>
<head>
    <title>{{ config('blog.title') }}</title>
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
      <li class="layui-nav-item">
        <a href="javascript:;">其它系统</a>
        <dl class="layui-nav-child">
          <dd><a href="">邮件管理</a></dd>
          <dd><a href="">消息管理</a></dd>
          <dd><a href="">授权管理</a></dd>
        </dl>
      </li> -->
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">
          <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
          进制
        </a>
        <!-- <dl class="layui-nav-child">
          <dd><a href="">基本资料</a></dd>
          <dd><a href="">安全设置</a></dd>
        </dl> -->
      </li>
      <!-- <li class="layui-nav-item"><a href="">退了</a></li> -->
    </ul>
  </div>

  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test">
        <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="{{asset('')}}">文章列表</a>
          <!-- <dl class="layui-nav-child"> -->
            <!-- <dd><a href="{{asset('blogs/1')}}">一</a></dd>
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
    <h1>{{ config('blog.title') }}</h1>
    <hr>
    <ul>
        @foreach($blogs as $blog)
            <li>
                <a href="/blogs/{{ $blog->id }}">{{ $blog->title }}</a>
                <em>{{ $blog->created_at }}</em>
                <!-- <img src ="{{URL::asset($blog->album_url)}}"> -->
                <p>{{ $blog->excerpt }}</p>
            </li>
        @endforeach
    </ul>
</div>
  </div>

  <div class="layui-footer">
    <!-- 底部固定区域 -->
    © layui.com - 底部固定区域
  </div>
</div>
<script src="../src/layui.js"></script>
<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;

});
</script>


</html>
