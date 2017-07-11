DROP TABLE IF EXISTS site;
CREATE TABLE site (
    site_id int not null auto_increment,
    code varchar(255), 
    title varchar(255),
    subtitle varchar(255),
    link varchar(255),
    author varchar(90),
    email varchar(90),
    power varchar(255),
    articles int,
    created_at timestamp default now(),
    updated_at datetime,
    primary key(site_id)
);

DROP TABLE IF EXISTS tag;
CREATE TABLE tag (
    tag_id int not null auto_increment,
    name varchar(100),
    primary key(tag_id)
);

DROP TABLE IF EXISTS article;
CREATE TABLE article (
    article_id int not null auto_increment,
    code varchar(255), 
    link varchar(255), 
    site_id int,
    title varchar(255),
    summary varchar(1000),
    content mediumtext,
    published_at datetime,
    created_at timestamp default now(),
    updated_at datetime,
    primary key(article_id)
);

DROP TABLE IF EXISTS tag_article;
CREATE TABLE tag_article (
    tag_article_id int not null auto_increment,
    cateory_id int,
    article_id int,
    primary key(tag_article_id)
);
