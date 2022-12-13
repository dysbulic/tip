-- Author: dysbulic <dys@dhappy.org>
-- Date: June 2008
--
-- Performs a TF-IDF analysis on listener data as defined in:
--   http://odin.himinbi.org/idf_to_item:item/

-- These are the base tables that should exist and by populated with
-- data before the program starts.
--
CREATE TABLE IF NOT EXISTS user
  (id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(32) UNIQUE);
CREATE TABLE IF NOT EXISTS artist
  (id INT AUTO_INCREMENT PRIMARY KEY,
   name TEXT,
   mbid VARCHAR(48) UNIQUE);
CREATE TABLE IF NOT EXISTS listen
  (user_id INT NOT NULL,
   artist_id INT NOT NULL,
   count INT NOT NULL DEFAULT 1,
   FOREIGN KEY (user_id) REFERENCES user(id),
   FOREIGN KEY (artist_id) REFERENCES artist(id));

-- Unicode encoding errors have given multiple artists the same name.
-- When there is no MusicBrainz id, it is impossible to distinguish
-- these and they are lumped in the results.
--
CREATE TABLE collected_listen
  (user_id INT NOT NULL,
   artist_id INT NOT NULL,
   count INT DEFAULT 0,
   FOREIGN KEY (user_id) REFERENCES user(id),
   FOREIGN KEY (artist_id) REFERENCES artist(id));

SELECT 'Building collected_listen';
SELECT CURTIME();

INSERT INTO collected_listen
  SELECT user_id, artist_id, SUM(count) AS count FROM listen
   GROUP BY user_id, artist_id;

SELECT CURTIME();

-- Calculate the percentage of all artists that a user has listened
-- to. This maps to the number of documents to contain a term.
--
CREATE TABLE IF NOT EXISTS user_promiscuity
  (user_id INT NOT NULL,
   artist_count INT DEFAULT 0,     -- Total number of artists per user
   artist_percent FLOAT DEFAULT 0, -- Percentage of all artists per user
   FOREIGN KEY (user_id) REFERENCES user(id));

SELECT 'Building user_promiscuity';
SELECT CURTIME();

INSERT INTO user_promiscuity
  SELECT user_id, COUNT(user_id) AS artist_count,
         COUNT(user_id) / total AS artist_percent
   FROM collected_listen,
        (SELECT COUNT(distinct artist_id) as total from collected_listen) AS t
   GROUP BY user_id;

SELECT CURTIME();

-- Aggregate some totals for artists to make future computations simpler.
--
CREATE TABLE artist_total
  (artist_id INT NOT NULL,
   listen_count INT DEFAULT 0,  -- Total listens for the artist
   user_count INT DEFAULT 0,    -- Total number of users for artist
   FOREIGN KEY (artist_id) REFERENCES artist(id));

SELECT 'Building artist_total';
SELECT CURTIME();

INSERT INTO artist_total
  SELECT artist_id, SUM(count) AS listen_count,
         COUNT(user_id) as user_count
   FROM collected_listen GROUP BY artist_id;

SELECT CURTIME();

-- Calculate the percentage of an artist's total listens are from
-- individual users. This maps to the percentage of terms in a
-- document.
--
-- CREATE TABLE IF NOT EXISTS user_fanatacism
--   (user_id INT NOT NULL,
--    artist_id INT NOT NULL,
--    percent FLOAT NOT NULL DEFAULT 0,
--    FOREIGN KEY (user_id) REFERENCES user(id),
--    FOREIGN KEY (artist_id) REFERENCES artist(id));

-- INSERT INTO user_fanatacism
--   SELECT user_id, artist_id, count / listen_length AS percent
--    FROM collected_listen JOIN artist_total USING (artist_id);

-- Combine fanatacism and the log of the inverse of promiscuity to
-- form the tf-idf.
--
CREATE TABLE IF NOT EXISTS user_tfidf
  (user_id INT NOT NULL,
   artist_id INT NOT NULL,
   tfidf FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (user_id) REFERENCES user(id),
   FOREIGN KEY (artist_id) REFERENCES artist(id));

--INSERT INTO user_tfidf
--  SELECT fan.user_id, fan.artist_id, fan.percent * log(1 / prom.percent) as tfidf
--   FROM user_fanatacism AS fan JOIN user_promiscuity AS prom
--   ON fan.user_id = prom.user_id
--   WHERE prom.percent > 0 and prom.percent < 1; -- Avoid undefined and 0 for log(1/p)

SELECT 'Building user_tfidf';
SELECT CURTIME();

INSERT INTO user_tfidf
  SELECT list.user_id, list.artist_id, log(1 / prom.artist_percent) * (1 + log(list.count)) as tfidf
   FROM collected_listen AS list JOIN user_promiscuity AS prom
   USING (user_id)
   WHERE prom.artist_percent > 0 and prom.artist_percent < 1; -- Avoid undefined and 0 for log(1/p)

SELECT CURTIME();

-- Compute the length of the artist vectors.
--
CREATE TABLE tfidf_length
  (artist_id INT NOT NULL,
   length FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_id) REFERENCES artist(id));

SELECT 'Building tfidf_length';
SELECT CURTIME();

INSERT INTO tfidf_length
  SELECT artist_id, SQRT(SUM(POW(tfidf,2))) AS length
   FROM user_tfidf GROUP BY artist_id;

SELECT CURTIME();

-- Compute the cross product of the artist vectors.
--
CREATE TABLE tfidf_dot
  (artist_1_id INT NOT NULL,
   artist_2_id INT NOT NULL,
   dot_product FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_1_id) REFERENCES artist(id),
   FOREIGN KEY (artist_2_id) REFERENCES artist(id));

SELECT 'Building tfidf_dot';
SELECT CURTIME();

INSERT INTO tfidf_dot
  SELECT t1.artist_id, t2.artist_id, SUM(t1.tfidf * t2.tfidf) AS dot_product
   FROM user_tfidf AS t1 JOIN user_tfidf AS t2 USING (user_id)
   WHERE t1.artist_id <> t2.artist_id
   GROUP BY t1.artist_id, t2.artist_id;

SELECT CURTIME();

-- Combine the lengths and dot products to find the cosine similarity.
--
CREATE TABLE tfidf_similarity
  (artist_1_id INT NOT NULL,
   artist_2_id INT NOT NULL,
   cosine FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_1_id) REFERENCES artist(id),
   FOREIGN KEY (artist_2_id) REFERENCES artist(id));

SELECT 'Building tfidf_similarity';
SELECT CURTIME();

INSERT INTO tfidf_similarity
  SELECT artist_1_id, artist_2_id, dot_product / (tl_1.length * tl_2.length) as cosine
   FROM tfidf_dot AS td JOIN tfidf_length AS tl_1 ON td.artist_1_id = tl_1.artist_id
                        JOIN tfidf_length AS tl_2 ON td.artist_2_id = tl_2.artist_id;

SELECT CURTIME();