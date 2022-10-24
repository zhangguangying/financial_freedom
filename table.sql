# 基金表
drop table if exists `fund`;
create table `fund`(
    `id` int unsigned not null auto_increment primary key,
    `code` char(6) not null default 0 comment '代码',
    `name` varchar(255) not null default '' comment '名称',
    `create_time` datetime default null comment '创建时间'
)engine=innodb charset=utf8 comment '基金表';
# 基金每日净值表
drop table if exists `fund_value`;
create table `fund_value`(
    `id` bigint unsigned not null auto_increment primary key,
    `fund_id` int unsigned not null default 0 comment '基金ID',
    `date` date default null comment '日期',
    `net_worth` decimal(10,4) not null default 0 comment '净值',
    unique idx_fund_id_date(fund_id, `date`)
)engine=innodb charset=utf8 comment '基金净值表';
# 持有表
drop table if exists `fund_owner`;
create table `fund_owner`(
    `id` int unsigned not null auto_increment primary key,
    `fund_id` int unsigned not null default 0 comment '基金ID',
    `own_amount` decimal(10, 4) not null default 0 comment '持有份额',
    `net_worth`  decimal(10, 4) not null default 0 comment '持有成本'
)engine=innodb charset=utf8 comment '基金持有表';
# 基金流水表
drop table if exists `fund_flow`;
create table `fund_flow`(
    `id` int unsigned not null auto_increment primary key,
    `fund_id` int unsigned not null default 0 comment '基金ID',
    `type` tinyint(1) not null default 0 comment '类型：1买入，2卖出',
    `amount` int unsigned not null default 0 comment '卖出份额',
    `net_worth` decimal(10, 4) not null default 0 comment '成交净值',
    `service_charge` decimal(10, 4) not null default 0 comment '手续费'
)engine=innodb charset=utf8 comment '基金流水表';
# 账户表
drop table if exists `account`;
create table `account`(
  `id` int unsigned not null auto_increment primary key,
  `name` varchar(20) not null default '' comment '账户名称',
  `type` tinyint unsigned not null default 1 comment '账户类型：1应急,2生活',
  `value` decimal(10, 2) not null default 0 comment '金额'
)engine=innodb charset=utf8 comment '账户表';
