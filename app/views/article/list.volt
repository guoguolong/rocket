{% block content %}
<h6 >文章数:{{ total }}</h6>
{% for article in page.items %}
    <article>
        <div style="display:flex;justify-content:space-between;">
            <span class="h3"><a href="/article/detail/{{ article.article_id }}"># {{ article.title }}</a></span>
            <span>发布于: {{ article.published_at }} | 源自: <a href="{{ article.link }}">{{ article.site.title }}</a> | By <a href="/site/detail/{{ article.site_id }}">{{ article.site.author }}</a></span>
        </div>
        <div class="content">
        {{ article | digest }}... <a href="/article/detail/{{ article.article_id }}">阅读全文</a>
        </div>
    </article>
{% endfor %}
</ul>
{% endblock %}
