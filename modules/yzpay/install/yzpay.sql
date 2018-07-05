DROP TABLE IF EXISTS `phpcms_yzpay_create_qr`;
DROP TABLE IF EXISTS `phpcms_yzpay_notify_TRADE_ORDER_STATE`;
DROP TABLE IF EXISTS `phpcms_yzpay_trades`;
DROP TABLE IF EXISTS `phpcms_yzpay`;
CREATE TABLE `phpcms_yzpay_create_qr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qr_id` varchar(45) DEFAULT NULL,
  `qr_url` varchar(85) DEFAULT NULL,
  `qr_code` text,
  `qr_type` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `phpcms_yzpay_notify_TRADE_ORDER_STATE` (
  `id` varchar(55) CHARACTER SET latin1 NOT NULL,
  `client_id` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `kdt_id` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `kdt_name` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `mode` int(11) DEFAULT NULL,
  `msg` text CHARACTER SET latin1,
  `sign` varchar(55) CHARACTER SET latin1 DEFAULT NULL,
  `status` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `test` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `type` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `version` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpcms_yzpay_trades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(55) NOT NULL,
  `qr_id` varchar(45) NOT NULL,
  `book_date` varchar(45) DEFAULT NULL,
  `created_date` varchar(45) DEFAULT NULL,
  `outer_tid` varchar(45) DEFAULT NULL,
  `pay_date` varchar(45) DEFAULT NULL,
  `pay_type` varchar(45) DEFAULT NULL,
  `payer_nick` varchar(45) DEFAULT NULL,
  `qr_name` varchar(45) DEFAULT NULL,
  `qr_price` float DEFAULT NULL,
  `qr_url` varchar(245) DEFAULT NULL,
  `real_price` float DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `action` int(11) DEFAULT '0' COMMENT '订单是否处理 账号增加金钱',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `phpcms_yzpay` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `app_id` varchar(45) NOT NULL,
    `app_secret` varchar(45) NOT NULL,
    `kdt_id` varchar(45) NOT NULL,
    `qr_source` varchar(45) DEFAULT NULL,
    `pay_desc` varchar(45) DEFAULT '充值',
    PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


