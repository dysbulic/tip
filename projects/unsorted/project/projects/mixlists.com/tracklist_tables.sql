drop table if exists artist;
create table artist(id int auto_increment primary key,
                    name text not null,
                    sortname text,
                    guid char(36) not null,
                    fulltext(name))
             character set utf8;

drop table if exists track;
create table track(id int auto_increment primary key,
                   name text not null,
                   artist int not null references artist(id),
                   length int,
                   year int,
                   guid char(36) not null,
                   fulltext(name))
             character set utf8;

drop table if exists track_artist_rship_type;
create table track_artist_rship_type
  (id int not null primary key,
   parent int not null references track_artist_rship_type(id),
   node_order int not null default 0,
   name text not null,
   description text not null)
             character set utf8;

drop table if exists track_artist;
create table track_artist
  (id int auto_increment unique not null,
   track int not null references track(id),
   artist int not null references artist(id),
   rship int not null references track_artist_rship_type(id),
   primary key(track, artist, rship));

drop table if exists artist_artist_rship_type;
create table artist_artist_rship_type
  (id int not null primary key,
   parent int not null references artist_artist_rship_type(id),
   node_order int not null default 0,
   name text not null,
   description text not null)
             character set utf8;

drop table if exists artist_artist;
create table artist_artist
  (id int auto_increment unique not null,
   subject int not null references artist(id),
   object int not null references artist(id),
   rship int not null references artist_artist_rship_type(id),
   primary key(subject, object, rship));

drop table if exists album;
create table album(id int auto_increment primary key,
                   name text not null,
                   artist int references artist(id),
                   guid char(36) not null) 
             character set utf8;

drop table if exists album_track;
create table album_track(album int not null references album(id),
                         track int not null references track(id),
                         number int not null);
