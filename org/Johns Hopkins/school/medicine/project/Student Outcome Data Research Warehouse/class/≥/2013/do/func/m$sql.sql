USE [sord_dev_will]
GO

DECLARE	@return_value int

EXEC @return_value = [dbo].[rpt_competency_ge2013_proc]

SELECT	'Return Value' = @return_value

GO
