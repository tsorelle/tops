/*
SQLyog Professional v12.4.3 (64 bit)
MySQL - 5.7.14 : Database - twoquake_test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Data for the table `tops_translations` */

insert  into `tops_translations`(`id`,`language`,`code`,`text`,`createdby`,`createdon`,`changedby`,`changedon`,`active`) values 
(5,'en','error-no-file','The file \"%s\" was not found.','admin','2017-11-04 13:39:55','admin',NULL,1),
(6,'en','error-unknown-file','File not found.','admin','2017-11-04 13:39:55','admin',NULL,1),
(7,'en','service-failed','Service failed. If the problem persists contact the site administrator.','admin','2017-11-04 13:39:55','admin',NULL,1),
(8,'en','service-insecure','Your request contains potentially insecure content. HTML tags are not allowed.','admin','2017-11-04 13:39:55','admin',NULL,1),
(9,'en','service-invalid-request','Invalid request','admin','2017-11-04 13:39:55','admin',NULL,1),
(10,'en','service-no-auth','Sorry, you are not authorized to use this service.','admin','2017-11-04 13:39:55','admin',NULL,1),
(11,'en','service-no-request-value','No \"%s\" value was received.','admin','2017-11-04 13:39:55','admin',NULL,1),
(12,'en','service-no-request','No request was received','admin','2017-11-04 13:39:55','admin',NULL,1),
(13,'en','session-expired','Sorry, your session has expired or is not valid. Please return to home page.','admin','2017-11-04 13:39:55','admin',NULL,1),
(14,'en','smtp-warning-1','Address is valid for SMTP but has unusual elements','admin','2017-11-04 13:39:55','admin',NULL,1),
(15,'en','smtp-warning-2','Address is valid within the message but cannot be used unmodified for the envelope','admin','2017-11-04 13:39:55','admin',NULL,1),
(16,'en','smtp-warning-3','Address contains deprecated elements but may still be valid in restricted contexts','admin','2017-11-04 13:39:55','admin',NULL,1),
(17,'en','smtp-warning-4','The address is only valid according to the broad definition of RFC 5322. It is otherwise invalid.','admin','2017-11-04 13:39:55','admin',NULL,1),
(18,'en','title-key-words','the,a,of,an,in,and','admin','2017-11-04 13:39:55','admin',NULL,1),
(19,'en','validation-code-blank','The code field cannot be blank.','admin','2017-11-04 13:39:55','admin',NULL,1),
(20,'en','validation-email-req','A valid email address is required','admin','2017-11-04 13:39:55','admin',NULL,1),
(21,'en','validation-field-invalid2','The entry \"%s\" is not valid for \"%s\".','admin','2017-11-04 13:39:55','admin',NULL,1),
(22,'en','validation-field-invalid','The entry \"%s\" is not valid\".','admin','2017-11-04 13:39:55','admin',NULL,1),
(23,'en','validation-field-req2','An entry \"%s\" is required for \"%s\".','admin','2017-11-04 13:39:55','admin',NULL,1),
(24,'en','validation-field-req','An entry is required for \"%s\".','admin','2017-11-04 13:39:55','admin',NULL,1),
(25,'en','validation-invalid-email2','The email address is not valid.','admin','2017-11-04 13:39:55','admin',NULL,1),
(26,'en','validation-invalid-email','The email address \"%s\" is not valid.','admin','2017-11-04 13:39:55','admin',NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
