<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="技术博客，PHP，Redis，软件开发，招聘" name="Keywords"/>
        <meta content="高质量的技术文章聚合" name="Description"/>
        <title>
            Rocket
        </title>
        {% block head %}
        <link href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.css" rel="stylesheet"/>
        <link href="/css/style.css" rel="stylesheet"/>
        {% endblock %}
    </head>
    <body>
        <header class="general">
            {% block header %}
            <a class="logo-text" href="/">
                Rocket
            </a>
            <div class="top-bar">
                <span>
                    <a href="/" style="color:#efefef;">
                        我思故我在
                    </a>
                </span>
                <span class="links">
                    <a href="/site/list">
                        网站列表
                    </a>
                </span>
            </div>
            {% endblock %}
        </header>
        <div class="wrap">
            <main class="container">
                {% block content %}{{ content() }}{% endblock %}
            </main>
        </div>
        <footer>
            <div class="box vertical justify content-wrapper add-class">
                <ul class="box vertical">
                    <li style="padding-right: 27px;">
                        ©我思故我在 版权所有
                    </li>
                    <li style="padding-right: 1em;">
                        <a href="/about" target="_blank" title="关于我们">
                            关于我们
                        </a>
                    </li>
                </ul>
            </div>
        </footer>
        {% block bottom %}        
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js">
        </script>
        <script src="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js">
        </script>
        {% endblock %}
    </body>
</html>
