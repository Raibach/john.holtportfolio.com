SET FOREIGN_KEY_CHECKS=0;
SET @tables = NULL;
SELECT GROUP_CONCAT(CONCAT('DROP TABLE IF EXISTS `', table_name, '`')) INTO @tables
FROM information_schema.tables
WHERE table_schema = DATABASE();
SET @tables = IFNULL(@tables, '');
SET @tables = IF(@tables != '', @tables, 'SELECT 1');
PREPARE stmt FROM @tables;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET FOREIGN_KEY_CHECKS=1;

