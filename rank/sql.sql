CREATE TABLE reajustedresults SELECT eventId, personName, competitionId, personId, row_number as pos,best,average
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
	WHERE c.year = '2011' AND c.countryId = 'France' AND res.competitionId <> 'France2011' AND best <> -1 
	GROUP BY competitionId, res.eventId, res.personId
	ORDER BY competitionId, eventId, rounds DESC, pos ASC
) c) c;

SET @eventId := '', @competitionId := '', @score := 0, @num := 1, @inc := 1;
SELECT eventId, personName, competitionId, personId, row_number as pos,best,average
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
	WHERE c.year = '2011' AND c.countryId = 'France' AND res.competitionId <> 'France2011' AND best <> -1 
	GROUP BY competitionId, res.eventId, res.personId
	ORDER BY competitionId, eventId, rounds DESC, pos ASC
) c) c;

DROP TABLE reajustedresults;
SELECT best, 
CAST(SUBSTRING_INDEX(concat(90,"|",if(best < 0, "-1", lpad(best, 10, 0))),"|", -1) AS SIGNED)
FROM results WHERE eventId = '333bf' LIMIT 100;
SHOW CREATE TABLE results;

SELECT * FROM events;

CREATE VIEW `classificationopen` AS select `r`.`competitionId` AS `competitionId`,count(distinct `r`.`personId`) AS `count` from (`results` `r` join `competitions` `c`) where ((`r`.`competitionId` = `c`.`id`) and (`c`.`countryId` = 'France') and (`c`.`year` = 2011) and (not((`c`.`id` like 'France%')))) group by `r`.`competitionId`;
CREATE TABLE opensize AS select `classificationopen`.`competitionId` AS `competitionid`,(case when (`classificationopen`.`count` >= 40) then 2 when (`classificationopen`.`count` <= 25) then 0 else 1 end) AS `class`, `count` from `classificationopen`;
select `classificationopen`.`competitionId` AS `competitionid`,(case when (`classificationopen`.`count` > 40) then 2 when (`classificationopen`.`count` <= 25) then 0 else 1 end) AS `class`, `count` from `classificationopen`;
CREATE VIEW fullrank AS SELECT res.eventId, res.competitionId, MAX(rd.rank*1000000+pos) mod 1000000 as pos, res.personName, res.personId FROM `wca`.`Results` res JOIN Rounds rd ON (roundId = id) JOIN competitions c ON (res.competitionId = c.id) WHERE c.year = '2011' AND c.countryId = 'France' AND competitionId <> 'France2011' AND best <> -1 GROUP BY res.eventId, res.competitionId, res.personId;
ALTER VIEW basicscore AS SELECT eventId, personName, personId, competitionId, maxpos+1-pos as score FROM reajustedresults NATURAL JOIN sizeperevent c;
ALTER VIEW sizeperevent as SELECT competitionId, eventId, max(pos) as maxpos FROM reajustedresults GROUP BY eventId, competitionId;
#ALTER VIEW f1score AS 
DROP VIEW f1score;
CREATE TEMPORARY TABLE f1score AS SELECT eventId, personName, personId, competitionId, pos, class,
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
      WHEN pos >= 17 AND pos <= 25 THEN 3
      WHEN pos >= 26 AND pos <= 39 THEN 2
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
      WHEN pos >= 17 AND pos <= 25 THEN 2
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

#SELECT eventId, personId, total, count, 
#      CAST(@num2 := if(@eventId2 = eventId, if(@score2 = total, @num2, @num2 + @inc2), 1) AS UNSIGNED) as row_number,
#      @inc2 := if(@eventId2 = eventId, if(@score2 = total, @inc2 + 1, 1), 1) as dummy2,
#      @eventId2 := eventId as dummy3,
#      @score2 := total as dummy5
#FROM (
CREATE TABLE f1rank AS
select eventId, personId, sum(score) as total, sum(if(pos = 1, 1, 0)) as count FROM (select * from (
select eventId, competitionId, personId, score, pos,
      @num := if(@eventId = eventId AND @personId = personId, @num + 1, 1) as row_number,
      @eventId := eventId as dummy1,
      @personId := personId as dummy2
from (SELECT eventId, competitionId, personId, score, pos FROM f1score order by eventId, personId, score DESC) c) c
WHERE eventId = '333fm' AND row_number <= 6 OR eventId <> '333fm' AND row_number <= 7) c
GROUP BY eventId, personId
ORDER BY eventId, total DESC, count DESC;

#DROP TABLE finalscore;
#CREATE TABLE finalscore AS 

SET @eventId := '', @total := 0, @count := -1, @average := -1, @best := -1, @num := 1, @inc := 1;
SELECT eventId, personId, total, count, average, best, row_number as pos FROM (
SELECT eventId, personId, total, count, average, best, 
      CAST(@num := if(@eventId = eventId, if(@total = total AND @count = count AND (@average IS NULL AND average IS NULL OR @average = average) AND @best = best, @num, @num + @inc), 1) AS UNSIGNED) as row_number,
      @inc := if(@eventId = eventId, if(@total = total AND @count = count AND (@average IS NULL AND average IS NULL OR @average = average) AND @best = best, @inc + 1, 1), 1) as dummy2,
      @eventId := eventId as dummy3,
      @total := total as dtotal,
      @count := count as dcount,
      @average := average as daverage,
      @best := best as dbest
FROM (
SELECT f.eventId, f.personId, f.total, f.count, r1.best, r2.average
FROM f1rank f 
  NATURAL JOIN (SELECT eventId, personId, MIN(best) as best FROM results WHERE best > 0 GROUP BY personId, eventId) r1 
  NATURAL LEFT OUTER JOIN (SELECT eventId, personId, MIN(average) as average FROM results WHERE average > 0 GROUP BY personId, eventId) r2 
WHERE f.eventId = '333'
GROUP BY f.eventId, f.personId
ORDER BY f.eventId, f.total DESC, f.count DESC, ISNULL(r2.average), r2.average ASC, r1.best ASC) c) c;

SELECT * FROM finalscore WHERE personId = '2008HANK01';

SELECT eventId as id, competitionId, pos, score FROM f1score WHERE personId = '2009PETI01' ORDER BY eventId ASC;

SELECT eventId, personId, MIN(best) as best FROM results WHERE best > 0 GROUP BY personId, eventId;

DROP VIEW opensize;
SELECT personId as id, competitionId, pos, score FROM f1score WHERE eventId = '444' ORDER BY personId ASC;
 select `r`.`personId` AS `round`,count(distinct `r`.`competitionId`) AS `count` from (`results` `r` join `competitions` `c`) where ((`r`.`competitionId` = `c`.`id`) and (`c`.`countryId` = 'France') and (`c`.`year` = 2011) and (not((`c`.`id` like 'France%')))) group by `r`.`personId`;