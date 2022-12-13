CREATE INDEX art ON tfidf_similarity(artist_1_id, artist_2_id);
CREATE INDEX art ON raw_similarity(artist_1_id, artist_2_id);

CREATE TABLE raw_tfidf_diff
  (artist_1_id INT NOT NULL,
   artist_2_id INT NOT NULL,
   diff FLOAT NOT NULL DEFAULT 0,
   FOREIGN KEY (artist_1_id) REFERENCES artist(id),
   FOREIGN KEY (artist_2_id) REFERENCES artist(id));

INSERT INTO raw_tfidf_diff
  SELECT r.artist_1_id, r.artist_2_id, ABS(t.cosine - r.cosine)
   FROM raw_similarity AS r INNER JOIN tfidf_similarity AS t
    USING (artist_1_id, artist_2_id);