{% block content %}
<span><a href="/site/list">网站列表</a></span>
{% for article in articles %}
    <article>
        <div style="display:flex;justify-content:space-between;">
            <span class="h3"><a href="/article/detail/{{ article.article_id }}"># {{ article.title }}</a></span>
            <span>发布于: {{ article.published_at }}</span>
            <span>源自: <a href="/article/list/{{ article.site_id }}">{{ article.site.title }}</a> | By <a href="/site/detail/{{ article.site_id }}">{{ article.site.author }}</a></span>
        </div>
        <div class="content" style="display:flex;">
        {{ article.content | digest }}... <a href="/article/detail/{{ article.article_id }}">更 多</a>
        </div>
    </article>
{% endfor %}
</ul>
{% endblock %}
