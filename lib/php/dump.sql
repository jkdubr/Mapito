

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `contactId` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `www` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `cat` varchar(10) NOT NULL,
  `region` varchar(50) NOT NULL,
  `userId` int(8) NOT NULL,
  PRIMARY KEY (`contactId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE IF NOT EXISTS `form` (
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `txt` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `layerId` int(8) unsigned NOT NULL,
  `formId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `autoupdate` tinyint(1) NOT NULL,
  PRIMARY KEY (`formId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `form`
--


-- --------------------------------------------------------

--
-- Table structure for table `formData`
--

CREATE TABLE IF NOT EXISTS `formData` (
  `formId` int(8) NOT NULL,
  `userId` int(8) NOT NULL,
  `data` text NOT NULL,
  `formDataId` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`formDataId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `formData`
--


-- --------------------------------------------------------

--
-- Table structure for table `formElement`
--

CREATE TABLE IF NOT EXISTS `formElement` (
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `txt` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `formElementId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `elementSettings` text COLLATE utf8_czech_ci NOT NULL COMMENT 'htlm  elem pro nastavení pole',
  `supported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`formElementId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `formElement`
--


-- --------------------------------------------------------

--
-- Table structure for table `formItem`
--

CREATE TABLE IF NOT EXISTS `formItem` (
  `title` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `placeholder` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `default` varchar(50) NOT NULL,
  `required` tinyint(1) unsigned zerofill NOT NULL,
  `options` text NOT NULL,
  `formId` int(8) unsigned NOT NULL,
  `formItemId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`formItemId`),
  UNIQUE KEY `name` (`name`,`formId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `formItem`
--


-- --------------------------------------------------------

--
-- Table structure for table `layer`
--

CREATE TABLE IF NOT EXISTS `layer` (
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `namespace` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `format` varchar(30) COLLATE utf8_czech_ci NOT NULL DEFAULT 'image/jpeg' COMMENT 'napr: image/png8',
  `opacity` tinyint(1) NOT NULL DEFAULT '10' COMMENT 'Průhlednost (0=průhledné - není vidět)',
  `transparent` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'if layer is transparent',
  `palete` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `type` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `legendImageUrl` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `txt` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `isInDb` tinyint(4) NOT NULL,
  `layerPublicId` int(8) unsigned NOT NULL,
  `layerFolderId` int(8) unsigned NOT NULL,
  `planId` int(8) unsigned NOT NULL,
  `layerId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `rank` smallint(6) NOT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `queryable` tinyint(4) NOT NULL DEFAULT '1',
  `visibility` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'je viditelna na mape pri zobrazeni mapy',
  `layerStyleId` int(8) unsigned NOT NULL,
  `isInLegend` tinyint(4) NOT NULL DEFAULT '1',
  `printable` tinyint(4) NOT NULL DEFAULT '1',
  `new` tinyint(4) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isLocked` tinyint(1) NOT NULL,
  `isLockedForGeometry` tinyint(1) NOT NULL,
  `srs` varchar(15) COLLATE utf8_czech_ci NOT NULL DEFAULT 'EPSG:900913',
  PRIMARY KEY (`layerId`),
  UNIQUE KEY `name` (`planId`,`url`,`namespace`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=284 ;

--
-- Dumping data for table `layer`
--

INSERT INTO `layer` (`title`, `url`, `namespace`, `name`, `format`, `opacity`, `transparent`, `palete`, `type`, `legendImageUrl`, `txt`, `isInDb`, `layerPublicId`, `layerFolderId`, `planId`, `layerId`, `rank`, `private`, `queryable`, `visibility`, `layerStyleId`, `isInLegend`, `printable`, `new`, `isActive`, `isLocked`, `isLockedForGeometry`, `srs`) VALUES
('Basic map 1:50000', 'http://geoportal.cuzk.cz/WMS_ZM50_PUB/service.svc/get?', '', 'GR_ZM50', 'image/jpeg', 10, 1, '', '', '', '', 0, 0, 1, 1, 283, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 'EPSG:900913'),
('Basic map 1:10000', 'http://geoportal.cuzk.cz/WMS_ZM10_PUB/service.svc/get?', '', 'GR_ZM10', 'image/jpeg', 10, 1, '', '', '', '', 0, 0, 1, 1, 282, 2, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 'EPSG:900913'),
('Ortophoto', 'http://geoportal.cuzk.cz/WMS_ORTOFOTO_PUB/service.svc/get?', '', 'GR_ORTFOTORGB', 'image/jpeg', 10, 1, '', '', '', '', 0, 0, 1, 1, 281, 0, 0, 1, 1, 0, 1, 1, 0, 1, 0, 0, 'EPSG:900913'),
('Ortophoto', 'http://geoportal.cuzk.cz/WMS_ORTOFOTO_PUB/service.svc/get?', '', 'GR_ORTFOTORGB', 'image/jpeg', 10, 1, '', '', '', '', 0, 0, 0, 0, 278, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 'EPSG:900913'),
('Basic map 1:10000', 'http://geoportal.cuzk.cz/WMS_ZM10_PUB/service.svc/get?', '', 'GR_ZM10', 'image/jpeg', 10, 1, '', '', '', '', 0, 0, 0, 0, 279, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 'EPSG:900913'),
('Basic map 1:50000', 'http://geoportal.cuzk.cz/WMS_ZM50_PUB/service.svc/get?', '', 'GR_ZM50', 'image/jpeg', 10, 1, '', '', '', '', 0, 0, 0, 0, 280, 0, 0, 1, 0, 0, 1, 1, 0, 1, 0, 0, 'EPSG:900913');

-- --------------------------------------------------------

--
-- Table structure for table `layerCol`
--

CREATE TABLE IF NOT EXISTS `layerCol` (
  `name` varchar(30) NOT NULL,
  `type` varchar(20) NOT NULL,
  `length` smallint(5) NOT NULL,
  `title` varchar(30) NOT NULL,
  `desc` varchar(100) NOT NULL,
  `layerId` int(8) unsigned NOT NULL,
  `layerColId` bigint(16) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`layerColId`),
  UNIQUE KEY `layerId` (`layerId`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `layerCol`
--


-- --------------------------------------------------------

--
-- Table structure for table `layerFolder`
--

CREATE TABLE IF NOT EXISTS `layerFolder` (
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `txt` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `planId` int(8) unsigned NOT NULL,
  `layerFolderId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `basic` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'pokud 1 - slozka nelze smazat',
  PRIMARY KEY (`layerFolderId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `layerFolder`
--

INSERT INTO `layerFolder` (`title`, `txt`, `planId`, `layerFolderId`, `basic`) VALUES
('Basic', '', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `layerStyle`
--

CREATE TABLE IF NOT EXISTS `layerStyle` (
  `title` varchar(30) NOT NULL,
  `layerStyleId` int(8) NOT NULL AUTO_INCREMENT,
  `txt` text NOT NULL,
  `content` text NOT NULL,
  `contentFormated` text NOT NULL,
  `public` tinyint(1) NOT NULL,
  `userCreatorId` int(8) unsigned NOT NULL,
  `parentPublicLayer` int(8) unsigned NOT NULL,
  `preview` blob NOT NULL,
  PRIMARY KEY (`layerStyleId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `layerStyle`
--


-- --------------------------------------------------------

--
-- Table structure for table `layerWPS`
--

CREATE TABLE IF NOT EXISTS `layerWPS` (
  `layerId` int(9) unsigned NOT NULL,
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `layerWPS`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_login`
--

CREATE TABLE IF NOT EXISTS `log_login` (
  `userId` int(8) unsigned NOT NULL,
  `mail` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `varServer` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `log_login`
--


-- --------------------------------------------------------

--
-- Table structure for table `mobil_stat`
--

CREATE TABLE IF NOT EXISTS `mobil_stat` (
  `user` varchar(50) NOT NULL COMMENT 'unikátní identifikátor uživatele',
  `sys` varchar(20) NOT NULL COMMENT 'mobilní systém na uživ. zařízení',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'čas přístupu',
  `appl` varchar(20) NOT NULL COMMENT 'kód aplikace'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='V tabulce jsou uchovávány přístupy na API z mobilních zaříze';

--
-- Dumping data for table `mobil_stat`
--


-- --------------------------------------------------------

--
-- Table structure for table `modul`
--

CREATE TABLE IF NOT EXISTS `modul` (
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `modulId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`modulId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `modul`
--


-- --------------------------------------------------------

--
-- Table structure for table `modulinplan`
--

CREATE TABLE IF NOT EXISTS `modulinplan` (
  `modulId` int(8) unsigned NOT NULL,
  `planId` int(8) unsigned NOT NULL,
  PRIMARY KEY (`modulId`,`planId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `modulinplan`
--


-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE IF NOT EXISTS `plan` (
  `name` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `txt` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `ico` blob NOT NULL,
  `planId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `mapCenterLat` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `mapCenterLon` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `mapZoom` tinyint(2) unsigned NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`planId`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=44 ;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`name`, `txt`, `ico`, `planId`, `title`, `mapCenterLat`, `mapCenterLon`, `mapZoom`, `private`) VALUES
('example', '', '', 1, 'example', '6468836.46751', '1771155.83315', 14, 0);

-- --------------------------------------------------------

--
-- Table structure for table `privilege`
--

CREATE TABLE IF NOT EXISTS `privilege` (
  `userId` int(8) unsigned NOT NULL,
  `planId` int(8) unsigned NOT NULL,
  `privilege` smallint(6) NOT NULL,
  `dateFrom` date NOT NULL,
  `dateTo` date NOT NULL,
  PRIMARY KEY (`userId`,`planId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `privilege`
--

INSERT INTO `privilege` (`userId`, `planId`, `privilege`, `dateFrom`, `dateTo`) VALUES
(1, 1, 6, '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `tel` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `txt` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `avatar` blob NOT NULL,
  `userId` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `privilege` smallint(6) NOT NULL COMMENT 'privilegie vrámci celé aplikace',
  `hash` varchar(50) COLLATE utf8_czech_ci NOT NULL COMMENT 'každým přihlášením vzniká unikátní hash, každý odhlášením se hash maže. HASH je uložen v cookies prohlížeče',
  `superUserId` int(8) unsigned NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--


-- --------------------------------------------------------

--
-- Table structure for table `userreset`
--

CREATE TABLE IF NOT EXISTS `userreset` (
  `key` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userId` int(8) unsigned NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `userreset`
--

