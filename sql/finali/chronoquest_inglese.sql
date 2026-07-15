-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Creato il: Lug 01, 2026 alle 12:24
-- Versione del server: 9.3.0
-- Versione PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chronobaseenglish`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_abilita`
--

CREATE TABLE `ct_abilita` (
  `id_abilita` int NOT NULL,
  `testo_abilita` varchar(400) NOT NULL,
  `tipologia` varchar(100) NOT NULL,
  `equipet` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_abilita`
--

INSERT INTO `ct_abilita` (`id_abilita`, `testo_abilita`, `tipologia`, `equipet`) VALUES
(1, '<i class=\"fa-solid fa-shield text-secondary fs-2\" style=\"margin-right:0.8rem; font-size:1.3rem\"></i> Defense', 'difesa', 0),
(2, '<i class=\"fa-solid fa-coins\" style=\"margin-right:0.8rem; font-size:1.3rem;color: #d4af37;text-shadow: 0 0 4px rgba(212, 175, 55, 0.5);\"></i> Coins earned:', 'monete', 0),
(3, '<i class=\"fa-solid fa-fire-flame-curved\" style=\"color: #2563eb;margin-right:0.8rem; font-size:1.3rem\"></i> Mana per level:', 'mana', 0),
(4, '<i class=\"fa-solid fa-book\" style=\"color: #2563eb;margin-right:0.8rem; font-size:1.3rem\"></i> XP increase:', 'esperienza', 0),
(5, '<i class=\"fa-solid fa-fire-flame-curved\" style=\"color: #2563eb;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#2563eb;\">Added mana:</span>', 'mana', 1),
(6, '<i class=\"fa-solid fa-heart\" style=\"color: #c22131;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#c22131;\">Added hearts:</span>', 'vita', 1),
(7, '<i class=\"fa-solid fa-book\" style=\"color: #139ca1;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#139ca1;\">Added XP:</span>', 'xp', 1),
(8, '<i class=\"fa-solid fa-coins\" style=\"color: #9e6508;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#9e6508;\">Coins added:</span>', 'monete', 1),
(9, '<i class=\"fa-solid fa-toolbox\" style=\"color: #96960e;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#96960e;\">Increased probability epic chest:</span>', 'probabilita_forziere_epico', 1),
(10, '<i class=\"fa-solid fa-burst\" style=\"color: #2ebd2b;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#2ebd2b;\">Increased probability of legendary chest:</span>', 'probabilita_forziere_leggendario', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_abilita_equipaggiamento`
--

CREATE TABLE `ct_abilita_equipaggiamento` (
  `id_abilita_equip` int NOT NULL,
  `fk_abilita` int NOT NULL,
  `fk_personalizzazione` int NOT NULL,
  `aumento` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_abilita_equipaggiamento`
--

INSERT INTO `ct_abilita_equipaggiamento` (`id_abilita_equip`, `fk_abilita`, `fk_personalizzazione`, `aumento`) VALUES
(1, 10, 139, 3),
(2, 5, 140, 1),
(3, 9, 141, 8),
(4, 6, 142, 2),
(5, 5, 143, 1),
(6, 5, 144, 2),
(7, 5, 145, 1),
(8, 7, 146, 50),
(9, 5, 147, 1),
(10, 9, 148, 4),
(11, 6, 149, 1),
(12, 10, 150, 3),
(13, 8, 151, 150),
(14, 9, 152, 4),
(15, 9, 153, 10),
(16, 10, 154, 6),
(17, 10, 155, 6),
(18, 5, 156, 1),
(19, 9, 157, 4),
(20, 8, 158, 75),
(21, 7, 159, 100),
(22, 6, 160, 1),
(23, 8, 161, 150),
(24, 8, 162, 75),
(25, 5, 163, 1),
(26, 9, 164, 4),
(27, 10, 165, 3),
(28, 5, 166, 2),
(29, 7, 167, 50),
(30, 9, 168, 10),
(31, 6, 169, 2),
(32, 10, 170, 6),
(33, 8, 171, 200),
(34, 7, 172, 150),
(35, 9, 173, 8),
(36, 10, 174, 9),
(37, 9, 175, 6),
(38, 10, 175, 3),
(39, 8, 175, 100),
(40, 8, 176, 200),
(41, 9, 177, 10),
(42, 10, 177, 8),
(43, 8, 177, 200),
(44, 6, 178, 1),
(45, 6, 179, 1),
(46, 6, 180, 2),
(47, 8, 181, 150),
(48, 7, 182, 100),
(49, 5, 183, 1),
(50, 10, 184, 9),
(51, 9, 185, 8),
(52, 10, 185, 6),
(53, 8, 185, 150),
(54, 8, 186, 250),
(55, 7, 187, 150),
(56, 7, 188, 100),
(57, 4, 189, 20),
(58, 2, 189, 10),
(59, 3, 189, 1),
(60, 4, 190, 20),
(61, 2, 190, 10),
(62, 3, 190, 1),
(63, 4, 191, 20),
(64, 2, 191, 10),
(65, 3, 191, 1),
(66, 4, 192, 20),
(67, 2, 192, 10),
(68, 3, 192, 1),
(69, 4, 193, 20),
(70, 2, 193, 10),
(71, 3, 193, 1),
(72, 4, 194, 20),
(73, 3, 194, 1),
(74, 2, 194, 10),
(75, 4, 195, 20),
(76, 2, 195, 10),
(77, 3, 195, 1),
(78, 4, 196, 20),
(79, 2, 197, 30),
(80, 2, 198, 10),
(81, 2, 199, 20),
(82, 4, 200, 30),
(83, 2, 200, 40),
(84, 3, 200, 1),
(85, 1, 201, 2),
(86, 2, 201, 20),
(87, 1, 202, 2),
(88, 2, 202, 20),
(89, 4, 202, 10),
(90, 1, 203, 3),
(91, 4, 203, 30),
(92, 2, 203, 30),
(93, 1, 204, 3),
(94, 1, 205, 3),
(95, 4, 205, 40),
(96, 1, 206, 1),
(97, 1, 207, 2),
(98, 3, 207, 2),
(99, 1, 208, 2),
(100, 4, 209, 30),
(101, 2, 209, 10),
(102, 1, 210, 3),
(103, 4, 210, 20),
(104, 2, 210, 30),
(105, 1, 211, 2),
(106, 4, 211, 20),
(107, 3, 211, 2),
(108, 2, 211, 20),
(109, 2, 212, 30),
(110, 4, 212, 20),
(111, 3, 213, 2),
(112, 3, 214, 1),
(113, 4, 215, 10),
(114, 3, 215, 2),
(115, 2, 215, 20),
(116, 3, 216, 2),
(117, 2, 216, 20),
(118, 3, 217, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_alerts`
--

CREATE TABLE `ct_alerts` (
  `id_alert` int NOT NULL,
  `fk_classe` int NOT NULL,
  `letto` int NOT NULL DEFAULT '0',
  `data_alert` datetime NOT NULL,
  `testo` text NOT NULL,
  `tipologia` varchar(100) NOT NULL,
  `link` varchar(200) NOT NULL,
  `doc_stud` int NOT NULL,
  `fk_studente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_anni_scolastici`
--

CREATE TABLE `ct_anni_scolastici` (
  `id_anno` int NOT NULL,
  `anno_scolastico` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_anni_scolastici`
--

INSERT INTO `ct_anni_scolastici` (`id_anno`, `anno_scolastico`) VALUES
(1, '2025/2026'),
(2, '2026/2027'),
(3, '2027/2028'),
(4, '2028/2029'),
(5, '2029/2030'),
(6, '2030/2031'),
(7, '2031/2032'),
(8, '2032/2033'),
(9, '2033/2034'),
(10, '2034/2035');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_argomenti`
--

CREATE TABLE `ct_argomenti` (
  `id_argomento` int NOT NULL,
  `nome_argomento` varchar(200) NOT NULL,
  `fk_materia` int NOT NULL,
  `uuid` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_argomenti`
--

INSERT INTO `ct_argomenti` (`id_argomento`, `nome_argomento`, `fk_materia`, `uuid`) VALUES
(1, 'Relational Algebra in Databases', 4, '827849cd-3ed1-4fb8-bec9-bb7e6d4ea440'),
(2, 'Search and Sorting Algorithms', 4, '9e8585f2-930c-4d48-ba5e-25d971c8af50'),
(3, 'Bootstrap, a Web Library', 4, 'ad3b9953-6cc0-43d4-90f1-f4400661185c'),
(4, 'NoSQL Databases', 4, '3c90a610-09b4-408d-8bf3-53b579b80865'),
(5, 'UML Class Diagrams', 4, '6e8cc08b-0124-4467-94ff-5512ad5ebecc'),
(6, 'Flowcharts', 4, 'a0350e43-5d56-4285-a4af-9c995bd8fb84'),
(7, 'ER Diagrams for Database Design', 4, '8bf0f1fd-5f7a-469f-aa97-357bec93a164'),
(8, 'Memory Management in Object-Oriented Languages', 4, 'ff9d2e06-c751-42a3-bb96-3078cec0ce91'),
(9, 'HTML and CSS', 4, '76d949e5-fbfc-4763-b7b2-b9bb9655ea72'),
(10, 'Introduction to Python', 4, 'c93bad1a-11a6-4dbc-94a5-0165744a0b3f'),
(11, 'Introduction to Databases', 4, '67139cea-162d-41a8-8bf3-47040bc6443e'),
(12, 'Introduction to Programming', 4, '865dbe5f-db4c-4747-b1d3-1c790e0e23b5'),
(13, 'Java Dynamic Arrays', 4, '60f1fb7f-07d9-41f2-963d-6262f302f1c5'),
(14, 'Java Basics', 4, 'e5a2f8a9-fb2d-4763-93d6-553d5c77cb04'),
(15, 'Java Classes', 4, '8ac2fcd2-028a-4a29-ab40-56c2a34c78f9'),
(16, 'Java Inheritance', 4, '4294d316-177f-4c5b-b740-8ba50f42f913'),
(17, 'Java File Handling', 4, 'f70ed098-3c4c-4133-8147-c903e4d2052e'),
(18, 'Compiled and Interpreted Languages', 4, '04106547-4bcf-4f6d-9d5f-b87cb179a825'),
(19, 'PHP: Database Connections and Queries', 4, 'b5f8fd84-9a61-4d67-bf4a-d6d886c4bc24'),
(20, 'PHP: Cookies and Sessions', 4, '7ad89e5f-6d72-41e2-b561-6c2e412b2d1d'),
(21, 'PHP: Form Data', 4, '7c0e8627-be57-4c70-a4dc-d853b41396b0'),
(22, 'PHP: Introduction, Variables, Functions, Arrays', 4, 'dc07ee51-ac2a-428e-8ac0-4afdd50474ea'),
(23, 'PHP: File Handling', 4, '5c9ed381-4cbb-4481-b028-e353b9b811af'),
(24, 'Database Design', 4, '346c856e-9bf5-4efc-bb9d-6645bd8685cc'),
(25, 'Object-Oriented Programming', 4, 'e524e118-cc02-43b6-bd44-8a13abe342d8'),
(26, 'Python Classes', 4, '931b6567-5de2-4287-a138-1c3da7aed6df'),
(27, 'Python Files', 4, 'd2f1e6fa-fd1b-4658-bc36-6e6d69d9269e'),
(28, 'Python Functions and Modules', 4, '1e836aa0-7ddb-4eb2-aa9c-97670e082636'),
(29, 'Python Data Structures (Lists, Sets, Dictionaries)', 4, 'd7f3a30e-373f-48ab-b769-f6f9374ddc52'),
(30, 'Python: Structured Programming', 4, 'd4084a08-e505-4958-b772-0c69227e1773'),
(31, 'Recursion', 4, '223315ff-8271-4649-954a-02cd9368aa07'),
(32, 'SQL DDL and DML', 4, '981f6158-86ba-4814-ab7a-9348701a36cc'),
(33, 'SQL Select', 4, 'c48fd5a1-221f-49bb-8b2b-f61606b6ab7b'),
(34, 'Advanced Data Structures', 4, 'ade4fd70-76bf-4ca4-9e28-5b8e4e03db33');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_argomenti_kahoot`
--

CREATE TABLE `ct_argomenti_kahoot` (
  `id_arg_kahoot` int NOT NULL,
  `fk_argomento` int NOT NULL,
  `fk_utente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_badge`
--

CREATE TABLE `ct_badge` (
  `id_badge` int NOT NULL,
  `nome_badge` varchar(200) NOT NULL,
  `img_badge` varchar(200) NOT NULL,
  `descrizione` text NOT NULL,
  `fk_utente_creatore` int NOT NULL,
  `fk_argomento` int NOT NULL,
  `num_esercizi` int NOT NULL,
  `media_minima` double NOT NULL,
  `sesso` varchar(2) NOT NULL DEFAULT 'U'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_badge`
--

INSERT INTO `ct_badge` (`id_badge`, `nome_badge`, `img_badge`, `descrizione`, `fk_utente_creatore`, `fk_argomento`, `num_esercizi`, `media_minima`, `sesso`) VALUES
(1, 'Apprentice Kitten', '/assets/images/Badge/imported_badge_6a44ffe30c1e63.29348004_badge_asset_6a2e66047a4cf4.84307188_badge_6a2e5d081e1878.35797338_gattino_badge.png', 'This badge for achieving sufficient proficiency in building PHP pages with basic elements', 1, 22, 6, 6, 'U'),
(2, 'Biplane Python Basics', '/assets/images/Badge/imported_badge_6a44ffe3128e13.26353788_badge_asset_6a2e66048e7537.13501971_badge_6a2e5d3cb3e808.72204091_badge_biplano.png', 'You have the speed and agility of a biplane in mastering the basic elements of Python: selection, loops and input output are mastered with sufficient skill', 1, 30, 6, 6, 'U'),
(3, 'Black Hawk Python Base', '/assets/images/Badge/imported_badge_6a44ffe31784f2.90329540_badge_asset_6a2e660498e262.67879568_badge_6a2e5d664fee52.17343019_badge_black_hawk.png', 'You have the speed and power of a Black Hawk helicopter to master the basic elements of Python: selection, loops and input output are overcome with reasonable skill', 1, 30, 6, 7, 'U'),
(4, 'Bonsai Python Functions', '/assets/images/Badge/imported_badge_6a44ffe31bae29.62221332_badge_asset_6a2e6604a26124.90126598_badge_6a2e5d8e15bb86.38510066_BonsaiFunzioni.png', 'This Badge for having reached a sufficiently good level in creating functions with the Python language', 1, 28, 6, 6, 'U'),
(5, 'Brachiosaurus Data Structures Python', '/assets/images/Badge/imported_badge_6a44ffe3225083.87907971_badge_asset_6a2e6604bb0c67.46044648_badge_6a2e5dbf277ae4.72711264_badge_brachiosauro_strutture.png', 'This badge for having reached a sufficient level in the use of the various data structures in Python: lists, matrices, sets, tuples, dictionaries', 1, 29, 6, 6, 'U'),
(6, 'Python Base Hunting', '/assets/images/Badge/imported_badge_6a44ffe3266f05.64362123_badge_asset_6a2e6604c63f00.36702772_badge_6a2e5de891dbc0.65213559_badge_caccia.png', 'You have the speed and agility of an F14 fighter at the basics of Python: selection, looping and input output are overcome with good skills', 1, 30, 6, 8, 'U'),
(7, 'Dog Hero SQL', '/assets/images/Badge/imported_badge_6a44ffe32afea8.85103867_badge_asset_6a2e6604d020d9.08835915_badge_6a2e5e17c34c04.20749485_cane_eroe_badge.png', 'The badge is earned by heroes who master SQL well to create database queries that extract data in an aggregate manner', 1, 33, 6, 8, 'U'),
(8, 'Dog Scout Adventurer SQL', '/assets/images/Badge/imported_badge_6a44ffe3310a57.86157958_badge_asset_6a2e6604e4fff3.05385145_badge_6a2e5e4b3d3043.70790172_scout_sql_badge.png', 'The badge is earned by heroes who discreetly master SQL to create database queries that extract data in a structured manner', 1, 33, 6, 7, 'U'),
(9, 'Intro Python Knight', '/assets/images/Badge/imported_badge_6a44ffe33706a3.99586801_badge_asset_6a2e66050620e2.13713382_badge_6a2e5e7a7e12c3.46084011_badge_cavaliere_intro_python.jpg', 'This badge is earned when a good preparation is achieved in the introductory elements of the Python language', 1, 10, 3, 7, 'U'),
(10, 'Olympic Champion Bunny PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe33a5197.30249931_badge_asset_6a2e66050a3455.64195755_badge_6a2e5ead7025b9.56329483_coniglietta_olimpica_badge.png', 'This badge is earned if you reach an Olympic level in using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 9, 'F'),
(11, 'Bunny training PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe3408412.33863618_badge_asset_6a2e66051f5783.55925700_badge_6a2e5edec09da8.39502029_coniglietta_all_badge.png', 'This badge is earned if you reach a basic level of using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 6, 'F'),
(12, 'Skater Bunny PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe345ef80.16743988_badge_asset_6a2e660532ceb4.75376299_badge_6a2e5f0a323332.39390971_coniglietta_pattinibadge.png', 'This badge is earned if you reach an average level in using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 7, 'F'),
(13, 'Tennis Bunny PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe34be5e4.21898316_badge_asset_6a2e6605471d90.91583498_badge_6a2e5f3f0db7d6.63667721_coniglietta_tennista_badge.png', 'This badge is earned if you reach an advanced level in using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 8, 'F'),
(14, 'Curious puppy SQL', '/assets/images/Badge/imported_badge_6a44ffe351d537.61711630_badge_asset_6a2e66055d4992.01762437_badge_6a2e5f66023fc1.98034780_cucciolo_sql_badge.png', 'The badge is earned by heroes who master SQL well enough to create database queries that extract data easily', 1, 33, 6, 6, 'U'),
(15, 'Ghost Galleon UML', '/assets/images/Badge/imported_badge_6a44ffe3586464.99888992_badge_asset_6a2e6605714bf9.86267030_badge_6a2e5f9a7a0265.28677427_galeone_cavaliere_intro_python.png', 'This badge is earned by heroes who know how to design relational databases using UML class diagrams at a medium level', 1, 5, 6, 7, 'U'),
(16, 'Ninja Cat', '/assets/images/Badge/imported_badge_6a44ffe35db218.48073564_badge_asset_6a2e66057be515.89894532_badge_6a2e5fc86c4858.41792173_gatto_ninja_badge.png', 'This badge for achieving medium-level proficiency in building PHP pages with basic elements', 1, 22, 6, 7, 'U'),
(17, 'Samurai cat', '/assets/images/Badge/imported_badge_6a44ffe3639959.16980847_badge_asset_6a2e66058c1f20.89826331_badge_6a2e5ff3bca050.82568939_gatto_samurai_badge.png', 'This badge for having achieved good proficiency in creating PHP pages with basic elements', 1, 22, 6, 8, 'U'),
(18, 'Supreme Sensei Cat', '/assets/images/Badge/imported_badge_6a44ffe3690e21.21160001_badge_asset_6a2e6605a233c9.28461857_badge_6a2e6018d98783.56322306_gatto_sensei_badge.png', 'This badge for having achieved excellent competence in creating PHP pages with basic elements', 1, 22, 6, 9, 'U'),
(19, 'Ipno Penguin Form PHP', '/assets/images/Badge/imported_badge_6a44ffe36f4a06.56264438_badge_asset_6a2e6605b7c380.23540318_badge_6a2e6043459f17.90683084_ipno_pinguino_badge.png', 'The badge for those who acquire a medium level of skills in using Forms to pass data in GET and POST to PHP', 1, 21, 5, 7, 'U'),
(20, 'Legend Algorithms', '/assets/images/Badge/imported_badge_6a44ffe3758f82.16900428_badge_asset_6a2e6605ca6c47.87885848_badge_6a2e6071bbdb84.82622816_lvl4.jpg', 'This badge is awarded for achieving excellent skills in algorithm programming, which classifies as a legend of basic programming', 1, 6, 6, 9, 'U'),
(21, 'SQL Canine Legend', '/assets/images/Badge/imported_badge_6a44ffe37a1df8.45995924_badge_asset_6a2e6605ce32e6.95710190_badge_6a2e609a920a20.90456687_leggenda_canina_badge.png', 'The badge is earned by heroes who master SQL well to create database queries that extract data of any type and in any way', 1, 33, 6, 9, 'U'),
(22, 'Jedi Master Python Files', '/assets/images/Badge/imported_badge_6a44ffe384f270.41513083_badge_asset_6a2e6605e35657.53077228_badge_6a2e60c0ceda01.00642967_badge_maestro_file.png', 'This badge for having reached a good level in using files in Python', 1, 27, 6, 8, 'U'),
(23, 'Master Yoda Python Files', '/assets/images/Badge/imported_badge_6a44ffe38d58d1.39717792_badge_asset_6a2e6605ecf290.19432293_badge_6a2e60e3e989c4.79063918_badge_yoda_file.png', 'This badge for having reached an excellent level in using files in Python', 1, 27, 6, 9, 'U'),
(24, 'Cruise Ship Database Design', '/assets/images/Badge/imported_badge_6a44ffe3935972.10654254_badge_asset_6a2e660602ef18.29404582_badge_6a2e6115516be8.80911507_badge_crociera.png', 'This badge is earned by heroes who know how to design relational databases using UML class diagrams at a very good level', 1, 5, 6, 8, 'U'),
(25, 'Normal Panda PHP Sessions', '/assets/images/Badge/imported_badge_6a44ffe3988670.13699810_badge_asset_6a2e66060ca5e0.43400584_badge_6a2e61418b2c45.71704444_normal_panda_badge.png', 'This badge is earned by heroes who know how to handle sessions and cookies in PHP at a basic level', 1, 20, 5, 6, 'U'),
(26, 'Padawan Python Files', '/assets/images/Badge/imported_badge_6a44ffe3a060e6.28504061_badge_asset_6a2e660622da66.13054504_badge_6a2e615fcec0c7.13094459_badge_padawan_file.png', 'This badge for having reached a sufficient level in using files in Python', 1, 27, 6, 6, 'U'),
(27, 'Panda Super Sayian God PHP Sessions', '/assets/images/Badge/imported_badge_6a44ffe3a58a20.27709149_badge_asset_6a2e66062c2cb2.37659263_badge_6a2e619395db81.44777352_panda_ssg_badge.png', 'This badge is obtained by heroes who know how to juggle sessions and cookies in PHP at a level that we could define as almost divine', 1, 20, 6, 9, 'U'),
(28, 'Panda Super Sayian Level 3 PHP Sessions', '/assets/images/Badge/imported_badge_6a44ffe3ad5805.15790082_badge_asset_6a2e66064536a6.60277168_badge_6a2e61c4caebb9.15205143_panda_supers3_badge.png', 'This badge is earned by heroes who can master sessions and cookies in PHP at an advanced level', 1, 20, 5, 8, 'U'),
(29, 'Panda Super Sayian PHP Sessions', '/assets/images/Badge/imported_badge_6a44ffe3b57ec5.12854178_badge_asset_6a2e66065d5c66.72915011_badge_6a2e61ea946484.68694146_panda_supers_badge.png', 'This badge is earned by heroes who can handle sessions and cookies in PHP at an average level', 1, 20, 5, 7, 'U'),
(30, 'X-Wing Pilot Python Files', '/assets/images/Badge/imported_badge_6a44ffe3bcef15.50637796_badge_asset_6a2e660675bb38.79689560_badge_6a2e620ad95b10.48873810_badge_pilota_file.png', 'This badge for achieving a decent level of using files in Python', 1, 27, 6, 7, 'U'),
(31, 'Lethal Baby Penguin Form PHP', '/assets/images/Badge/imported_badge_6a44ffe3c24950.94208991_badge_asset_6a2e66067e9698.87270134_badge_6a2e6248b9adc4.36215651_pinguino_letale_badge.png', 'The badge for those who acquire a basic level of skills in using Forms to pass data in GET and POST to PHP', 1, 21, 5, 6, 'U'),
(32, 'Penguin Nuclear Fury Form PHP', '/assets/images/Badge/imported_badge_6a44ffe3cad2a8.03403964_badge_asset_6a2e660693f164.31039653_badge_6a2e626dc505f6.42331427_furia_nucleare_badge.png', 'The badge for those who acquire an excellent level of skills in using Forms to pass data in GET and POST to PHP', 1, 21, 5, 9, 'U'),
(33, 'Rambo Penguin Form PHP', '/assets/images/Badge/imported_badge_6a44ffe3d1cbb5.71752346_badge_asset_6a2e6606a82165.34568349_badge_6a2e62ad00ab84.58152026_pinguino_rambo_badge.png', 'The badge for those who acquire an advanced level of skills in using Forms to pass GET and POST data to PHP', 1, 21, 5, 8, 'U'),
(34, 'Pino Python Functions', '/assets/images/Badge/imported_badge_6a44ffe3d97155.43102746_badge_asset_6a2e6606bdac51.01538766_badge_6a2e62d4694539.87492917_PinoFunzioni.png', 'This Badge for having achieved a reasonably good level in creating functions with Python', 1, 28, 6, 7, 'U'),
(35, 'Aircraft Carrier Design Database', '/assets/images/Badge/imported_badge_6a44ffe3e133d4.88355161_badge_asset_6a2e6606d65161.50970110_badge_6a2e62fc0b5e80.74112074_badge_portaaerei.png', 'This badge is earned by heroes who know how to design relational databases at the highest level via UML class diagram, with detailed analyzes and excellent classes', 1, 5, 6, 9, 'U'),
(36, 'Beginner Algorithms', '/assets/images/Badge/imported_badge_6a44ffe3e613d1.12747489_badge_asset_6a2e6606e03248.03576881_badge_6a2e631cb35390.53653140_lvl2.jpg', 'This badge is awarded for achieving a medium level in algorithm programming', 1, 6, 6, 7, 'U'),
(37, 'Oak Python Functions', '/assets/images/Badge/imported_badge_6a44ffe3e9fcd3.36581873_badge_asset_6a2e6606e3cff0.70569624_badge_6a2e6339eb4b31.83856025_QuerciaFunzioni.png', 'This Badge for having reached a good level in creating functions with the Python language', 1, 28, 6, 8, 'U'),
(38, 'Rookie Algorithms', '/assets/images/Badge/imported_badge_6a44ffe3f06325.97087606_badge_asset_6a2e6607062d15.14039250_badge_6a2e635866efb1.39905793_lvl1.jpg', 'This badge is awarded for achieving basic skills in algorithm programming', 1, 6, 6, 6, 'U'),
(39, 'Samurai Intro Python', '/assets/images/Badge/imported_badge_6a44ffe3f3d342.60604849_badge_asset_6a2e6607096238.33491818_badge_6a2e638423dd21.93377588_badge_samurai_intro_python.jpg', 'This badge is earned when you have an excellent understanding of the introductory elements of the Python language', 1, 10, 3, 9, 'U'),
(40, 'Squire Intro to Python', '/assets/images/Badge/imported_badge_6a44ffe4037916.75447842_badge_asset_6a2e66070d2a35.05050511_badge_6a2e63a9c25090.79786715_badge_scudiero_intro_python.jpg', 'The badge is awarded for reaching a sufficient level in the introductory exercises to the Python language', 1, 10, 6, 6, 'U'),
(41, 'Sequoia Python Functions', '/assets/images/Badge/imported_badge_6a44ffe4072881.97255617_badge_asset_6a2e66071128e5.77511948_badge_6a2e63d222fc40.46758027_SequoiaFunzioni.png', 'This Badge for having reached an excellent level in creating functions using Python', 1, 28, 6, 9, 'U'),
(42, 'Stealth Python Base', '/assets/images/Badge/imported_badge_6a44ffe40d5b13.07996819_badge_asset_6a2e66072856d3.04541698_badge_6a2e63f0dbfa00.53325191_badge_stealth.png', 'You have the incredible speed and agility of a stealth fighter that breaks the sound barrier when juggling the basic elements of Python: selection, looping and input output are mastered seamlessly with excellent skills', 1, 30, 6, 9, 'U'),
(43, 'T-Rex Python Data Structures', '/assets/images/Badge/imported_badge_6a44ffe411baf9.03879958_badge_asset_6a2e6607318fb2.55862874_badge_6a2e6410b08862.32061254_badge_trex.png', 'This badge for having reached an excellent level in the use of the various data structures in Python: lists, matrices, sets, tuples, dictionaries', 1, 29, 6, 9, 'U'),
(44, 'Little Tiger Student PHP Files', '/assets/images/Badge/imported_badge_6a44ffe4168cb4.66353451_badge_asset_6a2e66073cb8a6.22882130_badge_6a2e6438017770.68038240_tigrotto_alunno_badge.png', 'This Badge is only earned by PHP scholars who have reached a basic level of programming using files within PHP pages', 1, 23, 4, 6, 'U'),
(45, 'Tiger Librarian PHP Files', '/assets/images/Badge/imported_badge_6a44ffe41cdb49.39655526_badge_asset_6a2e66075373d1.33581777_badge_6a2e64617fcaf0.12530981_tigrotto_biblioteca_badge.png', 'This Badge is only earned by PHP scholars who have reached an average level of programming using files within PHP pages', 1, 23, 4, 7, 'U'),
(46, 'Little Tiger Indiana Jones PHP File', '/assets/images/Badge/imported_badge_6a44ffe422f228.38607870_badge_asset_6a2e66076a0ed8.61437334_badge_6a2e649224fcc8.98774030_tigrotto_indiana_badge.png', 'This Badge is only earned by PHP scholars who have reached an epic level of programming using files within PHP pages', 1, 17, 4, 9, 'U'),
(47, 'Little Tiger Teacher PHP File', '/assets/images/Badge/imported_badge_6a44ffe42a8194.43301163_badge_asset_6a2e66077e32e2.45984556_badge_6a2e64b0dbeb34.04670147_tigrotto_prof_badge.png', 'This Badge is earned only by PHP scholars who have reached an advanced level of programming using files within PHP pages', 1, 23, 4, 8, 'U'),
(48, 'Triceratops Python data structures', '/assets/images/Badge/imported_badge_6a44ffe43025b0.32588925_badge_asset_6a2e66078ead33.98346801_badge_6a2e64d4bbe3f1.89875936_badge_triceratopo.png', 'This badge for having reached an average level in the use of the various data structures in Python: lists, matrices, sets, tuples, dictionaries', 1, 29, 6, 7, 'U'),
(49, 'Walrus Footballer PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe434b5c6.98994797_badge_asset_6a2e660796f9d1.39898850_badge_6a2e650c53d5a1.23893217_tricheco_calciatorebadge.png', 'This badge is earned if you reach an average level in using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 7, 'M'),
(50, 'Walrus Olympic Champion PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe43a3935.03797242_badge_asset_6a2e6607a65ae3.39061215_badge_6a2e6536abef72.52323726_tricheco_olimpico_badge.png', 'This badge is earned if you reach an Olympic level in using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 9, 'M'),
(51, 'Walrus training PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe43fd5a4.46667280_badge_asset_6a2e6607b5a599.20893791_badge_6a2e655e92e393.05552124_tricheco_allenamento_badge.png', 'This badge is earned if you reach a basic level of using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 6, 'M'),
(52, 'Walrus Boxer PHP and MySQL', '/assets/images/Badge/imported_badge_6a44ffe4464f53.77649998_badge_asset_6a2e6607c49732.49281542_badge_6a2e65889c7092.21286410_tricheco_pugile_badge.png', 'This badge is earned if you reach an advanced level in using PHP to connect to SQL databases, launching various queries in DML or QL language', 1, 19, 5, 8, 'M'),
(53, 'Velociraptor Python Data Structures', '/assets/images/Badge/imported_badge_6a44ffe44c5cc7.60793746_badge_asset_6a2e6607d3ac59.12227007_badge_6a2e65b418f559.81751145_badge_raptor_strutture.png', 'This badge for having reached a good level in the use of the various data structures in Python: lists, matrices, sets, tuples, dictionaries', 1, 29, 6, 8, 'U'),
(54, 'Veteran Algorithms', '/assets/images/Badge/imported_badge_6a44ffe4515026.50264970_badge_asset_6a2e6607dca8d9.81664648_badge_6a2e65d1df2fe8.96702317_lvl3.jpg', 'This badge is awarded for achieving very good proficiency in algorithm programming', 1, 6, 6, 8, 'U'),
(55, 'Raft Database design', '/assets/images/Badge/imported_badge_6a44ffe455a839.03378163_badge_asset_6a2e6607df8ed0.10928008_badge_6a2e65ebf13a43.96924873_badge_zattera_progttazione.png', 'This badge is earned by heroes who know how to design relational databases using UML class diagrams at a basic level', 1, 5, 6, 6, 'U');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_badge_alunni`
--

CREATE TABLE `ct_badge_alunni` (
  `id_badge_alunno` int NOT NULL,
  `fk_utente` int NOT NULL,
  `fk_badge` int NOT NULL,
  `data_conquista` datetime DEFAULT NULL,
  `visto` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_capitoli`
--

CREATE TABLE `ct_capitoli` (
  `id_capitolo` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `nome_capitolo` varchar(150) NOT NULL,
  `coord_x` int NOT NULL,
  `coord_y` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_capitoli`
--

INSERT INTO `ct_capitoli` (`id_capitolo`, `uuid`, `nome_capitolo`, `coord_x`, `coord_y`) VALUES
(8, '4bf46763-faee-4c21-8665-baf06a4f8964', 'The Creator', 508, 313),
(9, '56ab989e-0aa1-4a81-ae40-19144a1dbb97', 'The search for neutral heroes', 523, 231),
(10, 'cc0e2e49-4ec8-40b1-9913-20a0deba7826', 'The First Step', 476, 208),
(11, 'b59089a8-9877-457e-bba0-7e5c035f15f6', 'The Ivory Arch', 448, 172),
(12, '39924b08-361b-42f8-9fab-db99d4eb92b7', 'The Spear of Destiny', 518, 177),
(13, '22c99711-d248-4b38-b0ed-d228de35ab40', 'The Flaming Sword', 365, 554),
(14, '8a615ef7-ee03-4805-8058-887600575a2d', 'The Golden Shield', 279, 407),
(15, '6c258bcc-5f18-4acb-9615-30c6f44a142d', 'The Hammer of Eden', 160, 443),
(16, '034049c8-3776-4d12-b71a-b9e1f8be8ce9', 'In difficulty', 884, 440),
(17, 'f9a45119-215f-4231-9b11-3b701e77b9f8', 'The Earthsplitting Axe', 740, 116),
(18, 'cae56305-bf81-4142-b7a1-926b278f4e30', 'The Final Clash', 121, 152);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_capitoli_quest`
--

CREATE TABLE `ct_capitoli_quest` (
  `id_cap_quest` int NOT NULL,
  `fk_quest` int NOT NULL,
  `fk_capitolo` int NOT NULL,
  `progressivo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_capitoli_quest`
--

INSERT INTO `ct_capitoli_quest` (`id_cap_quest`, `fk_quest`, `fk_capitolo`, `progressivo`) VALUES
(8, 5, 8, 1),
(9, 5, 9, 2),
(10, 5, 10, 3),
(11, 5, 11, 4),
(12, 5, 12, 5),
(13, 5, 13, 6),
(14, 5, 14, 7),
(15, 5, 15, 8),
(16, 5, 16, 9),
(17, 5, 17, 10),
(18, 5, 18, 11);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_classi`
--

CREATE TABLE `ct_classi` (
  `id_classe` int NOT NULL,
  `nome_classe` varchar(100) NOT NULL,
  `fk_anno_scolastico` int NOT NULL,
  `colore` varchar(20) NOT NULL DEFAULT '#ffffff',
  `icona` varchar(30) NOT NULL,
  `eliminata` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_classi`
--

INSERT INTO `ct_classi` (`id_classe`, `nome_classe`, `fk_anno_scolastico`, `colore`, `icona`, `eliminata`) VALUES
(1, 'Test Class', 1, '#0d6efd', 'fa-bomb', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_classi_esercizi_attivi`
--

CREATE TABLE `ct_classi_esercizi_attivi` (
  `id_attivi` int NOT NULL,
  `fk_classe` int NOT NULL,
  `fk_capitolo` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `attivo` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_classi_esercizi_attivi`
--

INSERT INTO `ct_classi_esercizi_attivi` (`id_attivi`, `fk_classe`, `fk_capitolo`, `fk_esercizio`, `attivo`) VALUES
(1, 1, 8, 1, 0),
(2, 1, 8, 2, 0),
(3, 1, 8, 3, 0),
(4, 1, 8, 4, 0),
(5, 1, 8, 5, 0),
(6, 1, 8, 6, 0),
(7, 1, 8, 7, 0),
(8, 1, 8, 8, 0),
(9, 1, 9, 9, 0),
(10, 1, 9, 10, 0),
(11, 1, 9, 11, 0),
(12, 1, 9, 12, 0),
(13, 1, 9, 13, 0),
(14, 1, 10, 14, 0),
(15, 1, 11, 15, 0),
(16, 1, 11, 16, 0),
(17, 1, 11, 17, 0),
(18, 1, 11, 18, 0),
(19, 1, 12, 19, 0),
(20, 1, 12, 20, 0),
(21, 1, 12, 21, 0),
(22, 1, 12, 22, 0),
(23, 1, 12, 23, 0),
(24, 1, 12, 24, 0),
(25, 1, 12, 25, 0),
(26, 1, 12, 26, 0),
(27, 1, 12, 27, 0),
(28, 1, 13, 28, 0),
(29, 1, 13, 29, 0),
(30, 1, 13, 30, 0),
(31, 1, 13, 31, 0),
(32, 1, 13, 32, 0),
(33, 1, 13, 33, 0),
(34, 1, 13, 34, 0),
(35, 1, 13, 35, 0),
(36, 1, 14, 36, 0),
(37, 1, 14, 37, 0),
(38, 1, 14, 38, 0),
(39, 1, 14, 39, 0),
(40, 1, 14, 40, 0),
(41, 1, 14, 41, 0),
(42, 1, 14, 42, 0),
(43, 1, 14, 43, 0),
(44, 1, 15, 44, 0),
(45, 1, 15, 45, 0),
(46, 1, 15, 46, 0),
(47, 1, 15, 47, 0),
(48, 1, 15, 48, 0),
(49, 1, 15, 49, 0),
(50, 1, 15, 50, 0),
(51, 1, 15, 51, 0),
(52, 1, 15, 52, 0),
(53, 1, 15, 53, 0),
(54, 1, 16, 54, 0),
(55, 1, 16, 55, 0),
(56, 1, 16, 56, 0),
(57, 1, 17, 57, 0),
(58, 1, 17, 58, 0),
(59, 1, 17, 59, 0),
(60, 1, 17, 60, 0),
(61, 1, 17, 61, 0),
(62, 1, 17, 62, 0),
(63, 1, 18, 63, 0),
(64, 1, 18, 64, 0),
(65, 1, 18, 65, 0),
(66, 1, 18, 66, 0),
(67, 1, 18, 67, 0),
(68, 1, 18, 68, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_classi_quest`
--

CREATE TABLE `ct_classi_quest` (
  `id_classe_quest` int NOT NULL,
  `fk_classe` int NOT NULL,
  `fk_quest` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_classi_quest`
--

INSERT INTO `ct_classi_quest` (`id_classe_quest`, `fk_classe`, `fk_quest`) VALUES
(5, 1, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_consegne_studenti`
--

CREATE TABLE `ct_consegne_studenti` (
  `id_consegna` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `valutazione` int NOT NULL DEFAULT '0',
  `valutato` int NOT NULL DEFAULT '0',
  `file_consegnato` varchar(200) DEFAULT NULL,
  `data_consegna` datetime DEFAULT NULL,
  `problema` int DEFAULT NULL,
  `descrizione_problema` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_domande`
--

CREATE TABLE `ct_domande` (
  `id_domanda` int NOT NULL,
  `domanda` text NOT NULL,
  `punti` float NOT NULL,
  `fk_argomento` int NOT NULL,
  `fk_tipo_domanda` int NOT NULL,
  `num_righe` int DEFAULT NULL,
  `fk_libro` int NOT NULL,
  `data_creazione` date NOT NULL,
  `ese_num` text,
  `fk_utente` int NOT NULL,
  `num_gruppo` int NOT NULL,
  `livello_diff` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_domande`
--

INSERT INTO `ct_domande` (`id_domanda`, `domanda`, `punti`, `fk_argomento`, `fk_tipo_domanda`, `num_righe`, `fk_libro`, `data_creazione`, `ese_num`, `fk_utente`, `num_gruppo`, `livello_diff`) VALUES
(1, 'What is meant by structured programming?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(2, 'What are the advantages of object-oriented programming?', 1, 26, 3, 0, 1, '2026-07-01', '', 1, 0, 3),
(3, 'Define possible attributes and methods of the Motorcycle object', 1, 26, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(4, 'What does instance of a class mean?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(5, 'How is a class usually represented?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(6, 'When is the constructor of a class called?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(7, 'What is meant by encapsulation?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(8, 'What is the correct definition of a Car class in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(9, 'What is the correct definition of a method with 2 parameters in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(10, 'How can I call the &#039;print&#039; method with no parameters on the &#039;obj&#039; object in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(11, 'How could I create an object of the Triangle class with base 5 and height 3 as parameters?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(12, 'What is the correct definition of a constructor for creating objects with 1 parameter?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(13, 'How can a protected attribute be created to define a color in a Python class?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(14, 'If I create an object of the Rectangle class named r1, how can I access its private __base attribute from an external main function?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(15, 'A class that inherits from a superclass is also called:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(16, 'If the Circle class is a subclass of the GeometricShape class in Python, I must write:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(17, 'To access superclass elements inside subclass methods, I need the keyword:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(18, 'Multiple inheritance:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(19, 'By polymorphism in programming we mean:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(20, 'In overriding:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(21, 'If I want to print an object directly with the print function, which operator must I overload?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(22, 'In Python, a function created inside a class definition is called:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(23, 'What could be possible attributes and methods for the Television object?', 1, 26, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(24, 'If a class derives from two superclasses, we have:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(25, 'An instance of a class:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(26, 'Which of the following cannot correctly create an object of the Dog class?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(27, 'Which of the following is NOT correct about object-oriented programming?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(28, 'The keyword that defines a model indicating the data that will be contained in an object of the class and the functions that can be called on an object of the class is', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(29, 'The word conventionally used to refer to the current instance (object) of a class:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(30, 'To extend a class, the new class should have access to all data and internal behavior of the superclass', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(31, 'Which of the following is the correct way to define a constructor?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(32, 'Which of the following statements is most accurate for the declaration x = Circle()?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(33, 'Which method must be overloaded to overload the addition operator?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(34, 'Draw a possible class diagram for the Car class', 1, 26, 1, 1, 1, '2026-07-01', '', 1, 0, 3),
(35, 'What is the difference between procedural, structured, and object-oriented programming?', 3, 26, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(36, 'What are the advantages of object-oriented programming? Give a brief description', 3, 26, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(37, 'What are the attributes and methods of a class? Give an example', 3, 26, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(38, 'What is the difference between a class and an object? Give an example', 3, 26, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(39, 'What is the constructor method used for? How is it declared in Python?', 3, 26, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(40, 'Explain what is meant by encapsulation in object-oriented programming', 3, 26, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(41, 'Explain what is meant by information hiding in an object-oriented language and how it is implemented in practice', 3, 26, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(42, 'What is meant by the interface of an object?', 3, 26, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(43, 'The word conventionally used to refer to the current instance of a class in Python:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(44, 'What does the __init__ function do in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(45, 'How can I call the &#039;print&#039; method with no parameters on the &#039;obj&#039; object in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(46, 'What is the correct definition of a constructor for creating objects with 1 parameter in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(47, 'An instance of a class is:', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(48, 'Which of the following cannot correctly create an object of the Dog class in Python?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(49, 'In Python, which method is called automatically when a new instance of a class is created?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(50, 'In Python, which class method is called automatically when attempting to convert an instance to a string (overloading the print operator)?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(51, 'In Python, which method is used to call an overridden method of the parent class inside a subclass?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(52, 'In Python, which keyword is used to indicate that a function does not return any value?', 1, 26, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(53, 'Which instruction must always be executed first on a file?', 1, 27, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(54, 'Why use functions in programming languages?', 3, 28, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(55, 'What elements are needed to declare a function in Python?', 3, 28, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(56, 'Write the code for a function that multiplies two numbers. Also write the code to call the function with the numbers 5 and 8 as actual parameters', 3, 28, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(57, 'What is meant by local visibility and global visibility of a variable?', 3, 28, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(58, 'What is meant by formal parameters and actual parameters?', 3, 28, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(59, 'What are the 3 correspondence rules between formal parameters and actual parameters in Python?', 3, 28, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(60, 'Explain the difference between passing parameters by value and by reference', 3, 28, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(61, 'What is meant by namespace?', 3, 28, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(62, 'What is meant by shadowing? Give an example', 3, 28, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(63, 'What is a module in Python and what is it used for?', 3, 28, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(64, 'Enter below the code to import a module named functions.py. Inside the file there are 2 functions named sum and product. Then enter how to import only the sum function and how to import the module using an alias (for example f)', 3, 28, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(65, 'What are subprograms in programming?', 1, 28, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(66, 'Which of the following is an example of a subprogram in Python?', 1, 28, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(67, 'What does a subprogram represent in programming?', 2, 28, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(68, 'Where is a global variable visible?', 1, 28, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(69, 'What is the main difference between passing arguments by value and by reference?', 1, 28, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(70, 'What are the main data structures in Python?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(71, 'What is the main purpose of data structures in programming?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(72, 'How do you access the second element of a list `x`?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(73, 'Which of these structures is immutable?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(74, 'What does `set([1, 2, 2, 3])` return?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(75, 'Which method adds an element to a list?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(76, 'Which data structure associates keys with values?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(77, 'How do you create an empty set in Python?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(78, 'What does the `keys()` method do in a dictionary?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(79, 'Which operation is valid for tuples?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(80, 'Which data structure is ordered and mutable?', 1, 29, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(81, 'What is meant by recursion in programming?', 1, 31, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(82, 'What is meant by recursion in programming?', 3, 31, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(83, 'Describe the stack data structure. What insertion/removal strategy does it use for data? What modification and search operations can we use within the stack? What is the idea for implementing the stack in a programming language of your choice?', 3, 34, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(84, 'Describe the linked list data structure, possibly using a drawing. How is a new element inserted into the list?', 2, 34, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(85, 'Describe the queue data structure. What insertion/removal strategy does it use for data? What modification and search operations can we use within the queue? What is the idea for implementing the queue in a programming language of your choice?', 3, 34, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(86, 'What is the complexity of searching an unordered linked list in the worst case?', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(87, 'Which operation is more efficient in a linked list than in an array?', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(88, 'In a doubly linked list, each node contains', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(89, 'In a binary tree, how many children can a node have at most?', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(90, 'Which traversal of a binary search tree produces values in increasing order?', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(91, 'In the worst case, a degenerate binary search tree (inserting increasing values one after another) has search complexity equal to', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(92, 'A graph is defined by', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(93, 'In a directed graph, the in-degree of a node indicates', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(94, 'A tree is', 1, 34, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(95, 'Think and answer: in a linked list, what is the computational complexity of inserting a node at the head? If the list only has a pointer to the head and not to the tail, what is the complexity of inserting a node at the tail of the list?', 2, 34, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(96, 'In a linked list, what happens if the reference to the head of the list is lost? (The reference is accidentally set to null)', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(97, 'In which cases is a linked list preferable to a dynamic array?', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(98, 'Think: What is the maximum number of nodes a binary tree of height 4 can have (height is the distance from the root to the deepest leaf, so 4 levels of nodes in this case)? Try to generalize with height h by creating a formula', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(99, 'Think: what is the computational complexity of fully printing a binary tree with n nodes using preorder traversal? And using postorder traversal?', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(100, 'What is a binary search tree? What are the two fundamental properties that every node must satisfy?', 2, 34, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(101, 'What is generally the complexity of searching for an element in a binary search tree? What does it become if the elements were inserted in increasing order (generating a so-called degenerate tree)?', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(102, 'Compare linked lists and BSTs for search, insertion, and deletion operations. How do they work and which of the two structures is better?', 2, 34, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(103, 'What is a graph? What elements is it composed of?', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(104, 'What is the difference between a directed and an undirected graph? What is meant by the degree of a node? How does it change in directed graphs?', 2, 34, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(105, 'What is a weighted graph? What is meant by path and shortest path?', 2, 34, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(106, 'Think: Compare tree traversal and graph node traversal: what complications arise in graphs?', 1, 34, 1, 2, 1, '2026-07-01', '', 1, 0, 3),
(107, 'Which relational operation allows you to choose only the rows that meet a certain condition?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(108, 'Which relational operation joins all rows of two tables with the same schema?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(109, 'Which relational operation returns only rows common to two tables with the same schema?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(110, 'Which relational operation returns the rows present in one table but not in the other?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(111, 'What operation combines each row of one table with each row of another table?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(112, 'Which relational operation relates two tables based on a common attribute?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(113, 'Which relational operation can be seen as a selection applied to a Cartesian product?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(114, 'If I want to get only the names of the students from a Students table, which operation should I use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(115, 'If I want to get the students enrolled in at least one course by comparing the Students table and the Enrollments table, which operation should I use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(116, 'From the Students table we want to extract only students over 20 years old. Which relational operation are we applying?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(117, 'From the Courses table we want to display only the names of the courses, without the other attributes. Which relational operation do we use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(118, 'We have two tables: Customers and Orders. We want to get all orders with the name of the customer who placed them. Which operation do we use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(119, 'We have two tables: Students and ErasmusStudents, with the same schema. We want to get the complete list of students. Which relational operation do we use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(120, 'We have two tables: Registered Customers and Newsletter Customers. We want to know which customers appear in both. Which relational operation do we use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(121, 'We have two tables: Registered Customers and Newsletter Customers. We want to know which customers are registered but not subscribed to the newsletter. Which relational operation do we use?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(122, 'From the Orders and Products tables we want to obtain all possible combinations between orders and products, without conditions. What relational operation does this describe?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(123, 'Which relational operation allows you to select only some columns of a table?', 1, 1, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(124, 'What is a sorting algorithm that compares elements two by two and swaps those out of position?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(125, 'Which sorting algorithm repeatedly selects the smallest element and moves it to the correct position?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(126, 'What is a search method that iteratively splits the array in half and compares the searched element to the middle one?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(127, 'Which sorting algorithm repeatedly splits the array in half and then reassembles it sorted?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(128, 'What is a search method that checks element by element from the beginning to the end of the array?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(129, 'What is the complexity of the bubblesort sorting algorithm?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(130, 'What is the complexity of linear search?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(131, 'What is the complexity of binary search?', 1, 2, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(132, 'The name NoSQL means', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(133, 'I database NoSQL:', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(134, 'What do we mean by basically available for NoSQL db?', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(135, 'What does eventually consistent mean for a NoSQL database?', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(136, 'Brewer&#039;s theorem states that:', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(137, 'What property does a relational db NOT have with respect to Brewer&#039;s theorem?', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(138, 'Why can&#039;t I have consistency if I have Partition Tolerance and Availability?', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(139, 'Which of the following is NOT a type of NoSQL db?', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(140, 'Which of the following is NOT a NoSQL DBMS?', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(141, 'MongoDB utilizza documenti in formato:', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(142, 'The Cassandra NoSQL DBMS uses a topology:', 1, 4, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(143, 'What is an instance of a class? What is an attribute? Give examples', 3, 5, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(144, 'What is the difference between identifier and descriptor attributes? Give an example of each', 3, 5, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(145, 'What is the difference between identifier and descriptor attributes? Give an example of an identifier attribute and a descriptor', 3, 5, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(146, 'Indicate and give a brief description of some typologies or classifications for the attributes of a class', 3, 5, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(147, 'In a conceptual diagram, what key uniquely identifies each instance of a class?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(148, 'A primary key is made up of multiple attributes. What is it called?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(149, 'Which of these is a key that can be chosen as primary but is not yet primary?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(150, 'In the conceptual model we use the `fk_Order` notation. What kind of key is it?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(151, 'In the conceptual model we use the `Customer_id` attribute as the key. What kind of key is it?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(152, 'Which statement is correct about artificial keys?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(153, 'In an association between &quot;Student&quot; and &quot;Course&quot;, using `fk_Student` in &quot;Registration&quot; represents...', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(154, 'What is the purpose of a foreign key?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(155, 'When a foreign key `fk_Customer` is present in the &quot;Order&quot; class, what does it represent?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(156, 'Which of these can NOT be used as a primary key?', 1, 5, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(157, 'What is an entity in an ER model? Give an example', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(158, 'What is the difference between strong entity and weak entity? Give an example', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(159, 'What is an entity instance? What is an attribute?', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(160, 'What is the difference between identifier and descriptor attributes?', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(161, 'Indicate and give a brief description of some types of attributes (for example looking at their optionality)', 3, 7, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(162, 'What are candidate keys, primary keys and alternative keys? Give an example.', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(163, 'What is an artificial key? Give an example', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(164, 'What is a compound key? Give an example', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(165, 'What is a foreign key? Give an example.', 3, 7, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(166, 'What is the garbage collector and how does it work in an object-oriented language?', 4, 8, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(167, 'What is meant by memory deallocation in an object-oriented programming language?', 4, 8, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(168, 'The stack in memory management in object-oriented programming', 4, 8, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(169, 'The Heap and memory allocation in Object-Oriented Programming', 4, 8, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(170, 'What does it mean that Python uses dynamic types?', 3, 10, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(171, 'What does casting mean and how is it done in Python?', 3, 10, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(172, 'What does it mean to indent code and why is it important in Python?', 3, 10, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(173, 'Python has a license:', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(174, 'In Python l&#039;indentazione:', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(175, 'I want to give the same value to 3 variables in Python:', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(176, '&quot;a&quot; * 5 in Python gives me:', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(177, 'Casting significa:', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(178, 'Which statement is used to perform an action only if a condition is true in Python?', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(179, 'How do you declare a variable in Python?', 1, 10, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(180, 'What is a DBMS and what is it for?', 3, 11, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(181, 'What possible problems can you have if you don&#039;t use databases?', 3, 11, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(182, 'Why use databases and not data saved by individual applications?', 3, 11, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(183, 'What are the steps to take to create a database? Give a brief description', 3, 11, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(184, 'What possible logical models exist for creating databases? Give a brief description', 3, 11, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(185, 'Describe the ACID properties of a database transaction', 3, 11, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(186, 'What does the Atomicity property guarantee in databases?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(187, 'What does the Consistency property mean in databases?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(188, 'What problem can arise without a centralized data management system?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(189, 'What is a DBMS for?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(190, 'What does the Isolation property represent in databases?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(191, 'What is a primary benefit of using databases?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(192, 'What can happen in the absence of control over concurrent access to data?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(193, 'What does the Durability property represent in a transaction?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(194, 'What is meant by data redundancy?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(195, 'What is a database?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(196, 'In a banking app, a user transfers &euro;100 from Account A to Account B. The system debits the &euro;100 from A but crashes before crediting it to B. Which ACID property is violated?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(197, 'Two customers purchase the last available piece of a product online almost at the same time. Both receive confirmation, but the warehouse only had one unit. Which ACID property is violated?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(198, 'A transaction enters an order with a non-existent customer number, and the system does not prevent the error. Which ACID property is violated?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(199, 'After completing a transaction that records the payment of an invoice, the system crashes. Upon restart, the payment appears as not made. Which ACID property is violated?', 1, 11, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(200, 'What is a character string? How is it represented in a programming language?', 3, 12, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(201, 'What does it mean to concatenate strings? What operator does Flowgorithm use for concatenation?', 3, 12, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(202, 'What is a variable? What are its characteristics?', 3, 12, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(203, 'What does pseudocode mean? And machine code?', 3, 12, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(204, 'What is an algorithm and what are its properties?', 3, 12, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(205, 'What is pseudocode? And what is a programming language?', 3, 12, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(206, 'What is assignment? Write what it means that it is a destructive operation on the left. Can I perform the assignment a=a+5? If so what does it mean?', 3, 12, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(207, 'Describe the main blocks of a block diagram', 3, 12, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(208, 'Which of the following is a valid variable name:', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(209, 'Describe the Boolean operators seen in class', 3, 12, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(210, 'What is the type of a variable (give examples)? What is a Boolean value?', 3, 12, 1, 3, 1, '2026-07-01', '', 1, 0, 3),
(211, 'What are the characteristics of formal languages? Give a brief explanation of each', 3, 12, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(212, 'Give a definition of high-level language and low-level language', 3, 12, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(213, 'Give a definition of constant and give at least one example of a constant. How is a constant written by convention?', 3, 12, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(214, 'What is the difference between single, double and multiple selection? (You can also draw the relevant block of the block diagram to answer)', 3, 12, 1, 4, 1, '2026-07-01', '', 1, 0, 3),
(215, 'What is a flowchart?', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(216, 'What does a diamond represent in a flowchart?', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(217, 'Where are the variables of a programming language stored?', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(218, 'What is the main purpose of flowcharts in programming?', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(219, 'What is used in a flowchart to perform a different action based on a specific condition?', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(220, 'What is the purpose of variables in programming?', 1, 12, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(221, 'Define what the difference is between a static array and a dynamic array in Java. Which class can be used in Java for dynamic arrays? What package is it in?', 4, 13, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(222, 'Describe the Java vector class, indicating what the capacity of the Vector is and why it differs from the number of elements it contains and what happens to the two values when elements are added or removed.', 4, 13, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(223, 'Describe what the type of a Vector is in Java, what types can be placed inside a Vector and what Wrapper classes are', 4, 13, 1, 7, 1, '2026-07-01', '', 1, 0, 3),
(224, 'What is the Java compiler called?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(225, 'What is an environment variable, for example PATH?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(226, 'What is the name of the environment that we can install on our PCs to call java and its compiler from the command line?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(227, 'What is an identifier?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(228, 'What are programming language keywords?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(229, 'What does case sensitive mean?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(230, 'Which of the following is NOT a good variable name in Java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(231, 'How can I write a variable for the side of the square from java convention?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(232, 'Which of the following is the declaration of a constant in java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(233, 'What does casting mean in a programming language?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(234, 'What does promotion mean in Java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(235, 'What does static typing mean?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(236, 'What is the characteristic of a strongly typed language?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(237, 'What is the operator to use to do AND in a Java condition?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(238, 'What is the OR operator in Java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(239, 'What is a package in Java', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(240, 'What is Java ByteCode?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(241, 'The ByteCode:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(242, 'The JVM:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(243, 'To do input in Java we can use the class:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(244, 'How do you print on screen in Java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(245, 'How do I read an integer from standard input if I created a Scanner object named myScanner?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(246, 'How do you insert a single line comment in Java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(247, 'How do you insert a multi-line comment in Java?', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(248, 'In Java if I have two integer variables and I divide them:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(249, 'If I declare a variable X in Java inside a block of code:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(250, 'The difference between the double &amp;&amp; and single &amp; operators when checking an if:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(251, 'The difference between the double || operators and single | when controlling an OR operation:', 1, 14, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(252, 'Why is object-oriented programming used?', 3, 15, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(253, 'What do attributes and methods of a class represent?', 3, 15, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(254, 'What is the Builder and what is it for?', 3, 15, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(255, 'What levels of visibility are possible? Give an explanation for each', 3, 15, 1, 7, 1, '2026-07-01', '', 1, 0, 3),
(256, 'What is the characteristic of a static attribute for a class?', 3, 15, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(257, 'What is meant by information hiding? Why is it used?', 3, 15, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(258, 'Instance of a class is synonymous with:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(259, 'Incapsulamento', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(260, 'The constructor in Java', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(261, 'An instance variable:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(262, 'Which of the following is a possible wording to create an object of the Circle class in Java?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(263, 'Which of the following is NOT the correct declaration of a method in Java?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(264, 'A local variable:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(265, 'The final keyword in Java:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(266, 'The null keyword in Java:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(267, 'How do I declare an array containing 5 objects of type Car in Java?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(268, 'What is a class interface?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(269, 'What is a programming object?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(270, 'If I create an object of the class Rectangle with name r1, how can I access its private attribute called height from an external class Main?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(271, 'How can I call &#039;computePerimeter&#039; method without parameters on &#039;obj&#039; object in Java?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(272, 'What is meant by information hiding?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(273, 'Which of the following statements is most accurate for the statement Circle x = new Circle()?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(274, 'Information Hiding is achieved when:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(275, 'When I declare a variable of type reference (so basically a reference to an object of a class), but I don&#039;t call the constructor with the new keyword:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(276, 'The final keyword in Java is used to:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(277, 'In Java it is possible to declare two constructors for a class:', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(278, 'In Java, what is the default constructor for a class if it is not explicitly defined?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(279, 'In Java, what is the keyword used to indicate inheritance between classes?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(280, 'In Java, what is the access modifier that makes a class member accessible only within the same class or its subclasses?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(281, 'In Java, what keyword is used to indicate that a method returns no value?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(282, 'What is one of the main uses of interfaces in Java?', 1, 15, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(283, 'A class that inherits attributes and methods of another class is also called:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(284, 'What are two ways to differentiate a subclass from its superclass?', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(285, 'If a subclass extends a single superclass I have:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(286, 'If a subclass extends multiple superclasses we have:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(287, 'Is it possible to have multiple inheritance in Java?', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(288, 'The keyword in Java to inherit from a superclass is:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(289, 'In Java every class is a subclass of the class:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(290, 'When I define 2 methods in the same class that have the same name (different from that of the class) and the same return type, but different parameters, I am faced with:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(291, 'When I write a method on a subclass that has the same parameters, same name and same return type as a method of the superclass, I am realizing:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(292, 'In Java the private attributes of a class:', 1, 16, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(293, 'Files are used to:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(294, 'Which of the following is NOT an operation we can do on files from Java', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(295, 'Opening a file:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(296, 'Reading from file', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(297, 'Writing to file', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(298, 'The package for doing input/output on Java is:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(299, 'A stream is:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(300, 'We have two categories of classes for reading and writing to files:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(301, 'The two types of files I can save on Java:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(302, 'A structured file:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(303, 'If I want to save an object to a structured file, the class must:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(304, 'Methods for reading/writing files in Java', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(305, 'To split a string based on a specific character we can use the class:', 1, 17, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(306, 'What is the difference between a compiled language and an interpreted language?', 3, 18, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(307, 'Describe the advantages and disadvantages of a compiled language', 3, 18, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(308, 'Describe the advantages and disadvantages of an interpreted language', 3, 18, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(309, 'What is an IDE?', 3, 18, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(310, 'What types of errors can we encounter when we program? Give examples for each.', 3, 18, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(311, 'How do you document your code and why is it important to do so?', 3, 18, 1, 7, 1, '2026-07-01', '', 1, 0, 3),
(312, 'What is the difference between a compiled language and an interpreted language? Say one advantage of each.', 3, 18, 1, 8, 1, '2026-07-01', '', 1, 0, 3),
(313, 'What is a pseudo-interpreted language?', 3, 18, 1, 6, 1, '2026-07-01', '', 1, 0, 3),
(314, 'What is a debugger and what does it allow you to do?', 3, 18, 1, 5, 1, '2026-07-01', '', 1, 0, 3),
(315, 'I have an .exe program available on Windows, it means that:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(316, 'I have an .exe program available on Windows:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(317, 'A compiled program:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(318, 'A compiled program for Linux:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(319, 'If I pass my Python program to a friend:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(320, 'Generally an IDE contains:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(321, 'Esempio di errore lessicale:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(322, 'The debugger DOES NOT allow:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(323, 'It does NOT have to do with code documentation:', 1, 18, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(324, 'What is meant by &quot;information hiding&quot; in object-oriented programming?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(325, 'What is the main purpose of inheritance in object-oriented programming?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(326, 'What does a class represent in object-oriented programming?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(327, 'What is the difference between a class and an object?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(328, 'What does override mean in object-oriented programming?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(329, 'How does inheritance contribute to code modularity in object-oriented programming?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(330, 'In Python and java, what is the symbol used to access an object&#039;s attributes and methods?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(331, 'In object-oriented programming, what is the concept used to group data and operations that manipulate that data?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(332, 'In object-oriented programming, what is the type of relationship between two classes where one class is an extension of another?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(333, 'What is the principle of inserting and removing elements of a stack?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(334, 'What is the principle of inserting and removing items in a queue?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(335, 'In object-oriented programming, what is a class in Java?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(336, 'What does a class attribute represent?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(337, 'What does a method in a Java class represent?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(338, 'Which keyword makes an attribute accessible only within the same class?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(339, 'What level of visibility allows access from any other class?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(340, 'What is meant by information hiding?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(341, 'What is the correct way to safely access a private attribute?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(342, 'What is a constructor in Java?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(343, 'What feature distinguishes a constructor from a normal method?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(344, 'What happens if you don&#039;t define any constructors in a Java class?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(345, 'What is inheritance in Java?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(346, 'What keyword is used in Java to indicate inheritance between classes?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(347, 'In an inheritance relationship the derived class is also called:', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(348, 'What is method overloading?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(349, 'What is method overriding?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(350, 'Which statement about overloading is correct?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(351, 'Which statement about overriding is correct?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(352, 'What is the purpose of the super keyword in a constructor?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(353, 'Why are attributes often declared private in Java?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3),
(354, 'If a class Student extends Person, which statement is correct?', 1, 25, 2, 0, 1, '2026-07-01', '', 1, 0, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizi`
--

CREATE TABLE `ct_esercizi` (
  `id_esercizio` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `testo_esercizio` text NOT NULL,
  `punti_esperienza` int DEFAULT NULL,
  `storia_esercizio` text NOT NULL,
  `fk_argomento` int NOT NULL,
  `tipo_esercizio` int NOT NULL,
  `nome_capitolo` varchar(300) NOT NULL,
  `num_domande` int NOT NULL DEFAULT '0',
  `fk_materiale` int NOT NULL,
  `monete_guadagnate` int DEFAULT NULL,
  `testo_ese104` text,
  `livello_diff` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_esercizi`
--

INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(1, 'b838cdf7-3eb8-4b52-981b-5e9f73d7df26', '&lt;p&gt;In order to understand what happened to the evil heroes, we need data. Identify the initial and final data of the following problems, inserting them as an answer to the task:&lt;/p&gt;\r\n&lt;p&gt;1) Warrior 4 begins to impose taxes because he wants to get rich. Each of his subjects must pay 1000 denarii in a year. On the first of the year he is required to pay 70 denarii immediately, then he must pay a fee at the end of the month. How much does each portion of the debt become?&lt;/p&gt;\r\n&lt;p&gt;2) Warrior 7 forces a group of men to build a castle for him. A square section tower has a side of 30 meters and a height of 20 metres. A stone block for construction has a length of 1 meter, a width of 50 cm and a height of 50 cm. each stone costs 3 denarii. How much money is needed to build the tower?&lt;/p&gt;\r\n&lt;p&gt;3) Warrior 12 hires an army, because he is too lazy to fight with his powers. The army will consist of 1200 people and must have commanders. Every 80 people a captain must be created, every 5 captains a general must be created. How many generals will the army need?&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b2dc3ae3.73196769_exercise_editor_6a32b46f58d7e5.01975229.png&quot; alt=&quot;&quot;&gt; Before civilization emerged in the form we know today, 14 extraordinary warriors were created, each with incredible abilities. Who created them remains a mystery shrouded in shadow, but their mission was clear: 14 men and women, chosen by humanity itself, were invested with immense powers to ensure peace and prosperity on Earth. They were just individuals, willing to sacrifice their lives for the common good and to bring happiness to all people.&lt;/p&gt;\r\n&lt;p&gt;Initially, the Creator&#039;s choice seemed flawless. However, he had not foreseen how easily the human soul could be corrupted. The power, so great and absolute, began to insinuate doubts and selfish desires in some of them. One of the warriors, the most powerful and originally the most selfless, was the first to succumb to the darkness. Where he had once acted for the good of others, he began to use his strength to subjugate the world to his will, transforming himself into the tyrant he had sworn to fight.&lt;/p&gt;\r\n&lt;p&gt;Others followed his example, falling victim to selfishness, pride and ambition. Of the 14 initial figures, 6 embraced the dark side, acting for their own gain. On the contrary, 6 remained faithful to their original mission, strenuously committed to protecting the common good. The remaining 2, disgusted by the conflicts and betrayals, decided to withdraw. They chose neutrality, isolating themselves in distant lands and abandoning any involvement in the duties and struggles of others.&lt;/p&gt;\r\n&lt;p&gt;Thus, the original warriors were divided into three factions: those who fought for themselves, those who fought for the good of others, and those who, now disillusioned, became estranged from everything.&lt;/p&gt;', 12, 1, 'The creation of heroes', 0, 0, 0, '&lt;p&gt;In order to understand what happened to the evil heroes, we need data. Identify the initial and final data of the following problems, inserting them as an answer to the task:&lt;/p&gt;\r\n&lt;p&gt;1) Warrior 4 begins to impose taxes because he wants to get rich. Each of his subjects must pay 1000 denarii in a year. On the first of the year he is required to pay 70 denarii immediately, then he must pay a fee at the end of the month. How much does each portion of the debt become?&lt;/p&gt;\r\n&lt;p&gt;2) Three dwarves in the service of warrior 7 take 12 days to dig a tunnel. If there are 4 dwarves and they work at the same pace, in how many days will it take them to complete the same tunnel?&lt;/p&gt;\r\n&lt;p&gt;3) An alchemist forced to work for warrior 12 buys some attack potions. Each potion costs 8 gold. In the end he spends 56 coins, including 8 coins for transportation. Question: How many potions did you purchase?&lt;/p&gt;', 0),
(2, 'ce688267-416f-4de0-8042-f1133c6a61eb', '&lt;p&gt;Help the heroes of good too: for the following problem identify which are the input data and which are the output data. Lists the variables that you must declare in order to have input and output data and to carry out data processing.&lt;/p&gt;\r\n&lt;p&gt;The first good warrior wants to use a contingent of snapping turtles to strike at the enemy. A good number would be 3000 turtles. He initially has 2 turtles (male and female) at his disposal. Each pair of turtles produces 20 eggs in a year. Only half of the turtles born from a clutch survive and can reproduce, in turn creating 20 eggs per pair. After how many years, can you get at least 3000 turtles, keeping in mind that the old ones are not dead and are reproducing themselves?&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b2e52f26.47293853_exercise_editor_6a339f36a7bd81.77106385.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;At this point, a terrible battle erupted between the forces of good and evil. Until then, good heroes had avoided taking up arms, since their goal was to preserve life in all its forms, not destroy it. However, faced with the growing threat posed by the evil heroes and their insatiable thirst for power, they were forced to change their approach. To protect themselves and humanity, they took up arms and prepared to fight.&lt;/p&gt;\r\n&lt;p&gt;That war flared up on a global scale, involving peoples and nations. Although the warriors of good had noble intentions, they were at a disadvantage: their opponents, corrupted by darkness, were the most powerful among the 14 and exploited their formidable powers without scruples. However, the protectors of justice found strength and support in the people they defended. Humans, grateful and supportive, joined them, forming an alliance of hope against the forces of tyranny and destruction.&lt;/p&gt;', 12, 1, 'The War', 0, 0, 0, '&lt;p&gt;Help the heroes of good too: for the following problem identify which are the input data and which are the output data. Lists the variables that you must declare in order to have input and output data and to carry out data processing.&lt;/p&gt;\r\n&lt;p&gt;The first good warrior wants to use an army of elephants to counter the evil heroes. Each elephant can carry 4 fighters. 40% of the fighters use the bow, 30% use the sword and the remainder use the spear. Taking into account that there are 243 elephants, how many weapons of each type will be needed to arm the entire army?&lt;/p&gt;', 0),
(3, '3f3b969f-5f65-434d-8b67-a6eb615faa84', '', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b2ed71d9.73250014_exercise_editor_6a33a226088168.83321462.jpg&quot; alt=&quot;&quot;&gt;The Creator of the 14 realized the damage he had caused by assigning great powers to some of the men. However, unable to revoke the gifts given, he decided that the warriors had to be stopped in some way. The Creator sent an iridescent gaseous cloud to the Earth, within which was hidden a powerful narcotic that would put powerful individuals who came into contact with it into sleep.&lt;/p&gt;', 12, 4, 'The intervention of the creator', 7, 0, 0, '', 1),
(4, '892ef3af-0ae6-430d-bbfc-84f68420e395', '&lt;p class=&quot;MsoNormal&quot;&gt;Use a block diagram to help the Creator create crystal cases to seal the warriors. To build a display case you need to know the height in cm of the hero to be enclosed. The display case will have the shape of a parallelepiped, the width is 1.5 metres, the length depends on the height of the hero and the depth will be 1 metre. Calculate in square cm how much glass surface will be used to build the display case.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b2f26823.39414039_exercise_editor_6a33a16b57f7d5.41290840.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;All the warriors, except the two neutrals, hidden in a remote place, fell asleep. The Creator decided that the heroes would sleep forever and he with them. The Creator sealed the 12 heroes in crystal cases and then locked himself in a thirteenth golden case, where in his plans, he would rest for eternity together with his creatures.&lt;/p&gt;', 6, 3, 'The Teche', 0, 0, 0, '&lt;p&gt;Use a block diagram to help the Creator create crystal cases to seal the warriors. You need to build the lid of the display case, which is represented by a rectangle. Ask the user for the base and height of the rectangle as input and return the area of the rectangle as output&lt;/p&gt;', 0),
(5, 'b561eae1-e46e-4f91-a72e-1b808dd44376', '&lt;p&gt;Human beings proceed on the path of science and technology. Build the block diagram with Flowgorithm that solves the following problem: a horse racing track must be built with a circle shape, like the black part in the figure. The track must be covered with grass. To know how much grass is needed we need to know the area to be covered. The track is enclosed by a wider and a narrower circle. Ask the user the radius of the two circles in meters and calculate the orange area inside them, then communicating it to the user. Knowing that 1.3 kg of grass seeds are needed for every square meter of soil, calculate how many total kg of seeds are needed to cover the track and communicate this to the user.&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 11.0pt; line-height: 107%; font-family: &#039;Aptos&#039;,sans-serif; mso-ascii-theme-font: minor-latin; mso-fareast-font-family: Aptos; mso-fareast-theme-font: minor-latin; mso-hansi-theme-font: minor-latin; mso-bidi-font-family: &#039;Times New Roman&#039;; mso-bidi-theme-font: minor-bidi; color: black; mso-color-alt: windowtext; background: white; mso-ansi-language: IT; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b303fa77.58346433_exercise_editor_6a33ad34247458.90034446.png&quot; alt=&quot;&quot; width=&quot;346&quot; height=&quot;296&quot;&gt;&lt;/span&gt;&lt;/p&gt;', 0, '&lt;p&gt;&lt;span style=&quot;font-size: 11.0pt; line-height: 107%; font-family: &#039;Aptos&#039;,sans-serif; mso-ascii-theme-font: minor-latin; mso-fareast-font-family: Aptos; mso-fareast-theme-font: minor-latin; mso-hansi-theme-font: minor-latin; mso-bidi-font-family: &#039;Times New Roman&#039;; mso-bidi-theme-font: minor-bidi; mso-ansi-language: IT; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b30d8ed7.23085663_exercise_editor_6a33aac71740b4.27361817.png&quot; alt=&quot;&quot;&gt; Humanity, meanwhile, faced the difficult challenge of living with the dark past of the 14 warriors. The stories of these heroes, their exploits and the epic conflict, became legends passed down from generation to generation. The events served as a constant lesson in the fragility of humanity in the face of power and corruption. As centuries passed, Earth recovered from the epic conflict, and human civilization continued to evolve. The crystal cases were kept as sacred relics, placed in a secret and inaccessible place, so that humanity would never forget the lessons learned from those events.&lt;/span&gt;&lt;/p&gt;', 6, 3, 'The world moves forward', 0, 0, 0, '&lt;p&gt;Human beings proceed on the path of science and technology. Build the block diagram with Flowgorithm that solves the following problem: a circle-shaped horse racing track must be built. The track must be covered with grass. To know how much grass is needed we need to know the area to be covered. Ask the user for the radius of the circle in meters and calculate the area, then communicating it to the user. Knowing that 1.3 kg of grass seeds are needed for every square meter of soil, calculate how many total kg of seeds are needed to cover the track and communicate this to the user.&lt;/p&gt;', 1),
(6, '48cab30c-e58c-4526-8921-f770174cf34d', '&lt;p&gt;Create a program in Flowgorithm where you ask the user whether they intend to be a good or evil hero. If you insert the word &quot;bad&quot; then you insert an output message on how it can conquer the World, if you insert the word &quot;good&quot; then it communicates a message on how to improve the planet.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b315a732.65365777_exercise_editor_6a33adaa2c0d20.27340561.jpg&quot; alt=&quot;&quot;&gt; However, over time, humanity began to forget the threat posed by the 14 warriors and their powers. Legends became stories for entertainment, and the world moved further and further into new scientific and technological developments. The display cases were forgotten and lost in the fog of the past.&lt;/p&gt;\r\n&lt;p&gt;Unfortunately, the Creator had underestimated the powers granted to the 12 trapped heroes: although their bodies were immobilized, their spirits were not. Little by little, the warriors devoted to evil managed to free themselves from their mortal remains, until they were able to return to act in our world again.&lt;/p&gt;', 6, 3, 'The return of evil', 0, 0, 0, '', 0),
(7, '2c597a69-2d52-4019-aa70-b9aee8e68a51', '&lt;p class=&quot;MsoNormal&quot;&gt;Create a program in Flogorithm that asks the user for any number greater than 0. It checks to ensure that the number is actually greater than 0, if it isn&#x27;t it reports an error. Multiply the number by 8 and divide it by 3. If the resulting number is between 1 and 10 it indicates that the predictions are in favor of the good warriors, if it is between 11 and 20 then it indicates that the evil heroes will probably win. If it is higher than 21, then it indicates that the outcome of the battle is uncertain. Use Boolean operators to indicate the numbers included in the selection&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;The evil heroes soon discovered that, as spirits, they could not use their powers to resume their purposes where they left off. The spirits thus began to look for a way to return to our world in material form. For years they studied and are now close to returning among us, with new clothes, but with the same wickedness of the past and only the heroes of good will be able to stop them. In fact, even the spirits of the heroes of good awakened, working hard to prepare for a possible return of the warriors of evil: if they had returned, a plan would have been devised to counter them.&lt;/p&gt;', 6, 3, 'The evil heroes on Earth', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Create a program in Flogorithm that asks the user for any number greater than 0.  Multiply the number by 8 and divide it by 3. If the resulting number is between 1 and 10 it indicates that the predictions are in favor of the good warriors, if it is between 11 and 20 then it communicates that the evil heroes will probably win. If it is higher than 21, then it indicates that the outcome of the battle is uncertain. Use Boolean operators to indicate the numbers included in the selection&lt;/p&gt;', 0),
(8, '9a42c4ed-8222-45b8-880d-46cab8f0220a', '&lt;p class=&quot;MsoNormal&quot;&gt;Create a program on Flowgorithm that provides a menu of choices to the user. The menu will simply ask which weapon is the user&#x27;s favorite. The choice will be between sword, spear, bow, axe. Each weapon must be represented by a number. The user will choose the number and the program will tell him which weapon he has selected.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;The two groups acted differently: the forces of evil learned how to take possession of the bodies of some human beings, while the benevolent heroes, or rather their spirits, managed to understand how to give their powers totally to other men. In this way they would die, but such a sacrifice would allow the evil warriors to be stopped. At midnight on the last day of the year the moment will come. Then the evil heroes will return to infest the Earth and you, a team of heroes, are the chosen ones: you will be entrusted with the powers of the benevolent heroes to oppose the evil that is coming!&lt;/p&gt;', 6, 3, 'Even Good returns', 0, 0, 0, '', 0),
(9, '6f56e731-6ead-4279-ac6b-e1ee14d7d6ff', '&lt;p class=&quot;MsoNormal&quot;&gt;Create a program on Flowgorithm to be able to locate the seventh meteor. Ask the user for a number as input and based on the result communicate on which mountain the monastery is located: if the number is between 0 and 10: the monastery is located on the White Mountain, if between 11 and 18 on the Green Mountain, if less than 0 it is located on the Red Mountain, if between 19 and 32 on the Pink Mountain, if between 33 and 50 on the Black Mountain, if between 51 and 100 on the Yellow Mountain, otherwise, if greater than 100, on the Blue Mountain.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b31c6fe0.03824197_exercise_editor_6a33af27c02451.78977713.jpg&quot; alt=&quot;&quot;&gt; The neutral heroes had hidden in isolated places to escape the wars unleashed by their companions. It was therefore very likely that one of them had lived for millennia as a hermit in a secluded environment far from society. Searching online, the heroes of good managed to discover the legend of the seventh meteor. In Greece there are 6 monasteries built on high, practically inaccessible cliffs, called meteors. Legend tells of the existence of a seventh monastery hidden from everyone&#039;s sight which is located in the highest meteor and inhabited by the ghost of a monk who has always appeared the same for hundreds of years and who showed himself to the few brave people who managed to reach him.&lt;/p&gt;', 6, 3, 'The seventh meteor', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Create a program on Flowgorithm to be able to locate the seventh meteor. Ask the user for a number as input and based on the result communicate which mountain the monastery is located on: if the number is less than 30: the monastery is located on the White Mountain, if between 31 and 100 on the Green Mountain, if greater than 100, on the Blue Mountain.&lt;/p&gt;', 1),
(10, '2c90447e-7cb7-41d3-95a4-f98be5d757d2', '&lt;p&gt;The heroes climb the mountain, how far have they climbed?&lt;/p&gt;\r\n&lt;p&gt;Create a program in Flowgorithm that continues to ask the user how far the heroes climbed (in meters), and how long it took them to climb that distance (in minutes), until the user enters 0 in the meters to indicate that they have arrived. It tells the user how many meters they traveled in total (therefore the sum of the meters) and how long it took them (in hours) to get to the top.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b320f8d3.40427290_exercise_editor_6a33b044cd1a06.19944820.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;This was an excellent possibility and the team of good heroes decided to travel to Greece, tackling the climb to the seventh meteor, where one of the neutral heroes could be hiding.&lt;/p&gt;\r\n&lt;p&gt;After an international flight and a long drive through picturesque Greek roads, they finally arrived at the foot of the majestic Meteora, the six imposing rock formations on which ancient monasteries had been built, but now their attention was focused on the seventh meteor, the one that no one had ever seen.&lt;/p&gt;\r\n&lt;p&gt;The climb proved to be extremely arduous, with rocky and steep routes that challenged their physical and mental endurance. But the heroes of good were driven by a strong purpose, and their determination carried them upward, until they finally reached the highest point of the Meteors.&lt;/p&gt;\r\n&lt;p&gt;There, surrounded by breathtaking views of the valleys and villages below, they discovered a small opening in the rock that appeared to lead inside the seventh meteor. It was a narrow, dark passage, but they couldn&#039;t afford to linger.&lt;/p&gt;', 6, 3, 'Climbing the mountain', 0, 0, 0, '&lt;p&gt;The heroes climb the mountain, how far have they climbed?&lt;/p&gt;\r\n&lt;p&gt;Create a program in Flowgorithm that continues to ask the user how far the heroes climbed (in meters) until the user enters 0 to indicate that they have arrived. It tells the user how many meters they have traveled in total (therefore the sum of the meters).&lt;/p&gt;', 1),
(11, '913b00ea-c448-49fa-b17c-61ef891baf8f', '&lt;p class=&quot;MsoNormal&quot;&gt;The heroes must find the maximum truth: Create a program that reads a series of numbers input from the user and stops when the read number is equal to 0 (so the loop continues as long as the read number is different from 0). The program must say in output which is the largest of the numbers read.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b325db91.92170486_exercise_editor_6a33b0c666c6a0.32413844.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Arming themselves with courage, they entered the cave and began an uncertain descent into the depths of the mountain. As they made their way through the dark tunnel, they began to hear a monotonous, peaceful chant echoing in their ears. It was a prayer song.&lt;/p&gt;\r\n&lt;p&gt;Following the sound, they finally came to a vast candle-lit cave, with a small altar in the center. Sitting before the altar was an incredibly young-looking monk, seemingly unchanged over time. The monk was chanting and praying in an ancient language, the meanings of which were unknown to the heroes.&lt;/p&gt;\r\n&lt;p&gt;Respectfully, they approached the monk and asked him if he knew of the neutral hero they sought. The monk looked at them with wise eyes and said: &quot;You have come this far because you seek truth and wisdom. My task is to guard the secret of the neutral hero you seek.&quot;&lt;/p&gt;', 6, 3, 'The cave and the monk', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;The heroes must find the maximum truth: Create a program that reads a series of numbers input from the user and stops when the read number is equal to 0 (so the loop continues as long as the read number is different from 0). The program must tell if a number greater than 50 has been entered&lt;/p&gt;', 1),
(12, 'a8740575-4184-468d-a907-f0e5c591ce71', '&lt;p&gt;For 8 weeks the heroes of good remain in the presence of Aionios. Every week they gain new knowledge, quantifiable with a number.&lt;/p&gt;\r\n&lt;p&gt;Write a program that uses a FOR loop and ask the user for each week what number indicates how much knowledge the heroes gain. At the end of the cycle, print the average of the knowledge acquired&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b32ea968.75092457_exercise_editor_6a33b12ba11787.83178296.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The good heroes explained their purpose and why they had come. The monk looked at them carefully and then nodded. &quot;You are worthy of knowing the truth. The neutral hero you seek is called Aionios, and he has lived here with me for millennia. He is the guardian of ancient knowledge and peace. He has hidden to avoid the wars and conflicts that plague the outside world.&quot;&lt;/p&gt;\r\n&lt;p&gt;With great respect, the heroes asked to meet Aionios. The monk agreed and led them to a small cell inside the cave. There they found Aionios, sitting in meditation, his face serene and calm.&lt;/p&gt;\r\n&lt;p&gt;When Aionios stood and looked at them, their hearts were filled with a sense of peace and wisdom. He was an ethereal being, a true keeper of knowledge and balance.&lt;/p&gt;\r\n&lt;p&gt;The team of good heroes spent days with Aionios, learning from his words and his thousand-year experience.&lt;/p&gt;', 6, 3, 'The neutral hero Aionios, the wise', 0, 0, 0, '&lt;p&gt;For 8 weeks the heroes of good remain in the presence of Aionios. Every week they gain new knowledge, quantifiable with a number.&lt;/p&gt;\r\n&lt;p&gt;Write a program that uses a FOR loop and ask the user for each week what number indicates how much knowledge the heroes gain. At the end of the cycle, print the sum of the knowledge gained&lt;/p&gt;', 1),
(13, 'd885df3e-eb9f-47da-8ef8-d21d10218faa', '&lt;p class=&quot;MsoNormal&quot;&gt;The heroes must decide which weapon is the most powerful. The weapons selected for the competition are 3 out of the 6 total: bow, sword and spear. Create a program with a FOR loop that draws a random number from 1 to 3 100 times and counts how many times each of the 3 numbers comes up. Each number represents a weapon. At the end, the program communicates which of the 3 weapons obtained the most random draws.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b335e690.94219837_exercise_editor_6a33b1a65d3f06.87242503.jpg&quot; alt=&quot;&quot;&gt;After 8 weeks of wisdom, the heroes of good finally decided to ask Aionios what the weapons that the ancient heroes hid in times immemorial were and where they were. Aionios warned the new heroes: &quot;Powerful were the objects forged by the ancient heroes of good and only after long training could they be found.&quot; The neutral hero nevertheless decided to help the new heroes, drawing a map that could lead them to the places where the weapons were hidden.&lt;br&gt;The weapons to be found were:&lt;br&gt;&bull; &nbsp; &nbsp;The Ivory Arch&lt;br&gt;&bull; &nbsp; &nbsp;The Spear of Destiny&lt;br&gt;&bull; &nbsp; &nbsp;The Flaming Sword&lt;br&gt;&bull; &nbsp; &nbsp;The Golden Shield&lt;br&gt;&bull; &nbsp; &nbsp;The Hammer of Eden&lt;br&gt;&bull; &nbsp; &nbsp;The Earthsplitting Axe&lt;br&gt;Each was located in a remote corner of the Earth. All that remained was to set out to find them&lt;/p&gt;', 6, 3, 'The weapons of the heroes of good', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;The heroes must decide which weapon is the most powerful. The weapons selected for the competition are 3 out of the 6 total: bow, sword and spear. Create a program with a FOR loop that draws a random number from 1 to 3 100 times and counts how many times each of the 3 numbers comes up. Each number represents a weapon. Finally, the program communicates the values ​​of the totals for each weapon.&lt;/p&gt;', 1),
(14, '5d30eef1-76ff-4bf0-a542-34346952bfd2', '&lt;p&gt;A prime number is visible among a thousand for those who know where to look, like the soldier in the story. Write a program that, given an integer as input, establishes whether it is a prime number or not.&lt;/p&gt;\r\n&lt;p&gt;PS: A number is prime if it is divisible only by 1 and itself (the remainder of the division by the numbers in between will always be different from 0).&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b33bc510.18373604_exercise_editor_6a33d9ac0cada6.15782507.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Cr&eacute;cy, 1346. Philip VI of France pronounced the fateful order: &quot;&lt;strong&gt;Load&lt;/strong&gt;!&quot;. The cavalry charged forward, the thunder of their hooves echoing across the valley.&lt;/p&gt;\r\n&lt;p&gt;On the other side, English archers and crossbowmen began shooting arrows with lethal precision. The first knights fell, overwhelmed by their own steeds, the heavy armor became steel tombs. Then the horses found holes hidden in the ground: many fell into them, increasing the chaos among the French ranks. Panic spread. That glorious squadron, undefeated in countless battles, was disintegrating.&lt;/p&gt;\r\n&lt;p&gt;But the English infantry was close, and the French, seized by a last burst of pride, spurred their horses with renewed vigor. It was then that long poles suddenly rose up, blocking their way. It was the end for the French cavalry.&lt;/p&gt;\r\n&lt;p&gt;Edward III and his son, the feared &lt;strong&gt;Black Prince&lt;/strong&gt;, in command of the English cavalry, launched themselves in pursuit of the fleeing enemy, finally dispersing them.&lt;/p&gt;\r\n&lt;p&gt;The battle of &lt;strong&gt;Crecy &lt;/strong&gt;it was the last place where the Ivory Bow appeared, one of the weapons of the heroes of good, which contributed to the victory of the English. The search for new heroes therefore had to start from France, where they went thanks to a direct flight from Athens. The new heroes went to the Crecy museum where they found themselves in front of an immense tapestry representing the battle. Among the thousand warriors present there, one carried in his hand a bow different from the others, easy to see for those who knew what to look for. Looking closer, they noticed the crest of the House of Stafford on the warrior&#039;s chest. It was a crucial clue. They now had to head to Staffordshire, the place of origin of that house, to search for the mighty bow.&lt;/p&gt;', 6, 3, 'The Battle of Crecy', 0, 0, 0, '&lt;p&gt;Write a program that checks whether an input number is prime: create a for loop that goes from 2 up to the entered number minus 1 and checks the remainder of the division of the number by the loop counter (operator %). Count how many times the remainder of the division is equal to 0 (using a variable for the sum). Insert an if at the end: if the sum is equal to 0 then output that the number is prime, otherwise output that it is not prime&lt;/p&gt;', 2),
(15, '0a2542ec-3d8b-44f9-94bd-b22ff0625cf5', '&lt;p&gt;One of the difficulties encountered by the new heroes in the Gem Forest are the Hydra ants: a strange population of ants that attacks them. For every ant killed N takes its place.&lt;/p&gt;\r\n&lt;p&gt;Given two integers which respectively represent the multiplication rate of the ants (how many new ants take the place of a killed ant) ​​and the maximum quantity of ants that I can face, say after how many ant killings we will be faced with the maximum number of ants to face, taking into account that we start from a single initial ant. Example: maximum = 1000, multiplication rate = 3. Initial ants = 1, After killing 1 ant I find myself 1*3=3 ants, if I kill the 3 ants I find myself 3*3=9 ants, if I kill the 9, I find myself 9*3=27 ants... and so on until I reach 1000. Use a while loop to calculate how many turns I killed ants in before exceeding the maximum (7 shifts in our case).&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b34078d5.70459912_exercise_editor_6a33dafbe74909.22764258.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Wasting no time, the new heroes took a further flight towards the UK and headed towards Staffordshire. They arrived in a hilly region, rich in history and tradition. They decided to begin their search by visiting the ancient homes and castles associated with the Staffords, hoping to find some clue to the presence of the Ivory Bow. During their research, they learned of a local legend that spoke of a magical bow hidden deep in an ancient forest. It seemed that only those who were destined to possess it could find it. It was a mysterious place, known as &quot;The Forest of Gems,&quot; and tales suggested that the bow was protected by powerful spells to prevent it from falling into the wrong hands.&lt;/p&gt;\r\n&lt;p&gt;The new heroes were determined to follow this lead. They ventured into the forest cautiously, aware of the challenges that awaited them. They passed through dense thickets, following the trail of legends and mysteries. Each step brought them a little closer to recovering the Ivory Bow to strengthen their team of good heroes.&lt;/p&gt;\r\n&lt;p&gt;But the Gem Forest wasn&#039;t just a place of hidden secrets; it was also a place of danger and trials. And as the new heroes of good advanced through the forest, they were unaware that they were being watched by evil eyes who wanted the bow for their own evil purposes. Their adventure was about to become even more intricate and dangerous than they had ever imagined.&lt;/p&gt;', 6, 3, 'Staffordshire and the Forest of Gems', 0, 0, 0, '&lt;p&gt;One of the difficulties encountered by the new heroes in the Gem Forest are the Hydra ants: a strange population of ants that attacks them. For every ant killed, 2 take its place.&lt;/p&gt;\r\n&lt;p&gt;Ask in input how many ants the heroes can face at most. At the beginning there is only one ant. It uses a while loop that stops when the number of ants reaches the maximum number. At each cycle the number of ants must double. At the end it communicates in output how many doublings were made to reach the maximum number of ants, i.e. how many times the cycle was entered (use a variable to take this into account).  Example: maximum = 100. Initial ants = 1, After killing 1 ant I find myself 1*2=2 ants, if I kill the 2 ants I find myself 2*2=4 ants, if I kill the 4, I find myself 4*2=8 ants... and so on until I reach 100. In this case I exceed the maximum after 7 cycles.&lt;/p&gt;', 2),
(16, '2834f935-fd59-4967-8f14-261ac273ced0', '&lt;p&gt;Jambalaya and Shinzo Hanzei sprint towards the Ivory Arch. The distance from the pedestal is X meters. Jambalaya can travel 3 meters in 10 seconds. Shinzo Hanzei, on the other hand, travels 7 meters in 10 seconds. Ask the user as input for the distance&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b344c6d4.16979936_exercise_editor_6a33dc5620a989.76895503.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The new heroes of good finally arrived in a clearing in the center of which stood a pedestal. Here is the Ivory Arch: it rested placidly on the low column. The heroes rushed towards the weapon, but a mysterious force pushed them all back. Everyone except Jambalaya, a Jamaican boy who was part of the team, who felt nothing on him except the late afternoon breeze. He was the boy destined to carry the Ivory Bow, heir to the hero of good that he was. Jambalaya approached the pedestal, a figure materialized in front of him, emerging from the shadows like a ghost. He was a boy of medium height, agile and quick, with short straight black hair that fell to the sides of his face and an aquiline nose. The new heroes recognized him immediately: he was Shinzo Hanzei, one of the evil heroes. It was hard to believe that one of their opponents was there, in front of them, even when they were trying to reclaim the Ivory Bow.&lt;/p&gt;', 6, 3, 'The first heroes are revealed', 0, 0, 0, '&lt;p&gt;Jambalaya and Shinzo Hanzei sprint towards the Ivory Arch. Ask the user for input the distance from the pedestal. Jambalaya can travel 3 meters in 10 seconds. Output how long it takes (in seconds) for Jambalaya to arrive at the pedestal, outputting where it is every 10 seconds.&lt;/p&gt;', 1),
(17, 'bc159f69-1e50-424a-bb5f-9465713425e0', '&lt;p class=&quot;MsoNormal&quot;&gt;Knowing that Shinzo Hanzei travels at 90 meters per second and the Ivory Bow arrow travels 110 meters per second and that Shinzo Hanzei has an advantage of Shinzo&#x27;s advantage over the arrow must be entered by the user and cannot be less than 800.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b34916f1.16953792_exercise_editor_6a33dd0ec64967.20077285.jpg&quot; alt=&quot;&quot;&gt; Shinzo Hanzei smiled mockingly, aware of the impact of his presence. &quot;You searched for the Ivory Bow, and now you have found it, but it will not be as simple as it seems,&quot; he said, his voice exuding evil self-confidence.&lt;/p&gt;\r\n&lt;p&gt;Shinzo Hanzei was incredibly fast, you couldn&#039;t follow his movements, such was his speed. Jamabalaya was no match for Shinzo&#039;s power! The two sprinted towards the pedestal, in less than 1 second Shinzo was there and placed his hands on the Bow. Jamabalaya was defeated, the Ivory Bow lost to the heroes of evil. But the Bow was not intended for an evil hero: a shock wave of impressive power unraveled from the pedestal, hitting Shinzo Hanzei squarely, who flew several meters from the weapon. Jambalaya also arrived at the small column and finally grabbed the Ivory Bow. The extraordinary power of the Bow was that it always hit the target. Only one arrow was available. Shinzo Hanzei rose from the ground, ready to sprint towards his opponent and end his life.&lt;/p&gt;', 6, 3, 'The conquest of the arch', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Knowing that Shinzo Hanzei travels at 9 meters per second and that the arrow hits him after 24 minutes, say after how many meters the arrow hits Shinzo, taking into account that Shinzo started 10 meters ahead. Then calculate how many seconds in 24 minutes, how many meters Shinzo traveled in those seconds, adding at the end the initial position, which the arrow must travel anyway.&lt;/p&gt;', 1),
(18, '59814944-310d-4dfa-bcc4-0d3c90b706aa', '', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b34f2cb3.59729515_exercise_editor_6a33de9d6c6f74.30073136.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Without hesitation, Jambalaya took aim, the gleaming arrow placed on the bow. Energy and focus floated in the air as the fates of the two heroes collided.&lt;/p&gt;\r\n&lt;p&gt;With a fluid and precise movement, Jambalaya released the arrow with the Ivory Bow, and it sliced through the air in the direction of Shinzo Hanzei with overwhelming speed. The evil hero had no time to react; the arrow hit him square in the chest and threw him backwards with unstoppable force.&lt;/p&gt;\r\n&lt;p&gt;Shinzo Hanzei found himself on the ground, seriously injured but still alive. The Ivory Bow had spared his life, but had ended his threat. The evil hero could no longer fight, but with the last of his energy he managed to get up and escape, taking advantage of his speed, which none of the good heroes could match.&lt;/p&gt;\r\n&lt;p&gt;With the Ivory Bow now in the hands of the new heroes of good, they were one step closer to reuniting the team and facing the dark threats that lurked in the shadows. Their adventure had just begun, but they were determined to complete it successfully for the good of all.&lt;/p&gt;', 18, 4, 'Shinzo\'s defeat', 4, 0, 0, '', 1),
(19, '4942fa50-81a2-4112-bc7e-bab52a646432', '&lt;p&gt;The heroes arrived in Munich. There was an important Champions League match and they stopped to watch it: Bayern against Real Madrid.&lt;/p&gt;\r\n&lt;p&gt;Create the following program in Python:&lt;/p&gt;\r\n&lt;p&gt;Ask the user for the names of 2 football teams and the goals scored. Print the winning team or if there was a tie.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b353eec1.47471224_exercise_editor_6a33df8329f5c0.47147843.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Summer 1940. Poland, Denmark, Norway, Belgium, the Netherlands and France fall under the terrifying power of the Blitzkrieg, the German lightning war. Hitler takes over most of Europe. There are 3 weapons available to Nazism: a powerful war industry that churns out tanks, battleships, weapons and ammunition in a continuous cycle, the Enigma machine, a machine for encrypting messages to be sent to the front that no one can interpret, even with the most brilliant minds available and finally, according to legend, the Spear of Destiny. A mystical object that seems to come from antiquity: it is said to be the spear used by the soldier to pierce the side of Jesus Christ during the crucifixion. Legends say that whoever possesses it can obtain extraordinary power or strength.&lt;/p&gt;\r\n&lt;p&gt;According to the Aionios map, the last appearance of the Spear of Destiny was during Operation Valkyrie, a plan devised by German army officers during World War II to overthrow the Nazi regime and kill Adolf Hitler. The operation failed on 20 July 1944 when a bomb planted by Claus von Stauffenberg exploded during a military meeting in Rastenburg, East Prussia. Although the explosion wounded Hitler, it did not kill him and the coup failed. But the operation achieved an important result: Baron von Fustenberg, having infiltrated Hitler&#039;s headquarters in Berlin, managed to steal the Spear of Destiny, taking it with him to Munich. From there the decline of the Third Reich began. The new heroes of good then headed towards the new stage in Germany, on the trail of the second weapon.&lt;/p&gt;', 10, 3, 'Blitzkrieg', 0, 0, 0, '', 0),
(20, 'a70aae8b-f5a3-4740-9a58-657673eb3e8b', '&lt;p&gt;The new heroes must search for the&lt;strong&gt;Baron&#x27;s diary&lt;/strong&gt;hidden inside one&lt;strong&gt;large library&lt;/strong&gt;.&lt;br&gt;To organize the search, the heroes decide to&lt;strong&gt;divide the library into 3 sections&lt;/strong&gt;.&lt;/p&gt;\r\n&lt;p&gt;1. Subdivision of the library&lt;/p&gt;\r\n&lt;p&gt;Using Python, generate&lt;strong&gt;two random numbers&lt;/strong&gt;(called&lt;code&gt;limit1&lt;/code&gt;e&lt;code&gt;limit2&lt;/code&gt;) with the function&lt;code&gt;random.randint&lt;/code&gt;.&lt;br&gt;Make sure the generated values meet these conditions:&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;code&gt;limit1&lt;/code&gt;it must be&lt;strong&gt;between 100 and 800&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;code&gt;limit2&lt;/code&gt;it must be&lt;strong&gt;between&lt;code&gt;limit1 + 1&lt;/code&gt;and 1500&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;div class=&quot;contain-inline-size rounded-md border-[0.5px] border-token-border-medium relative bg-token-sidebar-surface-primary&quot;&gt;\r\n&lt;div class=&quot;overflow-y-auto p-4&quot; dir=&quot;ltr&quot;&gt;&lt;code&gt;import random&lt;/code&gt;&lt;/div&gt;\r\n&lt;div class=&quot;overflow-y-auto p-4&quot; dir=&quot;ltr&quot;&gt;&lt;code&gt;limit1 = random.randint(100, 800)&lt;/code&gt;&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;p&gt;With these two values, divide the library as follows:&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;strong&gt;Section 1&lt;/strong&gt;: books numbered by&lt;strong&gt;1 year&lt;code&gt;limit1&lt;/code&gt;&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;strong&gt;Section 2&lt;/strong&gt;: books from&lt;strong&gt;&lt;code&gt;limit1 + 1&lt;/code&gt;a&lt;code&gt;limit2&lt;/code&gt;&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;strong&gt;Section 3&lt;/strong&gt;: books from&lt;strong&gt;&lt;code&gt;limit2 + 1&lt;/code&gt;to 2000&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;2. Calculation of probabilities&lt;/p&gt;\r\n&lt;p&gt;Every book has the&lt;strong&gt;same probability&lt;/strong&gt;to be the diary.&lt;br&gt;Calculate and print the probability that the diary is found in&lt;strong&gt;each of the three sections&lt;/strong&gt;, taking into account the number of books in each.&lt;/p&gt;\r\n&lt;p&gt;3. Discovery of the diary&lt;/p&gt;\r\n&lt;p&gt;Ask the user to enter the number of the book in which the journal was found.&lt;br&gt;Based on the number entered,&lt;strong&gt;indicates which section it belongs to&lt;/strong&gt; the book found&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3584b60.32954682_exercise_editor_6a33dfffbd7a15.11722470.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The city of Munich welcomed the new heroes of good with its picturesque streets and rich history, but it was also steeped in the dark marks left by the passage of the Nazi regime.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;With the information at their disposal they began to follow the traces left by Baron von Fustenberg, who, with the Spear of Destiny in his possession, had returned to his hometown, where he lived in a splendid castle. The new heroes knocked on the castle&#039;s doors, discovering that it was now inhabited by a renowned Munich doctor: Doctor Shubert. They explained the history of the spear and the doctor was immediately ready to help them, pointing out that Baron von Fustenberg had left a diary inside the library, but that it had never been found among thousands of other books.&lt;/p&gt;\r\n&lt;p&gt;The city of Munich welcomed the new heroes of good with its picturesque streets and rich history, but it was also steeped in the dark marks left by the passage of the Nazi regime.&lt;/p&gt;\r\n&lt;p&gt;With the information at their disposal they began to follow the traces left by Baron von Fustenberg, who, with the Spear of Destiny in his possession, had returned to his hometown, where he lived in a splendid castle. The new heroes knocked on the castle&#039;s doors, discovering that it was now inhabited by a renowned Munich doctor: Doctor Shubert. They explained the history of the spear and the doctor was immediately ready to help them, pointing out that Baron von Fustenberg had left a diary inside the library, but that it had never been found among thousands of other books.&lt;/p&gt;', 10, 3, 'Munich and the castle', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;New heroes must research the Baron&#x27;s diary inside the library. The heroes divide the library into 2 sections in which to research.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Get the appropriate Pythin function to get a random value, saving it to a variable&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;To use the random function, which allows you to obtain random values, in Python, you must first import the random library, then you can extract the random number in the following way:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;import random&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;random_value = random.randint(100,200)&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Inside the circles the minimum and maximum values for the random number. The random value must be between 1 and 100. Then ask the user for a value between 1 and 100. If the value given by the user is greater than the random number, say that the diary is in the EAST wing, otherwise say that it is in the WEST wing.&lt;/p&gt;', 1),
(21, 'd14fc90b-77ca-4eb0-80bb-e1093fd62f0f', '&lt;p&gt;Fortunately, on the shore of the lake the heroes find a boat. They get on it and start rowing.&lt;/p&gt;\r\n&lt;p&gt;Initially ask the user for two numbers to input. Then create a while loop that continues to ask for numbers until the number entered becomes less than the previous one, creating an increasing series. Each number represents the rows performed by the heroes within sight of the island. Output how many numbers have been entered in ascending order. Example: I read a=5, b=8: I enter the cycle because b&gt;a. I read 11, I move on, because 11&gt;8. I read 15 and move on because 15&gt;11. I read 7, I stop because it is smaller than 15. I read the 2 initial numbers + 2 other numbers in ascending order. Total 4 numbers.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b35d58a9.60613292_exercise_editor_6a33e172747f46.39255766.jpg&quot; alt=&quot;&quot;&gt;After a long search the heroes managed to find the Baron&#039;s diary. Leafing through it they managed to discover that beneath the castle there was a long secret passage and that at the end of the passage the Spear of Destiny was hidden, hidden so that no one could easily find it. The heroes walked through the tunnel: Jambalaya carried the Ivory Bow, his companions crowded around him. The tunnel was dark, dank, and silent, with only the faint whisper of the heroes&#039; footsteps echoing against the stone walls. Finally they came to a large cave, inside which was a large, ashen underground lake. In the center of the lake there was an island, a faint light came from inside it: here was the legendary weapon, resting on a pedestal similar to that of the Forest of Gems. The heroes now had to cross the lake.&lt;/p&gt;', 30, 3, 'The underground lake', 0, 0, 0, '&lt;p&gt;Fortunately, on the shore of the lake the heroes find a boat. They get on it and start rowing.&lt;/p&gt;\r\n&lt;p&gt;Ask the user for a number. Then create a while loop that continues to ask the user for numbers by adding them to the initial number, until this becomes greater than or equal to 100. Each loop represents the rows performed by the heroes in sight of the island. Output how many numbers have been entered. Example: I read n=8 initially, I enter the loop because 8&lt;100. I read 11, I move on, because 11+8&lt;100. I continue until the total sum becomes greater than or equal to 100. In a second variable I have to take into account how many times I have repeated the cycle.&lt;/p&gt;', 1);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(22, 'b1e499a0-c6a7-46b1-960a-c6c5968b6b4d', '', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3621185.11692861_exercise_editor_6a33e2ca86c960.01838555.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The heroes reached the shore of the island, and were therefore now close to conquering the second weapon of the ancient heroes. Fortunately this time there didn&#039;t seem to be any magical barrier blocking the path. As soon as you took a few steps towards your goal, however, the water of the lake began to ripple, creating real waves: from the waves a huge sea serpent emerged, which attacked the new heroes of good. Jambalaya took up his bow and began to hit the monster with the arrows he had found in a shop in Munich, but these didn&#039;t even seem to make a dent in him. As the waves crashed against the shore of the small island, the other good heroes tried to keep the sea serpent at bay with all the means at their disposal, but it seemed that the creature was protected by a force beyond their understanding.&lt;/p&gt;\r\n&lt;p&gt;The sea serpent&#039;s fury grew ever more intense, its glittering scales shining in the dim light of the water. Its sinuous tentacles flailed furiously, trying to grab the new heroes of good and drag them beneath the tumultuous surface of the lake.&lt;/p&gt;', 10, 2, 'The sea serpent', 6, 0, 0, '', 1),
(23, '35e68471-3231-4fc2-ab3b-e4f0c4d557c7', '&lt;p&gt;The sea serpent has 3 life points. Good heroes can attack a total of 6 times. 6 times extract a random number from 1 to 10, then ask the user for an input number (without showing the random number). If the number given in input by the user has a maximum distance of 2 numbers from the one randomly drawn, the snake loses 1 life point.&lt;/p&gt;\r\n&lt;p&gt;At the end of the cycle, communicate whether the heroes managed to defeat the snake or not.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b36843a9.55462443_exercise_editor_6a33e32856b087.59172171.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya, focused and determined, focused on a new approach. With the sharp glint of the Ivory Bow, he targeted the sea serpent&#039;s most vulnerable parts, trying to locate any weak spots. The arrows, shot with skill and strength, hit vital points, but once again it seemed that the snake was immune to attacks.&lt;/p&gt;\r\n&lt;p&gt;The tumultuous water made movement and coordination difficult, forcing the new heroes of good to fight with all their might to survive. It was a desperate battle, with the fate of the heroes hanging on the outcome of this epic clash.&lt;/p&gt;\r\n&lt;p&gt;As the sea serpent continued to engage the heroes in a fierce fight, Jambalaya realized that to defeat this mystical creature, they would need more than physical strength and fighting skill. They had to find a way to penetrate the snake&#039;s magical defense and exploit its hidden weaknesses to achieve victory.&lt;/p&gt;', 30, 3, 'The fight with the snake', 0, 0, 0, '&lt;p&gt;The sea serpent has 3 life points. Good heroes can attack a total of 6 times. Draw a random number from 1 to 10 6 times. If the random number is greater than 3, the snake loses 1 life point.&lt;/p&gt;\r\n&lt;p&gt;At the end of the cycle, communicate whether the heroes managed to defeat the snake or not.&lt;/p&gt;', 0),
(24, '6733803b-b199-4db1-8c81-ee61634f3afa', '&lt;p class=&quot;MsoNormal&quot;&gt;Write the program that solves the following problem: Aisha has 100 energy points, Isabela has 40 energy points. Aisha&#x27;s energy goes up 8% every minute, Isabela&#x27;s energy goes up 10% every minute. After how many minutes will Isabela have more energy than Aisha?&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b36d21d3.23276603_exercise_editor_6a33e3aac0fdd6.09484613.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;One of the girls in the group of new heroes, Isabela, a seventeen-year-old from Brazil, with a long braid of brown hair and green eyes, realized that the fight against the guardian was unequal: but the second weapon of the ancient heroes was not far away. With a sudden sprint, she began to run towards the pedestal where the Spear of Destiny resided, determined to take possession of it, in order to increase their attack force against the sea monster.&lt;/p&gt;\r\n&lt;p&gt;Isabela launched herself towards the pedestal, while her companions held the cave guardian at bay. Having arrived a few steps from the Spear of Destiny, she reached out to take the sacred relic, but something hit her violently, preventing her from taking the weapon. Turning to the right, Isabela found herself facing a second girl: it was Aisha, one of the evil heroes! They too were hunting for the weapons of the ancient heroes of good and Aisha, of whom the new heroes had been warned by Aionios, had also arrived at the place where the Spear was hidden. Events were taking an increasingly worse turn.&lt;/p&gt;', 30, 3, 'Isabela and Aisha', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Write the program that solves the following problem: Aisha has 100 energy points, Isabela has 40 energy points. Aisha&#x27;s energy goes up 8 points per minute, Isabela&#x27;s energy goes up 10 points per minute. After how many minutes will Isabela have more energy than Aisha? Use a while loop counting how many times you enter the loop, the loop continues until Aisha&#x27;s energy is higher than Isabela&#x27;s&lt;/p&gt;', 1),
(25, 'aebf8499-a818-4b56-a726-a75eb554ff3e', '&lt;p&gt;Define a list with 4 numbers and output a histogram based on these numbers, using question marks to draw it.&lt;/p&gt;\r\n&lt;p&gt;Each number indicates the probability of success for 4 different attack strategies by Isabela.&lt;/p&gt;\r\n&lt;p&gt;Use the FOR loop to iterate through the list and string multiplication to draw the asterisks. &quot;?&quot;*4 = &quot;????&quot;&lt;/p&gt;\r\n&lt;p&gt;Given, for example, the list [3, 7, 9, 5], the program will have to produce this sequence:&lt;/p&gt;\r\n&lt;p&gt;???&lt;/p&gt;\r\n&lt;p&gt;???????&lt;/p&gt;\r\n&lt;p&gt;?????????&lt;/p&gt;\r\n&lt;p&gt;???????&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3749ae7.95059460_exercise_editor_6a33e44b63ecc5.37542048.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Aisha had African origins and was known as the Black Panther. The nickname was not given to her by chance, in fact her power was that of having extraordinary agility: she was fast (although not as fast as Shinzo), she could perform jumps 20 meters long and 6 meters high and she was remarkably strong. Isabela had none of these skills!&lt;/p&gt;\r\n&lt;p&gt;With Aisha, the fearsome Black Panther, before her, Isabela found herself faced with an extremely dangerous situation. Aisha&#039;s superhuman abilities made her a formidable opponent, and Isabela knew she would have to act intelligently and quickly if she wanted to have even the slightest chance of surviving this fight.&lt;/p&gt;\r\n&lt;p&gt;As Aisha prepared to sprint towards her, Isabela recalled the basics of the training she had received from the other good heroes. She didn&#039;t have Aisha&#039;s extraordinary physical abilities, but she knew she had her own cunning and the ability to find weaknesses in even the most fearsome enemies.&lt;/p&gt;', 30, 3, 'Aisha\'s powers', 0, 0, 0, '', 1),
(26, '88a51cbc-52ec-4b86-a697-973497c94653', '&lt;p&gt;Isabela suddenly ducks in front of the stalagmite, Aisha is unable to contain her leap and collides with the rock formation.&lt;/p&gt;\r\n&lt;p&gt;Create an array (i.e. a list of elements of the same type) with 5 integers randomly drawn between 0 and 100. The program must calculate the multiplication of all the elements present in the array with a FOR cycle and print them on the screen. The numbers represent the force with which Aisha hits the stalagmite: if their product exceeds 10000 then it breaks and the slab above it falls on Aisha, otherwise the Black Panther bounces off the rock formation and saves himself. Say in output. which of the two options happens&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b37c33b7.46188886_exercise_editor_6a33e4d79e4599.10754033.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;With her heart beating furiously in her chest, Isabela tried to slow the pace of the battle, trying to use her surroundings to her advantage. He moved with agility and flexibility, trying to use his experience and courage to find a way out of this dangerous situation.&lt;/p&gt;\r\n&lt;p&gt;While Aisha rushed towards her with impressive determination, Isabela made an instinctive and bold decision. He realized he had to put his fear aside and focus on strategy. With a sudden movement, he dove to the side, trying to get out of Aisha&#039;s path and buy time to devise a plan that might give them a chance of survival.&lt;/p&gt;\r\n&lt;p&gt;The air vibrated with the energy and tension of this epic clash between two opposing forces, and Isabela prepared to prove that, despite her lack of superhuman abilities, she was still a formidable fighter, determined to champion the cause of good and protect the world from the danger that threatened to overwhelm it.&lt;/p&gt;\r\n&lt;p&gt;With determination imprinted in her movements, Isabela managed to dodge the first attack of Aisha, the Black Panther, but was not so lucky with the second, which threw her to the ground with unmatched force, leaving her breathless. &quot;Did you really think you could defeat us?&quot; Aisha asked contemptuously. Isabela gathered all her courage and, pretending that she no longer had the strength to fight, used Aionios&#039; techniques to obtain the greatest concentration possible, scanning the surrounding environment. &quot;One blow and you&#039;re already defeated, look at you, you don&#039;t even have the energy to move anymore, you&#039;re finished now!&quot; the Black Panther continued to tease her.&lt;/p&gt;\r\n&lt;p&gt;Finally Isabela understood what she had to do: a stalagmite supported the weight of a stone slab that had collapsed on it earlier. He suddenly stood up, quickly throwing himself towards the stalagmite. Aisha was taken by surprise, and reacted by making a prodigious leap towards Isabela.&lt;/p&gt;', 30, 3, 'The battle between Isabela and Aisha', 0, 0, 0, '&lt;p&gt;Isabela suddenly ducks in front of the stalagmite, Aisha is unable to contain her leap and collides with the rock formation.&lt;/p&gt;\r\n&lt;p&gt;Create an array (i.e. a list of elements of the same type) with 5 integers decided by you. The program must calculate the multiplication of all the elements present in the array with a FOR cycle and print them on the screen.&lt;/p&gt;', 1),
(27, '11f1ee31-ed1b-4c11-8fe8-c4551626c958', '&lt;p&gt;After having memorized in a list of numbers representing the damage inflicted by the 6 heroes of good on the sea monster (with a random), insert the best 3 damages, i.e. the 3 highest numbers from the first list, into a second list. Thanks to this damage and the power of the weapons of the ancient heroes of good, the new heroes manage to defeat the sea monster.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b38120e6.04990605_exercise_editor_6a33e55e5c1283.27045777.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Isabela dodged at the last second and Aisha was unable to stop her momentum, hitting the stalagmite squarely. The strength of the Black Panther crumbled the rock formation and the stone slab fell disastrously on Aisha, burying her. Isabela had managed to defeat her. The heroine of good then picked up the Spear of Destiny. A strange tingling sensation coursed through his entire body as he brandished the legendary weapon, the power of the Spear now radiating from Isabela, who ran to the rescue of her friends, still grappling with the monstrous sea serpent.&lt;/p&gt;', 29, 3, 'The conquest of the Spear', 0, 0, 0, '&lt;p&gt;After having stored in a list of numbers representing the damage inflicted by the 6 good heroes on the sea monster (with a random), calculate and indicate in output which is the largest number contained in the list.&lt;/p&gt;', 1),
(28, '7bf8ee1e-3376-418c-b9cd-9831ff0c4ea5', '&lt;p&gt;To pass the time while the sleds are being prepared, the heroes watch an episode of Big Bang Theory:&lt;/p&gt;\r\n&lt;p&gt;Professor Incredulous cannot understand the discovery made by his colleague Sheldon Cooper. In the corridor, Cooper confided his discovery to him: &quot;The square of every natural number n is equal to the sum of the first n odd numbers.&quot; Create a program that helps Professor Incredulous confirm or deny Sheldon&#x27;s thesis. Who will be right?&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b38688b9.74958591_exercise_editor_6a33e625bcb3b6.78685039.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The ship &lt;strong&gt;Endurance&lt;/strong&gt;, built in Norway in 1912, was designed to withstand extreme Arctic conditions. In 1914 Sir Ernest Shackleton decided to undertake one of the most daring undertakings in living memory: crossing Antarctica from coast to coast, via the South Pole. The motor sailing ship chosen to complete the journey was the Endurance. The ship departed with its crew under the command of Shackleton, soon arriving in the seas of the Antarctic Circle. Once she approached the land to cross, however, the ice surrounded her, trapping her. After 2 long winters spent trapped in the ice, the commander decided to go down to the pack and try to take to the sea with the lifeboats, after having dragged them to the first stretch of sea. The Endurance was finally crushed by the ice, sinking into the Arctic Sea. The sailors managed to put the lifeboats in the water, but they had to make a journey of 6,000 miles to reach the first inhabited island, South Georgia Island, through one of the stormiest seas on the planet, with waves above 12 meters and temperatures exceeding twenty below zero: the lifeboat was 6 meters long in total. The legend narrated in the map of Aionios said that in order not to die of cold, the sailors had with them a sword, capable of releasing intense heat: one of the weapons of the ancient heroes, it was most likely found in South Georgia.&lt;/p&gt;\r\n&lt;p&gt;Having reached the island, surrounded by ice, the new heroes purchased sleds pulled by packs of dogs to be able to travel inland.&lt;/p&gt;', 30, 3, 'Endurance', 0, 0, 0, '&lt;p&gt;To pass the time while the sleds are being prepared, the heroes puzzle over a question found in a puzzle magazine:&lt;/p&gt;\r\n&lt;p&gt;If you add the first 15 odd numbers you get the square of 15. Is this statement true or not? Use a loop to add the first 15 odd numbers: 1,3,5,7,9,11,13,15,17,19.... and tell the output if the result actually equals the square of 15.&lt;/p&gt;', 2),
(29, '7b476f9c-c21d-41ab-a4e4-1db1681b0275', '&lt;p&gt;Create a list of integers with 10 random elements between -5 and -60 representing the freezing temperatures faced by the heroes (using a FOR loop and the append method). Calculate the average of the elements in the list. Counts the components of the list that have a value above the average, using a FOR loop&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b38fce66.45766742_exercise_editor_6a33e72793d987.42274597.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The new heroes set off in their sleds. After an hour of travel the wind began to intensify: a storm was now approaching. The heroes decided to continue the journey anyway, their mission was too important.&lt;/p&gt;\r\n&lt;p&gt;With the wind increasing in intensity and the storm looming menacingly, the new heroes found themselves having to face an increasingly difficult and dangerous path. Despite the adversities that piled up around them, their determination to complete their mission did not waver. They would face any challenge to achieve their goal and protect the world from the dark forces that threatened to overwhelm it.&lt;/p&gt;\r\n&lt;p&gt;With their cloaks flapping wildly in the wind and their sleds pushing determinedly through the oncoming blizzard, the new heroes mentally prepared themselves for the arduous journey ahead. Their unity and determination to overcome any difficulty would guide them through this storm, as they had done so many times before.&lt;/p&gt;\r\n&lt;p&gt;With the horizon darkening more and more and the wind howling around them, the new heroes of good would have to combine their strength and their cunning to overcome the adversities they were about to face. The storm could not have extinguished the flame of their will, and with the courage and perseverance that characterized them, they would have overcome even this obstacle on their path to victory.&lt;/p&gt;', 29, 3, 'The ice storm', 0, 0, 0, '&lt;p&gt;Create a list of integers with 10 random elements between 5 and 60 representing the freezing temperatures faced by the heroes (using a FOR loop and the append method). Count the components of the list that have a value greater than 31, using a FOR loop, and output the counted number&lt;/p&gt;', 1),
(30, '551746ef-d572-4434-b54b-fb18b658c619', '&lt;p&gt;Create an empty list. The list contains Klaus&#x27; dogs.&lt;/p&gt;\r\n&lt;p&gt;Create a menu with several print()s to let the user choose what they want to do, placing it in a while loop:&lt;/p&gt;\r\n&lt;p&gt;1 - Insert a new dog in the list, with its name&lt;/p&gt;\r\n&lt;p&gt;2 - Remove the last item on the list, to indicate an exhausted dog&lt;/p&gt;\r\n&lt;p&gt;3 - Exit the program&lt;/p&gt;\r\n&lt;p&gt;Then read the user&#x27;s choice from input. If the user chooses 3 the loop is exited. If the user chooses 1 the program asks for the name of the dog to be included in the list. The word entered by the user is inserted into the list and the list is printed, then the menu is displayed again. If the user chooses 2, the last element of the list is removed and the list is printed on the screen, then the user is returned to the menu. If the user chooses a number other than 1,2 or 3 an error message is displayed, then the menu is reprinted.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3968118.16594403_exercise_editor_6a33e7af115a01.66868787.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The storm raged around the heroes. Klaus, a German boy in the group with short blond hair, began to fall behind, until he realized he could no longer see his companions. Terror gripped the boy, who began to call his companions out loud, but no response came from the main group. The dogs pulling the sled were exhausted. Klaus had to find shelter as soon as possible or he would freeze to death.&lt;/p&gt;\r\n&lt;p&gt;As he continued to call his companions and try to locate them in the darkness of the storm, Klaus forced himself to remain calm and focus on how to face the situation with clarity and promptness. Survival was not only a matter of courage, but also required cunning and the ability to adapt to react timely and appropriately to changing circumstances.&lt;/p&gt;\r\n&lt;p&gt;With snowflakes beating in his face and the wind whistling in his ears, Klaus pushed forward tenaciously, trying to locate any form of shelter or shelter that might offer a modicum of protection against the fury of the storm. With the dogs sensing anxiety and fear in his behavior, he knew he had to find a quick and effective solution to protect himself and his loyal companions.&lt;/p&gt;', 29, 3, 'Klaus gets lost', 0, 0, 0, '&lt;p&gt;Create an empty list. The list contains Klaus&#x27; dogs.&lt;/p&gt;\r\n&lt;p&gt;Create a menu with several print()s to let the user choose what they want to do, placing it in a while loop:&lt;/p&gt;\r\n&lt;p&gt;1 - Insert a new dog in the list, with its name&lt;/p&gt;\r\n&lt;p&gt;2 - Exit the program&lt;/p&gt;\r\n&lt;p&gt;Then read the user&#x27;s choice from input. If the user chooses 2 the loop is exited. If the user chooses 1 the program asks for the name of the dog to be included in the list. The word entered by the user is inserted into the list and the list is printed, then the menu is displayed again.&lt;/p&gt;', 2),
(31, 'b4eee5cd-3992-46e7-bd56-894a8ebd1edc', '&lt;p&gt;Given the list of the 6 original good heroes contained in one list and a list of the respective 6 weapons contained in a second list (hero and respective weapon must be in the same position in the two lists), ask the user the name of a hero, find his index (use the list&#x27;s index method: https://www.w3schools.com/python/ref_list_index.asp) and tell the user the weapon related to the hero. If the hero requested by the user is not in the list, it reports an error&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b39f2e13.67082189_exercise_editor_6a33e831ba4b16.84798659.jpg&quot; alt=&quot;&quot;&gt;Klaus&#039; instinct guided him into the darkness of the storm, he veered to the right, inciting the pack to make one last effort. An icy rock wall suddenly loomed ahead, the dogs braked, Klaus led them along the wall, looking for a broken man to take refuge in. And then at a certain point the wall opened, Klaus directed the sled inside the gully. Dodging various ice formations, after a few hundred meters, the hero noticed that the storm around him calmed down. The long path eventually led him to a larger sheltered area, in the center of which stood a pedestal. On the pedestal was the flaming sword, which warmed the area and kept away the cold of the storm. He had found the ancient weapon for which they had come all this way.&lt;/p&gt;', 29, 3, 'The Sword', 0, 0, 0, '&lt;p&gt;Given the list of 6 original good heroes contained in a list, ask the user for the name of a hero and tell the user whether the hero is present in the list or not&lt;/p&gt;', 1),
(32, '0d87a51f-556d-4239-bbf4-6a81ba1c12da', '&lt;p class=&quot;MsoNormal&quot;&gt;Create 2 sets with the colors of the good heroes and one with the colors of the evil heroes (you decide at least three colors for each of the 2 sets). List the colors common to the two sets using intersection, the colors of both, using union.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3a78b09.76645653_exercise_editor_6a33e8b3b58681.97087442.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Klaus got out of the sleigh, approaching the pedestal. Compared to other times, the conquest of the Flaming Sword seemed simple. Too simple. The boy turned to the right, turned to the left, certain that something would interrupt his grip on the weapon. And instead it seemed that nothing was stopping him from approaching: no evil hero nearby, nor monsters guarding the sword. He took the weapon in his hand, gripping the hilt. He immediately felt an intense heat pervade him, which continued to rise. The heat gradually became more and more unbearable, until it made him think he was burning alive. Klaus fell unconscious.&lt;/p&gt;\r\n&lt;p&gt;As Klaus struggled to stay conscious, he suddenly found himself in a different world, far from the storm and his team of good heroes. He found himself in a bare and desolate land, where the land was a barren desert and the sky was tinged with an intense red. In the distance, he saw a dark figure silhouetted against the horizon, approaching with a determined step.&lt;/p&gt;', 29, 3, 'The Dark World', 0, 0, 0, '', 0),
(33, '0943cc86-ad81-4aa0-b3a6-146c6f4eff6f', '&lt;p class=&quot;MsoNormal&quot;&gt;Create a dictionary that represents the people devastated by Lachlan through the dark power of nightmares, where the key is the name of the people and the value is the number of members of that people. Prints the number of people of a population requested in input. Prints the list of all peoples with the number of people belonging to them using a for loop&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3ae0b01.14834881_exercise_editor_6a33e90134a863.92511372.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;She soon realized that he was one of the evil heroes, a sinister figure who carried with him an aura of malevolence and darkness. As the figure approached, Klaus stood up with the Flaming Sword still firmly in his hand, feeling the blazing heat of the blade pulsing with an unknown power.&lt;/p&gt;\r\n&lt;p&gt;With determination burning within him, Klaus prepared to face his adversary, knowing that the fate of the world depended on the outcome of this battle. His mind focused on the flaming blade, trying to understand how it could be used to defeat the evil hero who approached menacingly.&lt;/p&gt;\r\n&lt;p&gt;With a cry of defiance, Klaus lunged towards his opponent, Flaming Sword in hand, cutting the air with blinding light and enveloping heat. The evil hero did not flinch from the attack, but prepared to counterattack with his own weapon, hurling a storm of shadow and darkness at Klaus.&lt;/p&gt;\r\n&lt;p&gt;The ensuing fight was a frenetic ballet of strikes and kicks, the fiery energy of the Flaming Sword clashing with the twisted darkness of the evil hero. Klaus found himself forced to fight with all he was, drawing on his determination and the strength of his spirit to resist the darkness that threatened to overwhelm him.&lt;/p&gt;\r\n&lt;p&gt;The evil hero Klaus faced in his dream world was known as Lachlan the Destroyer. With a past shrouded in mystery and darkness, Lachlan was feared for his mastery of the dark arts and his overwhelming power that swept across entire lands in a whirlwind of destruction and chaos.&lt;/p&gt;', 29, 3, 'Lachlan the Destroyer', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Create a dictionary that represents the people devastated by Lachlan through the dark power of nightmares, where the key is the name of the people and the value is the number of members of that people. Ask for the name of a population and print the number associated with it, using the dictionary.&lt;/p&gt;', 1),
(34, '73d8b8b7-03f9-4052-896b-1c7530dafa1a', '&lt;p&gt;Create the set of numbers from 1 to 100. Simulate a game in which the first player, Klaus, draws a number at random from the set and the second player, Lachlan, draws a second number. The hero with the highest number wins the battle and gains 1 point. The game continues until there are numbers in the set. At the end of the cycle the program communicates which of the two heroes won the war by winning more single battles or if there is a draw.&lt;/p&gt;\r\n&lt;p&gt;Instead of the set you can also use a list and use: import random and random.shuffle(list) to shuffle the elements of the list&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3b45ab6.13993683_exercise_editor_6a33e97fc32299.58139254.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;His presence was accompanied by an aura of malevolence and terror, and his gaze was cold and devoid of compassion. He had gone through countless battles and held the hearts of his enemies with immeasurable terror. With superhuman strength and relentless determination, Lachlan had earned the respect and fear of those who dared challenge his authority.&lt;/p&gt;\r\n&lt;p&gt;Klaus against Lachlan, light against darkness, good against evil, the Flaming Sword against the Power of Shadows. Lachlan attacked Klaus with his terrible power. Klaus&#039; greatest fears began to invade his mind: to his right an expanse of spiders was approaching threateningly, brought there by his arachnophobia. On his left is the image of his father, who throughout his life had made him feel inadequate and not up to his expectations. His father was chastising him for failing to get a passing grade on his latest history test. Faced with his greatest fear: the image of himself, alone and abandoned by everyone. Without friends, without love, lost..&lt;/p&gt;\r\n&lt;p&gt;However, Klaus, armed with the Flaming Sword, was not so easily overwhelmed. With every fiber of his being, he tried to push away the terrifying visions that threatened to paralyze him. The Sword&#039;s fiery blade glowed with a bright light, counteracting the darkness Lachlan sought to cast upon him.&lt;/p&gt;', 29, 3, 'The Flaming Sword against the Power of Shadows', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Create the set of numbers from 1 to 100, inserting them with a for loop into an initial empty set. Extract a random number from the set with the pop method. Extract a second number from the set by saving both to variables. If the first number is greater than the second it prints in output: &quot;Lachlan wins&quot;, otherwise &quot;Klaus wins&quot;&lt;/p&gt;', 1),
(35, '06162067-6b7a-4b3e-897c-161d153332e5', '&lt;p&gt;Every hero of good and evil seen so far belongs to a State of the World. Klaus from Germany, Lachlan from Australia, Aisha from Ivory Coast, Isabela from Portugal, Shinzo from Japan and Jambalaya from Jamaica.&lt;/p&gt;\r\n&lt;p&gt;Declare the 6 heroes within a data structure (dictionary), associating them with their respective states. Create a menu to allow the user to choose one of the following:&lt;/p&gt;\r\n&lt;p&gt;• Add a new hero to the data structure, with his State of Origin&lt;/p&gt;\r\n&lt;p&gt;• View heroes in alphabetical order&lt;/p&gt;\r\n&lt;p&gt;• Given a State, print the hero who comes from that State&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3bb5d62.41363539_exercise_editor_6a33ea5101b248.38778990.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;With a cry of defiance, Klaus lunged forward, using the Sword to cut through the illusions surrounding him. The sea of ​​spiders turned out to be just a deception, as was the image of the disappointing father. Faced with his greatest fear, the image of himself abandoned, Klaus decided to face his inner loneliness and transform it into strength. He wasn&#039;t alone, he had 5 companions who were looking for him in the midst of the storm, who loved him and would never abandon him.&lt;/p&gt;\r\n&lt;p&gt;The Flaming Sword responded to Klaus&#039; will, increasing its brightness and repelling Lachlan&#039;s Shadows.&lt;/p&gt;\r\n&lt;p&gt;Lachlan, impressed by Klaus&#039; determination, attempted to intensify the Power of the Shadows, trying to create even more frightening illusions. However, Klaus, with the Sword&#039;s flame dancing ever more vibrantly, withstood the attack and steadfastly advanced.&lt;/p&gt;\r\n&lt;p&gt;With a powerful blow, Klaus hurled the Sword at Lachlan, creating a blast of light that sent the evil hero reeling back. The Shadows retreated, unable to compete with the radiant power of the Flaming Sword.&lt;/p&gt;\r\n&lt;p&gt;Lachlan, weakened and surprised by Klaus&#039; steadfastness, tried to resist, but the light of the Sword completely enveloped him. His figure gradually dissolved into darkness.&lt;/p&gt;\r\n&lt;p&gt;With Lachlan&#039;s defeat, the dream world was transformed. The arid desert transfigured into a luminous land, and the red sky dissolved into shades of blue. Klaus, still supported by the light of the Sword, found himself standing amidst a renewed landscape.&lt;/p&gt;\r\n&lt;p&gt;The team of good heroes, who had lost sight of him during the storm, appeared on the horizon. With the Flaming Sword still in hand, Klaus greeted them with a smile of triumph. Light had triumphed over darkness, and the mission to protect the world could continue with renewed spirit and determination.&lt;/p&gt;', 29, 3, 'Klaus\' victory', 0, 0, 0, '&lt;p&gt;Every hero of good and evil seen so far belongs to a State of the World. Klaus from Germany, Lachlan from Australia, Aisha from Ivory Coast, Isabela from Portugal, Shinzo from Japan and Jambalaya from Jamaica.&lt;/p&gt;\r\n&lt;p&gt;Declare the 6 heroes within a data structure (dictionary), associating them with their respective states.&lt;/p&gt;\r\n&lt;p&gt;Input ask for the name of a State and, if this is present in the dictionary, print the Hero who comes from that State.&lt;/p&gt;', 2),
(36, 'f0de20e8-6c46-49d2-ad37-7a9000c0c661', '&lt;p&gt;The new heroes must calculate the height from which the Nazca drawings are visible. To do this you need to calculate a step-by-step power. Write a program in Python that prompts the user to enter a base and an integer exponent. The program should then calculate and print the power result using a FOR loop and subsequent multiplications.&lt;/p&gt;\r\n&lt;p&gt;Instructions:&lt;/p&gt;\r\n&lt;p&gt;Ask the user to enter the base (an integer).&lt;/p&gt;\r\n&lt;p&gt;Prompt the user to enter the exponent (an integer).&lt;/p&gt;\r\n&lt;p&gt;Use a for loop to calculate the power of the base raised to the exponent.&lt;/p&gt;\r\n&lt;p&gt;Prints the result with an explanatory message.&lt;/p&gt;\r\n&lt;p&gt;Handle the case where the exponent is zero (the power of any number raised to zero is 1).&lt;/p&gt;\r\n&lt;p&gt;Remember to comment your code to explain the main sections and make your work understandable.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3c31958.69789540_exercise_editor_6a33eb2fac49d7.82103662.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;416 AD Peru. The Nazca civilization was at its peak. The drawings of the monkey, tarantula and hummingbird were now complete. The visitor from the heavens was about to have his own performance, which was well underway. Visible only from above thanks to the visitor&#039;s aircraft, the drawings represented the maximum expression of Peruvian civilization. Always dedicated to agriculture, the Nazca people were going through a decidedly difficult period that year: an extreme drought had struck the region, a climate change never seen before, which had begun when the visitor from the skies had arrived through the storm, bringing with him the shining oval. Crops were dry and famine was imminent. Revered as a deity, the visitor to the heavens, although he seemed to have the power to change the seasons, cared little for the people. His sole purpose was the construction of a great Ziggurat, which could reach to the sky.&lt;/p&gt;\r\n&lt;p&gt;600 AD The Nazca civilization had disappeared due to climate change. No one remembered the visitor from the skies anymore, who also disappeared once the great Ziggurat was finished. Only the drawings remained of the glorious nation. Even the Ziggurat seemed to have vanished into thin air. But the map of Aionios indicated that the next weapon of the Heroes of Good resided exactly there. The new heroes now had to travel to Peru, in an attempt to also find the Golden Shield, to increase their defensive power against the evil heroes.&lt;/p&gt;', 30, 3, 'The Nazca drawings', 0, 0, 0, '&lt;p&gt;The new heroes must calculate the height from which the Nazca drawings are visible. To do this you need to calculate a step-by-step power. Write a program in Python that prompts the user to enter a base. The program should then calculate and print the result of the base raised to the power of 5 using a FOR loop and subsequent multiplications. For example, 3 raised to the power of 5 will be 3*3*3*3*3&lt;/p&gt;\r\n&lt;p&gt;Instructions:&lt;/p&gt;\r\n&lt;p&gt;Ask the user to enter the base (an integer).&lt;/p&gt;\r\n&lt;p&gt;Use a for loop to calculate the power of the base raised to the exponent.&lt;/p&gt;\r\n&lt;p&gt;Prints the result with an explanatory message.&lt;/p&gt;\r\n&lt;p&gt;Remember to comment your code to explain the main sections and make your work understandable.&lt;/p&gt;', 1),
(37, '65531a20-413e-4cd0-8d67-fb4975564918', '&lt;p&gt;Each of the new heroes decided to cross a different one of the bridges. However, the bridges turn out to be a trap. 5 of them start to collapse once the heroes get halfway.&lt;/p&gt;\r\n&lt;p&gt;The heroes must run back faster than the collapsing bridges, jumping over odd and even stones.&lt;/p&gt;\r\n&lt;p&gt;Write a program in Python that asks the user to enter a sequence of 7 integers. The program should then calculate and print two results:&lt;/p&gt;\r\n&lt;p&gt;The total sum of the even numbers entered.&lt;/p&gt;\r\n&lt;p&gt;The sum of the cubes (to the third power) of the odd numbers entered.&lt;/p&gt;\r\n&lt;p&gt;Instructions:&lt;/p&gt;\r\n&lt;p&gt;Use a for loop to input the 7 integers one at a time.&lt;/p&gt;\r\n&lt;p&gt;For each number, check whether it is even or odd.&lt;/p&gt;\r\n&lt;p&gt;Calculate the total sum of even numbers and the sum of the cubes of odd numbers.&lt;/p&gt;\r\n&lt;p&gt;Print both results.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3cb2096.56272913_exercise_editor_6a33ebeea84dd8.88417891.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The new heroes of good, guided by the map of Aionios, ventured into the lands of Peru in search of the Golden Shield, convinced that it was hidden in the remains of the disappeared Nazca civilization. The desert landscape carried with it the echo of a glorious past, now buried under sand and oblivion.&lt;/p&gt;\r\n&lt;p&gt;They reached the region one morning of radiant sunshine, but the parched land carried with it memories of difficult times.&lt;/p&gt;\r\n&lt;p&gt;Aionios&#039; map was clear: proceeding north of the tarantula, until the shadow of the star descends to the north indicating the way of the llama, descend like a dolphin diving, there the Ziggurat will be found. The new heroes followed the directions: and there it was, the imposing Ziggurat, before their eyes. The building was surrounded by a large crack in the ground, a ravine whose bottom could not be seen. 6 bridges crossed it to take visitors to the pyramid. The air around vibrated with an ancient energy.&lt;/p&gt;\r\n&lt;p&gt;Cautiously, the heroes began to cross the bridges over the ravine, knowing that their fate was linked to this sacred place. Every step they took on those ancient stone bridges brought with it the echo of a distant past, of a civilization that had tried to touch the sky and appease the gods.&lt;/p&gt;', 30, 3, 'The Ziggurat', 0, 0, 0, '&lt;p&gt;Each of the new heroes decided to cross a different one of the bridges. However, the bridges turn out to be a trap. 5 of them start to collapse once the heroes get halfway.&lt;/p&gt;\r\n&lt;p&gt;The heroes must run back faster than the collapsing bridges, jumping over odd and even stones.&lt;/p&gt;\r\n&lt;p&gt;Write a program in Python that asks the user to enter a sequence of 7 integers. The program should then calculate and print:&lt;/p&gt;\r\n&lt;p&gt;The sum of the cubes (to the third power) of the odd numbers entered.&lt;/p&gt;\r\n&lt;p&gt;Instructions:&lt;/p&gt;\r\n&lt;p&gt;Use a for loop to input the 7 integers one at a time.&lt;/p&gt;\r\n&lt;p&gt;For each number, check whether it is even or odd.&lt;/p&gt;\r\n&lt;p&gt;Calculate the sum of the cubes of odd numbers.&lt;/p&gt;\r\n&lt;p&gt;Print the result&lt;/p&gt;', 1),
(38, '7e6069e1-414a-44de-a96c-7f479f12257f', '&lt;p&gt;The door has a lock made of a disc containing various numbers.&lt;br&gt;In order to proceed through the door, the new heroes of good must decipher the symbols around it and insert a number into the lock disk. They know that the Nazca revered vowels.&lt;br&gt;Write a program in Python that asks the user to enter a sentence. The program should then count and print the number of vowels present in the sentence, a number that will be inserted into the disk. Considers only lowercase vowels (a, e, i, o, u).&lt;/p&gt;\r\n&lt;p&gt;Instructions:&lt;br&gt;Use a for loop to iterate through each character of the entered sentence.&lt;br&gt;Count the total number of lowercase vowels in the sentence.&lt;br&gt;Prints the total number of vowels and the vowels themselves.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3d20348.96913395_exercise_editor_6a33ec82dc7215.14577392.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The new heroes managed to escape the trap of the bridges. Crossing the only bridge that remained intact, chosen by Nuliajuk, an Inuit girl, the boys reached the Ziggurat, which they began to climb. Having reached the top of the structure, the heroes found a spacious platform and a secret entrance that led inside the majestic pyramid. Once beyond the threshold, they were greeted by an atmosphere full of mystery, with intricate corridors and rooms adorned with ancient symbols that told the story of the Nazca civilization.&lt;/p&gt;\r\n&lt;p&gt;They proceeded along the corridor, which entered the meanders of the Ziggurat, gradually becoming quieter, cooler and shadier. They finally found themselves in a large room. In front of them is a wall with a door in the center, surrounded by strange symbols.&lt;/p&gt;', 30, 3, 'Nuliajuk and the entrance to the Ziggurat', 0, 0, 0, '&lt;p&gt;The door has a lock made of a disc containing various numbers.&lt;br&gt;In order to proceed through the door, the new heroes of good must decipher the symbols around it and insert a number into the lock disk. They know that the Nazca revered vowels.&lt;br&gt;Write a program in Python that asks the user to enter a sentence. The program should then count and print the number of vowels present in the sentence, a number that will be inserted into the disk. Considers only lowercase vowels (a, e, i, o, u).&lt;/p&gt;\r\n&lt;p&gt;Instructions:&lt;br&gt;Use a for loop to iterate through each character of the entered sentence.&lt;br&gt;Count the total number of lowercase vowels in the sentence.&lt;br&gt;Prints the total number of vowels&lt;/p&gt;', 1),
(39, '13c1812f-c9db-473b-aa52-4f8b7ac35f41', '&lt;p&gt;The new heroes try to reach the room where the Golden Shield is located, perhaps it can help them eliminate the poison. But their thoughts are confused and the path they follow seems to reverse.&lt;/p&gt;\r\n&lt;p&gt;Create a list of random numbers, containing 10 elements between 10 and 50.&lt;/p&gt;\r\n&lt;p&gt;Use import random to import the random number module and random.randint(min,max) to extract the number to add to the list.&lt;/p&gt;\r\n&lt;p&gt;Print the list thus obtained.&lt;/p&gt;\r\n&lt;p&gt;Using the pop and append methods, extract all the elements of the first list and insert them into a second list, creating an inverse list of the initial one. Print the reverse list.&lt;/p&gt;\r\n&lt;p&gt;Example: initial list [1,2,3], reverse list [3,2,1]&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3da4d93.87487178_exercise_editor_6a33edeef31ef8.24731766.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The heroes passed through the door, finding themselves facing a new long tunnel. They walked along the street. After what seemed like a kilometer of walking, Klaus suddenly tripped over a protruding stone. The stone set in motion a mechanism that triggered a further trap. Darts began to fly from the walls, hitting Jambalaya below the knee and Klaus in the side. Isabela, thanks to the power of the spear, managed to dodge the arrows and Nuliajuk, who was at the head of the group, managed to reach safety with a cat-like dive. The other 2 boys were also hit. Once the volley was over, Nuliajuk, who had trained as a healer among her people, tried to understand how serious the wounds were. The darts were not too long and had penetrated little deeply into the bodies of their companions, but their tips were poisoned: a layer of dark green substance was found in the cusps.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk carefully examined the wounds caused by the poisoned darts. The dark green substance covering the tips clearly indicated the presence of a potent poison. The young Inuit healer knew that time to act was crucial.&lt;/p&gt;\r\n&lt;p&gt;With intense concentration, Nuliajuk made use of her knowledge of herbal medicine and her ability as a healer. He extracted some healing herbs and began to prepare an ointment on the skin of the wounded. With confident gestures, he applied the healing mixture to the wounds of Jambalaya and Klaus, trying to neutralize the poison and promote healing.&lt;/p&gt;\r\n&lt;p&gt;However, the situation was delicate. The poison had already begun to spread through their bodies, and Nuliajuk knew that the true cure required more than just a superficial remedy. They had to find the right antidote, and time was running out.&lt;/p&gt;\r\n&lt;p&gt;The heroes, although weakened, decided to continue their journey through the tunnel, aware that each step could bring them closer not only to the antidote but also to new deadly challenges. With Isabela&#039;s spear in hand and Nuliajuk&#039;s wisdom, they walked into the darkness, ready to face any obstacle that fate had in store for them.&lt;/p&gt;', 29, 3, 'The Ziggurat Traps', 0, 0, 0, '', 0),
(40, '78d37ecb-4b76-498d-b5a9-a6d7dd8279b1', '&lt;p class=&quot;MsoNormal&quot;&gt;The Golden Shield must be activated in order to function. Create a 4x4 matrix (list of lists) and insert random numbers between 1 and 10 into it. Get the sum of the numbers in each row. The sums in sequence will be the shield activation code. Print the activation code.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3e0b0c0.85261319_exercise_editor_6a33ee57247846.39328177.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The new heroes finally arrived at a large room. The room didn&#039;t seem to be from the past, but rather from the future. Halogen lamps illuminated it, the walls were covered with metal panels.&lt;/p&gt;\r\n&lt;p&gt;Leaning against a wall were some components that looked like electronics, metal tables and chairs... And in the center of the room, a pedestal, on which the Golden Shield was placed. The heroes hit by the bolts were too weak and collapsed at the exit of the tunnel. Isabela remained with them to check on their health, while Nuliajuk advanced to take the shield. One of the metal panels began to rise, a shadow-cloaked figure emerged from it: it was the visitor from the skies!&lt;/p&gt;\r\n&lt;p&gt;The visitor from the skies loomed in the room, shrouded in impenetrable shadow. His figure was tall and slender, and his eyes shone with an ancient light. Nuliajuk stopped in front of the pedestal, looking carefully at the figure.&lt;/p&gt;\r\n&lt;p&gt;&quot;Visitor of the heavens, we are the heroes of good, sent by Aionios to protect the world from the threat of evil heroes. We need the Golden Shield to fight the dark forces. Can we enlist your help?&quot;, asked Nuliajuk respectfully, trying to convey their peaceful intent.&lt;/p&gt;\r\n&lt;p&gt;The visitor did not answer. From the shadows behind it a new figure stepped out. Nuliajuk recognized her: it was Camila, one of the evil heroines..&lt;/p&gt;\r\n&lt;p&gt;Camila, emerging from the shadows, slowly advanced into the room. His dark presence contrasted with the radiant light of the Golden Shield, creating an atmosphere of tension in the air. Nuliajuk grabbed the Golden Shield, taking it from the pedestal.&lt;/p&gt;', 29, 3, 'The Visitor of the Skies and Camila', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;The Golden Shield must be activated in order to function. Create a 4x4 matrix (list of lists) and insert random numbers between 1 and 10 inside it. To do this, create 4 empty lists, into which to insert the numbers. Then insert the 4 lists with append into a further empty list. Get the total sum of the numbers in the matrix and print the obtained sum.&lt;/p&gt;', 2);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(41, 'be758d8a-771b-47e7-a8bd-5b4a246ae2e6', '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Nuliajuk must use a second power of the Golden Shield: the power of healing, to be able to eliminate the poison from his companions and make them participate in the fight. To do this he must press in the correct sequence a series of numbers printed inside the shield.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Create a 5x5 matrix with random elements between 1 and 100.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Print the matrix and then print its transpose. The transpose of a matrix A is a matrix B where the rows and columns are exchanged.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;For example the transpose of A=&lt;/span&gt;&lt;/p&gt;\r\n&lt;table class=&quot;MsoNormalTable&quot; style=&quot;width: 100.0%; background: white; border-collapse: collapse; mso-yfti-tbllook: 1184; mso-padding-alt: 0cm 0cm 0cm 0cm;&quot; border=&quot;0&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 0; mso-yfti-firstrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;1&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;2&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;3&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 1;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;4&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;5&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;6&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 2; mso-yfti-lastrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;7&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;8&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;9&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;&lt;br&gt;It&#x27;s B=&lt;/span&gt;&lt;/p&gt;\r\n&lt;table class=&quot;MsoNormalTable&quot; style=&quot;width: 100.0%; background: white; border-collapse: collapse; mso-yfti-tbllook: 1184; mso-padding-alt: 0cm 0cm 0cm 0cm;&quot; border=&quot;0&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 0; mso-yfti-firstrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;1&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;4&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;7&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 1;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;2&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;5&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;8&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 2; mso-yfti-lastrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;3&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;6&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#x27;inherit&#x27;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;9&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;p&gt;&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3e65ad0.09762188_exercise_editor_6a33ef0e9ce087.68320779.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;&ldquo;You have obtained the Golden Shield, but light cannot exist without shadow,&rdquo; Camila said in an ominous voice.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk, firmly, faced Camila. &quot;We are here to protect the world from the threat of evil heroes. We will not allow the darkness to take over.&quot;&lt;/p&gt;\r\n&lt;p&gt;Camila smiled mischievously. &quot;Protect the world? We&#039;ll see how well you can do it when the shadow spreads everywhere. The fate is already written.&quot;&lt;/p&gt;\r\n&lt;p&gt;Camila tried to use her power against Nuliajuk: the evil heroine was in fact able to control minds, causing people to do her will. Nuliajuk raised the Golden Shield. Her power protected her against Camila&#039;s.&lt;/p&gt;', 29, 3, 'The power of the Golden Shield', 0, 0, 0, '&lt;p&gt;Nuliajuk must use a second power of the Golden Shield: the power of healing, to be able to eliminate the poison from his companions and make them participate in the fight. To do this he must press in the correct sequence a series of numbers printed inside the shield.&lt;/p&gt;\r\n&lt;p&gt;Create a 5x5 matrix with random elements between 1 and 100.&lt;/p&gt;\r\n&lt;p&gt;Print the matrix to wrap each line.&lt;/p&gt;', 2),
(42, 'd98b9efd-7fd3-4323-9d6f-55bd7dfe0fa4', '&lt;p&gt;The new heroes must be arranged as in the main diagonal of a matrix.&lt;/p&gt;\r\n&lt;p&gt;the main diagonal of a square matrix is the diagonal that runs from the upper left corner to the lower right corner.&lt;/p&gt;\r\n&lt;p&gt;Create a 6x6 square matrix with random elements, calculate and print the sum of the elements of the main diagonal&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b3f04294.31737138_exercise_editor_6a33ef92afb694.26039554.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The evil heroine became furious: she could not control the good heroine. But he could control the mind of the visitor to the heavens. He threw the old man against the new heroes: the visitor from the skies pulled a device from his jacket and a strong wind began to expand in the room.&lt;/p&gt;\r\n&lt;p&gt;Perhaps what the ancient Nazca said was true: their ancient master could control atmospheric events!&lt;/p&gt;\r\n&lt;p&gt;The room was in uproar as the wind intensified, kicking up dust and light objects. The visitor from the skies, now under Camila&#039;s control, had become a tool in the hands of the dark forces. The wind howled in the room, but the Golden Shield glowed brightly, forming a barrier that protected the heroes from the worst of the wind.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk used the power of the Golden Shield not only to protect, but also to heal. His companions felt the effects of the poison begin to wear off. The Golden Shield emitted a brighter glow. The artifact&#039;s positive energy seemed to counteract Camila&#039;s dark influence. The room was shrouded in combined light and shadow, symbolizing the epic struggle between good and evil.&lt;/p&gt;', 29, 3, 'The visitor from the skies on the attack', 0, 0, 0, '', 3),
(43, 'b38d12bd-573e-4ab4-867b-4aea5aae88db', '&lt;p&gt;Camila begins to chant a dark litany, to try to take control of the minds of the new heroes.&lt;/p&gt;\r\n&lt;p&gt;Write a string with a sentence. Transforms the string into a list containing the different words by dividing it with split.&lt;/p&gt;\r\n&lt;p&gt;Reverse the list (you can use the reverse() -&gt;list.reverse() method)&lt;/p&gt;\r\n&lt;p&gt;Construct a string from the reversed list and print the new string in reverse words&lt;/p&gt;', 0, '&lt;p&gt;Jambalaya was the first to get up, then Klaus and the others too. The effects of the poison had worn off and they could use the ancient artifacts in their possession to counter the power of Camila and the visitor to the heavens.&lt;/p&gt;\r\n&lt;p&gt;Klaus advanced in the wind clutching the Flaming Sword, Isabela saw the visitor&#x27;s defeat through the power of the Spear of Destiny, Jambalaya held Camila at gunpoint with the Ivory Bow and Nuliajuk protected his companions from the rushing wind with the power of the Golden Shield.&lt;/p&gt;\r\n&lt;p&gt;Klaus used the power of the sword, enveloping the visitor from the heavens in light. Camila felt her control over the old man weakening. The sword&#x27;s light intensified and the visitor to the heavens fell to the ground, unconscious.&lt;/p&gt;\r\n&lt;p&gt;Camila then tried to control the minds of the new heroes again, but the power of the shield prevented her from doing so.&lt;/p&gt;\r\n&lt;p&gt;Camila didn&#x27;t have the chance to defeat the new heroes united and in possession of the ancient weapons alone. He did the only thing he could do: he gave up. He had no escape from the Ziggurat.&lt;/p&gt;\r\n&lt;p&gt;This was news to the good heroes. Until then, evil heroes had always managed to escape. So they decided to take Camila prisoner. Nuliajuk used the Shield&#x27;s power to envelop her in a bubble of light, preventing her from escaping and using her powers. They would take Camila with them to their new destination: the Hammer of Eden&lt;/p&gt;', 30, 3, 'Camila prisoner', 0, 0, 0, '&lt;p&gt;Create a list with 5 random numbers. Print the list&lt;/p&gt;\r\n&lt;p&gt;Reverse the list (you can use the reverse() -&gt;list.reverse() method)&lt;/p&gt;\r\n&lt;p&gt;Print the list with the numbers reversed&lt;/p&gt;', 1),
(44, '58893e7e-9c0d-4661-85de-161994ab90fc', '&lt;p&gt;Each Moai has a precise distance in meters from the turtle rock.&lt;br&gt;Write a program in Python that manages the collection of Moai with distances from the rock. Each Moai has a name and a distance in meters, use a tuple to represent them, since the names and distances do not change over time.&lt;br&gt;The program should do the following:&lt;br&gt;•    Create a list of tuples, where each tuple contains the name of the Moai and the distance in meters&lt;br&gt;•    Calculate and print the average distances of the Moai from the turtle rock&lt;br&gt;•    Find and print the name of the closest Moai and the furthest Moai from the rock&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4032cb9.99825654_exercise_editor_6a3414fda29e74.79654564.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Year 1400. Pak presided over the extraction of volcanic stone from Rano Raraku, the quarry that his people exploited to obtain the material to sculpt the gigantic heads of their ancestors: the Moai. He took notes in Rongorongo on his clay table, indicating what the next phases were to prepare: the work of the stonemasons and the transport of the Moai towards the coast of the island. Kiribu, a boy who had been assigned to inspect the place to transport the sculpture on the western bank, came running, all out of breath. He began to rant about an army of men coming from the sea, walking on the water. At the head of the army, a solitary man rode on a gigantic fish, his head adorned with a small coral crown and holding a powerful war hammer in his hand. They were clearly lies, perhaps the boy had used devil&#039;s weed and had strange hallucinations&lt;/p&gt;\r\n&lt;p&gt;The next stop indicated by Aionios&#039; map was clear: Easter Island (Rapa Nui in the local language), 3700km from the coast of Chile. Jambalaya, Nuliajuk, Klaus and the other good heroes took a flight to the island, bringing with them their new traveling companion and prisoner Camila. Once they landed at the port of Rapa Nui, thanks to a seaplane, they headed towards the western coast of the island. They had to find the Moai of Chief Knun, the third from the Turtle Rock. In the back of the Moai they would have found the indications for the discovery of the 5th artifact: the Hammer of Eden.&lt;/p&gt;', 29, 3, 'Easter Island', 0, 0, 0, '&lt;p&gt;Each Moai has a precise distance in meters from the turtle rock.&lt;br&gt;Write a program in Python that manages the collection of Moai with distances from the rock. Each Moai has a distance in meters from the rock, it uses a list for the distances, asking the user for input.&lt;br&gt;The program should do the following:&lt;/p&gt;\r\n&lt;p&gt;•    Ask the user for the total number of Moai&lt;br&gt;•    Create a list of numbers, representing the distance in meters of each Moai from the rock, asking the user for the distances for each Moai&lt;br&gt;•    Calculate and print the average distances of the Moai from the turtle rock&lt;/p&gt;\r\n&lt;p&gt;&lt;/p&gt;', 1),
(45, '2fdce92f-d1e7-49eb-9f87-7edc268c18df', '&lt;p&gt;During the long hours at sea the new heroes use the onboard library. Create a program to help them manage the books they borrow using sets.&lt;br&gt;The program should do the following:&lt;br&gt;Create two sets, one containing the books available in the library and the other containing the books currently on loan.&lt;br&gt;Allow the user to enter the name of a book to indicate that it has been checked out, or to return a previously borrowed book. Then ask for a choice: borrow/repay. Then the name of the book. Remove the book from one set and insert it into the other.&lt;br&gt;Prints the list of available books and the list of books on loan after each operation.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b40c6c26.26085010_exercise_editor_6a341558b137e4.27652761.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The Moai&#039;s directions seemed absurd, it indicated traveling west for 4,000 miles. The directions led to the Pacific Ocean, far from any island or archipelago. The new heroes were not discouraged: perhaps there could be an island not marked by maps. They boarded the only ship that left Easter Island and was headed east and that would reach the point indicated by the Moai: a three-masted ship, very similar to an ancient sailing ship. The journey would have taken about 3 days and the boys settled comfortably in the cabins below deck.&lt;/p&gt;\r\n&lt;p&gt;During the journey, the new heroes discussed the legends and prophecies they had encountered thus far. Jambalaya told of his connection to the Ivory Bow and how he had been chosen to carry on the legacy of the heroes of good. Isabela shared her experience trying to get hold of the Spear of Destiny and the obstacles she encountered.&lt;/p&gt;\r\n&lt;p&gt;Klaus and Nuliajuk, with their past tied to the Flaming Sword and the Golden Shield, reflected on the powers they possessed and how they could best use them to defeat the evil heroes. Meanwhile, the ocean stretched all around them, with no sign of land in sight.&lt;/p&gt;', 29, 3, 'Journey into the unknown', 0, 0, 0, '&lt;p&gt;During the long hours at sea the new heroes use the onboard library. Create a program to help them manage the books they borrow using sets.&lt;br&gt;The program should do the following:&lt;br&gt;Create two sets, one containing the books available in the library and the other containing the books currently on loan.&lt;br&gt;Ask the user for the name of the borrowed book. Extract that name from the first set and insert it into the second set, using the appropriate methods. Print the final two sets.&lt;/p&gt;', 2),
(46, '45c7bfef-d49b-4075-974f-842ae0a52ac4', '&lt;p&gt;Descending into the depths of the sea, the new heroes encounter various species of fish. Help them categorize them.&lt;/p&gt;\r\n&lt;p&gt;Ask the user 6 species of fish with the depth where they were spotted. Create tuples with the 2 data and insert them into a set.&lt;/p&gt;\r\n&lt;p&gt;Print the final set thus obtained.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4129df6.91124144_exercise_editor_6a3415a9e17e86.57191586.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;The ship reached the point indicated by the Moai, but no land was in sight, only water, everywhere you looked, all the way to the horizon. The new heroes were perplexed: could they have interpreted the instructions incorrectly?&lt;/p&gt;\r\n&lt;p&gt;Chijioke, the second to last boy in the group of new heroes, from Nigeria, with very dark and curly hair, tall and thin, had an idea. If what they were looking for wasn&#039;t above the sea, perhaps it was below. In the hold of the ship he had found some diving suits. Using them they could dive and continue the search for the Hammer of Eden in the depths of the ocean.&lt;/p&gt;\r\n&lt;p&gt;Unfortunately, there were only 3 diving suits, so they had to decide who would undertake the underwater adventure. The choice fell on Nuliajuk, who could bring with him the Golden Shield, Jambalaya and Chijioke, who had come up with the idea.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk, Chijioke and Jambalaya put on their diving suits, ready to dive into the depths of the ocean in search of the Hammer of Eden. With the darkness surrounding them, the lights from their diving suits illuminated the underwater world. The water was crystal clear and full of marine life, but there was no sign of the Hammer.&lt;/p&gt;', 29, 3, 'Chijioke', 0, 0, 0, '&lt;p&gt;Descending into the depths of the sea, the new heroes encounter various species of fish. Help them categorize them.&lt;/p&gt;\r\n&lt;p&gt;Manually create tuples with the name of the fish species and the number of meters of depth where it lives and insert them into a set using the appropriate method.&lt;/p&gt;\r\n&lt;p&gt;Tuple example: (&#x27;Barracuda&#x27;,20)&lt;/p&gt;\r\n&lt;p&gt;Print the final set thus obtained.&lt;/p&gt;', 1),
(47, 'ddec9b79-224c-4ddb-9019-40db883b808a', '&lt;p&gt;At the center of the underwater city there was an ancient academy, where students enrolled to study both scientific and artistic subjects.&lt;br&gt;Write a program in Python that handles the ancient enrollment of students in the 2 types of subjects using sets. The program should do the following:&lt;br&gt;Create two sets, one containing students enrolled in the science course and the other containing students enrolled in the art course.&lt;br&gt;Allow the user to enter the names of some students by asking for each one whether they want to enroll them in science, art or both, using a loop that ends when the entered student name is null&lt;br&gt;Prints the list of students enrolled in each course and the list of students enrolled in at least one course (union of sets).&lt;br&gt;Prints the list of students enrolled in both courses (intersection of the sets).&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b417b782.05935023_exercise_editor_6a3415f332ef86.19582295.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;As they went deeper they began to notice a structure resembling an underwater city. It was as if they had discovered a hidden world beneath the ocean&#039;s surface. The city was ancient and abandoned, covered in algae and coral.&lt;/p&gt;\r\n&lt;p&gt;They continued their exploration, following the signs on the Moai that seemed to point to this place. Suddenly, a massive sea creature approached them. He was an ancient guardian of the ocean, a huge blue whale. As it approached, the creature spoke into the minds of the 3 boys, revealing that it knew of their purpose and search for the Hammer of Eden.&lt;/p&gt;\r\n&lt;p&gt;The creature, called Thalassia, told them that the Hammer was at the center of the underwater city, but was protected by ancient spells. To reach it, they would have to pass the tests of the sea gods. Thalassia offered her guidance and protection during the challenge that lay ahead.&lt;/p&gt;\r\n&lt;p&gt;Thalassia, the sea creature, guided the 3 boys through an intricate labyrinth of corals and underwater ravines. The light filtered from above, creating plays of shadows and reflections.&lt;/p&gt;\r\n&lt;p&gt;The three heroes finally arrived at a square, which marked the center of the ruined city. Thalassia gracefully approached and pointed in the direction.&lt;/p&gt;', 29, 3, 'The ancient underwater city', 0, 0, 0, '&lt;p&gt;At the center of the underwater city there was an ancient academy, where students enrolled to study both scientific and artistic subjects.&lt;br&gt;Write a program in Python that handles the ancient enrollment of students in the 2 types of subjects using sets. The program should do the following:&lt;br&gt;Create two sets, one containing students enrolled in the science course and the other containing students enrolled in the art course.&lt;br&gt;Enter 3 student names each into the sets.&lt;br&gt;Prints the list of students enrolled in each course and the list of students enrolled in at least one course (union of sets).&lt;br&gt;Prints the list of students enrolled in both courses (intersection of the sets).&lt;/p&gt;', 1),
(48, '4de47701-5a6d-4cc4-9aa9-a6d0e54c20c2', '', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b41cee43.71449742_exercise_editor_6a34165f8c0466.33551189.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;A pedestal stood in the square, above it lay the Hammer of Eden, pulsating with ancient energy. However, something sinister moved in the shadows. Another figure emerged: an evil sea being, the Keeper of the Deep, eager to protect the precious artifact.&lt;/p&gt;\r\n&lt;p&gt;The underwater battle between Nuliajuk, Jambalaya, Chijioke and the Keeper of the Deep was about to begin.&lt;/p&gt;\r\n&lt;p&gt;The underwater battle began with a flurry of quick movements and powerful strikes. The Warden of the Abyss, a massive and fearsome creature, charged at Nuliajuk and Jambalaya with unparalleled ferocity. With his sharp claws and superhuman strength, he sought to repel the two heroes and protect the Hammer of Eden.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk, protected by the Golden Shield, managed to repel the Guardian&#039;s attacks while Jambalaya, with the Ivory Bow, shot precise and fast arrows at the enemy. However, the Warden of the Abyss was a formidable opponent, capable of withstanding their blows and counterattacking ferociously.&lt;/p&gt;\r\n&lt;p&gt;The fight continued for what seemed to be an infinite amount of time, with the three fighters moving between sea currents and underwater ravines. Thalassia, the sea guide, assisted from the distance, offering support and valuable guidance to the two heroes.&lt;/p&gt;\r\n&lt;p&gt;Until, finally, after an intense battle, Nuliajuk and Jambalaya found an opening in the armor of the Keeper of the Abyss. With a well-placed strike from the Golden Shield and a precision-fired arrow from the Ivory Bow, they managed to defeat their opponent allowing Chijioke to obtain the Hammer of Eden.&lt;/p&gt;', 29, 4, 'The heroes against the Guardian of the Abyss', 5, 0, 0, '', 1),
(49, '34d26b73-c3cb-4a8d-b0db-a215b1ef4286', '&lt;p&gt;The heroes, having returned aboard the ship, must first understand their position. To do this they must perform calculations with meridians and parallels.&lt;/p&gt;\r\n&lt;p&gt;Write a function that returns a Boolean value True or False by checking whether two lines, of which the equations y=m1*x+q1 and y=m2*x+q2 are known, are perpendicular. Ask Google, Bing or ChatGPT when 2 lines are perpendicular, if you don&#x27;t already know.&lt;/p&gt;\r\n&lt;p&gt;Use the function in a main program requesting as input the coefficients of the lines (m and q).&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;The 3 heroes said goodbye to Thalassia and headed towards the surface. They re-emerged from the depths of the sea, a few hundred meters from the sailing ship that had transported them to that point in the ocean. Their friends were waiting for them, they couldn&#x27;t wait to tell them the success of the venture. They began to swim towards the boat. The closer they got, however, the more they felt a sense of danger. Something didn&#x27;t add up: they didn&#x27;t see any movement on the deck of the sailing ship.&lt;/p&gt;', 28, 3, 'The return to the sailing ship', 0, 0, 0, '&lt;p&gt;The heroes, having returned aboard the ship, must first understand their position. To do this they must perform calculations with meridians and parallels.&lt;/p&gt;\r\n&lt;p&gt;Write a function that takes as parameters the values of angular coefficient m and intercept q of 2 lines and returns a Boolean value True or False checking whether the two lines, represented by the equations y=m1*x+q1 and y=m2*x+q2, are parallel. 2 lines are parallel if they have the same slope.&lt;/p&gt;\r\n&lt;p&gt;Use the function in a main program requesting as input the coefficients of the two lines (m1 and q1, m2 and q2), printing the result given by the function.&lt;/p&gt;', 1),
(50, '216e927f-e474-455e-9018-1ea726a26f86', '&lt;p&gt;Budi has a rubbery body, so if he falls from a certain height he bounces like a ball. Write a Python function that counts the number of bounces Budi makes if he falls from a certain height, passed as a parameter. Each time he bounces, Budi reaches 60% of his previous height. It stops when the height becomes smaller than 0.01&lt;/p&gt;\r\n&lt;p&gt;Use the function in a main() program that asks for the height and prints the number of bounces.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b422e244.76846985_exercise_editor_6a3416e919de63.95884525.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Once they reached the access ladder to the bridge, they climbed carefully, ready to face any threat that might await them. Once they reached the top, what they saw left them breathless: the bridge was deserted, and silence reigned all around. There was no sign of their friends, nor any human presence.&lt;/p&gt;\r\n&lt;p&gt;Suddenly, however, two figures emerged from below deck: one was Isabela, the other was a stocky boy, with almond-shaped eyes and a dark complexion, evidently overweight. Jamabalaya knew who he was: Budi, yet another hero of darkness. Its power: a rubber body, impossible to scratch. But why was Isabela with him? And she was certainly not a prisoner, in fact she was carrying the Spear of Destiny in her hand, which had not been taken from her..&lt;/p&gt;\r\n&lt;p&gt;Isabela&#039;s presence alongside Budi, a hero of darkness, cast shadows of doubt on the situation. Was it possible that Isabela had betrayed the group of new heroes of good? Or was there another explanation behind this strange alliance?&lt;/p&gt;', 28, 3, 'The evil hero Budi', 0, 0, 0, '&lt;p&gt;Budi has a rubbery body, so if he falls from a certain height he bounces like a ball. Write a Python function that counts the number of bounces Budi makes if he falls from a certain height, passed as a parameter to the function. Create a while loop where remove 10 from each loop at Budi&#x27;s height (with each bounce it gets lower and lower), the loop ends when the height is less than or equal to 0. Count how many times the loop is entered (this represents the number of bounces) and return the value with return.&lt;/p&gt;\r\n&lt;p&gt;Use the function in a main() program that asks for the height and prints the number of bounces made by the evil hero.&lt;/p&gt;', 1),
(51, '795d90e9-25f8-4d4f-bfad-705fb14cbb2f', '&lt;p&gt;The good heroes are dismayed by Isabela&#x27;s betrayal, a dismay that accumulates compared to every time they thought she was a friend and instead she was a spy for the evil heroes.&lt;/p&gt;\r\n&lt;p&gt;Write a function to calculate cumulative awe.&lt;/p&gt;\r\n&lt;p&gt;The function takes as input a list of numbers and creates a second list where the i-th element is the sum of the first i+1 elements of the original list. The function returns the cumulative list.&lt;/p&gt;\r\n&lt;p&gt;Example:&lt;/p&gt;\r\n&lt;p&gt;Original list -&gt; [3, 1, 5, 8, 2]&lt;/p&gt;\r\n&lt;p&gt;Cumulative list -&gt; [3, 4, 9, 17, 19], where position 0 is the sum of the first number of the original list, position 1 is the sum of the first 2 elements of the original list (3+1), position 2 is the sum of the first 3 elements of the original list (3+1+5) and so on.&lt;/p&gt;\r\n&lt;p&gt;Use the function in a main program, where you create the first list and print the second list obtained from the function.&lt;/p&gt;', 0, '&lt;p&gt;&quot;Isabela, what&#x27;s going on? Why are you with one of the evil heroes? Where are the others?&quot; Jambalaya asked. Budi didn&#x27;t give the girl time to respond, instead moving on to attack the 3 heroes who had re-emerged from the depths of the sea: he took a leap, trying to crush them under his imposing bulk. Nuliajuk raised his Golden Shield, parrying the blow.&lt;/p&gt;\r\n&lt;p&gt;At this point Chijioke used the power of the Hammer of Eden: it was able to immobilize anyone, forcing them to reveal the truth. The enemy could not move until he had completely answered the questions asked. The boy directed the power of the mystical weapon towards Budi, asking him what was happening.&lt;/p&gt;\r\n&lt;p&gt;Budi burst into contemptuous laughter: &quot;You have understood nothing! You cannot defeat the darkness! It will always be stronger than the light. What do you think is happening? You are not incorruptible! Isabela has gone over to the dark side. How did you think we were able to find ourselves every time in the places where the ancient weapons had been hidden? With the crystal ball? Of course not, it was Isabela who gave us the tips to be able to compete for the artifacts! And we left them to you, so that you could then obtain them all at once, you worked for us, and in the meantime we studied you, poor fools!&quot;&lt;/p&gt;\r\n&lt;p&gt;&quot;Isabela, how could you?&quot; Nuliajuk asked in shock, &quot;Tell me that you are under the influence of Camila&#x27;s power!&quot;. In response, Isabela threw herself against the three heroes of good, brandishing the Spear of Destiny.&lt;/p&gt;', 28, 3, 'The Betrayal', 0, 0, 0, '&lt;p&gt;The good heroes are dismayed by Isabela&#x27;s betrayal, a dismay that accumulates compared to every time they thought she was a friend and instead she was a spy for the evil heroes.&lt;/p&gt;\r\n&lt;p&gt;Write a function to calculate cumulative awe.&lt;/p&gt;\r\n&lt;p&gt;The function takes as input a list of numbers and creates a second list where each number is given by twice the number present in the first list.&lt;/p&gt;\r\n&lt;p&gt;Example:&lt;/p&gt;\r\n&lt;p&gt;Original list -&gt; [3, 1, 5, 8, 2]&lt;/p&gt;\r\n&lt;p&gt;Second list-&gt; [6, 2, 10, 16, 4]&lt;/p&gt;\r\n&lt;p&gt;The function returns the second list.&lt;/p&gt;\r\n&lt;p&gt;Use the function in a main program, where you create the first list and print the second list obtained from the function.&lt;/p&gt;', 1),
(52, '418e89c2-9051-4743-b7a1-7098247cf633', '&lt;p&gt;The heroes of good are literally on the ground.&lt;/p&gt;\r\n&lt;p&gt;Create a function that takes a string as a parameter and returns a tuple where the first element is the character of the string that appears multiple times within the string, the second element indicates how many times this appears. (you can use the count method of strings)&lt;/p&gt;\r\n&lt;p&gt;Example: within the string &quot;supercalifragilisticexpialidoso&quot; the character that appears most frequently is i and it appears 6 times.&lt;/p&gt;\r\n&lt;p&gt;Use the function defined in a main function, where you ask the user for input for a string and tell him which character appears most times, with its frequency.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4278180.80849621_exercise_editor_6a34177ec62046.96940990.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Isabela attacked furiously, striking with ferocity and determination. Nuliajuk raised the Golden Shield to protect herself and her companions, while Jambalaya held the Ivory Bow, ready to shoot arrows at the traitor. Nuliajuk parried Isabela&#039;s blows, Budi awoke from the power of the Hammer of Eden, renewing his attacks.&lt;/p&gt;\r\n&lt;p&gt;Jamabalaya hesitated in shooting arrows at her friend, she hoped that a breath of goodness still breathed in her heart. But Isabela had no hesitation in hitting her former traveling companions. The situation was desperate, the good heroes didn&#039;t know what to do and were succumbing under the impetus of their enemies.&lt;/p&gt;\r\n&lt;p&gt;As the battle raged, desperation grew in the hearts of the good heroes. Isabela, now completely immersed in darkness, seemed invincible, determined to destroy those she had once called friends. Nuliajuk continued to defend herself and her companions with the Golden Shield, but Isabela and Budi&#039;s blows were taking their toll on their defenses.&lt;/p&gt;\r\n&lt;p&gt;Jambalaya was paralyzed, Chijioke didn&#039;t know how he could use the power of the Hammer to help his companions.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk put up a strenuous resistance, but at a certain point he could only surrender: he was at the end of his strength. Budi stunned her with a rubbery punch, Isabela pinned Chijioke to the ground, pointing the Spear at his throat.&lt;/p&gt;\r\n&lt;p&gt;The heroes of good were defeated. But Jambalaya wasn&#039;t going to throw in the towel, he had to keep hope alive. He turned quickly, aiming for the ship&#039;s railing, an agile leap and launched himself into the open sea.&lt;/p&gt;', 28, 3, 'Prisoners!', 0, 0, 0, '&lt;p&gt;The heroes of good are literally on the ground.&lt;/p&gt;\r\n&lt;p&gt;Create a function that takes a string as a parameter and returns how many times the letter &quot;a&quot; appears within the string&lt;/p&gt;\r\n&lt;p&gt;Use the function defined in a main function, where you ask the user for input for a string and tell him how many times the &quot;a&quot; appears&lt;/p&gt;', 1),
(53, '8aad16d0-ccc5-4cbc-b847-e5d30194fb09', '&lt;p class=&quot;MsoNormal&quot;&gt;Thalassia swims to bring Jambalaya to safety: create a function that takes as parameters Thalassia&#x27;s speed in km/h and the time in minutes it swims. Using physical formulas that link speed, time and space, the function must calculate and return how many km the animal has travelled. Create a second function that calculates how many hours have passed, given the minutes. Use the second function within the first. Create a third main function that asks the user for speed and time in minutes and then prints the distance traveled.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b42cc6d0.60798205_exercise_editor_6a3417ce595194.99730768.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya found himself immersed in the immensity of the open sea, breathing heavily and his heart beating wildly. The ship drifted further and further away as he struggled against the waves, his ivory bow still strapped to his back. Each stroke seemed more difficult than the previous one, but the determination that animated him pushed him to continue, despite the tiredness that weighed on his legs and arms.&lt;/p&gt;\r\n&lt;p&gt;Suddenly, a familiar presence made itself felt. Thalassia, the sea creature that had helped them before, emerged from the deep waters, its gigantic and majestic form defying the laws of nature. With smooth, powerful movements, he approached Jambalaya. The boy, recognizing her, did not hesitate: he clung tightly to her enormous caudal fin, immediately feeling the heat and strength that emanated from the creature. With a decisive movement, Thalassia began to swim, lifting the boy and carrying him away from the enemy ship.&lt;/p&gt;\r\n&lt;p&gt;Thalassia&#039;s speed was extraordinary. The ship, despite the force of the storm unleashed by its adversaries, never managed to reach the underwater creature. The days passed, but Thalassia never stopped, guiding Jambalaya towards a safety that seemed increasingly distant. The boy clung to her like a raft, but each wave that hit made them feel smaller, more vulnerable.&lt;/p&gt;', 28, 3, 'Jambalaya\'s escape', 0, 0, 0, '&lt;p&gt;Thalassia swims to bring Jambalaya to safety: create a function that takes as parameters Thalassia&#x27;s speed in km/h and the time in hours for which it swims. Using physical formulas that link speed, time and space, the function must calculate and return how many km the animal has travelled.  Create a second main function that asks the user for speed and time in hours and then prints the distance traveled.&lt;/p&gt;', 1),
(54, 'd92fe368-ce3f-4093-a65c-f80df965ff6b', '&lt;p&gt;Create a file with a form. The module contains functions to reactivate the hope of Jambalaya. The first function must take a number of elements as a parameter and return a list of random elements equal to the number passed as a parameter. It represents the numbers of hope. A second function must take a list as a parameter and return the smallest odd number present within it. The slightest hope. A third function takes a list as a parameter and doubles all the elements within it. Hope grows.&lt;/p&gt;\r\n&lt;p&gt;Create a second file where you can insert a main and the import of the previous module. The main asks the user the number of elements of hope in Jambalaya, creates a list of random elements with the appropriate function, finds and prints the minimum, doubles the elements and prints the final list.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4320fa2.76688351_exercise_editor_6a3418478d8153.44698684.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Finally, after days of struggling against the sea, they reached land. The sand of Australia was a long-awaited and painful refuge. Jambalaya, exhausted and shaken by the events, collapsed on the beach. The hot sun hit him hard, but he didn&#039;t have the strength to get up right away. Thalassia looked at him with eyes full of wisdom and pity. He knew he couldn&#039;t stay too long; his place was in the depths of the sea, where he guarded the underwater city.&lt;/p&gt;\r\n&lt;p&gt;&quot;Jambalaya,&quot; he said, in a voice that seemed to come from the waters themselves, &quot;I am happy to have helped you, but my destiny lies elsewhere. You must continue alone. Your mission is not over, and your friends need you. Find the strength within yourself to save them.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya looked up, but tiredness and worry clouded his face. &quot;What can I do, Thalassia?&quot; he asked, his voice cracking with desperation. &quot;How can I save my friends if they remain prisoners of the most powerful of evil heroes? Isabela... I can&#039;t believe she betrayed everything that united us.&quot;&lt;/p&gt;\r\n&lt;p&gt;The sea whale slowly shook his head. &quot;It&#039;s not just strength that counts, boy. You cannot face evil with brute strength alone. You must find the heart of this battle, the key to freeing your friends. Destiny has chosen you, and you must accept it. Every step you take, every decision you make, will bring you closer to the truth. Remember, even the faintest light can chase away the darkest darkness, if it is guided by will.&quot;&lt;/p&gt;\r\n&lt;p&gt;With a final look of encouragement, Thalassia dove into the deep waters, disappearing into the waves as gracefully as she emerged. Jambalaya was left alone, the sand warm beneath him, the blue sky above him, but his heart was still tormented. He felt like he was too far from his mission, too small compared to the vastness of what he was facing. However, something inside him began to awaken, a determination he had never known before. He had to find a way to free his friends. He had to find the strength within himself to face the evil that had taken over.&lt;/p&gt;\r\n&lt;p&gt;He slowly got up, feeling the weight of responsibility weighing on his shoulders, but also a spark of hope lighting his path. The battle had just begun.&lt;/p&gt;', 28, 3, 'Jambalaya seeks hope', 0, 0, 0, '&lt;p&gt;Create a file with a form. The module contains functions to reactivate the hope of Jambalaya. The first function must take a number of elements as a parameter and return a list of random elements equal to the number passed as a parameter. It represents the numbers of hope. A second function must take a list as a parameter and return the sum of the elements of the list, the new hope.&lt;/p&gt;\r\n&lt;p&gt;Create a second file where you can insert a main and the import of the previous module. The main asks the user the number of elements of hope in Jambalaya, creates a list of random elements with the appropriate function, then prints it and uses it as a parameter of the second function, to obtain the sum of the elements and print it.&lt;/p&gt;', 1);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(55, '32b6495b-490d-4109-ae0e-da7bae889e99', '&lt;p&gt;The difficulties begin to dissipate for Jambalaya: create a recursive function that allows you to divide the difficulties in the following way (recursive integer division): the function calculates how many times a number can be subtracted from the initial number before it becomes negative. The function takes two parameters: the dividend and the divisor. If the divisor is 0 -&gt; impossible. If one of the numbers is negative -&gt; error. Otherwise: if the dividend becomes less than the divisor, the function returns 0, otherwise it returns 1 + the integer division of the dividend – the divisor by the divisor.&lt;/p&gt;\r\n&lt;p&gt;Example: integer division of 8 by 3 is given by&lt;/p&gt;\r\n&lt;p&gt;1 + integer division of (8-3) by 3&lt;/p&gt;\r\n&lt;p&gt;The division of 5 by 3 is given by:&lt;/p&gt;\r\n&lt;p&gt;1 + full division of (5-3) by 3&lt;/p&gt;\r\n&lt;p&gt;Integer division of 2 by 3 returns 0 because 2 is less than 3.&lt;/p&gt;\r\n&lt;p&gt;So the division of 5//3 becomes 1+0 and the division of 8//3 becomes 1+1, result = 2&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b437f636.41133705_exercise_editor_6a341898ea6c39.95865519.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya struggled to his feet, his legs shaking and his mind still confused, when a shadow stood out against the blinding sun. An imposing figure approached along the beach, his steps slow but determined, as if every grain of sand shifted at his command. He was a man of singular appearance: completely bald, his skull and body covered in intricate ancient tattoos that seemed to pulse slightly under the light. His eyes were two abysses of calm and determination, capable of scanning the heart of anyone who stood before him.&lt;/p&gt;\r\n&lt;p&gt;It was Strogar, the second of the neutral heroes. No one knew exactly his age, but it was said that he had lived for a time beyond the memory of men. For centuries he had been observing the world from the shadows, thanks to his powers that allowed him to perceive everything that happened on Earth, noting the most crucial events in human history in his sacred tomes. Normally, he simply observed, maintaining the neutrality he had sworn to respect. But now, something had changed.&lt;/p&gt;\r\n&lt;p&gt;In a deep, gravely voice, Strogar addressed Jambalaya:&lt;br&gt;&quot;I have watched you, boy. I have seen the courage in your eyes and the desperation in your heart. I have witnessed the fall of your comrades, the betrayal of those you believed to be friends. The balance of the world hangs by a thread, and if evil heroes gain the upper hand, the darkness that will ensue will be deeper than any ever known.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya looked at him, unable to respond right away. He felt the solemnity of those words, the weight that weighed on the moment.&lt;/p&gt;\r\n&lt;p&gt;&quot;I can&#039;t stand by any longer,&quot; Strogar continued, taking a few more steps forward. &quot;For too long I have maintained my neutrality, but now it is different. This clash is no longer just about heroes: it is about the entire future of humanity. If evil triumphs, there will be no more history to write, only silence and ruin.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya clenched his fists, feeling hope slowly resurface. Maybe he wasn&#039;t alone, maybe, with Strogar&#039;s help, he still had a chance to save his friends... and the entire world.&lt;/p&gt;\r\n&lt;p&gt;&quot;If you will accept my help,&quot; Strogar concluded, &quot;I will guide you. But know that the path will be hard, and every choice you make will change everyone&#039;s fate.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya nodded, feeling a new strength ignite within him. He was ready.&lt;br&gt;Or at least, it should have been.&lt;/p&gt;', 31, 3, 'Strogar', 0, 0, 0, '&lt;p&gt;Create a recursive function with one parameter and print the string &quot;Force increases&quot; as many times as the parameter. The function must make recursive calls to itself if the parameter is greater than 0, do nothing otherwise. Think about how to pass the parameter so that at a certain point it becomes 0.&lt;/p&gt;\r\n&lt;p&gt;Call the recursive function in a main program with an initial value prompted from the user.&lt;/p&gt;', 2),
(56, '1e48970f-962c-43b0-a253-e5096fffc7a8', '&lt;p&gt;The two heroes must manage the Russian coordinates to find the Earthsplitting Axe: write a program in Python that will help them. The program must use functions to separate the various operations and must allow the user (via input) to:&lt;/p&gt;\r\n&lt;p&gt;Insert a coordinate with latitude and longitude values, saved in the list as a tuple&lt;/p&gt;\r\n&lt;p&gt;Calculate the average of the latitudes and longitudes&lt;/p&gt;\r\n&lt;p&gt;Find the highest latitude&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b43c8737.00999014_exercise_editor_6a3418ea707320.11800565.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Strogar stared at the horizon for a long moment, then turned toward Jambalaya.&lt;/p&gt;\r\n&lt;p&gt;&quot;The last weapon has not been lost, as many believe,&quot; he said gravely. &quot;The Earthsplitting Axe... a tool forged in the heart of the world, capable of shattering mountains and splitting oceans. Its power is enough to awaken the primordial forces of the Earth itself. If it falls into the wrong hands, the planet may not survive.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya looked at him with eyes alight with new determination. &quot;And where is it?&quot;&lt;/p&gt;\r\n&lt;p&gt;&quot;In the place where the Earth still breathes with the breath of the gods,&quot; Strogar replied. &quot;We&#039;ll have to go to Russia.&quot;&lt;/p&gt;', 28, 3, 'The ultimate weapon', 0, 0, 0, '&lt;p&gt;Create a list containing 3 tuples with 2 numerical values ​​representing the latitude and longitude coordinates.&lt;/p&gt;\r\n&lt;p&gt;With one loop it finds the sum of the three latitudes and the sum of the three longitudes and prints the two calculated values at the end&lt;/p&gt;', 2),
(57, '0a224e5a-48bd-4432-a610-287b0ffce119', '&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya and Strogar need to save the information of the explosion. Create a Python program that opens a text file, asks the user for the names of the 5 cities affected by the explosion and saves them to file, wrapping between one and the other.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b441b6e5.26963703_exercise_editor_6a341960a99f72.51878526.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;It was the &lt;strong&gt;June 30, 1908&lt;/strong&gt; when, above the remote forests of central Siberia, an event occurred that still remains shrouded in mystery today: a &lt;strong&gt;gigantic explosion&lt;/strong&gt; shook the region of &lt;strong&gt;Tunguska&lt;/strong&gt;, cutting down millions of trees over an area of over 2,000 square kilometers. Witnesses told of a &lt;strong&gt;blinding glare&lt;/strong&gt;, followed by a&lt;strong&gt;shock wave&lt;/strong&gt; who traveled hundreds of kilometers, destroying everything in his path.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;For years, no one dared venture there. Only in &lt;strong&gt;1927&lt;/strong&gt;, a group of scientists led by the mineralogist &lt;strong&gt;Leonid Kulik&lt;/strong&gt; dared to enter the devastated forest. Kulik looked for a meteorite crater, but found nothing. Only felled trees in a radial pattern, blackened trunks and strange magnetic fields that seemed to dance in the air. No one knew that, centuries before, that area had been the scene of another mystery: the last weapon of the ancient heroes - &lt;strong&gt;the Earthsplitting Axe&lt;/strong&gt; &mdash; had been buried there, hidden between earth and ice, protected by forgotten spells.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;In 1908, it was exactly there &lt;strong&gt;reactivation&lt;/strong&gt; accident of the Ax to trigger the immense explosion. A partial awakening of his power, like a flapping of wings in a restless sleep. Not a meteorite, not a human experiment, but an ancient and powerful weapon, capable of shattering the very ground.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Now, more than a century later, Jambalaya and Strogar were determined to reclaim it. Only by wielding the Earthsplitting Ax could they counter the dark forces that had corrupted the heroes and bring hope back to the world.&lt;/p&gt;', 27, 3, 'Tunguska', 0, 0, 0, '&lt;p&gt;Jambalaya and Strogar need to save the information of the explosion. Create a Python program that opens a text file, asks the user for the name of a city, and saves it to file, wrapping at the end.&lt;/p&gt;', 0),
(58, '4076b704-03c2-40a5-80f7-434e05478e05', '&lt;p class=&quot;MsoNormal&quot;&gt;The Earthsplitting Ax is the last of the weapons of the heroes of good: what are its powers? Write 3 powers on a .txt text file by wrapping between one and the other. Write a Python program that reads the 3 powers one at a time from the file, printing them to the screen.&lt;/p&gt;', 0, '&lt;p&gt;Tunguska, Central Siberia.&lt;/p&gt;\r\n&lt;p&gt;The snow crunched under Jambalaya and Strogar&#x27;s footsteps as they advanced among the silent skeletons of trees felled more than a century before. The air was charged with electricity, as if the earth itself was holding its breath. Gray daylight filtered through low clouds, and a sharp wind kicked up puffs of ice around them.&lt;/p&gt;\r\n&lt;p&gt;They finally reached the center of the clearing. There, where the explosion had originated, the ground was split into a thousand fractures, blackened by time and the power of the past. And right in the middle of that unnatural crater, a pedestal of shiny black stone stood, as if it had just emerged from the bowels of the earth. On it, planted deeply, was the Earth-Splitting Axe.&lt;/p&gt;\r\n&lt;p&gt;Jambalaya felt his heart speed up. The air around the weapon trembled slightly, as if distorted by heat, even though it was bitterly cold. Strogar took a step forward, his muscles tense, his eyes fixed&lt;/p&gt;', 27, 3, 'The pedestal with the Axe', 0, 0, 0, '&lt;p&gt;The Earthsplitting Ax is the last of the weapons of the heroes of good: what are its powers? Write 1 power on a .txt text file with any editor, such as Notepad. Write a Python program that reads the power from the file and prints it to screen.&lt;/p&gt;', 0),
(59, '6f0432d9-e19a-4a6d-aed2-708003532b02', '&lt;p class=&quot;MsoNormal&quot;&gt;Nikolai&#x27;s powers are immense: superhuman strength, telekinesis, pyrokinesis, ability to fly. Insert the list of powers into a .txt text file, writing them end to end. Open the file for reading using Python and for each power ask for the number of damage it can cause to Jambalaia and Strogar, saving this number in a second .txt file&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b449dd62.59050982_exercise_editor_6a3419ed2a4730.20388139.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;But Jambalaya and Strogar were not alone.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;A deep, calm voice, full of contempt, made its way through the gusts of wind:&lt;br&gt;&lt;strong&gt;&quot;You arrived late.&quot;&lt;/strong&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;From the shadows between the broken trunks a slender figure emerged, an intense red aura emanating from it. &lt;strong&gt;Nikolai&lt;/strong&gt;, the most powerful and ruthless hero on the front of darkness.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Strogar clenched his fists.&lt;br&gt;&laquo;Nikolai&hellip; You.&raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&laquo;I knew you would come. The &lt;em&gt;Aionios map&lt;/em&gt;, taken from your imprisoned friends, never lies. The last of the weapons is here... and it&#039;s mine.&quot;&lt;br&gt;Nikolai&#039;s voice was devoid of anger. It was safe. Implacable. The darkness around him seemed alive.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya stepped forward. &ldquo;We won&#039;t let you take her. This war will end, and you won&#039;t be the one to win it.&quot;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolai laughed softly.&lt;br&gt;&laquo;You two? A tired old hero and a scared boy? Against &lt;em&gt;me&lt;/em&gt;?&quot;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Then he moved. In the blink of an eye, he was among them, and the air exploded with power.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The battle for the Earthsplitting Ax had begun.&lt;/p&gt;', 27, 3, 'The Last Evil Hero: Nikolai', 0, 0, 0, '&lt;p&gt;Nikolai&#x27;s powers are immense: superhuman strength, telekinesis, pyrokinesis, ability to fly. Insert the list of powers into a .txt text file, writing them end to end. Open the file for reading via Python and print the read powers.&lt;/p&gt;', 1),
(60, '34e3b177-9413-483e-a804-714b6f8e0c15', '&lt;p class=&quot;MsoNormal&quot;&gt;Using Python, open a file in write mode, then create 2 strings made up of 10 random numbers drawn between 1 and 100 each. The numbers must appear separated by semicolons within the strings. Save the 2 strings to file and then close the file. The first string represents the blows delivered by Strogar, the second those of Nikolaj.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Reopen the file in read mode. Read the two strings, breaking the various components with split. Add the components of the first row and the second row and indicate which of the two heroes wins (the one with the highest summed number wins).&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Example of string to save:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;17;92;12;66;35;81;40;84;27;1&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4507d24.91067018_exercise_editor_6a341a3a831869.53597265.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolai advanced like a storm, his footsteps shaking the frozen ground. An aura of dark energy was released around him that distorted the air, as if reality itself was struggling to contain him. He raised an arm and his weapon materialized out of nowhere: &lt;strong&gt;the Vortex Blade&lt;/strong&gt;, a huge sword of pure darkness, sharp as vengeance.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Strogar&lt;/strong&gt; he launched first. His bare hands shimmered with an earthy light, summoning the strength of rock and history. The ground shook beneath his feet as a mighty fist collided with Nikolai&#039;s sword. The impact was devastating, a roar rang out in the valley, sending snow and dust flying all around.&lt;/p&gt;', 27, 3, 'Battle for the Axe', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Using Python, open a file in write mode, then create 2 strings made up of 10 random numbers drawn between 1 and 100 each. The numbers must appear separated by semicolons within the strings. So you have to create the two empty strings, use a loop, extract the two random numbers, add them to the related string with +=, add the &quot;;&quot; character. Finally save the 2 strings to file and then close the file.&lt;/p&gt;', 1),
(61, '6fe845d0-5304-4223-8b38-2c3e179745cc', '&lt;p class=&quot;MsoNormal&quot;&gt;Using Python, create a text file with structured records composed of lines of 30 characters, where the characters from 0 to 9 indicate the number of the arrow launched by Jambalaya, the characters from 10 to 20 indicate the power of the arrow (a random number between 0 and 100), the last 10 characters indicate where the arrow hits (prompted as input to the user). Create dashes to fill the gaps by multiplying the “-“ character by the number of missing characters. Save the created line to the file.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Example of created file:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;arrow1--44--------arm---&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;arrow2—12--------earth------&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b455c3b6.37122447_exercise_editor_6a341a8679b583.81191602.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nel frattempo, &lt;strong&gt;Jambalaya&lt;/strong&gt; he positioned himself behind a broken boulder and grabbed the&lt;strong&gt;Ivory Arch&lt;/strong&gt;. He took a deep breath, pointed the bow at his opponent and let go of the first enchanted arrow: a bright bolt whistled through the air, hitting Nikolai in the side. But the warrior of darkness simply rotated his torso slightly and smiled.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&ldquo;You&#039;re going to need a lot more than this, boy.&rdquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya, however, was not intimidated. He pulled out three more arrows and fired them in quick succession. One hit the ground, generating a dazzling cloud of light. Another made an arc that exploded into the air, distracting Nikolai for a moment. The third... hit right in the chest.&lt;/p&gt;', 27, 3, 'The defense of Jambalaya', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Use Python to create a text file with structured records composed of lines of 10 characters, where the characters indicate the number of arrows launched by Jambalaya. Use a for loop. Create dashes to fill the gaps by multiplying the “-“ character by the number of missing characters and add them to the &quot;arrow&quot;+loop counter string. Also add the &quot;\\n&quot; character at the end for a new line. Save the created line to the file.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Example of created file:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;arrow1----&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;arrow2----&lt;/p&gt;', 1),
(62, '887e2100-28fd-4e65-a101-1baa7cd31644', '&lt;p&gt;Create a Python program that opens the file created in the previous exercise, asks the user for an arrow number as input and writes the data of the chosen arrow. For example, if I write 2 I will get:&lt;/p&gt;\r\n&lt;p&gt;Arrow 2, power: 12, hits the ground&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Fu allora che &lt;strong&gt;Strogar seized the moment&lt;/strong&gt;: He pounced on the Earthsplitting Axe, placing both hands on the handle set in the stone. For a moment, time seemed to stop. A green, pulsating light enveloped Strogar, as if the roots of the world were restoring to him the strength of millennia. With a scream, the neutral hero tore the weapon from the pedestal.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The earth shook again. The sky darkened for an instant, as if the Ax itself was greeting its new bearer.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolai turned angrily. &quot;No!&quot;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;He launched a wave of black energy at Strogar, but Jambalaya stepped in front of him, shooting a golden arrow that neutralized the attack in mid-air. Strogar raised the Earthsplitting Ax and brought it down hard on the ground. A telluric wave spread in all directions, throwing Nikolai back many meters.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;«You got what you wanted…» Nikolai growled, struggling to get up. “But you will not be able to change the fate of this world.”&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Then he vanished into the air, enveloped in a swirl of smoke and shadow.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya fell to his knees, exhausted. Strogar approached him and helped him up.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;«It&#x27;s just the beginning, boy. But now we have one more weapon. And with it… we will save your friends.»&lt;/p&gt;', 27, 3, 'Strogar takes the Axe', 0, 0, 0, '&lt;p&gt;Create a Python program that opens the file created in the previous exercise, asks the user for an arrow number as input and writes the data of the chosen arrow by reading the file with readlines and then selecting the relevant line. For example, if I write 2 I will get:&lt;/p&gt;\r\n&lt;p&gt;Arrow2---&lt;/p&gt;', 1),
(63, '91894b20-1895-4ec1-8910-bff0c13297a5', '&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya and Strogar manage to locate the secret base thanks to the powers of the Ivory Bow. Shooting an arrow to hit the secret base, all he had to do was follow it. The Bow always hits the target.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Create a Python program that inserts random numbers between 1 and 1000 into a list, which identify the km traveled by the arrow. Save the numbers inside a text file. Then open the text file for reading again. Read all the saved numbers, inserting them into a list and print the sum of the total km.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b45ae9a5.63136516_exercise_editor_6a341b2cbc1226.42716214.jpg&quot; alt=&quot;&quot;&gt;The cold north wind cut his face like a thin blade. Among the trees bent by the snow, two silhouettes advanced quickly and silently: &lt;strong&gt;Jambalaya&lt;/strong&gt;, con l&rsquo;Arco d&rsquo;Avorio sulle spalle, e &lt;strong&gt;Strogar&lt;/strong&gt;, the bald veteran with tattoos that told of forgotten wars.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Before them, in the mountains of Quebec, lay the secret base of the evil heroes. Here the heroes of good were held prisoner, locked in cold and silent cells, watched over by dark mechanisms.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya gritted his teeth. They had to free them. There was no other way.&lt;/p&gt;', 27, 3, 'The Canadian base', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya and Strogar manage to locate the secret base thanks to the powers of the Ivory Bow. Shooting an arrow to hit the secret base, all he had to do was follow it. The Bow always hits the target.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Create a Python program that saves 10 random numbers between 0 and 100 into a text file, wrapping between them (so using a write of &quot;\\n&quot;). Then open the text file again for reading and print the numbers written in the file on the screen.&lt;/p&gt;', 1),
(64, 'cffb9c53-8769-412f-8c50-16d0fd068c86', '&lt;p class=&quot;MsoNormal&quot;&gt;Create a Python program that handles data in a file representing Strogar&#x27;s mind map. The program must have a menu with 3 options:&lt;/p&gt;\r\n&lt;p class=&quot;MsoListParagraph&quot; style=&quot;text-indent: -18.0pt; mso-list: l0 level1 lfo1;&quot;&gt;&lt;!-- [if !supportLists]--&gt;&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;1-&lt;span style=&quot;font: 7.0pt &#x27;Times New Roman&#x27;;&quot;&gt;       &lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;!--[endif]--&gt;Insert new room in the mind map. The room must have a name, a size in square meters and a wing where it is located in the building (south, west, east or north).&lt;/p&gt;\r\n&lt;p class=&quot;MsoListParagraphCxSpLast&quot; style=&quot;text-indent: -18.0pt; mso-list: l0 level1 lfo1;&quot;&gt;&lt;!-- [if !supportLists]--&gt;&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;2-&lt;span style=&quot;font: 7.0pt &#x27;Times New Roman&#x27;;&quot;&gt;       &lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;!--[endif]--&gt;Count how many rooms are in a certain wing of the building requested as input&lt;/p&gt;\r\n&lt;p class=&quot;MsoListParagraphCxSpLast&quot; style=&quot;text-indent: -18.0pt; mso-list: l0 level1 lfo1;&quot;&gt;&lt;!-- [if !supportLists]--&gt;&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;3-&lt;span style=&quot;font: 7.0pt &#x27;Times New Roman&#x27;;&quot;&gt;       &lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;!--[endif]--&gt;Exit the program&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4602458.60447104_exercise_editor_6a341b75ae3e56.77713277.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The base exploded into chaos as the two burst inside, guided by a mental map drawn by Strogar. In a few minutes they managed to break the runic locks that imprisoned the other heroes: &lt;strong&gt;Nuliajuk&lt;/strong&gt;, &lt;strong&gt;Chijioke&lt;/strong&gt;, &lt;strong&gt;Klaus&lt;/strong&gt; ed &lt;strong&gt;Emily&lt;/strong&gt; si riversarono nei corridoi inondati dalla luce del giorno.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Emily, Canadian, daughter of those lands, was the only one in the group without powers or sacred weapons. But he didn&#039;t waver. A former figure skater, she moved with speed and precision, as sharp as the discipline she had cultivated since she was a child. His calm and firm voice commanded the others: &quot;Let&#039;s go. Before it&#039;s too late.&quot;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The heroes managed to recover the sacred weapons, which were temporarily abandoned in a room near the cells.&lt;/p&gt;', 27, 3, 'The rescue', 0, 0, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Create a Python program that handles data in a file representing Strogar&#x27;s mind map. The program must have a menu with 3 options:&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;&lt;span style=&quot;font: 7.0pt &#x27;Times New Roman&#x27;;&quot;&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li class=&quot;MsoNormal&quot;&gt;&lt;!--[endif]--&gt;Insert new room in the mind map by asking the user for the name. Ask for the name and insert it into the file, opening it in append mode.&lt;/li&gt;\r\n&lt;li class=&quot;MsoNormal&quot;&gt;Print all the stanzas found on the file.&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;&lt;span style=&quot;font: 7.0pt &#x27;Times New Roman&#x27;;&quot;&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;\r\n&lt;li class=&quot;MsoNormal&quot;&gt;&lt;!--[endif]--&gt;Exit the program&lt;/li&gt;\r\n&lt;/ul&gt;', 1),
(65, '261fd94c-3558-4a11-a4b9-d8e205145532', '&lt;p class=&quot;MsoNormal&quot;&gt;Create a dictionary in which to insert the names of the heroes, their life points and their magical power. The name will be the key, life points and spell power (expressed in numbers) are the values ​​(placed in a tuple). Save the dictionary to file using the pickle module.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Always using pickle, load the dictionary from the file and print the names of the heroes one by one with their life and magical power.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;pickle.dump() -&gt; save data to file&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;pickle.load() -&gt; load data from file&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4670884.10443402_exercise_editor_6a341bddefe601.89770409.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;In the frozen heart of the base, the final battle came to life.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Isabela awaited them, holding the Spear of Destiny. At his side &lt;strong&gt;Budi&lt;/strong&gt;, &lt;strong&gt;Camila&lt;/strong&gt;, &lt;strong&gt;Lachlan&lt;/strong&gt;, &lt;strong&gt;Shinzo Hanzei&lt;/strong&gt;, &lt;strong&gt;Aisha&lt;/strong&gt;&hellip; e soprattutto &lt;strong&gt;Nikolai&lt;/strong&gt;, the supreme commander of the evil heroes. Cold as steel, Nikolai advanced without a word, his eyes full of contempt. He knew the good ones were inferior. He knew he was the strongest.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;But he was wrong.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The clash exploded. Nuliajuk blocked Isabela with the Golden Shield, while Strogar faced Lachlan. Jambalaya found himself facing Shinzo, who used his insane speed to dodge the arrows of the Ivory Bow.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolai drew his blade, attacking Strogar, with whom he had a score to settle. Aisha and Budi used their powers to corner Klaus. Chijioke faced Camila, the power of the hammer truth against the power of dark persuasion. Emily, unarmed, didn&#039;t know how she could help her companions.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The clash seemed unequal.&lt;/p&gt;', 27, 3, 'The clash explodes', 0, 0, 0, '&lt;p&gt;Create a dictionary in which to insert the names of the heroes, their life points and their magical power. The name will be the key, life points and spell power (expressed in numbers) are the values ​​(placed in a tuple). Print the dictionary on screen.&lt;/p&gt;', 1),
(66, '47d664dd-e387-4281-8a89-95d26289e66e', '&lt;p class=&quot;MsoNormal&quot;&gt;Write a program that manages a file data store. The program must handle any input/output exceptions. An archive row contains data on: Good Hero&#x27;s Weapon, Good Hero&#x27;s Name, Name of Enemy Faced, Good Hero&#x27;s Power, Evil Hero&#x27;s Power. The program must give the user the possibility to: insert a new archive line within the file, view the total power of the heroes of good and the heroes of evil within the file, for each line say who is more powerful between good and evil and count how many times the heroes of good and how many times the heroes of evil win, saying who wins in total.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b46bd435.80867239_exercise_editor_6a341c37071715.20362705.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Nikolai unleashed a furious blow, his black blade glowing with a dark energy that seemed to devour light itself. Strogar, steady as a mountain, parried the attack with his mighty Earthsplitting Axe. The two weapons collided with a clang that made the walls of the base vibrate. The impact generated a shock wave that tore the floor beneath their feet.&lt;/p&gt;\r\n&lt;p&gt;Nikolai&#039;s blade was deflected by the brutal force of the axe, and it flew to the side&mdash;right at Aisha, who had gotten too close in the chaos of battle.&lt;/p&gt;\r\n&lt;p&gt;One moment. Only one.&lt;/p&gt;\r\n&lt;p&gt;Klaus saw everything. He didn&#039;t hesitate.&lt;/p&gt;\r\n&lt;p&gt;He threw himself in front of Aisha, opening his arms wide. The shot hit him square in the chest. An explosion of light and blood.&lt;/p&gt;\r\n&lt;p&gt;Aisha&#039;s eyes widened. Klaus&#039; body collapsed to the ground, shaking. His breathing stopped, but his face&hellip; was serene. As if he had found peace in protecting another life, even if it had been an enemy.&lt;/p&gt;\r\n&lt;p&gt;&laquo;Why&hellip;?&raquo; Aisha murmured, kneeling next to him, confused, shaken, vulnerable as ever.&lt;/p&gt;\r\n&lt;p&gt;Klaus smiled weakly at her, his lips cracked but calm. &laquo;Because you can be better than this&hellip;&raquo;&lt;/p&gt;\r\n&lt;p&gt;Then he closed his eyes.&lt;/p&gt;\r\n&lt;p&gt;For the first time in years, Aisha trembled. Not out of anger, but out of shame. Out of remorse. Because that gesture - that sacrifice - had pierced her more than Nikolai&#039;s sword. She slowly stood up, staring at the chaos of battle around her with different eyes. And for the first time, he chose to fight not for himself, but against what he had believed in until that moment.&lt;/p&gt;\r\n&lt;p&gt;With a cry, Aisha charged into the fray&hellip; on the side of the heroes of good.&lt;/p&gt;', 27, 3, 'Redemption', 0, 0, 0, '&lt;p&gt;Write a program that manages a file data store. An archive row contains data about: Good Hero&#x27;s Weapon, Good Hero&#x27;s Name. The program must give the user the possibility to: insert a new archive line within the file. Print the contents of the file. Think about how to separate the two values ​​to save them within the file.&lt;/p&gt;', 2),
(67, '6d1429f5-528f-4657-acae-ecd96b4678f0', '&lt;p&gt;Some heroes survive, others fall, as in the hangman game where some letters survive, others lead to the death of the little man.&lt;/p&gt;\r\n&lt;p&gt;Create the Hangman game as follows:&lt;/p&gt;\r\n&lt;p&gt;Save a list of words to a file, one at the end of the other, using a text editor.&lt;/p&gt;\r\n&lt;p&gt;Read the words from the file, perhaps saving them in a list and choose one randomly with random.randint(). It will be the word to guess.&lt;/p&gt;\r\n&lt;p&gt;Initialize 3 variables: one with the number of incorrect attempts, one with the number of correct attempts for the letters and one for the user&#x27;s choice of what to do.&lt;/p&gt;\r\n&lt;p&gt;We transform the original word (a string) into a list.&lt;/p&gt;\r\n&lt;p&gt;We create a second list of hyphens as long as the original word, multiplying the list [“-“] by the length of the word.&lt;/p&gt;\r\n&lt;p&gt;We create a while loop, from which we will exit when the user chooses to exit.&lt;/p&gt;\r\n&lt;p&gt;Inside the cycle we insert a menu to let the user choose one of the following options:&lt;/p&gt;\r\n&lt;p&gt;1- Attempt a letter&lt;/p&gt;\r\n&lt;p&gt;2- Try to guess the complete word&lt;/p&gt;\r\n&lt;p&gt;3- Exit the program&lt;/p&gt;\r\n&lt;p&gt;1 - If you try a letter: you must allow the letter to be inserted into the input. We check whether the letter is in the list with the hidden word with a for loop. Every time the letter is found, in the found position the found letter is replaced in the list with the dashes and the list with the dashes and the letters is printed. Furthermore, 1 is added to the correct attempts.&lt;/p&gt;\r\n&lt;p&gt;If the letter is not found at the end of the for, 1 is added to the incorrect attempts.&lt;/p&gt;\r\n&lt;p&gt;A check must be carried out: if the incorrect attempts have reached the maximum limit we want to give (like 8 attempts), then the defeat is communicated to the player and the cycle is exited with a break.&lt;/p&gt;\r\n&lt;p&gt;A second check is made: if the correct attempts are &gt;= the length of the hidden word, then the player has won, this is reported and the cycle is exited with a break.&lt;/p&gt;\r\n&lt;p&gt;2 – If you try a word: we check that the word that the player enters in input is equal to the hidden word. If they are equal, the player has won and the cycle exits with a break. If they are different, one is added to the failed attempts.&lt;/p&gt;\r\n&lt;p&gt;The check must be done again: if the incorrect attempts have reached the maximum limit we want to give (like 8 attempts), then the defeat is communicated to the player and the cycle is exited with a break.&lt;/p&gt;\r\n&lt;p&gt;3 – The while loop condition must allow you to exit the loop when you enter the choice as 3&lt;/p&gt;', 0, '&lt;p&gt;Aisha&#x27;s redemption turned the battle around. His agile body moved like the wind, striking those who had previously been his allies with precision and momentum. Budi hesitated when he saw her deployed on the other side, slowing his attacks. The balance was shifting.&lt;/p&gt;\r\n&lt;p&gt;The heroes of good, reinvigorated by that new strength, raised their heads. Nuliajuk hounded Isabela with precise blows of the Golden Shield, while Strogar, dripping with sweat, repelled Nikolai&#x27;s slashes, his Earthsplitting Ax shaking with each impact.&lt;/p&gt;\r\n&lt;p&gt;But Shinzo Hanzei, the shadow runner, continued to dodge Jambalaya&#x27;s arrows, his eyes still clouded with the anger of an unforgiven past. He moved too fast, he seemed untouchable.&lt;/p&gt;\r\n&lt;p&gt;“Why are you still fighting us?” Jambalaya shouted, shooting an arrow that barely grazed Shinzo&#x27;s cheek. “Revenge has already stolen too much from you!”&lt;/p&gt;\r\n&lt;p&gt;Shinzo stopped, for just a second.&lt;/p&gt;\r\n&lt;p&gt;&quot;You don&#x27;t know what they did to me...&quot; he murmured, clenching his fists. But something was breaking inside him. He had always said he was fighting for justice, but what was right about the massacre he was participating in?&lt;/p&gt;\r\n&lt;p&gt;It was then that Chijioke screamed.&lt;/p&gt;\r\n&lt;p&gt;Camila had launched a wave of darkness against him, fueled by lies and the seduction of the senses. The Hammer of Truth shone in response, trying to dispel the illusory fog, but Chijioke was in trouble. Camila whispered words that dug into the heart, trying to bend it with doubt.&lt;/p&gt;\r\n&lt;p&gt;But Chijioke did not give in. Rather than be fooled, he raised the hammer above his head and hurled it with all his might at the spell. The truth exploded in blinding light, but the dark energy overwhelmed him. Camila was pushed back, but Chijioke fell to his knees, his chest torn open by the force he had wanted to purify.&lt;/p&gt;\r\n&lt;p&gt;«I… I don&#x27;t regret…» he whispered. «As long as one of us remains… the truth will live…»&lt;/p&gt;\r\n&lt;p&gt;It was then that Shinzo, standing in the ruins of that devastating blow, watched the young man fall. Chijioke had fought until the end for something he believed in, without hatred, without revenge. For the first time, Shinzo asked himself: And what am I really fighting for?&lt;/p&gt;\r\n&lt;p&gt;He slowly turned his gaze towards Nikolai, who continued to strike mercilessly. His commander. The one who had given him a reason to hate… but never one to live.&lt;/p&gt;\r\n&lt;p&gt;&quot;Enough...&quot; he murmured.&lt;/p&gt;\r\n&lt;p&gt;Then he shouted: &quot;ENOUGH!&quot;&lt;/p&gt;\r\n&lt;p&gt;And he lunged at Budi, saving Jambalaya from a blow from behind. The two fell to the ground, and Shinzo got up alongside the heroes of good. The second redemption was accomplished.&lt;/p&gt;\r\n&lt;p&gt;But the price had been very high. Chijioke did not get up&lt;/p&gt;', 27, 3, 'A second redemption', 0, 0, 0, '&lt;p&gt;Save a series of words to a file, wrapping each other.&lt;/p&gt;\r\n&lt;p&gt;Read the file with readlines, saving the various words in the list.&lt;/p&gt;\r\n&lt;p&gt;Extract a random number between 0 and the number of words contained in the list.&lt;/p&gt;\r\n&lt;p&gt;Ask the user to write a word among those contained in the file. Check whether the word written by the user is the same as the one in the randomly extracted position. If it is give a message to the user of victory, otherwise say that he made a mistake and lost.&lt;/p&gt;', 3),
(68, '55663b81-41fb-4908-a2ff-e27181f2f9d3', '&lt;p class=&quot;MsoNormal&quot;&gt;The battle is over. The good heroes have won. And this is where the journey to learning basic programming with Python ends. As a last exercise, a game to relax after the fatigue of battle:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Create a Python program that simulates a simplification of the game of BlackJack or 21. The game works like this: the dealer has 2 cards and the player has 2 other cards (random) from a deck of French cards (those with hearts, diamonds, clubs, spades). Whoever comes closest to the number 21 by adding the values ​​of the cards wins. Whoever goes beyond 21 loses. If the player has a low score, he can request more cards, one at a time. If, when the player stops, the dealer has a lower score, he must draw more cards. For game creation, functions and modules should be used to separate the functions from the main program. We will need a function that extracts random numbers from 2 to 11 (face cards are worth 10, the ace is 11). We need a second function to distribute the initial cards (therefore assign 2 random numbers to the banker and the player), which we can insert into 2 lists&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;We then need a third function to add a card when the player or dealer requests it. A fourth useful function could sum the scores of a list of cards. A fifth function to check if we have won or if the dealer has won. The main program must call the function to deal the starting cards, then ask the player with a loop whether he wants to add cards to the hand or not. If he says yes, we call the function to add a card to his hand. If he says no, the hand goes to the dealer, so we call the function to add cards to the dealer until the dealer&#x27;s point total is less than the player&#x27;s point total. At the end of the cycle we check who won with the appropriate function.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a4500b4726463.07728652_exercise_editor_6a341d388b2e79.04622284.jpg&quot; alt=&quot;&quot;&gt;The battlefield was a chaos of ice, fire and light. The shots echoed off the walls of the Canadian base, while the two sides fought to the end. But now the balance had changed. With &lt;strong&gt;Aisha&lt;/strong&gt; e &lt;strong&gt;Shinzo Hanzei&lt;/strong&gt; having switched to the side of good, the evil heroes began to falter.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Strogar&lt;/strong&gt;, affiancato da &lt;strong&gt;Shinzo&lt;/strong&gt; e &lt;strong&gt;Jambalaya&lt;/strong&gt;, incalzava &lt;strong&gt;Nikolai&lt;/strong&gt;. The dark commander fought with icy fury, his sword cutting the air in devastating slashes, but the power of the &lt;strong&gt;Earthsplitting Axe&lt;/strong&gt;, the arrows of&lt;strong&gt;Ivory Arch&lt;/strong&gt; and Shinzo&#039;s lightning speed put him in serious trouble.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Around them, one by one, the evil heroes fell: &lt;strong&gt;Camila&lt;/strong&gt;, abbattuta da Aisha; &lt;strong&gt;Budi&lt;/strong&gt;, immobilizzato da Nuliajuk; &lt;strong&gt;Lachlan&lt;/strong&gt;, forced to his knees by Strogar with a powerful blow that shook the earth.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Isabella&lt;/strong&gt;, isolated and furious, tried to avenge her companions, but was blocked by &lt;strong&gt;Nuliajuk&lt;/strong&gt; e &lt;strong&gt;Aisha&lt;/strong&gt; in an intense and no-holds-barred fight. Isabela fought with blind rage, but the renewed alliance of the heroes of good was too strong. She was defeated, but not killed: her gaze remained cold, full of contempt.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Meanwhile, the central duel reached its climax.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolai, wounded, slowed down. A powerful blow from Strogar staggered him. Jambalaya shot an arrow that forced him back. It was &lt;strong&gt;Emily&lt;/strong&gt; to close the circle.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Without powers, but with a courage that shone like pure light, &lt;strong&gt;Emily&lt;/strong&gt; he stood in front of Nikolai, meeting his gaze. He raised the blade, but hesitated: not out of pity, but out of confusion. Because that girl, without weapons, challenged him with the strength of her heart, not with violence.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;That moment was enough.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Strogar brought down the Earthsplitting Ax with all the force of justice. Nikolai&#039;s blade broke. The evil behemoth fell to his knees, then to the ground.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;The supreme commander of the evil heroes had fallen.&lt;/strong&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;With his defeat, the others were finally defeated and chained. Deep within the base, the &lt;strong&gt;crystal display cases&lt;/strong&gt;, ancient prisons designed to contain immense powers. One by one, the cases closed: on Isabela, Camila, Budi, Lachlan... All of them returned to the sleep designed by the Creator.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;But a guardian was missing.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Fu allora che &lt;strong&gt;Emily&lt;/strong&gt; si fece avanti.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&laquo;Someone will have to stay with them. If one day they wake up... there will have to be someone to raise the alarm. Someone you remember.&quot;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;The others protested, but she smiled sweetly.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&laquo;I made my choice. I have no powers, but I have a heart. And that&#039;s my job.&quot;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Emily entered the display room, closing the doors behind her. The last case closed on her, not as a prisoner, but as an eternal guardian.&lt;/p&gt;\r\n&lt;div class=&quot;MsoNormal&quot; style=&quot;text-align: center;&quot; align=&quot;center&quot;&gt;&lt;hr align=&quot;center&quot; size=&quot;2&quot; width=&quot;100%&quot;&gt;&lt;/div&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Silence fell.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ice once again reigned over the Canadian base. The evil heroes slept, watched by Emily&#039;s light. And even if Klaus, Chijioke and Emily no longer walked alongside their companions, their names would be remembered forever.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Because, in the end, &lt;strong&gt;light had prevailed over darkness.&lt;/strong&gt;&lt;/p&gt;', 28, 3, 'The End of History', 0, 0, 0, '&lt;p&gt;The battle is over. The good heroes have won. And this is where the journey to learning basic programming with Python ends. As a last exercise, a game to relax after the fatigue of battle:&lt;/p&gt;\r\n&lt;p&gt;Create the BlackJack game: draw 2 random numbers between 1 and 11 representing the dealer&#x27;s cards, draw two random numbers between 1 and 11 representing the player&#x27;s cards. Insert the first two numbers in a first list with append and the second two numbers in a second list with append. Communicate the lists to the player and ask if he wants to draw a new card. If it says &quot;yes&quot; then add a third random number to the player&#x27;s list of numbers. If he says &quot;no&quot; don&#x27;t do anything.&lt;/p&gt;\r\n&lt;p&gt;At this point the values ​​of the banker&#x27;s list and those of the player&#x27;s list are added. If the player exceeds 21 then he has lost. If the player&#x27;s sum is less than the banker&#x27;s then he has lost. If his sum is equal to that of the dealer, he still loses. If the player&#x27;s sum is greater than the dealer&#x27;s and less than or equal to 21 then tell the player that he has won.&lt;/p&gt;', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizio_domande`
--

CREATE TABLE `ct_esercizio_domande` (
  `id_ese_dom` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `fk_domanda` int NOT NULL,
  `fk_studente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizio_materiali`
--

CREATE TABLE `ct_esercizio_materiali` (
  `id_ese_mat` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `fk_materiale` int DEFAULT NULL,
  `link` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizio_risposte`
--

CREATE TABLE `ct_esercizio_risposte` (
  `id_ese_risp` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_risposta` int DEFAULT NULL,
  `testo_risposta` text,
  `fk_domanda` int DEFAULT NULL,
  `data_risposta` date NOT NULL,
  `fk_consegna` int NOT NULL,
  `commento_prof` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizi_quest`
--

CREATE TABLE `ct_esercizi_quest` (
  `id_ese_quest` int NOT NULL,
  `fk_capitolo` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `progressivo` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_esercizi_quest`
--

INSERT INTO `ct_esercizi_quest` (`id_ese_quest`, `fk_capitolo`, `fk_esercizio`, `progressivo`) VALUES
(1, 8, 1, 1),
(2, 8, 2, 2),
(3, 8, 3, 3),
(4, 8, 4, 4),
(5, 8, 5, 5),
(6, 8, 6, 6),
(7, 8, 7, 7),
(8, 8, 8, 8),
(9, 9, 9, 1),
(10, 9, 10, 2),
(11, 9, 11, 3),
(12, 9, 12, 4),
(13, 9, 13, 5),
(14, 10, 14, 1),
(15, 11, 15, 1),
(16, 11, 16, 2),
(17, 11, 17, 3),
(18, 11, 18, 4),
(19, 12, 19, 1),
(20, 12, 20, 2),
(21, 12, 21, 3),
(22, 12, 22, 4),
(23, 12, 23, 5),
(24, 12, 24, 6),
(25, 12, 25, 7),
(26, 12, 26, 8),
(27, 12, 27, 9),
(28, 13, 28, 1),
(29, 13, 29, 2),
(30, 13, 30, 3),
(31, 13, 31, 4),
(32, 13, 32, 5),
(33, 13, 33, 6),
(34, 13, 34, 7),
(35, 13, 35, 8),
(36, 14, 36, 1),
(37, 14, 37, 2),
(38, 14, 38, 3),
(39, 14, 39, 4),
(40, 14, 40, 5),
(41, 14, 41, 6),
(42, 14, 42, 7),
(43, 14, 43, 8),
(44, 15, 44, 1),
(45, 15, 45, 2),
(46, 15, 46, 3),
(47, 15, 47, 4),
(48, 15, 48, 5),
(49, 15, 49, 6),
(50, 15, 50, 7),
(51, 15, 51, 8),
(52, 15, 52, 9),
(53, 15, 53, 10),
(54, 16, 54, 1),
(55, 16, 55, 2),
(56, 16, 56, 3),
(57, 17, 57, 1),
(58, 17, 58, 2),
(59, 17, 59, 3),
(60, 17, 60, 4),
(61, 17, 61, 5),
(62, 17, 62, 6),
(63, 18, 63, 1),
(64, 18, 64, 2),
(65, 18, 65, 3),
(66, 18, 66, 4),
(67, 18, 67, 5),
(68, 18, 68, 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_forzieri_vinti`
--

CREATE TABLE `ct_forzieri_vinti` (
  `id_forziere` int NOT NULL,
  `livello_rarita` varchar(50) DEFAULT NULL,
  `fk_studente` int DEFAULT NULL,
  `aperto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_giornate_sconti`
--

CREATE TABLE `ct_giornate_sconti` (
  `id_giornata` int NOT NULL,
  `data` date NOT NULL,
  `motivazione` text NOT NULL,
  `sconto` int NOT NULL,
  `recurrent` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_griglie_valutazione`
--

CREATE TABLE `ct_griglie_valutazione` (
  `id_griglia` int NOT NULL,
  `nome_griglia` varchar(40) NOT NULL,
  `griglia` text NOT NULL,
  `fk_utente` int NOT NULL,
  `attiva` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_inviti_squadre`
--

CREATE TABLE `ct_inviti_squadre` (
  `id_invito` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_squadra` int NOT NULL,
  `a_r` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_libri_testo`
--

CREATE TABLE `ct_libri_testo` (
  `id_libro_testo` int NOT NULL,
  `titolo_libro` varchar(200) NOT NULL,
  `casa_editrice` varchar(150) NOT NULL,
  `autori` varchar(200) NOT NULL,
  `disattivato` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_libri_testo`
--

INSERT INTO `ct_libri_testo` (`id_libro_testo`, `titolo_libro`, `casa_editrice`, `autori`, `disattivato`) VALUES
(1, 'None', '', '', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_mail_abilitate`
--

CREATE TABLE `ct_mail_abilitate` (
  `id_mail_abilitata` int NOT NULL,
  `mail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_materiali`
--

CREATE TABLE `ct_materiali` (
  `id_materiale` int NOT NULL,
  `nome_materiale` varchar(200) DEFAULT NULL,
  `descrizione` text,
  `link_materiale` varchar(200) DEFAULT NULL,
  `fk_argomento` int DEFAULT NULL,
  `fk_utente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_materie`
--

CREATE TABLE `ct_materie` (
  `id_materia` int NOT NULL,
  `nome_materia` varchar(200) NOT NULL,
  `uuid` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_materie`
--

INSERT INTO `ct_materie` (`id_materia`, `nome_materia`, `uuid`) VALUES
(4, 'Informatics', 'c2c6e2ff-989d-48ec-b91a-a172e47b7880');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_messages`
--

CREATE TABLE `ct_messages` (
  `id_messaggio` int NOT NULL,
  `testo_messaggio` text,
  `fk_docente` int DEFAULT NULL,
  `fk_studente` int DEFAULT NULL,
  `fk_classe` int NOT NULL,
  `data_messaggio` datetime NOT NULL,
  `letto` int NOT NULL DEFAULT '0',
  `doc_stud` int NOT NULL DEFAULT '0',
  `fk_last_msg_rel` int DEFAULT NULL,
  `oggetto_messaggio` text NOT NULL,
  `eliminato` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_personaggi`
--

CREATE TABLE `ct_personaggi` (
  `id_personaggio` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `nome_personaggio` varchar(100) NOT NULL,
  `immagine` varchar(300) NOT NULL,
  `vita_iniziale` int NOT NULL,
  `descrizione` text NOT NULL,
  `color` varchar(15) NOT NULL,
  `bordercolor` varchar(15) NOT NULL,
  `mana_iniziale` int NOT NULL,
  `fk_classe` int NOT NULL,
  `img_senza_sfondo` varchar(200) NOT NULL,
  `originale` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_personaggi`
--

INSERT INTO `ct_personaggi` (`id_personaggio`, `uuid`, `nome_personaggio`, `immagine`, `vita_iniziale`, `descrizione`, `color`, `bordercolor`, `mana_iniziale`, `fk_classe`, `img_senza_sfondo`, `originale`) VALUES
(16, NULL, 'Aionios', '/assets/images/Personaggi/character_69ce805aea4733.44071893.png', 5, 'Aionios is a wise old man, of no estimable age, from India. Shrouded in an aura of mystery, he possesses a profound knowledge of the arcane arts, philosophy and ancient traditions. He lives in harmony with nature and often meditates for days at a time, seeking answers in the spiritual realms. He is passionate about astronomy and Indian classical music, using the sitar to accompany his moments of reflection. With his calm demeanor and a voice that conveys wisdom, Aionios is a spiritual and moral guide to the heroes, always ready to dispense advice or reveal enigmas hidden in the folds of time.', '#808080', '#efefef', 6, 1, '/assets/images/Personaggi/character_69ce805b01de73.68479614.png', 1),
(17, NULL, 'Aisha', '/assets/images/Personaggi/character_69ce808df3d000.22164240.png', 4, 'Aisha is a 17 year old girl from Ivory Coast, known for her incredible agility and dexterity. Part of the group of evil heroes, Aisha combines her feline grace with a cunning mind and unyielding determination. Raised in an environment that hardened her, she is skilled in hand-to-hand combat and stealth missions. Although his heart has been corrupted by the desire for power, his past reveals a depth that may belie an inner conflict. Elegant and lethal, Aisha is a figure who inspires respect and fear in her opponents.', '#000000', '#000000', 8, 1, '/assets/images/Personaggi/character_69ce808e087186.42143939.png', 1),
(18, NULL, 'Budi', '/assets/images/Personaggi/character_69ce82b760bcd4.91309496.jpg', 8, 'Budi is an 18-year-old boy from Thailand, known for his dark nature and impressive hunger. Despite his stocky build, which makes him quite imposing, he is a fearsome fighter, thanks to his unique ability: his body is elastic like rubber, allowing him to escape blows and attacks with surprising agility. His passion for food is legendary, and he can often be seen eating to excess, but his insatiable appetite is not just a vice: it is also a form of energy that fuels his power. A member of the Evil Heroes, Budi is cynical and opportunistic, ready to use his strength to destroy anyone who dares defy his will. His mood can change quickly, but when it comes to completing a mission, he is determined and ruthless.', '#4a7915', '#67a31f', 5, 1, '/assets/images/Personaggi/character_69ce82b765c091.56258820.png', 1),
(19, NULL, 'Camila', '/assets/images/Personaggi/character_69ce830b866289.42179879.jpg', 4, 'Camila is an 18 year old girl from Argentina, one of the evil heroines. Cunning and determined, she is a born manipulator, capable of controlling the minds of others with her power, bending them to her own will. Raised in a difficult environment, her desire for power and autonomy led her to choose the dark side, convinced that only strength and cunning can guarantee her place in the world. Elegant and charismatic, Camila is a formidable strategist and knows how to exploit every enemy weakness to her advantage. Despite her ruthless nature, she retains a sharp intelligence and deep ambition that make her a fearsome and complex figure.', '#4287dd', '#06388a', 6, 1, '/assets/images/Personaggi/character_69ce830b8b4e02.48398607.png', 1),
(20, NULL, 'Chijioke', '/assets/images/Personaggi/character_69ce835b58bb21.57713415.jpg', 5, 'Chijioke is a 17-year-old boy from Nigeria, a do-gooder known for his calm resolve and extraordinary intelligence. Tall and slender, with dark curly hair and lively eyes, Chijioke is deeply connected to his homeland and its traditions. He is passionate about music, especially the sound of traditional percussion, and spends his free time playing drums or telling stories around the fire. Chijioke also loves football, combining his athletic skills with strategy, and brings this team spirit to his adventures too. Beyond that, he has a great interest in nature and often gets lost contemplating wild landscapes, gathering healing herbs, and studying the natural world. His analytical mind and generous heart make him a valuable guide to the group, always ready to sacrifice himself for the good of others.', '#13f01b', '#aee5b0', 5, 1, '/assets/images/Personaggi/character_69ce835b5d7387.92847611.png', 1),
(21, NULL, 'Emily', '/assets/images/Personaggi/character_69ce839443c0f3.60983577.jpg', 5, 'Emily, 17, is a Canadian girl who represents the ideals of good heroes with her determination and kind spirit. With her ash blonde hair and eyes as blue as the lakes of her hometown, Emily is a symbol of grace and strength. Passionate about ice skating since she was a child, her movements reflect the elegance and precision of this sport. During battles, these qualities translate into extraordinary agility and the ability to anticipate opponents\' movements. Emily is courageous and selfless, always willing to put others first. Her passion for Canadian nature and ice makes her particularly attached to challenges involving cold and hostile environments.', '#0507c9', '#000000', 7, 1, '/assets/images/Personaggi/character_69ce8394486f62.87187658.png', 1),
(22, NULL, 'Isabela', '/assets/images/Personaggi/character_69ce83ecd9fa97.34135328.png', 3, 'Isabela is a 16 year old girl from Brazil. Of a determined and ambitious nature, she grew up near the Amazon rainforest, developing a deep bond with nature and animals. She is passionate about Brazilian martial arts, especially capoeira, and loves dancing samba, immersing herself in the culture and music of her country. Her passion for adventure and risk sometimes leads her to make extreme choices.', '#ffbf66', '#d19330', 8, 1, '/assets/images/Personaggi/character_69ce83ece20ea3.50738754.png', 1),
(24, NULL, 'Jambalayah', '/assets/images/Personaggi/character_69ce844f394363.26990335.jpg', 4, 'Jambalayah is a 17-year-old boy from Jamaica, with an athletic build and an infectious smile. Growing up near the sea, he has a deep passion for swimming and sailing. He loves reggae music, which accompanies him in every free moment, and often plays the djembe drum. Jambalayah is adventurous and courageous, always ready to dive into new challenges, but also thoughtful and respectful of the traditions of his land. Despite the difficulties, he always keeps hope alive, guided by the belief that good can prevail.', '#4efee0', '#3e847c', 7, 1, '/assets/images/Personaggi/character_69ce844f3e0f31.74925631.png',1),
(25, NULL, 'Klaus', '/assets/images/Personaggi/character_69ce8496240a59.26169556.png', 6, 'Klaus is a 16 year old boy, originally from Germany, characterized by an introverted and reflective nature. Although he prefers solitude, he possesses a deep sense of justice that pushed him to join the heroes of good. Klaus excels at strategic thinking, often finding innovative solutions to the most complex problems. He loves reading and delving into history and philosophy, and his intuition makes him a precious ally in battle. Despite his reserved nature, his loyalty to his companions is unwavering, and in moments of crisis he can demonstrate silent but powerful courage.', '#a8ecff', '#5f9091', 5, 1, '/assets/images/Personaggi/character_69ce84962b3bf2.56273855.png', 1),
(26, NULL, 'Lachlan', '/assets/images/Personaggi/character_69ce85413b9dc2.02231516.jpg', 5, 'Lachlan, known as the Destroyer, is an 18-year-old boy from Australia, one of the most feared evil heroes. Behind his sunny appearance and deceptive smile lies a ruthless and ambitious personality. Lachlan is a lover of extreme sports, such as surfing giant waves and climbing inaccessible cliffs, activities that have honed his physical strength and dexterity. However, his true interest is the adrenaline rush of destruction: he loves to use his ability to manipulate kinetic energy to wreak havoc and subdue his opponents. Although he is an excellent strategist, Lachlan is often driven by the desire to demonstrate his superiority, making him as cunning as he is dangerous.', '#f0137e', '#600b5e', 6, 1, '/assets/images/Personaggi/character_69ce854141e0a9.98704522.png', 1),
(27, NULL, 'Nikolaj', '/assets/images/Personaggi/character_69ce858e33f139.09160165.jpg', 7, 'Nikolaj, 19 years old, from frozen Russia, is the undisputed leader of the evil heroes. His presence is imposing, with icy eyes that seem to peer into the soul, dark and short hair, and an always upright posture, which conveys authority and power. Nikolaj is a master of strategy and deception, capable of manipulating anyone to achieve his goals. Equipped with a dark power that seems to draw energy directly from his inner evil, he is able to generate devastating shockwaves, control the darkness and undermine the will of others. Cold, ruthless and ambitious, Nikolai despises any form of weakness and believes that absolute power is the only goal worth pursuing. Despite his cruelty, he is also incredibly intelligent and charismatic, luring other evil heroes to him with promises of glory and domination. His only real interest, however, is to consolidate his position as ruler of the world.', '#8a0606', '#db0606', 7, 1, '/assets/images/Personaggi/character_69ce858e37bd96.86457513.png', 1),
(28, NULL, 'Nuliajuk', '/assets/images/Personaggi/character_69ce85cf74bfa9.89269479.jpg', 5, 'Nuliajuk is a 16 year old girl from Alaska, a proud Inuit and one of the heroines of good. Her connection to nature runs deep, and she grew up learning the traditions of her people, including ice fishing. Skilled and determined, she has incredible endurance and inner strength that allows her to face the toughest challenges. Her indomitable spirit and wisdom are central to the group, and her connection to the natural elements gives her unique powers related to ice and snow. In addition to fishing, Nuliajuk loves exploring the wild landscape around her, always finding a sense of peace and strength in solitude. His loyalty to his comrades is unwavering, and he will always fight to protect those he loves, using his abilities to maintain harmony between the human and natural worlds.', '#f7bc0a', '#b08505', 7, 1, '/assets/images/Personaggi/character_69ce85cf798624.82952706.png', 1),
(29, NULL, 'Shinzo Hanzei', '/assets/images/Personaggi/character_69ce861a64f7b7.11640481.jpg', 5, 'Shinzo Hanzei is a young Japanese man with a rebellious and determined character, marked by a past of pain and injustice that led him to side with the heroes of evil. Passionate about racing and speed, he lives for the adrenaline of risk and stands out for his incredible speed of movement, which makes him an almost elusive opponent. Shinzo is an expert pilot and cunning fighter, using his speed to both attack with precision and escape danger. Behind his cold mask hides a wounded heart, still fighting against the demons of his past.', '#7706c2', '#41016b', 7, 1, '/assets/images/Personaggi/character_69ce861a699737.66095323.png', 1),
(30, NULL, 'Strogar', '/assets/images/Personaggi/character_69ce865a76c014.59840190.jpg', 7, 'Strogar, 45, is an enigmatic neutral hero, whose scarred face tells of a life of battles and hardships. He sided with neither good nor evil, preferring to observe the world and its conflicts from an external position. His physical strength is matched only by his determination to maintain balance in a world constantly divided between light and darkness. Passionate about history, Strogar maintains a collection of manuscripts and ancient texts on the history of humanity, which he meticulously updates with the events he experiences firsthand. While he lacks the mystical wisdom of Aionios, his pragmatism and knowledge of history make him a respected figure. Strogar is a silent but decisive presence, capable of influencing the course of events with a single intervention at the right time.', '#a2c904', '#5a7003', 5, 1, '/assets/images/Personaggi/character_69ce865a7b3851.98911790.png', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_personalizzazioni`
--

CREATE TABLE `ct_personalizzazioni` (
  `id_personalizzazione` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `nome_personalizzazione` varchar(100) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `costo` int DEFAULT NULL,
  `fk_personaggio` int DEFAULT NULL,
  `fk_classe` int DEFAULT NULL,
  `fk_studente` int DEFAULT NULL,
  `approvata` int NOT NULL DEFAULT '0',
  `descrizione` text,
  `suffisso_costume` varchar(100) DEFAULT NULL,
  `fk_set` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_personalizzazioni`
--

INSERT INTO `ct_personalizzazioni` (`id_personalizzazione`, `uuid`, `nome_personalizzazione`, `img`, `tipo`, `costo`, `fk_personaggio`, `fk_classe`, `fk_studente`, `approvata`, `descrizione`, `suffisso_costume`, `fk_set`) VALUES
(111, 'e62dcaef-9d2c-425a-b736-ebe61dd7c492', 'Blue Flag', '/assets/images/Personalizzazioni/Sfondi/Bandiera_azzurra_20260614_083257_1b3e0ad8.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(112, '02da2954-b3d8-4e32-a0e8-e0268892e99b', 'Black and White Flag', '/assets/images/Personalizzazioni/Sfondi/Bandiera_bianconera_20260614_083321_965d5e26.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(113, 'd91423d8-6f8f-4670-ba4e-27e88e1fe9fb', 'Yellow-Blue Flag', '/assets/images/Personalizzazioni/Sfondi/Bandiera_gialloblu_20260614_083343_a7ffc9d9.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(114, '329c25dd-1ab8-4fca-bc4b-d31be7a05e68', 'Yellow-red flag', '/assets/images/Personalizzazioni/Sfondi/Bandiera_giallorossa_20260614_083411_498d51a8.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(115, '64a162a4-4ce0-40d6-9987-099275aff175', 'Nerazzurri flag', '/assets/images/Personalizzazioni/Sfondi/Bandiera_nerazzurra_20260614_083429_33463ec3.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(116, 'c48b0699-6191-42ed-8795-e8ffc684c83d', 'Red and Black flag', '/assets/images/Personalizzazioni/Sfondi/Bandiera_rossonera_20260614_083449_6b237191.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(117, 'f3b7ecab-d196-429c-acb6-03db3e942622', 'Football Field', '/assets/images/Personalizzazioni/Sfondi/campo_calcio_20260614_083513_53b5d327.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(118, '955e7688-ba11-4bd5-828d-1d7c2c2f235c', 'City', '/assets/images/Personalizzazioni/Sfondi/sfondo_citta_20260614_083529_9fcd7c5f.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(119, 'f3b8494b-1398-4746-b11d-983d3438e925', 'Hill', '/assets/images/Personalizzazioni/Sfondi/sfondo_paese_collina_20260614_083549_3befd4a6.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(120, '594f7c0d-69ff-41f7-9c3d-8392db419bad', 'River', '/assets/images/Personalizzazioni/Sfondi/sfondo_fiume_20260614_083603_62431c9e.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(121, '3499fada-7889-491d-9bdb-d840fead4d15', 'Forest', '/assets/images/Personalizzazioni/Sfondi/sfondo_vegetazione_20260614_083633_b28daefc.png', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(122, '18a4a532-dea6-49f5-a1eb-64e05a63d6e5', 'Ice', '/assets/images/Personalizzazioni/Sfondi/sfondo_ghiacci_20260614_083651_c00483f8.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(123, 'abdb1d09-bde4-4a0b-ad4a-4380c1b1d344', 'Mountains', '/assets/images/Personalizzazioni/Sfondi/sfondo_montagne_20260614_083706_f4f6df07.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(124, 'd8b5144c-6c50-4bd3-918a-5e7e8895fe58', 'Christmas', '/assets/images/Personalizzazioni/Sfondi/sfondo_natale_20260614_083723_fe525e00.jpg', 'Sfondo', 120, 0, 1, NULL, 1, '', '', 5),
(125, 'cbb2ee27-cd65-4de0-8606-35f5ce2459fa', 'Oasis', '/assets/images/Personalizzazioni/Sfondi/sfondo_oasi_20260614_083750_6b12003b.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(126, '555088d1-9bba-4c3a-b319-0b9f5f632510', 'Ocean', '/assets/images/Personalizzazioni/Sfondi/sfondo_oceano_20260614_083806_6b7492b2.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(127, 'e0a3eef8-85b1-47f0-9cb9-f87e810156e5', 'Robot Rain', '/assets/images/Personalizzazioni/Sfondi/robot_pioggia_20260614_083827_54b7f260.jpg', 'Sfondo', 150, 0, 1, NULL, 1, '', '', 5),
(128, 'a7724b53-4e46-4af5-b074-9e110684bbea', 'Savannah', '/assets/images/Personalizzazioni/Sfondi/sfondo_savana_20260614_083903_700f0ed4.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(129, '99b19c4a-2db7-4654-9b48-f313599823fb', 'Universe', '/assets/images/Personalizzazioni/Sfondi/sfondo_spazio_20260614_083849_a6a71f09.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(130, 'e172112a-326c-4b80-a930-e2068b00fd05', 'Ancient Ruins', '/assets/images/Personalizzazioni/BigBackground/Ancient_ruins_bg_20260614_084311_4231e076.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(131, '45bb02e9-42c2-455d-85a8-62d0f841183e', 'Fantasy Arch', '/assets/images/Personalizzazioni/BigBackground/fantasy_bg_20260614_084331_58f53b22.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(132, '0f596f16-86bc-42cc-bc1e-d58b7ecd3624', 'Mystical Circle', '/assets/images/Personalizzazioni/BigBackground/Rune_Magic_bg_20260614_084354_17ad9784.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(133, '1d68fd9e-fc44-483f-91fa-f49f45fa9935', 'Desert', '/assets/images/Personalizzazioni/BigBackground/Desert_bg_20260614_084413_b0490951.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(134, '472dbb77-6d8d-4298-99af-ba46cf3762ed', 'Enchanted Forest', '/assets/images/Personalizzazioni/BigBackground/EnchantedForest_bg_20260614_084437_4253220c.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(135, '02e433e5-8102-4475-8d9a-696445a17b5b', 'Demonic land', '/assets/images/Personalizzazioni/BigBackground/Demoniac_bg_20260614_084456_97552417.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(136, '7e674a2d-c2e2-491c-b527-2ca68715b9da', 'Infected Land', '/assets/images/Personalizzazioni/BigBackground/Cursed_landscape_bg_20260614_084514_0f690a97.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(137, '338095df-2957-4243-9cd2-721a483755c5', 'Snow-capped mountains', '/assets/images/Personalizzazioni/BigBackground/Snow_mountains_bg_20260614_084536_657ee39c.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(138, 'a7be7942-82af-4562-9ba4-8062f71fef09', 'Cartoon landscape', '/assets/images/Personalizzazioni/BigBackground/DBall_bg_20260614_084605_5d927a9c.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(139, '1638cac8-d193-48b3-b6bf-1b4208792bee', 'Basilisk', '/assets/images/Personalizzazioni/Pet/Basilisco_20260617_115009_1e4b5573.png', 'Pet', 1000, 0, 1, NULL, 1, '', '', 7),
(140, '91f3a548-0b52-424b-b7ff-1fea3b460bc5', 'Blue Basilisk', '/assets/images/Personalizzazioni/Pet/BlueBasilisk_20260617_115048_cf6ecda4.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(141, 'fa6076b4-9d18-40fe-9324-a793758ecf74', 'Golden Basilisk', '/assets/images/Personalizzazioni/Pet/GoldenBasilisk_20260617_115123_8fdc85a5.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(142, '48922fd6-99a4-4f3a-9a40-685007c36606', 'Red Basilisk', '/assets/images/Personalizzazioni/Pet/RedBasilisk_20260617_115154_7eee420e.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(143, '6e5daf58-3721-4f7f-bf5e-9ad8b092185c', 'Blue Fenrir', '/assets/images/Personalizzazioni/Pet/BlueFenrir_20260617_115223_8ec20264.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(144, 'c32d4426-5094-4db4-9186-058c43a5241b', 'Blue Phoenix', '/assets/images/Personalizzazioni/Pet/BluePhoenix_20260617_115257_6e0f091e.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(145, '61b717ca-39a4-4ab2-81bc-3eea684556e5', 'Blue Unicorn', '/assets/images/Personalizzazioni/Pet/Blue_Unicorn_20260617_115330_76075735.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(146, '3f6e2ebc-5b89-4ab7-847d-5427735069a4', 'White Dragon', '/assets/images/Personalizzazioni/Pet/Cute_White_Dragon_20260617_115406_7eebc1ea.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(147, '4d72bf74-3a08-47ad-92d3-645a30637e5e', 'Blue Dragon', '/assets/images/Personalizzazioni/Pet/Cute_Blue_Dragon_20260617_115437_0eb5c6e9.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(148, 'f943b11e-147a-41e5-be80-083659e90d4f', 'Golden Dragon', '/assets/images/Personalizzazioni/Pet/Cute_Golden_Dragon_20260617_115519_b9550f81.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(149, '64342418-c1e1-4463-8ec0-fd17bd898a7b', 'Red Dragon', '/assets/images/Personalizzazioni/Pet/Cute_Red_Dragon_20260617_115548_f412519e.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(150, 'cb86e1e7-c919-475f-9551-9f55ef252dd7', 'Green Dragon', '/assets/images/Personalizzazioni/Pet/Cute_green_dragon_20260617_115625_79a0ce68.png', 'Pet', 1000, 0, 1, NULL, 1, '', '', 7),
(151, '00edae5b-09c1-4ac7-bfe8-8c165eaeb0f3', 'Fenrir', '/assets/images/Personalizzazioni/Pet/Fenrir_20260617_115704_18798ebf.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(152, '873b5a78-ac47-4f50-a7c4-1f79aa3ff75f', 'Golden Fenrir', '/assets/images/Personalizzazioni/Pet/GoldenFenrir_20260617_115732_721ee065.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(153, '6b98ffa0-edff-45b6-9a5c-62317daac5b8', 'Golden Unicorn', '/assets/images/Personalizzazioni/Pet/Golden_unicorn_20260617_115810_c89dfe25.png', 'Pet', 2400, 0, 1, NULL, 1, '', '', 7),
(154, 'f7cf5688-3c9e-4b3f-9350-61487aaa7897', 'Green Fenrir', '/assets/images/Personalizzazioni/Pet/GreenFenrir_20260617_115853_adae76d2.png', 'Pet', 2000, 0, 1, NULL, 1, '', '', 7),
(155, '4b98e260-707b-42d2-9292-9eef6fdd35ec', 'Green Phoenix', '/assets/images/Personalizzazioni/Pet/GreenPhoenix_20260617_115920_e105f116.png', 'Pet', 2000, 0, 1, NULL, 1, '', '', 7),
(156, 'd3b6eab2-df34-4ed5-8e35-4d752bfeba82', 'GrifonBlue', '/assets/images/Personalizzazioni/Pet/Grifonblue_20260617_120002_ba9f1778.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(157, 'c80d3aa6-6246-4eed-b86d-3ca83c3af220', 'Gryffindor', '/assets/images/Personalizzazioni/Pet/Grifondoro_20260617_120036_3e23771e.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(158, '53f0a592-0aba-4328-8e79-ced7311e3cf5', 'Griffin', '/assets/images/Personalizzazioni/Pet/GrifonBaby_20260617_120106_ee82f923.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(159, '8d16dc32-f4df-4a69-b5f1-6d218b448215', 'White Griffin', '/assets/images/Personalizzazioni/Pet/GrifoneBianco_20260617_120138_0ca05249.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(160, '4e4c9259-a969-4866-b6e3-dcb7c5e7a306', 'GrifonRed', '/assets/images/Personalizzazioni/Pet/GrifonRed_20260617_120213_bafb86b3.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(161, 'd01161a8-5414-4f60-a4f4-7aa30d84028b', 'Hydra', '/assets/images/Personalizzazioni/Pet/Hydra_20260617_120251_19f932ed.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(162, 'e25f51b5-78aa-46f6-8526-fb3abb40c945', 'Hippogriff', '/assets/images/Personalizzazioni/Pet/Ippogrifo_20260617_120327_b632e5ba.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(163, '308f16a5-f389-4ffe-bfe8-0c85262d8558', 'Blue Hippogriff', '/assets/images/Personalizzazioni/Pet/BlueHippo_20260617_120353_ee5b52d2.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(164, '0fca5b40-7d30-46eb-bf09-f9a29ffcbf98', 'Golden Hippogriff', '/assets/images/Personalizzazioni/Pet/GoldenHippo_20260617_120422_c9315d45.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(165, '696eaeff-5051-4985-a43c-3856edc3ae64', 'Green hippogriff', '/assets/images/Personalizzazioni/Pet/GreenHippo_20260617_120450_cfc2ccb9.png', 'Pet', 1000, 0, 1, NULL, 1, '', '', 7),
(166, '44bcd949-e59a-4f36-8151-55e07155ed33', 'Leviathan', '/assets/images/Personalizzazioni/Pet/Leviathan_20260617_120519_760eb44c.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(167, '94d7f9af-6b41-4d42-99d3-d5418756e876', 'White Leviathan', '/assets/images/Personalizzazioni/Pet/White_Leviathan_20260617_120554_8e1d0fab.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(168, '9a171441-8c12-45c2-9c6d-a0c0b78fcc7b', 'Golden Leviathan', '/assets/images/Personalizzazioni/Pet/Golden_Leviathan_20260617_120624_233fb5af.png', 'Pet', 2400, 0, 1, NULL, 1, '', '', 7),
(169, '73c78fd1-ae2d-4c99-877e-cbef0df69248', 'Red Leviathan', '/assets/images/Personalizzazioni/Pet/RedLeviathan_20260617_120701_2b0b4fcf.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(170, 'd36d288b-cafa-4ea6-b9c6-5872e7d7987d', 'Green Leviathan', '/assets/images/Personalizzazioni/Pet/GreenLeviathan_20260617_120733_52559198.png', 'Pet', 2000, 0, 1, NULL, 1, '', '', 7),
(171, 'b088bb7c-dae7-4ce8-85a5-be6fe963baf4', 'Manticore', '/assets/images/Personalizzazioni/Pet/Manticore_20260617_120810_2c248f95.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(172, '226c7fcf-def6-4b98-a6c6-a37e52b8300d', 'White Manticore', '/assets/images/Personalizzazioni/Pet/WhiteManticore_20260617_120842_27cc4d0c.png', 'Pet', 1800, 0, 1, NULL, 1, '', '', 7),
(173, '63e497e2-018c-4581-81b8-7fc48d09d71b', 'Golden manticore', '/assets/images/Personalizzazioni/Pet/GoldenManticore_20260617_120926_b727c73c.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(174, 'ad534211-1b38-495f-99db-9565a3b91394', 'Green Manticore', '/assets/images/Personalizzazioni/Pet/GreenManticore_20260617_120954_94505bba.png', 'Pet', 3000, 0, 1, NULL, 1, '', '', 7),
(175, 'f70089dc-625f-48df-9b0d-da1637aafc35', 'Purple Manticore', '/assets/images/Personalizzazioni/Pet/PurpleManticore_20260617_121036_928bd5d0.png', 'Pet', 1500, 0, 1, NULL, 1, '', '', 7),
(176, '9a41d0c8-f5a7-49da-badf-f838ad0bdd85', 'Pegasus', '/assets/images/Personalizzazioni/Pet/Pegasus_20260617_121102_1b548884.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(177, '8dee8bdf-f9de-4694-ab32-1e2067280302', 'Purple Phoenix', '/assets/images/Personalizzazioni/Pet/VioletPhoenix_20260617_121143_f2693168.png', 'Pet', 4000, 0, 1, NULL, 1, '', '', 7),
(178, 'be421028-f2ea-4ec8-9fe7-d10ccc6f7dfc', 'Red Fenrir', '/assets/images/Personalizzazioni/Pet/RedFenrir_20260617_121211_cf3ef468.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(179, '2fbf2708-5078-4dc8-a7d1-d6a5d2d5c832', 'Red Phoenix', '/assets/images/Personalizzazioni/Pet/Phoenix_20260617_121248_85de8e35.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(180, '0ec0ab7f-82ff-400b-8156-479902085c13', 'Red Unicorn', '/assets/images/Personalizzazioni/Pet/Red_unicorn_20260617_121315_20c49dc0.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(181, '01e2f633-635d-41b4-9a73-236342e13950', 'Sphinx', '/assets/images/Personalizzazioni/Pet/Sphinx_20260617_121358_a308986d.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(182, 'd4c18d8b-6652-4525-bf89-9180515fbc4b', 'White Sphinx', '/assets/images/Personalizzazioni/Pet/WhiteSphinx_20260617_121428_33ddd01d.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(183, '923b07c3-967d-4b82-82e6-24ff3f70af2d', 'Blue Sphinx', '/assets/images/Personalizzazioni/Pet/BlueSphinx_20260617_121456_02728a06.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(184, '4433417b-127f-4df1-8a0c-b4545ff8c006', 'Green Sphinx', '/assets/images/Personalizzazioni/Pet/GreenSphinx_20260617_121519_54b90909.png', 'Pet', 3000, 0, 1, NULL, 1, '', '', 7),
(185, '50179346-9abf-4ffa-9a58-9674ef328c55', 'Purple Sphinx', '/assets/images/Personalizzazioni/Pet/VioletSphinx_20260617_121603_4f80cb39.png', 'Pet', 3000, 0, 1, NULL, 1, '', '', 7),
(186, '4462ed84-9cdb-4957-bcd2-b0e65c7f0cdf', 'Unicorn', '/assets/images/Personalizzazioni/Pet/Unicorn_20260617_121640_68ed5051.png', 'Pet', 2200, 0, 1, NULL, 1, '', '', 7),
(187, '9a5cfea1-6cd8-4741-abdc-ef2b6024c9b9', 'White Phoenix', '/assets/images/Personalizzazioni/Pet/WhitePhoenix_20260617_121714_97c3718d.png', 'Pet', 1800, 0, 1, NULL, 1, '', '', 7),
(188, '2e37277b-64c3-40e4-97ec-740b370f63cc', 'White Unicorn', '/assets/images/Personalizzazioni/Pet/WhiteUnicorn_20260617_121744_ff7ea395.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(189, '29bdb3da-f4ac-4f06-9d4b-aef13568c67e', 'Azzurri shirt', '/assets/images/Personalizzazioni/Equip/Maglia_Azzurri_20260617_084236_337c3181.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Favorite team shirt: Azzurri', '_azzurri.png', 8),
(190, '4e9b5d5f-634f-438a-aa2a-446ac5fef4bc', 'Black and white shirt', '/assets/images/Personalizzazioni/Equip/Maglia_bianconera_20260617_084341_53013c1f.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Favorite team shirt: Bianconeri', '_bianconero.png', 8),
(191, 'f85ba766-063b-4833-b11a-ecee69c6ac4c', 'Yellow and Blue shirt', '/assets/images/Personalizzazioni/Equip/Maglia_gialloblu_20260617_084438_d39f41a9.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Favorite team shirt: yellow and blue', '_gialloblu.png', 8),
(192, '424b94dc-cb4e-4947-b232-24b3486b8c24', 'GialloRossi shirt', '/assets/images/Personalizzazioni/Equip/Maglia_GialloRossi_20260617_084548_43426ced.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Favorite team shirt: yellow and red', '_giallorosso.png', 8),
(193, 'bbb9561d-ca57-4c22-9485-300ac111b4c2', 'Nerazzurri shirt', '/assets/images/Personalizzazioni/Equip/Maglia_Nerazzurri_20260617_084641_775bf9db.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Shirt of my favorite team: Nerazzurri', '_nerazzurro.png', 8),
(194, 'd463f8af-0b52-41ea-94ef-a93d570a0716', 'Black and white shirt', '/assets/images/Personalizzazioni/Equip/Maglia_Nerobianchi_20260617_084747_5c134000.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Favorite team shirt: black and white', '_nerobianco.png', 8),
(195, '633a5633-1a02-4cd3-b2bd-f7dd7d8b1170', 'Rossoneri shirt', '/assets/images/Personalizzazioni/Equip/Maglia_RossoNeri_20260617_084837_8a0d2086.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Shirt of my favorite team: Rossoneri', '_rossonero.png', 8),
(196, '505e5ba5-6bba-4e6d-9620-a09df57c017a', 'Librarian clothes', '/assets/images/Personalizzazioni/Equip/Veste_bibliotecario_saggio_20260615_084330_2bcab6da.png', 'Equipaggiamento', 800, 0, 1, NULL, 1, 'Clothes that increase the wearer\'s wisdom and therefore the experience gained per exercise', '_librarian.png', 9),
(197, '936240b3-57dd-46cf-bac7-6049ffa215d4', 'Robes of the Burgomaster', '/assets/images/Personalizzazioni/Equip/riccone_fantasy_20260615_084436_ee59bda4.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Clothes worn by merchants who become the richest in the city, elevated to the rank of Burgomasters. They are able to achieve almost miraculous gains.', '_borgomaster.png', 9),
(198, '17b86819-3640-4c90-ba60-c7e586411795', 'Merchant\'s Clothes', '/assets/images/Personalizzazioni/Equip/mercante_povero_20260615_084521_6f0add5c.png', 'Equipaggiamento', 400, 0, 1, NULL, 1, 'Clothes worn by novice merchants allow you to increase your earnings slightly', '_merchant_base.png', 9),
(199, 'beedcdb3-a6f1-4dc0-b9a3-f2923b76e3f6', 'Clothes of the Rich Merchant', '/assets/images/Personalizzazioni/Equip/mercante_20260615_084614_208f8b04.png', 'Equipaggiamento', 700, 0, 1, NULL, 1, 'Clothes worn by merchants who became rich thanks to trade, able to obtain good profits from trade', '_merchant_rich.png', 9),
(200, 'd43ad460-1cae-45c4-8fb8-3104aa0bef8d', 'Shadow Assassin Outfit', '/assets/images/Personalizzazioni/Equip/Assassino_ombra_20260617_085615_03cc5a9d.png', 'Equipaggiamento', 3000, 0, 1, NULL, 1, 'Robes worn by the most skilled assassins in the realm, who move in the shadows and gain experience by defeating enemies', '_shadow_assassin.png', 9),
(201, '9da47d9d-c097-44ba-a743-8b56958d0b44', 'Golden Armor', '/assets/images/Personalizzazioni/Equip/Opulent_golden_armor_20260615_084805_15299e79.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Gold armor covered in gems. Worn only by warriors and kings who can afford it. Increases defense and allows you to earn more coins (money calls money)', '_golden_armor.png', 9),
(202, '36c8a215-dcdd-4c19-9681-cfa8516053c2', 'Football armor', '/assets/images/Personalizzazioni/Equip/armatura_football_20260615_084902_065cf057.png', 'Equipaggiamento', 1700, 0, 1, NULL, 1, 'Football player armor: improves defense, earnings (NFL players are rich) and also accumulated experience (playing sports also helps keep the mind active)', '_football_armor.png', 9),
(203, '37afac1b-6998-4c8b-bd9b-63b11a839588', 'Centurion armor', '/assets/images/Personalizzazioni/Equip/Centurione_20260615_085003_3940d271.png', 'Equipaggiamento', 2600, 0, 1, NULL, 1, 'Armor worn by Roman centurions in Caesar Augustus\' military campaigns: increased defense, increased tactics, increased experience', '_centurion.png', 9),
(204, '22891956-9565-4862-9bc3-00f9df82ca4f', 'Dragon Armor', '/assets/images/Personalizzazioni/Equip/dragon_armor_20260615_085056_a3101dd4.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Epic dragon scale armor, impenetrable to physical attacks. Guarantees maximum protection.', '_dragon_armor.png', 9),
(205, 'f3b99227-2b58-423d-8c52-d9011b9063c9', 'Samurai armor', '/assets/images/Personalizzazioni/Equip/Samurai_20260615_085239_5add48da.png', 'Equipaggiamento', 3000, 0, 1, NULL, 1, 'Armor worn by the wise and courageous Japanese Samurai warriors in the service of the Shogun', '_samurai.png', 9),
(206, 'c1cc7452-5455-4fe0-9234-aca78f65ebff', 'Light armor', '/assets/images/Personalizzazioni/Equip/armatura_leggera_20260615_085326_64e53722.jpg', 'Equipaggiamento', 400, 0, 1, NULL, 1, 'Leather armor capable of blocking light blows', '_light_armor.png', 9),
(207, '770de6e5-deb6-46eb-ba7a-a4ce09296c9c', 'Magitech armor', '/assets/images/Personalizzazioni/Equip/Magitech_Armor_20260617_083729_af72b6a7.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Legendary Armor forged by ancient wizards, who infused magical powers into the stones that cover it. Offers protection and magical power', '_magitech.png', 9),
(208, '745f6198-d8f4-45a4-a7f1-969c999a4840', 'Heavy armor', '/assets/images/Personalizzazioni/Equip/armatura_pesante_20260617_083816_3f0a7573.jpg', 'Equipaggiamento', 700, 0, 1, NULL, 1, 'Steel plate armor capable of blocking even heavy blows', '_heavy_armor.png', 9),
(209, '5c7c7277-a07d-4c07-b054-29f155175fb3', 'Martial arts outfit', '/assets/images/Personalizzazioni/Equip/Martial_arts_suite_20260617_083913_61563a23.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'An outfit that gives you energy for combat: it allows you to increase experience points and coins earned', '_martial_arts.png', 9),
(210, 'f8e3264f-ed9a-41c7-b5ef-a86b67f48913', 'Viking armor', '/assets/images/Personalizzazioni/Equip/Vichingo_20260617_084023_34a37f54.png', 'Equipaggiamento', 2100, 0, 1, NULL, 1, 'The fur-lined armor worn by the tough Vikings of the north', '_viking.png', 9),
(211, '13a5ec51-8581-4b05-9c9b-4518d9400133', 'Forest Druid', '/assets/images/Personalizzazioni/Equip/Druido_Foresta_20260617_084131_ba9312e4.png', 'Equipaggiamento', 2500, 0, 1, NULL, 1, 'Robes worn by the arcane and wise druids of the forest, who govern the magic of nature, surrounding themselves with an armor of tree bark', '_forest_druid.png', 9),
(212, '5f4c5df9-eec0-4803-852d-4f72abb8a8ed', 'Pirate dress', '/assets/images/Personalizzazioni/Equip/Pirate_suit_20260617_084927_03096f1a.png', 'Equipaggiamento', 1700, 0, 1, NULL, 1, 'The pirate dress allows you to increase your earnings by plundering passing ships and gives you the experience of life at sea', '_pirate.png', 9),
(213, 'c1111aad-e686-4189-8f48-72169b2f78e1', 'Advanced Mage Robes', '/assets/images/Personalizzazioni/Equip/mage_enchanted_robe_20260617_085032_06583954.png', 'Equipaggiamento', 700, 0, 1, NULL, 1, 'Enchanted robes that allow magical energy to increase when you level up', '_advanced_mage.png', 9),
(214, 'ec553e85-e378-45df-8cf3-ce9701e6695d', 'Dress up as a beginner wizard', '/assets/images/Personalizzazioni/Equip/vesti_mago_principiante_20260617_085129_8f39ca2f.png', 'Equipaggiamento', 400, 0, 1, NULL, 1, 'Robes worn by wizards at the beginning of their career: slightly increase magical power', '_apprentice_mage.png', 9),
(215, 'a937809a-8655-44fa-85db-eda80439cfd7', 'Robes of the Corrupted Wizard', '/assets/images/Personalizzazioni/Equip/Obscure_corrupted_mage_20260617_085233_dc41b50f.png', 'Equipaggiamento', 1700, 0, 1, NULL, 1, 'Robes worn by wizards who have turned to the dark side, corrupted by magical power and the lust for money, but with the same experience as good wizards', '_corrupted_mage.png', 9),
(216, '4aa0e77a-6a58-495b-b4d7-ec99bf5343c6', 'Robes of the Elemental Mage', '/assets/images/Personalizzazioni/Equip/mago_elementale_20260617_085324_915fb8e2.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Robes worn by wizards who master the elements: create magical energy and gold', '_elemental_mage.png', 9),
(217, '6598ad70-e6b4-46a1-b84d-91342337e66b', 'Robes of the Supreme Wizard', '/assets/images/Personalizzazioni/Equip/supreme_mage_robe_20260617_085412_65d865c6.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Robes worn by the Supreme Wizards who rule the Towers of High Magic, grant legendary-level magical power', '_supreme_mage.png', 9),
(219, '98a4541a-e0cb-44c5-a9f3-0d2d1ed37f6e', 'Aionios Aqua', '/assets/images/Personalizzazioni/Capelli/Aionios_aqua_20260614_132520_3cae6dd9.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(220, 'fd441f59-9486-4dca-93db-01a860465ef8', 'Aionios Light Blue', '/assets/images/Personalizzazioni/Capelli/Aionios_cyan_20260614_132554_9cc09b4c.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(221, '9712f71d-ca61-48a4-884f-1374e287022b', 'Aionios Blue', '/assets/images/Personalizzazioni/Capelli/Aionios_blue_20260614_132615_52674dc4.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(222, 'a5d59677-576c-4998-aae5-d0e655bb306a', 'Aionios Orange', '/assets/images/Personalizzazioni/Capelli/Aionios_orange_20260614_132635_fd7a69c8.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(223, '8c41d0f7-40c6-4833-a442-93a5de1bd929', 'Aionios Pink', '/assets/images/Personalizzazioni/Capelli/Aionios_pink_20260614_132654_769706d1.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(224, 'bf34600d-1c95-4d43-bbc1-ad66efd6b52a', 'Aionios Red', '/assets/images/Personalizzazioni/Capelli/Aionios_red_20260614_132718_b921ea68.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(225, 'ba7647f2-ab5f-48f0-a9b9-4ef844194b52', 'Aionios Green', '/assets/images/Personalizzazioni/Capelli/Aionios_green_20260614_132740_0aa92775.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(226, 'a15e7439-a929-44e5-ac64-4edf3064947b', 'Aionios Yellow', '/assets/images/Personalizzazioni/Capelli/Aionios_yellow_20260614_132805_70cda3a7.png', 'Capelli', 220, 16, 1, NULL, 1, '', '', NULL),
(227, '94081967-79b5-4cc7-932b-83997ae9ea1d', 'Aisha Blonde', '/assets/images/Personalizzazioni/Capelli/Aisha_blonde_20260615_075610_2c9cfa70.png', 'Capelli', 200, 17, 1, NULL, 1, '', '', NULL),
(228, '064729ff-3320-4cfc-abf8-e1b8305c317b', 'Aisha Blue', '/assets/images/Personalizzazioni/Capelli/Aisha_blue_20260615_075634_2ebc16e0.png', 'Capelli', 200, 17, 1, NULL, 1, '', '', NULL),
(229, '58b45ca3-6efc-44b6-bc3f-e160166a7f89', 'Aisha Green', '/assets/images/Personalizzazioni/Capelli/Aisha_green_20260615_075730_8c3e90c4.png', 'Capelli', 200, 17, 1, NULL, 1, '', '', NULL),
(230, 'f0aaae31-60ab-44e8-bee4-b4581c0edabc', 'Aisha Light Blue', '/assets/images/Personalizzazioni/Capelli/Aisha_cyan_20260615_075756_f1db3bc5.png', 'Capelli', 200, 17, 1, NULL, 1, '', '', NULL),
(231, 'e70d6c47-0dc9-4d71-9137-2cfd6179bdd6', 'Aisha Pink', '/assets/images/Personalizzazioni/Capelli/Aisha_pink_20260615_075819_aaa3fce8.png', 'Capelli', 200, 17, 1, NULL, 1, '', '', NULL),
(232, 'a6d8845d-9c7c-452e-ad96-cfd957d08769', 'Aisha Red', '/assets/images/Personalizzazioni/Capelli/Aisha_red_20260615_075840_d37fb4e0.png', 'Capelli', 200, 17, 1, NULL, 1, '', '', NULL),
(233, 'b5b2dcf5-d202-415d-8db3-47c4781d2a55', 'Aisha White', '/assets/images/Personalizzazioni/Capelli/Aisha_white_20260615_075903_7e2d4789.png', 'Capelli', 250, 17, 1, NULL, 1, '', '', NULL),
(234, 'd4ac2646-b88b-4471-acec-b4a14a214afb', 'Budi Black', '/assets/images/Personalizzazioni/Capelli/Budi_black_20260615_075930_918bae08.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(235, '481720d2-859c-4d58-9b17-c5b1bf0fe846', 'Budi Blonde', '/assets/images/Personalizzazioni/Capelli/Budi_blonde_20260615_075952_10284528.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(236, '17447997-5650-4c65-8251-15c26e3259f7', 'Budi Blue', '/assets/images/Personalizzazioni/Capelli/Budi_blu_20260615_080015_36c40a07.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(237, '239ab7c2-3f7c-4512-a41d-9d6bf9419af6', 'Budi Green', '/assets/images/Personalizzazioni/Capelli/Budi_green_20260615_080041_f3e1e342.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(238, '6b697672-2383-47e9-843d-aeef2d281b1a', 'Budi Light Blue', '/assets/images/Personalizzazioni/Capelli/Budi_cyan_20260615_080101_6712a330.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(239, '1224fa6e-e367-4b8b-956f-d2e4f40c6de9', 'Budi Pink', '/assets/images/Personalizzazioni/Capelli/Budi_pink_20260615_080124_fdae0ecc.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(240, '16938149-d4d1-4ac3-8c54-f651e3c2d597', 'Budi Red', '/assets/images/Personalizzazioni/Capelli/Budi_red_20260615_080145_62bb82be.png', 'Capelli', 200, 18, 1, NULL, 1, '', '', NULL),
(241, 'f8aa8bfa-b40b-4c5d-8064-4d9d81a33528', 'Budi White', '/assets/images/Personalizzazioni/Capelli/Budi_old_20260615_080207_28a9aa65.png', 'Capelli', 250, 18, 1, NULL, 1, '', '', NULL),
(242, 'cf9d42ca-904d-4fb7-bdf2-05c00c0b8b80', 'Camila Light Blue', '/assets/images/Personalizzazioni/Capelli/Camila_cyan_20260615_080231_359deff5.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(243, '3e6b9cda-387a-4854-a715-df43ea3e2a53', 'Camila Black', '/assets/images/Personalizzazioni/Capelli/Camila_black_20260615_080258_f189df26.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(244, 'b756aa35-31b9-44b2-a130-02c974ef97ad', 'Camila Blonde', '/assets/images/Personalizzazioni/Capelli/Camila_blonde_20260615_080317_5d3cbe76.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(245, '90a068ef-280d-41c3-ba90-dae0439ee23c', 'Camila Blue', '/assets/images/Personalizzazioni/Capelli/Camila_blue_20260615_080336_551b079e.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(246, '7a52851c-3d72-4628-9b9d-40c0c49396f2', 'Camila Green', '/assets/images/Personalizzazioni/Capelli/Camila_green_20260615_080400_838e1104.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(247, '9c7709f7-f4f2-4af1-b466-700e1903cb6c', 'Camila Orange', '/assets/images/Personalizzazioni/Capelli/Camila_orange_20260615_080423_110f790e.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(248, '85033e43-80d9-4903-aec0-7dac61d7c63f', 'Camila Pink', '/assets/images/Personalizzazioni/Capelli/Camila_pink_20260615_080446_0387f4d4.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(249, 'bf9eb1e2-d814-4e0e-b386-1e31d8c76110', 'Camila Red', '/assets/images/Personalizzazioni/Capelli/Camila_red_20260615_080512_28ce830f.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(250, '9150cbdb-2418-45ff-9d11-8f7d615d44ec', 'Camila White', '/assets/images/Personalizzazioni/Capelli/Camila_white_20260615_080533_f5d2be73.png', 'Capelli', 200, 19, 1, NULL, 1, '', '', NULL),
(251, '555f544d-e6ca-4b5d-92ba-430f0fb18445', 'Chijioke Black', '/assets/images/Personalizzazioni/Capelli/Chijioke-Black_20260615_080557_70d86eee.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(252, 'b38ba971-7885-4b3d-a4bf-f511e6e8440e', 'Chijioke Blonde', '/assets/images/Personalizzazioni/Capelli/Chijioke-Blonde_20260615_080618_c87f6da5.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(253, '2da0c098-dd52-41b1-8b68-5e3c4094acab', 'Chijioke Blue', '/assets/images/Personalizzazioni/Capelli/Chijioke-Blue_20260615_080643_1834aac1.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(254, 'de246832-60ba-450f-8f05-c8645d70f51d', 'Chijioke Collana', '/assets/images/Personalizzazioni/Capelli/Chijioke-Collana_20260615_080706_d55ac006.png', 'Capelli', 250, 20, 1, NULL, 1, '', '', NULL),
(255, '5aa7ceef-c4be-4f6f-a1c2-0d1a047c809e', 'Chijioke Green', '/assets/images/Personalizzazioni/Capelli/Chijioke-Green_20260615_080733_4d4f40e7.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(256, 'a5247bf4-96dc-4e23-9d2a-79e72b484d11', 'Chijioke Light Blue', '/assets/images/Personalizzazioni/Capelli/Chijioke-Cyan_20260615_080753_141cccc9.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(257, 'c5402281-509e-45ed-9e94-f6654ec80ccb', 'Chijioke Pink', '/assets/images/Personalizzazioni/Capelli/Chijioke-Pink_20260615_080816_f77da648.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(258, 'ad963230-5c70-4261-9bf8-78145e8637a8', 'Chijioke Red', '/assets/images/Personalizzazioni/Capelli/Chijioke-Red_20260615_080840_962729c9.png', 'Capelli', 200, 20, 1, NULL, 1, '', '', NULL),
(259, '9c73cca0-f74a-44b0-9d56-9dfb4415b9e8', 'Emily Light Blue', '/assets/images/Personalizzazioni/Capelli/Emily_cyan_20260615_080904_afdeb48c.png', 'Capelli', 200, 21, 1, NULL, 1, '', '', NULL),
(260, 'd9a2000a-a91a-4d89-8cb9-9a1b92fdd85e', 'Emily Blue', '/assets/images/Personalizzazioni/Capelli/Emily_blue_20260615_080929_e9a6c8fb.png', 'Capelli', 200, 21, 1, NULL, 1, '', '', NULL),
(261, '9e3ad3d6-7d93-446e-9406-126c52d5c83c', 'Emily Green', '/assets/images/Personalizzazioni/Capelli/Emily_green_20260615_080950_362313d5.png', 'Capelli', 200, 21, 1, NULL, 1, '', '', NULL),
(262, '29b134a8-15f2-487f-93ee-ebfbca0566b1', 'Emily Mesh Azzurre', '/assets/images/Personalizzazioni/Capelli/Emily_sfumata_giallo_cyan_20260615_081016_9b517157.png', 'Capelli', 300, 21, 1, NULL, 1, '', '', NULL),
(263, '7a3762e4-d6fd-4d0a-9b64-a5b7db26e09c', 'Emily Mesh RosseBlu', '/assets/images/Personalizzazioni/Capelli/Emily_sfumatura_rossoblu_20260615_081036_1175a367.png', 'Capelli', 300, 21, 1, NULL, 1, '', '', NULL),
(264, '0423181a-5a4c-4f18-a965-32f02d588dc8', 'Emily Orange', '/assets/images/Personalizzazioni/Capelli/Emily_orange_20260615_081058_418da9db.png', 'Capelli', 200, 21, 1, NULL, 1, '', '', NULL),
(265, '70d02c51-6fca-499e-afd6-a5ca37739a37', 'Emily Pink', '/assets/images/Personalizzazioni/Capelli/Emily_pink_20260615_081120_99909aa3.png', 'Capelli', 200, 21, 1, NULL, 1, '', '', NULL),
(266, '67421d0d-085f-4f07-bf98-eb81723ba895', 'Emily Red', '/assets/images/Personalizzazioni/Capelli/Emily_red_20260615_081142_303e0499.png', 'Capelli', 200, 21, 1, NULL, 1, '', '', NULL),
(267, '06aac316-67c5-4310-85e0-8882797318ff', 'Emily White', '/assets/images/Personalizzazioni/Capelli/Emily_white_20260615_081204_c03b34d9.png', 'Capelli', 220, 21, 1, NULL, 1, '', '', NULL),
(268, 'ecbc8a5f-df52-4f0f-9de4-a657a6ced7eb', 'Isabela Light Blue', '/assets/images/Personalizzazioni/Capelli/Isabela_cyan_20260615_081230_f508ceb3.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(269, 'b900ec9b-7289-4445-9f7a-0efbecac1574', 'Isabela Black', '/assets/images/Personalizzazioni/Capelli/Isabela_black_20260615_081256_a99e86fd.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(270, '80473318-7eb8-4cbd-a43d-6ad7c8e3de13', 'Isabela Blonde', '/assets/images/Personalizzazioni/Capelli/Isabela_blonde_20260615_081320_fb1c6ecf.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(271, 'ab09f97d-c4ba-48b9-b1da-9fd7b1633c7b', 'Isabela Blue', '/assets/images/Personalizzazioni/Capelli/Isabela_blue_20260615_081341_f6a82eec.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(272, '24145b58-50e1-42ae-b2f1-9020f9c072e3', 'Isabela Green', '/assets/images/Personalizzazioni/Capelli/Isabela_green_20260615_081408_0f5ad085.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(273, '5ac80be5-b519-4a4f-9778-b9701ae03523', 'Isabela Old', '/assets/images/Personalizzazioni/Capelli/Isabela_old_20260615_081436_82d530b3.png', 'Capelli', 250, 22, 1, NULL, 1, '', '', NULL),
(274, 'd6231c6c-6d5e-4629-bd4d-1491bbda2b6a', 'Isabela Orange', '/assets/images/Personalizzazioni/Capelli/Isabela_orange_20260615_081457_882b5da1.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(275, '30f37c9b-a492-42d4-8cb4-d1a0ae0006f0', 'Isabela Red', '/assets/images/Personalizzazioni/Capelli/Isabela_red_20260615_081519_43b7e995.png', 'Capelli', 200, 22, 1, NULL, 1, '', '', NULL),
(277, '032ffc14-c71d-44bc-97fa-9d8b879bbe09', 'Jambalaya Aqua', '/assets/images/Personalizzazioni/Capelli/Jambalaya_aqua_20260615_081545_b639ed91.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(278, '655cb61f-1960-4674-9543-bf2d4cc68740', 'Jambalaya Blonde', '/assets/images/Personalizzazioni/Capelli/Jambalaya_biondo_20260615_081605_f998afaf.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(279, '2c8bc0f3-0dac-466a-aa2b-8058ea756e97', 'Jambalaya Blue', '/assets/images/Personalizzazioni/Capelli/Jambalaya_blue_20260615_081627_ed510b32.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(280, '4ef3fa8d-01c6-441f-ae6d-a3e70b8661f6', 'Jambalaya Green', '/assets/images/Personalizzazioni/Capelli/Jambalaya_verde_20260615_081657_44516902.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(281, '1fa1544d-610a-4014-a162-1a09f6f5e395', 'Jambalaya Light Blue', '/assets/images/Personalizzazioni/Capelli/Jambalaya_azzurro_20260615_081722_b5f41519.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(282, '887d1fbd-271e-44e0-a129-26c85fe32afc', 'Jambalaya Pink', '/assets/images/Personalizzazioni/Capelli/Jambalaya_rosa_20260615_081744_74773a4c.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(283, 'b6faddc3-80ef-43c4-accf-53f91a006208', 'Jambalaya Red', '/assets/images/Personalizzazioni/Capelli/Jambalaya_red_20260615_081814_2cc633cc.png', 'Capelli', 200, 24, 1, NULL, 1, '', '', NULL),
(284, 'c69db30e-f279-493b-afc2-78c3f741ad7c', 'Klaus light blue', '/assets/images/Personalizzazioni/Capelli/Klaus_cyan_20260615_081840_7e6b2bd5.png', 'Capelli', 200, 25, 1, NULL, 1, '', '', NULL),
(285, 'dbcb3c40-8912-426b-acdc-c40c7091f6d6', 'Klaus Blue', '/assets/images/Personalizzazioni/Capelli/Klaus_blue_20260615_081903_e40c57ba.png', 'Capelli', 200, 25, 1, NULL, 1, '', '', NULL),
(286, '61f89bd1-f6bb-4625-9374-4a57917a3459', 'Klaus Green', '/assets/images/Personalizzazioni/Capelli/Klaus_green_20260615_081922_de6d10c6.png', 'Capelli', 200, 25, 1, NULL, 1, '', '', NULL),
(287, 'bbe7be06-e904-47d4-878b-3fa9a2b29a92', 'Klaus Old', '/assets/images/Personalizzazioni/Capelli/Klaus_old_20260615_081949_4e203e6c.png', 'Capelli', 220, 25, 1, NULL, 1, '', '', NULL),
(288, 'ead08594-2a33-4e13-a663-cc5a34ec7323', 'Klaus Orange', '/assets/images/Personalizzazioni/Capelli/Klaus_orange_20260615_082009_e3354921.png', 'Capelli', 200, 25, 1, NULL, 1, '', '', NULL),
(289, 'b8886a06-e151-4828-8c3a-44c0e0af90e0', 'Klaus Pink', '/assets/images/Personalizzazioni/Capelli/Klaus_pink_20260615_082029_3c73f4cf.png', 'Capelli', 200, 25, 1, NULL, 1, '', '', NULL),
(290, '1a736163-5507-4417-bc74-78a55a349ac4', 'Klaus Red', '/assets/images/Personalizzazioni/Capelli/Klaus_red_20260615_082055_536d6767.png', 'Capelli', 200, 25, 1, NULL, 1, '', '', NULL),
(291, '6ab90934-bcd3-4691-8cd6-5cc361d59fc0', 'Klaus Mesh Verdi', '/assets/images/Personalizzazioni/Capelli/Klaus_bluverde_20260615_082122_062fa376.png', 'Capelli', 300, 25, 1, NULL, 1, '', '', NULL),
(292, '20e7561c-6a2f-4676-81dd-fa5390dd5eca', 'Lachlan Light Blue', '/assets/images/Personalizzazioni/Capelli/Lachlan-cyan_20260615_082152_2c2541e6.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(293, '08dbbdc6-7786-47d5-a914-4433c20488dc', 'Lachlan Blonde', '/assets/images/Personalizzazioni/Capelli/Lachlan-yellow_20260615_082215_fde99462.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(294, 'ba7ccac4-05a3-429e-9509-188e587b1088', 'Lachlan Blue', '/assets/images/Personalizzazioni/Capelli/Lachlan-blue_20260615_082236_092d395b.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(295, '6d97cecc-9270-4cc1-98a1-d5bb9a54a9af', 'Lachlan Blue Mesh', '/assets/images/Personalizzazioni/Capelli/Lachlan-blumesh_20260615_082257_c5ede6a3.png', 'Capelli', 250, 26, 1, NULL, 1, '', '', NULL),
(296, '775b4712-91c0-4098-9a9e-15d06bb438d8', 'Lachlan Brizzolato', '/assets/images/Personalizzazioni/Capelli/Lachlan-brizzolato_20260615_082317_195473d8.png', 'Capelli', 250, 26, 1, NULL, 1, '', '', NULL),
(297, '2f949c57-3b97-4e6c-8cde-1bf78de3fe3e', 'Lachlan Green', '/assets/images/Personalizzazioni/Capelli/Lachlan-green_20260615_082339_d9df8a06.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(298, 'db1ce95b-361d-4de8-9142-08969fe3f9d8', 'Lachlan Orange', '/assets/images/Personalizzazioni/Capelli/Lachlan-orange_20260615_082359_fe6d9479.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(299, 'be62cb2a-7f32-4c75-8896-3f50fdf7b848', 'Lachlan Pink', '/assets/images/Personalizzazioni/Capelli/Lachlan-pink_20260615_082419_bb15ecbf.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(300, 'd50520d9-3390-43ae-a366-7dde586fd2b2', 'Lachlan Red', '/assets/images/Personalizzazioni/Capelli/Lachlan_red_20260615_082445_9762ccee.png', 'Capelli', 200, 26, 1, NULL, 1, '', '', NULL),
(301, '839ace28-4aae-406c-90cc-0b873935d0f9', 'Nikolaj Light Blue', '/assets/images/Personalizzazioni/Capelli/Nikolaj-intero_cyan_20260615_082515_635dc54c.png', 'Capelli', 250, 27, 1, NULL, 1, '', '', NULL),
(302, '1bdafcf6-a0bc-4d9a-bd55-d135e17435f6', 'Nikolaj Biondo', '/assets/images/Personalizzazioni/Capelli/Nikolaj-blonde_20260615_082541_46012601.png', 'Capelli', 200, 27, 1, NULL, 1, '', '', NULL),
(303, 'd2cc81ad-8f5d-4945-9f54-75b5a233d5a3', 'Nikolaj Black', '/assets/images/Personalizzazioni/Capelli/Nikolaj-Black_20260615_082600_73f65b23.png', 'Capelli', 200, 27, 1, NULL, 1, '', '', NULL),
(304, 'e80e5b59-2ccb-4991-9024-8fff49a968c3', 'Nikolaj Blue', '/assets/images/Personalizzazioni/Capelli/Nikolaj-Blue_20260615_082625_98a4ff34.png', 'Capelli', 200, 27, 1, NULL, 1, '', '', NULL),
(305, 'f2d5983c-80fe-4fb0-9309-e25f614d82f6', 'Nikolaj Green', '/assets/images/Personalizzazioni/Capelli/Nikolaj-green_20260615_082648_72f09df9.png', 'Capelli', 200, 27, 1, NULL, 1, '', '', NULL),
(306, '069e03fb-eed8-43a7-820b-24dfe8a12ba6', 'Nikolaj Violet', '/assets/images/Personalizzazioni/Capelli/Nikolaj-violet_20260615_082708_d01aa2cc.png', 'Capelli', 200, 27, 1, NULL, 1, '', '', NULL),
(307, 'd4794863-4f80-4b9f-a542-01682472434b', 'Nikolaj White', '/assets/images/Personalizzazioni/Capelli/Nikolaj-white_20260615_082726_76e952eb.png', 'Capelli', 200, 27, 1, NULL, 1, '', '', NULL),
(308, 'b29b07c4-af75-493f-8b06-edf7d8368c71', 'Nuliajuk Light Blue', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_cyan_20260615_082749_05e17525.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(309, '69f349a0-0c62-4b24-a9a5-be9793bf6db7', 'Nuliajuk Black', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_black_20260615_082809_a434cc0d.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(310, '972f8827-8c13-4af3-b190-12fb583d41c5', 'Nuliajuk Blonde', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_blonde_20260615_082838_3bd02033.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(311, '88f1af1c-7707-49f4-b9a4-92605b3783bd', 'Nuliajuk Blue', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_blue_20260615_082858_8f182911.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(312, 'c99be5e6-b413-4ae5-b84e-b7342714e3a6', 'Nuliajuk Green', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_green_20260615_082920_4790eca7.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(313, '287908dd-09cf-48a5-9acf-c0195dbdd880', 'Nuliajuk Orange', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_orange_20260615_082939_817062b9.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(314, '917ea3d1-5db5-4311-b829-496dd29543c4', 'Nuliajuk Pink', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_pink_20260615_083000_9d37ca52.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(315, '9457f241-38b2-48ee-927e-c93f752e1f83', 'Nuliajuk Red', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_red_20260615_083022_6ca54c85.png', 'Capelli', 200, 28, 1, NULL, 1, '', '', NULL),
(316, 'e8add188-4830-44ae-9412-2028e3ae5e72', 'Shinzo Blonde', '/assets/images/Personalizzazioni/Capelli/Shinzo_biondo_20260615_083048_2f83b1ad.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(317, '9b1ca4de-507d-46ce-834a-1d7cc611bdb2', 'Shinzo Green', '/assets/images/Personalizzazioni/Capelli/Shinzo_green_20260615_083108_5f5fb8c0.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(318, '1f1825c4-a71a-47fb-92f3-a65709dac491', 'Shinzo Light Blue', '/assets/images/Personalizzazioni/Capelli/Shinzo_azzurro_20260615_083130_d536ca21.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(319, '2f938f97-7200-4452-ae92-b4804ce494bb', 'Shinzo Orange', '/assets/images/Personalizzazioni/Capelli/Shinzo_orange_20260615_083149_98022af0.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(320, '64d954b5-f10a-43c4-94d0-e5bde2be4ab9', 'Shinzo Pink', '/assets/images/Personalizzazioni/Capelli/Shinzo_pink_20260615_083216_57f3ecc0.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(321, '1a0410b3-7468-4cef-8774-c7c54ebad63c', 'Shinzo Red', '/assets/images/Personalizzazioni/Capelli/Shinzo_Red_20260615_083235_23793a6f.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(322, '51dad053-3483-4452-ab40-3457c0a4f4dc', 'Shinzo Violet', '/assets/images/Personalizzazioni/Capelli/Shinzo_viola_20260615_083252_fd05c774.png', 'Capelli', 200, 29, 1, NULL, 1, '', '', NULL),
(323, 'e9e526a3-bc5f-4b2e-992b-ec55eab7feeb', 'Shinzo White', '/assets/images/Personalizzazioni/Capelli/Shinzo_white_20260615_083310_45d78f28.png', 'Capelli', 250, 29, 1, NULL, 1, '', '', NULL),
(324, '070ae40e-7039-4034-ae5e-8494d2cc04a9', 'Strogar Blue', '/assets/images/Personalizzazioni/Capelli/Strogar-Blu_20260615_083336_2b98d481.png', 'Capelli', 200, 30, 1, NULL, 1, '', '', NULL),
(325, '2a138819-b582-49c7-a497-3096c36e47ff', 'Strogar Green', '/assets/images/Personalizzazioni/Capelli/Strogar-Green_20260615_083358_3495cbfe.png', 'Capelli', 200, 30, 1, NULL, 1, '', '', NULL),
(326, '07ab1f77-a5ee-416b-b1d5-a5caab4ddb36', 'Strogar Light Blue', '/assets/images/Personalizzazioni/Capelli/Strogar-Azzurro_20260615_083414_902a497c.png', 'Capelli', 200, 30, 1, NULL, 1, '', '', NULL),
(327, 'ad8f2b60-2280-4625-b361-34a819e1a7a4', 'Strogar Pink', '/assets/images/Personalizzazioni/Capelli/Strogar-Pink_20260615_083432_46b88e9a.png', 'Capelli', 200, 30, 1, NULL, 1, '', '', NULL),
(328, '1df844ff-38e3-4f35-a1af-7ed7dffaa2d8', 'Strogar Red', '/assets/images/Personalizzazioni/Capelli/Strogar-Red_20260615_083451_1ec9e522.png', 'Capelli', 200, 30, 1, NULL, 1, '', '', NULL),
(329, '9035f6f2-85c5-451e-ac98-7b4e33321772', 'Strogar Yellow', '/assets/images/Personalizzazioni/Capelli/Strogar-Yellow_20260615_083511_1a4bd897.png', 'Capelli', 200, 30, 1, NULL, 1, '', '', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_plugin`
--

CREATE TABLE `ct_plugin` (
  `id_plugin` int NOT NULL,
  `nome_plugin` varchar(255) NOT NULL,
  `versione` varchar(10) NOT NULL,
  `attivo` int NOT NULL,
  `codice_plugin` varchar(255) NOT NULL,
  `descrizione` text,
  `configurazione_json` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_plugin_classe`
--

CREATE TABLE `ct_plugin_classe` (
  `id_plugin_classe` int NOT NULL,
  `fk_plugin` int NOT NULL,
  `fk_classe` int NOT NULL,
  `attivo` int NOT NULL DEFAULT '0',
  `configurazione_json` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_poteri`
--

CREATE TABLE `ct_poteri` (
  `id_potere` int NOT NULL,
  `nome_potere` varchar(100) NOT NULL,
  `descrizione_potere` text NOT NULL,
  `img_potere` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `livello` int NOT NULL,
  `mana_necessario` int NOT NULL,
  `fk_classe` int NOT NULL,
  `fisso` int NOT NULL DEFAULT '0',
  `originale` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_poteri`
--

INSERT INTO `ct_poteri` (`id_potere`, `nome_potere`, `descrizione_potere`, `img_potere`, `livello`, `mana_necessario`, `fk_classe`, `fisso`, `originale`) VALUES
(5, 'Dona la vita', 'Ricarica 1 cuore a te stesso o ad un compagno di classe (Ricarica anche gli scudi)\r\n', '/assets/images/Poteri/677fcba8951ec_dona_1_vita.jpg', 5, 3, 1, 1, 0),
(6, 'Estremo sacrificio', 'Ricarica 2 cuori ad ognuno dei tuoi compagni di classe. Tu perdi 2 cuori\r\n', '/assets/images/Poteri/677fcbf2b53f6_dona_vita_classe.jpg', 10, 4, 1, 1, 0),
(7, 'Ricarica del mana', 'Ricarichi completamente il mana ad un compagno di classe\r\n', '/assets/images/Poteri/677fcc73775c5_ricarica_1_mana.jpg', 5, 5, 1, 1, 0),
(8, 'Tempesta magica', 'Ricarichi 2 mana ad ogni compagno di classe\r\n', '/assets/images/Poteri/677fcc98b5362_ricarica_classe_mana.jpg', 10, 4, 1, 1, 0),
(30, 'Aumenta la vita', 'Aumenta le tue vite massime di 1 permanentemente. Usando il potere, questo viene dimenticato e va scelto nuovamente alla prossima scelta dei poteri.\r\n', '/assets/images/Poteri/683c545027189_aumenta_vita.jpg', 16, 5, 1, 1, 0),
(31, 'Aumenta il mana', 'Aumenta permanentemente di 1 il mana massimo. Il potere viene dimenticato dopo l\'utilizzo e va scelto nuovamente per poter essere usato\r\n', '/assets/images/Poteri/683c54cf4e318_aumenta_mana.jpg', 16, 4, 1, 1, 0),
(33, 'Ivory Bow', 'You can ask for help with one multiple-choice answer during a class test: only 2 answers will remain.', '/assets/images/Poteri/power_6a3291244c1912.38148098_arcoav.jpg', 6, 2, 1, 0, 0),
(34, 'Earthsplitter Axe', 'The Earthsplitter Axe allows you to destroy one question in a written test (theoretical) or oral exam, replacing it with one of your choice.', '/assets/images/Poteri/power_6a32914c74ce23.31768365_ascia_spaccaterra.jpg', 15, 3, 1, 0, 0),
(35, 'Arcane Barrier', 'The arcane barrier protects you from losing a heart if you have not completed an exercise assigned as homework.', '/assets/images/Poteri/power_6a3291b1a3db40.21941966_barriera_arcana_fin.jpg', 4, 3, 1, 0, 0),
(36, 'Change Desk Mate', 'For two weeks, the student may change desk during the lessons of the subject for which they use the power, swapping places with a classmate.', '/assets/images/Poteri/power_6a3291cc3ed940.32237527_cambio_banco.jpg', 14, 4, 1, 0, 0),
(37, 'Circle of Fire', 'Gain +0.5 points on an oral exam or written test of your choice.', '/assets/images/Poteri/power_6a3291e9ad79e5.92163100_circolo_fuoco.jpg', 21, 5, 1, 0, 0),
(38, 'The Great Leap', 'Thanks to your agility, you can use a justification to skip an oral exam.', '/assets/images/Poteri/power_6a32920469ccb8.05378184_grande_salto.jpg', 10, 3, 1, 0, 0),
(39, 'Spear of Destiny', 'The Spear of Destiny allows you to predict the future: you may view one question from the next theoretical test (the power cannot be used if another student has already used the same power).', '/assets/images/Poteri/power_6a329223d4c475.54048185_lancia_destino.jpg', 18, 5, 1, 0, 0),
(40, 'Hammer of Eden', 'The Hammer of Eden has the power to immobilize people. You can try to immobilize a test by asking the class to vote on moving its date (the test can only be moved once - teachers also vote).', '/assets/images/Poteri/power_6a3292603b6049.42562337_martelloeden.jpg', 21, 6, 1, 0, 0),
(41, 'Refreshment', 'You can eat a quick snack during the lesson in class.', '/assets/images/Poteri/power_6a3292c0c473e9.21764338_rifocillarsi2.jpg', 3, 1, 1, 0, 0),
(42, 'Essential Wisdom', 'You can bring a mind map (no summaries) and use it during a class test.', '/assets/images/Poteri/power_6a3292e974ba01.45985006_mago_libro.jpg', 12, 4, 1, 0, 0),
(43, 'Golden Shield', 'The Golden Shield protects and heals. You can use its power to ask for a recovery oral exam after a test that went badly. The test grade will not count toward the average.', '/assets/images/Poteri/power_6a3293014212b1.35800245_scudo_dorato.jpg', 22, 4, 1, 0, 0),
(44, 'Fiery Orb', 'Gain +0.25 points on a written test or oral exam of your choice.', '/assets/images/Poteri/power_6a32931b403a37.37656901_mago_sfera_fuoco.jpg', 16, 4, 1, 0, 0),
(45, 'Flaming Sword', 'The flaming sword allows you to dispel the shadows: during a practical test, you may ask the teacher to clarify an exercise better, making it easier to complete.', '/assets/images/Poteri/power_6a329334d1b817.67121807_spada_fiammeggiante.jpg', 13, 3, 1, 0, 0),
(46, 'Amplified Hearing', 'By choosing a classmate in advance, you may receive a hint from the chosen classmate during an oral exam.', '/assets/images/Poteri/power_6a329356d31397.65903013_orecchio.jpg', 8, 3, 1, 0, 0),
(47, 'Exception of the Ages', 'Allows the student to ignore one multiple-choice question or one small technical requirement in a practical test (to be agreed upon during the test) while still receiving full points.', '/assets/images/Poteri/power_6a3294bbdd4ad3.30096413_eccezione_ere.png', 20, 5, 1, 0, 0),
(48, 'Library of the Ages', 'During a test or in-class exercise, the student may activate the power for 10 minutes, gaining permission to consult an external resource (official documentation, a PDF manual, or a specific website approved by the teacher - so no ChatGPT).', '/assets/images/Poteri/power_6a3296b164e9f2.42731753_Biblioteca_ere.png', 25, 7, 1, 0, 0),
(49, 'Fracture of Destiny', 'A failure is suspended in the flow of time. To erase it, the hero must face an extraordinary challenge from an alternate timeline.\r\nThe teacher will assign the student a single \"cursed\" logic/coding exercise (harder than usual). If the exercise is completed correctly, obviously without ChatGPT, the original failure disappears and is replaced by a minimum grade of 7. If the challenge fails, the student instantly loses 2 Hearts and the original grade is confirmed.', '/assets/images/Poteri/power_6a32974e104aa8.58839622_frattura_destino.png', 27, 6, 1, 0, 0),
(50, 'Convergence of Heroes', 'Team power (it must be activated by the whole team): if the average grade of the teammates on the test is 7 or higher, all team members receive a bonus of +2.5 points on the total test score (so not directly on the grade; it depends on the total number of points). If the average is below 7, the power fails and all participants lose 1 Heart in addition to the mana used.', '/assets/images/Poteri/power_6a32984594d1f8.92231805_Convergenza_eroi.png', 22, 4, 1, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_punizioni`
--

CREATE TABLE `ct_punizioni` (
  `id_punizione` int NOT NULL,
  `giorni_per_consegna` int NOT NULL,
  `descrizione_punizione` text NOT NULL,
  `img_punizione` varchar(200) NOT NULL,
  `fk_classe` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_punizioni`
--

INSERT INTO `ct_punizioni` (`id_punizione`, `giorni_per_consegna`, `descrizione_punizione`, `img_punizione`, `fk_classe`) VALUES
(16, 7, 'Create a PowerPoint presentation on the following topic: why it is important not to create confusion in class and to stay attentive during lessons\r\n', '/assets/images/Punizioni/6779374a01761_gabbia_deserto.jpg', 1),
(17, 7, 'Create a PowerPoint presentation on the following topic: why it is important to complete subject exercises independently (in the lab or at home), without having ChatGPT do them or copying them from a classmate\r\n', '/assets/images/Punizioni/677953f1448dd_carcerata.jpg', 1),
(18, 10, 'Research online the theme and plot of Golding\'s book Lord of the Flies. Create a presentation that summarizes the plot and theme of the book and try to make a connection between the book\'s theme and why teachers need to assign punishments such as notes and suspensions to students. What could happen in a class if there were no teacher supervision?', '/assets/images//Punizioni/6800b9349ada2_lordflies.jpg', 1),
(19, 7, 'Write a page to read in class with your personal reflections on the topic: How the improper use of artificial intelligence can hinder my learning and autonomy.\r\n', '/assets/images/Punizioni/683aee9672886_uso_scorretto_ia.jpg', 1),
(20, 10, 'Record a video of at least 2 minutes in which you explain: Why the value of honesty is important. Imagine one world where all people are honest and a second world where all people are dishonest. Which one would you prefer to live in?\r\n', '/assets/images/Punizioni/683aef72c4aae_mondo_disonesto.jpg', 1),
(21, 14, 'Create a comic (by hand or with online software) that tells: The adventures of a student who always copies and where they end up.\r\nThe comic should therefore make people reflect on why it is wrong to copy both exercises and tests\r\n', '/assets/images/Punizioni/683af048582b2_studente_sbarre.jpg', 1),
(22, 7, 'Create a flyer titled: Smartphone VS attention: who really wins?\r\nCompare wasted time, loss of concentration, and tips for using the phone intelligently.\r\n', '/assets/images/Punizioni/683af0d7bfb94_cellulare.jpg', 1),
(23, 7, 'Write a letter to read in class to your future self: Letter from a brain asleep in class: chronicle of a missed opportunity\r\n', '/assets/images/Punizioni/683af1b1f391e_dorme.jpg', 1),
(24, 7, 'From passivity to participation: my plan to be more present and active. Create a mind map as a roadmap to become more attentive and participatory during lessons.\r\n', '/assets/images/Punizioni/683af25e67cca_strada.jpg', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_quest`
--

CREATE TABLE `ct_quest` (
  `id_quest` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `nome_quest` text NOT NULL,
  `image_quest` varchar(200) NOT NULL,
  `piantina_quest` varchar(200) NOT NULL,
  `originale` int NOT NULL DEFAULT '0',
  `blocca_ese` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_quest`
--

INSERT INTO `ct_quest` (`id_quest`, `uuid`, `nome_quest`, `image_quest`, `piantina_quest`, `originale`, `blocca_ese`) VALUES
(5, 'a4cee784-c7d5-4558-ba91-1f4dcf3a6373', '14 Heroes (Structured Python)', '/assets/images/Quest/imported_6a4500b2cab347.15192540_quest_6a32b3c0b33f50.64012251.jpg', '/assets/images/Quest/imported_6a4500b2cee979.75316468_piantina_6a32b3c0b7b7d1.73995565.png', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_quiz`
--

CREATE TABLE `ct_quiz` (
  `id_quiz` int NOT NULL,
  `nome_quiz` varchar(200) NOT NULL,
  `fk_utente` int NOT NULL,
  `data_creazione` date NOT NULL,
  `casuale` int NOT NULL,
  `mix_answer` int NOT NULL,
  `fk_materia` int NOT NULL,
  `mostra_punti_dom` int NOT NULL DEFAULT '1',
  `fk_griglia` int NOT NULL DEFAULT '0',
  `mix_questions` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_quiz_argomenti`
--

CREATE TABLE `ct_quiz_argomenti` (
  `id_quiz_argomento` int NOT NULL,
  `fk_quiz` int NOT NULL,
  `fk_argomento` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_quiz_domande`
--

CREATE TABLE `ct_quiz_domande` (
  `id_quiz_domanda` int NOT NULL,
  `fk_quiz` int NOT NULL,
  `fk_domanda` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_quiz_tipo_domande`
--

CREATE TABLE `ct_quiz_tipo_domande` (
  `id_quiz_tipo` int NOT NULL,
  `fk_tipo_domande` int NOT NULL,
  `num_domande` int NOT NULL,
  `fk_quiz` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_risposte`
--

CREATE TABLE `ct_risposte` (
  `id_risposta` int NOT NULL,
  `risposta` text NOT NULL,
  `corretta` int NOT NULL,
  `fk_domanda` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_risposte`
--

INSERT INTO `ct_risposte` (`id_risposta`, `risposta`, `corretta`, `fk_domanda`) VALUES
(1, 'A type of programming in which the program is divided into functions and modules that can be reused in other programs', 1, 1),
(2, 'A type of programming where classes representing real objects are included', 0, 1),
(3, 'A type of programming where everything is a function: for example, recursive functions must be used to create loops', 0, 1),
(4, 'A type of programming where only logical functions are used to obtain logical results from predicates', 0, 1),
(5, 'Code reusability', 1, 2),
(6, 'Code portability across other operating systems', 0, 2),
(7, 'Ease of maintenance', 1, 2),
(8, 'Faster instruction execution', 0, 2),
(9, 'Collaborative development by multiple programmers', 1, 2),
(10, 'Fewer bugs in the code', 1, 2),
(11, 'An instance of a class is the definition of the class', 0, 4),
(12, 'An instance of a class is an object of the class', 1, 4),
(13, 'An instance of a class is an abstraction that includes attributes and methods', 0, 4),
(14, 'An instance of a class is the main function of a program', 0, 4),
(15, 'Using a UML class diagram', 1, 5),
(16, 'Using an RST flowchart', 0, 5),
(17, 'Using a UML component diagram', 0, 5),
(18, 'Using an RST hierarchy diagram', 0, 5),
(19, 'When you want to create a new object of that class', 1, 6),
(20, 'When the methods of the class must be defined', 0, 6),
(21, 'When you want to call the main function of the program', 0, 6),
(22, 'When an object must be deleted from memory', 0, 6),
(23, 'The technique by which attributes and methods are enclosed inside the class', 1, 7),
(24, 'The technique that lets me define a class inside a module', 0, 7),
(25, 'The fact that I can call a method on an object', 0, 7),
(26, 'The possibility of reusing classes across multiple programs', 0, 7),
(27, 'class Car(object):', 1, 8),
(28, 'class Car:', 0, 8),
(29, 'object Car(class):', 0, 8),
(30, 'object Car():', 0, 8),
(31, 'def methodName(self, par_a, par_b):', 1, 9),
(32, 'class.methodName(self, x, y):', 0, 9),
(33, 'def methodName(par_a, par_b):', 0, 9),
(34, 'function methodName(self,x,y):', 0, 9),
(35, 'obj.print()', 1, 10),
(36, 'from obj import print()', 0, 10),
(37, 'obj-&gt;print()', 0, 10),
(38, 'print(obj)', 0, 10),
(39, 't1 = new Triangle()', 0, 11),
(40, 't1 = Triangle(5,3)', 1, 11),
(41, 'Triangle(base=5, height=3) = t1', 0, 11),
(42, 't1 = class Triangle(self, 5, 3)', 0, 11),
(43, 'def __init__(self, a):', 1, 12),
(44, 'def init(self, x):', 0, 12),
(45, 'def __const__(self,a):', 0, 12),
(46, 'def __init(self):', 0, 12),
(47, '__color = &quot; &quot;', 1, 13),
(48, 'color__ = &quot; &quot;', 0, 13),
(49, 'private color = &quot; &quot;', 0, 13),
(50, 'def color(self, private):', 0, 13),
(51, 'r1.__base', 0, 14),
(52, 'It is not possible to directly access the private attributes of a class from an external function', 1, 14),
(53, 'r1.base', 0, 14),
(54, 'get(r1,__base)', 0, 14),
(55, 'Base class', 0, 15),
(56, 'Derived class', 1, 15),
(57, 'Superclass', 0, 15),
(58, 'Multiple class', 0, 15),
(59, 'class Circle inherit from GeometricShape:', 1, 16),
(60, 'from GeometricShape import Circle', 0, 16),
(61, 'class Circle(GeometricShape):', 0, 16),
(62, 'class Circle(object) -&gt; GeometricShape:', 0, 16),
(63, 'super()', 1, 17),
(64, 'greater()', 0, 17),
(65, 'import()', 0, 17),
(66, 'class()', 0, 17),
(67, 'It means that a class derives from a single superclass', 1, 18),
(68, 'It means that multiple subclasses derive from a single superclass', 0, 18),
(69, 'It means that a class derives from multiple superclasses', 0, 18),
(70, 'It means that a class does not derive from any other class', 0, 18),
(71, 'It is a synonym for object-oriented programming', 0, 19),
(72, 'The ability of a method to take multiple forms', 1, 19),
(73, 'The ability of an attribute to be public or private', 0, 19),
(74, 'The fact that multiple methods can be called on the same object', 0, 19),
(75, 'A method of a superclass is redefined in a subclass, changing its code', 1, 20),
(76, 'There are multiple methods in a class with the same name but different parameters', 0, 20),
(77, 'There are methods inherited by default from the object class that we can use in subclasses', 0, 20),
(78, 'A class can inherit attributes and methods of a superclass without having to rewrite them', 0, 20),
(79, '__init__', 1, 21),
(80, '__str__', 0, 21),
(81, '__add__', 0, 21),
(82, '__len__', 0, 21),
(83, 'Method', 1, 22),
(84, 'Class function', 0, 22),
(85, 'Operation', 0, 22),
(86, 'Factory', 0, 22),
(87, 'Multiple inheritance', 1, 24),
(88, 'Multilevel inheritance', 0, 24),
(89, 'Hierarchical inheritance', 0, 24),
(90, 'Single-level inheritance', 0, 24),
(91, 'Object', 1, 25),
(92, 'Attribute', 0, 25),
(93, 'Method', 0, 25),
(94, 'OOP', 0, 25),
(95, 'shadow = Dog(&quot;Shadow&quot;)', 0, 26),
(96, 'dog = Dog(&quot;Shadow&quot;)', 0, 26),
(97, 'rex = Dog()', 0, 26),
(98, 'stella = new Dog(&quot;Stella&quot;)', 1, 26),
(99, 'One advantage of OOP is that it can hide the complexity of an object implementation through encapsulation', 0, 27),
(100, 'A major opportunity provided by OOP is the ability to build a class from a base class, extending it with new functionality', 0, 27),
(101, 'A class contains functions called methods and variables called attributes, which are used by the methods', 0, 27),
(102, 'An advantage of OOP is that it makes programs portable across different operating systems', 1, 27),
(103, 'class', 1, 28),
(104, 'instance', 0, 28),
(105, 'object', 0, 28),
(106, 'import', 0, 28),
(107, 'class', 0, 29),
(108, 'def', 0, 29),
(109, 'self', 1, 29),
(110, 'init', 0, 29),
(111, 'True', 0, 30),
(112, 'False', 1, 30),
(113, 'def __init__(title, author):', 0, 31),
(114, 'def __init__(self, title, author):', 1, 31),
(115, 'def __init__():', 0, 31),
(116, '__init__(self, title, author):', 0, 31),
(117, 'x contains an integer value', 0, 32),
(118, 'x contains a reference to an object of the Circle class', 1, 32),
(119, 'x contains the value of the area of a circle', 0, 32),
(120, 'the Circle class contains an attribute named x', 0, 32),
(121, '__str__', 0, 33),
(122, '__init__', 0, 33),
(123, '__add__', 1, 33),
(124, '__many__', 0, 33),
(125, 'class', 0, 43),
(126, 'def', 0, 43),
(127, 'self', 1, 43),
(128, 'init', 0, 43),
(129, 'It defines attributes and methods of a class', 0, 44),
(130, 'It is called when a new object is instantiated', 1, 44),
(131, 'It always initializes all attributes to 0 when called', 0, 44),
(132, 'None of the other answers is correct', 0, 44),
(133, 'obj.print()', 1, 45),
(134, 'from obj import print()', 0, 45),
(135, 'obj-&gt;print()', 0, 45),
(136, 'print(obj)', 0, 45),
(137, 'def __init__(self, a):', 1, 46),
(138, 'def init(self, x):', 0, 46),
(139, 'def __const__(self,a):', 0, 46),
(140, 'def __init(self):', 0, 46),
(141, 'An Object', 1, 47),
(142, 'An Attribute', 0, 47),
(143, 'A Method', 0, 47),
(144, 'An OOP', 0, 47),
(145, 'dog = Dog(&quot;Shadow&quot;)', 0, 48),
(146, 'rex = Dog()', 0, 48),
(147, 'stella = new Dog(&quot;Stella&quot;)', 1, 48),
(148, 'shadow = Dog(&quot;Shadow&quot;)', 0, 48),
(149, '__init__()', 1, 49),
(150, '__new__()', 0, 49),
(151, '__create__()', 0, 49),
(152, '__instance__()', 0, 49),
(153, '__str__()', 1, 50),
(154, '__repr__()', 0, 50),
(155, '__tostring__()', 0, 50),
(156, '__convert__()', 0, 50),
(157, 'super().methodName()', 1, 51),
(158, 'super.methodName()', 0, 51),
(159, 'parent.methodName()', 0, 51),
(160, 'parent().methodName()', 0, 51),
(161, 'void', 0, 52),
(162, 'null', 0, 52),
(163, 'None', 1, 52),
(164, 'empty', 0, 52),
(165, 'write', 0, 53),
(166, 'append', 0, 53),
(167, 'open', 1, 53),
(168, 'update', 0, 53),
(169, 'readline', 0, 53),
(170, 'Sections of code that can be called multiple times within a program', 1, 65),
(171, 'For loops with complex iteration variables', 0, 65),
(172, 'Nested conditional statements', 0, 65),
(173, 'Local variables inside a function', 0, 65),
(174, 'Alternative flow routes', 0, 65),
(175, 'A break statement', 0, 66),
(176, 'An if-else conditional structure', 0, 66),
(177, 'A user-defined function that calculates the average of a list of numbers', 1, 66),
(178, 'A global variable', 0, 66),
(179, 'A pass statement', 0, 66),
(180, 'A conditional statement', 0, 67),
(181, 'A global variable', 0, 67),
(182, 'A separate function or procedure', 1, 67),
(183, 'A mathematical operation', 0, 67),
(184, 'Only in subprograms', 0, 68),
(185, 'Only among the arguments', 0, 68),
(186, 'Nowhere in the program', 0, 68),
(187, 'Anywhere in the program', 1, 68),
(188, 'Passing by value allows the original variable passed to the function to be modified directly, while passing by reference works on a copy', 0, 69),
(189, 'Passing by reference allows the function to modify the original object directly, while passing by value works on a copy.', 1, 69),
(190, 'Passing by reference is used only in object-oriented languages, while passing by value is used in procedural languages.', 0, 69),
(191, 'Passing by reference allows default parameters to be used, while passing by value does not', 0, 69),
(192, 'Records and Files', 0, 70),
(193, 'Lists and Dictionaries', 1, 70),
(194, 'Stacks and Queues', 0, 70),
(195, 'Arrays and Matrices', 0, 70),
(196, 'Strings and Booleans', 0, 70),
(197, 'Defining conditional statements', 0, 71),
(198, 'Organizing and managing collections of data', 1, 71),
(199, 'Creating complex flowcharts', 0, 71),
(200, 'Representing mathematical sets', 0, 71),
(201, 'x(2)', 0, 72),
(202, 'x[1]', 1, 72),
(203, 'x{1}', 0, 72),
(204, 'x&lt;2&gt;', 0, 72),
(205, 'list', 0, 73),
(206, 'dictionary', 0, 73),
(207, 'set', 0, 73),
(208, 'tuple', 1, 73),
(209, '[1, 2, 2, 3]', 0, 74),
(210, '{1, 2, 3}', 1, 74),
(211, '(1, 2, 2, 3)', 0, 74),
(212, '{1: 2, 2: 1, 3: 1}', 0, 74),
(213, 'add()', 0, 75),
(214, 'append()', 1, 75),
(215, 'insert()', 0, 75),
(216, 'update()', 0, 75),
(217, 'list', 0, 76),
(218, 'set', 0, 76),
(219, 'dictionary', 1, 76),
(220, 'tuple', 0, 76),
(221, '{}', 0, 77),
(222, '[]', 0, 77),
(223, 'set()', 1, 77),
(224, 'dict()', 0, 77),
(225, 'It returns the values', 0, 78),
(226, 'It returns the key/value pairs', 0, 78),
(227, 'It returns the keys', 1, 78),
(228, 'It deletes the keys', 0, 78),
(229, 'append()', 0, 79),
(230, 'pop()', 0, 79),
(231, 'concatenation with +', 1, 79),
(232, 'remove()', 0, 79),
(233, 'set', 0, 80),
(234, 'tuple', 0, 80),
(235, 'list', 1, 80),
(236, 'dictionary', 0, 80),
(237, 'Using a variable without initializing it', 0, 81),
(238, 'Nesting multiple functions inside the same function', 0, 81),
(239, 'A function calling itself', 1, 81),
(240, 'Recreating a variable every time it is used', 0, 81),
(241, 'Repeating an instruction a defined number of times', 0, 81),
(242, 'O(1)', 0, 86),
(243, 'O(log n)', 0, 86),
(244, 'O(n)', 1, 86),
(245, 'O(n log n)', 0, 86),
(246, 'O(n^2)', 0, 86),
(247, 'O(&radic;n)', 0, 86),
(248, 'Direct access by index', 0, 87),
(249, 'Binary search', 0, 87),
(250, 'Insertion at the head', 1, 87),
(251, 'Sorting', 0, 87),
(252, 'Random access', 0, 87),
(253, 'Calculating the length', 0, 87),
(254, 'Only the data', 0, 88),
(255, 'Data and a pointer to the next node', 0, 88),
(256, 'Data and a pointer to the previous node', 0, 88),
(257, 'Data and two pointers', 1, 88),
(258, 'Three pointers', 0, 88),
(259, 'No pointers', 0, 88),
(260, '1', 0, 89),
(261, '2', 1, 89),
(262, '3', 0, 89),
(263, 'Unlimited', 0, 89),
(264, 'It depends on the height', 0, 89),
(265, 'None', 0, 89),
(266, 'Preorder', 0, 90),
(267, 'Postorder', 0, 90),
(268, 'Level order', 0, 90),
(269, 'Inorder', 1, 90),
(270, 'Random DFS', 0, 90),
(271, 'Reverse BFS', 0, 90),
(272, 'O(1)', 0, 91),
(273, 'O(log n)', 0, 91),
(274, 'O(n)', 1, 91),
(275, 'O(n log n)', 0, 91),
(276, 'O(n^2)', 0, 91),
(277, 'O(&radic;n)', 0, 91),
(278, 'Only vertices', 0, 92),
(279, 'Only edges', 0, 92),
(280, 'Vertices and edges', 1, 92),
(281, 'Only weights', 0, 92),
(282, 'Matrices', 0, 92),
(283, 'Lists', 0, 92),
(284, 'Total number of nodes', 0, 93),
(285, 'Number of outgoing edges', 0, 93),
(286, 'Number of incoming edges', 1, 93),
(287, 'Number of cycles', 0, 93),
(288, 'Number of components', 0, 93),
(289, 'Number of shortest paths', 0, 93),
(290, 'A graph with at least one cycle', 0, 94),
(291, 'A connected and acyclic graph', 1, 94),
(292, 'A weighted directed graph', 0, 94),
(293, 'A complete graph', 0, 94),
(294, 'A linked list', 0, 94),
(295, 'Any DAG', 0, 94),
(296, 'Projection', 0, 107),
(297, 'Selection', 1, 107),
(298, 'Intersection', 0, 107),
(299, 'Difference', 0, 107),
(300, 'Union', 1, 108),
(301, 'Join', 0, 108),
(302, 'Selection', 0, 108),
(303, 'Cartesian product', 0, 108),
(304, 'Union', 0, 109),
(305, 'Intersection', 1, 109),
(306, 'Difference', 0, 109),
(307, 'Join', 0, 109),
(308, 'Join', 0, 110),
(309, 'Union', 0, 110),
(310, 'Difference', 1, 110),
(311, 'Projection', 0, 110),
(312, 'Cartesian product', 1, 111),
(313, 'Join', 0, 111),
(314, 'Selection', 0, 111),
(315, 'Intersection', 0, 111),
(316, 'Join', 1, 112),
(317, 'Union', 0, 112),
(318, 'Projection', 0, 112),
(319, 'Difference', 0, 112),
(320, 'Join', 1, 113),
(321, 'Projection', 0, 113),
(322, 'Intersection', 0, 113),
(323, 'Union', 0, 113),
(324, 'Join', 0, 114),
(325, 'Selection', 0, 114),
(326, 'Projection', 1, 114),
(327, 'Intersection', 0, 114),
(328, 'Join', 1, 115),
(329, 'Union', 0, 115),
(330, 'Difference', 0, 115),
(331, 'Projection', 0, 115),
(332, 'Projection', 0, 116),
(333, 'Join', 0, 116),
(334, 'Selection', 1, 116),
(335, 'Union', 0, 116),
(336, 'Selection', 0, 117),
(337, 'Projection', 1, 117),
(338, 'Join', 0, 117),
(339, 'Intersection', 0, 117),
(340, 'Union', 0, 118),
(341, 'Difference', 0, 118),
(342, 'Join', 1, 118),
(343, 'Selection', 0, 118),
(344, 'Union', 1, 119),
(345, 'Intersection', 0, 119),
(346, 'Projection', 0, 119),
(347, 'Difference', 0, 119),
(348, 'Join', 0, 120),
(349, 'Difference', 0, 120),
(350, 'Union', 0, 120),
(351, 'Intersection', 1, 120),
(352, 'Intersection', 0, 121),
(353, 'Difference', 1, 121),
(354, 'Union', 0, 121),
(355, 'Selection', 0, 121),
(356, 'Cartesian product', 1, 122),
(357, 'Join', 0, 122),
(358, 'Union', 0, 122),
(359, 'Difference', 0, 122),
(360, 'Join', 0, 123),
(361, 'Projection', 1, 123),
(362, 'Selection', 0, 123),
(363, 'Union', 0, 123),
(364, 'Bubble sort', 1, 124),
(365, 'Insertion sort', 0, 124),
(366, 'Selection sort', 0, 124),
(367, 'Merge sort', 0, 124),
(368, 'Bubble sort', 0, 125),
(369, 'Insertion sort', 0, 125),
(370, 'Selection sort', 1, 125),
(371, 'Merge sort', 0, 125),
(372, 'Binary search', 1, 126),
(373, 'Ordered search', 0, 126),
(374, 'Search by trial and error', 0, 126),
(375, 'Linear search', 0, 126),
(376, 'Bubble sort', 0, 127),
(377, 'Insertion sort', 0, 127),
(378, 'Selection sort', 0, 127),
(379, 'Merge sort', 1, 127),
(380, 'Binary search', 0, 128),
(381, 'Sequential search', 1, 128),
(382, 'Search by trial and error', 0, 128),
(383, 'Ordered search', 0, 128),
(384, 'O(n^2)', 1, 129),
(385, 'O(1)', 0, 129),
(386, 'O(n)', 0, 129),
(387, 'O(n log n)', 0, 129),
(388, 'O(n)', 1, 130),
(389, 'O(n^2)', 0, 130),
(390, 'O(1)', 0, 130),
(391, 'O(log n)', 0, 130),
(392, 'O(n)', 0, 131),
(393, 'O(n^2)', 0, 131),
(394, 'O(1)', 0, 131),
(395, 'O(log n)', 1, 131),
(396, 'Not SQL', 0, 132),
(397, 'Not Only SQL', 1, 132),
(398, 'Near Only SQL', 0, 132),
(399, 'Not or SQL', 0, 132),
(400, 'They use fixed-schema tables', 0, 133),
(401, 'They are schema-less', 1, 133),
(402, 'They use both fixed schema tables and free schema documents', 0, 133),
(403, 'They need to define the data schema to be used in advance', 0, 133),
(404, 'The property of having all the data in a single server that is always active', 0, 134),
(405, 'The property of guaranteeing data availability even in the event of the failure of some nodes in a distributed system', 1, 134),
(406, 'The property of guaranteeing transactions with ACID mode', 0, 134),
(407, 'The property of guaranteeing data consistency at every single moment', 0, 134),
(408, 'That data is always available as long as the database host is online', 0, 135),
(409, 'That the data is guaranteed to be consistent after a certain time if no changes occur', 1, 135),
(410, 'Which guarantees the complete consistency of the entire database at all times', 0, 135),
(411, 'That data is saved in documents rather than tables', 0, 135),
(412, 'I can have a maximum of 2 of the following properties for a db: Consistency, Availability, Partition tolerance', 1, 136),
(413, 'Only in rare cases am I able to obtain all three of the following properties for a db: Consistency, Availability, Partition Tolerance', 0, 136),
(414, 'I can have at most one of the following properties: Consistency, Availability, Horizontal scalability', 0, 136),
(415, 'I can have a maximum of 2 of the following properties for a db: Consistency, Flexibility, Partition Tolerance', 0, 136),
(416, 'Consistency: All nodes see the same data at the same time', 0, 137),
(417, 'Availability: every operation always receives a response', 0, 137),
(418, 'Partition Tolerance: the system can add nodes, a node can fall or be removed in a distributed perspective', 1, 137),
(419, 'High performance: data can be reached in a short time', 0, 137),
(420, 'If every request must be answered and everyone must see the same data, then I cannot have database distribution', 0, 138),
(421, 'If every request must be answered and I have a distributed database, I necessarily have to duplicate the data, which could be different from one node to another for a certain time', 1, 138),
(422, 'If I have distributed db and the data needs to be the same for everyone, then I may not have answers to some questions, as long as the changes propagate', 0, 138),
(423, 'If every request must be answered and I have a distributed database, then if a network node falls I can no longer access the data', 0, 138),
(424, 'Orientato alle colonne', 0, 139),
(425, 'A documenti', 0, 139),
(426, 'A grafo', 0, 139),
(427, 'A valori duplicati', 1, 139),
(428, 'MongoDB', 0, 140),
(429, 'Cassandra', 0, 140),
(430, 'CouchDB', 0, 140),
(431, 'Oracle', 1, 140),
(432, 'XML', 0, 141),
(433, 'YAML', 0, 141),
(434, 'JSON', 1, 141),
(435, 'Testo semplice', 0, 141),
(436, 'A bus', 0, 142),
(437, 'A maglia', 0, 142),
(438, 'Ad anello', 1, 142),
(439, 'A stella', 0, 142),
(440, 'Foreign key', 0, 147),
(441, 'Candidate key', 0, 147),
(442, 'Primary key', 1, 147),
(443, 'Artificial key', 0, 147),
(444, 'Foreign key', 0, 148),
(445, 'Compound key', 1, 148),
(446, 'Candidate key', 0, 148),
(447, 'Secondary key', 0, 148),
(448, 'Foreign key', 0, 149),
(449, 'Compound key', 0, 149),
(450, 'Candidate key', 1, 149),
(451, 'Artificial key', 0, 149),
(452, 'Primary key', 0, 150),
(453, 'Artificial key', 0, 150),
(454, 'Foreign key', 1, 150),
(455, 'Candidate key', 0, 150),
(456, 'Compound key', 0, 151),
(457, 'Artificial key', 1, 151),
(458, 'Foreign key', 0, 151),
(459, 'Candidate key', 0, 151),
(460, 'They are derived from existing attributes', 0, 152),
(461, 'They are chosen from among the candidate keys', 0, 152),
(462, 'They are artificially generated and do not derive from existing attributes', 1, 152),
(463, 'Possono contenere riferimenti esterni', 0, 152),
(464, 'a candidate key', 0, 153),
(465, 'an artificial key', 0, 153),
(466, 'a composite key', 0, 153),
(467, 'a foreign key', 1, 153),
(468, 'Ensure the uniqueness of the data in the table', 0, 154),
(469, 'Allow connections between different entities', 1, 154),
(470, 'Create new artificial identifiers', 0, 154),
(471, 'Avoid redundancy in attribute names', 0, 154),
(472, 'A unique identifier for orders', 0, 155),
(473, 'A link between order and customer', 1, 155),
(474, 'A calculated attribute', 0, 155),
(475, 'An optional field', 0, 155),
(476, 'Candidate key', 0, 156),
(477, 'Compound key', 0, 156),
(478, 'Foreign key', 1, 156),
(479, 'Artificial key', 0, 156),
(480, 'Chiusa di tipo EULA', 0, 173),
(481, 'Open Source', 1, 173),
(482, 'Creative Commons', 0, 173),
(483, 'ADWare', 0, 173),
(484, 'Create a new block of code, we insert it for example after an if', 1, 174),
(485, 'It can be inserted anywhere, it causes no problems', 0, 174),
(486, 'Used to insert a comment', 0, 174),
(487, 'It only serves to have tidier code', 0, 174),
(488, 'a=b=c=5', 1, 175),
(489, 'a=5, b=5, c=5', 0, 175),
(490, 'a==b==c==5', 0, 175),
(491, 'a!=b=!c!=5', 0, 175),
(492, 'the string &quot;a5&quot;', 0, 176),
(493, 'the string &quot;5a&quot;', 0, 176),
(494, 'the string &quot;aaaaa&quot;', 1, 176),
(495, 'Errore', 0, 176),
(496, 'Change the value of a variable', 0, 177),
(497, 'Change the type of a variable', 1, 177),
(498, 'Just turning a string into an integer', 0, 177),
(499, 'Require an integer type as input', 0, 177),
(500, 'try', 0, 178),
(501, 'for', 0, 178),
(502, 'switch', 0, 178),
(503, 'if', 1, 178),
(504, 'Using the keyword &quot;final&quot;', 0, 179),
(505, 'Using the keyword &quot;constant&quot;', 0, 179),
(506, 'Using the &quot;=&quot; symbol to assign a value to the variable name', 1, 179),
(507, 'Using the &quot;:&quot; symbol in front of the variable name', 0, 179),
(508, 'That every operation is fast', 0, 186),
(509, 'That the data is readable', 0, 186),
(510, 'Whether a transaction completes all or nothing', 1, 186),
(511, 'That the transaction is made by a single user', 0, 186),
(512, 'That all data is in JSON format', 0, 187),
(513, 'That the data is duplicated', 0, 187),
(514, 'That the data respects the constraints defined in the DB', 1, 187),
(515, 'That the data is sorted alphabetically', 0, 187),
(516, 'Faster access', 0, 188),
(517, 'Data redundancy and inconsistency', 1, 188),
(518, 'Maggiore sicurezza', 0, 188),
(519, 'Miglior backup', 0, 188),
(520, 'To create web pages', 0, 189),
(521, 'A visualizzare PDF', 0, 189),
(522, 'To manage and organize data in a database', 1, 189),
(523, 'To compress files', 0, 189),
(524, 'That the database is encrypted', 0, 190),
(525, 'That each transaction is independent of other competing ones', 1, 190),
(526, 'That the database is not accessible', 0, 190),
(527, 'Which only the administrator can access', 0, 190),
(528, 'Aumento della ridondanza', 0, 191),
(529, 'Difficulty in concurrent access', 0, 191),
(530, 'Centralized data control', 1, 191),
(531, 'Costi maggiori', 0, 191),
(532, 'Miglioramento delle performance', 0, 192),
(533, 'Data always updated', 0, 192),
(534, 'Inconsistent updates and data loss', 1, 192),
(535, 'Maggiore sicurezza', 0, 192),
(536, 'That the data is deleted immediately after use', 0, 193),
(537, 'That the data can be modified by anyone', 0, 193),
(538, 'That data persists even after a crash', 1, 193),
(539, 'That the data is temporary', 0, 193),
(540, 'Data accessible to all', 0, 194),
(541, 'Unformatted data', 0, 194),
(542, 'Ripetizione inutile delle stesse informazioni', 1, 194),
(543, 'Missing data in the database', 0, 194),
(544, 'A messaging app', 0, 195),
(545, 'A structured collection of data managed by a software system', 1, 195),
(546, 'A spreadsheet', 0, 195),
(547, 'A web browser', 0, 195),
(548, 'Durability', 0, 196),
(549, 'Referential integrity constraints', 0, 196),
(550, 'Atomicity', 1, 196),
(551, 'Isolamento', 0, 196),
(552, 'Isolamento', 1, 197),
(553, 'Consistenza', 0, 197),
(554, 'Durability', 0, 197),
(555, 'Atomicity', 0, 197),
(556, 'Isolamento', 0, 198),
(557, 'Durability', 0, 198),
(558, 'Consistenza', 1, 198),
(559, 'Atomicity', 0, 198),
(560, 'Consistenza', 0, 199),
(561, 'Atomicity', 0, 199),
(562, 'Isolamento', 0, 199),
(563, 'Durability', 1, 199),
(564, '3lato', 0, 208),
(565, 'lato triangolo', 0, 208),
(566, 'latoTriangolo', 1, 208),
(567, 'lato_triangolo!', 0, 208),
(568, 'A graphical representation of a program&#039;s instruction flow', 1, 215),
(569, 'A list of programming language keywords', 0, 215),
(570, 'A list of functions available in Python', 0, 215),
(571, 'A summary table of the variables used in the program', 0, 215),
(572, 'A collection of Python code examples', 0, 215),
(573, 'A function to call', 0, 216),
(574, 'A condition to check', 1, 216),
(575, 'An arithmetic operation to perform', 0, 216),
(576, 'A variable to initialize', 0, 216),
(577, 'An instruction to execute', 0, 216),
(578, 'On a hard drive', 0, 217),
(579, 'In ROM memory', 0, 217),
(580, 'Nella CPU', 0, 217),
(581, 'In RAM memory', 1, 217),
(582, 'Nell&#039;ALU', 0, 217),
(583, 'Rappresentare graficamente l&#039;algoritmo', 1, 218),
(584, 'Define program variables', 0, 218),
(585, 'Eseguire istruzioni condizionali', 0, 218),
(586, 'Create complex data structures', 0, 218),
(587, 'Variabili', 0, 219),
(588, 'Istruzioni condizionali', 1, 219),
(589, 'Data structures', 0, 219),
(590, 'Sottoprogrammi', 0, 219),
(591, 'Store temporary data', 1, 220),
(592, 'Create repetition loops', 0, 220),
(593, 'Definire funzioni personalizzate', 0, 220),
(594, 'Create flowcharts', 0, 220),
(595, 'javac', 1, 224),
(596, 'java', 0, 224),
(597, 'rmic', 0, 224),
(598, 'gcc', 0, 224),
(599, 'A special variable saved at the operating system level', 1, 225),
(600, 'An integer variable declared without assigning a value', 0, 225),
(601, 'It is a synonym for constant in Java', 0, 225),
(602, 'A special Java variable visible to all possible classes of a program', 0, 225),
(603, 'JDK (Java Development Kit)', 1, 226),
(604, 'JRE (Java Runtime Environment)', 0, 226),
(605, 'J2EE (Java 2 Enterprise Edition)', 0, 226),
(606, 'JSP (Java Server Pages)', 0, 226),
(607, 'A command to execute a statement, for example if', 0, 227),
(608, 'The name we assign to a variable', 1, 227),
(609, 'A list of items', 0, 227),
(610, 'An indefinite cycle', 0, 227),
(611, 'Words that identify language commands, such as if', 1, 228),
(612, 'The names that are assigned to the variables', 0, 228),
(613, 'A programmers convention for declaring constants', 0, 228),
(614, 'The ability to insert special characters within strings using backslash before the character', 0, 228),
(615, 'What uppercase and lowercase letters make a difference in variable names, for example foo and Foo are two different variables', 1, 229),
(616, 'That it is no longer possible to change the type of a variable after its declaration', 0, 229),
(617, 'That the type of a variable is deduced from the value we assign to it', 0, 229),
(618, 'That the convention for writing variables in Java is the first letter is lowercase, if there are multiple words, from the second onwards they are capitalized', 0, 229),
(619, 'lato', 0, 230),
(620, '3lato', 1, 230),
(621, 'lato3', 0, 230),
(622, 'latoTriangolo', 0, 230),
(623, 'latoQuadrato', 1, 231),
(624, 'lato_quadrato', 0, 231),
(625, 'LatoQuadrato', 0, 231),
(626, 'latoquadrato', 0, 231),
(627, 'const PIGRECO = 3.14;', 0, 232),
(628, 'final double PIGRECO = 3.14;', 1, 232),
(629, 'static float PIGRECO = 3.14f;', 0, 232),
(630, 'double PIGRECO = 3.14;', 0, 232),
(631, 'Change the type of a variable, for example by transforming a double into an integer', 1, 233),
(632, 'That the compiler understands on its own what the type of a variable is', 0, 233),
(633, 'That we cannot change the type of a variable after its declaration', 0, 233),
(634, 'That I can assign a value to a variable', 0, 233),
(635, 'That java automatically casts between different types if there is no loss of information', 1, 234),
(636, 'That I can always change the type of a variable', 0, 234),
(637, 'That in Java I always have to perform an explicit cast to change the type of a variable', 0, 234),
(638, 'That in Java it is never possible to change the type of a variable', 0, 234),
(639, 'That once I assign a type to a variable, I can no longer modify it', 1, 235),
(640, 'That I can dynamically change the type of a variable, assigning it a different value', 0, 235),
(641, 'That the compiler understands on its own what the type of a variable is, based on the value assigned to it', 0, 235),
(642, 'That there are 4 types of variables: int, float, boolean, char', 0, 235),
(643, 'Each variable must have its own type, which cannot be changed', 1, 236),
(644, 'Variables can take on different types during program execution', 0, 236),
(645, 'The compiler or interpreter itself figures out the type of the variable based on the assigned value', 0, 236),
(646, 'That there is a differentiation between primitive types and reference types', 0, 236),
(647, 'AND', 0, 237),
(648, '&amp;&amp;', 1, 237),
(649, '||', 0, 237),
(650, '!%', 0, 237),
(651, '&amp;&amp;', 0, 238),
(652, 'OR', 0, 238),
(653, '||', 1, 238),
(654, '!?', 0, 238),
(655, 'A library that I can import inside my programs, similar to Python modules', 1, 239),
(656, 'It is the assignment statement in Java', 0, 239),
(657, 'It is a tool for creating lists of elements, similar to Python lists', 0, 239),
(658, 'It is the executable program on the JVM obtained from the compiler', 0, 239),
(659, 'It is the code obtained by compiling a Java program, interpretable by the JVM', 1, 240),
(660, 'It&#039;s a Java library that I can import into programs, like Python modules', 0, 240),
(661, 'It is a program that can be directly executable by the operating system with an .exe extension', 0, 240),
(662, 'It is a synonym for binary code made up of 0 and 1', 0, 240),
(663, 'It is a cross between the high-level language Java and machine language', 1, 241),
(664, 'It can be run directly on the CPU, without the need for other programs', 0, 241),
(665, 'It is the Java file written by the programmer with the source code', 0, 241),
(666, 'It is the interpreter that transforms Java code into machine code executable by the processor', 0, 241),
(667, 'It is the Java Virtual Machine, the interpreter that transforms the ByteCode into code executable by the CPU', 1, 242),
(668, 'It is the Java Visual Procedure, a specific procedure for performing input in Java', 0, 242),
(669, 'It is Java Vehicle Merge, the compiler that transforms source code into executable code', 0, 242),
(670, 'Standing for Just Virtual Merchandise, it indicates the fact that Java is portable across multiple operating systems', 0, 242),
(671, 'Scanner', 1, 243),
(672, 'Input', 0, 243),
(673, 'String', 0, 243),
(674, 'System.out', 0, 243),
(675, 'System.out.println(&quot;string&quot;);', 1, 244),
(676, 'System.in;', 0, 244),
(677, 'print(&quot;string&quot;);', 0, 244),
(678, 'Scanner output = new Scanner(&quot;string&quot;);', 0, 244),
(679, 'int x = myScanner.nextInt();', 1, 245),
(680, 'double x = myScanner.nextDouble();', 0, 245),
(681, 'int x = new Scanner(System.in);', 0, 245),
(682, 'int x = myScanner.input(&quot;Enter an integer&quot;);', 0, 245),
(683, 'With two slashes //', 1, 246),
(684, 'With /* to start and */ to finish', 0, 246),
(685, 'With the pound character #', 0, 246),
(686, 'With the characters ?$', 0, 246),
(687, 'With two slashes //', 0, 247),
(688, 'With /* to start and */ to end', 1, 247),
(689, 'It begins with the characters &lt;!-- and ends with --&gt;', 0, 247),
(690, 'With three asterisks *** to start and two to end **', 0, 247),
(691, 'The result will be of type int and the decimal part will be truncated without rounding', 1, 248),
(692, 'The result will be of type int if there is no remainder in the division, of type double otherwise', 0, 248),
(693, 'The result will always be double', 0, 248),
(694, 'The result will be of type int and the number will be rounded (so if the decimal part is greater than .5 it is rounded up)', 0, 248),
(695, 'The variable will always be visible even outside that block of code', 0, 249),
(696, 'The variable will only be visible within that block of code and its internal blocks', 1, 249),
(697, 'If the code block contains another code block, the variable will not be visible in the inner block', 0, 249),
(698, 'Variables must be declared at the beginning and I cannot declare them within subsequent blocks of code', 0, 249),
(699, 'They are equivalent, there are no differences between the two', 0, 250),
(700, 'The double &amp;&amp; stops as soon as it finds a false condition returning false, without checking the other conditions, the single &amp; always checks all the conditions', 1, 250),
(701, 'The single &amp; stops as soon as it finds a false condition returning true, without checking the other conditions, the double &amp;&amp; always checks all the conditions', 0, 250),
(702, 'The double &amp;&amp; always checks all the conditions entered, the single &amp; stops as soon as it finds a true condition returning true', 0, 250),
(703, 'The double || it stops as soon as it finds a true condition and returns true, without checking the other conditions, the single | always check all conditions', 1, 251),
(704, 'The double || it stops as soon as it finds a true condition and returns false, without checking the other conditions, the single | always check all conditions', 0, 251),
(705, 'The single | it stops as soon as it finds a true condition and returns true, without checking the other conditions, double || always check all conditions', 0, 251),
(706, 'There are no differences between the two', 0, 251),
(707, 'Class object', 1, 258),
(708, 'Class constructor', 0, 258),
(709, 'Class attribute', 0, 258),
(710, 'Class method', 0, 258),
(711, 'Indicates the property of objects to incorporate attributes and methods within them', 1, 259),
(712, 'Indicates the fact that the attributes of a class are private', 0, 259),
(713, 'Indicates the fact that a class cannot have subclasses', 0, 259),
(714, 'It is a Java property that allows us to create constants', 0, 259),
(715, 'It is a special method with the same name as the class', 1, 260),
(716, 'It is any method of the class', 0, 260),
(717, 'It cannot have parameters', 0, 260),
(718, 'It is an instance variable of the class', 0, 260),
(719, 'It is an attribute valued for a given object', 1, 261),
(720, 'It is a variable declared inside any method', 0, 261),
(721, 'It is a variable declared in the main method', 0, 261),
(722, 'It is a global variable visible to the entire Java program', 0, 261),
(723, 'Circle c  = new Circle(8);', 1, 262),
(724, 'Circle c = __init__(8);', 0, 262),
(725, 'Circle c = new Circle[8];', 0, 262),
(726, 'Circle c = Circle(8);', 0, 262),
(727, 'public int somma()', 0, 263),
(728, 'protected double divisione(int x,int y)', 0, 263),
(729, 'String saluto()', 0, 263),
(730, 'public versamento(int denaro)', 1, 263),
(731, 'It is an attribute of the class', 0, 264),
(732, 'It is a variable defined within a method and visible only within it', 1, 264),
(733, 'It is a variable visible to the entire Java program', 0, 264),
(734, 'It is a variable defined with the static keyword', 0, 264),
(735, 'It is used to define constants', 1, 265),
(736, 'It is used to create global variables', 0, 265),
(737, 'It is used to create instance variables', 0, 265),
(738, 'It is used to define methods that can be called with classname.methodname()', 0, 265),
(739, 'It is used when referring to a null reference, which does not point to any object', 1, 266),
(740, 'It is used when a primitive type, such as int, has not been given a value', 0, 266),
(741, 'It is used when I want to compare two strings and make sure they are equal', 0, 266),
(742, 'It is used when I want to refer to a class attribute within the code of a method', 0, 266),
(743, 'Array a1 = new Array(5,Car)', 0, 267),
(744, 'Car a1 = new Array[5]', 0, 267),
(745, 'Car a1[] = new Car[5]', 1, 267),
(746, 'Car a1 = new Car(5)', 0, 267),
(747, 'The set of private attributes and methods of a class', 1, 268),
(748, 'The set of methods that the programmer can use on an object of a certain class', 0, 268),
(749, 'It is a synonym for class constructor', 0, 268),
(750, 'It is the package containing the class', 0, 268),
(751, 'A model of a real object, with its characteristics and functionality', 1, 269),
(752, 'A particular function that can be imported into programs', 0, 269),
(753, 'It is a synonym for package in Java', 0, 269),
(754, 'It&#039;s a library that allows me to perform complex operations', 0, 269),
(755, 'r1.altezza', 0, 270),
(756, 'Private attributes of a class cannot be directly accessed from an external class', 1, 270),
(757, 'r1.__altezza', 0, 270),
(758, 'get(r1,altezza)', 0, 270),
(759, 'obj.calculatePerimeter()', 1, 271),
(760, 'obj-&gt;calculatePerimeter()', 0, 271),
(761, 'calculatePerimeter(obj)', 0, 271),
(762, 'import obj.calculatePerimeter()', 0, 271),
(763, 'The masking of the implementation methods of an object, making only its functionality available', 1, 272),
(764, 'The fact of being able to attribute public attributes and methods to classes', 0, 272),
(765, 'The fact that we cannot know how the java.util.Random package is implemented', 0, 272),
(766, 'The sharing of information between objects of the same class', 0, 272),
(767, 'x contains an integer value', 0, 273),
(768, 'x contains a reference to an object of class Circle', 1, 273),
(769, 'x contains the value of the area of a circle', 0, 273),
(770, 'the Circle class contains an attribute named x', 0, 273),
(771, 'All class attributes are public', 0, 274),
(772, 'Some of the class attributes are private', 1, 274),
(773, 'You insert a toString() method inside the class to transform it into a string', 0, 274),
(774, 'I insert static attributes inside the class', 0, 274),
(775, 'I have a null reference', 1, 275),
(776, 'I can call the toString() method on that reference, because it is inherited from the Object class', 0, 275),
(777, 'I can access the object&#039;s attributes with the operator. (period)', 0, 275),
(778, 'I can&#039;t do this unless the class has been declared static', 0, 275),
(779, 'Declare a class variable, rather than tied to objects', 0, 276),
(780, 'Declare a Constructor method', 0, 276),
(781, 'Declare a constant, which once initialized can no longer be modified', 1, 276),
(782, 'Declare that the class we are building inherits from a superclass', 0, 276),
(783, 'Only if the names of the two manufacturers are different', 0, 277),
(784, 'Only if they contain the same parameters', 0, 277),
(785, 'Only if they contain different parameters', 1, 277),
(786, 'False, it is never possible to declare multiple constructors for a class in Java', 0, 277),
(787, 'public void ClassName()', 0, 278),
(788, 'public ClassName()', 1, 278),
(789, 'private ClassName()', 0, 278),
(790, 'It is not possible to define a class without a constructor', 0, 278),
(791, 'extends', 1, 279),
(792, 'inherits', 0, 279),
(793, 'implements', 0, 279),
(794, 'extendsOf', 0, 279),
(795, 'public', 0, 280),
(796, 'private', 0, 280),
(797, 'protected', 1, 280),
(798, 'default', 0, 280),
(799, 'void', 1, 281),
(800, 'null', 0, 281),
(801, 'none', 0, 281),
(802, 'empty', 0, 281),
(803, 'Being able to create simplified objects from the interface', 0, 282),
(804, 'Specify a contract that classes must follow', 1, 282),
(805, 'They give the possibility to add static methods to classes', 0, 282),
(806, 'They allow information hiding since all attributes are private', 0, 282),
(807, 'Subclass', 1, 283),
(808, 'Superclass', 0, 283),
(809, 'Object class', 0, 283),
(810, 'Constructor', 0, 283),
(811, 'Estensione e ridefinizione', 1, 284),
(812, 'Estensione e polimorfismo', 0, 284),
(813, 'Ridefinizione e polimorfismo', 0, 284),
(814, 'Overloading e overriding', 0, 284),
(815, 'Single inheritance', 1, 285),
(816, 'Multiple inheritance', 0, 285),
(817, 'Hierarchical inheritance', 0, 285),
(818, 'Multilevel inheritance', 0, 285),
(819, 'Single inheritance', 0, 286),
(820, 'Multilevel inheritance', 0, 286),
(821, 'Multiple inheritance', 1, 286),
(822, 'Hierarchical inheritance', 0, 286),
(823, 'No, mai', 0, 287),
(824, 'Yes, always, for any class', 0, 287),
(825, 'Yes, but only if we inherit from a single class and multiple interfaces', 1, 287),
(826, 'Yes, only if we inherit from a single interface and multiple classes', 0, 287),
(827, 'extends', 1, 288),
(828, 'super', 0, 288),
(829, 'implements', 0, 288),
(830, 'I put the superclass in parentheses after the class declaration', 0, 288),
(831, 'Object', 1, 289),
(832, 'Main', 0, 289),
(833, 'Util', 0, 289),
(834, 'Javac', 0, 289),
(835, 'Overloading', 1, 290),
(836, 'Overriding', 0, 290),
(837, 'Information Hiding', 0, 290),
(838, 'Of the builders of the class', 0, 290),
(839, 'Overriding', 1, 291),
(840, 'Overloading', 0, 291),
(841, 'Information Hiding', 0, 291),
(842, 'Encapsulation', 0, 291),
(843, 'They are never inherited by subclasses', 1, 292),
(844, 'They are always inherited by subclasses', 0, 292),
(845, 'They are inherited by subclasses only if they are also protected', 0, 292),
(846, 'They are inherited from subclasses only if the subclasses are interfaces', 0, 292),
(847, 'Save data within secondary storage', 1, 293),
(848, 'Save data within RAM memory', 0, 293),
(849, 'Save data within ROM memory', 0, 293),
(850, 'View data within your notebook', 0, 293),
(851, 'Apertura', 0, 294),
(852, 'Chiusura', 0, 294),
(853, 'Scrittura', 0, 294),
(854, 'Modellazione', 1, 294),
(855, 'Creates a link between main memory and secondary memory', 1, 295),
(856, 'Create a connection between the RAM and the printer', 0, 295),
(857, 'It is never possible if the file is not already present on the hard disk', 0, 295),
(858, 'Create a connection between the hard disk and the screen where I view the file', 0, 295),
(859, 'Input Operation: Data passes from secondary memory to the running program', 1, 296),
(860, 'Output Operation: Data passes from secondary storage to the running program', 0, 296),
(861, 'Input Operation: Data passes from the running program to secondary memory', 0, 296),
(862, 'Output Operation: Data passes from the running program to secondary storage', 0, 296),
(863, 'Output Operation: Data passes from the running program to secondary storage', 1, 297),
(864, 'Input Operation: Data passes from the running program to secondary memory', 0, 297),
(865, 'Input Operation: Data passes from secondary memory to the running program', 0, 297),
(866, 'Output Operation: Data passes from secondary storage to the running program', 0, 297),
(867, 'java.io', 1, 298),
(868, 'java.util', 0, 298),
(869, 'java.lang', 0, 298),
(870, 'java.awt', 0, 298),
(871, 'A stream of data', 1, 299),
(872, 'An Internet connection', 0, 299),
(873, 'A program to view films or listen to music (in streaming)', 0, 299),
(874, 'An inherited Java class', 0, 299),
(875, 'Basate sui Byte e basate sui Caratteri', 1, 300),
(876, 'Basate sulle Stringhe e basate sui bit', 0, 300),
(877, 'Input-based and output-based', 0, 300),
(878, 'Basate su binario e basate su eadecimale', 0, 300),
(879, 'Structured files and text files', 1, 301),
(880, 'Binary files and hexadecimal files', 0, 301),
(881, 'Executable files and data files', 0, 301),
(882, 'Heavy files and light files', 0, 301),
(883, 'It must have a defined structure that I need to know if I want to read the file', 1, 302),
(884, 'It contains only lines of text', 0, 302),
(885, 'It can never contain strings', 0, 302),
(886, 'It has a variable structure and I can read the data in any order I like', 0, 302),
(887, 'Inherit from the Object class', 0, 303),
(888, 'Implementare l&#039;interfaccia Serializable', 1, 303),
(889, 'Not be a subclass of any superclass', 0, 303),
(890, 'Inherit from the Stream class', 0, 303),
(891, 'Non possono mai dare errori', 0, 304),
(892, 'They can throw exceptions that must be handled with try..catch blocks', 1, 304),
(893, 'They can generate exceptions that we can not even handle, the JVM takes care of the management', 0, 304),
(894, 'In any case, they generate the IOException exception, which must be declared at the beginning of the program', 0, 304),
(895, 'Split', 0, 305),
(896, 'StringTokenizer', 1, 305),
(897, 'StringExplode', 0, 305),
(898, 'StripCharacter', 0, 305),
(899, 'The program has been compiled, creating an executable. I can launch it directly from the operating system', 1, 315),
(900, 'The program needs an interpreter to run', 0, 315),
(901, 'The program was not written by a programmer', 0, 315),
(902, 'The program has been compiled, but you also need an interpreter program to run it', 0, 315),
(903, 'The program is in machine code, practically directly executable by the CPU', 1, 316),
(904, 'The program is in bytecode, it has been compiled, but requires an interpreter to run', 0, 316),
(905, 'The program is in source code, it is executed line by line by an interpreter', 0, 316),
(906, 'The program is in octal code, accesses ROM memory', 0, 316),
(907, 'Runs faster than an interpreted program', 1, 317),
(908, 'Runs slower than an interpreted program', 0, 317),
(909, 'It runs at about the same speed as an interpreted program', 0, 317),
(910, 'It cannot access RAM memory, so it is limited compared to an interpreted program', 0, 317),
(911, 'I can safely use it on Windows too', 0, 318),
(912, 'It will only work for Linux system', 1, 318),
(913, 'It runs faster than Windows, but I use the same compiled program', 0, 318),
(914, 'I can never compile it for Windows too, using a different compiler', 0, 318),
(915, 'You can launch it directly from the operating system', 0, 319),
(916, 'It must install the Python interpreter to run it', 1, 319),
(917, 'He needs to install a compiler, compile the program and then he can run it', 0, 319),
(918, 'It will never be able to launch the Python program', 0, 319),
(919, 'Editor di testo, compilatore o interprete, debugger', 1, 320),
(920, 'Text editor, executable program, project folder', 0, 320),
(921, 'Debugger, compilatore o interprete, ma non l&#039;editor di testo', 0, 320),
(922, 'Text editor only', 0, 320),
(923, 'wile scritto al posto di while', 1, 321),
(924, 'if: without the condition', 0, 321),
(925, 'Calculate the area of the triangle as base * height (without /2)', 0, 321),
(926, 'Attempt to divide by 0', 0, 321),
(927, 'to follow the program one instruction at a time, so as to verify its correct evolution', 0, 322),
(928, 'to control the values assumed by the variables during the execution of the program', 0, 322),
(929, 'establish breakpoints during execution to establish controls', 0, 322),
(930, 'automatic correction of errors (bugs) without the intervention of the programmer', 1, 322),
(931, 'Dare nomi significativi alle variabili', 0, 323),
(932, 'Inserire molti commenti', 0, 323),
(933, 'Write an instruction manual for the end user', 0, 323),
(934, 'Insert as few cycles as possible to avoid making the program heavier', 1, 323),
(935, 'Hide sensitive information within the classroom', 1, 324),
(936, 'Show all information publicly', 0, 324),
(937, 'Share information with other classes', 0, 324),
(938, 'Ignoring information within the classroom', 0, 324),
(939, 'Hide information within the class', 0, 325),
(940, 'Create new instances of a class', 0, 325),
(941, 'Extend the behavior of an existing class', 1, 325),
(942, 'Delete information from the parent class', 0, 325),
(943, 'An instance of an object', 0, 326),
(944, 'A collection of attributes and methods', 1, 326),
(945, 'An instance of a method', 0, 326),
(946, 'A function that operates on data', 0, 326),
(947, 'A class is an instance of an object, while an object is an instance of a class', 0, 327),
(948, 'A class contains methods, while an object contains data', 0, 327),
(949, 'A class is static, while an object is dynamic', 0, 327),
(950, 'A class is abstract, while an object is concrete', 1, 327),
(951, 'Hide a method of a parent class', 0, 328),
(952, 'Add a new method to a class', 0, 328),
(953, 'Override a method of a parent class in a subclass', 1, 328),
(954, 'Delete a method from a class', 0, 328),
(955, 'Nascondendo i dettagli di implementazione', 0, 329),
(956, 'Allowing the reuse of existing code', 1, 329),
(957, 'Aggregating objects into a class', 0, 329),
(958, 'Creando classi intermedie', 0, 329),
(959, '::', 0, 330),
(960, '-&gt;', 0, 330),
(961, '.', 1, 330),
(962, '&gt;&gt;', 0, 330),
(963, 'Funzione', 0, 331),
(964, 'Class', 1, 331),
(965, 'Object', 0, 331),
(966, 'Interfaccia', 0, 331),
(967, 'Composizione', 0, 332),
(968, 'Aggregazione', 0, 332),
(969, 'Inheritance', 1, 332),
(970, 'Associazione', 0, 332),
(971, 'LIFO Last In First Out', 1, 333),
(972, 'FIFO First In First Out', 0, 333),
(973, 'LILO Last In Last Out', 0, 333),
(974, 'Round Robin', 0, 333),
(975, 'LIFO Last In First Out', 0, 334),
(976, 'FIFO First In First Out', 1, 334),
(977, 'Round Robin', 0, 334),
(978, 'FILO First In Last Out', 0, 334),
(979, 'A template from which to create objects', 1, 335),
(980, 'A command to print on screen', 0, 335),
(981, 'A type of iterative loop', 0, 335),
(982, 'An executable file', 0, 335),
(983, 'A variable that describes the state of objects', 1, 336),
(984, 'A method that performs calculations', 0, 336),
(985, 'An external library', 0, 336),
(986, 'A key word for cycles', 0, 336),
(987, 'An operation or behavior of objects', 1, 337),
(988, 'A constant numeric value', 0, 337),
(989, 'A comment on the program', 0, 337),
(990, 'A type of visibility', 0, 337),
(991, 'private', 1, 338),
(992, 'public', 0, 338),
(993, 'protected', 0, 338),
(994, 'static', 0, 338),
(995, 'public', 1, 339),
(996, 'private', 0, 339),
(997, 'protected', 0, 339),
(998, 'default', 0, 339),
(999, 'Hide the internal details of a class showing only what is needed', 1, 340),
(1000, 'Delete all private attributes', 0, 340),
(1001, 'Writing classes without methods', 0, 340),
(1002, 'Use only public variables', 0, 340),
(1003, 'Through public methods such as getters and setters', 1, 341),
(1004, 'Accedendo direttamente dall&#039;esterno', 0, 341),
(1005, 'Always transforming it into public', 0, 341),
(1006, 'Using a for loop', 0, 341),
(1007, 'A special method used to initialize an object', 1, 342),
(1008, 'A required attribute of the class', 0, 342),
(1009, 'A method that always returns int', 0, 342),
(1010, 'A ready-made language class', 0, 342),
(1011, 'It has the same name as the class', 1, 343),
(1012, 'Deve essere sempre public', 0, 343),
(1013, 'Always returns an object', 0, 343),
(1014, 'It can only be written once', 0, 343),
(1015, 'The compiler provides a default parameterless constructor', 1, 344),
(1016, 'The class cannot be instantiated', 0, 344),
(1017, 'All attributes become public', 0, 344),
(1018, 'The methods cannot be used', 0, 344),
(1019, 'The mechanism by which one class derives characteristics from another class', 1, 345),
(1020, 'The ability to create multiple identical objects', 0, 345),
(1021, 'The requirement to use private attributes', 0, 345),
(1022, 'A special kind of builder', 0, 345),
(1023, 'extends', 1, 346),
(1024, 'implements', 0, 346),
(1025, 'inherits', 0, 346),
(1026, 'super', 0, 346),
(1027, 'subclass', 1, 347),
(1028, 'method', 0, 347),
(1029, 'interfaccia', 0, 347),
(1030, 'istanza', 0, 347),
(1031, 'Define multiple methods with the same name but different parameters', 1, 348),
(1032, 'Override an inherited method with the same signature', 0, 348),
(1033, 'Hide a private attribute', 0, 348),
(1034, 'Create a method inside another method', 0, 348),
(1035, 'Override an inherited method in a subclass while maintaining the same signature', 1, 349),
(1036, 'Write two identical constructors', 0, 349),
(1037, 'Use an attribute with the same name in two classes', 0, 349),
(1038, 'Declare a method private', 0, 349),
(1039, 'The methods must have different parameters', 1, 350),
(1040, 'The methods must belong to different classes', 0, 350),
(1041, 'The methods must have different names', 0, 350),
(1042, 'Methods must always return void', 0, 350),
(1043, 'The method in the subclass must have the same signature as the method in the superclass', 1, 351),
(1044, 'The method must necessarily have different parameters', 0, 351),
(1045, 'The method must be static', 0, 351),
(1046, 'The method needs to change its name', 0, 351),
(1047, 'To invoke the constructor of the superclass', 1, 352),
(1048, 'To create a new subclass', 0, 352),
(1049, 'To make an attribute private', 0, 352),
(1050, 'To overload a method', 0, 352),
(1051, 'To protect the internal state of the object', 1, 353);
INSERT INTO `ct_risposte` (`id_risposta`, `risposta`, `corretta`, `fk_domanda`) VALUES
(1052, 'To allow direct access from each classroom', 0, 353),
(1053, 'To force inheritance', 0, 353),
(1054, 'To avoid the use of methods', 0, 353),
(1055, 'Student inherits Person&#039;s accessible attributes and methods', 1, 354),
(1056, 'Person eredita tutto da Student', 0, 354),
(1057, 'Student cannot have his own methods', 0, 354),
(1058, 'Person and Student must be in the same file', 0, 354);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_set_personalizzazioni`
--

CREATE TABLE `ct_set_personalizzazioni` (
  `id_set` int NOT NULL,
  `nome_set` varchar(200) NOT NULL,
  `colore_set` varchar(100) NOT NULL,
  `tipologia` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_set_personalizzazioni`
--

INSERT INTO `ct_set_personalizzazioni` (`id_set`, `nome_set`, `colore_set`, `tipologia`) VALUES
(4, 'Flags', '#edde35', 'Sfondo'),
(5, 'Sfondos', '#2f80ed', 'Sfondo'),
(6, 'BigBackgrounds', '#20a22f', 'BigBackground'),
(7, 'Mythological Animals', '#f70202', 'Pet'),
(8, 'Football Shirts', '#29ff69', 'Equipaggiamento'),
(9, 'Basic Armor', '#7c7f83', 'Equipaggiamento');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_squadra_studente`
--

CREATE TABLE `ct_squadra_studente` (
  `id_squadra_stud` int NOT NULL,
  `fk_squadra` int NOT NULL,
  `fk_studente` int NOT NULL,
  `tot_ese_consegnati` int DEFAULT '0',
  `potere_attivato` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_squadre`
--

CREATE TABLE `ct_squadre` (
  `id_squadra` int NOT NULL,
  `nome_squadra` varchar(200) NOT NULL,
  `emblema_squadra` varchar(300) NOT NULL,
  `fk_classe` int NOT NULL,
  `tipologia` varchar(200) DEFAULT NULL,
  `approvata` int DEFAULT '0',
  `fk_creatore` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studente_personalizzazioni`
--

CREATE TABLE `ct_studente_personalizzazioni` (
  `id_stud_pers` int NOT NULL,
  `fk_studente` int DEFAULT NULL,
  `fk_personalizzazione` int DEFAULT NULL,
  `in_uso` int DEFAULT NULL,
  `primo` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studenti`
--

CREATE TABLE `ct_studenti` (
  `id_studente` int NOT NULL,
  `fk_utente` int NOT NULL,
  `xp` int NOT NULL DEFAULT '0',
  `livello` int NOT NULL DEFAULT '1',
  `fk_personaggio` int DEFAULT NULL,
  `vite` int DEFAULT NULL,
  `mana` int DEFAULT NULL,
  `pot_da_scegliere` int NOT NULL DEFAULT '0',
  `monete` int NOT NULL DEFAULT '0',
  `vite_massime` int DEFAULT NULL,
  `mana_massimo` int DEFAULT NULL,
  `l104` int NOT NULL DEFAULT '0',
  `vite_ultima_visita` int DEFAULT NULL,
  `scudi` int NOT NULL DEFAULT '0',
  `scudi_massimi` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studenti_classi`
--

CREATE TABLE `ct_studenti_classi` (
  `id_stud_classe` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_classe` int NOT NULL,
  `esercizi_cons` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studenti_poteri`
--

CREATE TABLE `ct_studenti_poteri` (
  `id_stud_pot` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_potere` int NOT NULL,
  `usato` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studenti_punizioni`
--

CREATE TABLE `ct_studenti_punizioni` (
  `id_stud_pun` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_punizione` int NOT NULL,
  `link_consegna` varchar(500) NOT NULL,
  `completata` int NOT NULL DEFAULT '0',
  `data_scadenza` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_temporary_dom`
--

CREATE TABLE `ct_temporary_dom` (
  `id_temporary_dom` int NOT NULL,
  `fk_utente` int NOT NULL,
  `fk_domanda` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_tipi_domande`
--

CREATE TABLE `ct_tipi_domande` (
  `id_tipo_domanda` int NOT NULL,
  `tipo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_tipi_domande`
--

INSERT INTO `ct_tipi_domande` (`id_tipo_domanda`, `tipo`) VALUES
(1, 'Risposta aperta'),
(2, 'Scelta multipla'),
(3, 'Risposta multipla'),
(4, 'Esercizio con Numeri');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_tipi_esercizio`
--

CREATE TABLE `ct_tipi_esercizio` (
  `id_tipo_esercizio` int NOT NULL,
  `tipo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_tipi_esercizio`
--

INSERT INTO `ct_tipi_esercizio` (`id_tipo_esercizio`, `tipo`) VALUES
(1, 'Domanda aperta'),
(2, 'Quiz a risposta multipla'),
(3, 'Esercizio da consegnare'),
(4, 'Quiz con risposte multiple e domande aperte');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_tipo_utente`
--

CREATE TABLE `ct_tipo_utente` (
  `id_tipo_utente` int NOT NULL,
  `tipo_utente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_tipo_utente`
--

INSERT INTO `ct_tipo_utente` (`id_tipo_utente`, `tipo_utente`) VALUES
(1, 'amministratore'),
(2, 'studente'),
(3, 'docente');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_traduzioni`
--

CREATE TABLE `ct_traduzioni` (
  `id_traduzione` int NOT NULL,
  `nome_tabella` varchar(50) NOT NULL,
  `nome_campo` varchar(50) NOT NULL,
  `traduzione` text NOT NULL,
  `lingua` varchar(10) NOT NULL,
  `fk_collegamento` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_traduzioni`
--

INSERT INTO `ct_traduzioni` (`id_traduzione`, `nome_tabella`, `nome_campo`, `traduzione`, `lingua`, `fk_collegamento`) VALUES
(1, 'ct_poteri', 'nome_potere', 'Give Life', 'en', 5),
(2, 'ct_poteri', 'descrizione_potere', 'Recharge 1 heart for yourself or a classmate (Also recharges shields)', 'en', 5),
(3, 'ct_poteri', 'nome_potere', 'Extreme sacrifice', 'en', 6),
(4, 'ct_poteri', 'descrizione_potere', 'Recharge 2 hearts for each of your classmates. You lose 2 hearts.', 'en', 6),
(5, 'ct_poteri', 'nome_potere', 'Mana recharge', 'en', 7),
(6, 'ct_poteri', 'descrizione_potere', 'Fully recharge a classmate\'s mana.', 'en', 7),
(7, 'ct_poteri', 'nome_potere', 'Magical storm', 'en', 8),
(8, 'ct_poteri', 'descrizione_potere', 'Recharge 2 mana for each classmate', 'en', 8),
(9, 'ct_poteri', 'nome_potere', 'Increase life', 'en', 30),
(10, 'ct_poteri', 'descrizione_potere', 'Increases your maximum health by 1 permanently. Using this power forgets it and must be chosen again during your next power selection.', 'en', 30),
(11, 'ct_poteri', 'nome_potere', 'Mana increase', 'en', 31),
(12, 'ct_poteri', 'descrizione_potere', 'Permanently increases maximum mana by 1. This power is forgotten after use and must be chosen again before it can be used.', 'en', 31),
(13, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-shield text-secondary fs-2\" style=\"margin-right:0.8rem; font-size:1.3rem\"></i> Defense', 'en', 1),
(14, 'ct_abilita', 'tipologia', 'defense', 'en', 1),
(15, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-coins\" style=\"margin-right:0.8rem; font-size:1.3rem;color: #d4af37;text-shadow: 0 0 4px rgba(212, 175, 55, 0.5);\"></i> Coins earned:', 'en', 2),
(16, 'ct_abilita', 'tipologia', 'coins', 'en', 2),
(17, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-fire-flame-curved\" style=\"color: #2563eb;margin-right:0.8rem; font-size:1.3rem\"></i> Mana per level:', 'en', 3),
(18, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-book\" style=\"color: #2563eb;margin-right:0.8rem; font-size:1.3rem\"></i> XP increase:', 'en', 4),
(19, 'ct_abilita', 'tipologia', 'experience', 'en', 4),
(20, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-fire-flame-curved\" style=\"color: #2563eb;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#2563eb;\">Added mana:</span>', 'en', 5),
(21, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-heart\" style=\"color: #c22131;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#c22131;\">Added hearts:</span>', 'en', 6),
(22, 'ct_abilita', 'tipologia', 'life', 'en', 6),
(23, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-book\" style=\"color: #139ca1;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#139ca1;\">Added XP:</span>', 'en', 7),
(24, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-coins\" style=\"color: #9e6508;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#9e6508;\">Coins added:</span>', 'en', 8),
(25, 'ct_abilita', 'tipologia', 'coins', 'en', 8),
(26, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-toolbox\" style=\"color: #96960e;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#96960e;\">Increased probability epic chest:</span>', 'en', 9),
(27, 'ct_abilita', 'tipologia', 'epic_chest_probability', 'en', 9),
(28, 'ct_abilita', 'testo_abilita', '<i class=\"fa-solid fa-burst\" style=\"color: #2ebd2b;margin-right:0.8rem; font-size:1.2rem\"></i> <span style=\"color:#2ebd2b;\">Increased probability of legendary chest:</span>', 'en', 10),
(29, 'ct_abilita', 'tipologia', 'legendary_chest_chance', 'en', 10),
(30, 'ct_tipi_esercizio', 'tipo', 'Open Question', 'en', 1),
(31, 'ct_tipi_esercizio', 'tipo', 'Multiple choice quiz', 'en', 2),
(32, 'ct_tipi_esercizio', 'tipo', 'Exercise to be delivered', 'en', 3),
(33, 'ct_tipi_esercizio', 'tipo', 'Quiz with multiple choice answers and open questions', 'en', 4),
(34, 'ct_tipi_domande', 'tipo', 'Open Question', 'en', 1),
(35, 'ct_tipi_domande', 'tipo', 'Multiple Choice', 'en', 2),
(36, 'ct_tipi_domande', 'tipo', 'Multiple Answer', 'en', 3),
(37, 'ct_tipi_domande', 'tipo', 'Exercise with Numbers', 'en', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_utente_domande`
--

CREATE TABLE `ct_utente_domande` (
  `id_utente_dom` int NOT NULL,
  `fk_utente` int NOT NULL,
  `fk_domanda` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_utente_domande`
--

INSERT INTO `ct_utente_domande` (`id_utente_dom`, `fk_utente`, `fk_domanda`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 1, 30),
(31, 1, 31),
(32, 1, 32),
(33, 1, 33),
(34, 1, 34),
(35, 1, 35),
(36, 1, 36),
(37, 1, 37),
(38, 1, 38),
(39, 1, 39),
(40, 1, 40),
(41, 1, 41),
(42, 1, 42),
(43, 1, 43),
(44, 1, 44),
(45, 1, 45),
(46, 1, 46),
(47, 1, 47),
(48, 1, 48),
(49, 1, 49),
(50, 1, 50),
(51, 1, 51),
(52, 1, 52),
(53, 1, 53),
(54, 1, 54),
(55, 1, 55),
(56, 1, 56),
(57, 1, 57),
(58, 1, 58),
(59, 1, 59),
(60, 1, 60),
(61, 1, 61),
(62, 1, 62),
(63, 1, 63),
(64, 1, 64),
(65, 1, 65),
(66, 1, 66),
(67, 1, 67),
(68, 1, 68),
(69, 1, 69),
(70, 1, 70),
(71, 1, 71),
(72, 1, 72),
(73, 1, 73),
(74, 1, 74),
(75, 1, 75),
(76, 1, 76),
(77, 1, 77),
(78, 1, 78),
(79, 1, 79),
(80, 1, 80),
(81, 1, 81),
(82, 1, 82),
(83, 1, 83),
(84, 1, 84),
(85, 1, 85),
(86, 1, 86),
(87, 1, 87),
(88, 1, 88),
(89, 1, 89),
(90, 1, 90),
(91, 1, 91),
(92, 1, 92),
(93, 1, 93),
(94, 1, 94),
(95, 1, 95),
(96, 1, 96),
(97, 1, 97),
(98, 1, 98),
(99, 1, 99),
(100, 1, 100),
(101, 1, 101),
(102, 1, 102),
(103, 1, 103),
(104, 1, 104),
(105, 1, 105),
(106, 1, 106),
(107, 1, 107),
(108, 1, 108),
(109, 1, 109),
(110, 1, 110),
(111, 1, 111),
(112, 1, 112),
(113, 1, 113),
(114, 1, 114),
(115, 1, 115),
(116, 1, 116),
(117, 1, 117),
(118, 1, 118),
(119, 1, 119),
(120, 1, 120),
(121, 1, 121),
(122, 1, 122),
(123, 1, 123),
(124, 1, 124),
(125, 1, 125),
(126, 1, 126),
(127, 1, 127),
(128, 1, 128),
(129, 1, 129),
(130, 1, 130),
(131, 1, 131),
(132, 1, 132),
(133, 1, 133),
(134, 1, 134),
(135, 1, 135),
(136, 1, 136),
(137, 1, 137),
(138, 1, 138),
(139, 1, 139),
(140, 1, 140),
(141, 1, 141),
(142, 1, 142),
(143, 1, 143),
(144, 1, 144),
(145, 1, 145),
(146, 1, 146),
(147, 1, 147),
(148, 1, 148),
(149, 1, 149),
(150, 1, 150),
(151, 1, 151),
(152, 1, 152),
(153, 1, 153),
(154, 1, 154),
(155, 1, 155),
(156, 1, 156),
(157, 1, 157),
(158, 1, 158),
(159, 1, 159),
(160, 1, 160),
(161, 1, 161),
(162, 1, 162),
(163, 1, 163),
(164, 1, 164),
(165, 1, 165),
(166, 1, 166),
(167, 1, 167),
(168, 1, 168),
(169, 1, 169),
(170, 1, 170),
(171, 1, 171),
(172, 1, 172),
(173, 1, 173),
(174, 1, 174),
(175, 1, 175),
(176, 1, 176),
(177, 1, 177),
(178, 1, 178),
(179, 1, 179),
(180, 1, 180),
(181, 1, 181),
(182, 1, 182),
(183, 1, 183),
(184, 1, 184),
(185, 1, 185),
(186, 1, 186),
(187, 1, 187),
(188, 1, 188),
(189, 1, 189),
(190, 1, 190),
(191, 1, 191),
(192, 1, 192),
(193, 1, 193),
(194, 1, 194),
(195, 1, 195),
(196, 1, 196),
(197, 1, 197),
(198, 1, 198),
(199, 1, 199),
(200, 1, 200),
(201, 1, 201),
(202, 1, 202),
(203, 1, 203),
(204, 1, 204),
(205, 1, 205),
(206, 1, 206),
(207, 1, 207),
(208, 1, 208),
(209, 1, 209),
(210, 1, 210),
(211, 1, 211),
(212, 1, 212),
(213, 1, 213),
(214, 1, 214),
(215, 1, 215),
(216, 1, 216),
(217, 1, 217),
(218, 1, 218),
(219, 1, 219),
(220, 1, 220),
(221, 1, 221),
(222, 1, 222),
(223, 1, 223),
(224, 1, 224),
(225, 1, 225),
(226, 1, 226),
(227, 1, 227),
(228, 1, 228),
(229, 1, 229),
(230, 1, 230),
(231, 1, 231),
(232, 1, 232),
(233, 1, 233),
(234, 1, 234),
(235, 1, 235),
(236, 1, 236),
(237, 1, 237),
(238, 1, 238),
(239, 1, 239),
(240, 1, 240),
(241, 1, 241),
(242, 1, 242),
(243, 1, 243),
(244, 1, 244),
(245, 1, 245),
(246, 1, 246),
(247, 1, 247),
(248, 1, 248),
(249, 1, 249),
(250, 1, 250),
(251, 1, 251),
(252, 1, 252),
(253, 1, 253),
(254, 1, 254),
(255, 1, 255),
(256, 1, 256),
(257, 1, 257),
(258, 1, 258),
(259, 1, 259),
(260, 1, 260),
(261, 1, 261),
(262, 1, 262),
(263, 1, 263),
(264, 1, 264),
(265, 1, 265),
(266, 1, 266),
(267, 1, 267),
(268, 1, 268),
(269, 1, 269),
(270, 1, 270),
(271, 1, 271),
(272, 1, 272),
(273, 1, 273),
(274, 1, 274),
(275, 1, 275),
(276, 1, 276),
(277, 1, 277),
(278, 1, 278),
(279, 1, 279),
(280, 1, 280),
(281, 1, 281),
(282, 1, 282),
(283, 1, 283),
(284, 1, 284),
(285, 1, 285),
(286, 1, 286),
(287, 1, 287),
(288, 1, 288),
(289, 1, 289),
(290, 1, 290),
(291, 1, 291),
(292, 1, 292),
(293, 1, 293),
(294, 1, 294),
(295, 1, 295),
(296, 1, 296),
(297, 1, 297),
(298, 1, 298),
(299, 1, 299),
(300, 1, 300),
(301, 1, 301),
(302, 1, 302),
(303, 1, 303),
(304, 1, 304),
(305, 1, 305),
(306, 1, 306),
(307, 1, 307),
(308, 1, 308),
(309, 1, 309),
(310, 1, 310),
(311, 1, 311),
(312, 1, 312),
(313, 1, 313),
(314, 1, 314),
(315, 1, 315),
(316, 1, 316),
(317, 1, 317),
(318, 1, 318),
(319, 1, 319),
(320, 1, 320),
(321, 1, 321),
(322, 1, 322),
(323, 1, 323),
(324, 1, 324),
(325, 1, 325),
(326, 1, 326),
(327, 1, 327),
(328, 1, 328),
(329, 1, 329),
(330, 1, 330),
(331, 1, 331),
(332, 1, 332),
(333, 1, 333),
(334, 1, 334),
(335, 1, 335),
(336, 1, 336),
(337, 1, 337),
(338, 1, 338),
(339, 1, 339),
(340, 1, 340),
(341, 1, 341),
(342, 1, 342),
(343, 1, 343),
(344, 1, 344),
(345, 1, 345),
(346, 1, 346),
(347, 1, 347),
(348, 1, 348),
(349, 1, 349),
(350, 1, 350),
(351, 1, 351),
(352, 1, 352),
(353, 1, 353),
(354, 1, 354);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_utenti`
--

CREATE TABLE `ct_utenti` (
  `id_utente` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `codice_conf` varchar(10) DEFAULT NULL,
  `validato` int NOT NULL,
  `fk_tipo_utente` int DEFAULT NULL,
  `ricevi_mail` int NOT NULL DEFAULT '0',
  `template_stampa` varchar(100) DEFAULT NULL,
  `sesso` varchar(2) NOT NULL DEFAULT 'M',
  `API_gemini` varchar(200) DEFAULT NULL,
  `language` varchar(2) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_utenti`
--

INSERT INTO `ct_utenti` (`id_utente`, `nome`, `cognome`, `username`, `password`, `email`, `codice_conf`, `validato`, `fk_tipo_utente`, `ricevi_mail`, `template_stampa`, `sesso`, `API_gemini`, `language`) VALUES
(1, 'Admin', 'Admin', 'admin', '$2y$10$wWiZS9JLeZCAS1NBxL8lWeAasxwkFm9knb6tp.dMYoO298jyu/liC', '', NULL, 1, 1, 1, '', 'M', '', 'en');

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_utenti_classi`
--

CREATE TABLE `ct_utenti_classi` (
  `id_utente_classe` int NOT NULL,
  `fk_utente` int NOT NULL,
  `fk_classe` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_utenti_classi`
--

INSERT INTO `ct_utenti_classi` (`id_utente_classe`, `fk_utente`, `fk_classe`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_utenti_materie`
--

CREATE TABLE `ct_utenti_materie` (
  `id_utmat` int NOT NULL,
  `fk_utente` int NOT NULL,
  `fk_materia` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_utenti_materie`
--

INSERT INTO `ct_utenti_materie` (`id_utmat`, `fk_utente`, `fk_materia`) VALUES
(5, 1, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_utenti_tipi`
--

CREATE TABLE `ct_utenti_tipi` (
  `id_utenti_tipi` int NOT NULL,
  `fk_utente` int NOT NULL,
  `fk_tipo_utente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ct_utenti_tipi`
--

INSERT INTO `ct_utenti_tipi` (`id_utenti_tipi`, `fk_utente`, `fk_tipo_utente`) VALUES
(6, 1, 1),
(7, 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_xp_livello`
--

CREATE TABLE `ct_xp_livello` (
  `id_xp_livello` int NOT NULL,
  `livello` int NOT NULL,
  `xp` int NOT NULL,
  `xp_cumulata` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dump dei dati per la tabella `ct_xp_livello`
--

INSERT INTO `ct_xp_livello` (`id_xp_livello`, `livello`, `xp`, `xp_cumulata`) VALUES
(1, 2, 200, 200),
(2, 3, 220, 420),
(3, 4, 242, 662),
(4, 5, 266, 928),
(5, 6, 293, 1221),
(6, 7, 322, 1543),
(7, 8, 354, 1897),
(8, 9, 390, 2287),
(9, 10, 428, 2715),
(10, 11, 471, 3186),
(11, 12, 518, 3704),
(12, 13, 570, 4274),
(13, 14, 627, 4901),
(14, 15, 690, 5591),
(15, 16, 759, 6350),
(16, 17, 834, 7184),
(17, 18, 918, 8102),
(18, 19, 1010, 9112),
(19, 20, 1111, 10223),
(20, 21, 1222, 11445),
(21, 22, 1344, 12789),
(22, 23, 1478, 14267),
(23, 24, 1626, 15893),
(24, 25, 1788, 17681),
(25, 26, 1967, 19648),
(26, 27, 2164, 21812),
(27, 28, 2380, 24192),
(28, 29, 2618, 26810),
(29, 30, 2880, 29690),
(30, 31, 3168, 32858),
(31, 32, 3484, 36342),
(32, 33, 3833, 40175),
(33, 34, 4216, 44391),
(34, 35, 4637, 49028),
(35, 36, 5101, 54129),
(36, 37, 5611, 59740),
(37, 1, 0, 0),
(38, 38, 6172, 65912),
(39, 39, 6789, 72701),
(40, 40, 7468, 80169),
(41, 41, 8215, 88384),
(42, 42, 9037, 97421),
(43, 43, 9940, 107361),
(44, 44, 10934, 118295),
(45, 45, 12027, 130322),
(46, 46, 13230, 143552),
(47, 47, 14553, 158105),
(48, 48, 16008, 174113),
(49, 49, 17609, 191722),
(50, 50, 19370, 211092),
(51, 51, 21307, 232399),
(52, 52, 23438, 255837),
(53, 53, 25781, 281618),
(54, 54, 28359, 309977),
(55, 55, 31195, 341172),
(56, 56, 34315, 375487),
(57, 57, 37746, 413233),
(58, 58, 41521, 454754),
(59, 59, 45673, 500427),
(60, 60, 50240, 550667),
(61, 61, 55264, 605931),
(62, 62, 60790, 666721),
(63, 63, 66869, 733590),
(64, 64, 73556, 807146),
(65, 65, 80912, 888058);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ct_abilita`
--
ALTER TABLE `ct_abilita`
  ADD PRIMARY KEY (`id_abilita`);

--
-- Indici per le tabelle `ct_abilita_equipaggiamento`
--
ALTER TABLE `ct_abilita_equipaggiamento`
  ADD PRIMARY KEY (`id_abilita_equip`),
  ADD KEY `idx_ct_abilita_equipaggiamento_fk_abilita` (`fk_abilita`),
  ADD KEY `idx_ct_abilita_equipaggiamento_fk_personalizzazione` (`fk_personalizzazione`);

--
-- Indici per le tabelle `ct_alerts`
--
ALTER TABLE `ct_alerts`
  ADD PRIMARY KEY (`id_alert`),
  ADD KEY `idx_ct_alerts_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_alerts_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_anni_scolastici`
--
ALTER TABLE `ct_anni_scolastici`
  ADD PRIMARY KEY (`id_anno`);

--
-- Indici per le tabelle `ct_argomenti`
--
ALTER TABLE `ct_argomenti`
  ADD PRIMARY KEY (`id_argomento`),
  ADD KEY `idx_ct_argomenti_fk_materia` (`fk_materia`);

--
-- Indici per le tabelle `ct_argomenti_kahoot`
--
ALTER TABLE `ct_argomenti_kahoot`
  ADD PRIMARY KEY (`id_arg_kahoot`),
  ADD KEY `idx_ct_argomenti_kahoot_fk_argomento` (`fk_argomento`),
  ADD KEY `idx_ct_argomenti_kahoot_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_badge`
--
ALTER TABLE `ct_badge`
  ADD PRIMARY KEY (`id_badge`),
  ADD KEY `idx_ct_badge_fk_argomento` (`fk_argomento`),
  ADD KEY `idx_ct_badge_fk_utente_creatore` (`fk_utente_creatore`);

--
-- Indici per le tabelle `ct_badge_alunni`
--
ALTER TABLE `ct_badge_alunni`
  ADD PRIMARY KEY (`id_badge_alunno`),
  ADD KEY `idx_ct_badge_alunni_fk_badge` (`fk_badge`),
  ADD KEY `idx_ct_badge_alunni_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_capitoli`
--
ALTER TABLE `ct_capitoli`
  ADD PRIMARY KEY (`id_capitolo`),
  ADD UNIQUE KEY `uq_ct_capitoli_uuid` (`uuid`);

--
-- Indici per le tabelle `ct_capitoli_quest`
--
ALTER TABLE `ct_capitoli_quest`
  ADD PRIMARY KEY (`id_cap_quest`),
  ADD KEY `idx_ct_capitoli_quest_fk_capitolo` (`fk_capitolo`),
  ADD KEY `idx_ct_capitoli_quest_fk_quest` (`fk_quest`);

--
-- Indici per le tabelle `ct_classi`
--
ALTER TABLE `ct_classi`
  ADD PRIMARY KEY (`id_classe`),
  ADD KEY `idx_ct_classi_fk_anno_scolastico` (`fk_anno_scolastico`);

--
-- Indici per le tabelle `ct_classi_esercizi_attivi`
--
ALTER TABLE `ct_classi_esercizi_attivi`
  ADD PRIMARY KEY (`id_attivi`),
  ADD KEY `idx_ct_classi_esercizi_attivi_fk_capitolo` (`fk_capitolo`),
  ADD KEY `idx_ct_classi_esercizi_attivi_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_classi_esercizi_attivi_fk_esercizio` (`fk_esercizio`);

--
-- Indici per le tabelle `ct_classi_quest`
--
ALTER TABLE `ct_classi_quest`
  ADD PRIMARY KEY (`id_classe_quest`),
  ADD KEY `idx_ct_classi_quest_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_classi_quest_fk_quest` (`fk_quest`);

--
-- Indici per le tabelle `ct_consegne_studenti`
--
ALTER TABLE `ct_consegne_studenti`
  ADD PRIMARY KEY (`id_consegna`),
  ADD KEY `idx_ct_consegne_studenti_fk_esercizio` (`fk_esercizio`),
  ADD KEY `idx_ct_consegne_studenti_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_domande`
--
ALTER TABLE `ct_domande`
  ADD PRIMARY KEY (`id_domanda`),
  ADD KEY `idx_ct_domande_fk_argomento` (`fk_argomento`),
  ADD KEY `idx_ct_domande_fk_libro` (`fk_libro`),
  ADD KEY `idx_ct_domande_fk_tipo_domanda` (`fk_tipo_domanda`),
  ADD KEY `idx_ct_domande_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_esercizi`
--
ALTER TABLE `ct_esercizi`
  ADD PRIMARY KEY (`id_esercizio`),
  ADD UNIQUE KEY `uq_ct_esercizi_uuid` (`uuid`),
  ADD KEY `idx_ct_esercizi_fk_argomento` (`fk_argomento`),
  ADD KEY `idx_ct_esercizi_fk_materiale` (`fk_materiale`);

--
-- Indici per le tabelle `ct_esercizio_domande`
--
ALTER TABLE `ct_esercizio_domande`
  ADD PRIMARY KEY (`id_ese_dom`),
  ADD KEY `idx_ct_esercizio_domande_fk_domanda` (`fk_domanda`),
  ADD KEY `idx_ct_esercizio_domande_fk_esercizio` (`fk_esercizio`),
  ADD KEY `idx_ct_esercizio_domande_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_esercizio_materiali`
--
ALTER TABLE `ct_esercizio_materiali`
  ADD PRIMARY KEY (`id_ese_mat`),
  ADD KEY `idx_ct_esercizio_materiali_fk_esercizio` (`fk_esercizio`),
  ADD KEY `idx_ct_esercizio_materiali_fk_materiale` (`fk_materiale`);

--
-- Indici per le tabelle `ct_esercizio_risposte`
--
ALTER TABLE `ct_esercizio_risposte`
  ADD PRIMARY KEY (`id_ese_risp`),
  ADD KEY `idx_ct_esercizio_risposte_fk_consegna` (`fk_consegna`),
  ADD KEY `idx_ct_esercizio_risposte_fk_domanda` (`fk_domanda`),
  ADD KEY `idx_ct_esercizio_risposte_fk_esercizio` (`fk_esercizio`),
  ADD KEY `idx_ct_esercizio_risposte_fk_risposta` (`fk_risposta`),
  ADD KEY `idx_ct_esercizio_risposte_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_esercizi_quest`
--
ALTER TABLE `ct_esercizi_quest`
  ADD PRIMARY KEY (`id_ese_quest`),
  ADD KEY `idx_ct_esercizi_quest_fk_capitolo` (`fk_capitolo`),
  ADD KEY `idx_ct_esercizi_quest_fk_esercizio` (`fk_esercizio`);

--
-- Indici per le tabelle `ct_forzieri_vinti`
--
ALTER TABLE `ct_forzieri_vinti`
  ADD PRIMARY KEY (`id_forziere`),
  ADD KEY `idx_ct_forzieri_vinti_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_giornate_sconti`
--
ALTER TABLE `ct_giornate_sconti`
  ADD PRIMARY KEY (`id_giornata`);

--
-- Indici per le tabelle `ct_griglie_valutazione`
--
ALTER TABLE `ct_griglie_valutazione`
  ADD PRIMARY KEY (`id_griglia`),
  ADD KEY `idx_ct_griglie_valutazione_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_inviti_squadre`
--
ALTER TABLE `ct_inviti_squadre`
  ADD PRIMARY KEY (`id_invito`),
  ADD KEY `idx_ct_inviti_squadre_fk_squadra` (`fk_squadra`),
  ADD KEY `idx_ct_inviti_squadre_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_libri_testo`
--
ALTER TABLE `ct_libri_testo`
  ADD PRIMARY KEY (`id_libro_testo`);

--
-- Indici per le tabelle `ct_mail_abilitate`
--
ALTER TABLE `ct_mail_abilitate`
  ADD PRIMARY KEY (`id_mail_abilitata`);

--
-- Indici per le tabelle `ct_materiali`
--
ALTER TABLE `ct_materiali`
  ADD PRIMARY KEY (`id_materiale`),
  ADD KEY `idx_ct_materiali_fk_argomento` (`fk_argomento`),
  ADD KEY `idx_ct_materiali_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_materie`
--
ALTER TABLE `ct_materie`
  ADD PRIMARY KEY (`id_materia`);

--
-- Indici per le tabelle `ct_messages`
--
ALTER TABLE `ct_messages`
  ADD PRIMARY KEY (`id_messaggio`),
  ADD KEY `idx_ct_messages_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_messages_fk_docente` (`fk_docente`),
  ADD KEY `idx_ct_messages_fk_last_msg_rel` (`fk_last_msg_rel`),
  ADD KEY `idx_ct_messages_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_personaggi`
--
ALTER TABLE `ct_personaggi`
  ADD PRIMARY KEY (`id_personaggio`),
  ADD UNIQUE KEY `uq_ct_personaggi_uuid` (`uuid`),
  ADD KEY `idx_ct_personaggi_fk_classe` (`fk_classe`);

--
-- Indici per le tabelle `ct_personalizzazioni`
--
ALTER TABLE `ct_personalizzazioni`
  ADD PRIMARY KEY (`id_personalizzazione`),
  ADD UNIQUE KEY `uq_ct_personalizzazioni_uuid` (`uuid`),
  ADD KEY `idx_ct_personalizzazioni_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_personalizzazioni_fk_personaggio` (`fk_personaggio`),
  ADD KEY `idx_ct_personalizzazioni_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_plugin_classe`
--
ALTER TABLE `ct_plugin_classe`
  ADD PRIMARY KEY (`id_plugin_classe`),
  ADD UNIQUE KEY `uq_ct_plugin_classe_plugin_classe` (`fk_plugin`,`fk_classe`);

--
-- Indici per le tabelle `ct_poteri`
--
ALTER TABLE `ct_poteri`
  ADD PRIMARY KEY (`id_potere`),
  ADD KEY `idx_ct_poteri_fk_classe` (`fk_classe`);

--
-- Indici per le tabelle `ct_punizioni`
--
ALTER TABLE `ct_punizioni`
  ADD PRIMARY KEY (`id_punizione`),
  ADD KEY `idx_ct_punizioni_fk_classe` (`fk_classe`);

--
-- Indici per le tabelle `ct_quest`
--
ALTER TABLE `ct_quest`
  ADD PRIMARY KEY (`id_quest`),
  ADD UNIQUE KEY `uq_ct_quest_uuid` (`uuid`);

--
-- Indici per le tabelle `ct_quiz`
--
ALTER TABLE `ct_quiz`
  ADD PRIMARY KEY (`id_quiz`),
  ADD KEY `idx_ct_quiz_fk_griglia` (`fk_griglia`),
  ADD KEY `idx_ct_quiz_fk_materia` (`fk_materia`),
  ADD KEY `idx_ct_quiz_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_quiz_argomenti`
--
ALTER TABLE `ct_quiz_argomenti`
  ADD PRIMARY KEY (`id_quiz_argomento`),
  ADD KEY `idx_ct_quiz_argomenti_fk_argomento` (`fk_argomento`),
  ADD KEY `idx_ct_quiz_argomenti_fk_quiz` (`fk_quiz`);

--
-- Indici per le tabelle `ct_quiz_domande`
--
ALTER TABLE `ct_quiz_domande`
  ADD PRIMARY KEY (`id_quiz_domanda`),
  ADD KEY `idx_ct_quiz_domande_fk_domanda` (`fk_domanda`),
  ADD KEY `idx_ct_quiz_domande_fk_quiz` (`fk_quiz`);

--
-- Indici per le tabelle `ct_quiz_tipo_domande`
--
ALTER TABLE `ct_quiz_tipo_domande`
  ADD PRIMARY KEY (`id_quiz_tipo`),
  ADD KEY `idx_ct_quiz_tipo_domande_fk_quiz` (`fk_quiz`),
  ADD KEY `idx_ct_quiz_tipo_domande_fk_tipo_domande` (`fk_tipo_domande`);

--
-- Indici per le tabelle `ct_risposte`
--
ALTER TABLE `ct_risposte`
  ADD PRIMARY KEY (`id_risposta`),
  ADD KEY `idx_ct_risposte_fk_domanda` (`fk_domanda`);

--
-- Indici per le tabelle `ct_set_personalizzazioni`
--
ALTER TABLE `ct_set_personalizzazioni`
  ADD PRIMARY KEY (`id_set`);

--
-- Indici per le tabelle `ct_squadra_studente`
--
ALTER TABLE `ct_squadra_studente`
  ADD PRIMARY KEY (`id_squadra_stud`),
  ADD KEY `idx_ct_squadra_studente_fk_squadra` (`fk_squadra`),
  ADD KEY `idx_ct_squadra_studente_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_squadre`
--
ALTER TABLE `ct_squadre`
  ADD PRIMARY KEY (`id_squadra`),
  ADD KEY `idx_ct_squadre_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_squadre_fk_creatore` (`fk_creatore`);

--
-- Indici per le tabelle `ct_studente_personalizzazioni`
--
ALTER TABLE `ct_studente_personalizzazioni`
  ADD PRIMARY KEY (`id_stud_pers`),
  ADD KEY `idx_ct_studente_personalizzazioni_fk_personalizzazione` (`fk_personalizzazione`),
  ADD KEY `idx_ct_studente_personalizzazioni_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_studenti`
--
ALTER TABLE `ct_studenti`
  ADD PRIMARY KEY (`id_studente`),
  ADD KEY `idx_ct_studenti_fk_personaggio` (`fk_personaggio`),
  ADD KEY `idx_ct_studenti_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_studenti_classi`
--
ALTER TABLE `ct_studenti_classi`
  ADD PRIMARY KEY (`id_stud_classe`),
  ADD KEY `idx_ct_studenti_classi_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_studenti_classi_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_studenti_poteri`
--
ALTER TABLE `ct_studenti_poteri`
  ADD PRIMARY KEY (`id_stud_pot`),
  ADD KEY `idx_ct_studenti_poteri_fk_potere` (`fk_potere`),
  ADD KEY `idx_ct_studenti_poteri_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_studenti_punizioni`
--
ALTER TABLE `ct_studenti_punizioni`
  ADD PRIMARY KEY (`id_stud_pun`),
  ADD KEY `idx_ct_studenti_punizioni_fk_punizione` (`fk_punizione`),
  ADD KEY `idx_ct_studenti_punizioni_fk_studente` (`fk_studente`);

--
-- Indici per le tabelle `ct_temporary_dom`
--
ALTER TABLE `ct_temporary_dom`
  ADD PRIMARY KEY (`id_temporary_dom`),
  ADD KEY `idx_ct_temporary_dom_fk_domanda` (`fk_domanda`),
  ADD KEY `idx_ct_temporary_dom_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_tipi_domande`
--
ALTER TABLE `ct_tipi_domande`
  ADD PRIMARY KEY (`id_tipo_domanda`);

--
-- Indici per le tabelle `ct_tipi_esercizio`
--
ALTER TABLE `ct_tipi_esercizio`
  ADD PRIMARY KEY (`id_tipo_esercizio`);

--
-- Indici per le tabelle `ct_tipo_utente`
--
ALTER TABLE `ct_tipo_utente`
  ADD PRIMARY KEY (`id_tipo_utente`);

--
-- Indici per le tabelle `ct_traduzioni`
--
ALTER TABLE `ct_traduzioni`
  ADD PRIMARY KEY (`id_traduzione`);

--
-- Indici per le tabelle `ct_utente_domande`
--
ALTER TABLE `ct_utente_domande`
  ADD PRIMARY KEY (`id_utente_dom`),
  ADD KEY `idx_ct_utente_domande_fk_domanda` (`fk_domanda`),
  ADD KEY `idx_ct_utente_domande_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_utenti`
--
ALTER TABLE `ct_utenti`
  ADD PRIMARY KEY (`id_utente`),
  ADD KEY `idx_ct_utenti_fk_tipo_utente` (`fk_tipo_utente`);

--
-- Indici per le tabelle `ct_utenti_classi`
--
ALTER TABLE `ct_utenti_classi`
  ADD PRIMARY KEY (`id_utente_classe`),
  ADD KEY `idx_ct_utenti_classi_fk_classe` (`fk_classe`),
  ADD KEY `idx_ct_utenti_classi_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_utenti_materie`
--
ALTER TABLE `ct_utenti_materie`
  ADD PRIMARY KEY (`id_utmat`),
  ADD KEY `idx_ct_utenti_materie_fk_materia` (`fk_materia`),
  ADD KEY `idx_ct_utenti_materie_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_utenti_tipi`
--
ALTER TABLE `ct_utenti_tipi`
  ADD PRIMARY KEY (`id_utenti_tipi`),
  ADD KEY `idx_ct_utenti_tipi_fk_tipo_utente` (`fk_tipo_utente`),
  ADD KEY `idx_ct_utenti_tipi_fk_utente` (`fk_utente`);

--
-- Indici per le tabelle `ct_xp_livello`
--
ALTER TABLE `ct_xp_livello`
  ADD PRIMARY KEY (`id_xp_livello`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ct_abilita`
--
ALTER TABLE `ct_abilita`
  MODIFY `id_abilita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `ct_abilita_equipaggiamento`
--
ALTER TABLE `ct_abilita_equipaggiamento`
  MODIFY `id_abilita_equip` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT per la tabella `ct_alerts`
--
ALTER TABLE `ct_alerts`
  MODIFY `id_alert` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_anni_scolastici`
--
ALTER TABLE `ct_anni_scolastici`
  MODIFY `id_anno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `ct_argomenti`
--
ALTER TABLE `ct_argomenti`
  MODIFY `id_argomento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT per la tabella `ct_argomenti_kahoot`
--
ALTER TABLE `ct_argomenti_kahoot`
  MODIFY `id_arg_kahoot` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_badge`
--
ALTER TABLE `ct_badge`
  MODIFY `id_badge` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT per la tabella `ct_badge_alunni`
--
ALTER TABLE `ct_badge_alunni`
  MODIFY `id_badge_alunno` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_capitoli`
--
ALTER TABLE `ct_capitoli`
  MODIFY `id_capitolo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `ct_capitoli_quest`
--
ALTER TABLE `ct_capitoli_quest`
  MODIFY `id_cap_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `ct_classi`
--
ALTER TABLE `ct_classi`
  MODIFY `id_classe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_classi_esercizi_attivi`
--
ALTER TABLE `ct_classi_esercizi_attivi`
  MODIFY `id_attivi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT per la tabella `ct_classi_quest`
--
ALTER TABLE `ct_classi_quest`
  MODIFY `id_classe_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `ct_consegne_studenti`
--
ALTER TABLE `ct_consegne_studenti`
  MODIFY `id_consegna` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_domande`
--
ALTER TABLE `ct_domande`
  MODIFY `id_domanda` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT per la tabella `ct_esercizi`
--
ALTER TABLE `ct_esercizi`
  MODIFY `id_esercizio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT per la tabella `ct_esercizio_domande`
--
ALTER TABLE `ct_esercizio_domande`
  MODIFY `id_ese_dom` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_esercizio_materiali`
--
ALTER TABLE `ct_esercizio_materiali`
  MODIFY `id_ese_mat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_esercizio_risposte`
--
ALTER TABLE `ct_esercizio_risposte`
  MODIFY `id_ese_risp` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_esercizi_quest`
--
ALTER TABLE `ct_esercizi_quest`
  MODIFY `id_ese_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT per la tabella `ct_forzieri_vinti`
--
ALTER TABLE `ct_forzieri_vinti`
  MODIFY `id_forziere` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_giornate_sconti`
--
ALTER TABLE `ct_giornate_sconti`
  MODIFY `id_giornata` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_griglie_valutazione`
--
ALTER TABLE `ct_griglie_valutazione`
  MODIFY `id_griglia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_inviti_squadre`
--
ALTER TABLE `ct_inviti_squadre`
  MODIFY `id_invito` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_libri_testo`
--
ALTER TABLE `ct_libri_testo`
  MODIFY `id_libro_testo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `ct_mail_abilitate`
--
ALTER TABLE `ct_mail_abilitate`
  MODIFY `id_mail_abilitata` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_materiali`
--
ALTER TABLE `ct_materiali`
  MODIFY `id_materiale` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ct_materie`
--
ALTER TABLE `ct_materie`
  MODIFY `id_materia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_messages`
--
ALTER TABLE `ct_messages`
  MODIFY `id_messaggio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_personaggi`
--
ALTER TABLE `ct_personaggi`
  MODIFY `id_personaggio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `ct_personalizzazioni`
--
ALTER TABLE `ct_personalizzazioni`
  MODIFY `id_personalizzazione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT per la tabella `ct_plugin_classe`
--
ALTER TABLE `ct_plugin_classe`
  MODIFY `id_plugin_classe` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_poteri`
--
ALTER TABLE `ct_poteri`
  MODIFY `id_potere` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT per la tabella `ct_punizioni`
--
ALTER TABLE `ct_punizioni`
  MODIFY `id_punizione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `ct_quest`
--
ALTER TABLE `ct_quest`
  MODIFY `id_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `ct_quiz`
--
ALTER TABLE `ct_quiz`
  MODIFY `id_quiz` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `ct_quiz_argomenti`
--
ALTER TABLE `ct_quiz_argomenti`
  MODIFY `id_quiz_argomento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `ct_quiz_domande`
--
ALTER TABLE `ct_quiz_domande`
  MODIFY `id_quiz_domanda` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_quiz_tipo_domande`
--
ALTER TABLE `ct_quiz_tipo_domande`
  MODIFY `id_quiz_tipo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_risposte`
--
ALTER TABLE `ct_risposte`
  MODIFY `id_risposta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1059;

--
-- AUTO_INCREMENT per la tabella `ct_set_personalizzazioni`
--
ALTER TABLE `ct_set_personalizzazioni`
  MODIFY `id_set` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `ct_squadra_studente`
--
ALTER TABLE `ct_squadra_studente`
  MODIFY `id_squadra_stud` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_squadre`
--
ALTER TABLE `ct_squadre`
  MODIFY `id_squadra` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_studente_personalizzazioni`
--
ALTER TABLE `ct_studente_personalizzazioni`
  MODIFY `id_stud_pers` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_studenti`
--
ALTER TABLE `ct_studenti`
  MODIFY `id_studente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `ct_studenti_classi`
--
ALTER TABLE `ct_studenti_classi`
  MODIFY `id_stud_classe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `ct_studenti_poteri`
--
ALTER TABLE `ct_studenti_poteri`
  MODIFY `id_stud_pot` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `ct_studenti_punizioni`
--
ALTER TABLE `ct_studenti_punizioni`
  MODIFY `id_stud_pun` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_temporary_dom`
--
ALTER TABLE `ct_temporary_dom`
  MODIFY `id_temporary_dom` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_tipi_esercizio`
--
ALTER TABLE `ct_tipi_esercizio`
  MODIFY `id_tipo_esercizio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_tipo_utente`
--
ALTER TABLE `ct_tipo_utente`
  MODIFY `id_tipo_utente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ct_traduzioni`
--
ALTER TABLE `ct_traduzioni`
  MODIFY `id_traduzione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT per la tabella `ct_utente_domande`
--
ALTER TABLE `ct_utente_domande`
  MODIFY `id_utente_dom` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT per la tabella `ct_utenti`
--
ALTER TABLE `ct_utenti`
  MODIFY `id_utente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `ct_utenti_classi`
--
ALTER TABLE `ct_utenti_classi`
  MODIFY `id_utente_classe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `ct_utenti_materie`
--
ALTER TABLE `ct_utenti_materie`
  MODIFY `id_utmat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `ct_utenti_tipi`
--
ALTER TABLE `ct_utenti_tipi`
  MODIFY `id_utenti_tipi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `ct_xp_livello`
--
ALTER TABLE `ct_xp_livello`
  MODIFY `id_xp_livello` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ct_abilita_equipaggiamento`
--
ALTER TABLE `ct_abilita_equipaggiamento`
  ADD CONSTRAINT `fk_ct_abilita_equipaggiamento_fk_abilita` FOREIGN KEY (`fk_abilita`) REFERENCES `ct_abilita` (`id_abilita`),
  ADD CONSTRAINT `fk_ct_abilita_equipaggiamento_fk_personalizzazione` FOREIGN KEY (`fk_personalizzazione`) REFERENCES `ct_personalizzazioni` (`id_personalizzazione`);

--
-- Limiti per la tabella `ct_alerts`
--
ALTER TABLE `ct_alerts`
  ADD CONSTRAINT `fk_ct_alerts_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`);

--
-- Limiti per la tabella `ct_argomenti`
--
ALTER TABLE `ct_argomenti`
  ADD CONSTRAINT `fk_ct_argomenti_fk_materia` FOREIGN KEY (`fk_materia`) REFERENCES `ct_materie` (`id_materia`);

--
-- Limiti per la tabella `ct_argomenti_kahoot`
--
ALTER TABLE `ct_argomenti_kahoot`
  ADD CONSTRAINT `fk_ct_argomenti_kahoot_fk_argomento` FOREIGN KEY (`fk_argomento`) REFERENCES `ct_argomenti` (`id_argomento`),
  ADD CONSTRAINT `fk_ct_argomenti_kahoot_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_badge`
--
ALTER TABLE `ct_badge`
  ADD CONSTRAINT `fk_ct_badge_fk_argomento` FOREIGN KEY (`fk_argomento`) REFERENCES `ct_argomenti` (`id_argomento`),
  ADD CONSTRAINT `fk_ct_badge_fk_utente_creatore` FOREIGN KEY (`fk_utente_creatore`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_badge_alunni`
--
ALTER TABLE `ct_badge_alunni`
  ADD CONSTRAINT `fk_ct_badge_alunni_fk_badge` FOREIGN KEY (`fk_badge`) REFERENCES `ct_badge` (`id_badge`),
  ADD CONSTRAINT `fk_ct_badge_alunni_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_capitoli_quest`
--
ALTER TABLE `ct_capitoli_quest`
  ADD CONSTRAINT `fk_ct_capitoli_quest_fk_capitolo` FOREIGN KEY (`fk_capitolo`) REFERENCES `ct_capitoli` (`id_capitolo`),
  ADD CONSTRAINT `fk_ct_capitoli_quest_fk_quest` FOREIGN KEY (`fk_quest`) REFERENCES `ct_quest` (`id_quest`);

--
-- Limiti per la tabella `ct_classi`
--
ALTER TABLE `ct_classi`
  ADD CONSTRAINT `fk_ct_classi_fk_anno_scolastico` FOREIGN KEY (`fk_anno_scolastico`) REFERENCES `ct_anni_scolastici` (`id_anno`);

--
-- Limiti per la tabella `ct_classi_esercizi_attivi`
--
ALTER TABLE `ct_classi_esercizi_attivi`
  ADD CONSTRAINT `fk_ct_classi_esercizi_attivi_fk_capitolo` FOREIGN KEY (`fk_capitolo`) REFERENCES `ct_capitoli` (`id_capitolo`),
  ADD CONSTRAINT `fk_ct_classi_esercizi_attivi_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`),
  ADD CONSTRAINT `fk_ct_classi_esercizi_attivi_fk_esercizio` FOREIGN KEY (`fk_esercizio`) REFERENCES `ct_esercizi` (`id_esercizio`);

--
-- Limiti per la tabella `ct_classi_quest`
--
ALTER TABLE `ct_classi_quest`
  ADD CONSTRAINT `fk_ct_classi_quest_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`),
  ADD CONSTRAINT `fk_ct_classi_quest_fk_quest` FOREIGN KEY (`fk_quest`) REFERENCES `ct_quest` (`id_quest`);

--
-- Limiti per la tabella `ct_consegne_studenti`
--
ALTER TABLE `ct_consegne_studenti`
  ADD CONSTRAINT `fk_ct_consegne_studenti_fk_esercizio` FOREIGN KEY (`fk_esercizio`) REFERENCES `ct_esercizi` (`id_esercizio`),
  ADD CONSTRAINT `fk_ct_consegne_studenti_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_domande`
--
ALTER TABLE `ct_domande`
  ADD CONSTRAINT `fk_ct_domande_fk_argomento` FOREIGN KEY (`fk_argomento`) REFERENCES `ct_argomenti` (`id_argomento`),
  ADD CONSTRAINT `fk_ct_domande_fk_tipo_domanda` FOREIGN KEY (`fk_tipo_domanda`) REFERENCES `ct_tipi_domande` (`id_tipo_domanda`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_ct_domande_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_esercizi`
--
ALTER TABLE `ct_esercizi`
  ADD CONSTRAINT `fk_ct_esercizi_fk_argomento` FOREIGN KEY (`fk_argomento`) REFERENCES `ct_argomenti` (`id_argomento`);

--
-- Limiti per la tabella `ct_esercizio_domande`
--
ALTER TABLE `ct_esercizio_domande`
  ADD CONSTRAINT `fk_ct_esercizio_domande_fk_domanda` FOREIGN KEY (`fk_domanda`) REFERENCES `ct_domande` (`id_domanda`),
  ADD CONSTRAINT `fk_ct_esercizio_domande_fk_esercizio` FOREIGN KEY (`fk_esercizio`) REFERENCES `ct_esercizi` (`id_esercizio`),
  ADD CONSTRAINT `fk_ct_esercizio_domande_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_esercizio_materiali`
--
ALTER TABLE `ct_esercizio_materiali`
  ADD CONSTRAINT `fk_ct_esercizio_materiali_fk_esercizio` FOREIGN KEY (`fk_esercizio`) REFERENCES `ct_esercizi` (`id_esercizio`),
  ADD CONSTRAINT `fk_ct_esercizio_materiali_fk_materiale` FOREIGN KEY (`fk_materiale`) REFERENCES `ct_materiali` (`id_materiale`);

--
-- Limiti per la tabella `ct_esercizio_risposte`
--
ALTER TABLE `ct_esercizio_risposte`
  ADD CONSTRAINT `fk_ct_esercizio_risposte_fk_consegna` FOREIGN KEY (`fk_consegna`) REFERENCES `ct_consegne_studenti` (`id_consegna`),
  ADD CONSTRAINT `fk_ct_esercizio_risposte_fk_domanda` FOREIGN KEY (`fk_domanda`) REFERENCES `ct_domande` (`id_domanda`),
  ADD CONSTRAINT `fk_ct_esercizio_risposte_fk_esercizio` FOREIGN KEY (`fk_esercizio`) REFERENCES `ct_esercizi` (`id_esercizio`),
  ADD CONSTRAINT `fk_ct_esercizio_risposte_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_esercizi_quest`
--
ALTER TABLE `ct_esercizi_quest`
  ADD CONSTRAINT `fk_ct_esercizi_quest_fk_capitolo` FOREIGN KEY (`fk_capitolo`) REFERENCES `ct_capitoli` (`id_capitolo`),
  ADD CONSTRAINT `fk_ct_esercizi_quest_fk_esercizio` FOREIGN KEY (`fk_esercizio`) REFERENCES `ct_esercizi` (`id_esercizio`);

--
-- Limiti per la tabella `ct_forzieri_vinti`
--
ALTER TABLE `ct_forzieri_vinti`
  ADD CONSTRAINT `fk_ct_forzieri_vinti_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_griglie_valutazione`
--
ALTER TABLE `ct_griglie_valutazione`
  ADD CONSTRAINT `fk_ct_griglie_valutazione_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_inviti_squadre`
--
ALTER TABLE `ct_inviti_squadre`
  ADD CONSTRAINT `fk_ct_inviti_squadre_fk_squadra` FOREIGN KEY (`fk_squadra`) REFERENCES `ct_squadre` (`id_squadra`),
  ADD CONSTRAINT `fk_ct_inviti_squadre_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_materiali`
--
ALTER TABLE `ct_materiali`
  ADD CONSTRAINT `fk_ct_materiali_fk_argomento` FOREIGN KEY (`fk_argomento`) REFERENCES `ct_argomenti` (`id_argomento`),
  ADD CONSTRAINT `fk_ct_materiali_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_messages`
--
ALTER TABLE `ct_messages`
  ADD CONSTRAINT `fk_ct_messages_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`),
  ADD CONSTRAINT `fk_ct_messages_fk_docente` FOREIGN KEY (`fk_docente`) REFERENCES `ct_utenti` (`id_utente`),
  ADD CONSTRAINT `fk_ct_messages_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_personaggi`
--
ALTER TABLE `ct_personaggi`
  ADD CONSTRAINT `fk_ct_personaggi_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`);

--
-- Limiti per la tabella `ct_personalizzazioni`
--
ALTER TABLE `ct_personalizzazioni`
  ADD CONSTRAINT `fk_ct_personalizzazioni_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`);

--
-- Limiti per la tabella `ct_poteri`
--
ALTER TABLE `ct_poteri`
  ADD CONSTRAINT `fk_ct_poteri_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`);

--
-- Limiti per la tabella `ct_punizioni`
--
ALTER TABLE `ct_punizioni`
  ADD CONSTRAINT `fk_ct_punizioni_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`);

--
-- Limiti per la tabella `ct_quiz`
--
ALTER TABLE `ct_quiz`
  ADD CONSTRAINT `fk_ct_quiz_fk_materia` FOREIGN KEY (`fk_materia`) REFERENCES `ct_materie` (`id_materia`),
  ADD CONSTRAINT `fk_ct_quiz_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_quiz_argomenti`
--
ALTER TABLE `ct_quiz_argomenti`
  ADD CONSTRAINT `fk_ct_quiz_argomenti_fk_argomento` FOREIGN KEY (`fk_argomento`) REFERENCES `ct_argomenti` (`id_argomento`),
  ADD CONSTRAINT `fk_ct_quiz_argomenti_fk_quiz` FOREIGN KEY (`fk_quiz`) REFERENCES `ct_quiz` (`id_quiz`);

--
-- Limiti per la tabella `ct_quiz_domande`
--
ALTER TABLE `ct_quiz_domande`
  ADD CONSTRAINT `fk_ct_quiz_domande_fk_domanda` FOREIGN KEY (`fk_domanda`) REFERENCES `ct_domande` (`id_domanda`),
  ADD CONSTRAINT `fk_ct_quiz_domande_fk_quiz` FOREIGN KEY (`fk_quiz`) REFERENCES `ct_quiz` (`id_quiz`);

--
-- Limiti per la tabella `ct_quiz_tipo_domande`
--
ALTER TABLE `ct_quiz_tipo_domande`
  ADD CONSTRAINT `fk_ct_quiz_tipo_domande_fk_quiz` FOREIGN KEY (`fk_quiz`) REFERENCES `ct_quiz` (`id_quiz`);

--
-- Limiti per la tabella `ct_risposte`
--
ALTER TABLE `ct_risposte`
  ADD CONSTRAINT `fk_ct_risposte_fk_domanda` FOREIGN KEY (`fk_domanda`) REFERENCES `ct_domande` (`id_domanda`);

--
-- Limiti per la tabella `ct_squadra_studente`
--
ALTER TABLE `ct_squadra_studente`
  ADD CONSTRAINT `fk_ct_squadra_studente_fk_squadra` FOREIGN KEY (`fk_squadra`) REFERENCES `ct_squadre` (`id_squadra`),
  ADD CONSTRAINT `fk_ct_squadra_studente_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_squadre`
--
ALTER TABLE `ct_squadre`
  ADD CONSTRAINT `fk_ct_squadre_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`);

--
-- Limiti per la tabella `ct_studente_personalizzazioni`
--
ALTER TABLE `ct_studente_personalizzazioni`
  ADD CONSTRAINT `fk_ct_studente_personalizzazioni_fk_personalizzazione` FOREIGN KEY (`fk_personalizzazione`) REFERENCES `ct_personalizzazioni` (`id_personalizzazione`),
  ADD CONSTRAINT `fk_ct_studente_personalizzazioni_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_studenti`
--
ALTER TABLE `ct_studenti`
  ADD CONSTRAINT `fk_ct_studenti_fk_personaggio` FOREIGN KEY (`fk_personaggio`) REFERENCES `ct_personaggi` (`id_personaggio`),
  ADD CONSTRAINT `fk_ct_studenti_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_studenti_classi`
--
ALTER TABLE `ct_studenti_classi`
  ADD CONSTRAINT `fk_ct_studenti_classi_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`),
  ADD CONSTRAINT `fk_ct_studenti_classi_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_studenti_poteri`
--
ALTER TABLE `ct_studenti_poteri`
  ADD CONSTRAINT `fk_ct_studenti_poteri_fk_potere` FOREIGN KEY (`fk_potere`) REFERENCES `ct_poteri` (`id_potere`),
  ADD CONSTRAINT `fk_ct_studenti_poteri_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_studenti_punizioni`
--
ALTER TABLE `ct_studenti_punizioni`
  ADD CONSTRAINT `fk_ct_studenti_punizioni_fk_punizione` FOREIGN KEY (`fk_punizione`) REFERENCES `ct_punizioni` (`id_punizione`),
  ADD CONSTRAINT `fk_ct_studenti_punizioni_fk_studente` FOREIGN KEY (`fk_studente`) REFERENCES `ct_studenti` (`id_studente`);

--
-- Limiti per la tabella `ct_temporary_dom`
--
ALTER TABLE `ct_temporary_dom`
  ADD CONSTRAINT `fk_ct_temporary_dom_fk_domanda` FOREIGN KEY (`fk_domanda`) REFERENCES `ct_domande` (`id_domanda`),
  ADD CONSTRAINT `fk_ct_temporary_dom_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_utente_domande`
--
ALTER TABLE `ct_utente_domande`
  ADD CONSTRAINT `fk_ct_utente_domande_fk_domanda` FOREIGN KEY (`fk_domanda`) REFERENCES `ct_domande` (`id_domanda`),
  ADD CONSTRAINT `fk_ct_utente_domande_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_utenti`
--
ALTER TABLE `ct_utenti`
  ADD CONSTRAINT `fk_ct_utenti_fk_tipo_utente` FOREIGN KEY (`fk_tipo_utente`) REFERENCES `ct_tipo_utente` (`id_tipo_utente`);

--
-- Limiti per la tabella `ct_utenti_classi`
--
ALTER TABLE `ct_utenti_classi`
  ADD CONSTRAINT `fk_ct_utenti_classi_fk_classe` FOREIGN KEY (`fk_classe`) REFERENCES `ct_classi` (`id_classe`),
  ADD CONSTRAINT `fk_ct_utenti_classi_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_utenti_materie`
--
ALTER TABLE `ct_utenti_materie`
  ADD CONSTRAINT `fk_ct_utenti_materie_fk_materia` FOREIGN KEY (`fk_materia`) REFERENCES `ct_materie` (`id_materia`),
  ADD CONSTRAINT `fk_ct_utenti_materie_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);

--
-- Limiti per la tabella `ct_utenti_tipi`
--
ALTER TABLE `ct_utenti_tipi`
  ADD CONSTRAINT `fk_ct_utenti_tipi_fk_tipo_utente` FOREIGN KEY (`fk_tipo_utente`) REFERENCES `ct_tipo_utente` (`id_tipo_utente`),
  ADD CONSTRAINT `fk_ct_utenti_tipi_fk_utente` FOREIGN KEY (`fk_utente`) REFERENCES `ct_utenti` (`id_utente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
