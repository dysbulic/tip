-- Author: dysbulic <dys@dhappy.org>
-- Date: June 2008
--
-- Performs a consine similarity analysis on raw artist data as described in:
--   http://odin.himinbi.org/idf_to_item:item/


-- Compute the length of the artist vectors.
--
CREATE TABLE raw_length
  (artist_id INT NOT NULL,
   length FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_id) REFERENCES artist(id));

INSERT INTO raw_length
  SELECT artist_id, SQRT(SUM(POW(count,2))) AS length
   FROM collected_listen GROUP BY artist_id;

-- Compute the cross product of the artist vectors.
--
CREATE TABLE raw_dot
  (artist_1_id INT NOT NULL,
   artist_2_id INT NOT NULL,
   dot_product FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_1_id) REFERENCES artist(id),
   FOREIGN KEY (artist_2_id) REFERENCES artist(id));

INSERT INTO raw_dot
  SELECT t1.artist_id, t2.artist_id, SUM(t1.count * t2.count) AS dot_product
   FROM collected_listen AS t1 JOIN collected_listen AS t2 USING (user_id)
   WHERE t1.artist_id <> t2.artist_id
   GROUP BY t1.artist_id, t2.artist_id;

-- Combine the lengths and dot products to find the cosine similarity.
--
CREATE TABLE raw_similarity
  (artist_1_id INT NOT NULL,
   artist_2_id INT NOT NULL,
   cosine FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_1_id) REFERENCES artist(id),
   FOREIGN KEY (artist_2_id) REFERENCES artist(id));

INSERT INTO raw_similarity
  SELECT artist_1_id, artist_2_id, dot_product / (rl_1.length * rl_2.length) as cosine
   FROM raw_dot AS rd JOIN raw_length AS rl_1 ON rd.artist_1_id = rl_1.artist_id
                      JOIN raw_length AS rl_2 ON rd.artist_2_id = rl_2.artist_id;
