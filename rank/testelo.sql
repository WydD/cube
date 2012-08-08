CREATE TABLE eloscore (personId VARCHAR(32), eventId VARCHAR(32), competitionId VARCHAR(32),personName  varchar(80),pos INT, score INT);
DROP VIEW IF EXISTS elosum;
CREATE VIEW elosum AS SELECT personId,eventId,personName,sum(score) as elo FROM eloscore GROUP BY personId,eventId;

delimiter |
DROP PROCEDURE loopELO |
CREATE PROCEDURE loopELO()
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE c VARCHAR(32);
  DECLARE cur CURSOR FOR SELECT o.competitionId FROM opensize o, Competitions c WHERE o.competitionId = c.id ORDER BY year, month, day;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
  
  OPEN cur;
  
  REPEAT
    FETCH cur INTO c;
    IF NOT done THEN
    	SELECT c;
    	CALL computeELO(c);
    END IF;
  UNTIL done END REPEAT;
  
  CLOSE cur;
END |
delimiter ;

delimiter |
DROP PROCEDURE computeELO;
CREATE PROCEDURE computeELO(comp VARCHAR(32))
BEGIN
INSERT INTO eloscore (
SELECT
	r1.personId,r1.eventId,r1.competitionId,r1.personName,r1.pos,
	ROUND( 500 *(SUM(1 - 1/(1+POW(10,(IFNULL(e2.elo, 0)-IFNULL(e1.elo, 0))/200))
	))/v.c) as elodiff
FROM ((reajustedresults r1 NATURAL LEFT JOIN elosum e1) LEFT JOIN 
	 (reajustedresults r2 NATURAL LEFT JOIN elosum e2) ON (
        r1.eventId = r2.eventId AND 
        r1.competitionId = r2.competitionId AND
#        r1.personId <> r2.personId AND
        r1.pos <= r2.pos
	 )) JOIN (SELECT competitionId, eventId, count(*) c FROM reajustedresults r GROUP BY competitionId, eventId) v ON (r1.eventId = v.eventId AND r1.competitionId = v.competitionId)
WHERE
	r1.competitionId = comp   
GROUP BY r1.personId,r1.eventId);

INSERT INTO eloscore (select personId, eventId, NULL, personName,0,round(elo*500/m - elo) as elodiff from elosum NATURAL JOIN (select eventId, max(elo) m FROM elosum GROUP BY eventId) v);
END |
delimiter ;

DROP TABLE IF EXISTS elorank;
CREATE TEMPORARY TABLE elorank AS SELECT personId,eventId,sum(score) as total,sum(score) as nltotal,sum(if(pos = 1, 1, 0)) as count FROM eloscore GROUP BY personId,eventId;
DROP TABLE IF EXISTS finaleloscore;
SET @eventId := '', @total := 0, @count := -1, @average := -1, @nltotal := -1, @best := -1, @num := 1, @inc := 1;
CREATE TABLE finaleloscore AS 
SELECT eventId, personId, total, nltotal, count, average, best, row_number as pos FROM (
SELECT eventId, personId, total, nltotal, count, average, best, 
      CAST(@num := if(@eventId = eventId, if(@total = total AND @nltotal = nltotal AND @count = count AND (@average IS NULL AND average IS NULL OR @average = average) AND @best = best, @num, @num + @inc), 1) AS UNSIGNED) as row_number,
      @inc := if(@eventId = eventId, if(@total = total AND @nltotal = nltotal AND @count = count AND (@average IS NULL AND average IS NULL OR @average = average) AND @best = best, @inc + 1, 1), 1) as dummy2,
      @eventId := eventId as dummy3,
      @total := total as dtotal,
      @count := count as dcount,
      @average := average as daverage,
      @best := best as dbest
FROM (
SELECT f.eventId, f.personId, f.total, f.nltotal, f.count, r1.best, r2.average
FROM elorank f 
  NATURAL JOIN (SELECT eventId, personId, MIN(best) as best FROM results WHERE best > 0 GROUP BY personId, eventId) r1 
  NATURAL LEFT OUTER JOIN (SELECT eventId, personId, MIN(average) as average FROM results WHERE average > 0 GROUP BY personId, eventId) r2 
GROUP BY f.eventId, f.personId
ORDER BY f.eventId, f.total DESC, f.nltotal DESC, f.count DESC, ISNULL(r2.average), r2.average ASC, r1.best ASC) c) c;

truncate table eloscore;
call loopELO();
SELECT * FROM elosum WHERE eventId = '333' ORDER BY elo DESC LIMIT 10;
