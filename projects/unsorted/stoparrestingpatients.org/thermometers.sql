create table thermometers (id int auto_increment primary key,
                           name text not null,
                           description text,
                           min int not null default 0,
                           max int not null);
create table thermometer_values (id int auto_increment primary key,
                                 thermometer int not null references thermometers(id),
                                 value int not null,
                                 time TIMESTAMP);
insert into thermometers (name, description, min, max)
       values ("money-therm", "Money Raised", 0, 450000),
              ("sigs-therm", "Signatures Raised", 0, 100000);
