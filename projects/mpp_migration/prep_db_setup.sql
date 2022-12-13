-- SQL commands necessary to create intermediary database used in the sitebuilder -> Kintera transition

create table author (
  id int auto_increment primary key,
  name text not null) TYPE=MyISAM;

create table source (
  id int auto_increment primary key,
  name text not null) TYPE=MyISAM;

create table type (
  id int auto_increment primary key,
  name text not null) TYPE=MyISAM;

create table article (
  id int auto_increment primary key,
  author_id int references author(id),
  source_id int references source(id),
  type_id int references type(id),
  title text not null,
  subtitle text,
  text text,
  publication_date date default null,
  modification_time timestamp default current_timestamp) TYPE=MyISAM;

create table location (
  id int auto_increment primary key,
  name text not null,
  abbreviation text not null) TYPE=MyISAM;

create table itemlocation (
  id int auto_increment primary key,
  item_id int not null,
  location_id int not null references location(id)) TYPE=MyISAM;

create table bill (
  id int auto_increment primary key,
  title text not null,
  number text default null,
  summary text default null,
  sponsors text default null,
  history text default null,
  text text default null,
  status_id int references status(id),
  publication_date date default null) TYPE=MyISAM;

create table status (
  id int auto_increment primary key,
  text text not null) TYPE=MyISAM;

create table notes (
  id int auto_increment primary key,
  bill_id int references bill(id),
  title text not null,
  text text default null) TYPE=MyISAM;
