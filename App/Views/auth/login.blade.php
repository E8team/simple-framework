<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
</head>
<body>
<form action="{{ url('Login/do_login') }}" method="post">
    用户名：<input type="text" name="username"><br/>
    密　码：<input type="password" name="password"><br/>
    <button type="submit">登录</button>
</form>
</body>
</html>