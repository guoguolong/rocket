<!DOCTYPE html>
<html>
<head>
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
        {% block header %}LOGO{% endblock %}
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
