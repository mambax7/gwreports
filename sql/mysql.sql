#
# Tables for gwreports module
# @version    $Id: mysql.sql 45 2013-04-04 17:08:14Z rgriffith $
#

CREATE TABLE gwreports_report (
  report_id          INT(8) UNSIGNED  NOT NULL AUTO_INCREMENT,
  report_name        VARCHAR(255)     NOT NULL,
  report_description TEXT             NOT NULL,
  report_active      TINYINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (report_id)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE gwreports_access (
  report  INT(8) UNSIGNED NOT NULL,
  groupid INT(5) UNSIGNED NOT NULL,
  PRIMARY KEY (report, groupid)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE gwreports_parameter (
  parameter_id          INT(8) UNSIGNED                                                                  NOT NULL AUTO_INCREMENT,
  report                INT(8) UNSIGNED                                                                  NOT NULL,
  parameter_name        VARCHAR(255)                                                                     NOT NULL,
  parameter_title       VARCHAR(255)                                                                     NOT NULL,
  parameter_description TEXT                                                                             NOT NULL,
  parameter_order       INT(8) UNSIGNED                                                                  NOT NULL DEFAULT '0',
  parameter_default     VARCHAR(255)                                                                     NOT NULL,
  parameter_required    TINYINT UNSIGNED                                                                 NOT NULL DEFAULT '0',
  parameter_length      INT(3) UNSIGNED                                                                  NOT NULL DEFAULT '0',
  parameter_type        ENUM ('text', 'liketext', 'date', 'integer', 'yesno', 'decimal', 'autocomplete') NOT NULL DEFAULT 'text',
  parameter_decimals    INT(3) UNSIGNED                                                                  NOT NULL DEFAULT '0',
  parameter_sqlchoice   TEXT                                                                             NOT NULL,
  PRIMARY KEY (parameter_id),
  UNIQUE KEY (report, parameter_name)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE gwreports_topic (
  topic_id          INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  topic_name        VARCHAR(255)    NOT NULL,
  topic_description TEXT            NOT NULL,
  topic_order       INT(8) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (topic_id)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE gwreports_grouping (
  topic          INT(8) UNSIGNED NOT NULL,
  report         INT(8) UNSIGNED NOT NULL,
  grouping_order INT(8) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (topic, report)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE gwreports_section (
  section_id          INT(8) UNSIGNED  NOT NULL AUTO_INCREMENT,
  report              INT(8) UNSIGNED  NOT NULL,
  section_name        VARCHAR(255)     NOT NULL,
  section_description TEXT             NOT NULL,
  section_order       INT(8) UNSIGNED  NOT NULL DEFAULT '0',
  section_showtitle   TINYINT UNSIGNED NOT NULL DEFAULT '0',
  section_multirow    TINYINT UNSIGNED NOT NULL DEFAULT '1',
  section_skipempty   TINYINT UNSIGNED NOT NULL DEFAULT '0',
  section_datatools   TINYINT UNSIGNED NOT NULL DEFAULT '0',
  section_query       TEXT             NOT NULL,
  PRIMARY KEY (section_id)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE gwreports_column (
  column_id              INT(8) UNSIGNED  NOT NULL AUTO_INCREMENT,
  section                INT(8) UNSIGNED  NOT NULL,
  column_name            VARCHAR(255)     NOT NULL,
  column_title           VARCHAR(255)     NOT NULL,
  column_hide            TINYINT UNSIGNED NOT NULL DEFAULT '0',
  column_sum             TINYINT UNSIGNED NOT NULL DEFAULT '0',
  column_break           TINYINT UNSIGNED NOT NULL DEFAULT '0',
  column_outline         TINYINT UNSIGNED NOT NULL DEFAULT '0',
  column_apply_nl2br     TINYINT UNSIGNED NOT NULL DEFAULT '0',
  column_is_unixtime     TINYINT UNSIGNED NOT NULL DEFAULT '0',
  column_format          VARCHAR(255)     NOT NULL DEFAULT '',
  column_style           VARCHAR(255)     NOT NULL DEFAULT '',
  column_extended_format TEXT             NOT NULL,
  PRIMARY KEY (column_id),
  UNIQUE KEY (section, column_name)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

