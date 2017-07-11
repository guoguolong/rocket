{% block content %}
<article>
    <div style="display:flex;justify-content:space-between;">
        <span class="h3">
            <a href="/article/detail/{{ article.article_id }}">
                # {{ article.title }}
            </a>
        </span>
        <span>
            发布于: {{ article.published_at }}
        </span>
        <span>
            源自:
            <a href="/article/list/{{ article.site_id }}">
                {{ article.site.title }}
            </a>
            | By
            <a href="/site/detail/{{ article.site_id }}">
                {{ article.site.author }}
            </a>
        </span>
    </div>
    <div class="content">
        {{ article.content }}
    </div>
    <div style="display:flex; background: #eee;padding: 6px 0px;">
        <span>
            原文链接:
            <a targe="_blank" href="{{ article.link }}">
                {{ article.link }}
            </a>
        </span>
    </div>
</article>
{% endblock %}
