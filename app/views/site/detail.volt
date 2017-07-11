{% block content %}
<div class="content">
    <div class="site-block">
        <div>
            <span class="title">
                <a href="{{ site.link }}" target="_blank">
                    {{ site.title }}
                </a>
            </span>
            <span>
                <a href="/article/list/{{ site.site_id }}">
                    文章
                </a>
            </span>
        </div>
        <div class="author">
            <span class="label">
                作者：
            </span>
            {{ site.author }}
        </div>
        <div class="email">
            <span class="label">
                联系方式：
            </span>
            <a href="mailto:{{ site.email }}">
                {{ site.email }}
            </a>
        </div>
        <div class="link">
            <span class="label">
                官博：
            </span>
            <a href="{{ site.link }}" target="_blank">
                {{ site.link }}
            </a>
        </div>
        <div class="updated">
            <span class="label">
                最近更新：
            </span>
            {{ site.updated_at }}
        </div>
    </div>
</div>
{% endblock %}
