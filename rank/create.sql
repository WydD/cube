DROP TABLE IF EXISTS reajustedresults;
SET @eventId := '', @competitionId := '', @score := 0, @num := 1, @inc := 1;
CREATE TEMPORARY TABLE reajustedresults SELECT eventId, personName, competitionId, personId, row_number as pos,best,average
FROM 
(SELECT eventId, personName, competitionId, personId, pos,rounds,best,average,
      @num := if(@eventId = eventId AND @competitionId = competitionId, if(@score = rounds*1000000+pos, @num, @num + @inc), 1) as row_number,
      @inc := if(@eventId = eventId AND @competitionId = competitionId, if(@score = rounds*1000000+pos, @inc + 1, 1), 1) as dummy2,
      @eventId := eventId as dummy3,
      @competitionId := competitionId as dummy4,
      @score := rounds*1000000+pos as dummy5
FROM (
	SELECT 
    res.eventId, 
    competitionId, 
    count(*) as rounds, 
    MAX(rd.rank*1000000+pos) mod 1000000 as pos, 
    CAST(SUBSTRING_INDEX(MAX(concat(rank,"|",if(best < 0, "-1", lpad(best, 10, 0)))),"|", -1) AS SIGNED) as best, 
    CAST(SUBSTRING_INDEX(MAX(concat(rank,"|",if(average < 0, "-1", lpad(average, 10, 0)))),"|", -1) AS SIGNED) as average, 
    res.personName, 
    res.personId
	FROM Results res JOIN Rounds rd ON (roundId = rd.id) JOIN competitions c ON (res.competitionId = c.id) 
	WHERE c.year = '2011' AND c.countryId = 'France' AND res.competitionId <> 'France2011' AND best <> -1 AND average <> -1 
	GROUP BY competitionId, res.eventId, res.personId
	ORDER BY competitionId, eventId, rounds DESC, pos ASC
) c) c;
DROP TABLE IF EXISTS opensize;
CREATE TABLE opensize AS select `competitionId` AS `competitionid`,(case when (`count` >= 40) then 2 when (`count` < 25) then 0 else 1 end) AS `class`, `count` from (select `r`.`competitionId` AS `competitionId`,count(distinct `r`.`personId`) AS `count` from (`results` `r` join `competitions` `c`) where ((`r`.`competitionId` = `c`.`id`) and (`c`.`countryId` = 'France') and (`c`.`year` = 2011) and (not((`c`.`id` like 'France%')))) group by `r`.`competitionId`) c;

DROP TABLE IF EXISTS f1score;
CREATE TABLE f1score AS SELECT eventId, personName, personId, competitionId, pos, class,
  CASE(class) WHEN 2 THEN
    CASE
      WHEN pos = 1 THEN 25 
      WHEN pos = 2 THEN 18
      WHEN pos = 3 THEN 15
      WHEN pos = 4 THEN 12
      WHEN pos = 5 THEN 10
      WHEN pos = 6 THEN 9
      WHEN pos = 7 THEN 8
      WHEN pos = 8 THEN 7
      WHEN pos = 9 THEN 6
      WHEN pos >= 10 AND pos <= 12 THEN 5
      WHEN pos >= 13 AND pos <= 16 THEN 4
      WHEN pos >= 17 AND pos <= 24 THEN 3
      WHEN pos >= 25 AND pos <= 39 THEN 2
      ELSE 1
    END
    WHEN 1 THEN
    CASE
      WHEN pos = 1 THEN 18 
      WHEN pos = 2 THEN 15
      WHEN pos = 3 THEN 12
      WHEN pos = 4 THEN 10
      WHEN pos = 5 THEN 9
      WHEN pos = 6 THEN 8
      WHEN pos = 7 THEN 7
      WHEN pos = 8 THEN 6
      WHEN pos = 9 THEN 5
      WHEN pos >= 10 AND pos <= 12 THEN 4
      WHEN pos >= 13 AND pos <= 16 THEN 3
      WHEN pos >= 17 AND pos <= 24 THEN 2
      ELSE 1
    END
    WHEN 0 THEN
    CASE
      WHEN pos = 1 THEN 15 
      WHEN pos = 2 THEN 12
      WHEN pos = 3 THEN 10
      WHEN pos = 4 THEN 9
      WHEN pos = 5 THEN 8
      WHEN pos = 6 THEN 7
      WHEN pos = 7 THEN 6
      WHEN pos = 8 THEN 5
      WHEN pos = 9 THEN 4
      WHEN pos >= 10 AND pos <= 12 THEN 3
      WHEN pos >= 13 AND pos <= 16 THEN 2
      ELSE 1
    END
  END
  score FROM reajustedresults NATURAL JOIN opensize c;

SET @eventId := '', @personId := '', @num := 1;
CREATE TEMPORARY TABLE f1rank AS
select eventId, personId, sum(if(
#row_number <= 5,
 eventId = '333fm' AND row_number <= 6 OR eventId <> '333fm' AND row_number <= 7,
	score,0)) as total, sum(score) as nltotal, sum(if(pos = 1, 1, 0)) as count FROM (
select eventId, competitionId, personId, score, pos,
      @num := if(@eventId = eventId AND @personId = personId, @num + 1, 1) as row_number,
      @eventId := eventId as dummy1,
      @personId := personId as dummy2
from (SELECT eventId, competitionId, personId, score, pos FROM f1score order by eventId, personId, score DESC) c) c 
GROUP BY eventId, personId
ORDER BY eventId, total DESC, nltotal DESC, count DESC;

DROP TABLE IF EXISTS finalscore;
SET @eventId := '', @total := 0, @count := -1, @average := -1, @nltotal := -1, @best := -1, @num := 1, @inc := 1;
CREATE TABLE finalscore AS 
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
FROM f1rank f 
  NATURAL JOIN (SELECT eventId, personId, MIN(best) as best FROM results WHERE best > 0 GROUP BY personId, eventId) r1 
  NATURAL LEFT OUTER JOIN (SELECT eventId, personId, MIN(average) as average FROM results WHERE average > 0 GROUP BY personId, eventId) r2 
GROUP BY f.eventId, f.personId
ORDER BY f.eventId, f.total DESC, f.nltotal DESC, f.count DESC, ISNULL(r2.average), r2.average ASC, r1.best ASC) c) c;
