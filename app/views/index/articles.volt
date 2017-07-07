{% block content %}
{% for article in articles %}
    <li>
        <h3>#{{ article.title }}</h3>
        <div class="content">
        {{ article.content | digest }}
        </div>
    </li>
{% endfor %}
</ul>
{% endblock %}
