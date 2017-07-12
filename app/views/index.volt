<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <meta content="IE=edge" http-equiv="X-UA-Compatible">
                <meta content="width=device-width, initial-scale=1" name="viewport">
                    <meta content="Coding、Git、代码托管、WebIDE、 冒泡、多人协作、开发协作、团队协作、开发管理、开发流程、软件开发管理、周期管理、SVN" name="Keywords">
                        <meta content="Coding.net 是一个面向开发者的云端开发平台，提供 git/svn 代码托管，代码质量分析，在线 WebIDE，项目管理，开发协作，冒泡社区，提供个人和企业公有云及企业私有云的服务。" name="Description">
                            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
                            <title>
                                Rocket
                            </title>
                            {% block head %}
                            <link href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.css" rel="stylesheet">
                                <link href="/css/style.css" rel="stylesheet">
                                    {% endblock %}
                                </link>
                            </link>
                        </meta>
                    </meta>
                </meta>
            </meta>
        </meta>
    </head>
    <body>
        <div id="header">
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
        </div>
        <div class="container">
            {% block content %}{{ content() }}{% endblock %}
        </div>
        <div id="footer">
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
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js">
        </script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js">
        </script>
    </body>
</html>
