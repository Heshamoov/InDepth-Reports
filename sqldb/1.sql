SELECT
t0.subject_name 'Subject0', t0.exam_name 'Exam0', t0.acd_code 'Year0', t0.grade 'Grade0', t0.MoreOrEqual65P '>=65%0', t0.MoreOrEqual75P '>=75%0', t0.exam_mark 'Mark0',
t1.subject_name 'Subject1', t1.exam_name 'Exam1', t1.acd_code 'Year1', t1.grade 'Grade1', t1.MoreOrEqual65P '>=65%1', t1.MoreOrEqual75P '>=75%1', t1.exam_mark 'Mark1',
t2.subject_name 'Subject2', t2.exam_name 'Exam2', t2.acd_code 'Year2', t2.grade 'Grade2', t2.MoreOrEqual65P '>=65%2', t2.MoreOrEqual75P '>=75%2', t2.exam_mark 'Mark2'
FROM 
(
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks        
        WHERE acd_code = '2016 / 2017' AND(REPLACE(exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR08' GROUP BY subject_name ORDER BY subject_name
    ) t0 
LEFT JOIN
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total', COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks 
        WHERE acd_code = '2017 / 2018' AND  (REPLACE (exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR09' GROUP BY subject_name ORDER BY subject_name
    ) t1
    ON (t0.subject_name = t1.subject_name)
LEFT JOIN
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total', COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks 
        WHERE acd_code = '2018 / 2019' AND  (REPLACE (exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR10' GROUP BY subject_name ORDER BY subject_name
    ) t2
    ON (t0.subject_name = t2.subject_name)
)

UNION

SELECT
t0.subject_name 'Subject0', t0.exam_name 'Exam0', t0.acd_code 'Year0', t0.grade 'Grade0', t0.MoreOrEqual65P '>=65%0', t0.MoreOrEqual75P '>=75%0', t0.exam_mark 'Mark0',
t1.subject_name 'Subject1', t1.exam_name 'Exam1', t1.acd_code 'Year1', t1.grade 'Grade1', t1.MoreOrEqual65P '>=65%1', t1.MoreOrEqual75P '>=75%1', t1.exam_mark 'Mark1',
t2.subject_name 'Subject2', t2.exam_name 'Exam2', t2.acd_code 'Year2', t2.grade 'Grade2', t2.MoreOrEqual65P '>=65%2', t2.MoreOrEqual75P '>=75%2', t2.exam_mark 'Mark2'


FROM 
(
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks        
        WHERE acd_code = '2016 / 2017' AND(REPLACE(exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR08' GROUP BY subject_name ORDER BY subject_name
    ) t0 
RIGHT JOIN
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total', COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks 
        WHERE acd_code = '2017 / 2018' AND  (REPLACE (exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR09' GROUP BY subject_name ORDER BY subject_name
    ) t1
    ON (t0.subject_name = t1.subject_name)
LEFT JOIN
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total', COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks 
        WHERE acd_code = '2018 / 2019' AND  (REPLACE (exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR10' GROUP BY subject_name ORDER BY subject_name
    ) t2
    ON (t1.subject_name = t2.subject_name)
)

UNION

SELECT
t0.subject_name 'Subject0', t0.exam_name 'Exam0', t0.acd_code 'Year0', t0.grade 'Grade0', t0.MoreOrEqual65P '>=65%0', t0.MoreOrEqual75P '>=75%0', t0.exam_mark 'Mark0',
t1.subject_name 'Subject1', t1.exam_name 'Exam1', t1.acd_code 'Year1', t1.grade 'Grade1', t1.MoreOrEqual65P '>=65%1', t1.MoreOrEqual75P '>=75%1', t1.exam_mark 'Mark1',
t2.subject_name 'Subject2', t2.exam_name 'Exam2', t2.acd_code 'Year2', t2.grade 'Grade2', t2.MoreOrEqual65P '>=65%2', t2.MoreOrEqual75P '>=75%2', t2.exam_mark 'Mark2'


FROM 
(
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks        
        WHERE acd_code = '2016 / 2017' AND(REPLACE(exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR08' GROUP BY subject_name ORDER BY subject_name
    ) t0
RIGHT JOIN
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total', COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks 
        WHERE acd_code = '2018 / 2019' AND  (REPLACE (exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR10' GROUP BY subject_name ORDER BY subject_name
    ) t2    
    ON (t0.subject_name = t2.subject_name)
LEFT JOIN    
    (
        SELECT subject_name, exam_name, acd_code, grade, section, COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total', COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65', ROUND( COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P', COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75', ROUND( COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark FROM new_marks 
        WHERE acd_code = '2017 / 2018' AND  (REPLACE (exam_name, ' ', '') = REPLACE('Final Certificate Mark', ' ','') ) AND grade = 'GR09' GROUP BY subject_name ORDER BY subject_name
    ) t1
    ON (t2.subject_name = t1.subject_name)    

)
