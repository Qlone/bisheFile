/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2017/3/1 11:07:21                            */
/*==============================================================*/


drop index Index_userName on user;

drop table if exists user;

/*==============================================================*/
/* Table: user                                                  */
/*==============================================================*/
create table user
(
   userId               int not null auto_increment,
   userName             varchar(100),
   password             varchar(100),
   balance              float(10,2),
   userType             varchar(50),
   userStatus           varchar(50),
   primary key (userId)
);

/*==============================================================*/
/* Index: Index_userName                                        */
/*==============================================================*/
create unique index Index_userName on user
(
   userName
);

