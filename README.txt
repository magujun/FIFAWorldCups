##
# COSC 213 Course Project
# MARCELO GUIMARAES JUNIOR
# ID 300326367
# Computer Information Systems
# Okanagan College
# Fall 2022
##

All of the features intended for this project have been implemented and are fully functional.
Nevertheless, other features can be added according to any new requirements.

I have used git as my version control platform and the project source can be found at:
https://github.com/magujun/fifaworldcups

Notes:
       Files have been copied over different servers using rsync and/or scp.
       After any and all copy operations, permissions and ownership might need to be adjusted to match the server's UIDs/GIDs. 
       Currently thy are set to 775 (-rwxrwxr-x). 
       Preferably, they should be owned:grouped as www-data:www-data, which works well for both Apache and NGINX web servers.
       The files/dirs access permissions could be anything less restrictive than 750.

##########################
# Development Stack
##########################
   LAMP (local)
   LNMP (remote*)
   Apache NetBeans IDE

# Server (Backend)
   Ubuntu Linux
   PHP8.1
   MySQL

# Client (Frontend/UI) 
   HTML5
   CSS3
   Javascript/ES6
   Bootstrap 5

##########################
# Web Server Setup
##########################
The project is currently hosted locally and remotely with SSL enabled and both servers are running on Ubuntu 20.04LTS VMs.

The remote* (deployment) uses NGINX with a virtual host/subdomain setup within my own Digital Ocean hosted domain.
   Let's Encrypt Trusted CA certificate (via CertBot)
   https://fifaworldcups.oncoto.app

The local (development) project is served by Apache2 with a virtual host setup and my own CA** and certificate.
   The CA and certificate were created with OpenSSL and the CA was imported into the browser pool of trusted CAs.
   https://fifaworldcups.cosc.okc

------------
* The remote version is using NGINX instead of Apache because I already have other projects using it and I have grown fond of the its power given the simplicity of its configuration.

** The bash script used to create my own CA that signs my certificate is included in the project (certificate_authority folder).
This CA can be imported into the browser's trusted CAs pool which successfully avoids any browser-related self-signed certificate issues.
------------

##########################
# MySQL Tables Structure
##########################

mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| fifaDB             |
| information_schema |
| mysql              |
| performance_schema |
| sys                |
+--------------------+
5 rows in set (0.00 sec)

mysql> desc worldcupmatches;
+---------------------+--------------+------+-----+---------+-------+
| Field               | Type         | Null | Key | Default | Extra |
+---------------------+--------------+------+-----+---------+-------+
| Year                | int          | YES  | MUL | NULL    |       |
| Datetime            | datetime     | YES  |     | NULL    |       |
| Stage               | varchar(30)  | YES  |     | NULL    |       |
| Stadium             | varchar(50)  | YES  |     | NULL    |       |
| City                | varchar(100) | YES  |     | NULL    |       |
| Home_Team_Goals     | int          | YES  |     | NULL    |       |
| Away_Team_Goals     | int          | YES  |     | NULL    |       |
| Win_Conditions      | varchar(100) | YES  |     | NULL    |       |
| Attendance          | int          | YES  |     | NULL    |       |
| Halftime_Home_Goals | int          | YES  |     | NULL    |       |
| Halftime_Away_Goals | int          | YES  |     | NULL    |       |
| Referee             | varchar(40)  | YES  |     | NULL    |       |
| Assistant1          | varchar(40)  | YES  |     | NULL    |       |
| Assistant2          | varchar(40)  | YES  |     | NULL    |       |
| RoundID             | int          | NO   | PRI | NULL    |       |
| MatchID             | int          | NO   | PRI | NULL    |       |
| Home_Team_Initials  | varchar(3)   | YES  |     | NULL    |       |
| Away_Team_Initials  | varchar(3)   | YES  |     | NULL    |       |
+---------------------+--------------+------+-----+---------+-------+
18 rows in set (0.00 sec)

mysql> desc worldcups;
+----------------+-------------+------+-----+---------+-------+
| Field          | Type        | Null | Key | Default | Extra |
+----------------+-------------+------+-----+---------+-------+
| Year           | int         | NO   | PRI | NULL    |       |
| Country        | varchar(20) | YES  |     | NULL    |       |
| Winner         | varchar(20) | YES  |     | NULL    |       |
| Runners        | varchar(20) | YES  |     | NULL    |       |
| Third          | varchar(20) | YES  |     | NULL    |       |
| Fourth         | varchar(20) | YES  |     | NULL    |       |
| GoalsScored    | int         | YES  |     | NULL    |       |
| QualifiedTeams | int         | YES  |     | NULL    |       |
| MatchesPlayed  | int         | YES  |     | NULL    |       |
| Attendance     | int         | YES  |     | NULL    |       |
+----------------+-------------+------+-----+---------+-------+
10 rows in set (0.01 sec)

mysql> desc countries;
+----------+-------------+------+-----+---------+-------+
| Field    | Type        | Null | Key | Default | Extra |
+----------+-------------+------+-----+---------+-------+
| initials | varchar(3)  | NO   | PRI | NULL    |       |
| name     | varchar(40) | YES  |     | NULL    |       |
+----------+-------------+------+-----+---------+-------+
2 rows in set (0.00 sec)

mysql> desc auth_users;
+----------+--------------+------+-----+---------+----------------+
| Field    | Type         | Null | Key | Default | Extra          |
+----------+--------------+------+-----+---------+----------------+
| id       | int          | NO   | PRI | NULL    | auto_increment |
| username | varchar(50)  | YES  |     | NULL    |                |
| fname    | varchar(50)  | YES  |     | NULL    |                |
| lname    | varchar(50)  | YES  |     | NULL    |                |
| password | varchar(100) | YES  |     | NULL    |                |
| email    | varchar(50)  | YES  |     | NULL    |                |
| country  | varchar(50)  | YES  |     | NULL    |                |
| date     | date         | YES  |     | NULL    |                |
+----------+--------------+------+-----+---------+----------------+
8 rows in set (0.00 sec)

####                                                                 ####
# Most of the data was imported from a dataset available at Kaggle.com  #                 
# which gathers official FIFA World Cup matches data from 1930 to 2014. #
# After having applied some normalization, the resulting tables now     #
# can be easily expanded/updated.                                       #
####                                                                 ####
