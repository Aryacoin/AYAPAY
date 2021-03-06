

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Table structure's for AYAPAY data
--

CREATE TABLE `user_coin_address` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `coin_type` varchar(15) CHARACTER SET utf8 NOT NULL,
  `wallet_address` char(64) CHARACTER SET utf8 NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `user_coin_address`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_coin_address`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `ipn_notify` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `address` char(64) CHARACTER SET utf8 NOT NULL,
  `txid` char(64) CHARACTER SET utf8 NOT NULL,
  `coin_type` varchar(20) CHARACTER SET utf8 NOT NULL,
  `amount` decimal(20,8) NOT NULL,
  `confirm` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `status_text` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `ipn_notify`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `ipn_notify`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
