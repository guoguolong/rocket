{% block head %}
<link href="/css/hexo/apollo.css" rel="stylesheet"/>
{% endblock %}

{% block content %}
<div class="post">
<article class="post-block">
    <h1 class="post-title">
        # {{ article.title }}
    </h1>
    <div class="post-info">{{ article.published_at }}</div>
    <div class="post-content">
        {{ article.content }}
    </div>
    <div class="refer">
        <span>
            <a target="_blank" href="{{ article.link }}">
                原文链接
            </a>
        </span>
        <span>
            <a href="/site/detail/{{ article.site_id }}">
                {{ article.site.author }}
            </a>
        </span> 
        <span><a href="/article/list/{{ article.site_id }}">所有文章</a>
        </span>
    </div>
    <div>
    </div>
</article>
</div>
{% endblock %}
