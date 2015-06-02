DROP TABLE IF EXISTS mvc_tag2question;
DROP TABLE IF EXISTS mvc_tag;

--
-- Create table for User
--
DROP TABLE IF EXISTS mvc_user;
CREATE TABLE mvc_user
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    acronym VARCHAR(64) UNIQUE NOT NULL,
    email VARCHAR(127),
    name VARCHAR(127),
    password VARCHAR(255),
    description TEXT,
    created DATETIME,
    updated DATETIME,
    active DATETIME

) ENGINE INNODB CHARACTER SET utf8;

--
-- Create table for Comments
--
DROP TABLE IF EXISTS mvc_comments;
CREATE TABLE mvc_comments
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    commentTypeId INT NOT NULL,
    questionId INT,
    parentId INT,
    userId INT NOT NULL,
    userAcronym VARCHAR(64) NOT NULL,
    userEmail VARCHAR(127),
    title TEXT,
    text TEXT,
    created DATETIME,
    updated DATETIME

) ENGINE INNODB CHARACTER SET utf8;

--
-- Add table for tags
--
DROP TABLE IF EXISTS mvc_tag;
CREATE TABLE mvc_tag
(
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name CHAR(64) NOT NULL
) ENGINE INNODB CHARACTER SET utf8;

--
-- Add table for tags to question
--
DROP TABLE IF EXISTS mvc_tag2question;
CREATE TABLE mvc_tag2question
(
  idQuestion INT NOT NULL,
  idTag INT NOT NULL,
 
  FOREIGN KEY (idQuestion) REFERENCES mvc_comments (id),
  FOREIGN KEY (idTag) REFERENCES mvc_tag (id),
 
  PRIMARY KEY (idQuestion, idTag)
) ENGINE INNODB;


DROP VIEW IF EXISTS mvc_VQuestion;
 
CREATE VIEW mvc_VQuestion
AS
SELECT 
  C.*,
  GROUP_CONCAT(T.name) AS tags
FROM mvc_comments AS C
  LEFT OUTER JOIN mvc_tag2question AS T2Q
    ON C.id = T2Q.idQuestion
  LEFT OUTER JOIN mvc_tag AS T
    ON T2Q.idTag = T.id
GROUP BY C.id
;
 
SELECT * FROM mvc_VQuestion;
