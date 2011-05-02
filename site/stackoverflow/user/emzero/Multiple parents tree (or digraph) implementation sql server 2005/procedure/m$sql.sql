--IF NOT EXISTS ( SELECT * FROM sysobjects
--				  WHERE id = object_id( N'[dbo].[#ObjectRelations]' ) AND OBJECTPROPERTY( id, N'IsUserTable') = 1 )
IF OBJECT_ID('tempdb..#ObjectRelations') IS NULL 
	CREATE TABLE #ObjectRelations( id varchar(20), nextId varchar(20) )

/* Cycle */
/*
INSERT INTO #ObjectRelations VALUES ('A', 'B')
INSERT INTO #ObjectRelations VALUES ('B', 'C') 
INSERT INTO #ObjectRelations VALUES ('C', 'A')
*/

/* Multi root */
INSERT INTO #ObjectRelations VALUES ('G', 'B')
INSERT INTO #ObjectRelations VALUES ('A', 'B') 
INSERT INTO #ObjectRelations VALUES ('B', 'C')
INSERT INTO #ObjectRelations VALUES ('B', 'X')
INSERT INTO #ObjectRelations VALUES ('C', 'E') 
INSERT INTO #ObjectRelations VALUES ('C', 'D') 
INSERT INTO #ObjectRelations VALUES ('E', 'F') 
INSERT INTO #ObjectRelations VALUES ('D', 'F') 

DECLARE @startIds TABLE ( id VARCHAR(20) PRIMARY KEY )

--INSERT INTO @startIds SELECT TOP 1 id FROM #ObjectRelations
;WITH 
        Ids (Id) AS ( SELECT Id FROM #ObjectRelations ),
    NextIds (Id) AS ( SELECT NextId FROM #ObjectRelations )
INSERT INTO @startIds
  /* This select will not return anything since there are not objects without predecessor, because it's a cyclic of course */
  SELECT DISTINCT Ids.Id FROM Ids
    LEFT JOIN NextIds on Ids.Id = NextIds.Id
    WHERE NextIds.Id IS NULL

SELECT * FROM @startIds

--;WITH Objects (Id, NextId, [Level], Path) AS
--( -- This is the 'Anchor' or starting point of the recursive query
--  SELECT rel.Id, rel.NextId, 1, CAST(rel.Id as VARCHAR(MAX))
--    FROM #ObjectRelations rel
--    WHERE rel.Id IN ( SELECT Id FROM @startIds )
--  UNION ALL
--  SELECT rel.Id, rel.NextId, [Level] + 1, RecObjects.Path + '' + rel.Id
--    FROM #ObjectRelations rel
--   INNER JOIN Objects RecObjects -- recursive join
--      ON rel.Id = RecObjects.NextId
--   WHERE RecObjects.Path NOT LIKE '%' + rel.Id + '%'
--
--)
--SELECT DISTINCT Id, NextId, [Level], Path
--FROM    Objects
--ORDER BY [Level]

DROP TABLE #ObjectRelations