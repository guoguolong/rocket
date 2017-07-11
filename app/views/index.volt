<!DOCTYPE html>
<html>
<head>
<style>
.logo-text {
    float: left;
    display: block;
    width:40px;
    height: 40px;
    background: url(/img/ico-rocket.png) no-repeat left center;
    background-size: auto 40px;
    text-indent: 110%;
    white-space: nowrap;
    overflow: hidden;
    text-transform: capitalize;
}

.top-bar {
    display: flex;
    padding-top: 8px;
    justify-content: space-between;
}

.top-bar span.links a {
    color: #FFFFFF;
    font-size: 0.8rem;
}
</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Rocket</title>
    {% block head %}
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.css">
    <link rel="stylesheet" href="/css/style.css">
    {% endblock %}        
</head>
<body>
    <div id='header'>
        {% block header %}
        <a href="/" class="logo-text">Rocket</a>
        <div class="top-bar">
            <span> <a href="/" style="color:#efefef;">我思故我在</a></span>
            <span class="links"> <a href="/site/list">网站列表</a></span>
        </div>
        {% endblock %}
    </div>        
    <div class="container">
        {% block content %}{{ content() }}{% endblock %}
    </div>
    <div id='footer'>
        <div class="box vertical justify content-wrapper add-class">
            <ul class="box vertical">
                <li style="padding-right: 27px;">©我思故我在 版权所有</li>
                <li style="padding-right: 1em;"><a href="/about" target="_blank" title="关于我们">关于我们</a></li>
            </ul>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
</body>
</html>
