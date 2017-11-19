Позволяет сокращать ссылки.
1. В мускул нужно создать БД: create database shortlinks character set=utf8;
use shortlinks; create table links (hash VARCHAR(10) NOT NULL, link VARCHAR(2048) NOT NULL, primary key (hash)); 