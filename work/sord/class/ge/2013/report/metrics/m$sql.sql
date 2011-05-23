--		SELECT competency_id, class_of_desc,
--		       AVG(score) AS avg, STDEV(score) as stddev, COUNT(score) as count
--
--SELECT c.id AS competency_id, s.id AS score_id, score, student_id
--					FROM [dbo].[rpt_competency_ge2013] c
--						INNER JOIN [dbo].[rpt_competency_ge2013_score] s ON c.id = s.competency_id

DECLARE @nodes TABLE( id INT, desc_id INT, depth INT, path VARCHAR(MAX) )

;WITH nodes ( id, desc_id, depth, path ) AS
( SELECT tree.node_id, tree.child_id, 1, '|' + CAST(tree.node_id AS VARCHAR(MAX)) + '|'
    FROM rpt_competency_ge2013_tree_map tree
    WHERE tree.node_id IN ( SELECT DISTINCT id FROM rpt_competency_ge2013 )
  UNION ALL
  SELECT supertree.id, tree.child_id, depth + 1, supertree.path + '|' + CAST(tree.node_id AS VARCHAR(MAX)) + '|'
    FROM rpt_competency_ge2013_tree_map tree
    INNER JOIN nodes supertree -- recursive join
      ON supertree.desc_id = tree.node_id
   WHERE supertree.path NOT LIKE '%|' + CAST(tree.node_id AS VARCHAR(MAX)) + '|%' -- Avoid cycles
)
INSERT INTO @nodes( id, desc_id, depth, path ) SELECT * FROM nodes

INSERT INTO @nodes( id, desc_id, depth, path )
  SELECT desc_id, desc_id, depth, nodes.path + '|'
    FROM @nodes as nodes
   WHERE desc_id NOT IN ( SELECT id FROM @nodes )

SELECT node.id, AVG( score ) AS AVG
FROM @nodes node
INNER JOIN rpt_competency_ge2013_score score ON node.desc_id = score.competency_id
GROUP BY node.id
