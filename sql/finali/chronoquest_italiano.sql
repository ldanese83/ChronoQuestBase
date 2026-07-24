-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Creato il: Giu 30, 2026 alle 11:45
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
-- Database: `chronobaseitalia`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Algebra Relazionale nei DB', 4, '827849cd-3ed1-4fb8-bec9-bb7e6d4ea440'),
(2, 'Algoritmi di ricerca ed ordinamento', 4, '9e8585f2-930c-4d48-ba5e-25d971c8af50'),
(3, 'Bootstrap, una libreria per il web', 4, 'ad3b9953-6cc0-43d4-90f1-f4400661185c'),
(4, 'Database NoSQL', 4, '3c90a610-09b4-408d-8bf3-53b579b80865'),
(5, 'Diagrammi delle classi UML', 4, '6e8cc08b-0124-4467-94ff-5512ad5ebecc'),
(6, 'Diagrammi di flusso', 4, 'a0350e43-5d56-4285-a4af-9c995bd8fb84'),
(7, 'Diagrammi ER per progettare database', 4, '8bf0f1fd-5f7a-469f-aa97-357bec93a164'),
(8, 'Gestione della memoria nei Linguaggi ad Oggetti', 4, 'ff9d2e06-c751-42a3-bb96-3078cec0ce91'),
(9, 'HTML e CSS', 4, '76d949e5-fbfc-4763-b7b2-b9bb9655ea72'),
(10, 'Introduzione a Python', 4, 'c93bad1a-11a6-4dbc-94a5-0165744a0b3f'),
(11, 'Introduzione ai database', 4, '67139cea-162d-41a8-8bf3-47040bc6443e'),
(12, 'Introduzione alla Programmazione', 4, '865dbe5f-db4c-4747-b1d3-1c790e0e23b5'),
(13, 'Java Array Dinamici', 4, '60f1fb7f-07d9-41f2-963d-6262f302f1c5'),
(14, 'Java Basi', 4, 'e5a2f8a9-fb2d-4763-93d6-553d5c77cb04'),
(15, 'Java Classi', 4, '8ac2fcd2-028a-4a29-ab40-56c2a34c78f9'),
(16, 'Java Ereditarietà', 4, '4294d316-177f-4c5b-b740-8ba50f42f913'),
(17, 'Java uso di File', 4, 'f70ed098-3c4c-4133-8147-c903e4d2052e'),
(18, 'Linguaggi Compilati ed Interpretati', 4, '04106547-4bcf-4f6d-9d5f-b87cb179a825'),
(19, 'PHP: Connessione a DB e Query', 4, 'b5f8fd84-9a61-4d67-bf4a-d6d886c4bc24'),
(20, 'PHP: cookies e sessioni', 4, '7ad89e5f-6d72-41e2-b561-6c2e412b2d1d'),
(21, 'PHP: Dati da Form', 4, '7c0e8627-be57-4c70-a4dc-d853b41396b0'),
(22, 'PHP: introduzione, variabili, funzioni, array', 4, 'dc07ee51-ac2a-428e-8ac0-4afdd50474ea'),
(23, 'PHP: uso dei files', 4, '5c9ed381-4cbb-4481-b028-e353b9b811af'),
(24, 'Progettare database', 4, '346c856e-9bf5-4efc-bb9d-6645bd8685cc'),
(25, 'Programmazione ad Oggetti', 4, 'e524e118-cc02-43b6-bd44-8a13abe342d8'),
(26, 'Python classi', 4, '931b6567-5de2-4287-a138-1c3da7aed6df'),
(27, 'Python file', 4, 'd2f1e6fa-fd1b-4658-bc36-6e6d69d9269e'),
(28, 'Python Funzioni e Moduli', 4, '1e836aa0-7ddb-4eb2-aa9c-97670e082636'),
(29, 'Python Strutture dati (liste, insiemi, dizionari)', 4, 'd7f3a30e-373f-48ab-b769-f6f9374ddc52'),
(30, 'Python: programmazione strutturata', 4, 'd4084a08-e505-4958-b772-0c69227e1773'),
(31, 'Ricorsione', 4, '223315ff-8271-4649-954a-02cd9368aa07'),
(32, 'SQL DDL e DML', 4, '981f6158-86ba-4814-ab7a-9348701a36cc'),
(33, 'SQL Select', 4, 'c48fd5a1-221f-49bb-8b2b-f61606b6ab7b'),
(34, 'Strutture dati avanzate', 4, 'ade4fd70-76bf-4ca4-9e28-5b8e4e03db33');

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
(1, 'Apprendista Gattino', '/assets/images/Badge/imported_badge_6a43a808b37698.49754052_badge_asset_6a2e66047a4cf4.84307188_badge_6a2e5d081e1878.35797338_gattino_badge.png', 'Questo badge per aver raggiunto una competenza sufficiente nella realizzazione di pagine PHP con elementi di base', 1, 22, 6, 6, 'U'),
(2, 'Biplano Basi di Python', '/assets/images/Badge/imported_badge_6a43a808bdea94.52468541_badge_asset_6a2e66048e7537.13501971_badge_6a2e5d3cb3e808.72204091_badge_biplano.png', 'Hai la velocità e l&#039;agilità di un biplano nel destreggiarti con gli elementi base di Python: selezione, cicli e input output vengono superati con sufficienti abilità', 1, 30, 6, 6, 'U'),
(3, 'Black Hawk Python Base', '/assets/images/Badge/imported_badge_6a43a808c46e71.04230297_badge_asset_6a2e660498e262.67879568_badge_6a2e5d664fee52.17343019_badge_black_hawk.png', 'Hai la velocità e la potenza di un elicottero Black Hawk nel destreggiarti con gli elementi base di Python: selezione, cicli e input output vengono superati con discreta abilità', 1, 30, 6, 7, 'U'),
(4, 'Bonsai Funzioni Python', '/assets/images/Badge/imported_badge_6a43a808ca03d5.50101523_badge_asset_6a2e6604a26124.90126598_badge_6a2e5d8e15bb86.38510066_BonsaiFunzioni.png', 'Questo Badge per essere arrivati ad un livello sufficientemente buono nella realizzazione di funzioni con il linguaggio Python', 1, 28, 6, 6, 'U'),
(5, 'Brachiosauro Strutture Dati Python', '/assets/images/Badge/imported_badge_6a43a808d15c89.22143374_badge_asset_6a2e6604bb0c67.46044648_badge_6a2e5dbf277ae4.72711264_badge_brachiosauro_strutture.png', 'Questo badge per aver raggiunto un livello sufficiente nell&#039;utilizzo delle varie strutture dati in Python: liste, matrici, insiemi, tuple, dizionari', 1, 29, 6, 6, 'U'),
(6, 'Caccia Python Base', '/assets/images/Badge/imported_badge_6a43a808d7be16.61046551_badge_asset_6a2e6604c63f00.36702772_badge_6a2e5de891dbc0.65213559_badge_caccia.png', 'Hai la velocità e l&#039;agilità di un caccia F14 nel destreggiarti con gli elementi base di Python: selezione, cicli e input output vengono superati con buone abilità', 1, 30, 6, 8, 'U'),
(7, 'Cane Eroe SQL', '/assets/images/Badge/imported_badge_6a43a808dd3a23.48452407_badge_asset_6a2e6604d020d9.08835915_badge_6a2e5e17c34c04.20749485_cane_eroe_badge.png', 'Il badge viene conquistato dagli eroi che padroneggiano l&#039;SQL in maniera buona per creare query a database che estraggono dati in maniera aggregata', 1, 33, 6, 8, 'U'),
(8, 'Cane Scout Avventuriero SQL', '/assets/images/Badge/imported_badge_6a43a808e4b9d5.41603751_badge_asset_6a2e6604e4fff3.05385145_badge_6a2e5e4b3d3043.70790172_scout_sql_badge.png', 'Il badge viene conquistato dagli eroi che padroneggiano l&#039;SQL in maniera discreta per creare query a database che estraggono dati in maniera strutturata', 1, 33, 6, 7, 'U'),
(9, 'Cavaliere Intro Python', '/assets/images/Badge/imported_badge_6a43a808ebf4d2.13181807_badge_asset_6a2e66050620e2.13713382_badge_6a2e5e7a7e12c3.46084011_badge_cavaliere_intro_python.jpg', 'Questo badge viene conquistato quando si raggiunge una buona preparazione negli elementi introduttivi al linguaggio Python', 1, 10, 3, 7, 'U'),
(10, 'Coniglietta Campionessa Olimpica PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a808f0ab63.16800115_badge_asset_6a2e66050a3455.64195755_badge_6a2e5ead7025b9.56329483_coniglietta_olimpica_badge.png', 'Questo badge viene guadagnato se si raggiunge un livello olimpico nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 9, 'F'),
(11, 'Coniglietta in allenamento PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a809064696.14395036_badge_asset_6a2e66051f5783.55925700_badge_6a2e5edec09da8.39502029_coniglietta_all_badge.png', 'Questo badge viene guadagnato se si raggiunge un livello di base nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 6, 'F'),
(12, 'Coniglietta Pattinatrice PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a8090e32c3.70076260_badge_asset_6a2e660532ceb4.75376299_badge_6a2e5f0a323332.39390971_coniglietta_pattinibadge.png', 'Questo badge viene guadagnato se si raggiunge un livello medio nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 7, 'F'),
(13, 'Coniglietta Tennista PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a809164195.96397763_badge_asset_6a2e6605471d90.91583498_badge_6a2e5f3f0db7d6.63667721_coniglietta_tennista_badge.png', 'Questo badge viene guadagnato se si raggiunge un livello avanzato nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 8, 'F'),
(14, 'Cucciolo curioso SQL', '/assets/images/Badge/imported_badge_6a43a8091ea720.77258292_badge_asset_6a2e66055d4992.01762437_badge_6a2e5f66023fc1.98034780_cucciolo_sql_badge.png', 'Il badge viene conquistato dagli eroi che padroneggiano l&#039;SQL in maniera sufficiente per creare query a database che estraggono dati in maniera semplice', 1, 33, 6, 6, 'U'),
(15, 'Galeone Fantasma UML', '/assets/images/Badge/imported_badge_6a43a809264173.90500899_badge_asset_6a2e6605714bf9.86267030_badge_6a2e5f9a7a0265.28677427_galeone_cavaliere_intro_python.png', 'Questo badge viene conquistato dagli eroi che sanno progettare ad un livello medio database relazionali tramite diagramma delle classi UML', 1, 5, 6, 7, 'U'),
(16, 'Gatto Ninja', '/assets/images/Badge/imported_badge_6a43a8092e7aa6.05972443_badge_asset_6a2e66057be515.89894532_badge_6a2e5fc86c4858.41792173_gatto_ninja_badge.png', 'Questo badge per aver raggiunto una competenza di livello medio nella realizzazione di pagine PHP con elementi di base', 1, 22, 6, 7, 'U'),
(17, 'Gatto Samurai', '/assets/images/Badge/imported_badge_6a43a8093589a2.32029612_badge_asset_6a2e66058c1f20.89826331_badge_6a2e5ff3bca050.82568939_gatto_samurai_badge.png', 'Questo badge per aver raggiunto una competenza buona nella realizzazione di pagine PHP con elementi di base', 1, 22, 6, 8, 'U'),
(18, 'Gatto Sensei Supremo', '/assets/images/Badge/imported_badge_6a43a8093e1c15.47424367_badge_asset_6a2e6605a233c9.28461857_badge_6a2e6018d98783.56322306_gatto_sensei_badge.png', 'Questo badge per aver raggiunto una competenza ottima nella realizzazione di pagine PHP con elementi di base', 1, 22, 6, 9, 'U'),
(19, 'Ipno Pinguino Form PHP', '/assets/images/Badge/imported_badge_6a43a80945c680.47420558_badge_asset_6a2e6605b7c380.23540318_badge_6a2e6043459f17.90683084_ipno_pinguino_badge.png', 'Il badge per chi acquisisce un livello medio di competenze nell&#039;utilizzo di Form per passare dati in GET e POST a PHP', 1, 21, 5, 7, 'U'),
(20, 'Legend Algoritmi', '/assets/images/Badge/imported_badge_6a43a8094ce443.20222850_badge_asset_6a2e6605ca6c47.87885848_badge_6a2e6071bbdb84.82622816_lvl4.jpg', 'Questo badge viene assegnato per aver ottenuto ottime competenze nella programmazione di algoritmi, che classificano come una leggenda della programmazione di base', 1, 6, 6, 9, 'U'),
(21, 'Leggenda canina SQL', '/assets/images/Badge/imported_badge_6a43a8095097c1.69410470_badge_asset_6a2e6605ce32e6.95710190_badge_6a2e609a920a20.90456687_leggenda_canina_badge.png', 'Il badge viene conquistato dagli eroi che padroneggiano l&#039;SQL in maniera ottima per creare query a database che estraggono dati di qualsiasi tipo ed in qualsiasi modo', 1, 33, 6, 9, 'U'),
(22, 'Maestro Jedi Python File', '/assets/images/Badge/imported_badge_6a43a80956ff21.88879295_badge_asset_6a2e6605e35657.53077228_badge_6a2e60c0ceda01.00642967_badge_maestro_file.png', 'Questo badge per aver raggiunto un livello buono nell&#039;utilizzo di file in Python', 1, 27, 6, 8, 'U'),
(23, 'Maestro Yoda File Python', '/assets/images/Badge/imported_badge_6a43a8095b8e68.79442729_badge_asset_6a2e6605ecf290.19432293_badge_6a2e60e3e989c4.79063918_badge_yoda_file.png', 'Questo badge per aver raggiunto un livello ottimo nell&#039;utilizzo di file in Python', 1, 27, 6, 9, 'U'),
(24, 'Nave da Crociera Progettazione Database', '/assets/images/Badge/imported_badge_6a43a8095fe9e5.64679142_badge_asset_6a2e660602ef18.29404582_badge_6a2e6115516be8.80911507_badge_crociera.png', 'Questo badge viene conquistato dagli eroi che sanno progettare ad un livello molto buono database relazionali tramite diagramma delle classi UML', 1, 5, 6, 8, 'U'),
(25, 'Normal Panda Sessioni PHP', '/assets/images/Badge/imported_badge_6a43a809649794.84325139_badge_asset_6a2e66060ca5e0.43400584_badge_6a2e61418b2c45.71704444_normal_panda_badge.png', 'Questo badge viene ottenuto dagli eroi che sanno destreggiarsi tra le sessioni ed i cookies in PHP ad un livello base', 1, 20, 5, 6, 'U'),
(26, 'Padawan File Python', '/assets/images/Badge/imported_badge_6a43a8096b4952.97253573_badge_asset_6a2e660622da66.13054504_badge_6a2e615fcec0c7.13094459_badge_padawan_file.png', 'Questo badge per aver raggiunto un livello sufficiente nell&#039;utilizzo di file in Python', 1, 27, 6, 6, 'U'),
(27, 'Panda Super Sayian God Sessioni PHP', '/assets/images/Badge/imported_badge_6a43a8096fbfb4.23003889_badge_asset_6a2e66062c2cb2.37659263_badge_6a2e619395db81.44777352_panda_ssg_badge.png', 'Questo badge viene ottenuto dagli eroi che sanno destreggiarsi tra le sessioni ed i cookies in PHP ad un livello che potremmo definire quasi divino', 1, 20, 6, 9, 'U'),
(28, 'Panda Super Sayian Level 3 Sessioni PHP', '/assets/images/Badge/imported_badge_6a43a8097683e8.69944090_badge_asset_6a2e66064536a6.60277168_badge_6a2e61c4caebb9.15205143_panda_supers3_badge.png', 'Questo badge viene ottenuto dagli eroi che sanno destreggiarsi tra le sessioni ed i cookies in PHP ad un livello avanzato', 1, 20, 5, 8, 'U'),
(29, 'Panda Super Sayian Sessioni PHP', '/assets/images/Badge/imported_badge_6a43a8097d9c92.13683642_badge_asset_6a2e66065d5c66.72915011_badge_6a2e61ea946484.68694146_panda_supers_badge.png', 'Questo badge viene ottenuto dagli eroi che sanno destreggiarsi tra le sessioni ed i cookies in PHP ad un livello medio', 1, 20, 5, 7, 'U'),
(30, 'Pilota X-Wing File Python', '/assets/images/Badge/imported_badge_6a43a809848410.14018327_badge_asset_6a2e660675bb38.79689560_badge_6a2e620ad95b10.48873810_badge_pilota_file.png', 'Questo badge per aver raggiunto un livello discreto nell&#039;utilizzo di file in Python', 1, 27, 6, 7, 'U'),
(31, 'Pinguino Cucciolo Letale Form PHP', '/assets/images/Badge/imported_badge_6a43a8098956c8.46525495_badge_asset_6a2e66067e9698.87270134_badge_6a2e6248b9adc4.36215651_pinguino_letale_badge.png', 'Il badge per chi acquisisce un livello base di competenze nell&#039;utilizzo di Form per passare dati in GET e POST a PHP', 1, 21, 5, 6, 'U'),
(32, 'Pinguino Furia Nucleare Form PHP', '/assets/images/Badge/imported_badge_6a43a8098fdb30.28446472_badge_asset_6a2e660693f164.31039653_badge_6a2e626dc505f6.42331427_furia_nucleare_badge.png', 'Il badge per chi acquisisce un livello ottimo di competenze nell&#039;utilizzo di Form per passare dati in GET e POST a PHP', 1, 21, 5, 9, 'U'),
(33, 'Pinguino Rambo Form PHP', '/assets/images/Badge/imported_badge_6a43a80995e149.49704433_badge_asset_6a2e6606a82165.34568349_badge_6a2e62ad00ab84.58152026_pinguino_rambo_badge.png', 'Il badge per chi acquisisce un livello avanzato di competenze nell&#039;utilizzo di Form per passare dati in GET e POST a PHP', 1, 21, 5, 8, 'U'),
(34, 'Pino Funzioni Python', '/assets/images/Badge/imported_badge_6a43a8099bf2b0.61790937_badge_asset_6a2e6606bdac51.01538766_badge_6a2e62d4694539.87492917_PinoFunzioni.png', 'Questo Badge per aver ottenuto un livello discretamente buono nella creazione di funzioni con Python', 1, 28, 6, 7, 'U'),
(35, 'Portaaerei Progettazione Database', '/assets/images/Badge/imported_badge_6a43a809a319b1.58587623_badge_asset_6a2e6606d65161.50970110_badge_6a2e62fc0b5e80.74112074_badge_portaaerei.png', 'Questo badge viene conquistato dagli eroi che sanno progettare al massimo livello database relazionali tramite diagramma delle classi UML, con analisi dettagliate e classi eccellenti', 1, 5, 6, 9, 'U'),
(36, 'Principiante Algoritmi', '/assets/images/Badge/imported_badge_6a43a809a7a0a9.45676608_badge_asset_6a2e6606e03248.03576881_badge_6a2e631cb35390.53653140_lvl2.jpg', 'Questo badge viene assegnato per aver ottenuto un livello medio nella programmazione di algoritmi', 1, 6, 6, 7, 'U'),
(37, 'Quercia Funzioni Python', '/assets/images/Badge/imported_badge_6a43a809abcac1.55663583_badge_asset_6a2e6606e3cff0.70569624_badge_6a2e6339eb4b31.83856025_QuerciaFunzioni.png', 'Questo Badge per essere arrivati ad un buon livello nella realizzazione di funzioni con il linguaggio Python', 1, 28, 6, 8, 'U'),
(38, 'Rookie Algoritmi', '/assets/images/Badge/imported_badge_6a43a809b2c709.04173754_badge_asset_6a2e6607062d15.14039250_badge_6a2e635866efb1.39905793_lvl1.jpg', 'Questo badge viene assegnato per aver ottenuto le competenze di base nella programmazione di algoritmi', 1, 6, 6, 6, 'U'),
(39, 'Samurai Intro Python', '/assets/images/Badge/imported_badge_6a43a809b64d21.55955502_badge_asset_6a2e6607096238.33491818_badge_6a2e638423dd21.93377588_badge_samurai_intro_python.jpg', 'Questo badge viene conquistato quando si ha una ottima comprensione degli elementi introduttivi al linguaggio Python', 1, 10, 3, 9, 'U'),
(40, 'Scudiero Intro a Python', '/assets/images/Badge/imported_badge_6a43a809b9b6a8.22582120_badge_asset_6a2e66070d2a35.05050511_badge_6a2e63a9c25090.79786715_badge_scudiero_intro_python.jpg', 'Il badge viene assegnato per aver raggiunto un livello sufficiente negli esercizi introduttivi al linguaggio Python', 1, 10, 6, 6, 'U'),
(41, 'Sequoia Funzioni Python', '/assets/images/Badge/imported_badge_6a43a809bd15b3.01976734_badge_asset_6a2e66071128e5.77511948_badge_6a2e63d222fc40.46758027_SequoiaFunzioni.png', 'Questo Badge per aver raggiunto un livello eccellente nella realizzazione di funzioni tramite Python', 1, 28, 6, 9, 'U'),
(42, 'Stealth Python Base', '/assets/images/Badge/imported_badge_6a43a809c37393.86043066_badge_asset_6a2e66072856d3.04541698_badge_6a2e63f0dbfa00.53325191_badge_stealth.png', 'Hai le incredibili velocità e agilità di un caccia stealth che abbatte il muro del suono nel destreggiarti con gli elementi base di Python: selezione, cicli e input output vengono superati senza problemi con ottime abilità', 1, 30, 6, 9, 'U'),
(43, 'T-Rex Strutture Dati Python', '/assets/images/Badge/imported_badge_6a43a809c7b9c4.05745808_badge_asset_6a2e6607318fb2.55862874_badge_6a2e6410b08862.32061254_badge_trex.png', 'Questo badge per aver raggiunto un livello ottimo nell&#039;utilizzo delle varie strutture dati in Python: liste, matrici, insiemi, tuple, dizionari', 1, 29, 6, 9, 'U'),
(44, 'Tigrotto Alunno Files PHP', '/assets/images/Badge/imported_badge_6a43a809cc4f26.86290560_badge_asset_6a2e66073cb8a6.22882130_badge_6a2e6438017770.68038240_tigrotto_alunno_badge.png', 'Questo Badge viene conquistato solo dagli studiosi di PHP che abbiano raggiunto un livello base di programmazione usando i files all&#039;interno di pagine PHP', 1, 23, 4, 6, 'U'),
(45, 'Tigrotto Bibliotecario Files PHP', '/assets/images/Badge/imported_badge_6a43a809d69753.61755659_badge_asset_6a2e66075373d1.33581777_badge_6a2e64617fcaf0.12530981_tigrotto_biblioteca_badge.png', 'Questo Badge viene conquistato solo dagli studiosi di PHP che abbiano raggiunto un livello medio di programmazione usando i files all&#039;interno di pagine PHP', 1, 23, 4, 7, 'U'),
(46, 'Tigrotto Indiana Jones File PHP', '/assets/images/Badge/imported_badge_6a43a809dd85e5.41862243_badge_asset_6a2e66076a0ed8.61437334_badge_6a2e649224fcc8.98774030_tigrotto_indiana_badge.png', 'Questo Badge viene conquistato solo dagli studiosi di PHP che abbiano raggiunto un livello epico di programmazione usando i files all&#039;interno di pagine PHP', 1, 17, 4, 9, 'U'),
(47, 'Tigrotto Prof File PHP', '/assets/images/Badge/imported_badge_6a43a809e43964.41142930_badge_asset_6a2e66077e32e2.45984556_badge_6a2e64b0dbeb34.04670147_tigrotto_prof_badge.png', 'Questo Badge viene conquistato solo dagli studiosi di PHP che abbiano raggiunto un livello avanzato di programmazione usando i files all&#039;interno di pagine PHP', 1, 23, 4, 8, 'U'),
(48, 'Triceratopo Strutture dati Python', '/assets/images/Badge/imported_badge_6a43a809eab046.99153755_badge_asset_6a2e66078ead33.98346801_badge_6a2e64d4bbe3f1.89875936_badge_triceratopo.png', 'Questo badge per aver raggiunto un livello medio nell&#039;utilizzo delle varie strutture dati in Python: liste, matrici, insiemi, tuple, dizionari', 1, 29, 6, 7, 'U'),
(49, 'Tricheco Calciatore PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a809efe846.95992183_badge_asset_6a2e660796f9d1.39898850_badge_6a2e650c53d5a1.23893217_tricheco_calciatorebadge.png', 'Questo badge viene guadagnato se si raggiunge un livello medio nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 7, 'M'),
(50, 'Tricheco Campione Olimpico PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a80a022761.40083155_badge_asset_6a2e6607a65ae3.39061215_badge_6a2e6536abef72.52323726_tricheco_olimpico_badge.png', 'Questo badge viene guadagnato se si raggiunge un livello olimpico nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 9, 'M'),
(51, 'Tricheco in allenamento PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a80a087c20.20955406_badge_asset_6a2e6607b5a599.20893791_badge_6a2e655e92e393.05552124_tricheco_allenamento_badge.png', 'Questo badge viene guadagnato se si raggiunge un livello di base nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 6, 'M'),
(52, 'Tricheco Pugile PHP e MySQL', '/assets/images/Badge/imported_badge_6a43a80a0fcf40.86521439_badge_asset_6a2e6607c49732.49281542_badge_6a2e65889c7092.21286410_tricheco_pugile_badge.png', 'Questo badge viene guadagnato se si raggiunge un livello avanzato nell&#039;uso di PHP per la connessione a database SQL, lanciando varie query in linguaggio DML o QL', 1, 19, 5, 8, 'M'),
(53, 'Velociraptor Strutture Dati Python', '/assets/images/Badge/imported_badge_6a43a80a165e71.23400543_badge_asset_6a2e6607d3ac59.12227007_badge_6a2e65b418f559.81751145_badge_raptor_strutture.png', 'Questo badge per aver raggiunto un livello buono nell&#039;utilizzo delle varie strutture dati in Python: liste, matrici, insiemi, tuple, dizionari', 1, 29, 6, 8, 'U'),
(54, 'Veteran Algoritmi', '/assets/images/Badge/imported_badge_6a43a80a1b50a6.98436734_badge_asset_6a2e6607dca8d9.81664648_badge_6a2e65d1df2fe8.96702317_lvl3.jpg', 'Questo badge viene assegnato per aver ottenuto una competenza molto buona nella programmazione di algoritmi', 1, 6, 6, 8, 'U'),
(55, 'Zattera Progettazione database', '/assets/images/Badge/imported_badge_6a43a80a1eec93.33833896_badge_asset_6a2e6607df8ed0.10928008_badge_6a2e65ebf13a43.96924873_badge_zattera_progttazione.png', 'Questo badge viene conquistato dagli eroi che sanno progettare a livello base database relazionali tramite diagramma delle classi UML', 1, 5, 6, 6, 'U');

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
(8, '4bf46763-faee-4c21-8665-baf06a4f8964', 'Il Creatore', 508, 313),
(9, '56ab989e-0aa1-4a81-ae40-19144a1dbb97', 'La ricerca degli eroi neutrali', 523, 231),
(10, 'cc0e2e49-4ec8-40b1-9913-20a0deba7826', 'Il Primo Passo', 476, 208),
(11, 'b59089a8-9877-457e-bba0-7e5c035f15f6', 'L\'Arco d\'Avorio', 448, 172),
(12, '39924b08-361b-42f8-9fab-db99d4eb92b7', 'La Lancia del Destino', 518, 177),
(13, '22c99711-d248-4b38-b0ed-d228de35ab40', 'La Spada Fiammeggiante', 365, 554),
(14, '8a615ef7-ee03-4805-8058-887600575a2d', 'Lo Scudo Dorato', 279, 407),
(15, '6c258bcc-5f18-4acb-9615-30c6f44a142d', 'Il Martello dell\'Eden', 160, 443),
(16, '034049c8-3776-4d12-b71a-b9e1f8be8ce9', 'In difficoltà', 884, 440),
(17, 'f9a45119-215f-4231-9b11-3b701e77b9f8', 'L\'Ascia Spaccaterra', 740, 116),
(18, 'cae56305-bf81-4142-b7a1-926b278f4e30', 'Lo Scontro Finale', 121, 152);

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
(8, 6, 8, 1),
(9, 6, 9, 2),
(10, 6, 10, 3),
(11, 6, 11, 4),
(12, 6, 12, 5),
(13, 6, 13, 6),
(14, 6, 14, 7),
(15, 6, 15, 8),
(16, 6, 16, 9),
(17, 6, 17, 10),
(18, 6, 18, 11);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ct_classi_quest`
--

INSERT INTO `ct_classi_quest` (`id_classe_quest`, `fk_classe`, `fk_quest`) VALUES
(6, 1, 6);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Cosa si intende con programmazione strutturata?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(2, 'Quali sono i vantaggi della programmazione ad oggetti?', 1, 26, 3, 0, 1, '2026-06-30', '', 1, 0, 3),
(3, 'Definire possibili attributi e metodi dell&#039;oggetto Moto', 1, 26, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(4, 'Cosa significa istanza di una classe?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(5, 'Come si rappresenta solitamente la classe?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(6, 'La classe Film ha gli attributi: titolo e regista. Disegnare il diagramma degli oggetti per 2 oggetti della classe Film', 1, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(7, 'Quando viene richiamato il costruttore di una classe?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(8, 'Cosa si intende con incapsulamento?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(9, 'Qual &egrave; la corretta definizione di una classe Automobile in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(10, 'Qual &egrave; la corretta definizione di un metodo con 2 parametri in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(11, 'Come posso richiamare il metodo &#039;stampa&#039; senza parametri sull&#039;oggetto &#039;ogg&#039; in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(12, 'Come potrei creare un oggetto della classe Triangolo con i parametri base a 5 ed altezza a 3?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(13, 'Qual &egrave; la corretta definizione di un costruttore per creare oggetti con 1 parametro?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(14, 'Come si pu&ograve; creare un attributo protetto per la definizione di un colore in una classe Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(15, 'Se creo un oggetto della classe Rettangolo con nome r1, come posso accedere ad un suo attributo privato __base da una funzione esterna main?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(16, 'Una classe che eredita da una superclasse &egrave; anche detta:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(17, 'Se la classe Cerchio &egrave; sottoclasse della classe FiguraGeometrica in Python devo scrivere:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(18, 'Per accedere agli elementi della sopraclasse all&#039;interno dei metodi della sottoclasse mi serve la parola chiave:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(19, 'Ereditariet&agrave; multipla:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(20, 'Con polimorfismo in programmazione intendiamo:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(21, 'Nell&#039;overriding:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(22, 'Volendo stampare un oggetto direttamente con la funzione print, quale operatore devo sovraccaricare?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(23, 'In Python una funzione creata all&#039;interno della definizione di una classe si chiama:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(24, 'Quali possono essere dei possibili attributi e metodi per l&#039;oggetto Televisore?', 1, 26, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(25, 'Se una classe deriva da due superclassi abbiamo:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(26, 'Un&#039;istanza di una classe:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(27, 'Quale delle seguenti non pu&ograve; creare correttamente un oggetto della classe Cane?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(28, 'Quali delle seguenti NON &egrave; corretta riguardo la programmazione ad oggetti?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(29, 'La parola chiave che definisce un modello che indica i dati che saranno contenuti in un oggetto della classe e le funzioni che possono essere richiamate su un oggetto della classe &egrave;', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(30, 'La parola che per convenzione &egrave; usata per riferirsi all&#039;istanza corrente (oggetto) di una classe:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(31, 'Per estendere una classe, la nuova classe dovrebbe avere accesso a tutti i dati e al funzionamento interno della superclasse', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(32, 'Quale dei seguenti &egrave; il modo corretto di definire un costruttore?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(33, 'Quale delle seguenti affermazioni &egrave; pi&ugrave; accurata per la dichiarazione x = Cerchio()?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(34, 'Qual &egrave; il metodo di cui fare l&#039;overload per sovraccaricare l&#039;operatore di somma?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(35, 'Disegnare un possibile diagramma delle classi per la classe Auto', 1, 26, 1, 1, 1, '2026-06-30', '', 1, 0, 3),
(36, 'Qual &egrave; la differenza tra programmazione procedurale, strutturata e ad oggetti?', 3, 26, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(37, 'Quali sono i vantaggi della programmazione ad oggetti? Darne una breve descrizione', 3, 26, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(38, 'Cosa sono gli attributi ed i metodi di una classe? Fare un esempio', 3, 26, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(39, 'Qual &egrave; la differenza tra classe ed oggetto? Fare un esempio', 3, 26, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(40, 'Disegnare un possibile diagramma delle classi UML per la classe Film, inserendo almeno 2 attributi e 2 metodi', 3, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(41, 'Disegnare un possibile diagramma delle classi UML per la classe Nave, inserendo almeno 2 attributi e 2 metodi', 3, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(42, 'Disegnare un possibile diagramma delle classi UML per la classe SmartPhone, inserendo almeno 2 attributi e 2 metodi', 3, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(43, 'Disegnare un possibile diagramma delle classi UML per la classe Notebook, inserendo almeno 2 attributi e 2 metodi', 3, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(44, 'A cosa serve il metodo costruttore? Come si dichiara in Python?', 3, 26, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(45, 'Spiegare cosa si intende con incapsulamento nella programmazione ad oggetti', 3, 26, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(46, 'Spiegare cosa si intende con information hiding in un linguaggio ad oggetti e come si realizza in pratica', 3, 26, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(47, 'Cosa si intende con interfaccia di un oggetto?', 3, 26, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(48, 'La parola che per convenzione &egrave; usata per riferirsi all&#039;istanza corrente di una classe in Python:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(49, 'Cosa fa la funzione __init__ in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(50, 'Come posso richiamare il metodo &#039;stampa&#039; senza parametri sull&#039;oggetto &#039;ogg&#039; in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(51, 'Qual &egrave; la corretta definizione di un costruttore per creare oggetti con 1 parametro in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(52, 'Un&#039;istanza di una classe &egrave;:', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(53, 'Quale delle seguenti non pu&ograve; creare correttamente un oggetto della classe Cane in Python?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(54, 'In Python, qual &egrave; il metodo che viene chiamato automaticamente quando viene creata una nuova istanza di una classe?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(55, 'In Python, quale metodo di una classe &egrave; chiamato automaticamente quando si tenta di convertire un&#039;istanza in una stringa (overload dell&#039;operatore di stampa)?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(56, 'In Python, qual &egrave; il metodo utilizzato per chiamare un metodo sovrascritto della classe genitore all&#039;interno di una sottoclasse?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(57, 'In Python, quale parola chiave &egrave; utilizzata per indicare che una funzione non restituisce alcun valore?', 1, 26, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(58, 'Dato il codice seguente indicare cosa stampa il programma Python quando viene lanciato:', 1, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/recupero1.jpg&quot; style=&quot;height:691px; width:1006px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(59, 'Dato il codice seguente indicare cosa stampa il programma Python quando viene lanciato:', 1, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/recupero2.jpg&quot; style=&quot;height:669px; width:890px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_______________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(60, 'Dato il codice seguente indicare cosa stampa il programma Python quando viene lanciato:', 1, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/recupero3.jpg&quot; style=&quot;height:924px; width:1013px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(61, 'Dato il codice seguente indicare cosa stampa il programma Python quando viene lanciato:', 1, 26, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/recupero4.jpg&quot; style=&quot;height:913px; width:950px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;____________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(62, 'Quale istruzione &egrave; necessario eseguire sempre per prima su un file?', 1, 27, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(63, 'Cosa stampa il seguente frammento di codice?', 2, 27, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;file&lt;/span&gt;=&lt;span style=&quot;color:#795e26&quot;&gt;open&lt;/span&gt;(&lt;span style=&quot;color:#a31515&quot;&gt;&quot;ciao.txt&quot;&lt;/span&gt;,&lt;span style=&quot;color:#a31515&quot;&gt;&quot;w&quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;file&lt;/span&gt;.&lt;span style=&quot;color:#795e26&quot;&gt;write&lt;/span&gt;(&lt;span style=&quot;color:#a31515&quot;&gt;&quot;hello &quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;file&lt;/span&gt;.&lt;span style=&quot;color:#795e26&quot;&gt;close&lt;/span&gt;()&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;file&lt;/span&gt;.&lt;span style=&quot;color:#795e26&quot;&gt;write&lt;/span&gt;(&lt;span style=&quot;color:#a31515&quot;&gt;&quot;world &quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;___________________________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(64, 'Cosa stampa il seguente frammento di codice sapendo che il file nomi.txt contiene i nomi: Mario, Luigi e Marta su 3 diverse righe, andando a capo ogni nome?', 2, 27, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;&quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;file&lt;/span&gt;=&lt;span style=&quot;color:#795e26&quot;&gt;open&lt;/span&gt;(&lt;span style=&quot;color:#a31515&quot;&gt;&quot;nomi.txt&quot;&lt;/span&gt;,&lt;span style=&quot;color:#a31515&quot;&gt;&quot;r&quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;nomi&lt;/span&gt;=&lt;span style=&quot;color:#001080&quot;&gt;file&lt;/span&gt;.&lt;span style=&quot;color:#795e26&quot;&gt;readline&lt;/span&gt;()&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#af00db&quot;&gt;for&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;n&lt;/span&gt; &lt;span style=&quot;color:#af00db&quot;&gt;in&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;nomi&lt;/span&gt;:&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &lt;span style=&quot;color:#795e26&quot;&gt;print&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;n&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;_______________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(65, 'Perch&eacute; utilizzare le funzioni nei linguaggi di programmazione?', 3, 28, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(66, 'Quali sono gli elementi necessari a dichiarare una funzione in Python?', 3, 28, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(67, 'Scrivere il codice per la funzione che fa la moltiplicazione di due numeri. Scrivere inoltre il codice per richiamare la funzione con i numeri 5 e 8 come parametri attuali', 3, 28, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(68, 'Cosa si intende per visibilit&agrave; locale e per visibilit&agrave; globale di una variabile?', 3, 28, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(69, 'Cosa si intende con parametri formali e parametri attuali?', 3, 28, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(70, 'Quali sono le 3 regole di corrispondenza tra parametri formali e parametri attuali in Python?', 3, 28, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(71, 'Spiegare la differenza del passaggio dei parametri per valore e per riferimento', 3, 28, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(72, 'Cosa si intende con namespace?', 3, 28, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(73, 'Cosa si intende per shadowing? Fare un esempio', 3, 28, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(74, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def percentuale(x,y):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; z=x*(y/100)&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return z&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(percentuale(%%7,10??0,%%1,3??0))&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n___________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(75, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def assoluto(x):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; if x&gt;=0:&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; return x&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; else:&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; return -x&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(assoluto(%%-4,4??))&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_____________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(76, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def power(x,y):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; if(y&gt;0):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; return x**y&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; else:&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; return &quot;Errore&quot;&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(power(2,%%-4,4??))&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;___________________________________________________________________________________________&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n___________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(77, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def intercetta_x(m,q):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; x=-q/m&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return x&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(intercetta_x(%%2,4??,%%8,10??))&lt;/p&gt;\r\n', 1, 0, 3),
(78, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(c)&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\na=%%2,8??&lt;br /&gt;\r\nb=%%3,12??&lt;br /&gt;\r\nprint(somma())&lt;/p&gt;\r\n\r\n&lt;p&gt;___________________________________________________________________________________________________________&lt;/p&gt;\r\n\r\n&lt;p&gt;___________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(79, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(a,b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\na=%%3,6??&lt;br /&gt;\r\nb=%%6,10??&lt;br /&gt;\r\nprint(somma(%%5,11??,%%9,14??))&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(80, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(somma(%%3,5??,%%10,13??))&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(81, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=6&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nsomma(10)&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n____________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(82, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%2,10??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nsomma(%%1,7??)&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_______________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(83, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%1,8??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(b):&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; c=a+b&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; return c&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp; &amp;nbsp;&lt;br /&gt;\r\nprint(somma(a))&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(84, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; a=%%4,12??&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(somma(a))&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n___________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(85, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(a=%%3,8??,b):&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; c=a+b&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; return c&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp; &amp;nbsp;&lt;br /&gt;\r\nprint(somma())&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n_____________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(86, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(a=%%1,7??,b=%%10,15??):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(somma())&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n____________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(87, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(a,b=%%2,5??):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(somma(%%1,5??,%%7,12??))&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n______________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(88, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(a,b=%%2,6??):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a+b&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nprint(somma(%%1,7??))&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;___________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(89, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%4,8??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(a,b):&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; return a+b&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp; &amp;nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; a=%%1,3??&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp;&amp;nbsp; print(somma(a,%%10,14??))&lt;br /&gt;\r\n&amp;nbsp;&amp;nbsp; &amp;nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n____________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(90, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%1,5??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(a,b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; a=%%6,10??&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(somma(a,5))&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n____________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(91, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%1,5??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(a,b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(somma(a,%%6,10??))&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n____________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(92, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%1,5??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(b=%%6,10??):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(somma(%%11,15??))&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n___________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(93, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%1,5??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(b=%%6,10??):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(somma())&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n___________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(94, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%6,10??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; global a&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; a=%%1,5??&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(somma(10))&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(95, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;def somma(b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; global a&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; a=%%3,8??&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; return a+b&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\ndef main():&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(somma(%%10,14??))&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; print(a)&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nmain()&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n___________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;___________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(96, 'Cos&rsquo;&egrave; e a cosa serve un modulo in Python?', 3, 28, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(97, 'Inserire di seguito il codice per importare un modulo denominato funzioni.py. All&#039;interno del file vi sono 2 funzioni denominate somma e prodotto. Inserire quindi come si importa la sola funzione somma e come si importa il modulo dando un alias (ad esempio f)', 3, 28, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(98, 'Cosa sono i sottoprogrammi in programmazione?', 1, 28, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(99, 'Quale dei seguenti &egrave; un esempio di sottoprogramma in Python?', 1, 28, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(100, 'Cosa rappresenta un sottoprogramma in programmazione?', 2, 28, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(101, 'Dove &egrave; visibile una variabile globale?', 1, 28, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(102, 'Cosa stampa il seguente frammento di codice?', 2, 28, 4, 0, 1, '2026-06-30', '&lt;p&gt;a=%%8,15??&lt;/p&gt;\r\n\r\n&lt;p&gt;def somma(a,b):&lt;br /&gt;\r\n&nbsp;&nbsp;&nbsp; c=a-b&lt;br /&gt;\r\n&nbsp; &nbsp;&nbsp;return c&lt;br /&gt;\r\n&nbsp;&nbsp; &nbsp;&lt;br /&gt;\r\nd=somma(a,%%1,4??)&lt;br /&gt;\r\nprint(d)&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n____________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(103, 'Qual &egrave; la principale differenza tra il passaggio di argomenti per valore e per riferimento?', 1, 28, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(104, 'Scrivere il seguente programma usando le liste di Python:', 4, 29, 4, 0, 1, '2026-06-30', '&lt;p&gt;Creare una lista contenente 4 cognomi di alunni della 3EI.&lt;br /&gt;\r\nChiedere all&amp;#39;utente il proprio cognome. Controllare se il cognome &amp;egrave; contenuto all&amp;#39;interno della lista e dire in output se &amp;egrave; presente o meno.&lt;br /&gt;\r\nSe il cognome fornito dall&amp;#39;utente non &amp;egrave; contenuto nella lista, aggiungerlo nella prima posizione della lista.&lt;/p&gt;\r\n', 1, 0, 3),
(105, 'Scrivere il seguente programma usando le liste di Python:', 4, 29, 4, 0, 1, '2026-06-30', '&lt;p&gt;Creare una lista contenente 4 sport.&lt;br /&gt;\r\nChiedere all&amp;#39;utente qual &amp;egrave; il suo sport preferito. Controllare se lo sport dell&amp;#39;utente &amp;egrave; contenuto all&amp;#39;interno della lista e dire in output se &amp;egrave; presente o meno.&lt;br /&gt;\r\nSe lo sport dato dall&amp;#39;utente non &amp;egrave; contenuto nella lista, aggiungerlo nell&amp;#39;ultima posizione della lista.&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(106, 'Creare il seguente programma Python con le liste', 4, 29, 4, 0, 1, '2026-06-30', '&lt;p&gt;Creare una lista di 5 animali domestici. Chiedere all&amp;#39;utente quale &amp;egrave; il suo animale domestico preferito. Controllare se l&amp;#39;animale dell&amp;#39;utente &amp;egrave; contenuto all&amp;#39;interno della lista e dire in output se &amp;egrave; presente o meno. Se l&amp;#39;animale dato dall&amp;#39;utente non &amp;egrave; contenuto nella lista, sostituire il terzo elemento della lista con quello dato in input dall&amp;#39;utente.&lt;/p&gt;\r\n', 1, 0, 3),
(107, 'Quali sono le strutture dati principali in Python?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(108, 'Qual &egrave; l&#039;obiettivo principale delle strutture dati nella programmazione?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(109, 'Cosa stampa il seguente frammento di codice?', 2, 29, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;&quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;lista&lt;/span&gt; = [&lt;span style=&quot;color:#098658&quot;&gt;3&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;7&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;1&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;4&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;10&lt;/span&gt;]&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#af00db&quot;&gt;for&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;elem&lt;/span&gt; &lt;span style=&quot;color:#af00db&quot;&gt;in&lt;/span&gt; &lt;span style=&quot;color:#267f99&quot;&gt;range&lt;/span&gt;(&lt;span style=&quot;color:#795e26&quot;&gt;len&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;lista&lt;/span&gt;)):&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &lt;span style=&quot;color:#795e26&quot;&gt;print&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;elem&lt;/span&gt;+&lt;span style=&quot;color:#001080&quot;&gt;lista&lt;/span&gt;[&lt;span style=&quot;color:#001080&quot;&gt;elem&lt;/span&gt;],&lt;span style=&quot;color:#001080&quot;&gt;end&lt;/span&gt;=&lt;span style=&quot;color:#a31515&quot;&gt;&quot; &quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;_______________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(110, 'Cosa stampa il seguente frammento di codice?', 2, 29, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;&quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;persone_eta&lt;/span&gt; = {&lt;span style=&quot;color:#a31515&quot;&gt;&quot;mario&quot;&lt;/span&gt;:&lt;span style=&quot;color:#098658&quot;&gt;25&lt;/span&gt;,&lt;span style=&quot;color:#a31515&quot;&gt;&quot;luigi&quot;&lt;/span&gt;:&lt;span style=&quot;color:#098658&quot;&gt;32&lt;/span&gt;,&lt;span style=&quot;color:#a31515&quot;&gt;&quot;marta&quot;&lt;/span&gt;:&lt;span style=&quot;color:#098658&quot;&gt;20&lt;/span&gt;}&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;=&lt;span style=&quot;color:#a31515&quot;&gt;&quot;&quot;&lt;/span&gt;&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#af00db&quot;&gt;for&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;p&lt;/span&gt; &lt;span style=&quot;color:#af00db&quot;&gt;in&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;persone_eta&lt;/span&gt;:&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;+=&lt;span style=&quot;color:#001080&quot;&gt;p&lt;/span&gt;+&lt;span style=&quot;color:#267f99&quot;&gt;str&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;persone_eta&lt;/span&gt;[&lt;span style=&quot;color:#001080&quot;&gt;p&lt;/span&gt;])&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#795e26&quot;&gt;print&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;_____________________________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(111, 'Cosa stampa il seguente frammento di codice?', 2, 29, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;&quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;t&lt;/span&gt;=(&lt;span style=&quot;color:#098658&quot;&gt;3&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;0&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;1&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;l&lt;/span&gt;=&lt;span style=&quot;color:#267f99&quot;&gt;list&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;t&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;l&lt;/span&gt;.&lt;span style=&quot;color:#795e26&quot;&gt;append&lt;/span&gt;(&lt;span style=&quot;color:#098658&quot;&gt;5&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#af00db&quot;&gt;for&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;elem&lt;/span&gt; &lt;span style=&quot;color:#af00db&quot;&gt;in&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;t&lt;/span&gt;:&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &lt;span style=&quot;color:#795e26&quot;&gt;print&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;l&lt;/span&gt;[&lt;span style=&quot;color:#001080&quot;&gt;elem&lt;/span&gt;],&lt;span style=&quot;color:#001080&quot;&gt;end&lt;/span&gt;=&lt;span style=&quot;color:#a31515&quot;&gt;&quot; &quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;_______________________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(112, 'Come si accede al secondo elemento di una lista `x`?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(113, 'Quale di queste strutture &egrave; immutabile?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(114, 'Cosa restituisce `set([1, 2, 2, 3])`?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(115, 'Quale metodo aggiunge un elemento a una lista?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(116, 'Quale struttura dati associa chiavi a valori?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(117, 'Come si crea un insieme vuoto in Python?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(118, 'Cosa fa il metodo `keys()` in un dizionario?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(119, 'Quale operazione &egrave; valida per le tuple?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(120, 'Quale struttura dati &egrave; ordinata e modificabile?', 1, 29, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(121, 'Realizza in Python il seguente programma:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Si vuole realizzare una calcolatrice che trasformi un numero dato in formato binario, esadecimale o ottale in base a quanto scelto dall&amp;#39;utente.&lt;br /&gt;\r\nL&amp;#39;utente inserisce un numero ed il programma chiede se lo vuole trasformare in binario, in esadecimale o in ottale. Il programma legge in input la scelta dell&amp;#39;utente e restituisce in output il valore del numero trasformato nella base scelta.&lt;br /&gt;\r\nUtilizzare un elif per controllare la scelta dell&amp;#39;utente.&lt;/p&gt;\r\n', 1, 0, 3),
(122, 'Realizza in Python il seguente programma:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere in input due numeri all&amp;#39;utente, trasformarli in binario e dare in output il valore binario dei due numeri.&lt;br /&gt;\r\nFare la somma dei due numeri interi e dare in output il numero binario somma dei primi due numeri.&lt;br /&gt;\r\nDare poi in output anche il valore in ottale e in esadecimale della somma ottenuta.&lt;/p&gt;\r\n', 1, 0, 3),
(123, 'Scrivere il seguente programma in Python', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chidere in input all&amp;#39;utente una frase che rappresenti la prima riga di una poesia o di un libro famosi (esempi: &amp;quot;la donzelletta vien dalla campagna&amp;quot; o &amp;quot;nel mezzo del cammin di nostra vita&amp;quot;).&lt;br /&gt;\r\nControllare la lunghezza della frase ottenuta. Prendere la sottostringa che inizia dalla met&amp;agrave; della frase fino alla fine e darla in output. Se la sottostringa &amp;egrave; abbastanza lunga, stampare i caratteri della sottostringa che si trovano in posizione 3, 5 e 9 (quindi il quarto, il sesto e il decimo carattere)&lt;/p&gt;\r\n', 1, 0, 3),
(124, 'Scrivere il seguente programma in Python', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chidere in input all&amp;#39;utente una frase che rappresenti un verso di una canzone famosa (esempi: &amp;quot;il pomeriggio &amp;egrave; sempre azzurro&amp;quot; o &amp;quot;eravamo quattro amici al bar&amp;quot;).&lt;br /&gt;\r\nControllare la lunghezza della frase ottenuta. Prendere la sottostringa che va dall&amp;#39;inizio del verso fino ad un terzo dello stesso. Se la sottostringa &amp;egrave; abbastanza lunga, stampare i caratteri della sottostringa che si trovano in posizione 2, 4 e 8 (quindi il terzo, il quinto ed il nono carattere)&lt;/p&gt;\r\n', 1, 0, 3),
(125, 'Creare il seguente programma Python:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Scrivi un programma che prenda in input tre numeri e stampi il maggiore ed il minore tra essi.&lt;/p&gt;\r\n', 1, 0, 3),
(126, 'Creare il seguente programma Python:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Scrivi un programma che legga un carattere da tastiera e che dica se il carattere &amp;egrave; o meno una vocale.&lt;/p&gt;\r\n', 1, 0, 3),
(127, 'Creare il programma Python che risolva il seguente problema:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un verduriere ha comprato X quintali(1 quintale = 100 Kg) di patate a euro Y il quintale. Le ha rivendute tutte per euro Z.&lt;br /&gt;\r\nDare in input i quintali X comprati, la spesa&amp;nbsp; Y del verduriere al quintale ed il totale incassato dalla rivendita Z. Dare in output se il verduriere ha effettuato un guadagno o una perdita e quanto ha guadagnato/perso&lt;/p&gt;\r\n', 1, 0, 3),
(128, 'Creare in Python il programma che risolva il seguente problema:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un calzolaio vende X paia di pantofole, al prezzo medio di euro Y l&amp;#39;uno. Ha pure venduto Z paia di scarpe da signora e W da uomo per la somma complessiva di euro K.&lt;br /&gt;\r\nQuanto incassa per le pantofole vendute? Quale incasso totale ha fatto nella giornata?&lt;br /&gt;\r\nRiflettere su quali dati servono in input per risolvere il problema ed utilizzare il minor numero di input possibili per dare le due risposte in output.&lt;/p&gt;\r\n', 1, 0, 3),
(129, 'Creare il seguente programma Python:', 5, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;L&amp;#39;industria Nike deve acquistare il cuoio per rivestire i propri palloni da calcio. Un pallone &amp;egrave; una sfera con un certo raggio. L&amp;#39;industria vuole sapere quanto sar&amp;agrave; il costo per rivestire un certo numero di palloni da fornire al campionato di serie A.&lt;br /&gt;\r\nCreare un programma per l&amp;#39;industria che chieda in input:&lt;br /&gt;\r\n- il raggio dei palloni in cm&lt;br /&gt;\r\n- il numero di palloni da ricoprire&lt;br /&gt;\r\n- quanto costa il cuoio al cm quadrato&lt;br /&gt;\r\nE mostri in output il costo di ogni pallone ed il costo totale dei palloni. I due costi dovranno essere separati da un a capo e formattati in maniera da occupare esattamente lo spazio di 8 caratteri, con 2 cifre decimali&lt;/p&gt;\r\n\r\n&lt;p&gt;La superficie di una sfera si calcola con la formula: 4*3.14*raggio al quadrato&lt;/p&gt;\r\n', 1, 0, 3),
(130, 'Creare in Python il programma che risolva il seguente problema:', 5, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;L&amp;#39;azienda Modiano crea dadi da gioco. Deve creare dei nuovi dadi colorati di blu di Prussia e le serve un programma per calcolare i costi da sostenere per rifornire di dadi il casin&amp;ograve; di Venezia.&lt;br /&gt;\r\nCreare il programma per l&amp;#39;azienda chiedendo in input: il lato del dado in centimetri, quanti dadi deve creare, quanto costa la vernice al cm quadrato.&lt;br /&gt;\r\nIl programma deve dare in output il costo per ogni dado e il costo totale da pagare. I due dati devono stare su due righe diverse e devono essere formattati in maniera da occupare esattamente 6 caratteri con due cifre decimali.&lt;/p&gt;\r\n', 1, 0, 3),
(131, 'Scrivere il seguente programma in Python:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere in input all&amp;#39;utente una frase che rappresenti un proverbio famoso (esempi: &amp;quot;chi lascia la strada vecchia per quella nuova sa quel che lascia ma non sa quel che trova&amp;quot; o &amp;quot;chi va piano va sano e va lontano&amp;quot;). Controllare e dare in output la lunghezza della frase data in input. Chiedere all&amp;#39;utente un numero inferiore alla lunghezza della frase. Prendere la sottostringa che va dal numero inserito dall&amp;#39;utente fino alla fine del proverbio. Se la sottostringa &amp;egrave; abbastanza lunga, stampare i caratteri della sottostringa che si trovano in posizione 1, 3 e 5 (quindi il secondo, il quarto ed il sesto carattere).&lt;/p&gt;\r\n', 1, 0, 3),
(132, 'Creare in Python il programma che risolva il seguente problema:', 5, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;L&amp;#39;azienda Alimentari s.r.l. produce e vende conserve di pomodoro. Deve creare un nuovo tipo di confezione e le serve un programma per calcolare i costi da sostenere per produrre le conserve. Creare il programma per l&amp;#39;azienda chiedendo in input: quanti grammi di pomodoro contiene una confezione, quante confezioni deve produrre, quanto costa il pomodoro al chilo. Il programma deve dare in output il costo per ogni confezione e il costo totale da pagare. I due dati devono stare su due righe diverse e devono essere formattati in maniera da occupare esattamente 8 caratteri con due cifre decimali.&lt;/p&gt;\r\n', 1, 0, 3),
(133, 'Realizzare in Python:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Scrivi un programma che legga un carattere da tastiera e che dica se il carattere &amp;egrave; o meno una consonante.&lt;/p&gt;\r\n', 1, 0, 3),
(134, 'Realizza in Python il seguente programma:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere in input due numeri interi all&amp;#39;utente, trasformarli in ottale e dare in output il valore ottale dei due numeri. Fare la sottrazione dei due numeri in decimale e dare in output il numero ottale sottrazione dei primi due numeri. Dare in output anche il valore esadecimale della sottrazione.&lt;/p&gt;\r\n', 1, 0, 3),
(135, 'Scrivere il programma Python che risolve il seguente problema:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un&amp;#39;azienda che produce e vende candele artigianali deve calcolare i profitti ottenuti dalle vendite di un mese. Nel mese ha venduto N candele al prezzo di P euro l&amp;#39;una. Ogni candela ha un costo in cera di Q euro.&lt;br /&gt;\r\nScrivere un programma Python che, dati in input N, P e Q, calcoli il profitto ottenuto dalle vendite delle candele in un mese e il profitto totale ottenuto dall&amp;#39;azienda in un anno.&lt;/p&gt;\r\n', 1, 0, 3),
(136, 'Risolvi il seguente problema tramite Python:', 4, 30, 4, 0, 1, '2026-06-30', '&lt;p&gt;In una classe ci sono X studenti, di cui Y maschi e Z femmine. W% degli studenti &amp;egrave; stato promosso, i rimanenti hanno ottenuto una pagella insufficiente. Dare in output il numero di studenti promossi ed il numero di studenti insufficienti. Chiedere in input i soli dati necessarei alla risoluzione del problema. Effettuare un controllo sui dati in input affinch&amp;egrave; siano corretti e realistici.&lt;/p&gt;\r\n', 1, 0, 3),
(137, 'Cosa stampa il seguente frammento di codice?', 2, 30, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;&quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;=&lt;span style=&quot;color:#098658&quot;&gt;0&lt;/span&gt;&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#af00db&quot;&gt;for&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;c&lt;/span&gt; &lt;span style=&quot;color:#af00db&quot;&gt;in&lt;/span&gt; &lt;span style=&quot;color:#267f99&quot;&gt;range&lt;/span&gt;(&lt;span style=&quot;color:#098658&quot;&gt;0&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;15&lt;/span&gt;,&lt;span style=&quot;color:#098658&quot;&gt;3&lt;/span&gt;):&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp;&lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;=&lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;+&lt;span style=&quot;color:#001080&quot;&gt;c&lt;/span&gt;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp;&lt;span style=&quot;color:#795e26&quot;&gt;print&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;s&lt;/span&gt;,&lt;span style=&quot;color:#001080&quot;&gt;end&lt;/span&gt;=&lt;span style=&quot;color:#a31515&quot;&gt;&quot; &quot;&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;____________________________________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(138, 'Cosa si intende per ricorsione in programmazione?', 1, 31, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(139, 'Cosa si intende con ricorsione in programmazione?', 3, 31, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(140, 'Cosa stampa il frammento di codice seguente con una funzione ricorsiva?', 2, 31, 4, 0, 1, '2026-06-30', '&lt;div style=&quot;color: rgb(0, 0, 0); font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;&quot;&gt;\r\n&lt;div&gt;&lt;span style=&quot;color:#0000ff&quot;&gt;def&lt;/span&gt; &lt;span style=&quot;color:#795e26&quot;&gt;funzione_ricorsiva&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;somma&lt;/span&gt;):&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &lt;span style=&quot;color:#af00db&quot;&gt;if&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;somma&lt;/span&gt;&lt;=&lt;span style=&quot;color:#098658&quot;&gt;0&lt;/span&gt;:&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &nbsp; &nbsp; &lt;span style=&quot;color:#af00db&quot;&gt;return&lt;/span&gt; &lt;span style=&quot;color:#098658&quot;&gt;0&lt;/span&gt;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &lt;span style=&quot;color:#af00db&quot;&gt;else&lt;/span&gt;:&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &nbsp; &nbsp; &lt;span style=&quot;color:#001080&quot;&gt;x&lt;/span&gt;=&lt;span style=&quot;color:#001080&quot;&gt;somma&lt;/span&gt;+&lt;span style=&quot;color:#795e26&quot;&gt;funzione_ricorsiva&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;somma&lt;/span&gt;-&lt;span style=&quot;color:#098658&quot;&gt;2&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp; &nbsp; &nbsp; &lt;span style=&quot;color:#af00db&quot;&gt;return&lt;/span&gt; &lt;span style=&quot;color:#001080&quot;&gt;x&lt;/span&gt;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp; &nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#001080&quot;&gt;y&lt;/span&gt;=&lt;span style=&quot;color:#795e26&quot;&gt;funzione_ricorsiva&lt;/span&gt;(&lt;span style=&quot;color:#098658&quot;&gt;5&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&lt;span style=&quot;color:#795e26&quot;&gt;print&lt;/span&gt;(&lt;span style=&quot;color:#001080&quot;&gt;y&lt;/span&gt;)&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;&nbsp;&lt;/div&gt;\r\n\r\n&lt;div&gt;_________________________________________________________&lt;/div&gt;\r\n&lt;/div&gt;\r\n', 1, 0, 3),
(141, 'Descrivi la struttura dati a pila. Quale strategia di inserimento/rimozione usa per i dati? Quali sono le operazioni di modifica e ricerca che possiamo utilizzare all&rsquo;interno della pila? Qual &egrave; l&rsquo;idea per implementare la pila in un linguaggio di programmazione a tua scelta?', 3, 34, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(142, 'Descrivi la struttura dati lista concatenata, avvalendoti magari di un disegno. Come avviene l&#039;inserimento di un nuovo elemento nella lista?', 2, 34, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(143, 'Descrivi la struttura dati a coda. Quale strategia di inserimento/rimozione usa per i dati? Quali sono le operazioni di modifica e ricerca che possiamo utilizzare all&rsquo;interno della coda? Qual &egrave; l&rsquo;idea per implementare la coda in un linguaggio di programmazione a tua scelta?', 3, 34, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(144, 'Il seguente disegno rappresenta una struttura dati ad albero con nodi contenenti lettere. Indicare quali sono i nodi foglia, qual &egrave; il nodo radice e quali sono i nodi interni:', 1, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/albero.jpg&quot; style=&quot;height:286px; width:371px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(145, 'l seguente disegno rappresenta una struttura dati ad albero con nodi contenenti numeri. Indicare quali sono i nodi foglia, qual &egrave; il nodo radice e quali sono i nodi interni', 1, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/alberonum.jpg&quot; style=&quot;height:215px; width:247px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3);
INSERT INTO `ct_domande` (`id_domanda`, `domanda`, `punti`, `fk_argomento`, `fk_tipo_domanda`, `num_righe`, `fk_libro`, `data_creazione`, `ese_num`, `fk_utente`, `num_gruppo`, `livello_diff`) VALUES
(146, 'Disegnare come cambia il seguente albero binario di ricerca inserendo i nodi con i valori: 9, 16, 5, 2', 2, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/alberobin1.jpg&quot; style=&quot;height:197px; width:231px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(147, 'Disegnare come cambia il seguente albero binario di ricerca inserendo i nodi con i valori: 4, 17, 33, 51, 58, 67', 2, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/alberobin2.jpg&quot; style=&quot;height:360px; width:400px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(148, 'Dato il seguente albero scrivi la sequenza dei nodi nella visita in preordine e nella visita in postordine', 2, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/alberobin3.jpg&quot; style=&quot;height:250px; width:470px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_____________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_____________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(149, 'Dato il seguente albero con nodi contenenti numeri scrivi la sequenza dei nodi nella visita in preordine e nella visita in postordine', 2, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/alberobin4.jpg&quot; style=&quot;height:250px; width:444px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(150, 'Qual &egrave; la complessit&agrave; della ricerca in una lista concatenata non ordinata nel caso peggiore?', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(151, 'Quale operazione &egrave; pi&ugrave; efficiente in una lista concatenata rispetto a un array?', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(152, 'In una lista doppiamente concatenata ogni nodo contiene', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(153, 'In un albero binario quanti figli pu&ograve; avere al massimo un nodo?', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(154, 'Quale visita di un albero binario di ricerca produce valori ordinati crescenti?', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(155, 'Nel caso peggiore un albero binario di ricerca degenerato (inserendo valori crescenti uno dopo l&#039;altro) ha complessit&agrave; di ricerca pari a', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(156, 'Un grafo &egrave; definito da', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(157, 'In un grafo orientato il grado entrante di un nodo indica', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(158, 'Un albero &egrave;', 1, 34, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(159, 'Rifletti e rispondi: in una lista concatenata qual &egrave; la complessit&agrave; computazionale di inserire un nodo in testa? Se la lista ha solo un puntatore alla testa e non alla coda, qual &egrave; invece la complessit&agrave; di inserire un nodo in coda alla lista?', 2, 34, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(160, 'In una lista concatenata cosa succede se si perde il riferimento alla testa della lista? (Per sbaglio si setta a null il riferimento)', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(161, 'In quali casi una lista concatenata &egrave; preferibile a un array dinamico?', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(162, 'Rifletti: Quanti nodi pu&ograve; avere al massimo un albero binario di altezza 4 (l&#039;altezza &egrave; la distanza dalla radice alla foglia pi&ugrave; profonda, quindi 4 livelli di nodi in questo caso)? Prova a generalizzare con altezza h, creando una formula', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(163, 'Rifletti: qual &egrave; la complessit&agrave; computazionale della stampa completa di un albero binario con n nodi usando la visita in pre-ordine? Ed in post-ordine?', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(164, 'Cos&rsquo;&egrave; un albero binario di ricerca? Quali sono le due propriet&agrave; fondamentali che deve rispettare ogni nodo?', 2, 34, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(165, 'Qual &egrave; in genere la complessit&agrave; della ricerca di un elemento su un albero binario di ricerca? Come diventa se l&#039;inserimento degli elementi &egrave; stato fatto in ordine crescente (generando un albero detto degenerato)?', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(166, 'Confronta lista concatenata e BST per operazioni di ricerca, inserimento e cancellazione. Come avvengono e quale delle due strutture &egrave; la migliore?', 2, 34, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(167, 'Cos&rsquo;&egrave; un grafo? Da quali elementi &egrave; composto?', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(168, 'Qual &egrave; la differenza tra grafo orientato e non orientato?  Cosa si intende per grado di un nodo? Come cambia nei grafi orientati?', 2, 34, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(169, 'Cos&rsquo;&egrave; un grafo pesato?  Cosa si intende per cammino e per cammino minimo?', 2, 34, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(170, 'Rifletti: Confronta visita di un albero e visita dei nodi di un grafo: quali complicazioni nascono nei grafi?', 1, 34, 1, 2, 1, '2026-06-30', '', 1, 0, 3),
(171, 'Scrivi la matrice delle adiacenze per il grafo in figura:', 3, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;Weighted graph &middot; Hyperskill&quot; src=&quot;https://ucarecdn.com/a67cb888-aa0c-424b-8c7f-847e38dd5691/&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(172, 'Scrivi la matrice delle adiacenze per il grafo in figura:', 3, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;Soluzione&quot; src=&quot;https://solving.altervista.org/HTML/clip1430.png&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3);
INSERT INTO `ct_domande` (`id_domanda`, `domanda`, `punti`, `fk_argomento`, `fk_tipo_domanda`, `num_righe`, `fk_libro`, `data_creazione`, `ese_num`, `fk_utente`, `num_gruppo`, `livello_diff`) VALUES
(173, 'Dato il seguente codice HTML, scrivere l&#039;albero con il DOM HTML del documento. Scrivere la stampa in pre-ordine degli elementi dell&#039;albero.', 3, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;data:image/png;base64,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&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3);
INSERT INTO `ct_domande` (`id_domanda`, `domanda`, `punti`, `fk_argomento`, `fk_tipo_domanda`, `num_righe`, `fk_libro`, `data_creazione`, `ese_num`, `fk_utente`, `num_gruppo`, `livello_diff`) VALUES
(174, 'Per il seguente codice HTML, creare l&#039;albero del DOM. Inoltre scrivere quale sarebbe la sequenza di nodi della visita in post-ordine dell&#039;albero', 3, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;data:image/png;base64,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&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(175, 'Dato il seguente albero binario scrivi la visita in preordine ed in postordine dei nodi', 1, 34, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/albero_binario.jpg&quot; style=&quot;width:400px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;________________________________________________________________________________________________________&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(176, 'Quale operazione relazionale permette di scegliere solo le righe che rispettano una certa condizione?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(177, 'Quale operazione relazionale unisce tutte le righe di due tabelle con lo stesso schema?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(178, 'Quale operazione relazionale restituisce solo le righe comuni a due tabelle con lo stesso schema?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(179, 'Quale operazione relazionale restituisce le righe presenti in una tabella ma non nell&rsquo;altra?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(180, 'Quale operazione combina ogni riga di una tabella con ogni riga di un&rsquo;altra tabella?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(181, 'Quale operazione relazionale mette in relazione due tabelle basandosi su un attributo comune?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(182, 'Quale operazione relazionale pu&ograve; essere vista come una selezione applicata a un prodotto cartesiano?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(183, 'Se da una tabella Studenti voglio ottenere solo i nomi degli studenti, quale operazione devo usare?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(184, 'Se voglio ottenere gli studenti iscritti ad almeno un corso confrontando la tabella Studenti e la tabella Iscrizioni, quale operazione devo usare?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(185, 'Dalla tabella Studenti vogliamo estrarre solo gli studenti con et&agrave; maggiore di 20 anni. Quale operazione relazionale stiamo applicando?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(186, 'Dalla tabella Corsi vogliamo visualizzare solo i nomi dei corsi, senza gli altri attributi. Quale operazione relazionale usiamo?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(187, 'Abbiamo due tabelle: Clienti e Ordini. Vogliamo ottenere tutti gli ordini con il nome del cliente che li ha effettuati. Quale operazione usiamo?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(188, 'Abbiamo due tabelle: Studenti e StudentiErasmus, con lo stesso schema. Vogliamo ottenere la lista completa di studenti. Quale operazione relazionale usiamo?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(189, 'Abbiamo due tabelle: ClientiRegistrati e ClientiNewsletter. Vogliamo sapere quali clienti compaiono in entrambe. Quale operazione relazionale usiamo?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(190, 'Abbiamo due tabelle: ClientiRegistrati e ClientiNewsletter. Vogliamo sapere quali clienti sono registrati ma non iscritti alla newsletter. Quale operazione relazionale usiamo?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(191, 'Dalle tabelle Ordini e Prodotti vogliamo ottenere tutte le combinazioni possibili tra ordini e prodotti, senza condizioni. Quale operazione relazionale descrive questo?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(192, 'Quale operazione relazionale permette di selezionare solo alcune colonne di una tabella?', 1, 1, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(193, 'Qual &egrave; un algoritmo di ordinamento che confronta gli elementi due a due e scambia quelli fuori posizione?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(194, 'Quale algoritmo di ordinamento seleziona ripetutamente l&#039;elemento pi&ugrave; piccolo e lo sposta nella posizione corretta?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(195, 'Qual &egrave; un metodo di ricerca che divide iterativamente l&#039;array a meta e confronta l&#039;elemento cercato con quello centrale?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(196, 'Quale algoritmo di ordinamento suddivide ripetutamente a met&agrave; l&#039;array per poi ricomporlo ordinato?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(197, 'Qual &egrave; un metodo di ricerca che controlla elemento per elemento dall&#039;inizio alla fine dell&#039;array?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3);
INSERT INTO `ct_domande` (`id_domanda`, `domanda`, `punti`, `fk_argomento`, `fk_tipo_domanda`, `num_righe`, `fk_libro`, `data_creazione`, `ese_num`, `fk_utente`, `num_gruppo`, `livello_diff`) VALUES
(198, 'Quale &egrave; la complessit&agrave; dell&#039;algoritmo di ordinamento bubblesort?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(199, 'Qual e&#039; la complessita&#039; della ricerca lineare?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(200, 'Quale e&#039; la complessita&#039; della ricerca binaria?', 1, 2, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(201, 'Il nome NoSQL significa', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(202, 'I database NoSQL:', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(203, 'Cosa si intende con basically available per db NoSQL?', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(204, 'Cosa significa eventually consistent per un database NoSQL?', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(205, 'Il teorema di Brewer sostiene che:', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(206, 'Quale propriet&agrave; NON riesce ad avere un db relazionale rispetto al teorema di Brewer?', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(207, 'Perch&eacute; se ho Partition Tolerance e Availability non posso avere consistency?', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(208, 'Quale tra i seguenti NON &egrave; un tipo di db NoSQL?', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(209, 'Quale tra i seguenti NON &egrave; un DBMS NoSQL?', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(210, 'MongoDB utilizza documenti in formato:', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(211, 'Il DBMS NoSQL Cassandra utilizza una topologia:', 1, 4, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(212, 'Cos&#039;&egrave; un&#039;istanza di una classe? Cos&#039;&egrave; un attributo? Fare degli esempi', 3, 5, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(213, 'Qual &egrave; la differenza tra attributi identificatori e descrittori? Fare un esempio di ognuno', 3, 5, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(214, 'Qual &egrave; la differenza tra attributi identificatori e descrittori? Fare un esempio di un attributo identificatore e di un descrittore', 3, 5, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(215, 'Indicare e dare una breve descrizione di alcune tipologie o classificazioni per gli attributi di una classe', 3, 5, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(216, 'Disegnare con notazione UML la classe che rappresenta un Libro con i suoi attributi', 3, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(217, 'Disegnare con notazione UML la classe che rappresenta un Film con i suoi attributi', 3, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(218, 'Disegnare con notazione UML la classe che rappresenta uno SmartPhone con i suoi attributi', 3, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(219, 'Disegnare con notazione UML la classe che rappresenta un Quadro di una mostra con i suoi attributi', 3, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(220, 'Disegnare con notazione UML la classe che rappresenta una Bottiglia di Vino con i suoi attributi', 3, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(221, 'Crea il diagramma delle classi UML per la seguente realt&agrave;:', 7, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una singola stazione ferroviaria deve gestire treni in partenza e in arrivo tramite database.&lt;br /&gt;\r\nI treni che transitano per la stazione hanno un certo numero di vagoni ed un codice identificativo, un numero massimo di passeggeri che possono trasportare, un tipo di motore (elettrico o diesel), una tipologia (regionale, regionale veloce, intercity&hellip;).&lt;br /&gt;\r\nI treni che transitano per la stazione hanno un orario di partenza, un binario di partenza, una stazione di arrivo&nbsp;ed una stazione dalla quale sono partiti. Non interessa sapere tutte le fermate dei treni, solo da dove partono e dove arrivano.&lt;br /&gt;\r\nAl treno &egrave; associato un macchinista ed uno o pi&ugrave; controllori. Il macchinista lavora solo e sempre sullo stesso treno. I controllori possono lavorare su pi&ugrave; treni.&nbsp;&lt;br /&gt;\r\nSi vogliono inoltre salvare i dati dei clienti che acquistano i biglietti e del biglietto. Il biglietto nel nostro caso sar&agrave; valido per un certo giorno e per una stazione di partenza ed una di arrivo, quindi non va indicato l&#039;orario, &egrave; valido per tutta la giornata su quel percorso. Il biglietto &egrave; nominativo, quindi valido solo per il cliente che lo acquista.&lt;br /&gt;\r\n&lt;em&gt;Inserire un commento sulla scelta delle molteplicit&agrave;.&lt;/em&gt;&lt;br /&gt;\r\n&lt;strong&gt;Esempio di dato completo:&lt;/strong&gt;&lt;br /&gt;\r\nLa stazione che deve gestire i treni &egrave; la stazione di Verona. Il treno codice 45 &egrave; un intercity con 6 vagoni e pu&ograve; trasportare al massimo 300 passeggeri, ha un motore elettrico. Parte alle ore 15.00 dalla stazione di Verona con destinazione Padova, arriva dalla stazione di Milano. Il macchinista &egrave; Carlo Verdi ed i controllori sono Mario Rossi e Veronica Gialli. Il cliente Fabio Bianchi acquista un biglietto per il 16/01/2024 per le stazioni di partenza/arrivo: Firenze - Verona.&lt;/p&gt;\r\n', 1, 0, 3),
(222, 'Dopo aver raffinato il diagramma delle classi sviluppato al punto 1, creare il modello logico conseguente, inserendo chiavi primarie, chiavi esterne e tipo dei campi per ogni tabella da creare.', 3, 5, 4, 0, 1, '2026-06-30', '', 1, 0, 3),
(223, 'Disegnare il diagramma UML delle classi per la seguente realt&agrave;:', 7, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un servizio di noleggio auto vuole registrare i dati necessari all&rsquo;attivit&agrave; tramite un database.&lt;br /&gt;\r\nIl servizio vuole registrare i dati riguardanti le automobili in suo possesso (marca, modello, km percorsi, colore, tipo di motore) ed i dati delle persone che noleggiano le auto.&lt;br /&gt;\r\nIl noleggio avviene in una certa data, per un certo numero di giorni.&lt;br /&gt;\r\nIl servizio vuole registrare anche&nbsp;i dati del personale, associando il noleggio effettuato alla persona che ha fatto sottoscrivere il contratto di noleggio. Va registrato inoltre il pagamento finale effettuato alla riconsegna dell&rsquo;automobile, con l&rsquo;importo pagato ed il metodo di pagamento, associando la riconsegna all&rsquo;impiegato che ritira l&rsquo;automobile e fa effettuare il pagamento.&lt;br /&gt;\r\n&lt;em&gt;Inserire un commento sulla scelta delle molteplicit&agrave;.&lt;/em&gt;&lt;br /&gt;\r\n&lt;strong&gt;Esempio di dato completo:&lt;/strong&gt; Il signor Mario Rossi, con CF RSSMRA78C12G892K noleggia il giorno 24 di dicembre 2022 per 10 giorni l&rsquo;auto Fiat Panda rossa con motore a metano, km percorsi 18.400. Il contratto di noleggio &egrave; stato fatto sottoscrivere dall&rsquo;impiegato Giuseppe Verdi. Alla fine del noleggio, dopo 10 giorni, il signor Mario Rossi restituisce l&rsquo;auto all&rsquo;impiegata Valeria Bruni, pagando un importo di 450&euro; in contanti.&lt;/p&gt;\r\n', 1, 0, 3),
(224, 'Crea il modello concettuale con diagramma delle classi UML per la seguente realt&agrave;:', 7, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una singola stazione ferroviaria deve gestire treni in partenza e in arrivo tramite database.&lt;br /&gt;\r\nI treni che transitano per la stazione hanno un certo numero di vagoni ed un codice identificativo, un numero massimo di passeggeri che possono trasportare, un tipo di motore (elettrico o diesel), una tipologia (regionale, regionale veloce, intercity&hellip;).&lt;br /&gt;\r\nI treni che transitano per la stazione hanno un orario di partenza, un binario di partenza, una stazione di arrivo&nbsp;ed una stazione dalla quale sono partiti. Non interessa sapere tutte le fermate dei treni, solo da dove partono e dove arrivano.&lt;br /&gt;\r\nAl treno &egrave; associato un macchinista ed uno o pi&ugrave; controllori. Il macchinista lavora solo e sempre sullo stesso treno. I controllori possono lavorare su pi&ugrave; treni.&nbsp;&lt;br /&gt;\r\n&lt;em&gt;Inserire un commento sulla scelta delle molteplicit&agrave;.&lt;/em&gt;&lt;br /&gt;\r\n&lt;strong&gt;Esempio di dato completo:&lt;/strong&gt;&lt;br /&gt;\r\nLa stazione che deve gestire i treni &egrave; la stazione di Verona. Il treno codice 45 &egrave; un intercity con 6 vagoni e pu&ograve; trasportare al massimo 300 passeggeri, ha un motore elettrico. Parte alle ore 15.00 dalla stazione di Verona con destinazione Padova, arriva dalla stazione di Milano. Il macchinista &egrave; Carlo Verdi ed i controllori sono Mario Rossi e Veronica Gialli.&lt;/p&gt;\r\n', 1, 0, 3),
(225, 'Disegnare il diagramma delle classi UML per la seguente realt&agrave;:', 7, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una fumetteria vuole registrare i dati riguardanti il negozio su database.&lt;br /&gt;\r\nLa fumetteria, gestita da svariati dipendenti, vuole salvare i dati dei fumetti in suo possesso, con titolo, numero, data di pubblicazione, numero di pagine, prezzo. La fumetteria vende anche gadget, suddivisi in action figures (pupazzi) e magliette dei fumetti e vuole salvare i dati anche di questi oggetti, in particolare vuole conoscere quanti pezzi ha a disposizione di ogni oggetto. La fumetteria vuole tener traccia anche delle vendite, indicando i dati che vengono emessi con lo scontrino: fumetto o oggetto venduto, quantit&agrave;, prezzo, totale, data scontrino. Vuole anche registrare nome e cognome del cliente che ha acquistato i prodotti. Si devono inoltre salvare i dati dei dipendenti e dei giorni nei quali lavorano al negozio.&lt;br /&gt;\r\n&lt;em&gt;Inserire un commento sulla scelta delle molteplicit&agrave;.&lt;/em&gt;&lt;br /&gt;\r\n&lt;strong&gt;Esempio di dato completo&lt;/strong&gt;: la fumetteria ha a disposizione il fumetto &ldquo;Superman&rdquo; numero 2, pubblicato il 5/8/1965 con 30 pagine e che vende a 50&euro;. Vende l&rsquo;action figure di Superman, del peso di 70g a 30&euro; e ne ha 2 a disposizione. In magazzino ha anche 10 magliette di Superman, taglia L, che vende a 15&euro; l&rsquo;una. Il signor Mario Rossi acquista il fumetto di Superman, una action figure e una maglietta di Superman, per un totale di 95&euro; il 10/12/2022. I dipendenti che lavoravano il 10/12/2022 erano Giuseppe Verdi e Maria Bianchi.&lt;/p&gt;\r\n', 1, 0, 3),
(226, 'In un diagramma concettuale, quale chiave identifica univocamente ogni istanza di una classe?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(227, 'Una chiave primaria &egrave; composta da pi&ugrave; attributi. Come viene chiamata?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(228, 'Quale tra queste &egrave; una chiave che pu&ograve; essere scelta come primaria ma non lo &egrave; ancora?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(229, 'Nel modello concettuale usiamo la notazione `fk_Ordine`. Che tipo di chiave &egrave;?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(230, 'Nel modello concettuale usiamo l&rsquo;attributo `id_Cliente` come chiave. Che tipo di chiave &egrave;?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(231, 'Quale affermazione &egrave; corretta riguardo alle chiavi artificiali?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(232, 'In un&#039;associazione tra &quot;Studente&quot; e &quot;Corso&quot;, l&#039;uso di `fk_Studente` in &quot;Iscrizione&quot; rappresenta...', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(233, 'Qual &egrave; lo scopo di una chiave esterna?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(234, 'Quando una chiave esterna `fk_Cliente` &egrave; presente nella classe &quot;Ordine&quot;, cosa rappresenta?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(235, 'Quale tra queste NON pu&ograve; mai essere usata come chiave primaria?', 1, 5, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(236, 'Disegna il diagramma delle classi UML per la seguente realt&agrave;:', 2, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;Vi sono delle aziende che hanno degli impiegati. Le aziende hanno un nome ed un indirizzo, gli impiegati hanno nome, cognome, stipendio lordo. Per gli impiegati esiste un metodo di calcolo dello stipendio netto, che tiene conto internamente delle ritenute fiscali e previdenziali e che restituisce lo stipendio netto a partire da quello lordo. Un impiegato lavora per una sola azienda, in un&#039;azienda lavorano molti impiegati.&lt;/p&gt;\r\n', 1, 0, 3),
(237, 'Disegna il diagramma delle classi UML per la seguente realt&agrave;:', 2, 5, 4, 0, 1, '2026-06-30', '&lt;p&gt;Nelle scuole insegnano diversi professori. I professori hanno nome, cognome ed indirizzo email. Le scuole hanno un nome ed un indirizzo. Un professore lavora su una sola scuola. I professori hanno anche un metodo per la modifica dell&#039;email con una nuova, il metodo restituisce un valore booleano: true per indicare che la modifica &egrave; avvenuta con successo oppure false se c&#039;&egrave; un errore nel formato dell&#039;email (manca @ o punto)&lt;/p&gt;\r\n', 1, 0, 3),
(238, 'Un&#039;azienda elettrica ha stabilito le seguenti tariffe:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;table border=&quot;1&quot; cellpadding=&quot;1&quot; cellspacing=&quot;1&quot; style=&quot;width:100%&quot;&gt;\r\n	&lt;thead&gt;\r\n		&lt;tr&gt;\r\n			&lt;th style=&quot;background-color:#efefef; width:50%&quot;&gt;KILOWATT ORA&lt;/th&gt;\r\n			&lt;th style=&quot;background-color:#efefef; width:50%&quot;&gt;COSTO IN EURO&lt;/th&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/thead&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;0 - 500&lt;/td&gt;\r\n			&lt;td&gt;%%18,28?? euro&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;501 - 1000&lt;/td&gt;\r\n			&lt;td&gt;%%28,45?? euro + 0.0%%3,9?? euro per ogni kilowatt sopra i 500&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;1001 e oltre&lt;/td&gt;\r\n			&lt;td&gt;%%45,65?? euro + 0.1%%0,9?? euro per ogni kilowatt sopra i 1000&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Creare il diagramma a blocchi che, dato il consumo mensile, calcoli e stampi l&#039;importo della bolletta (i kilowatt non possono essere minori di 0)&lt;/p&gt;\r\n', 1, 0, 3),
(239, 'Creare con Flowgorithm il diagramma a blocchi che risolva il seguente problema', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Si deve dipingere una parete rettangolare di base B centimetri e altezza H centimetri. Inserire un controllo affinch&egrave; base ed altezza non siano miinori di 0. Il colore scelto per dipingere la parete costa C euro per centimetro quadrato, inserire un controllo affinch&egrave; non venga inserito un costo negativo. Creare un diagramma a blocchi che calcoli il costo per dipingere una qualsiasi parete, leggendo in input il valore della base e dell&rsquo;altezza della parete e il costo della pittura.&lt;/p&gt;\r\n', 1, 0, 3),
(240, 'Dati due numeri naturali A e B, con A diverso da B, aggiungere al pi&ugrave; piccolo la somma dei due numeri', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Esempio: se A &egrave; 5 e B &egrave; 7, allora il pi&ugrave; piccolo &egrave; A e ad A aggiungiamo A+B, quindi A = 5 + (5+7) = 17, B rimane uguale. Alla fine si danno in output A e B.&lt;/p&gt;\r\n', 1, 0, 3),
(241, 'Risolvere il seguente problema con uun diagramma a blocchi e ciclo indefinito', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una lumaca si trova in fondo ad un pozzo fondo X metri. Chiedere in input all&amp;#39;utente l&amp;#39;altezza del pozzo e verificare che sia maggiore di 5, dare un errore altrimenti. La lumaca vuole uscire dal pozzo. Durante il giorno la lumaca riesce a salire di 3 metri, purtoppo, quando dorme durante la notte, scivola indietro di 2 metri. Dopo quanti giorni la lumaca riuscir&amp;agrave; ad uscire dal pozzo?&lt;/p&gt;\r\n', 1, 0, 3),
(242, 'Risolvere il seguente problema utilizzando un diagramma a blocchi con ciclo indefinito', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una lumaca si trova in fondo ad un pozzo fondo X metri. Chiedere in input all&#039;utente l&#039;altezza del pozzo e verificare che sia maggiore di 5, dare un errore altrimenti. La lumaca vuole uscire dal pozzo. Durante il giorno la lumaca riesce a salire di 3 metri, purtoppo, quando dorme durante la notte, scivola indietro di 2 metri. Dare in output dove si trova la lumaca alla fine di ogni giorno (prima di scivolare la notte) e dopo quanti giorni riesce ad uscire dal pozzo. Leggere bene l&#039;output per dare la risposta finale correttamente.&lt;/p&gt;\r\n', 1, 0, 3),
(243, 'Un&#039;autonoleggio applica le seguenti tariffe:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;table border=&quot;1&quot; cellpadding=&quot;1&quot; cellspacing=&quot;1&quot; style=&quot;width:100%&quot;&gt;\r\n	&lt;thead&gt;\r\n		&lt;tr&gt;\r\n			&lt;th style=&quot;background-color:#efefef; width:50%&quot;&gt;GIORNI NOLEGGIO&lt;/th&gt;\r\n			&lt;th style=&quot;background-color:#efefef; width:50%&quot;&gt;COSTO NOLEGGIO&lt;/th&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/thead&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;1-5 giorni&lt;/td&gt;\r\n			&lt;td&gt;%%2,4??0 euro al giorno&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;5-10 giorni&lt;/td&gt;\r\n			&lt;td&gt;%%5,9??0 euro al giorno&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;oltre i 10 giorni&lt;/td&gt;\r\n			&lt;td&gt;%%10,16??0 euro al giorno + un fisso di %%10,14??0 euro&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Dati i giorni di noleggio, calcolare e dare in output quanto verr&agrave; a costare il noleggio dell&#039;auto (i giorni non possono essere minori di 1)&lt;/p&gt;\r\n', 1, 0, 3),
(244, 'Dati due numeri naturali A e B, con A diverso da B, aggiungere al maggiore dei due la moltiplicazione dei due numeri', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Esempio: se A &egrave; 5 e B &egrave; 7, il maggiore &egrave; B, quindi B diventa B = B+(A*B) = 7+(5*7)=42&lt;/p&gt;\r\n', 1, 0, 3),
(245, 'Creare con Flowgorithm il diagramma a blocchi che risolva il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un sarto deve creare dalla stoffa una tovaglia per un tavolo rotondo. Il tavolo ha un diametro di X centimetri. La stoffa deve essere di 1 metro pi&ugrave; lunga rispetto al bordo del tavolo per avere una tovaglia che scenda dai bordi. La stoffa ha un costo in euro al metro quadrato. Chiedere in input all&#039;utente il diametro in centimetri del tavolo ed il costo al metro quadro della tovaglia. Dare in output il costo totale della tovaglia.&lt;/p&gt;\r\n\r\n&lt;p&gt;Come valore di pi greco usare 3.14&lt;/p&gt;\r\n', 1, 0, 3),
(246, 'Utilizzare un diagramma a blocchi e un ciclo while per risolvere il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;La lepre e la tartaruga fanno una gara. La lunghezza del percorso &egrave; di X metri. La tartaruga ogni 10 minuti riesce a percorrere %%3,5?? metri. La lepre, invece, percorre %%10,15?? metri in 10 minuti. Chiedere in input all&#039;utente la lunghezza X del percorso, che deve essere di almeno 50 metri e dare in output dopo quanto tempo la lepre e la tartaruga riescono a tagliare il traguardo, dando in output dove si trovano ogni 10 minuti.&lt;/p&gt;\r\n', 1, 0, 3),
(247, 'Un mutuo agevolato in banca applica le seguenti tariffe:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;table border=&quot;1&quot; cellpadding=&quot;1&quot; cellspacing=&quot;1&quot; style=&quot;width:100%&quot;&gt;\r\n	&lt;thead&gt;\r\n		&lt;tr&gt;\r\n			&lt;th scope=&quot;col&quot;&gt;DURATA IN ANNI&lt;/th&gt;\r\n			&lt;th scope=&quot;col&quot;&gt;COSTI DA RIPAGARE&lt;/th&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/thead&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;1-10 anni&lt;/td&gt;\r\n			&lt;td&gt;%%18,25??0 euro al mese&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;11-20 anni&lt;/td&gt;\r\n			&lt;td&gt;%%16,18??0 euro al mese + %%8,12??00 euro all&#039;anno&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;Oltre i 20 anni&lt;/td&gt;\r\n			&lt;td&gt;%%12,16??0 euro al mese + %%4,7??00 euro all&#039;anno&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Dato in input il numero di anni del mutuo, che non pu&ograve; essere negativo, calcolare il costo totale da ripagare alla banca per il mutuo concesso&lt;/p&gt;\r\n', 1, 0, 3),
(248, 'Dati due numeri naturali A e B, con A diverso da B, controllare e dare in output se il maggiore dei due &egrave; pi&ugrave; grande del primo di almeno 5', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Esempio: se A &egrave; 5 e B &egrave; 7 allora il pi&ugrave; grande &egrave; B ed &egrave; pi&ugrave; grande di A di 2, quindi do in output la scritta &quot;B &egrave; il maggiore e non supera di 5 il valore di A&quot;&lt;/p&gt;\r\n', 1, 0, 3),
(249, 'Creare con Flowgorithm il diagramma a blocchi che risolva il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un agricoltore possiede un campo a forma di trapezio. Vuole coltivare il campo ad orzo, che ha un costo al kilo. Per ogni metro quadro del campo vanno piantati un tot di chili di semi di orzo. Dati in input la base maggiore, la base minore e l&#039;altezza del trapezio, il costo al kilo dei semi d&#039;orzo e i kili di semi di orzo per metro quadrato (tutti devono essere maggiori di 0), calcolare e dare in output il costo di coltivazione del campo ad orzo.&lt;/p&gt;\r\n\r\n&lt;p&gt;Vi ricordo che un trapezio ha la seguente forma:&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/trapezio-isoscele.png&quot; style=&quot;height:123px; width:250px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(250, 'Risolvere il seguente problema utilizzando un diagramma a blocchi con ciclo indefinito:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;In Africa ogni giorno una gazzella si alza e dovr&agrave; correre pi&ugrave; del leone. Sapendo che la gazzella corre per %%5,8?? metri al secondo e che il leone corre %%9,12?? metri al secondo e che la gazzella ha un vantaggio di X metri, calcolare dopo quanti secondi il leone raggiunge la gazzella, stampando le posizioni di leone e gazzella dopo ogni secondo. I metri di vantaggio della gazzella vanno inseriti dall&#039;utente e non possono essere inferiori a 80.&lt;/p&gt;\r\n', 1, 0, 3),
(251, 'Crea il diagramma a blocchi per risolvere il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Il Pentagono (foto sotto) decide di tappezzare il tetto dell&#039;edificio di pannelli solari. Ogni pannello solare ha un certo costo in dollari e in un metro quadrato possono stare 3 pannelli solari. Sapendo che l&#039;area di un pentagono si calcola come (Perimetro*Apotema)/2, chiedere in input all&#039;utente lato e apotema del pentagono esterno, lato e apotema del pentagono interno, calcolare e dare in output il costo totale per riempire il tetto di pannelli. Controllare l&#039;input dell&#039;utente affinch&egrave; sia corretto (il pentagono interno &egrave; pi&ugrave; piccolo dell&#039;esterno).&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/pentagono-ufo.jpg&quot; style=&quot;float:left; height:174px; width:246px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(252, 'Creare con Flowgorithm il diagramma a blocchi che risolva il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Carlo ha comprato una piramide souvenir dell&#039;Egitto. La piramide &egrave; a base quadrata. La piramide &egrave; di colore giallo, ma Carlo la vuole ridipingere di blu con colori a tempera, ogni tubetto di tempera ha un certo costo. Scrivi il programma che aiuti Carlo a capire quanto gli coster&agrave; ridipingere la sua piramide souvenir. Il programma deve richiedere in input il lato del quadrato di base della piramide (in centimetri), l&#039;altezza dei triangoli che costituiscono i lati della piramide (in centimetri), il costo di un tubetto di tempera,&nbsp; quanti cm quadrati si riesce a dipingere con un tubetto. Tutti i dati di input devono essere maggiori di 0, altrimenti il programma deve dare un errore. Dare in output il numero di tubetti di tempera necessari per dipingere la piramide ed il loro costo totale.&lt;/p&gt;\r\n', 1, 0, 3),
(253, 'Dati due numeri naturali A e B, con A diverso da B, dire se il pi&ugrave; piccolo dei due &egrave; pi&ugrave; piccolo di almeno 4 rispetto al pi&ugrave; grande', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Esempio: A=9, B=6 il pi&ugrave; piccolo &egrave; B ed &egrave; pi&ugrave; piccolo di 3. Quindi il programma scrive qualcosa del tipo &quot;Il pi&ugrave; piccolo &egrave; B ed &egrave; pi&ugrave; piccolo di A di un valore inferiore a 4&quot;.&lt;/p&gt;\r\n', 1, 0, 3),
(254, 'Una compagnia di scavatori applica le seguenti tariffe in base al terreno da scavare:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;table border=&quot;1&quot; cellpadding=&quot;1&quot; cellspacing=&quot;1&quot; style=&quot;width:95%&quot;&gt;\r\n	&lt;thead&gt;\r\n		&lt;tr&gt;\r\n			&lt;th scope=&quot;col&quot; style=&quot;background-color:#efefef&quot;&gt;Metri quadri da scavare&lt;/th&gt;\r\n			&lt;th scope=&quot;col&quot; style=&quot;background-color:#efefef&quot;&gt;Costo&lt;/th&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/thead&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td style=&quot;width:50%&quot;&gt;1 - 120&lt;/td&gt;\r\n			&lt;td style=&quot;width:50%&quot;&gt;%%20,39?? euro al metro quadro&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td style=&quot;width:50%&quot;&gt;121 - 400&lt;/td&gt;\r\n			&lt;td style=&quot;width:50%&quot;&gt;%%40,49?? euro al metro quadro + %%5,15?? euro per ogni metro quadro oltre i 120&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;Oltre i 401&lt;/td&gt;\r\n			&lt;td&gt;%%50,65?? euro al metro quadro + %%16,22?? euro per ogni metro quadro oltre i 400&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;Leggere in input i metri quadrati da scavare e dare in output il costo richiesto per scavare quei metri quadrati. Il programma deve dare un errore se i metri sono inferiori a 1. I dati in tabella sono fissi e non vanno letti in input.&lt;/p&gt;\r\n', 1, 0, 3),
(255, 'Risolvere il seguente problema utilizzando un ciclo indefinito (Achille e la tartaruga)', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Achille (famoso erore dell&#039;Iliade) e la tartaruga fanno una gara. Dato che Achille &egrave; pi&ugrave; veloce concede alla tartaruga un vantaggio, che parte pi&ugrave; avanti nel percorso. Il percorso &egrave; di %%3,6??00 metri. Leggere il vantaggio della tartaruga in input (deve essere maggiore di 20 e minore di 250). La tartaruga viaggia a %%3,8?? metri al secondo. Ogni secondo Achille dimezza lo svantaggio che ha sulla tartaruga (usare variabili intere). Il programma deve dirmi se Achille riesce a superare la tartaruga prima del traguardo e se lo fa a quanti secondi &egrave; avvenuto il sorpasso. Il programma continua fino a che la tartaruga non taglia il traguardo, dicendo a quanti secondi lo taglia.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio di dimezza lo svantaggio: la tartaruga &egrave; a 104, Achille &egrave; a 0, il vantaggio della tartaruga &egrave; di 104.&lt;/p&gt;\r\n\r\n&lt;p&gt;Achille &egrave; a 0, la sua posizione diviene: 104 meno la posizione di Achille, il tutto diviso 2, quindi Achille arriva in posizione 52 metri= 0+52.&lt;/p&gt;\r\n\r\n&lt;p&gt;Prossima iterazione: la taratruga va avanti di 4, arriva a 108, il suo vantaggio su Achille &egrave; 108-52 = 56.&lt;/p&gt;\r\n\r\n&lt;p&gt;Achille dimezza lo svantaggio, cio&egrave; alla sua posizone di 52 aggiungo&nbsp;56/2 = 28, che &egrave; il vantaggio della tartaruga dimezzato, quindi arriva a 80 metri. E cos&igrave; via.&lt;/p&gt;\r\n', 1, 0, 3),
(256, 'Disegna il diagramma a blocchi per il seguente programma:', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Dato un elenco di 8 numeri inseriti dall&amp;#39;utente in input, scegli quelli che sono maggiori di 10 e minori di 100. Di questi calcola la somma e stampa il risultato.&lt;/p&gt;\r\n', 1, 0, 3),
(257, 'Crea il diagramma a blocchi per risolvere il seguente problema:', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Date 6 coppie di numeri interi in input dall&amp;#39;utente contare quelle che generano un prodotto pari. (per vedere se un numero &amp;egrave; pari possiamo controllare il resto della divisione per 2, usando l&amp;rsquo;operatore %)&lt;/p&gt;\r\n', 1, 0, 3),
(258, 'Creare il seguente con un diagramma a blocchi:', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Scrivere un programma che verifica se un numero &amp;egrave; un numero primo. Esempio: 13 &amp;egrave; primo perch&amp;egrave; non &amp;egrave; divisibile n&amp;egrave; per 2, n&amp;egrave; per 3, n&amp;egrave; per 4, n&amp;egrave; per 5..... Ricordo che il resto della divisione si ottiene con l&amp;#39;operatore %&lt;/p&gt;\r\n', 1, 0, 3),
(259, 'Un programmatore pratica le seguenti tariffe per la creazione di nuovi programmi in base al numero di giornate che comporta il lavoro:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;table border=&quot;1&quot; cellpadding=&quot;1&quot; cellspacing=&quot;1&quot; style=&quot;width:100%&quot;&gt;\r\n	&lt;thead&gt;\r\n		&lt;tr&gt;\r\n			&lt;th style=&quot;background-color:rgb(239, 239, 239); width:50%&quot;&gt;&lt;span style=&quot;font-size:15px&quot;&gt;Giornate di lavoro&lt;/span&gt;&lt;/th&gt;\r\n			&lt;th style=&quot;background-color:rgb(239, 239, 239); width:50%&quot;&gt;&lt;span style=&quot;font-size:15px&quot;&gt;COSTO IN EURO&lt;/span&gt;&lt;/th&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/thead&gt;\r\n	&lt;tbody&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;&lt;span style=&quot;font-size:15px&quot;&gt;1 - 10&lt;/span&gt;&lt;/td&gt;\r\n			&lt;td&gt;&lt;span style=&quot;font-size:15px&quot;&gt;%%15,30?? euro a giornata&lt;/span&gt;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;&lt;span style=&quot;font-size:15px&quot;&gt;11 - 30&lt;/span&gt;&lt;/td&gt;\r\n			&lt;td&gt;&lt;span style=&quot;font-size:15px&quot;&gt;%%25, 40?? euro a giornata + %%5,10?? euro per ogni giornata oltre le 20&lt;/span&gt;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n		&lt;tr&gt;\r\n			&lt;td&gt;&lt;span style=&quot;font-size:15px&quot;&gt;31 e oltre&lt;/span&gt;&lt;/td&gt;\r\n			&lt;td&gt;&lt;span style=&quot;font-size:15px&quot;&gt;%%45,65?? euro a giornata - il %%5,10?? % sul totale, che applica come sconto&lt;/span&gt;&lt;/td&gt;\r\n		&lt;/tr&gt;\r\n	&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\nCreare il diagramma a blocchi che, dato il numero di giorni di lavoro, calcoli e stampi l&#039;importo totale che il programmatore chieder&agrave; per effettuare il lavoro. Le giornate di lavoro devono essere come minimo 1, dare errore in caso contrario&lt;/p&gt;\r\n', 1, 0, 3),
(260, 'Creare con Flowgorithm il diagramma a blocchi che risolva il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Mario ha a disposizione due piedistalli a forma di cubo sui quali sistemare delle fioriere all&#039;esterno della sua casa. Vuole dipingere il primo cubo di azzurro ed il secondo di giallo, in modo da intonarsi con i fiori. Creare un programma che indichi quanto spender&agrave; Mario per dipingere i due cubi, chiedendo in input: il lato dei 2&nbsp;cubi&nbsp;in metri, quanti metri quadrati si riesce a dipingere con un barattolo di vernice, quanto costa ogni barattolo di vernice gialla e quanto ogni barattolo di&nbsp;azzurra. I dati in inputnon possono essere minori o uguali a 0, se uno di essi&nbsp;&egrave; minore o uguale a 0 segnalare un errore.&lt;/p&gt;\r\n', 1, 0, 3),
(261, 'Risolvi il seguente problema utilizzando il ciclo FOR', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere in input da tastiera %%3,8??&nbsp;numeri e indicare in output quanti sono i numeri letti pari e quanti sono i numeri letti dispari&lt;/p&gt;\r\n', 1, 0, 3),
(262, 'Usare il ciclo FOR per implementare il seguente programma', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Date %%3,7?? coppie di numeri naturali in input, dire in output quante hanno lo stesso valore di parit&agrave;. Quindi dire quante coppie hanno 2 numeri pari o due numeri dispari e quante coppie hanno un pari e un dispari.&lt;/p&gt;\r\n', 1, 0, 3),
(263, 'Risolvi il seguente problema utilizzando il ciclo definito FOR:', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Date %%3,7?? coppie di numeri interi, stampare la media dei primi numeri della coppia e la somma dei secondi numeri della coppia. Dire se la somma finale &egrave; pari o dispari, la media deve comparire con la virgola&lt;/p&gt;\r\n', 1, 0, 3),
(264, 'Creare il programma che implementi il seguente algoritmo, con ciclo FOR', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Leggere %%4,9?? numeri da tastiera, fare la somma dei pari ed il prodotto dei dispari e dare in output il primo ed il secondo valore calcolato.&nbsp;&lt;/p&gt;\r\n', 1, 0, 3),
(265, 'Utilizzando un doppio ciclo, realizza il programma seguente:', 6, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Stampare svariati conti alla rovescia, a partire da un numero dato in input dall&#039;utente. Il primo conto&nbsp;alla rovescia parte&nbsp;dal numero dato in input e arriva&nbsp;fino a 0. Il secondo dal numero - 1 fino a 0, il terzo dal numero - 2 fino a 0 e cos&igrave; via fino a che il conto alla rovescia non parte da 1. Continuare a chiedere in input il numero fino a che l&#039;utente non inserisca un numero maggiore di 1, dare un errore e richiedere il numero se l&#039;utente lo inserisce minore o uguale a 1.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio di esecuzione (in grassetto input dell&#039;utente):&lt;/p&gt;\r\n\r\n&lt;p&gt;Inserire numero: &lt;strong&gt;1&lt;/strong&gt;&lt;br /&gt;\r\nErrore! Il numero deve essere minimo 2&lt;br /&gt;\r\nInserire numero: &lt;strong&gt;0&lt;/strong&gt;&lt;br /&gt;\r\nErrore! Il numero deve essere minimo 2&lt;br /&gt;\r\nInserire numero: &lt;strong&gt;5&lt;/strong&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;5 4 3 2 1 0&lt;br /&gt;\r\n4 3 2 1 0&lt;br /&gt;\r\n3 2 1 0&lt;br /&gt;\r\n2 1 0&lt;br /&gt;\r\n1 0&lt;/p&gt;\r\n', 1, 0, 3),
(266, 'Utilizzando un doppio ciclo realizzare il programma seguente:', 6, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere un input all&#039;utente. Continuare a chiedere l&#039;input all&#039;utente fino a che non inserisce un numero inferiore a 10 e superiore a 1. Se il numero inserito non &egrave; corretto segnalare un errore e chiedere nuovamente il numero.&nbsp;Il programma deve stampare le tabelline dei numeri da 1 al numero letto.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio di esecuzione (in grassetto l&#039;input dell&#039;utente)&lt;/p&gt;\r\n\r\n&lt;p&gt;Inserire un numero: &lt;strong&gt;12&lt;/strong&gt;&lt;br /&gt;\r\nErrore! Il numero deve essere compreso tra 1 e 10&lt;br /&gt;\r\nInserire un numero: &lt;strong&gt;0&lt;/strong&gt;&lt;br /&gt;\r\nErrore! Il numero deve essere compreso tra 1 e 10&lt;br /&gt;\r\nInserire un numero: &lt;strong&gt;5&lt;/strong&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;1 2 3 4 5 6 7 8 9 10&lt;br /&gt;\r\n2 4 6 8 10 12 14 16 18 20&lt;br /&gt;\r\n3 6 9 12 15 18 21 24 27 30&lt;br /&gt;\r\n4 8 12 16 20 24 28 32 36 40&lt;br /&gt;\r\n5 10 15 20 25 30 35 40 45 50&lt;/p&gt;\r\n', 1, 0, 3),
(267, 'Utilizzando un doppio ciclo FOR risolvere il seguente problema:', 6, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere un numero in input all&#039;utente. L&#039;input deve essere compreso tra 10 e 100. Continuare a chiedere il numero fino a che l&#039;utente non ne inserisce uno valido. Se l&#039;input non &egrave; valido dare un errore e chiedere nuovamente il numero in input.&lt;/p&gt;\r\n\r\n&lt;p&gt;Il programma deve stampare la somma di&nbsp;tutti i numeri dispari compresi tra 5 e 10, tra 5&nbsp;e 15, tra 5&nbsp;e 20&nbsp;e cos&igrave; via, incrementando di volta in volta il limite superiore di 5 e arrivando al numero letto in input.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio di esecuzione (input dell&#039;utente in grassetto):&lt;/p&gt;\r\n\r\n&lt;p&gt;Inserisci numero: 5&lt;br /&gt;\r\nErrore! Il numero deve stare tra 10 e 100&lt;br /&gt;\r\nInserisci numero: 120&lt;br /&gt;\r\nErrore! Il numero deve stare tra 10 e 100&lt;br /&gt;\r\nInserisci numero: 28&lt;br /&gt;\r\n21&nbsp;&lt;br /&gt;\r\n60&lt;br /&gt;\r\n96&lt;br /&gt;\r\n165&lt;/p&gt;\r\n\r\n&lt;p&gt;N.B: 21 = 5+7+9, 60&nbsp;= 5+7+9+11+13+15, 96 =&nbsp;5+7+9+11+13+15+17+19, 165&nbsp;=&nbsp;5+7+9+11+13+15+17+19+21+23+25, poi si ferma perch&egrave; 25+5=30, superiore al 28&lt;/p&gt;\r\n', 1, 0, 3),
(268, 'Utilizzare il ciclo indefinito while per risolvere il seguente problema:', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere in input all&#039;utente una sequenza di numeri fino a che non inserisce 0. Fare la somma di tutti i numeri letti. Stampare se la somma finale &egrave; maggiore o minore di 0&lt;/p&gt;\r\n', 1, 0, 3),
(269, 'Creare con Flowgorithm il diagramma a blocchi che risolva il seguente problema:', 6, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedere all&#039;utente un numero compreso tra 5 e 10, chiameremo questo numero NUM. Dare un errore se il numero non rispetta la condizione.&lt;/p&gt;\r\n\r\n&lt;p&gt;Chiedere all&#039;utente una serie di NUM&nbsp;coppie di numeri. Sommare in una variabile il prodotto dei due numeri della coppia, ma solo se questo &egrave; pari.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio: viene inserita la coppia 5 e 8. 5*8=40, 40 &egrave; pari quindi far&agrave; parte della somma.&lt;br /&gt;\r\nviene inserito 9 e 3, il prodotto da 27, numero dispari, non lo sommo al 40.&lt;/p&gt;\r\n', 1, 0, 3),
(270, 'Utilizzare un ciclo indefinito per risolvere il seguente problema', 5, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Vi sono 2 centometristi: Jacobs e Bolt,&nbsp;che devono effettuare una gara sui 100 metri piani.&nbsp;&lt;br /&gt;\r\nChiedere all&#039;utente la velocit&agrave; massima dei 2 velocisti in metri al secondo. Le velocit&agrave; massime devono essere superiori a 10, altrimenti dare errore e chiudere il programma.&lt;br /&gt;\r\nSimulare la corsa dei cento metri, indicando dove si trova ogni concorrente ogni secondo. Ad ogni secondo i 2 concorrenti avanzano di un numero casuale di metri estratto tra 5 e il valore della loro velocit&agrave; massima data dall&#039;utente.&lt;br /&gt;\r\nIndicare chi arriva primo e secondo in base alle loro posizioni finali, dopo che entrambi hanno tagliato il traguardo.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio:&lt;br /&gt;\r\nVelocit&agrave; massima Jacobs: 40&lt;br /&gt;\r\nVelocit&agrave; massima Bolt: 37&lt;/p&gt;\r\n\r\n&lt;p&gt;Dopo 1 secondo -&gt; Jacobs valore a caso tra 5 e 40, ad esempio&nbsp;viene estratto 24, quindi la posizione di Jacobs sar&agrave; 24, Bolt altro valore a caso tra 5 e 37, esempio 16, quindi Bolt si trova a 16 metri&lt;br /&gt;\r\nDopo 2 secondi -&gt;&nbsp;Jacobs valore a caso tra 5 e 40, ad esempio&nbsp;viene estratto 12, quindi la posizione di Jacobs sar&agrave; 24+12=36, Bolt estare a caso 15, quindi arriva a 31 metri... e cos&igrave; via fino a che non tagliano il traguardo a 100 metri.&lt;/p&gt;\r\n\r\n&lt;p&gt;Al primo posto arriva Bolt&lt;br /&gt;\r\nAl secondo posto arriva Jacobs&lt;/p&gt;\r\n', 1, 0, 3),
(271, 'Utilizzare il ciclo FOR per risolvere il seguente esercizio:', 4, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Scrivi un programma che chieda all&#039;utente di inserire una sequenza di %%5,10??&nbsp;numeri interi. Il programma dovrebbe quindi determinare e stampare il numero massimo&nbsp;tra quelli inseriti&lt;/p&gt;\r\n', 1, 0, 3),
(272, 'Realizza il seguente programma, utilizzando un doppio ciclo', 6, 6, 4, 0, 1, '2026-06-30', '&lt;p&gt;Chiedi in input un numero all&#039;utente. Il numero richiesto deve essere compreso tra 10 e 20. Continua a chiedere in input il numero nel caso l&#039;utente sbagli ad inserirlo.&lt;br /&gt;\r\nCalcola e stampa la somma di tutti i numeri compresi tra 1 ed il numero inserito, poi tra 1 ed il numero inserito -1, poi tra 1 ed il numero inserito -2 e cos&igrave; via fino ad arrivare alla somma di 1 e 2.&lt;/p&gt;\r\n\r\n&lt;p&gt;Esempio di esecuzione (in grassetto l&#039;input dell&#039;utente):&lt;br /&gt;\r\nInserisci un numero tra 10 e 20: &lt;strong&gt;25&lt;/strong&gt;&lt;br /&gt;\r\nErrore! Il numero deve essere compreso tra 10 e 20&lt;br /&gt;\r\nInserisci un numero tra 10 e 20: &lt;strong&gt;8&lt;/strong&gt;&lt;br /&gt;\r\nErrore! Il numero deve essere compreso tra 10 e 20&lt;br /&gt;\r\nInserisci un numero tra 10 e 20: &lt;strong&gt;18&lt;/strong&gt;&lt;br /&gt;\r\nSomma fino a 18:&nbsp; 171&lt;br /&gt;\r\nSomma fino a 17:&nbsp; 153&lt;br /&gt;\r\nSomma fino a 16:&nbsp; 136&lt;br /&gt;\r\n.........&lt;/p&gt;\r\n', 1, 0, 3),
(273, 'Cos&#039;&egrave; un&#039;entit&agrave; in un modello ER? Fare un esempio', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(274, 'Qual &egrave; la differenza tra entit&agrave; forte ed entit&agrave; debole? Fare un esempio', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(275, 'Cos&#039;&egrave; un&#039;istanza di un&#039;entit&agrave;? Cos&#039;&egrave; un attributo?', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(276, 'Qual &egrave; la differenza tra attributi identificatori e descrittori?', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(277, 'Indicare e dare una breve descrizione di alcune tipologie di attributo (per esempio guardandone l&#039;opzionalit&agrave;)', 3, 7, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(278, 'Cosa sono le chiavi candidate, le chiavi primarie e le chiavi alternative? Fare un esempio.', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(279, 'Cos&#039;&egrave; una chiave artificiale? Fare un esempio', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(280, 'Cos&#039;&egrave; una chiave composta? Fare un esempio', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(281, 'Cos&#039;&egrave; una chiave esterna? Fare un esempio.', 3, 7, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(282, 'Per il problema seguente disegnare con notazione ER quali sono le possibili entit&agrave;, gli attributi delle entit&agrave; e le possibili chiavi:', 5, 7, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un database deve gestire i dati riguardanti una pizzeria: le ordinazioni, i camerieri ed i tavoli&lt;/p&gt;\r\n', 1, 0, 3),
(283, 'Per il problema seguente disegnare con notazione ER quali sono le possibili entit&agrave;, gli attributi delle entit&agrave; e le possibili chiavi:', 5, 7, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una nave da crociera vuole registrare i dati riguardanti il personale di bordo, i passeggeri e le cabine&lt;/p&gt;\r\n', 1, 0, 3),
(284, 'Per il problema seguente disegnare con notazione ER quali sono le possibili entit&agrave;, gli attributi delle entit&agrave; e le possibili chiavi:', 5, 7, 4, 0, 1, '2026-06-30', '&lt;p&gt;Si devono gestire i dati relativi ad una gara motociclistica. I dati indispensabili sono i dati sui piloti, sulle motociclette e sui meccanici che vi lavorano&lt;/p&gt;\r\n', 1, 0, 3),
(285, 'Per il problema seguente disegnare con notazione ER quali sono le possibili entit&agrave;, gli attributi delle entit&agrave; e le possibili chiavi:', 5, 7, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una biblioteca vuole gestire i dati riguardanti i libri a disposizione dei tesserati, i dati riguardanti i tesserati ed infine i dati del personale della biblioteca.&lt;/p&gt;\r\n', 1, 0, 3),
(286, 'Disegnare il diagramma ER per la seguente realt&agrave; (stazione ferroviaria):', 6, 7, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una stazione ferroviaria deve gestire treni in partenza e in arrivo tramite database. I treni che transitano per la stazione hanno un certo numero di vagoni ed un codice identificativo, un numero massimo di passeggeri che possono salire, un tipo di motore (elettrico o diesel), una tipologia (regionale, regionale veloce, intercity&amp;hellip;). I treni che transitano per la stazione hanno un orario di partenza ed una stazione di arrivo, oppure un orario di arrivo ed una stazione di partenza. Al treno &amp;egrave; associato un macchinista ed un controllore. Non vengono salvate informazioni sui passeggeri o sui biglietti venduti.&lt;br /&gt;\r\nInserire un commento sulla scelta delle molteplicit&amp;agrave;.&lt;br /&gt;\r\nEsempio di dato completo: La stazione che deve gestire i treni &amp;egrave; la stazione di Verona. Il treno 45 &amp;egrave; un intercity con 6 vagoni e pu&amp;ograve; trasportare al massimo 300 passeggeri, ha un motore elettrico. Parte alle ore 15.00 con destinazione Padova. Il macchinista &amp;egrave; Carlo Verdi ed il controllore &amp;egrave; Mario Rossi.&lt;/p&gt;\r\n', 1, 0, 3),
(287, 'Disegnare il diagramma ER per la seguente realt&agrave; (autonoleggio):', 6, 7, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un servizio di noleggio auto vuole registrare i dati necessari all&amp;rsquo;attivit&amp;agrave; tramite un database. Il servizio vuole registrare i dati riguardanti le automobili in suo possesso (marca, modello, km percorsi, colore, tipo di motore) ed i dati delle persone che noleggiano le auto. Il noleggio avviene in una certa data, per un certo numero di giorni. Il servizio vuole registrare i dati del personale, associando il noleggio effettuato alla persona che ha fatto sottoscrivere il contratto di noleggio. Va registrato inoltre il pagamento finale effettuato alla riconsegna dell&amp;rsquo;automobile, con l&amp;rsquo;importo pagato ed il metodo di pagamento, associando la riconsegna all&amp;rsquo;impiegato che ritira l&amp;rsquo;automobile e fa effettuare il pagamento.&lt;br /&gt;\r\nInserire un commento sulla scelta delle molteplicit&amp;agrave;.&lt;br /&gt;\r\nEsempio di dato completo: Il signor Mario Rossi, con CF RSSMRA78C12G892K noleggia il giorno 24 di dicembre 2022 per 10 giorni l&amp;rsquo;auto Fiat Panda rossa con motore a metano, km percorsi 18.400. Il contratto di noleggio &amp;egrave; stato fatto sottoscrivere dall&amp;rsquo;impiegato Giuseppe Verdi. Alla fine del noleggio, dopo 10 giorni, il signor Mario Rossi restituisce l&amp;rsquo;auto all&amp;rsquo;impiegata Valeria Bruni, pagando un importo di 450&amp;euro; in contanti.&lt;/p&gt;\r\n', 1, 0, 3),
(288, 'Dopo aver raffinato il diagramma ER sviluppato al punto 1, creare il modello logico conseguente, inserendo chiavi primarie, chiavi esterne e tipo dei campi per ogni tabella da creare.', 4, 7, 4, 0, 1, '2026-06-30', '', 1, 0, 3),
(289, 'Cos&rsquo;&egrave; e come funziona il Garbage Collector in un linguaggio ad oggetti?', 4, 8, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(290, 'Cosa si intende con deallocazione della memoria in un linguaggio di programmazione ad oggetti?', 4, 8, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(291, 'Lo stack nella gestione della memoria in programmazione ad oggetti', 4, 8, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(292, 'Lo Heap e l&#039;allocazione della memoria in Programmazione ad Oggetti', 4, 8, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(293, 'Cosa significa che Python utilizza i tipi dinamici?', 3, 10, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(294, 'Cosa significa casting e come si esegue in Python?', 3, 10, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(295, 'Cosa significa indentare il codice e perch&eacute; &egrave; importante in Python?', 3, 10, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(296, 'Python ha una licenza:', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(297, 'In Python l&#039;indentazione:', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(298, 'Voglio dare lo stesso valore a 3 variabili in Python:', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(299, '&quot;a&quot; * 5 in Python mi da:', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(300, 'Casting significa:', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(301, 'Quale istruzione viene utilizzata per eseguire un&#039;azione solo se una condizione &egrave; vera in Python?', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(302, 'Come si dichiara una variabile in Python?', 1, 10, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(303, 'Cos&#039;&egrave; e a cosa serve un DBMS?', 3, 11, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(304, 'Quali possibili problemi si possono avere se non si utilizzano i database?', 3, 11, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(305, 'Perch&egrave; usare i database e non dati salvati dalle singole applicazioni?', 3, 11, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(306, 'Quali sono i passi da fare per la realizzazione di un database? Darne una breve descrizione', 3, 11, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(307, 'Quali possibili modelli logici esistono per la creazione di database? Darne una breve descrizione', 3, 11, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(308, 'Descrivere le propriet&agrave; ACID di una transazione su un database', 3, 11, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(309, 'Cosa garantisce la propriet&agrave; di Atomicit&agrave; nei database?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(310, 'Cosa significa la propriet&agrave; di Consistenza nei database?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(311, 'Quale problema pu&ograve; sorgere senza un sistema centralizzato di gestione dei dati?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(312, 'A cosa serve un DBMS?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(313, 'Cosa rappresenta la propriet&agrave; di Isolamento nei database?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(314, 'Qual &egrave; un vantaggio principale dell&rsquo;uso dei database?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(315, 'Cosa pu&ograve; succedere in assenza di un controllo sull&rsquo;accesso concorrente ai dati?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3);
INSERT INTO `ct_domande` (`id_domanda`, `domanda`, `punti`, `fk_argomento`, `fk_tipo_domanda`, `num_righe`, `fk_libro`, `data_creazione`, `ese_num`, `fk_utente`, `num_gruppo`, `livello_diff`) VALUES
(316, 'Cosa rappresenta la propriet&agrave; di Durabilit&agrave; in una transazione?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(317, 'Cosa si intende per ridondanza dei dati?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(318, 'Cos&#039;&egrave; un database?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(319, 'In un&#039;app bancaria, un utente trasferisce 100&euro; da Conto A a Conto B. Il sistema addebita i 100&euro; da A ma si blocca prima di accreditarli su B. Quale propriet&agrave; ACID &egrave; violata?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(320, 'Due clienti acquistano online l&rsquo;ultimo pezzo disponibile di un prodotto quasi contemporaneamente. Entrambi ricevono conferma, ma il magazzino aveva solo un&rsquo;unit&agrave;. Quale propriet&agrave; ACID &egrave; violata?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(321, 'Una transazione inserisce un ordine con un codice cliente inesistente, e il sistema non impedisce l&rsquo;errore. Quale propriet&agrave; ACID &egrave; violata?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(322, 'Dopo aver completato una transazione che registra il pagamento di una fattura, il sistema va in crash. Al riavvio, il pagamento risulta come non effettuato. Quale propriet&agrave; ACID &egrave; violata?', 1, 11, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(323, 'Cos&#039;&egrave; una stringa di caratteri? Come si rappresenta in un linguaggio di programmazione?', 3, 12, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(324, 'Cosa significa concatenare stringhe? Qual &egrave; l&#039;operatore che usa Flowgorithm per la concatenazione?', 3, 12, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(325, 'Cos&#039;&egrave; una variabile? Quali sono le sue caratteristiche?', 3, 12, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(326, 'Cosa significa pseudocodice? E codice macchina?', 3, 12, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(327, 'Cos&#039;&egrave; un algoritmo e quali sono le sue propriet&agrave;?', 3, 12, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(328, 'Cos&#039;&egrave; lo pseudocodice? E cos&#039;&egrave; invece un linguaggio di programmazione?', 3, 12, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(329, 'Cos&#039;&egrave; l&#039;assegnazione? Scrivi cosa significa che &egrave; un&#039;operazione distruttiva a sinistra. Posso eseguire l&#039;assegnazione a=a+5? Se si cosa significa?', 3, 12, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(330, 'Descrivi i principali blocchi di un diagramma a blocchi', 3, 12, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(331, 'Quale tra i seguenti &egrave; un nome valido di variabile:', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(332, 'Descrivere gli operatori booleani visti a lezione', 3, 12, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(333, 'Cos&#039;&egrave; il tipo di una variabile (fare degli esempi)? Cos&#039;&egrave; un valore booleano?', 3, 12, 1, 3, 1, '2026-06-30', '', 1, 0, 3),
(334, 'Quali sono i dati di input e i dati di output per il seguente problema? Da loro un nome in modo che possano diventare le variabili di un programma', 2, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una vasca riceve acqua da due condutture che versano rispettivamente %%3,6??0 e %%4,9??0 litri al minuto. In quante ore si riempir&agrave; la vasca sapendo che la sua capacit&agrave; &egrave; di %%200,300?? ettolitri?&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n______________________________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(335, 'Quali sono i dati di input e i dati di output per il seguente problema? Da loro un nome in modo che possano diventare le variabili di un programma', 2, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;Due falegnami per costruire una libreria ricevono una paga oraria rispettivamente di 18 euro e 21 euro. Sapendo che mediamente hanno lavorato per 9 ore al giorno e che alla consegna della libreria il secondo falegname ha ricevuto 297 euro pi&ugrave; del primo, calcola quanti giorni hanno lavorato i due (stesso numero di giorni).&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n______________________________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(336, 'Quali sono i dati di input e i dati di output per il seguente problema? Da loro un nome in modo che possano diventare le variabili di un programma', 2, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;Per partecipare ad una gita scolastica ogni alunno versa una cifra di %%15,25?? euro. I partecipanti, che sono %%27,38?? devono inoltre dividere tra di loro una spesa extra di %%100,200?? euro. Quanto spende in totale ciascun alunno?&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;br /&gt;\r\n______________________________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;br /&gt;\r\n&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(337, 'Disegna il diagramma a blocchi che risolva il seguente problema:', 5, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;Si deve dipingere una parete rettangolare di base B centimetri e altezza H centimetri. Il colore scelto per dipingere la parete costa C euro per centimetro quadrato.Calcolare il costo per dipingere una qualsiasi parete, leggendo in input il valore della base e dell&#039;altezza della parete e il costo della pittura.&lt;/p&gt;\r\n', 1, 0, 3),
(338, 'Disegna il diagramma a blocchi che risolva il seguente problema:', 5, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;Gli alunni iscritti in una scuola media sono X. Y di essi non frequentano la terza. Quante sono le sezioni di terza se ogni classe &egrave; formata da Z alunni? Vogliamo avere in input quanti iscritti totali alle medie, quanti non frequentano la terza e quanti alunni compongono ogni terza.&lt;/p&gt;\r\n', 1, 0, 3),
(339, 'Disegna il diagramma a blocchi che risolva il seguente problema:', 5, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;L&#039;abbonamento annuale ad un settimanale, il cui prezzo di copertina &egrave; di X euro, costa X1 euro e l&#039;abbonamento ad un mensile, il cui prezzo di copertina &egrave; di Y euro, costa Y1 euro. Calcolare il risparmio totale se ci si abbona ai 2 giornali, piuttosto che acquistarli in edicola (considerando l&#039;anno composto da 52 settimane).&lt;/p&gt;\r\n', 1, 0, 3),
(340, 'Quali sono le caratteristiche dei linguaggi formali? Dare una breve spiegazione di ognuna', 3, 12, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(341, 'Dare una definizione di linguaggio ad alto livello e di linguaggio a basso livello', 3, 12, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(342, 'Dare una definizione di costante e fare almeno un esempio di costante. Come si scrive per convenzione una costante?', 3, 12, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(343, 'Qual &egrave; la differenza tra selezione singola, doppia e multipla? (Si pu&ograve; anche disegnare il blocco relativo del diagramma a blocchi per rispondere)', 3, 12, 1, 4, 1, '2026-06-30', '', 1, 0, 3),
(344, 'Risolvi l&#039;indovinello di Einstein semplificato (da fare per ultimo per il 10):', 2, 12, 4, 0, 1, '2026-06-30', '&lt;p&gt;In un quartiere ci sono tre case, ognuna di un colore diverso: una rossa, una blu e una verde. In ciascuna casa vive una persona di nazionalit&agrave; diversa: una italiana, una spagnola e una francese. Ognuna di queste persone beve una bevanda diversa: il caff&egrave;, il t&egrave; e il latte. Inoltre, ognuna di loro ha un animale domestico diverso: un cane, un gatto e un pesce. Chi ha il pesce? Ecco alcune informazioni su queste case e i loro abitanti:&lt;/p&gt;\r\n\r\n&lt;ol&gt;\r\n	&lt;li&gt;La casa rossa &egrave; a sinistra della casa verde.&lt;br /&gt;\r\n	La persona che vive nella casa rossa beve caff&egrave;.&lt;br /&gt;\r\n	La casa verde non &egrave; adiacente alla casa blu.&lt;br /&gt;\r\n	La persona che vive nella casa verde ha un gatto.&lt;br /&gt;\r\n	La persona francese vive nella prima casa.&lt;br /&gt;\r\n	La persona che beve latte non vive nella casa blu.&lt;br /&gt;\r\n	La persona che vive a sinistra dello spagnolo ha un cane.&lt;br /&gt;\r\n	La persona italiana vive vicino a chi beve latte.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n', 1, 0, 3),
(345, 'Cos&#039;&egrave; un diagramma di flusso?', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(346, 'Cosa rappresenta un rombo in un diagramma di flusso?', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(347, 'Dove sono memorizzate le variabili di un linguaggio di programmazione?', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(348, 'Qual &egrave; lo scopo principale dei diagrammi di flusso nella programmazione?', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(349, 'Cosa viene utilizzato in un diagramma di flusso per eseguire un&#039;azione diversa in base a una condizione specifica?', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(350, 'Qual &egrave; lo scopo delle variabili in programmazione?', 1, 12, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(351, 'Definire quale sia la differenza tra un array statico ed un array dinamico in Java. Quale classe si pu&ograve; utilizzare in Java per gli array dinamici? In quale package la si trova?', 4, 13, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(352, 'Descrivere la classe vector di Java, indicando cosa sia la capacit&agrave; del Vector e perch&egrave; si differenzia dal numero di elementi in esso contenuti e cosa accade ai due valori quando si aggiungono o rimuovono elementi.', 4, 13, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(353, 'Descrivere cosa sia il tipo di un Vector in Java, quali tipi possono essere inseriti all&#039;itnerno di un Vector e cosa siano le classi Wrapper', 4, 13, 1, 7, 1, '2026-06-30', '', 1, 0, 3),
(354, 'Come si chiama il compilatore Java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(355, 'Cos&#039;&egrave; una variabile d&#039;ambiente, per esempio PATH?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(356, 'Come si chiama l&#039;ambiente che possiamo installare sui nostri PC per richiamare java e il suo compilatore da riga di comando?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(357, 'Cos&#039;&egrave; un identificatore?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(358, 'Cosa sono le parole chiave di un linguaggio di programmazione?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(359, 'Cosa significa case sensitive?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(360, 'Quale dei seguenti NON va bene come nome di variabile in java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(361, 'Come posso scrivere una variabile per il lato del quadrato da convenzione java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(362, 'Quale delle seguenti &egrave; la dichiarazione di una costante in java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(363, 'Cosa significa casting in un linguaggio di programmazione?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(364, 'Cosa significa promozione in Java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(365, 'Cosa significa tipizzazione statica?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(366, 'Qual &egrave; la caratteristica di un linguaggio fortemente tipizzato?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(367, 'Qual &egrave; l&#039;operatore da usare per fare l&#039;AND in una condizione Java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(368, 'Qual &egrave; l&#039;operatore per effettuare l&#039;OR in Java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(369, 'Cos&#039;&egrave; un package in Java', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(370, 'Cos&#039;&egrave; il ByteCode Java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(371, 'Il ByteCode:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(372, 'La JVM:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(373, 'Per fare input in Java possiamo utilizzare la classe:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(374, 'Come si stampa a video in Java?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(375, 'Come leggo un intero da standard input se ho creato un oggetto Scanner denominato myScanner?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(376, 'Come si inserisce in Java un commento su una sola riga?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(377, 'Come si inserisce in Java un commento su pi&ugrave; righe?', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(378, 'In Java se ho due variabili intere e effettuo la loro divisione:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(379, 'Se dichiaro una variabile X in Java all&#039;interno di un blocco di codice:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(380, 'La differenza tra gli operatori doppio &amp;&amp; e singolo &amp; nel controllo di un if:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(381, 'La differenza tra gli operatori doppio || e singolo | nel controllo di un&#039;operazione OR:', 1, 14, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(382, 'Perch&egrave; si utilizza la programmazione ad oggetti?', 3, 15, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(383, 'Cosa rappresentano attributi e metodi di una classe?', 3, 15, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(384, 'Cos&#039;&egrave; e a cosa serve il Costruttore?', 3, 15, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(385, 'Quali sono i livelli di visibilit&agrave; possibili? Dare una spiegazione per ognuno', 3, 15, 1, 7, 1, '2026-06-30', '', 1, 0, 3),
(386, 'Qual &egrave; la caratteristica di un attributo static per una classe?', 3, 15, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(387, 'Cosa si intende con information hiding? Perch&egrave; viene utilizzato?', 3, 15, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(388, 'Disegnare il diagramma delle classi per la classe Quadrato', 4, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;La classe Quadrato rappresenta una figura geometrica con quattro lati e quattro angoli uguali. Inserire possibili attributi e metodi per la classe.&lt;/p&gt;\r\n', 1, 0, 3),
(389, 'Disegnare il diagramma delle classi per la classe Ebook:', 4, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;La classe rappresenta un libro letto dall&amp;#39;utente di un ebook reader (lettore di libri digitali). Il libro digitale deve tener conto, tra le altre cose, della pagina dove si &amp;egrave; arrivati a leggere (tipo segnalibro).&lt;/p&gt;\r\n', 1, 0, 3),
(390, 'Disegnare il diagramma delle classi per la classe: Sveglia', 4, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una sveglia da la possibilit&amp;agrave; di leggere l&amp;#39;orario, ma anche di impostare l&amp;#39;ora in cui suonare per svegliarci.&lt;/p&gt;\r\n', 1, 0, 3),
(391, 'Disegnare il diagramma delle classi per la classe Cisterna:', 4, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;Una cisterna contiene una certa quantit&amp;agrave; di liquido di un certo tipo.&lt;/p&gt;\r\n', 1, 0, 3),
(392, 'Disegnare il diagramma delle classi per la classe: Televisore', 4, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;Un televisore acceso sar&amp;agrave; sintonizzato su un certo canale, modificabile con telecomando&lt;/p&gt;\r\n', 1, 0, 3),
(393, 'Istanza di una classe &egrave; sinonimo di:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(394, 'Incapsulamento', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(395, 'Il costruttore in Java', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(396, 'Una variabile d&#039;istanza:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(397, 'Quale tra le seguenti &egrave; una possibile dicitura per creare un oggetto della classe Cerchio in Java?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(398, 'Quale tra le seguenti NON &egrave; la dichiarazione corretta di un metodo in Java?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(399, 'Una variabile locale:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(400, 'La parola chiave final in Java:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(401, 'La parola chiave null in Java:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(402, 'Come dichiaro un array contenente 5 oggetti di tipo Automobile in Java?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(403, 'Cosa stampa? (uguaglianza)', 2, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/domanda1.jpg&quot; style=&quot;height:720px; width:734px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(404, 'Cosa stampa? (static)', 2, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/domanda2.jpg&quot; style=&quot;height:603px; width:732px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(405, 'Cosa stampa? (array)', 2, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/domanda3.jpg&quot; style=&quot;height:627px; width:702px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(406, 'Cos&#039;&egrave; l&#039;interfaccia di una classe?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(407, 'Cos&#039;&egrave; un oggetto in programmazione?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(408, 'Se creo un oggetto della classe Rettangolo con nome r1, come posso accedere ad un suo attributo privato denominato altezza da una classe esterna Main?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(409, 'Come posso richiamare il metodo &#039;calcolaPerimetro&#039; senza parametri sull&#039;oggetto &#039;ogg&#039; in Java?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(410, 'Cosa si intende con information hiding?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(411, 'Quale delle seguenti affermazioni &egrave; pi&ugrave; accurata per la dichiarazione Cerchio x = new Cerchio()?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(412, 'Cosa stampa (array2)', 2, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/domanda4.jpg&quot; style=&quot;height:573px; width:646px&quot; /&gt;&lt;/p&gt;\r\n', 1, 0, 3),
(413, 'Si realizza Information Hiding quando:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(414, 'Quando dichiaro una variabile di tipo riferimento (quindi in pratica un riferimento ad un oggetto di una classe), ma non richiamo il costruttore con la parola chiave new:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(415, 'La parola chiave final in Java serve a:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(416, 'In Java &egrave; possibile dichiarare due costruttori per una classe:', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(417, 'In Java, qual &egrave; il costruttore di default per una classe se non &egrave; definito esplicitamente?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(418, 'In Java, qual &egrave; la parola chiave utilizzata per indicare l&#039;ereditariet&agrave; tra classi?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(419, 'In Java, quale &egrave; il modificatore di accesso che rende un membro della classe accessibile solo all&#039;interno della stessa classe o delle sue sottoclassi?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(420, 'In Java, quale parola chiave &egrave; utilizzata per indicare che un metodo non restituisce alcun valore?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(421, 'Data la classe seguente indicare cosa stampa il programma Java quando viene lanciato o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore):', 1, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/CosaStampa1.jpg&quot; style=&quot;height:1010px; width:1347px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;_____________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(422, 'Data la classe seguente indicare cosa stampa il programma Java quando viene lanciato o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore):', 1, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/CosaStampa2.jpg&quot; style=&quot;height:997px; width:1353px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;_________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(423, 'Data la classe seguente indicare cosa stampa il programma Java quando viene lanciato o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore):', 1, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/CosaStampa3.jpg&quot; style=&quot;height:1040px; width:1307px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(424, 'Data la classe seguente indicare cosa stampa il programma Java quando viene lanciato o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore):', 1, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/CosaStampa4.jpg&quot; style=&quot;height:961px; width:1326px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;____________________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(425, 'Qual &egrave; uno dei principali utilizzi delle interfacce in Java?', 1, 15, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(426, 'Data la classe seguente indicare cosa stampa il programma Java quando viene lanciato o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore):', 1, 15, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img src=&quot;/assets/images/Questions/parallela2026_3.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;____________________________________________________________________________________&lt;/p&gt;', 1, 0, 3),
(427, 'Una classe che eredita attributi e metodi di un&#039;altra classe &egrave; anche detta:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(428, 'Quali sono i due modi per differenziare una sottoclasse dalla sua superclasse?', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(429, 'Se una sottoclasse estende una sola superclasse ho:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(430, 'Se una sottoclasse estende pi&ugrave; superclassi abbiamo:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(431, 'In Java &egrave; possibile avere ereditariet&agrave; multipla?', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(432, 'La parola chiave in Java per ereditare da una superclasse &egrave;:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(433, 'In Java ogni classe &egrave; sottoclasse della classe:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(434, 'Quando nella stessa classe definisco 2 metodi che abbiano lo stesso nome (diverso da quello della classe) e lo stesso tipo di ritorno, ma parametri diversi, sono di fronte a:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(435, 'Quando scrivo un metodo su una sottoclasse che abbia stessi parametri, stesso nome e stesso tipo di ritorno di un metodo della superclasse, sto realizzando:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(436, 'In Java gli attributi privati di una classe:', 1, 16, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(437, 'Date le seguenti classi Java in relazione di ereditariet&agrave;, indicare cosa stampa il programma quando va in esecuzione o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore)', 1, 16, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/parallela2026_1.jpg&quot; style=&quot;height:1409px; width:1382px&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;______________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(438, 'Dato il seguente programma con ereditariet&agrave; in Java, indicare cosa stampa quando lanciato in esecuzione o se c&#039;&egrave; un errore (in questo caso scrivere qual &egrave; l&#039;errore)', 1, 16, 4, 0, 1, '2026-06-30', '&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;/assets/images/Questions/parallela2026_2.jpg&quot; /&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n\r\n&lt;p&gt;________________________________________________________________________________________________&lt;/p&gt;\r\n', 1, 0, 3),
(439, 'I file servono a:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(440, 'Quale tra le seguenti NON &egrave; un&#039;operazione che possiamo fare su file da Java', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(441, 'L&#039;apertura di un file:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(442, 'Lettura da file', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(443, 'Scrittura su file', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(444, 'Il package per getire input/output su Java &egrave;:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(445, 'Uno stream &egrave;:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(446, 'Abbiamo due categorie di classi per leggere e scrivere su file:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(447, 'I due tipi di file che posso salvare su Java:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(448, 'Un file strutturato:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(449, 'Se voglio salvare un oggetto su file strutturato, la classe deve:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(450, 'I metodi per leggere/scrivere su file in Java', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(451, 'Per suddividere una stringa in base ad uno specifico carattere possiamo usare la classe:', 1, 17, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(452, 'Qual &egrave; la differenza tra un linguaggio compilato ed uno interpretato?', 3, 18, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(453, 'Descrivere vantaggi e svantaggi di un linguaggio compilato', 3, 18, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(454, 'Descrivere vantaggi e svantaggi di un linguaggio interpretato', 3, 18, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(455, 'Cos&rsquo;&egrave; un IDE?', 3, 18, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(456, 'Quali tipi di errore possiamo incontrare quando programmiamo? Fare degli esempi per ognuno.', 3, 18, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(457, 'Come si documenta il codice e perch&eacute; &egrave; importante farlo?', 3, 18, 1, 7, 1, '2026-06-30', '', 1, 0, 3),
(458, 'Qual &egrave; la differenza tra un linguaggio compilato ed uno interpretato? Dire un vantaggio di ognuno.', 3, 18, 1, 8, 1, '2026-06-30', '', 1, 0, 3),
(459, 'Cos&rsquo;&egrave; un linguaggio pseudo-interpretato?', 3, 18, 1, 6, 1, '2026-06-30', '', 1, 0, 3),
(460, 'Che cos&rsquo;&egrave; un debugger e cosa permette di fare?', 3, 18, 1, 5, 1, '2026-06-30', '', 1, 0, 3),
(461, 'Ho a disposizone un programma .exe su Windows, significa che:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(462, 'Ho a disposizione un programma .exe su Windows:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(463, 'Un programma compilato:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(464, 'Un programma compilato per Linux:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(465, 'Se passo il mio programma Python ad un amico:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(466, 'Generalmente un IDE contiene:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(467, 'Esempio di errore lessicale:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(468, 'Il debugger NON permette:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(469, 'NON ha a che fare con la documentazione del codice:', 1, 18, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(470, 'Cosa si intende per &quot;information hiding&quot; in programmazione ad oggetti?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(471, 'Qual &egrave; l&#039;obiettivo principale dell&#039;ereditariet&agrave; in programmazione ad oggetti?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(472, 'Cosa rappresenta una classe in programmazione ad oggetti?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(473, 'Qual &egrave; la differenza tra una classe e un oggetto?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(474, 'Cosa significa override in programmazione ad oggetti?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(475, 'In che modo l&#039;ereditariet&agrave; contribuisce alla modularit&agrave; del codice in programmazione ad oggetti?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(476, 'In Python e java, quale &egrave; il simbolo utilizzato per accedere agli attributi e ai metodi di un oggetto?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(477, 'In programmazione ad oggetti, qual &egrave; il concetto utilizzato per raggruppare dati e operazioni che manipolano quei dati?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(478, 'In programmazione ad oggetti, qual &egrave; il tipo di relazione tra due classi dove una classe &egrave; un&#039;estensione di un&#039;altra?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(479, 'Qual &egrave; il principio di inserimento e rimozione degli elementi di una pila?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(480, 'Qual &egrave; il principio di inserimento e rimozione degli elementi di una coda?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(481, 'In programmazione ad oggetti che cos&#039;&egrave; una classe in Java?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(482, 'Che cosa rappresenta un attributo di una classe?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(483, 'Che cosa rappresenta un metodo in una classe Java?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(484, 'Quale parola chiave rende un attributo accessibile solo all&#039;interno della stessa classe?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(485, 'Quale livello di visibilit&agrave; permette l&#039;accesso da qualunque altra classe?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(486, 'Che cosa si intende con information hiding?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(487, 'Qual &egrave; il modo corretto per accedere in sicurezza a un attributo privato?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(488, 'Che cos&#039;&egrave; un costruttore in Java?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(489, 'Quale caratteristica distingue un costruttore da un normale metodo?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(490, 'Che cosa accade se in una classe Java non si definisce alcun costruttore?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(491, 'Che cos&#039;&egrave; l&#039;ereditariet&agrave; in Java?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(492, 'Quale parola chiave si usa in Java per indicare l&#039;ereditariet&agrave; tra classi?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(493, 'In una relazione di ereditariet&agrave; la classe derivata viene detta anche:', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(494, 'Che cos&#039;&egrave; l&#039;overloading di un metodo?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(495, 'Che cos&#039;&egrave; l&#039;overriding di un metodo?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(496, 'Quale affermazione sull&#039;overloading &egrave; corretta?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(497, 'Quale affermazione sull&#039;overriding &egrave; corretta?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(498, 'A che cosa serve la parola chiave super in un costruttore?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(499, 'Per quale motivo spesso gli attributi vengono dichiarati private in Java?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3),
(500, 'Se una classe Studente estende Persona, quale affermazione &egrave; corretta?', 1, 25, 2, 0, 1, '2026-06-30', '', 1, 0, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ct_esercizi`
--

INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(1, 'b838cdf7-3eb8-4b52-981b-5e9f73d7df26', '&lt;p&gt;Per poter comprendere cosa successe agli eroi malvagi, abbiamo bisogno di dati. Individua i dati iniziali e finali dei seguenti problemi, inserendoli come risposta al compito:&lt;/p&gt;\r\n&lt;p&gt;1) Il guerriero 4 comincia ad imporre delle tasse perch&amp;eacute; intende arricchirsi. Ogni suo suddito deve pagare 1000 denari in un anno. Il primo dell&#039;anno &amp;egrave; tenuto a versare 70 denari immediatamente, poi deve pagare una quota ogni fine del mese. Quanto diviene ogni quota del debito?&lt;/p&gt;\r\n&lt;p&gt;2) Il guerriero 7 costringe un gruppo di uomini a costruire per lui un castello. Una torre a sezione quadrata ha lato di 30 metri ed altezza di 20 metri. Un blocco di pietra per la costruzione ha una lunghezza di 1 metro, una larghezza di 50 cm ed un&#039;altezza di 50 cm. ogni pietra costa 3 denari. Quanti denari servono per la costruzione della torre?&lt;/p&gt;\r\n&lt;p&gt;3) Il guerriero 12 assolda un esercito, perch&amp;eacute; troppo pigro per combattere con i suoi poteri. L&#039;esercito sar&amp;agrave; composto da 1200 persone e deve avere dei comandanti. Ogni 80 persone va creato un capitano, ogni 5 capitani va creato un generale. Di quanti generali avr&amp;agrave; bisogno l&#039;esercito?&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74f818212.88508118_exercise_editor_6a32b46f58d7e5.01975229.png&quot; alt=&quot;&quot;&gt; Prima che la civilt&amp;agrave; emergesse nella forma che conosciamo oggi, furono creati 14 guerrieri straordinari, ciascuno dotato di capacit&amp;agrave; incredibili. Chi li cre&amp;ograve; rimane un mistero avvolto nell&#039;ombra, ma la loro missione era chiara: 14 uomini e donne, scelti dall&#039;umanit&amp;agrave; stessa, furono investiti di poteri immensi per garantire pace e prosperit&amp;agrave; sulla Terra. Erano individui giusti, disposti a sacrificare la propria vita per il bene comune e per portare felicit&amp;agrave; a tutti i popoli.&lt;/p&gt;\r\n&lt;p&gt;Inizialmente, la scelta del Creatore sembrava impeccabile. Tuttavia, non aveva previsto quanto facilmente l&#039;animo umano potesse essere corrotto. Il potere, cos&amp;igrave; grande e assoluto, cominci&amp;ograve; a insinuare dubbi e desideri egoistici in alcuni di loro. Uno dei guerrieri, il pi&amp;ugrave; potente e in origine il pi&amp;ugrave; altruista, fu il primo a cedere all&#039;oscurit&amp;agrave;. Dove un tempo aveva agito per il bene degli altri, inizi&amp;ograve; a usare la sua forza per sottomettere il mondo alla propria volont&amp;agrave;, trasformandosi nel tiranno che aveva giurato di combattere.&lt;/p&gt;\r\n&lt;p&gt;Altri seguirono il suo esempio, cadendo vittime di egoismo, superbia e ambizione. Delle 14 figure iniziali, 6 abbracciarono il lato oscuro, agendo per il proprio tornaconto. Al contrario, 6 rimasero fedeli alla loro missione originaria, impegnandosi strenuamente a proteggere il bene comune. I restanti 2, disgustati dai conflitti e dai tradimenti, decisero di ritirarsi. Scelsero la neutralit&amp;agrave;, isolandosi in terre lontane e abbandonando ogni coinvolgimento nei doveri e nelle lotte degli altri.&lt;/p&gt;\r\n&lt;p&gt;Cos&amp;igrave;, i guerrieri originari si divisero in tre fazioni: coloro che combattevano per s&amp;eacute; stessi, coloro che lottavano per il bene altrui, e coloro che, ormai disillusi, si estraniarono da tutto.&lt;/p&gt;', 12, 1, 'La creazione degli eroi', 0, 26, 0, '&lt;p&gt;Per poter comprendere cosa successe agli eroi malvagi, abbiamo bisogno di dati. Individua i dati iniziali e finali dei seguenti problemi, inserendoli come risposta al compito:&lt;/p&gt;\r\n&lt;p&gt;1) Il guerriero 4 comincia ad imporre delle tasse perch&amp;eacute; intende arricchirsi. Ogni suo suddito deve pagare 1000 denari in un anno. Il primo dell&#039;anno &amp;egrave; tenuto a versare 70 denari immediatamente, poi deve pagare una quota ogni fine del mese. Quanto diviene ogni quota del debito?&lt;/p&gt;\r\n&lt;p&gt;2) Tre nani al servizio del guerriero 7 impiegano 12 giorni per scavare un tunnel. Se i nani diventano 4 e lavorano allo stesso ritmo, in quanti giorni completeranno lo stesso tunnel?&lt;/p&gt;\r\n&lt;p&gt;3) Un alchimista costretto a lavorare per il guerriero 12 acquista alcune pozioni di attacco. Ogni pozione costa 8 monete d&#039;oro. Alla fine spende 56 monete, comprese 8 monete per il trasporto. Domanda: Quante pozioni ha acquistato?&lt;/p&gt;', 0),
(2, 'ce688267-416f-4de0-8042-f1133c6a61eb', '&lt;p&gt;Aiuta anche tu gli eroi del bene: per il problema seguente identifica quali sono i dati di input e quali quelli di output. Elenca le variabili che devi dichiarare per poter avere i dati di input, di output e per effettuare le elaborazioni di dati.&lt;/p&gt;\r\n&lt;p&gt;Il primo guerriero del bene vuole utilizzare un contingente di tartarughe azzannatrici per colpire il nemico. Un buon numero sarebbe di 3000 tartarughe. Ha inizialmente a disposizione 2 tartarughe (maschio e femmina). Ogni coppia di tartarughe produce 20 uova in un anno. Solo la met&amp;agrave; delle tartarughe nate da una nidiata sopravvive e pu&amp;ograve; riprodursi, creando a loro volta 20 uova per coppia. Dopo quanti anni, si riesce ad ottenere almeno 3000 tartarughe, tenendo presente che quelle vecchie non sono morte e si riproducono a loro volta?&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74f8ab261.68636601_exercise_editor_6a339f36a7bd81.77106385.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;A questo punto, una terribile battaglia esplose tra le forze del bene e quelle del male. Fino ad allora, gli eroi del bene avevano evitato di impugnare armi, poich&amp;eacute; il loro obiettivo era preservare la vita in ogni sua forma, non distruggerla. Tuttavia, di fronte alla minaccia crescente rappresentata dagli eroi malvagi e alla loro insaziabile sete di potere, furono costretti a cambiare approccio. Per proteggere s&amp;eacute; stessi e l&#039;umanit&amp;agrave;, presero le armi e si prepararono a combattere.&lt;/p&gt;\r\n&lt;p&gt;Quella guerra divamp&amp;ograve; su scala mondiale, coinvolgendo popoli e nazioni. Sebbene i guerrieri del bene fossero animati da nobili intenzioni, si trovavano in svantaggio: i loro avversari, corrotti dall&#039;oscurit&amp;agrave;, erano i pi&amp;ugrave; potenti tra i 14 e sfruttavano senza scrupoli i loro formidabili poteri. Tuttavia, i protettori della giustizia trovarono forza e sostegno nelle genti che difendevano. Gli esseri umani, riconoscenti e solidali, si unirono a loro, formando un&#039;alleanza di speranza contro le forze della tirannia e della distruzione.&lt;/p&gt;', 12, 1, 'La Guerra', 0, 26, 0, '&lt;p&gt;Aiuta anche tu gli eroi del bene: per il problema seguente identifica quali sono i dati di input e quali quelli di output. Elenca le variabili che devi dichiarare per poter avere i dati di input, di output e per effettuare le elaborazioni di dati.&lt;/p&gt;\r\n&lt;p&gt;Il primo guerriero del bene vuole utilizzare un esercito di elefanti per contrastare gli eroi del male. Ogni elefante pu&amp;ograve; trasportare 4 combattenti. Il 40% dei combattenti usa l&#039;arco, il 30% usa la spada ed i restanti usano la lancia. Tenendo conto che ci sono 243 elefanti, quante armi saranno necessarie per ciascun tipo per armare l&#039;intero esercito?&lt;/p&gt;', 0),
(3, '3f3b969f-5f65-434d-8b67-a6eb615faa84', '', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74f924089.76392619_exercise_editor_6a33a226088168.83321462.jpg&quot; alt=&quot;&quot;&gt;Il Creatore dei 14 si rese conto del danno che aveva provocato assegnando grandi poteri ad alcuni degli uomini. Non potendo tuttavia revocare i doni elargiti, decise che i guerrieri andavano in qualche modo fermati. Il Creatore invi&amp;ograve; sulla Terra una nube gassosa iridescente, al cui interno era nascosto un potente narcotico che avrebbe fatto piombare nel sonno gli individui dotati di grande potere che ne fossero venuti in contatto.&lt;/p&gt;', 12, 4, 'L\'intervento del creatore', 7, 26, 0, '', 1),
(4, '892ef3af-0ae6-430d-bbfc-84f68420e395', '&lt;p class=&quot;MsoNormal&quot;&gt;Utilizza un diagramma a blocchi per aiutare il Creatore a creare delle teche di cristallo per sigillare i guerrieri. Per costruire una teca serve sapere l&#039;altezza in cm dell&#039;eroe da racchiudere. La teca avr&amp;agrave; forma di parallelepipedo, la larghezza &amp;egrave; di 1,5 metri, la lunghezza dipende dall&#039;altezza dell&#039;eroe e la profondit&amp;agrave; sar&amp;agrave; di 1 metro. Calcola in cm quadrati quanto sar&amp;agrave; la superficie di cristallo da adoperare per costruire la teca.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74f977284.60608279_exercise_editor_6a33a16b57f7d5.41290840.jpg&quot; alt=&quot;&quot;&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Tutti i guerrieri, tranne i due neutrali, nascosti in un luogo sperduto, caddero addormentati. Il Creatore decise che gli eroi avrebbero dormito per sempre e lui con loro. Il Creatore sigill&amp;ograve; i 12 eroi in teche di cristallo per poi rinchiudere s&amp;eacute; stesso in una tredicesima teca d&#039;oro, dove nei suoi piani, avrebbe riposato per l&#039;eternit&amp;agrave; assieme alle sue creature.&lt;/p&gt;', 6, 3, 'Le Teche', 0, 33, 0, '&lt;p&gt;Utilizza un diagramma a blocchi per aiutare il Creatore a creare delle teche di cristallo per sigillare i guerrieri. Devi costruire il coperchio della teca, che &amp;egrave; rappresentato da un rettangolo. Chiedi base e altezza del rettangolo in input all&#039;utente e restituisci in output l&#039;area del rettangolo&lt;/p&gt;', 0),
(5, 'b561eae1-e46e-4f91-a72e-1b808dd44376', '&lt;p&gt;Gli esseri umani procedono nella strada della scienza e della tecnologia. Costruisci il diagramma a blocchi con Flowgorithm che risolva il seguente problema: va costruita una pista per le corse dei cavalli con forma a cerchio, come la parte nera in figura. La pista deve essere ricoperta di erba. Per sapere quanta erba serva ci serve sapere l&#039;area da ricoprire. La pista &amp;egrave; racchiusa da un cerchio pi&amp;ugrave; largo ed uno pi&amp;ugrave; stretto. Chiedi all&#039;utente il raggio dei due cerchi in metri e calcola l&#039;area arancione al loro interno, comunicandola poi all&#039;utente. Sapendo che per ogni metro quadrato di terreno servono 1,3 kg di semi d&#039;erba, calcola quanti kg totali di semi servono per ricoprire la pista e comunicalo all&#039;utente.&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 11.0pt; line-height: 107%; font-family: &#039;Aptos&#039;,sans-serif; mso-ascii-theme-font: minor-latin; mso-fareast-font-family: Aptos; mso-fareast-theme-font: minor-latin; mso-hansi-theme-font: minor-latin; mso-bidi-font-family: &#039;Times New Roman&#039;; mso-bidi-theme-font: minor-bidi; color: black; mso-color-alt: windowtext; background: white; mso-ansi-language: IT; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74f9c5c43.69376327_exercise_editor_6a33ad34247458.90034446.png&quot; alt=&quot;&quot; width=&quot;346&quot; height=&quot;296&quot;&gt;&lt;/span&gt;&lt;/p&gt;', 0, '&lt;p&gt;&lt;span style=&quot;font-size: 11.0pt; line-height: 107%; font-family: &#039;Aptos&#039;,sans-serif; mso-ascii-theme-font: minor-latin; mso-fareast-font-family: Aptos; mso-fareast-theme-font: minor-latin; mso-hansi-theme-font: minor-latin; mso-bidi-font-family: &#039;Times New Roman&#039;; mso-bidi-theme-font: minor-bidi; mso-ansi-language: IT; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fa0c996.33262243_exercise_editor_6a33aac71740b4.27361817.png&quot; alt=&quot;&quot;&gt; L&#039;umanit&amp;agrave;, nel frattempo, dovette affrontare la difficile sfida di convivere con il passato oscuro dei 14 guerrieri. Le storie di questi eroi, delle loro gesta e del conflitto epico, divennero leggende tramandate di generazione in generazione. Gli eventi servirono come una costante lezione sulla fragilit&amp;agrave; dell&#039;umanit&amp;agrave; di fronte al potere e alla corruzione. Con il passare dei secoli, la Terra si riprese dall&#039;epico conflitto, e la civilt&amp;agrave; umana continu&amp;ograve; a evolversi. Le teche di cristallo furono custodite come reliquie sacre, poste in un luogo segreto e inaccessibile, affinch&amp;eacute; l&#039;umanit&amp;agrave; non dimenticasse mai la lezione imparata da quegli eventi.&lt;/span&gt;&lt;/p&gt;', 6, 3, 'Il Mondo va avanti', 0, 33, 0, '&lt;p&gt;Gli esseri umani procedono nella strada della scienza e della tecnologia. Costruisci il diagramma a blocchi con Flowgorithm che risolva il seguente problema: va costruita una pista per le corse dei cavalli con forma a cerchio. La pista deve essere ricoperta di erba. Per sapere quanta erba serva ci serve sapere l&#039;area da ricoprire. Chiedi all&#039;utente il raggio del cerchio in metri e calcola l&#039;area, comunicandola poi all&#039;utente. Sapendo che per ogni metro quadrato di terreno servono 1,3 kg di semi d&#039;erba, calcola quanti kg totali di semi servono per ricoprire la pista e comunicalo all&#039;utente.&lt;/p&gt;', 1),
(6, '48cab30c-e58c-4526-8921-f770174cf34d', '&lt;p&gt;Crea un programma in Flowgorithm in cui chiedi all&#039;utente se intende essere un eroe del bene o del male. Se inserisce la parola &quot;male&quot; allora inserisci un messaggio in output su come pu&amp;ograve; conquistare il Mondo, se inserisce la parola &quot;bene&quot; allora comunica un messaggio su come migliorare il pianeta.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fa88bc1.36895315_exercise_editor_6a33adaa2c0d20.27340561.jpg&quot; alt=&quot;&quot;&gt; Tuttavia, nel corso del tempo, l&#039;umanit&amp;agrave; cominci&amp;ograve; a dimenticare la minaccia rappresentata dai 14 guerrieri e dai loro poteri. Le leggende diventarono storie per l&#039;intrattenimento, e il mondo si spinse sempre pi&amp;ugrave; avanti in nuovi sviluppi scientifici e tecnologici. Le teche furono dimenticate e perse nella nebbia del passato.&lt;/p&gt;\r\n&lt;p&gt;Purtroppo il Creatore aveva sottovalutato i poteri concessi ai 12 eroi intrappolati: sebbene i loro corpi fossero immobilizzati, i loro spiriti non lo erano. Poco a poco i guerrieri votati al male riuscirono a liberarsi delle loro spoglie mortali, fino a poter tornare ad agire nel nostro mondo nuovamente.&lt;/p&gt;', 6, 3, 'Il ritorno del male', 0, 34, 0, '', 0),
(7, '2c597a69-2d52-4019-aa70-b9aee8e68a51', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma in Flogorithm che chieda all&#039;utente un numero qualsiasi maggiore di 0. Effettua un controllo affinch&amp;eacute; il numero sia effettivamente maggiore di 0, se non lo &amp;egrave; comunica un errore. Moltiplica il numero per 8 e dividilo per 3. Se il numero risultante &amp;egrave; compreso tra 1 e 10 indica come i pronostici siano a favore dei guerrieri buoni, se &amp;egrave; compreso tra 11 e 20 allora comunica che probabilmente vinceranno gli eroi malvagi. Se &amp;egrave; superiore a 21, allora comunica che l&#039;esito della battaglia &amp;egrave; incerto. Usa gli operatori booleani per indicare i numeri compresi nella selezione&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Gli eroi malvagi scoprirono ben presto che, come spiriti, non potevano sfruttare i loro poteri per riprendere i loro scopi dove li avevano interrotti. Gli spiriti cominciarono cos&amp;igrave; a cercare un modo per poter tornare nel nostro mondo in forma materiale. Per anni studiarono e sono ormai prossimi a tornare tra noi, con nuove vesti, ma con la stessa malvagit&amp;agrave; di un tempo e solo gli eroi del bene potranno fermarli. In effetti, anche gli spiriti degli eroi del bene si risvegliarono, dandosi da fare per prepararsi ad un eventuale ritorno dei guerrieri del male: se fossero tornati sarebbe stato escogitato un piano per contrastarli.&lt;/p&gt;', 6, 3, 'Gli eroi del male sulla Terra', 0, 34, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma in Flogorithm che chieda all&#039;utente un numero qualsiasi maggiore di 0. &amp;nbsp;Moltiplica il numero per 8 e dividilo per 3. Se il numero risultante &amp;egrave; compreso tra 1 e 10 indica come i pronostici siano a favore dei guerrieri buoni, se &amp;egrave; compreso tra 11 e 20 allora comunica che probabilmente vinceranno gli eroi malvagi. Se &amp;egrave; superiore a 21, allora comunica che l&#039;esito della battaglia &amp;egrave; incerto. Usa gli operatori booleani per indicare i numeri compresi nella selezione&lt;/p&gt;', 0),
(8, '9a42c4ed-8222-45b8-880d-46cab8f0220a', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma su Flowgorithm che metta a disposizione un menu di scelta all&#039;utente. Il menu dovr&amp;agrave; semplicemente richiedere quale arma sia la preferita dell&#039;utente. La scelta sar&amp;agrave; tra spada, lancia, arco, ascia. Ogni arma dovr&amp;agrave; essere rappresentata da un numero. L&#039;utente sceglier&amp;agrave; il numero ed il programma gli comunicher&amp;agrave; quale arma ha selezionato.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;I due gruppi agirono in maniera differente: le forze del male hanno imparato come prendere possesso dei corpi di alcuni degli esseri umani, mentre gli eroi benevoli o, meglio, i loro spiriti, sono riusciti a capire il modo di donare i loro poteri totalmente ad altri uomini. In questo modo essi morirebbero, ma un tale sacrificio permetterebbe di fermare i guerrieri malvagi. Alla mezzanotte dell&#039;ultimo giorno dell&#039;anno il momento sar&amp;agrave; giunto. Allora gli eroi malvagi torneranno ad infestare la Terra e voi, squadra di eroi, siete i prescelti: a voi verranno affidati i poteri degli eroi benevoli per opporvi al male che sta arrivando!&lt;/p&gt;', 6, 3, 'Anche il Bene ritorna', 0, 34, 0, '', 0),
(9, '6f56e731-6ead-4279-ac6b-e1ee14d7d6ff', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma su Flowgorithm per riuscire ad individuare la settima meteora. Chiedi in input un numero all&amp;rsquo;utente ed in base al risultato comunica su quale montagna si trovi il monastero: se il numero &amp;egrave; compreso tra 0 e 10: il monastero si trova sulla Montagna Bianca, se compreso tra 11 e 18 sulla Montagna Verde, se minore di 0 si trova sulla Montagna Rossa, se compreso tra 19 e 32 sulla Montagna Rosa, se compreso tra 33 e 50 sulla Montagna Nera, se tra 51 e 100 sulla Montagna Gialla, altrimenti, se maggiore di 100, sulla Montagna Blu.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74faf5c15.04036798_exercise_editor_6a33af27c02451.78977713.jpg&quot; alt=&quot;&quot;&gt; Gli eroi neutrali si erano nascosti in luoghi isolati per poter sfuggire alle guerre scatenate dai loro compagni. Era quindi molto probabile che uno di loro vivesse da millenni come eremita in un ambiente appartato e lontano dalla societ&amp;agrave;. Ricercando in rete gli eroi del bene riuscirono a scoprire la leggenda della settima meteora. In Grecia esistono 6 monasteri costruiti su alte rupi praticamente inaccessibili, dette meteore. La leggenda narra dell&#039;esistenza di un settimo monastero nascosto alla vista di tutti che si trova nella meteora pi&amp;ugrave; elevata ed abitato dal fantasma di un monaco che appare sempre uguale da centinaia di anni e che si &amp;egrave; mostrato ai pochi coraggiosi che siano riusciti a raggiungerlo.&lt;/p&gt;', 6, 3, 'La settima meteora', 0, 34, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma su Flowgorithm per riuscire ad individuare la settima meteora. Chiedi in input un numero all&amp;rsquo;utente ed in base al risultato comunica su quale montagna si trovi il monastero: se il numero &amp;egrave; minore di 30: il monastero si trova sulla Montagna Bianca, se compreso tra 31 e 100 sulla Montagna Verde, se maggiore di 100, sulla Montagna Blu.&lt;/p&gt;', 1),
(10, '2c90447e-7cb7-41d3-95a4-f98be5d757d2', '&lt;p&gt;Gli eroi scalano la montagna, quanto sono saliti?&lt;/p&gt;\r\n&lt;p&gt;Crea un programma in Flowgorithm che continui a chiedere all&#039;utente di quanto sono saliti gli eroi (in metri) e quanto tempo hanno impiegato a salire quel tratto (in minuti), fino a quando l&#039;utente non inserisce 0 nei metri per indicare che sono arrivati. Comunica all&#039;utente quanti metri hanno percorso in totale (quindi la somma dei metri) e quanto tempo hanno impiegato (in ore) per arrivare in cima.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fb46a53.71552661_exercise_editor_6a33b044cd1a06.19944820.jpg&quot; alt=&quot;&quot;&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Si trattava di un&#039;ottima possibilit&amp;agrave; e la squadra degli eroi del bene decise di recarsi in Grecia, affrontando la scalata della settima meteora, dove avrebbe potuto nascondersi uno degli eroi neutrali.&lt;/p&gt;\r\n&lt;p&gt;Dopo un volo internazionale e una lunga guida attraverso le pittoresche strade greche, giunsero finalmente ai piedi delle maestose Meteore, le sei imponenti formazioni rocciose su cui erano stati costruiti monasteri antichi, ma ora la loro attenzione era concentrata sulla settima meteora, quella che nessuno aveva mai visto.&lt;/p&gt;\r\n&lt;p&gt;La scalata si rivel&amp;ograve; estremamente ardua, con percorsi rocciosi e ripidi che sfidavano la loro resistenza fisica e mentale. Ma gli eroi del bene erano guidati da un proposito forte e la loro determinazione li port&amp;ograve; in alto, fino a quando finalmente raggiunsero il punto pi&amp;ugrave; elevato delle Meteore.&lt;/p&gt;\r\n&lt;p&gt;L&amp;igrave;, circondati da una vista mozzafiato delle valli e dei villaggi sottostanti, scoprirono una piccola apertura nella roccia che sembrava condurre all&#039;interno della settima meteora. Era un passaggio stretto e buio, ma non potevano permettersi di indugiare.&lt;/p&gt;', 6, 3, 'La scalata della montagna', 0, 32, 0, '&lt;p&gt;Gli eroi scalano la montagna, quanto sono saliti?&lt;/p&gt;\r\n&lt;p&gt;Crea un programma in Flowgorithm che continui a chiedere all&#039;utente di quanto sono saliti gli eroi (in metri) fino a quando l&#039;utente non inserisce 0 per indicare che sono arrivati. Comunica all&#039;utente quanti metri hanno percorso in totale (quindi la somma dei metri).&lt;/p&gt;', 1),
(11, '913b00ea-c448-49fa-b17c-61ef891baf8f', '&lt;p class=&quot;MsoNormal&quot;&gt;Gli eroi devono trovare la massima verit&amp;agrave;: Realizza un programma che legga una serie di numeri in input dall&#039;utente e si fermi quando il numero letto &amp;egrave; uguale a 0 (quindi il ciclo continua fintanto che il numero letto &amp;egrave; diverso da 0). Il programma deve dire in output qual &amp;egrave; il maggiore dei numeri letti.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fb9d023.02983668_exercise_editor_6a33b0c666c6a0.32413844.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Armarti di coraggio, entrarono nella grotta e iniziarono una discesa incerta nelle profondit&amp;agrave; della montagna. Non appena si fecero strada attraverso il tunnel oscuro, iniziarono a sentire un canto monotono e pacifico che echeggiava nelle loro orecchie. Era un canto di preghiera.&lt;/p&gt;\r\n&lt;p&gt;Seguendo il suono, giunsero finalmente in una vasta caverna illuminata da candele, con un piccolo altare al centro. Seduto davanti all&#039;altare c&#039;era un monaco dall&#039;aspetto incredibilmente giovane, apparentemente immutato nel tempo. Il monaco stava cantando e pregando in una lingua antica, i cui significati erano sconosciuti agli eroi.&lt;/p&gt;\r\n&lt;p&gt;Con rispetto, si avvicinarono al monaco e gli chiesero se fosse a conoscenza dell&#039;eroe neutrale che cercavano. Il monaco li guard&amp;ograve; con occhi saggi e disse: &quot;Siete giunti fin qui perch&amp;eacute; cercate la verit&amp;agrave; e la saggezza. Il mio compito &amp;egrave; custodire il segreto dell&#039;eroe neutrale che cercate.&quot;&lt;/p&gt;', 6, 3, 'La grotta ed il monaco', 0, 32, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Gli eroi devono trovare la massima verit&amp;agrave;: Realizza un programma che legga una serie di numeri in input dall&#039;utente e si fermi quando il numero letto &amp;egrave; uguale a 0 (quindi il ciclo continua fintanto che il numero letto &amp;egrave; diverso da 0). Il programma deve dire se &amp;egrave; stato inserito un numero maggiore di 50&lt;/p&gt;', 1),
(12, 'a8740575-4184-468d-a907-f0e5c591ce71', '&lt;p&gt;Per 8 settimane gli eroi del bene rimangono al cospetto di Aionios. Ogni settimana ottengono un nuovo bagaglio di conoscenza, quantificabile con un numero.&lt;/p&gt;\r\n&lt;p&gt;Scrivi un programma che utilizzi un ciclo FOR e chiedi all&#039;utente per ogni settimana quale sia il numero indicante quanta conoscenza acquisiscono gli eroi. Alla fine del ciclo, stampa la media della conoscenza acquisita&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fbfe5a0.76541741_exercise_editor_6a33b12ba11787.83178296.png&quot; alt=&quot;&quot;&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Gli eroi del bene spiegarono il loro scopo e il motivo per cui erano venuti. Il monaco li osserv&amp;ograve; attentamente e poi annu&amp;igrave;. &quot;Siete degni di conoscere la verit&amp;agrave;. L&#039;eroe neutrale che cercate si chiama Aionios, e vive qui con me da millenni. &amp;Egrave; il custode della conoscenza antica e della pace. Si &amp;egrave; nascosto per evitare le guerre e i conflitti che affliggono il mondo esterno.&quot;&lt;/p&gt;\r\n&lt;p&gt;Con grande rispetto, gli eroi chiesero di incontrare Aionios. Il monaco acconsent&amp;igrave; e li condusse a una piccola cella all&#039;interno della grotta. L&amp;igrave; trovarono Aionios, seduto in meditazione, il suo viso sereno e calmo.&lt;/p&gt;\r\n&lt;p&gt;Quando Aionios si alz&amp;ograve; e li guard&amp;ograve;, i loro cuori si riempirono di un senso di pace e saggezza. Era un essere etereo, un vero custode della conoscenza e dell&#039;equilibrio.&lt;/p&gt;\r\n&lt;p&gt;La squadra degli eroi del bene trascorse giorni con Aionios, apprendendo dalle sue parole e dalla sua esperienza millenaria.&lt;/p&gt;', 6, 3, 'L\'eroe neutrale Aionios, il saggio', 0, 32, 0, '&lt;p&gt;Per 8 settimane gli eroi del bene rimangono al cospetto di Aionios. Ogni settimana ottengono un nuovo bagaglio di conoscenza, quantificabile con un numero.&lt;/p&gt;\r\n&lt;p&gt;Scrivi un programma che utilizzi un ciclo FOR e chiedi all&#039;utente per ogni settimana quale sia il numero indicante quanta conoscenza acquisiscono gli eroi. Alla fine del ciclo, stampa la somma della conoscenza acquisita&lt;/p&gt;', 1),
(13, 'd885df3e-eb9f-47da-8ef8-d21d10218faa', '&lt;p class=&quot;MsoNormal&quot;&gt;Gli eroi devono decidere quale arma sia la pi&amp;ugrave; potente. Le armi selezionate per la competizione sono 3 sulle 6 totali: arco, spada e lancia. Crea un programma con un ciclo FOR che per 100 volte estragga un numero casuale da 1 a 3 e conti quante volte esce ciascuno dei 3 numeri. Ogni numero rappresenta un&#039;arma. Alla fine, il programma comunica quale delle 3 armi ha ottenuto pi&amp;ugrave; estrazioni casuali.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fc76a79.42278868_exercise_editor_6a33b1a65d3f06.87242503.jpg&quot; alt=&quot;&quot;&gt;Dopo 8 settimane di saggezza gli eroi del bene decisero infine di chiedere ad Aionios quali fossero e dove si trovassero le armi che in tempi immemori gli antichi eroi nascosero. Aionios mise in guardia i nuovi eroi: &quot;Potenti erano gli oggetti forgiati dagli antichi eroi del bene e solo dopo un lungo addestramento esse possono essere trovate&quot;. L&#039;eroe neutrale decise comunque di aiutare i nuovi eroi, disegnando una mappa che potesse condurli ai luoghi dove le armi erano nascoste.&amp;nbsp;&lt;br&gt;Le armi da ritrovare erano:&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;L&#039;Arco d&#039;Avorio&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;La Lancia del Destino&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;La Spada Fiammeggiante&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Lo Scudo Dorato&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Il Martello dell&#039;Eden&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;L&#039;Ascia SpaccaTerra&lt;br&gt;Ognuna era ubicata in un remoto angolo della Terra. Non restava che partire per ritrovarle&lt;/p&gt;', 6, 3, 'Le armi degli eroi del bene', 0, 32, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Gli eroi devono decidere quale arma sia la pi&amp;ugrave; potente. Le armi selezionate per la competizione sono 3 sulle 6 totali: arco, spada e lancia. Crea un programma con un ciclo FOR che per 100 volte estragga un numero casuale da 1 a 3 e conti quante volte esce ciascuno dei 3 numeri. Ogni numero rappresenta un&#039;arma. Alla fine, il programma comunica i valori dei totali per ogni arma.&lt;/p&gt;', 1),
(14, '5d30eef1-76ff-4bf0-a542-34346952bfd2', '&lt;p&gt;Un numero primo &amp;egrave; visibile tra mille per chi sa dove guardare, come il soldato della storia. Scrivere un programma che dato un numero intero in input stabilisce se &amp;egrave; un numero primo o meno.&lt;/p&gt;\r\n&lt;p&gt;PS: Un numero &amp;egrave; primo se &amp;egrave; divisibile solo per 1 e per s&amp;eacute; stesso (il resto della divisione per i numeri in mezzo sar&amp;agrave; sempre diverso da 0).&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fcc73f4.04717139_exercise_editor_6a33d9ac0cada6.15782507.jpg&quot; alt=&quot;&quot;&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Cr&amp;eacute;cy, 1346. Filippo VI di Francia pronunci&amp;ograve; l&#039;ordine fatidico: &quot;&lt;strong&gt;Carica&lt;/strong&gt;!&quot;. La cavalleria si lanci&amp;ograve; in avanti, il fragore degli zoccoli rimbomb&amp;ograve; nella valle.&lt;/p&gt;\r\n&lt;p&gt;Dall&amp;rsquo;altro lato, gli arcieri e i balestrieri inglesi iniziarono a scoccare frecce con precisione letale. I primi cavalieri caddero, travolti dai loro stessi destrieri, le pesanti armature divennero tombe d&amp;rsquo;acciaio. Poi i cavalli trovarono le buche nascoste nel terreno: molti vi precipitarono, aumentando il caos tra le file francesi. Il panico si diffuse. Quel glorioso squadrone, imbattuto in innumerevoli battaglie, si stava disgregando.&lt;/p&gt;\r\n&lt;p&gt;Ma la fanteria inglese era vicina, e i francesi, colti da un ultimo slancio di orgoglio, spronarono i cavalli con rinnovato vigore. Fu allora che lunghi pali si sollevarono all&amp;rsquo;improvviso, sbarrando loro la strada. Per la cavalleria francese fu la fine.&lt;/p&gt;\r\n&lt;p&gt;Edoardo III e suo figlio, il temuto &lt;strong&gt;Black Prince&lt;/strong&gt;, al comando della cavalleria inglese, si lanciarono all&amp;rsquo;inseguimento del nemico in fuga, disperdendolo definitivamente.&lt;/p&gt;\r\n&lt;p&gt;La battaglia di &lt;strong&gt;Crecy &lt;/strong&gt;fu l&#039;ultimo luogo dove comparve l&#039;Arco d&#039;Avorio, una delle armi degli eroi del bene, che contribu&amp;igrave; alla vittoria degli inglesi. La ricerca dei nuovi eroi doveva partire pertanto dalla Francia, dove si recarono grazie ad un volo diretto partito da Atene. I nuovi eroi si recarono al museo di Crecy dove si trovarono di fronte ad un immenso arazzo rappresentante la battaglia. Tra i mille guerrieri l&amp;igrave; presenti, uno recava in mano un arco diverso dagli altri, semplice da vedere per chi sapeva cosa guardare. Guardando pi&amp;ugrave; da vicino, notarono lo stemma della casata degli Stafford sul petto del guerriero. Era un indizio cruciale. Dovevano ora dirigersi verso lo Staffordshire, il luogo di provenienza di quella casata, per cercare il potente arco.&lt;/p&gt;', 6, 3, 'La battaglia di Crecy', 0, 32, 0, '&lt;p&gt;Scrivi un programma che controlli se un numero dato in input &amp;egrave; primo: crea un ciclo for che va da 2 fino al numero inserito meno 1 e controlla il resto della divisione del numero per il contatore del ciclo (operatore %). Conta quante volte il resto della divisione &amp;egrave; uguale a 0 (usando una variabile per la somma). Inserisci alla fine un if: se la somma &amp;egrave; uguale a 0 allora dai in output che il numero &amp;egrave; primo, altrimenti dai in output che non &amp;egrave; primo&lt;/p&gt;', 2),
(15, '0a2542ec-3d8b-44f9-94bd-b22ff0625cf5', '&lt;p&gt;Una delle difficolt&amp;agrave; incontrate dai nuovi eroi nella Foresta delle Gemme sono le formiche Idra: una strana popolazione di formiche che li attacca. Per ogni formica abbattuta N prendono il suo posto.&lt;/p&gt;\r\n&lt;p&gt;Dati due interi che rappresentano rispettivamente il tasso di moltiplicazione delle formiche (quante nuove formiche prendono il posto di una formica abbattuta) e la quantit&amp;agrave; di formiche massima che posso affrontare, dire dopo quanti abbattimenti di formiche ci si trover&amp;agrave; di fronte il numero massimo di formiche da affrontare, tenendo conto che si parte da una sola formica iniziale. Esempio: massimo = 1000, tasso di moltiplicazione = 3. Formiche iniziali = 1, Dopo 1 formica abbattuta mi ritrovo 1*3=3 formiche, se abbatto le 3 formiche mi ritrovo 3*3=9 formiche, se abbatto le 9, mi ritrovo 9*3=27 formiche&amp;hellip; e cos&amp;igrave; via fino ad arrivare a 1000. Usare un ciclo while per calcolare in quanti turni ho abbattuto formiche prima di superare il massimo (7 turni nel nostro caso).&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fd11849.95415496_exercise_editor_6a33dafbe74909.22764258.jpg&quot; alt=&quot;&quot;&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Senza perdere tempo, i nuovi eroi presero un ulteriore volo diretto verso il Regno Unito e si diressero verso lo Staffordshire. Arrivarono in una regione collinare, ricca di storia e tradizione. Decisero di iniziare la loro ricerca visitando le antiche dimore e i castelli associati agli Stafford, sperando di trovare qualche indizio sulla presenza dell&#039;Arco d&#039;Avorio. Durante le loro ricerche, vennero a conoscenza di una leggenda locale che parlava di un arco magico nascosto nelle profondit&amp;agrave; di una foresta secolare. Sembrava che solo coloro che erano destinati a possederlo potessero trovarlo. Era un luogo misterioso, noto come &quot;La Foresta delle Gemme,&quot; e i racconti suggerivano che l&#039;arco fosse protetto da incantesimi potenti per evitare che cadesse nelle mani sbagliate.&lt;/p&gt;\r\n&lt;p&gt;I nuovi eroi erano determinati a seguire questa pista. Si avventurarono nella foresta con cautela, consapevoli delle sfide che li attendevano. Attraversarono fitte boscaglie, seguendo le tracce di leggende e misteri. Ogni passo li avvicinava un po&#039; di pi&amp;ugrave; alla possibilit&amp;agrave; di recuperare l&#039;Arco d&#039;Avorio per potenziare la loro squadra di eroi del bene.&lt;/p&gt;\r\n&lt;p&gt;Ma la Foresta delle Gemme non era solo un luogo di segreti nascosti; era anche un luogo di pericoli e prove. E mentre i nuovi eroi del bene avanzavano nella foresta, erano ignari del fatto che fossero osservati da occhi malvagi che volevano l&#039;arco per i loro scopi negativi. La loro avventura stava per diventare ancora pi&amp;ugrave; intricata e pericolosa di quanto avessero mai immaginato.&lt;/p&gt;', 6, 3, 'Lo Staffordshire e la Foresta delle Gemme', 0, 32, 0, '&lt;p&gt;Una delle difficolt&amp;agrave; incontrate dai nuovi eroi nella Foresta delle Gemme sono le formiche Idra: una strana popolazione di formiche che li attacca. Per ogni formica abbattuta 2 prendono il suo posto.&lt;/p&gt;\r\n&lt;p&gt;Chiedi in input quante formiche al massimo possono affrontare gli eroi. All&#039;inizio c&#039;&amp;egrave; una sola formica. Usa un ciclo while che si ferma quando il numero di formiche raggiunge il numero massimo. Ad ogni ciclo il numero di formiche deve raddoppiare. Alla fine comunica in output quanti raddoppi sono stati fatti per arrivare al numero massimo di formiche, cio&amp;egrave; quante volte si &amp;egrave; entrati nel ciclo (usa una variabile per tenerne conto). &amp;nbsp;Esempio: massimo = 100. Formiche iniziali = 1, Dopo 1 formica abbattuta mi ritrovo 1*2=2 formiche, se abbatto le 2 formiche mi ritrovo 2*2=4 formiche, se abbatto le 4, mi ritrovo 4*2=8 formiche&amp;hellip; e cos&amp;igrave; via fino ad arrivare a 100. In questo caso supero il massimo dopo 7 cicli.&lt;/p&gt;', 2),
(16, '2834f935-fd59-4967-8f14-261ac273ced0', '&lt;p&gt;Jambalaya e Shinzo Hanzei scattano vero l&#039;Arco d&#039;Avorio. La distanza dal piedistallo &amp;egrave; di X metri. Jambalaya in 10 secondi riesce a percorrere 3 metri. Shinzo Hanzei, invece, percorre 7 metri in 10 secondi. Chiedere in input all&#039;utente la distanza X dal piedistallo, che deve essere di almeno 50 metri e dare in output dopo quanto tempo Jambalaya e Shinzo Hanzei riescono a tagliare il traguardo, dando in output dove si trovano ogni 10 secondi.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fd5ec14.61614683_exercise_editor_6a33dc5620a989.76895503.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;I nuovi eroi del bene arrivarono infine in una radura al centro della quale si ergeva un piedistallo. Ecco l&#039;Arco d&#039;Avorio: esso era poggiato placidamente sopra la bassa colonna. Gli eroi si lanciarono verso l&#039;arma, ma una forza misteriosa li respinse tutti. Tutti tranne Jambalaya, un ragazzo giamaicano che faceva parte della squadra, che non sent&amp;igrave; su di s&amp;eacute; nulla, tranne la brezza del tardo pomeriggio. Era lui il ragazzo destinato a portare l&#039;Arco d&#039;Avorio, erede dell&#039;eroe del bene che fu. Jambalaya si avvicin&amp;ograve; al piedistallo, una figura si materializz&amp;ograve; di fronte a lui, emergendo dall&#039;ombra come un fantasma. Era un ragazzo di media statura, agile e veloce, con corti capelli neri lisci che gli ricadevano ai lati del viso ed un naso aquilino. I nuovi eroi lo riconobbero subito: era Shinzo Hanzei, uno degli eroi malvagi. Era difficile credere che uno dei loro avversari fosse l&amp;igrave;, di fronte a loro, proprio quando stavano cercando di reclamare l&#039;Arco d&#039;Avorio.&lt;/p&gt;', 6, 3, 'Si svelano i primi eroi', 0, 32, 0, '&lt;p&gt;Jambalaya e Shinzo Hanzei scattano vero l&#039;Arco d&#039;Avorio. Chiedi in input all&#039;utente la distanza dal piedistallo. Jambalaya in 10 secondi riesce a percorrere 3 metri. Dare in output dopo quanto tempo (in secondi) Jambalaya arriva al piedistallo, dando in output dove si trova ogni 10 secondi.&lt;/p&gt;', 1),
(17, 'bc159f69-1e50-424a-bb5f-9465713425e0', '&lt;p class=&quot;MsoNormal&quot;&gt;Sapendo che Shinzo Hanzei viaggia a 90 metri al secondo e la freccia dell&#039;Arco d&#039;Avorio viaggia 110 metri al secondo e che Shinzo Hanzei ha un vantaggio di X metri, calcolare dopo quanti secondi la freccia raggiunge l&#039;eroe malvagio, stampando le posizioni di freccia e Shinzo dopo ogni secondo. I metri di vantaggio di Shinzo rispetto alla freccia vanno inseriti dall&#039;utente e non possono essere inferiori a 800.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fdbdc76.96247121_exercise_editor_6a33dd0ec64967.20077285.jpg&quot; alt=&quot;&quot;&gt; Shinzo Hanzei sorrideva in modo beffardo, consapevole dell&#039;impatto della sua presenza. &quot;Avete cercato l&#039;Arco d&#039;Avorio, e ora lo avete trovato, ma non sar&amp;agrave; cos&amp;igrave; semplice come sembra,&quot; disse, la sua voce trasudava una malvagia fiducia in s&amp;eacute;.&lt;/p&gt;\r\n&lt;p&gt;Shinzo Hanzei era incredibilmente veloce, non si riuscivano a seguire i suoi movimenti, tale era la sua velocit&amp;agrave;. Jamabalaya non poteva competere con il potere di Shinzo! I due scattarono verso il piedistallo, in meno di 1 secondo Shinzo era l&amp;igrave; e pose le sue mani sull&#039;Arco. Jamabalaya era sconfitto, l&#039;Arco d&#039;Avorio perso nelle mani degli eroi del male. Ma l&#039;Arco non era destinato ad un eroe malvagio: un&#039;onda d&#039;urto di impressionante potenza si dipan&amp;ograve; dal piedistallo, investendo in pieno Shinzo Hanzei, che vol&amp;ograve; a diversi metri dall&#039;arma. Jambalaya arriv&amp;ograve; anch&#039;egli alla piccola colonna e finalmente afferr&amp;ograve; l&#039;Arco d&#039;Avorio. Lo straordinario potere dell&#039;Arco era quello di colpire sempre il bersaglio. Una sola freccia era disponibile. Shinzo Hanzei si alz&amp;ograve; da terra, pronto a scattare fulmineo verso il suo avversario e mettere fine alla sua vita.&lt;/p&gt;', 6, 3, 'La conquista dell\'arco', 0, 32, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Sapendo che Shinzo Hanzei viaggia a 9 metri al secondo e che la freccia lo colpisce dopo 24 minuti, dire dopo quanti metri la freccia arriva a colpire Shinzo, tenendo conto che Shinzo partiva 10 metri avanti. Quindi calcolare quanti secondi in 24 minuti, quanti metri ha fatto Shinzo in quei secondi, aggiungendo alla fine la posizione iniziale, che la freccia deve comuqnue percorrere.&lt;/p&gt;', 1),
(18, '59814944-310d-4dfa-bcc4-0d3c90b706aa', '', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fe0e263.39057241_exercise_editor_6a33de9d6c6f74.30073136.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Senza esitazione, Jambalaya prese la mira, la freccia scintillante posta sull&#039;arco. L&#039;energia e la concentrazione fluttuavano nell&#039;aria mentre il destino dei due eroi si scontrava.&lt;/p&gt;\r\n&lt;p&gt;Con un movimento fluido e preciso, Jambalaya scocc&amp;ograve; la freccia con l&#039;Arco d&#039;Avorio, e questa fendette l&#039;aria in direzione di Shinzo Hanzei con una rapidit&amp;agrave; travolgente. L&#039;eroe malvagio non ebbe il tempo di reagire; la freccia lo colp&amp;igrave; in pieno petto e lo scaravent&amp;ograve; all&#039;indietro con una forza inarrestabile.&lt;/p&gt;\r\n&lt;p&gt;Shinzo Hanzei si ritrov&amp;ograve; a terra, gravemente ferito ma ancora vivo. L&#039;Arco d&#039;Avorio aveva risparmiato la sua vita, ma aveva messo fine alla sua minaccia. L&#039;eroe del male non poteva pi&amp;ugrave; combattere, ma con le ultime energie riusc&amp;igrave; a rialzarsi e fuggire, sfruttando la sua velocit&amp;agrave;, che nessuno degli eroi del bene poteva eguagliare.&lt;/p&gt;\r\n&lt;p&gt;Con l&#039;Arco d&#039;Avorio ora nelle mani dei nuovi eroi del bene, erano un passo pi&amp;ugrave; vicini a riunire la squadra e a fronteggiare le minacce oscure che si nascondevano nell&#039;ombra. La loro avventura era appena iniziata, ma erano determinati a portarla a termine con successo per il bene di tutti.&lt;/p&gt;', 18, 4, 'La sconfitta di Shinzo', 4, 25, 0, '', 1),
(19, '4942fa50-81a2-4112-bc7e-bab52a646432', '&lt;p&gt;Gli eroi arrivarono a Monaco. C&#039;era un&#039;importante partita di Champions e si fermarono a vederla: Bayern contro Real Madrid.&lt;/p&gt;\r\n&lt;p&gt;Realizzare in Python il seguente programma:&lt;/p&gt;\r\n&lt;p&gt;Chiedere all&#039;utente i nominativi di 2 squadre di calcio e i goal realizzati. Stampare la squadra vincitrice o se c&#039;&amp;egrave; stato un pareggio.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fe607a3.29788373_exercise_editor_6a33df8329f5c0.47147843.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Estate 1940. Polonia, Danimarca, Norvegia, Belgio, Paesi Bassi e Francia cadono sotto la terrificante potenza della Blitzkrieg, la guerra lampo tedesca. Hitler si impadronisce della maggior parte dell&#039;Europa. 3 sono le armi a disposizione del nazismo: una potente industria bellica che sforna carri armati, corazzate, armi e munizioni a ciclo continuo, la macchina Enigma, una macchina per cifrare i messaggi da inviare al fronte che nessuno riesce ad interpretare, anche con le pi&amp;ugrave; brillanti menti a disposizione e infine, secondo la leggenda, la Lancia del Destino. Un oggetto mistico che sembra provenire dall&#039;antichit&amp;agrave;: si dice fosse la lancia utilizzata dal soldato per trafiggere il costato di Ges&amp;ugrave; Cristo durante la crocifissione. Le leggende narrano che chiunque la possegga possa ottenere un potere o una forza straordinari.&lt;/p&gt;\r\n&lt;p&gt;Secondo la mappa di Aionios, l&#039;ultima apparizione della Lancia del Destino fu durante l&#039;operazione Valchiria, un piano elaborato da ufficiali dell&#039;esercito tedesco durante la Seconda guerra mondiale per rovesciare il regime nazista e uccidere Adolf Hitler. L&#039;operazione fall&amp;igrave; il 20 luglio 1944 quando una bomba piazzata da Claus von Stauffenberg esplose durante una riunione militare a Rastenburg, in Prussia orientale. Sebbene l&#039;esplosione abbia ferito Hitler, non lo uccise e il colpo di stato fall&amp;igrave;. Ma l&#039;operazione ottenne un importante risultato: il Barone von Fustenberg, infiltratosi nel quartier generale di Hitler a Berlino, riusc&amp;igrave; a trafugare la Lancia del Destino, portandola con s&amp;eacute; a Monaco di Baviera. Da l&amp;igrave; inizi&amp;ograve; il declino del Terzo Reich. I nuovi eroi del bene si diressero quindi verso la nuova tappa in Germania, sulle tracce della seconda arma.&lt;/p&gt;', 10, 3, 'Blitzkrieg', 0, 27, 0, '', 0);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(20, 'a70aae8b-f5a3-4740-9a58-657673eb3e8b', '&lt;p&gt;I nuovi eroi devono cercare il&amp;nbsp;&lt;strong&gt;diario del barone&lt;/strong&gt;&amp;nbsp;nascosto all&#039;interno di una&amp;nbsp;&lt;strong&gt;grande biblioteca&lt;/strong&gt;.&lt;br&gt;Per organizzare la ricerca, gli eroi decidono di&amp;nbsp;&lt;strong&gt;dividere la biblioteca in 3 sezioni&lt;/strong&gt;.&lt;/p&gt;\r\n&lt;p&gt;1. Suddivisione della biblioteca&lt;/p&gt;\r\n&lt;p&gt;Usando Python, genera&amp;nbsp;&lt;strong&gt;due numeri casuali&lt;/strong&gt;&amp;nbsp;(chiamati&amp;nbsp;&lt;code&gt;limite1&lt;/code&gt;&amp;nbsp;e&amp;nbsp;&lt;code&gt;limite2&lt;/code&gt;) con la funzione&amp;nbsp;&lt;code&gt;random.randint&lt;/code&gt;.&lt;br&gt;Assicurati che i valori generati soddisfino queste condizioni:&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;code&gt;limite1&lt;/code&gt;&amp;nbsp;deve essere&amp;nbsp;&lt;strong&gt;compreso tra 100 e 800&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;code&gt;limite2&lt;/code&gt;&amp;nbsp;deve essere&amp;nbsp;&lt;strong&gt;compreso tra&amp;nbsp;&lt;code&gt;limite1 + 1&lt;/code&gt;&amp;nbsp;e 1500&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;div class=&quot;contain-inline-size rounded-md border-[0.5px] border-token-border-medium relative bg-token-sidebar-surface-primary&quot;&gt;\r\n&lt;div class=&quot;overflow-y-auto p-4&quot; dir=&quot;ltr&quot;&gt;&lt;code&gt;import random&lt;/code&gt;&lt;/div&gt;\r\n&lt;div class=&quot;overflow-y-auto p-4&quot; dir=&quot;ltr&quot;&gt;&lt;code&gt;limite1 = random.randint(100, 800)&lt;/code&gt;&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;p&gt;Con questi due valori, dividi la biblioteca nel seguente modo:&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;strong&gt;Sezione 1&lt;/strong&gt;: libri numerati da&amp;nbsp;&lt;strong&gt;1 a&amp;nbsp;&lt;code&gt;limite1&lt;/code&gt;&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;strong&gt;Sezione 2&lt;/strong&gt;: libri da&amp;nbsp;&lt;strong&gt;&lt;code&gt;limite1 + 1&lt;/code&gt;&amp;nbsp;a&amp;nbsp;&lt;code&gt;limite2&lt;/code&gt;&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;li&gt;\r\n&lt;p&gt;&lt;strong&gt;Sezione 3&lt;/strong&gt;: libri da&amp;nbsp;&lt;strong&gt;&lt;code&gt;limite2 + 1&lt;/code&gt;&amp;nbsp;a 2000&lt;/strong&gt;&lt;/p&gt;\r\n&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;2. Calcolo delle probabilit&amp;agrave;&lt;/p&gt;\r\n&lt;p&gt;Ogni libro ha la&amp;nbsp;&lt;strong&gt;stessa probabilit&amp;agrave;&lt;/strong&gt;&amp;nbsp;di essere il diario.&lt;br&gt;Calcola e stampa la probabilit&amp;agrave; che il diario si trovi in&amp;nbsp;&lt;strong&gt;ognuna delle tre sezioni&lt;/strong&gt;, tenendo conto del numero di libri in ciascuna.&lt;/p&gt;\r\n&lt;p&gt;3. Scoperta del diario&lt;/p&gt;\r\n&lt;p&gt;Chiedi all&amp;rsquo;utente di inserire il numero del libro in cui &amp;egrave; stato trovato il diario.&lt;br&gt;In base al numero inserito,&amp;nbsp;&lt;strong&gt;indica a quale sezione appartiene&lt;/strong&gt; il libro trovato&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74fead1a1.14188191_exercise_editor_6a33dfffbd7a15.11722470.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;La citt&amp;agrave; di Monaco di Baviera accolse i nuovi eroi del bene con le sue strade pittoresche e la ricca storia, ma era anche intrisa dei segni oscuri lasciati dal passaggio del regime nazista.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Con le informazioni a loro disposizione essi iniziarono a seguire le tracce lasciate dal Barone von Fustenberg, il quale, con la Lancia del Destino in suo possesso, aveva fatto ritorno al suo paese natale, dove viveva in uno splendido castello. I nuovi eroi bussarono alle porte del castello, scoprendo che ora era abitato da un rinomato medico di Monaco: il dottor Shubert. Spiegarono la storia della lancia ed il dottore fu subito pronto ad aiutarli, indicando come il Barone von Fustenberg aveva lasciato un diario all&#039;interno della biblioteca, ma che non era mai stato trovato in mezzo a migliaia di altri libri.&lt;/p&gt;\r\n&lt;p&gt;La citt&amp;agrave; di Monaco di Baviera accolse i nuovi eroi del bene con le sue strade pittoresche e la ricca storia, ma era anche intrisa dei segni oscuri lasciati dal passaggio del regime nazista.&lt;/p&gt;\r\n&lt;p&gt;Con le informazioni a loro disposizione essi iniziarono a seguire le tracce lasciate dal Barone von Fustenberg, il quale, con la Lancia del Destino in suo possesso, aveva fatto ritorno al suo paese natale, dove viveva in uno splendido castello. I nuovi eroi bussarono alle porte del castello, scoprendo che ora era abitato da un rinomato medico di Monaco: il dottor Shubert. Spiegarono la storia della lancia ed il dottore fu subito pronto ad aiutarli, indicando come il Barone von Fustenberg aveva lasciato un diario all&#039;interno della biblioteca, ma che non era mai stato trovato in mezzo a migliaia di altri libri.&lt;/p&gt;', 10, 3, 'Monaco di Baviera ed il castello', 0, 27, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;I nuovi eroi devono ricercare il diario del barone all&#039;interno della biblioteca. Gli eroi suddividono la biblioteca in 2 sezioni nelle quali ricercare.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ottenere la funzione apposita di Pythin per ottenere un valore casuale, salvandolo su variabile&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Per utilizzare la funzione random, che permette di ottenere dei valori casuali, in Python, si deve prima importare la libreria random, quindi si pu&amp;ograve; estrarre il numero casuale nel seguente modo:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;import random&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;valore_casuale = random.randint(100,200)&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;All&#039;interno delle tonde i valori minimi e massimi per il numero casuale. Il valore casuale deve essere compreso tra 1 e 100. Chiedere quindi un valore all&#039;utente tra 1 e 100. Se il valore dato dall&#039;utente &amp;egrave; superiore al numero casuale dire che il diario si trova nell&#039;ala EST, altrimenti dire che si trova nell&#039;ala OVEST.&lt;/p&gt;', 1),
(21, 'd14fc90b-77ca-4eb0-80bb-e1093fd62f0f', '&lt;p&gt;Fortunatamente sulla sponda del lago gli eroi trovano una imbarcazione. Vi salgono e cominciano a remare.&lt;/p&gt;\r\n&lt;p&gt;Chiedere inizialmente in input due numeri all&#039;utente. Creare quindi un ciclo while che continui a chiedere numeri fino a che il numero inserito non diventi minore del precedente, creando una serie crescente. Ogni numero rappresenta le remate eseguite dagli eroi in vista dell&#039;isola. Dare in output quanti numeri in ordine crescente sono stati inseriti. Esempio: leggo a=5, b=8: entro nel ciclo perch&amp;egrave; b&amp;gt;a. Leggo 11, vado avanti, perch&amp;egrave; 11&amp;gt;8. Leggo 15 vado avanti perch&amp;egrave; 15&amp;gt;11. Leggo 7, mi fermo perch&amp;egrave; &amp;egrave; pi&amp;ugrave; piccolo di 15. Ho letto i 2 numeri iniziali + altri 2 numeri in ordine crescente. Totale 4 numeri.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a74ff109f2.06971386_exercise_editor_6a33e172747f46.39255766.jpg&quot; alt=&quot;&quot;&gt;Dopo una lunga ricerca gli eroi riuscirono a trovare il diario del Barone. Sfogliandolo riuscirono a scoprire che al di sotto del castello era situato un lungo passaggio segreto e che alla fine del passaggio era stata celata la Lancia del Destino, nascosta in maniera che nessuno potesse trovarla facilmente. Gli eroi si incamminarono attraverso il tunnel: Jambalaya portava l&#039;Arco d&#039;Avorio, i compagni gli si stringevano attorno. Il tunnel era buio, umido e silenzioso, con solo il sussurro fievole dei passi degli eroi che echeggiavano contro le pareti di pietra. Infine giunsero ad un&amp;rsquo;ampia caverna, all&#039;interno della quale era situato un esteso e cinereo lago sotterraneo. Al centro del lago era situata un&#039;isola, un debole chiarore proveniva dal suo interno: ecco l&#039;arma leggendaria, poggiata su un piedistallo simile a quello della Foresta delle Gemme. Gli eroi dovevano ora attraversare il lago.&lt;/p&gt;', 30, 3, 'Il lago sotterraneo', 0, 15, 0, '&lt;p&gt;Fortunatamente sulla sponda del lago gli eroi trovano una imbarcazione. Vi salgono e cominciano a remare.&lt;/p&gt;\r\n&lt;p&gt;Chiedere un numero all&#039;utente. Creare quindi un ciclo while che continui a chiedere numeri all&#039;utente sommandoli al numero iniziale, fino a che questo non diventa maggiore o uguale a 100. Ogni ciclo rappresenta le remate eseguite dagli eroi in vista dell&#039;isola. Dare in output quanti numeri sono stati inseriti. Esempio: leggo n=8 inizialmente, entro nel ciclo perch&amp;egrave; 8&amp;lt;100. Leggo 11, vado avanti, perch&amp;egrave; 11+8&amp;lt;100. Vado avanti fino a che la somma totale non diviene maggiore o uguale a 100. In una seconda variabile devo tener conto di quante volte ho ripetuto il ciclo.&lt;/p&gt;', 1),
(22, 'b1e499a0-c6a7-46b1-960a-c6c5968b6b4d', '', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750010582.11349984_exercise_editor_6a33e2ca86c960.01838555.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Gli eroi raggiunsero la sponda dell&#039;isoletta, erano quindi ormai vicini alla conquista della seconda arma degli antichi eroi. Fortunatamente questa volta non sembrava vi fosse nessuna barriera magica ad impedire il cammino. Non appena fatti pochi passi verso la meta, per&amp;ograve;, l&#039;acqua del lago cominci&amp;ograve; ad incresparsi, fino a creare delle vere e proprie onde: dalle onde scatur&amp;igrave; un enorme serpente marino, che attacc&amp;ograve; i nuovi eroi del bene. Jambalaya imbracci&amp;ograve; l&#039;arco cominciando a colpire il mostro con le frecce che aveva recuperato in un negozio di Monaco, ma queste non sembravano nemmeno scalfirlo. Mentre le onde si infrangevano contro la costa dell&#039;isoletta, gli altri eroi del bene cercavano di tenere a bada il serpente marino con tutti i mezzi a loro disposizione, ma sembrava che la creatura fosse protetta da una forza che superava la loro comprensione.&lt;/p&gt;\r\n&lt;p&gt;La furia del serpente marino si faceva sempre pi&amp;ugrave; intensa, con le sue squame scintillanti che risplendevano alla luce fioca dell&#039;acqua. I suoi tentacoli sinuosi si agitavano furiosamente, cercando di afferrare i nuovi eroi del bene e trascinarli sotto la superficie tumultuosa del lago.&lt;/p&gt;', 10, 2, 'Il serpente marino', 6, 27, 0, '', 1),
(23, '35e68471-3231-4fc2-ab3b-e4f0c4d557c7', '&lt;p&gt;Il serpente marino ha 3 punti vita. Gli eroi del bene possono attaccare per un totale di 6 volte. Per 6 volte estrarre un numero casuale da 1 a 10, quindi chiedere all&#039;utente un numero in input (senza mostrare il numero casuale). Se il numero dato in input dall&#039;utente ha una distanza massima di 2 numeri da quello estratto casualmente, il serpente perde 1 punto vita.&lt;/p&gt;\r\n&lt;p&gt;Al termine del ciclo comunicare se gli eroi sono riusciti a sconfiggere il serpente oppure no.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750064017.48610999_exercise_editor_6a33e32856b087.59172171.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya, concentrato e deciso, si concentr&amp;ograve; su un nuovo approccio. Con l&#039;acuto scintillio dell&#039;Arco d&#039;Avorio, prese di mira le parti pi&amp;ugrave; vulnerabili del serpente marino, cercando di individuare eventuali punti deboli. Le frecce, scoccate con destrezza e forza, colpirono i punti vitali, ma ancora una volta sembr&amp;ograve; che il serpente fosse immune agli attacchi.&lt;/p&gt;\r\n&lt;p&gt;L&#039;acqua tumultuosa rendeva difficile il movimento e la coordinazione, costringendo i nuovi eroi del bene a lottare con tutte le loro forze per sopravvivere. Era una battaglia disperata, con il destino degli eroi che pendeva sull&#039;esito di questo scontro epico.&lt;/p&gt;\r\n&lt;p&gt;Mentre il serpente marino continuava a ingaggiare gli eroi in una lotta feroce, Jambalaya si rese conto che per sconfiggere questa creatura mistica, avrebbero avuto bisogno di qualcosa di pi&amp;ugrave; di forza fisica e abilit&amp;agrave; di combattimento. Dovevano trovare un modo per penetrare la difesa magica del serpente e sfruttare le sue debolezze nascoste per ottenere la vittoria.&lt;/p&gt;', 30, 3, 'Il combattimento con il serpente', 0, 15, 0, '&lt;p&gt;Il serpente marino ha 3 punti vita. Gli eroi del bene possono attaccare per un totale di 6 volte. Per 6 volte estrarre un numero casuale da 1 a 10. Se il numero casuale &amp;egrave; superiore a 3, il serpente perde 1 punto vita.&lt;/p&gt;\r\n&lt;p&gt;Al termine del ciclo comunicare se gli eroi sono riusciti a sconfiggere il serpente oppure no.&lt;/p&gt;', 0),
(24, '6733803b-b199-4db1-8c81-ee61634f3afa', '&lt;p class=&quot;MsoNormal&quot;&gt;Scrivere il programma che risolva il seguente problema: Aisha ha 100 punti energia, Isabela ha 40 punti energia. L&#039;energia di Aisha sale dell&#039;8% ogni minuto, l&#039;energia di Isabela sale del 10% al minuto. Dopo quanti minuti, Isabela avr&amp;agrave; pi&amp;ugrave; energia di Aisha?&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7500afe75.18085841_exercise_editor_6a33e3aac0fdd6.09484613.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Una delle ragazze del gruppo dei nuovi eroi, Isabela, una diciassettenne proveniente dal Brasile, con una lunga treccia di capelli marroni ed occhi verdi, si rese conto che la lotta contro il custode era impari: ma la seconda arma degli antichi eroi era a poca distanza. Con uno scatto repentino, cominci&amp;ograve; a correre verso il piedistallo dove risiedeva la Lancia del Destino, decisa ad impossessarsene, per poter aumentare la loro forza d&#039;attacco contro il mostro marino.&lt;/p&gt;\r\n&lt;p&gt;Isabela si lanci&amp;ograve; verso il piedistallo, mentre i suoi compagni tenevano a bada il custode della grotta. Arrivata a pochi passi dalla Lancia del Destino allung&amp;ograve; la mano per prendere la sacra reliquia, ma qualcosa la colp&amp;igrave; violentemente, impedendole di prendere l&#039;arma. Voltandosi verso destra Isabela si trov&amp;ograve; di fronte una seconda ragazza: era Aisha, una degli eroi malvagi! Anche loro erano a caccia delle armi degli antichi eroi del bene e Aisha, dalla quale i nuovi eroi erano stati messi in guardia da Aionios, era anch&#039;essa arrivata al luogo dov&#039;era nascosta la Lancia. Gli eventi stavano prendendo una piega sempre peggiore.&lt;/p&gt;', 30, 3, 'Isabela ed Aisha', 0, 15, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Scrivere il programma che risolva il seguente problema: Aisha ha 100 punti energia, Isabela ha 40 punti energia. L&#039;energia di Aisha sale di 8 punti al minuto, l&#039;energia di Isabela sale di 10 punti al minuto. Dopo quanti minuti, Isabela avr&amp;agrave; pi&amp;ugrave; energia di Aisha? Usa un ciclo while contando quante volte entri nel ciclo, il ciclo continua fino a che l&#039;energia di Aisha &amp;egrave; superiore a quella di Isabela&lt;/p&gt;', 1),
(25, 'aebf8499-a818-4b56-a726-a75eb554ff3e', '&lt;p&gt;Definisci una lista con 4 numeri e dai in output un istogramma basato su questi numeri, usando dei punti di domanda per disegnarlo.&lt;/p&gt;\r\n&lt;p&gt;Ogni numero indica le probabilit&amp;agrave; di successo per 4 differenti strategie di attacco da parte di Isabela.&lt;/p&gt;\r\n&lt;p&gt;Usa il ciclo FOR per scorrere la lista e la moltiplicazione di stringhe per disegnare gli asterischi. &quot;?&quot;*4 = &quot;????&quot;&lt;/p&gt;\r\n&lt;p&gt;Data, ad esempio, la lista [3, 7, 9, 5], il programma dovr&amp;agrave; produrre questa sequenza:&lt;/p&gt;\r\n&lt;p&gt;???&lt;/p&gt;\r\n&lt;p&gt;???????&lt;/p&gt;\r\n&lt;p&gt;?????????&lt;/p&gt;\r\n&lt;p&gt;?????&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750142fb8.58309378_exercise_editor_6a33e44b63ecc5.37542048.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Aisha aveva origini Africane ed era nota come la Pantera Nera. Il soprannome non le era stato dato a caso, infatti il suo potere era quello di avere una straordinaria agilit&amp;agrave;: era veloce (anche se non quanto Shinzo), poteva effettuare salti lunghi 20 metri ed alti 6 ed era notevolmente forte. Isabela non aveva nessuna di queste abilit&amp;agrave;!&lt;/p&gt;\r\n&lt;p&gt;Con Aisha, la temibile Pantera Nera, di fronte a lei, Isabela si trov&amp;ograve; di fronte a una situazione estremamente pericolosa. Le abilit&amp;agrave; sovrumane di Aisha la rendevano un avversario formidabile, e Isabela sapeva che avrebbe dovuto agire con intelligenza e prontezza se voleva avere la bench&amp;eacute; minima possibilit&amp;agrave; di sopravvivere a questo scontro.&lt;/p&gt;\r\n&lt;p&gt;Mentre Aisha si preparava a scattare verso di lei, Isabela si richiam&amp;ograve; alla mente i principi fondamentali dell&#039;addestramento che aveva ricevuto dagli altri eroi del bene. Non aveva le abilit&amp;agrave; fisiche straordinarie di Aisha, ma sapeva di avere la propria astuzia e la capacit&amp;agrave; di trovare punti deboli anche nei nemici pi&amp;ugrave; temibili.&lt;/p&gt;', 30, 3, 'I poteri di Aisha', 0, 15, 0, '', 1),
(26, '88a51cbc-52ec-4b86-a697-973497c94653', '&lt;p&gt;Isabela si abbassa di colpo davanti alla stalagmite, Aisha non riesce a contenere il suo balzo e si scontra contro la formazione rocciosa.&lt;/p&gt;\r\n&lt;p&gt;Crea un array (cio&amp;egrave; una lista di elementi dello stesso tipo) con 5 numeri interi estratti casualmente tra 0 e 100. Il programma deve calcolare con un ciclo FOR la moltiplicazione di tutti gli elementi presenti nell&#039;array e stamparli a video. I numeri rappresentano la forza con la quale Aisha colpisce la stalagmite: se il loro prodotto supera 10000 allora questa si spezza e la lastra sopra di essa cade su Aisha, altrimenti la Pantera Nera rimbalza sulla formazione rocciosa e si salva. Dire in output. quale delle due opzioni accade&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7501bddb8.71411741_exercise_editor_6a33e4d79e4599.10754033.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Con il cuore che batteva furiosamente nel petto, Isabela cerc&amp;ograve; di rallentare il ritmo della battaglia, cercando di sfruttare l&#039;ambiente circostante a suo vantaggio. Si spost&amp;ograve; con agilit&amp;agrave; e flessibilit&amp;agrave;, cercando di sfruttare la sua esperienza e il suo coraggio per trovare una via d&#039;uscita da questa situazione pericolosa.&lt;/p&gt;\r\n&lt;p&gt;Mentre Aisha si lanciava verso di lei con una grinta impressionante, Isabela prese una decisione istintiva e audace. Si rese conto che doveva mettere da parte la paura e concentrarsi sulla strategia. Con un movimento repentino, si tuff&amp;ograve; di lato, cercando di allontanarsi dalla traiettoria di Aisha e di guadagnare tempo per ideare un piano che potesse dare loro una possibilit&amp;agrave; di sopravvivere.&lt;/p&gt;\r\n&lt;p&gt;L&#039;aria vibrava con l&#039;energia e la tensione di questo scontro epico tra due forze contrapposte, e Isabela si prepar&amp;ograve; a dimostrare che, nonostante la mancanza di abilit&amp;agrave; sovrumane, era comunque una combattente formidabile, determinata a difendere la causa del bene e a proteggere il mondo dal pericolo che minacciava di sopraffarlo.&lt;/p&gt;\r\n&lt;p&gt;Con la determinazione impressa nei suoi movimenti, Isabela riusc&amp;igrave; a schivare il primo attacco di Aisha, la Pantera Nera, ma non fu cos&amp;igrave; fortunata con il secondo, che la scaravent&amp;ograve; a terra con una forza senza eguali, lasciandola senza respiro. &quot;Credevate davvero di poterci sconfiggere?&quot;, chiese sprezzante Aisha. Isabela raccolse tutto il suo coraggio e, fingendo di non avere pi&amp;ugrave; forze per combattere, utilizz&amp;ograve; le tecniche di Aionios per ottenere la maggiore concentrazione possibile, scandagliando l&#039;ambiente circostante. &quot;Un colpo e siete gi&amp;agrave; sconfitti, guardati, non hai nemmeno pi&amp;ugrave; l&#039;energia per muoverti, ormai sei finita!&quot; continu&amp;ograve; a canzonarla la Pantera Nera.&lt;/p&gt;\r\n&lt;p&gt;Finalmente Isabela cap&amp;igrave; ci&amp;ograve; che doveva fare: una stalagmite reggeva il peso di una lastra di pietra crollatavi sopra tempo primo. Si alz&amp;ograve; all&#039;improvviso, gettandosi veloce verso la stalagmite. Aisha fu colta di sorpresa, e reag&amp;igrave; compiendo un balzo prodigioso verso Isabela.&lt;/p&gt;', 30, 3, 'La battaglia tra Isabela ed Aisha', 0, 15, 0, '&lt;p&gt;Isabela si abbassa di colpo davanti alla stalagmite, Aisha non riesce a contenere il suo balzo e si scontra contro la formazione rocciosa.&lt;/p&gt;\r\n&lt;p&gt;Crea un array (cio&amp;egrave; una lista di elementi dello stesso tipo) con 5 numeri interi decisi da te. Il programma deve calcolare con un ciclo FOR la moltiplicazione di tutti gli elementi presenti nell&#039;array e stamparli a video.&amp;nbsp;&lt;/p&gt;', 1),
(27, '11f1ee31-ed1b-4c11-8fe8-c4551626c958', '&lt;p&gt;Dopo aver memorizzato in una lista dei numeri rappresentanti i danni inflitti dai 6 eroi del bene al mostro marino (con un random), inserisci in una seconda lista i migliori 3 danni, cio&amp;egrave; i 3 numeri pi&amp;ugrave; elevati della prima lista. Grazie a questi danni e al potere delle armi degli antichi eroi del bene, i nuovi eroi riescono a sconfiggere il mostro marino.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75020bf02.24251434_exercise_editor_6a33e55e5c1283.27045777.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Isabela si scans&amp;ograve; all&#039;ultimo secondo e Aisha non riusc&amp;igrave; a frenare il suo impeto, colpendo in pieno la stalagmite. La forza della Pantera Nera sgretol&amp;ograve; la formazione rocciosa e la lastra di pietra cadde rovinosamente addosso ad Aisha, seppellendola. Isabela era riuscita a sconfiggerla. L&#039;eroina del bene raccolse quindi la Lancia del Destino. Uno strano formicolio attravers&amp;ograve; tutto il suo corpo quando brand&amp;igrave; l&#039;arma leggendaria, il potere della Lancia irradiava ora da Isabela, che corse in soccorso dei suoi amici, ancora alle prese con il mostruoso serpente marino.&lt;/p&gt;', 29, 3, 'La conquista della Lancia', 0, 10, 0, '&lt;p&gt;Dopo aver memorizzato in una lista dei numeri rappresentanti i danni inflitti dai 6 eroi del bene al mostro marino (con un random), calcola ed indica in output qual &amp;egrave; il pi&amp;ugrave; grande numero contenuto nella lista.&lt;/p&gt;', 1),
(28, '7bf8ee1e-3376-418c-b9cd-9831ff0c4ea5', '&lt;p&gt;Per passare il tempo mentre le slitte vengono preparate, gli eroi guardano una puntata di Big Bang Theory:&lt;/p&gt;\r\n&lt;p&gt;Il professor Incredulo non si pu&amp;ograve; capacitare della scoperta fatta dal collega Sheldon Cooper. In corridoio, Cooper gli ha confidato la sua scoperta: &quot;Il quadrato di ogni numero naturale n &amp;egrave; uguale alla somma dei primi n numeri dispari&quot;. Realizzare un programma che aiuti il professor Incredulo a confermare o smentire la tesi di Sheldon. Chi avr&amp;agrave; ragione?&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7502614f8.19062433_exercise_editor_6a33e625bcb3b6.78685039.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;La nave &lt;strong&gt;Endurance&lt;/strong&gt;, costruita in Norvegia nel 1912, era stata progettata per resistere alle condizioni artiche estreme. Nel 1914 Sir Ernest Shackleton decise di intraprendere una delle pi&amp;ugrave; audaci imprese a memoria d&#039;uomo: attraversare l&#039;Antartide da costa a costa, attraverso il Polo Sud. Il veliero a motore scelto per portare a termine il viaggio fu l&#039;Endurance. La nave part&amp;igrave; con il suo equipaggio ai comandi di Shackleton, arrivando ben presto nei mari del Circolo Polare Antartico. Una volta avvicinatasi alla terra da attraversare, per&amp;ograve;, i ghiacci la circondarono, intrappolandola. Dopo 2 lunghi inverni trascorsi intrappolati tra i ghiacci, il comandante decise di scendere sul pack e di tentare di prendere il mare con le scialuppe di salvataggio, dopo averle trascinate fino al primo tratto di mare. L&#039;Endurance venne infine stritolata tra i ghiacci, affondando nel mare artico. I marinai riuscirono a mettere in acqua le scialuppe, ma dovevano affrontare un viaggio di 6000 miglia per arrivare alla prima isola abitata, l&#039;isola della Georgia del Sud, attraverso uno dei mari pi&amp;ugrave; burrascosi del pianeta, con onde sopra i 12 metri e temperature oltre i venti sotto zero: la scialuppa era lunga 6 metri in tutto. La leggenda narrata nella mappa di Aionios raccontava che per non morire di freddo i marinai avevano con loro una spada, capace di sprigionare un intenso calore: una delle armi degli antichi eroi, si trovava con tutta probabilit&amp;agrave; nella Georgia del Sud.&lt;/p&gt;\r\n&lt;p&gt;Raggiunta l&amp;rsquo;isola, circondata dai ghiacci, i nuovi eroi acquistarono delle slitte trainate da mute di cani, per poter viaggiare nell&amp;rsquo;entroterra.&lt;/p&gt;', 30, 3, 'Endurance', 0, 15, 0, '&lt;p&gt;Per passare il tempo mentre le slitte vengono preparate, gli eroi si scervellano su un quesito trovato in un giornale di enigmistica:&lt;/p&gt;\r\n&lt;p&gt;Se sommi i primi 15 numeri dispari ottieni il quadrato di 15. Questa affermazione &amp;egrave; vera o no? Usa un ciclo per sommare i primi 15 numeri dispari: 1,3,5,7,9,11,13,15,17,19.... e d&amp;igrave; in output se effettivamente il risultato &amp;egrave; uguale al quadrato di 15.&lt;/p&gt;', 2),
(29, '7b476f9c-c21d-41ab-a4e4-1db1681b0275', '&lt;p&gt;Crea una lista di numeri interi con 10 elementi casuali tra -5 e -60 rappresentanti le temperature glaciali affrontate dagli eroi (usando un ciclo FOR ed il metodo append). Calcola la media degli elementi della lista. Conta le componenti della lista che hanno un valore superiore alla media, usando un ciclo FOR&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7502b6440.11159517_exercise_editor_6a33e72793d987.42274597.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;I nuovi eroi partirono con le loro slitte. Dopo un&#039;ora di viaggio il vento cominci&amp;ograve; ad intensificarsi: una tempesta era ormai vicina. Gli eroi decisero comunque di continuare il viaggio, la loro missione era troppo importante.&lt;/p&gt;\r\n&lt;p&gt;Con il vento che aumentava d&#039;intensit&amp;agrave; e la tempesta che incombeva minacciosa, i nuovi eroi si trovarono a dover affrontare un percorso sempre pi&amp;ugrave; difficile e pericoloso. Nonostante le avversit&amp;agrave; che si accumulavano intorno a loro, la determinazione a portare a termine la loro missione non vacillava. Avrebbero affrontato qualsiasi sfida pur di raggiungere il loro obiettivo e proteggere il mondo dalle forze oscure che minacciavano di sopraffarlo.&lt;/p&gt;\r\n&lt;p&gt;Con i loro mantelli sventolanti selvaggiamente al vento e le slitte che si spingevano con determinazione attraverso la tormenta imminente, i nuovi eroi si prepararono mentalmente per l&#039;arduo viaggio che li attendeva. La loro unit&amp;agrave; e la loro determinazione a superare ogni difficolt&amp;agrave; li avrebbero guidati attraverso questa tempesta, come avevano fatto tante volte in passato.&lt;/p&gt;\r\n&lt;p&gt;Con l&#039;orizzonte che si oscurava sempre di pi&amp;ugrave; e il vento che ululava intorno a loro, i nuovi eroi del bene avrebbero dovuto unire le loro forze e la loro astuzia per superare le avversit&amp;agrave; che stavano per affrontare. La tempesta non avrebbe potuto spegnere la fiamma della loro volont&amp;agrave;, e con il coraggio e la perseveranza che li caratterizzavano, avrebbero superato anche questo ostacolo sul loro cammino verso la vittoria.&lt;/p&gt;', 29, 3, 'La tempesta di ghiaccio', 0, 10, 0, '&lt;p&gt;Crea una lista di numeri interi con 10 elementi casuali tra 5 e 60 rappresentanti le temperature glaciali affrontate dagli eroi (usando un ciclo FOR ed il metodo append). Conta le componenti della lista che hanno un valore superiore al 31, usando un ciclo FOR, e dai in output il numero conteggiato&lt;/p&gt;', 1),
(30, '551746ef-d572-4434-b54b-fb18b658c619', '&lt;p&gt;Crea una lista vuota. La lista contiene i cani di Klaus.&lt;/p&gt;\r\n&lt;p&gt;Crea un menu con vari print() per far scegliere all&#039;utente cosa vuole fare, inserendolo in un ciclo while:&lt;/p&gt;\r\n&lt;p&gt;1 - Inserire un nuovo cane nella lista, con il suo nome&lt;/p&gt;\r\n&lt;p&gt;2 - Rimuovere l&#039;ultimo elemento della lista, per indicare un cane sfinito&lt;/p&gt;\r\n&lt;p&gt;3 - Uscire dal programma&lt;/p&gt;\r\n&lt;p&gt;Quindi leggi la scelta dell&#039;utente da input. Se l&#039;utente sceglie 3 si esce dal ciclo. Se l&#039;utente sceglie 1 il programma chiede in input il nome del cane da inserire nella lista. La parola inserita dall&#039;utente viene inserita nella lista e viene fatto un print della lista, poi viene visualizzato nuovamente il menu. Se l&#039;utente sceglie 2 viene rimosso l&#039;ultimo elemento della lista e viene stampata a video la lista, quindi l&#039;utente viene riportato al menu. Se l&#039;utente sceglie un numero diverso da 1,2 o 3 viene visualizzato un messaggio di errore, poi viene ristampato il menu.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75031b5c4.31859132_exercise_editor_6a33e7af115a01.66868787.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;La tempesta imperversava attorno agli eroi. Klaus, un ragazzo tedesco del gruppo con corti capelli biondi, cominci&amp;ograve; a rimanere indietro, fino a che si accorse di non riuscire pi&amp;ugrave; a scorgere i compagni. Il terrore si impadron&amp;igrave; del ragazzo, che cominci&amp;ograve; a chiamare ad alta voce i compagni, ma nessuna risposta gli arrivava dal gruppo principale. I cani che trainavano la slitta erano stremati. Klaus doveva trovare al pi&amp;ugrave; presto un riparo o sarebbe morto assiderato.&lt;/p&gt;\r\n&lt;p&gt;Mentre continuava a chiamare i suoi compagni e a cercare di localizzarli nell&#039;oscurit&amp;agrave; della tormenta, Klaus si sforz&amp;ograve; di mantenere la calma e di concentrarsi su come affrontare la situazione con lucidit&amp;agrave; e prontezza. La sopravvivenza non era solo una questione di coraggio, ma richiedeva anche astuzia e capacit&amp;agrave; di adattamento per reagire in modo tempestivo e appropriato alle circostanze mutevoli.&lt;/p&gt;\r\n&lt;p&gt;Con i fiocchi di neve che gli picchiavano in faccia e il vento che gli sibilava nelle orecchie, Klaus si spinse avanti tenacemente, cercando di individuare qualsiasi forma di riparo o rifugio che potesse offrire un minimo di protezione contro la furia della tempesta. Con i cani che fiutavano l&#039;ansia e il timore nel suo comportamento, sapeva di dover trovare una soluzione rapida e efficace per proteggere s&amp;eacute; stesso e i suoi fedeli compagni.&lt;/p&gt;', 29, 3, 'Klaus si perde', 0, 10, 0, '&lt;p&gt;Crea una lista vuota. La lista contiene i cani di Klaus.&lt;/p&gt;\r\n&lt;p&gt;Crea un menu con vari print() per far scegliere all&#039;utente cosa vuole fare, inserendolo in un ciclo while:&lt;/p&gt;\r\n&lt;p&gt;1 - Inserire un nuovo cane nella lista, con il suo nome&lt;/p&gt;\r\n&lt;p&gt;2 - Uscire dal programma&lt;/p&gt;\r\n&lt;p&gt;Quindi leggi la scelta dell&#039;utente da input. Se l&#039;utente sceglie 2 si esce dal ciclo. Se l&#039;utente sceglie 1 il programma chiede in input il nome del cane da inserire nella lista. La parola inserita dall&#039;utente viene inserita nella lista e viene fatto un print della lista, poi viene visualizzato nuovamente il menu.&amp;nbsp;&lt;/p&gt;', 2),
(31, 'b4eee5cd-3992-46e7-bd56-894a8ebd1edc', '&lt;p&gt;Dato l&#039;elenco dei 6 eroi del bene originali contenuto in una lista ed un elenco delle rispettive 6 armi contenuto in una seconda lista (eroe e rispettiva arma si devono trovare nella medesima posizione delle due liste), chiedi all&#039;utente il nome di un eroe, trova il suo indice (usa metodo index della lista: https://www.w3schools.com/python/ref_list_index.asp) e comunica all&#039;utente l&#039;arma relativa all&#039;eroe. Se l&#039;eroe chiesto dall&#039;utente non &amp;egrave; nella lista segnala un errore&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750383568.80351865_exercise_editor_6a33e831ba4b16.84798659.jpg&quot; alt=&quot;&quot;&gt;L&#039;istinto di Klaus lo guid&amp;ograve; nell&#039;oscurit&amp;agrave; della tormenta, vir&amp;ograve; a destra, incitando la muta ad un ultimo sforzo. Una parete di roccia ghiacciata si stagli&amp;ograve; davanti all&#039;improvviso, i cani frenarono, Klaus li condusse lungo la parete, in cerca di un affranto all&#039;interno del quale ripararsi. Ed ecco che ad un certo punto la parete si apriva, Klaus diresse la slitta all&#039;interno del canalone. Schivando varie formazioni di ghiaccio, dopo qualche centinaio di metri, l&#039;eroe si accorse che la tempesta attorno a lui si placava. Il lungo sentiero lo port&amp;ograve; infine ad un&#039;area riparata pi&amp;ugrave; ampia, al cui centro si ergeva un piedistallo. Sul piedistallo la spada fiammeggiante, che riscaldava l&#039;area e teneva lontano il freddo della tempesta. Aveva trovato l&#039;antica arma per la quale erano giunti fino a l&amp;igrave;.&lt;/p&gt;', 29, 3, 'La Spada', 0, 10, 0, '&lt;p&gt;Dato l&#039;elenco dei 6 eroi del bene originali contenuto in una lista, chiedi all&#039;utente il nome di un eroe e comunica all&#039;utente se l&#039;eroe &amp;egrave; presente o meno nella lista&lt;/p&gt;', 1),
(32, '0d87a51f-556d-4239-bbf4-6a81ba1c12da', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea 2 insiemi con i colori deli eroi del bene e uno con i colori degli eroi del male (decidi tu almeno tre colori per ciascuno dei 2 insiemi). Elenca i colori comuni ai due insiemi usando l&#039;intersezione, i colori di entrambi, usando l&#039;unione.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7503dd0e0.55807940_exercise_editor_6a33e8b3b58681.97087442.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Klaus scese dalla slitta, avvicinandosi al piedistallo. Rispetto alle altre volte la conquista della Spada Fiammeggiante sembrava cosa semplice. Troppo semplice. Il ragazzo si volse a destra, si volse a sinistra, certo che qualcosa avrebbe interrotto la presa dell&#039;arma. Ed invece sembrava che niente gli impedisse di avvicinarsi: nessun eroe malvagio nei dintorni, n&amp;egrave; mostri a custodia della spada. Prese l&#039;arma in mano, stringendone l&#039;elsa. Immediatamente si sent&amp;igrave; pervadere da un intenso calore, che continuava a salire. Il caldo diveniva via via sempre pi&amp;ugrave; insopportabile, fino a fargli pensare di star bruciando vivo. Klaus perse i sensi.&lt;/p&gt;\r\n&lt;p&gt;Mentre Klaus lottava per rimanere cosciente, si trov&amp;ograve; improvvisamente in un mondo diverso, lontano dalla tempesta e dalla sua squadra di eroi del bene. Si ritrov&amp;ograve; in una landa spoglia e desolata, dove il terreno era un deserto arido e il cielo si tingeva di un rosso intenso. In lontananza, vide una figura oscura che si stagliava contro l&#039;orizzonte, avvicinandosi con passo deciso.&lt;/p&gt;', 29, 3, 'Il Mondo oscuro', 0, 9, 0, '', 0),
(33, '0943cc86-ad81-4aa0-b3a6-146c6f4eff6f', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un dizionario che rappresenti i popoli devastati da Lachlan attraverso il potere oscuro degli incubi, dove la chiave &amp;egrave; il nominativo del popolo ed il valore &amp;egrave; il numero dei componenti di quel popolo. Stampa il numero di persone di un popolo chiesto in input. Stampa l&#039;elenco di tutti i popoli con il numero di persone che vi appartengono usando un ciclo for&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750430132.14893281_exercise_editor_6a33e90134a863.92511372.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Presto cap&amp;igrave; che era uno degli eroi malvagi, una figura sinistra che portava con s&amp;eacute; un&#039;aura di malevolenza e oscurit&amp;agrave;. Mentre la figura si avvicinava, Klaus si rialz&amp;ograve; con la Spada Fiammeggiante ancora saldamente in mano, sentendo il calore ardente della lama che pulsava con una potenza sconosciuta.&lt;/p&gt;\r\n&lt;p&gt;Con la determinazione che bruciava dentro di lui, Klaus si prepar&amp;ograve; ad affrontare il suo avversario, sapendo che il destino del mondo dipendeva dall&#039;esito di questo scontro. La sua mente si concentr&amp;ograve; sulla lama fiammeggiante, cercando di comprendere come potesse essere usata per sconfiggere l&#039;eroe malvagio che si avvicinava minacciosamente.&lt;/p&gt;\r\n&lt;p&gt;Con un grido di sfida, Klaus si lanci&amp;ograve; verso il suo avversario, la Spada Fiammeggiante in mano, tagliando l&#039;aria con una luce accecante e un calore avvolgente. L&#039;eroe malvagio non indietreggi&amp;ograve; di fronte all&#039;attacco, ma si prepar&amp;ograve; a contrattaccare con la sua propria arma, scagliando una tempesta di ombre e oscurit&amp;agrave; contro Klaus.&lt;/p&gt;\r\n&lt;p&gt;La lotta che ne segu&amp;igrave; fu un balletto frenetico di colpi e contraccolpi, l&#039;energia infuocata della Spada Fiammeggiante che si scontrava con l&#039;oscurit&amp;agrave; contorta dell&#039;eroe malvagio. Klaus si trov&amp;ograve; costretto a combattere con tutto s&amp;eacute; stesso, attingendo alla sua determinazione e alla forza del suo spirito per resistere all&#039;oscurit&amp;agrave; che minacciava di sopraffarlo.&lt;/p&gt;\r\n&lt;p&gt;L&#039;eroe malvagio che Klaus affrontava nel mondo dei suoi sogni era conosciuto come Lachlan il Distruttore. Con un passato avvolto nel mistero e nell&#039;oscurit&amp;agrave;, Lachlan era temuto per la sua maestria nelle arti oscure e per il suo potere soverchiante che aveva spazzato via intere terre in un turbine di distruzione e caos.&lt;/p&gt;', 29, 3, 'Lachlan il Distruttore', 0, 8, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un dizionario che rappresenti i popoli devastati da Lachlan attraverso il potere oscuro degli incubi, dove la chiave &amp;egrave; il nominativo del popolo ed il valore &amp;egrave; il numero dei componenti di quel popolo. Chiedi in input il nome di un popolo e stampa il numero ad esso associato, usando il dizionario.&lt;/p&gt;', 1),
(34, '73d8b8b7-03f9-4052-896b-1c7530dafa1a', '&lt;p&gt;Crea l&#039;insieme dei numeri da 1 a 100. Simula un gioco in cui il primo giocatore, Klaus, estrae un numero a caso dall&#039;insieme ed il secondo giocatore, Lachlan, estrae un secondo numero. L&#039;eroe con il numero maggiore vince la battaglia e guadagna 1 punto. Il gioco continua fino a che ci sono numeri nell&#039;insieme. Alla fine del ciclo il programma comunica quale dei due eroi ha vinto la guerra vincendo pi&amp;ugrave; battaglie singole o se c&#039;&amp;egrave; un pareggio.&lt;/p&gt;\r\n&lt;p&gt;Al posto dell&#039;insieme si pu&amp;ograve; usare anche una lista ed usare: import random e random.shuffle(lista) per mescolare gli elementi della lista&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750486801.89757717_exercise_editor_6a33e97fc32299.58139254.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;La sua presenza era accompagnata da un&#039;aura di malevolenza e terrore, e il suo sguardo era freddo e privo di compassione. Aveva attraversato innumerevoli battaglie e aveva dominato i cuori dei suoi nemici con un terrore incommensurabile. Con una forza sovrumana e una determinazione implacabile, Lachlan si era guadagnato il rispetto e il timore di coloro che avevano osato sfidare la sua autorit&amp;agrave;.&lt;/p&gt;\r\n&lt;p&gt;Klaus contro Lachlan, la luce contro l&#039;oscurit&amp;agrave;, il bene contro il male, la Spada Fiammeggiante contro il Potere delle Ombre. Lachlan attacc&amp;ograve; Klaus con il suo terribile potere. Le pi&amp;ugrave; grandi paure di Klaus cominciarono ad invadere la sua mente: alla sua destra una distesa di ragni si avvicinava minacciosa, portata fin l&amp;igrave; dalla sua aracnofobia. Sulla sua sinistra l&#039;immagine del padre, che per tutta la sua vita l&#039;aveva fatto sentire inadeguato e non all&#039;altezza delle sue aspettative. Il padre lo stava riprendendo per non aver ottenuto un voto positivo nell&#039;ultima verifica di storia. Di fronte la sua pi&amp;ugrave; grande paura: l&#039;immagine di s&amp;egrave; stesso, solo e abbandonato da chiunque. Senza amici, senza amore, perduto..&lt;/p&gt;\r\n&lt;p&gt;Tuttavia, Klaus, armato della Spada Fiammeggiante, non si lasci&amp;ograve; sopraffare cos&amp;igrave; facilmente. Con ogni fibra del suo essere, cerc&amp;ograve; di respingere le visioni terrificanti che minacciavano di paralizzarlo. La lama ardente della Spada risplendeva con una luce intensa, contrastando l&#039;oscurit&amp;agrave; che Lachlan cercava di proiettare su di lui.&lt;/p&gt;', 29, 3, 'La Spada Fiammeggiante contro il Potere delle Ombre', 0, 9, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Crea l&#039;insieme dei numeri da 1 a 100, inserendoli con un ciclo for in un insieme vuoto iniziale. Estrai un numero casuale dall&#039;insieme con il metodo pop. Estrai un secondo numero dall&#039;insieme salvandoli entrambi su variabili. Se il primo numero &amp;egrave; maggiore del secondo stampa in output: &quot;vince Lachlan&quot;, altrimenti &quot;vince Klaus&quot;&lt;/p&gt;', 1),
(35, '06162067-6b7a-4b3e-897c-161d153332e5', '&lt;p&gt;Ogni eroe del bene e del male visto finora appartiene ad uno Stato del Mondo. Klaus dalla Germania, Lachlan dall&#039;Australia, Aisha dalla Costa d&#039;Avorio, Isabela dal Portogallo, Shinzo dal Giappone e Jambalaya dalla Jamaica.&lt;/p&gt;\r\n&lt;p&gt;Dichiarare all&#039;interno di una struttura dati (dizionario) i 6 eroi, associandoli ai rispettivi Stati. Creare un menu per permettere all&#039;utente di scegliere una delle seguenti:&lt;/p&gt;\r\n&lt;p&gt;&amp;bull; Aggiungere un nuovo eroe alla struttura dati, con il suo Stato di Provenienza&lt;/p&gt;\r\n&lt;p&gt;&amp;bull; Visualizzare gli eroi in ordine alfabetico&lt;/p&gt;\r\n&lt;p&gt;&amp;bull; Dato uno Stato stampare l&#039;eroe che proviene da quello Stato&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75051bd12.26223355_exercise_editor_6a33ea5101b248.38778990.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Con un grido di sfida, Klaus fece un balzo avanti, usando la Spada per tagliare attraverso le illusioni che lo circondavano. La distesa di ragni si svel&amp;ograve; essere solo un inganno, cos&amp;igrave; come l&#039;immagine del padre deludente. Di fronte alla sua pi&amp;ugrave; grande paura, l&#039;immagine di s&amp;eacute; stesso abbandonato, Klaus decise di affrontare la sua solitudine interiore e di trasformarla in forza. Lui non era solo, aveva 5 compagni che lo stavano cercando in mezzo alla tormenta, che gli volevano bene e mai lo avrebbero abbandonato.&lt;/p&gt;\r\n&lt;p&gt;La Spada Fiammeggiante reagiva alla volont&amp;agrave; di Klaus, aumentando la sua luminosit&amp;agrave; e respingendo le Ombre di Lachlan.&lt;/p&gt;\r\n&lt;p&gt;Lachlan, colpito dalla determinazione di Klaus, tent&amp;ograve; di intensificare il Potere delle Ombre, cercando di creare illusioni ancora pi&amp;ugrave; spaventose. Tuttavia, Klaus, con la fiamma della Spada che danzava in modo sempre pi&amp;ugrave; vibrante, resistette all&#039;attacco e avanz&amp;ograve; con fermezza.&lt;/p&gt;\r\n&lt;p&gt;Con un colpo potente, Klaus scagli&amp;ograve; la Spada contro Lachlan, creando un&#039;esplosione di luce che fece retrocedere l&#039;eroe malvagio. Le Ombre si ritirarono, incapaci di competere con la potenza radiante della Spada Fiammeggiante.&lt;/p&gt;\r\n&lt;p&gt;Lachlan, indebolito e sorpreso dalla fermezza di Klaus, cerc&amp;ograve; di resistere, ma la luce della Spada lo avvolse completamente. La sua figura si dissolse gradualmente nell&#039;oscurit&amp;agrave;.&lt;/p&gt;\r\n&lt;p&gt;Con la sconfitta di Lachlan, il mondo dei sogni si trasform&amp;ograve;. Il deserto arido si trasfigur&amp;ograve; in una landa luminosa, e il cielo rosso si dissolse in sfumature di azzurro. Klaus, ancora sostenuto dalla luce della Spada, si ritrov&amp;ograve; in piedi in mezzo a un paesaggio rinnovato.&lt;/p&gt;\r\n&lt;p&gt;La squadra di eroi del bene, che lo aveva perso di vista durante la tempesta, comparve all&#039;orizzonte. Con la Spada Fiammeggiante ancora in mano, Klaus li accolse con un sorriso di trionfo. La luce aveva trionfato sulle tenebre, e la missione per proteggere il mondo poteva proseguire con rinnovato spirito e determinazione.&lt;/p&gt;', 29, 3, 'La vittoria di Klaus', 0, 8, 0, '&lt;p&gt;Ogni eroe del bene e del male visto finora appartiene ad uno Stato del Mondo. Klaus dalla Germania, Lachlan dall&#039;Australia, Aisha dalla Costa d&#039;Avorio, Isabela dal Portogallo, Shinzo dal Giappone e Jambalaya dalla Jamaica.&lt;/p&gt;\r\n&lt;p&gt;Dichiarare all&#039;interno di una struttura dati (dizionario) i 6 eroi, associandoli ai rispettivi Stati.&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Chiedere in input il nome di uno Stato e, se questo &amp;egrave; presente nel dizionario, stampare l&#039;Eroe che proviene da quello Stato.&lt;/p&gt;', 2);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(36, 'f0de20e8-6c46-49d2-ad37-7a9000c0c661', '&lt;p&gt;I nuovi eroi devono calcolare l&#039;altezza dalla quale siano visibili i disegni di Nazca. Per farlo serve calcolare una potenza passo passo. Scrivi un programma in Python che richieda all&#039;utente di inserire una base e un esponente intero. Il programma dovrebbe quindi calcolare e stampare il risultato della potenza usando un ciclo FOR e moltiplicazioni successive.&lt;/p&gt;\r\n&lt;p&gt;Istruzioni:&lt;/p&gt;\r\n&lt;p&gt;Chiedi all&#039;utente di inserire la base (un numero intero).&lt;/p&gt;\r\n&lt;p&gt;Chiedi all&#039;utente di inserire l&#039;esponente (un numero intero).&lt;/p&gt;\r\n&lt;p&gt;Utilizza un ciclo for per calcolare la potenza della base elevata all&#039;esponente.&lt;/p&gt;\r\n&lt;p&gt;Stampa il risultato con un messaggio esplicativo.&lt;/p&gt;\r\n&lt;p&gt;Gestisci il caso in cui l&#039;esponente &amp;egrave; zero (la potenza di qualsiasi numero elevato a zero &amp;egrave; 1).&lt;/p&gt;\r\n&lt;p&gt;Ricorda di commentare il tuo codice per spiegare le sezioni principali e rendere il tuo lavoro comprensibile.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75056e4c1.37959587_exercise_editor_6a33eb2fac49d7.82103662.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;416 D.C. Per&amp;ugrave;. La civilt&amp;agrave; Nazca era al suo massimo splendore. I disegni della scimmia, della tarantola e del colibr&amp;igrave; erano ormai stati completati. Il visitatore dei cieli stava per avere anche lui la sua rappresentazione, che era a buon punto. Visibili solo dall&#039;alto grazie al velivolo del visitatore, i disegni rappresentavano la massima espressione della civilt&amp;agrave; peruviana. Da sempre dedito all&#039;agricoltura, il popolo Nazca stava attraversando quell&#039;anno un periodo decisamente difficile: una estrema siccit&amp;agrave; si era abbattuta nella regione, un cambiamento climatico mai visto prima, che aveva avuto inizio quando il visitatore dei cieli era arrivato attraverso la tempesta, recando con lui l&#039;ovale risplendente. Le colture erano secche e la carestia imminente. Venerato come una divinit&amp;agrave;, il visitatore dei cieli, sebbene sembrasse avere il potere di mutare le stagioni, non si interessava del popolo. Il suo unico scopo era la costruzione di una grande Ziggurat, che potesse arrivare al cielo.&lt;/p&gt;\r\n&lt;p&gt;600 D.C. La civilt&amp;agrave; Nazca era scomparsa a causa dei cambiamenti climatici. Nessuno si ricordava pi&amp;ugrave; del visitatore dei cieli, scomparso a sua volta una volta terminata la grande Ziggurat. Della gloriosa nazione rimanevano solo i disegni. Anche la Ziggurat sembrava svanita nel nulla. Ma la mappa di Aionios indicava che la prossima arma degli Eroi del Bene risiedeva esattamente l&amp;igrave;. I nuovi eroi dovevano ora recarsi in Per&amp;ugrave;, nel tentativo di trovare anche lo Scudo Dorato, per aumentare la loro potenza difensiva contro gli eroi malvagi.&lt;/p&gt;', 30, 3, 'I disegni di Nazca', 0, 15, 0, '&lt;p&gt;I nuovi eroi devono calcolare l&#039;altezza dalla quale siano visibili i disegni di Nazca. Per farlo serve calcolare una potenza passo passo. Scrivi un programma in Python che richieda all&#039;utente di inserire una base. Il programma dovrebbe quindi calcolare e stampare il risultato della base elevato alla 5 usando un ciclo FOR e moltiplicazioni successive. Ad esempio 3 elevato alla 5 sar&amp;agrave; 3*3*3*3*3&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Istruzioni:&lt;/p&gt;\r\n&lt;p&gt;Chiedi all&#039;utente di inserire la base (un numero intero).&lt;/p&gt;\r\n&lt;p&gt;Utilizza un ciclo for per calcolare la potenza della base elevata all&#039;esponente.&lt;/p&gt;\r\n&lt;p&gt;Stampa il risultato con un messaggio esplicativo.&lt;/p&gt;\r\n&lt;p&gt;Ricorda di commentare il tuo codice per spiegare le sezioni principali e rendere il tuo lavoro comprensibile.&lt;/p&gt;', 1),
(37, '65531a20-413e-4cd0-8d67-fb4975564918', '&lt;p&gt;Ognuno dei nuovi eroi decise di attraversare uno diverso dei ponti. I ponti si rivelano per&amp;ograve; una trappola. 5 di essi cominciano a crollare, una volta che gli eroi arrivano a met&amp;agrave;.&lt;/p&gt;\r\n&lt;p&gt;Gli eroi devono correre indietro pi&amp;ugrave; veloci dei ponti che stanno crollando, saltando su pietre pari e dispari.&lt;/p&gt;\r\n&lt;p&gt;Scrivi un programma in Python che chieda all&#039;utente di inserire una sequenza di 7 numeri interi. Il programma dovrebbe quindi calcolare e stampare due risultati:&lt;/p&gt;\r\n&lt;p&gt;La somma totale dei numeri pari inseriti.&lt;/p&gt;\r\n&lt;p&gt;La somma dei cubi (elevato alla terza) dei numeri dispari inseriti.&lt;/p&gt;\r\n&lt;p&gt;Istruzioni:&lt;/p&gt;\r\n&lt;p&gt;Utilizza un ciclo for per acquisire in input i 7 numeri interi uno alla volta.&lt;/p&gt;\r\n&lt;p&gt;Per ciascun numero, verifica se &amp;egrave; pari o dispari.&lt;/p&gt;\r\n&lt;p&gt;Calcola la somma totale dei numeri pari e la somma dei cubi dei numeri dispari.&lt;/p&gt;\r\n&lt;p&gt;Stampa entrambi i risultati.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7505c33e5.02096939_exercise_editor_6a33ebeea84dd8.88417891.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;I nuovi eroi del bene, guidati dalla mappa di Aionios, si avventurarono nelle terre del Per&amp;ugrave; alla ricerca dello Scudo Dorato, convinti che fosse nascosto nei resti della scomparsa civilt&amp;agrave; Nazca. Il paesaggio desertico portava con s&amp;eacute; l&#039;eco di un passato glorioso, ora sepolto sotto la sabbia e l&#039;oblio.&lt;/p&gt;\r\n&lt;p&gt;Raggiunsero la regione una mattina di sole radiante, ma la terra arida portava con s&amp;eacute; il ricordo di tempi difficili.&lt;/p&gt;\r\n&lt;p&gt;La mappa di Aionios era chiara: procedendo a nord della tarantola, fino a quando l&#039;ombra dell&#039;astro scende a settentrione indicando la via del lama, scendi come un delfino che s&#039;immerge, l&amp;igrave; si trover&amp;agrave; la Ziggurat. I nuovi eroi seguirono le indicazioni: ed eccola l&amp;igrave;, l&#039;imponente Ziggurat, davanti ai loro occhi. L&#039;edificio era circondato da una larga spaccatura nel terreno, un burrone di cui non si vedeva il fondo. 6 ponti lo attraversavano per portare i visitatori alla piramide. L&#039;aria intorno vibrava di un&#039;energia antica.&lt;/p&gt;\r\n&lt;p&gt;Con cautela, gli eroi iniziarono ad attraversare i ponti che sovrastavano il burrone, consapevoli che il loro destino era legato a questo luogo sacro. Ogni passo che facevano su quei ponti di pietra antica portava con s&amp;eacute; l&#039;eco di un passato remoto, di una civilt&amp;agrave; che aveva cercato di toccare il cielo e di placare gli dei.&lt;/p&gt;', 30, 3, 'La Ziggurat', 0, 15, 0, '&lt;p&gt;Ognuno dei nuovi eroi decise di attraversare uno diverso dei ponti. I ponti si rivelano per&amp;ograve; una trappola. 5 di essi cominciano a crollare, una volta che gli eroi arrivano a met&amp;agrave;.&lt;/p&gt;\r\n&lt;p&gt;Gli eroi devono correre indietro pi&amp;ugrave; veloci dei ponti che stanno crollando, saltando su pietre pari e dispari.&lt;/p&gt;\r\n&lt;p&gt;Scrivi un programma in Python che chieda all&#039;utente di inserire una sequenza di 7 numeri interi. Il programma dovrebbe quindi calcolare e stampare:&lt;/p&gt;\r\n&lt;p&gt;La somma dei cubi (elevato alla terza) dei numeri dispari inseriti.&lt;/p&gt;\r\n&lt;p&gt;Istruzioni:&lt;/p&gt;\r\n&lt;p&gt;Utilizza un ciclo for per acquisire in input i 7 numeri interi uno alla volta.&lt;/p&gt;\r\n&lt;p&gt;Per ciascun numero, verifica se &amp;egrave; pari o dispari.&lt;/p&gt;\r\n&lt;p&gt;Calcola la somma dei cubi dei numeri dispari.&lt;/p&gt;\r\n&lt;p&gt;Stampa il risultato&lt;/p&gt;', 1),
(38, '7e6069e1-414a-44de-a96c-7f479f12257f', '&lt;p&gt;La porta ha una serratura formata da un disco contenente vari numeri.&lt;br&gt;Per poter procedere attraverso la porta, i nuovi eroi del bene devono decifrare i simboli che vi stanno attorno ed inserire un numero nel disco della serratura. Sanno che i Nazca veneravano le vocali.&lt;br&gt;Scrivi un programma in Python che chieda all&#039;utente di inserire una frase. Il programma dovrebbe quindi contare e stampare il numero di vocali presenti nella frase, numero che andr&amp;agrave; inserito nel disco. Considera solo le vocali minuscole (a, e, i, o, u).&lt;/p&gt;\r\n&lt;p&gt;Istruzioni:&lt;br&gt;Utilizza un ciclo for per iterare attraverso ogni carattere della frase inserita.&lt;br&gt;Conta il numero totale di vocali minuscole presenti nella frase.&lt;br&gt;Stampa il numero totale di vocali e le vocali stesse.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7506108d4.50036812_exercise_editor_6a33ec82dc7215.14577392.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;I nuovi eroi riuscirono a sfuggire alla trappola dei ponti. Attraversando l&#039;unico ponte rimasto intatto, scelto da Nuliajuk, una ragazza Inuit, i ragazzi si portarono di fronte alla Ziggurat, che cominciarono a scalare. Giunti alla sommit&amp;agrave; della struttura, gli eroi trovarono una piattaforma spaziosa e un ingresso segreto che portava all&#039;interno della maestosa piramide. Una volta oltre la soglia, furono accolti da un&#039;atmosfera carica di mistero, con corridoi intricati e stanze ornate di simboli antichi che narravano la storia della civilt&amp;agrave; Nazca.&lt;/p&gt;\r\n&lt;p&gt;Procederono lungo il corridoio, che si inoltrava nei meandri della Ziggurat, divenendo via via pi&amp;ugrave; silenzioso, fresco ed ombroso. Si ritrovarono infine in un&#039;ampia stanza. Di fronte a loro un muro con al centro una porta, circondata da strani simboli.&lt;/p&gt;', 30, 3, 'Nuliajuk e l\'entrata della Ziggurat', 0, 17, 0, '&lt;p&gt;La porta ha una serratura formata da un disco contenente vari numeri.&lt;br&gt;Per poter procedere attraverso la porta, i nuovi eroi del bene devono decifrare i simboli che vi stanno attorno ed inserire un numero nel disco della serratura. Sanno che i Nazca veneravano le vocali.&lt;br&gt;Scrivi un programma in Python che chieda all&#039;utente di inserire una frase. Il programma dovrebbe quindi contare e stampare il numero di vocali presenti nella frase, numero che andr&amp;agrave; inserito nel disco. Considera solo le vocali minuscole (a, e, i, o, u).&lt;/p&gt;\r\n&lt;p&gt;Istruzioni:&lt;br&gt;Utilizza un ciclo for per iterare attraverso ogni carattere della frase inserita.&lt;br&gt;Conta il numero totale di vocali minuscole presenti nella frase.&lt;br&gt;Stampa il numero totale di vocali&lt;/p&gt;', 1),
(39, '13c1812f-c9db-473b-aa52-4f8b7ac35f41', '&lt;p&gt;I nuovi eroi cercano di raggiungere la stanza dove si trova lo Scudo Dorato, forse potr&amp;agrave; aiutarli ad eliminare il veleno. Ma i loro pensieri sono confusi e la via che percorrono sembra invertirsi.&lt;/p&gt;\r\n&lt;p&gt;Crea una lista di numeri casuali, contenente 10 elementi compresi tra 10 e 50.&lt;/p&gt;\r\n&lt;p&gt;Usa import random per importare il modulo per i numeri casuali e random.randint(min,max) per estrarre il numero da aggiungere alla lista.&lt;/p&gt;\r\n&lt;p&gt;Stampa la lista cos&amp;igrave; ottenuta.&lt;/p&gt;\r\n&lt;p&gt;Usando i metodi pop e append, estrai tutti gli elementi della prima lista ed inseriscili in una seconda lista, creando una lista inversa rispetto a quella iniziale. Stampa la lista inversa.&lt;/p&gt;\r\n&lt;p&gt;Esempio: lista iniziale [1,2,3], lista inversa [3,2,1]&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75067a901.47078196_exercise_editor_6a33edeef31ef8.24731766.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Gli eroi oltrepassarono la porta, trovandosi di fronte un nuovo lungo tunnel. Si incamminarono lungo la via. Dopo quello che sembrava un chilometro di strada, improvvisamente Klaus inciamp&amp;ograve; in una pietra sporgente. La pietra mise in moto un meccanismo che fece scattare un&#039;ulteriore trappola. Dalle pareti cominciarono a volare dardi, che colpirono Jambalaya sotto il ginocchio e Klaus al fianco. Isabela, grazie al potere della lancia riusc&amp;igrave; a schivare le frecce e Nuliajuk, che era in capo al gruppo, riusc&amp;igrave; con un tuffo felino a portarsi al sicuro. Anche gli altri 2 ragazzi furono colpiti. Una volta terminata la raffica, Nuliajuk, che aveva studiato da guaritrice presso il suo popolo, cerc&amp;ograve; di capire quanto gravi fossero le ferite. I dardi non erano troppo lunghi ed erano penetrati poco in profondit&amp;agrave; nei corpi dei propri compagni, ma le loro punte erano avvelenate: uno strato di sostanza verde scuro si trovava nelle cuspidi.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk esamin&amp;ograve; attentamente le ferite causate dai dardi avvelenati. La sostanza verde scuro che ricopriva le punte indicava chiaramente la presenza di un veleno potente. La giovane guaritrice Inuit sapeva che il tempo per agire era cruciale.&lt;/p&gt;\r\n&lt;p&gt;Con una concentrazione intensa, Nuliajuk fece uso delle sue conoscenze in erboristeria e della sua abilit&amp;agrave; di guaritrice. Estrasse alcune erbe curative e inizi&amp;ograve; a preparare un unguento sulla pelle dei feriti. Con gesti sicuri, applic&amp;ograve; la miscela curativa sulle ferite di Jambalaya e Klaus, cercando di neutralizzare il veleno e promuovere la guarigione.&lt;/p&gt;\r\n&lt;p&gt;Tuttavia, la situazione era delicata. Il veleno aveva gi&amp;agrave; iniziato a diffondersi nei loro corpi, e Nuliajuk sapeva che la vera cura richiedeva pi&amp;ugrave; di un semplice rimedio superficiale. Dovevano trovare l&#039;antidoto giusto, e il tempo stava per scadere.&lt;/p&gt;\r\n&lt;p&gt;Gli eroi, bench&amp;eacute; indeboliti, decisero di continuare il loro cammino attraverso il tunnel, consapevoli che ogni passo poteva portarli pi&amp;ugrave; vicino non solo all&#039;antidoto ma anche a nuove sfide mortali. Con la lancia di Isabela in mano e la saggezza di Nuliajuk, si incamminarono nell&#039;oscurit&amp;agrave;, pronti a affrontare qualsiasi ostacolo che il destino avesse loro riservato.&lt;/p&gt;', 29, 3, 'Le trappole della Ziggurat', 0, 10, 0, '', 0),
(40, '78d37ecb-4b76-498d-b5a9-a6d7dd8279b1', '&lt;p class=&quot;MsoNormal&quot;&gt;Lo Scudo Dorato deve essere attivato per poter funzionare. Crea una matrice (lista di liste) 4x4 ed inserisci al suo interno dei numeri casuali tra 1 e 10. Ottieni la somma dei numeri di ogni riga. Le somme in sequenza saranno il codice di attivazione dello scudo. Stampa il codice di attivazione.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7506c9226.90932845_exercise_editor_6a33ee57247846.39328177.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;I nuovi eroi arrivarono infine ad una ampia stanza. La stanza non sembrava provenire dal passato, piuttosto dal futuro. Lampade alogene la illuminavano, le pareti erano rivestite di pannelli di metallo.&lt;/p&gt;\r\n&lt;p&gt;Addossate ad una parete delle componenti che sembravano elettroniche, tavoli e sedie metalliche... Ed al centro della stanza un piedistallo, sul quale era appoggiato lo Scudo Dorato. Gli eroi colpiti dai dardi erano troppo deboli e si accasciarono all&#039;uscita del tunnel. Isabela rimase con loro per verificarne lo stato di salute, mentre Nuliajuk avanz&amp;ograve; per prendere lo scudo. Uno dei pannelli metallici cominci&amp;ograve; a sollevarsi, da esso usc&amp;igrave; una figura ammantata d&#039;ombra: era il visitatore dei cieli!&lt;/p&gt;\r\n&lt;p&gt;Il visitatore dei cieli si stagliava nella stanza, avvolto da un&#039;ombra impenetrabile. La sua figura era alta e slanciata, e gli occhi brillavano di una luce antica. Nuliajuk si ferm&amp;ograve; di fronte al piedistallo, osservando attentamente la figura.&lt;/p&gt;\r\n&lt;p&gt;&quot;Visitatore dei cieli, noi siamo gli eroi del bene, inviati da Aionios per proteggere il mondo dalla minaccia degli eroi malvagi. Abbiamo bisogno dello Scudo Dorato per combattere le forze oscure. Possiamo ottenere il tuo aiuto?&quot;, chiese Nuliajuk con rispetto, cercando di comunicare il loro intento pacifico.&lt;/p&gt;\r\n&lt;p&gt;Il visitatore non rispose. Dall&#039;ombra dietro di esso usc&amp;igrave; una nuova figura. Nuliajuk la riconobbe: era Camila, una delle eroine malvagie..&lt;/p&gt;\r\n&lt;p&gt;Camila, emergendo dall&#039;ombra, avanz&amp;ograve; lentamente nella stanza. La sua presenza oscura contrastava con la luce radiante dello Scudo Dorato, creando un&#039;atmosfera di tensione nell&#039;aria. Nuliajuk afferr&amp;ograve; lo Scudo Dorato, prendendolo dal piedistallo.&lt;/p&gt;', 29, 3, 'Il visitatore dei cieli e Camila', 0, 11, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Lo Scudo Dorato deve essere attivato per poter funzionare. Crea una matrice (lista di liste) 4x4 ed inserisci al suo interno dei numeri casuali tra 1 e 10. Per farlo crea 4 liste vuote, dove inserire i numeri. Poi inserisci in un&#039;ulteriore lista vuota le 4 liste con append. Ottieni la somma totale dei numeri della matrice e stampa la somma ottenuta.&lt;/p&gt;', 2),
(41, 'be758d8a-771b-47e7-a8bd-5b4a246ae2e6', '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Nuliajuk deve usare un secondo potere dello Scudo Dorato: il potere della guarigione, per poter eliminare il veleno dai suoi compagni e farli partecipare alla lotta. Per farlo deve premere nella giusta sequenza una serie di numeri stampati all&#039;interno dello scudo.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Crea una matrice 5x5 con elementi casuali compresi tra 1 e 100.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Stampa la matrice e poi stampa la sua trasposta. La trasposta di una matrice A &amp;egrave; una matrice B dove le righe e le colonne sono scambiate.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;Ad esempio la trasposta di A=&lt;/span&gt;&lt;/p&gt;\r\n&lt;table class=&quot;MsoNormalTable&quot; style=&quot;width: 100.0%; background: white; border-collapse: collapse; mso-yfti-tbllook: 1184; mso-padding-alt: 0cm 0cm 0cm 0cm;&quot; border=&quot;0&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 0; mso-yfti-firstrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;1&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;2&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;3&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 1;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;4&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;5&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;6&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 2; mso-yfti-lastrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;7&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;8&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;9&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;mso-fareast-language: IT;&quot;&gt;&lt;br&gt;E&#039; B=&lt;/span&gt;&lt;/p&gt;\r\n&lt;table class=&quot;MsoNormalTable&quot; style=&quot;width: 100.0%; background: white; border-collapse: collapse; mso-yfti-tbllook: 1184; mso-padding-alt: 0cm 0cm 0cm 0cm;&quot; border=&quot;0&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;\r\n&lt;tbody&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 0; mso-yfti-firstrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;1&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;4&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-left: none; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;7&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 1;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;2&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;5&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; background: #F7F7F7; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;8&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;tr style=&quot;mso-yfti-irow: 2; mso-yfti-lastrow: yes;&quot;&gt;\r\n&lt;td style=&quot;width: 33.34%; border: solid #DDDDDD 1.0pt; border-top: none; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;3&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;6&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;td style=&quot;width: 33.34%; border-top: none; border-left: none; border-bottom: solid #DDDDDD 1.0pt; border-right: solid #DDDDDD 1.0pt; mso-border-top-alt: solid #DDDDDD .75pt; mso-border-left-alt: solid #DDDDDD .75pt; mso-border-alt: solid #DDDDDD .75pt; padding: 3.0pt 3.0pt 3.0pt 3.0pt;&quot; valign=&quot;top&quot; width=&quot;33%&quot;&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;font-family: &#039;inherit&#039;,serif; color: black; mso-color-alt: windowtext; mso-fareast-language: IT;&quot;&gt;9&lt;/span&gt;&lt;/p&gt;\r\n&lt;/td&gt;\r\n&lt;/tr&gt;\r\n&lt;/tbody&gt;\r\n&lt;/table&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750716f02.56788982_exercise_editor_6a33ef0e9ce087.68320779.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;&quot;Avete ottenuto lo Scudo Dorato, ma la luce non pu&amp;ograve; esistere senza ombra&quot;, disse Camila con voce sinistra.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk, con fermezza, affront&amp;ograve; Camila. &quot;Noi siamo qui per proteggere il mondo dalla minaccia degli eroi malvagi. Non permetteremo che l&#039;oscurit&amp;agrave; prenda il sopravvento.&quot;&lt;/p&gt;\r\n&lt;p&gt;Camila sorrise maliziosamente. &quot;Proteggere il mondo? Vedremo quanto bene riuscirete a farlo quando l&#039;ombra si diffonder&amp;agrave; ovunque. Il destino &amp;egrave; gi&amp;agrave; scritto.&quot;&lt;/p&gt;\r\n&lt;p&gt;Camila cerc&amp;ograve; di usare il suo potere contro Nuliajuk: l&#039;eroina malvagia era infatti in grado di controllare le menti, inducendo le persone a fare la sua volont&amp;agrave;. Nuliajuk alz&amp;ograve; lo Scudo Dorato. Il suo potere la protesse contro quello di Camila.&lt;/p&gt;', 29, 3, 'Il potere dello Scudo Dorato', 0, 11, 0, '&lt;p&gt;Nuliajuk deve usare un secondo potere dello Scudo Dorato: il potere della guarigione, per poter eliminare il veleno dai suoi compagni e farli partecipare alla lotta. Per farlo deve premere nella giusta sequenza una serie di numeri stampati all&#039;interno dello scudo.&lt;/p&gt;\r\n&lt;p&gt;Crea una matrice 5x5 con elementi casuali compresi tra 1 e 100.&lt;/p&gt;\r\n&lt;p&gt;Stampa la matrice in modo da andare a capo ad ogni riga.&lt;/p&gt;', 2),
(42, 'd98b9efd-7fd3-4323-9d6f-55bd7dfe0fa4', '&lt;p&gt;I nuovi eroi si devono disporre come nella diagonale principale di una matrice.&lt;/p&gt;\r\n&lt;p&gt;la diagonale principale di una matrice quadrata &amp;egrave; la diagonale che va dall&#039;angolo in alto a sinistra a quello in basso a destra.&lt;/p&gt;\r\n&lt;p&gt;Crea una matrice quadrata 6x6 con elementi casuali, calcola e stampa la somma degli elementi della diagonale principale&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75077e287.05107639_exercise_editor_6a33ef92afb694.26039554.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;L&#039;eroina malvagia si infuri&amp;ograve;: non poteva controllare l&#039;eroina del bene. Ma poteva controllare la mente del visitatore dei cieli. Scagli&amp;ograve; l&#039;anziano contro i nuovi eroi: il visitatore dei cieli estrasse dalla giacca un marchingegno ed un forte vento cominci&amp;ograve; ad espandersi nella stanza.&lt;/p&gt;\r\n&lt;p&gt;Forse era vero quanto affermavano gli antichi Nazca: il loro antico padrone poteva controllare gli eventi atmosferici!&lt;/p&gt;\r\n&lt;p&gt;La stanza era in tumulto mentre il vento si intensificava, sollevando polvere e oggetti leggeri. Il visitatore dei cieli, ora sotto il controllo di Camila, era diventato uno strumento nelle mani delle forze oscure.Il vento ululava nella stanza, ma lo Scudo Dorato brill&amp;ograve; con intensit&amp;agrave;, formando una barriera che proteggeva gli eroi dal peggio del vento.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk utilizz&amp;ograve; il potere dello Scudo Dorato, oltre che per proteggere, per guarire. I suoi compagni sentirono che gli effetti del veleno cominciavano a svanire. Lo Scudo Dorato emise un bagliore pi&amp;ugrave; intenso. L&#039;energia positiva dell&#039;artefatto sembrava contrastare l&#039;influenza oscura di Camila. La stanza era avvolta in una luce e ombra combinate, simbolo della lotta epica tra il bene e il male.&lt;/p&gt;', 29, 3, 'Il visitatore dei cieli all\'attacco', 0, 11, 0, '', 3),
(43, 'b38d12bd-573e-4ab4-867b-4aea5aae88db', '&lt;p&gt;Camila comincia a salmodiare una litania oscura, per cercare di prendere il controllo delle menti dei nuovi eroi.&lt;/p&gt;\r\n&lt;p&gt;Scrivi una stringa con una frase. Trasforma la stringa in una lista contenente le diverse parole suddividendola con split.&lt;/p&gt;\r\n&lt;p&gt;Inverti la lista (si pu&amp;ograve; usare il metodo reverse() -&amp;gt;list.reverse())&lt;/p&gt;\r\n&lt;p&gt;Costruisci una stringa a partire dalla lista invertita e stampa la nuova stringa a parole inverse&lt;/p&gt;', 0, '&lt;p&gt;Jambalaya fu il primo a rialzarsi, poi Klaus e anche gli altri. Gli effetti del veleno erano svaniti e potevano utilizzare gli antichi artefatti in loro possesso per contrastare il potere di Camila e del visitatore dei cieli.&lt;/p&gt;\r\n&lt;p&gt;Klaus avanz&amp;ograve; nel vento stringendo in pugno la Spada Fiammeggiante, Isabela vedeva la sconfitta del visitatore tramite il potere della Lancia del Destino, Jambalaya teneva sotto tiro Camila con l&#039;Arco d&#039;Avorio e Nuliajuk proteggeva i compagni dal vento impetuoso tramite il potere dello Scudo Dorato.&lt;/p&gt;\r\n&lt;p&gt;Klaus utilizz&amp;ograve; il potere della spada, avvolgendo nella luce il visitatore dei cieli. Camila percepiva il suo controllo sul vegliardo affievolirsi. La luce della spada si intensific&amp;ograve; ed il visitatore dei cieli cadde a terra, privo di sensi.&lt;/p&gt;\r\n&lt;p&gt;Allora Camila tent&amp;ograve; nuovamente di controllare la mente dei nuovi eroi, ma il potere dello scudo glielo impediva.&lt;/p&gt;\r\n&lt;p&gt;Camila non aveva la possibilit&amp;agrave; di sconfiggere da sola i nuovi eroi uniti ed in possesso delle antiche armi. Fece l&#039;unica cosa che poteva fare: si arrese. Non aveva via di fuga dalla Ziggurat.&lt;/p&gt;\r\n&lt;p&gt;Questa era una novit&amp;agrave; per gli eroi del bene. Fino a quel momento gli eroi malvagi erano sempre riusciti a fuggire. Decisero cos&amp;igrave; di prendere Camila prigioniera. Nuliajuk utilizz&amp;ograve; il potere dello Scudo per avvolgerla in una bolla di luce, che le impediva di fuggire e di utilizzare i suoi poteri. Avrebbero condotto con loro Camila verso la nuova destinazione: il Martello dell&#039;Eden&lt;/p&gt;', 30, 3, 'Camila prigioniera', 0, 17, 0, '&lt;p&gt;Crea una lista con 5 numeri casuali.Stampa la lista&lt;/p&gt;\r\n&lt;p&gt;Inverti la lista (si pu&amp;ograve; usare il metodo reverse() -&amp;gt;list.reverse())&lt;/p&gt;\r\n&lt;p&gt;Stampa la lista con i numeri inversi&lt;/p&gt;', 1),
(44, '58893e7e-9c0d-4661-85de-161994ab90fc', '&lt;p&gt;Ogni Moai ha una distanza precisa in metri dalla roccia della tartaruga.&lt;br&gt;Scrivi un programma in Python che gestisca la raccolta dei Moai con le distanze dalla roccia. Ogni Moai ha un nome ed una distanza in metri, utilizza una tupla per rappresentarle, dato che i nomi e le distanze non cambiano nel tempo.&lt;br&gt;Il programma dovrebbe svolgere le seguenti operazioni:&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Creare una lista di tuple, dove ogni tupla contiene il nome del Moai e la distanza in metri&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Calcolare e stampare la media delle distanze dei Moai dalla roccia della tartaruga&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Trovare e stampare il nome del Moai pi&amp;ugrave; vicino e del Moai pi&amp;ugrave; lontano dalla roccia&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7507e3d45.59278767_exercise_editor_6a3414fda29e74.79654564.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Anno 1400. Pak presiedeva all&#039;estrazione della pietra vulcanica da Rano Raraku, la cava che il suo popolo sfruttava per ottenere il materiale per scolpire le gigantesche teste degli antenati: i Moai. Prendeva appunti in Rongorongo nella sua tavola d&#039;argilla, indicando quali fossero le prossime fasi da approntare: il lavoro degli scalpellini ed il trasporto del Moai verso la costa dell&#039;isola. Kiribu, un ragazzo che era stato incaricato di visionare il luogo dove trasportare la scultura sulla sponda occidentale, arriv&amp;ograve; correndo, tutto trafelato. Cominci&amp;ograve; a sproloquiare circa un esercito di uomini che veniva dal mare, camminando sulle acque. In testa all&#039;esercito cavalcava una pesce gigantesco un uomo solitario, la testa ornata da una piccola corona di corallo e recante in mano un poderoso martello da guerra. Erano chiaramente fandonie, forse il ragazzo aveva fatto uso di erba del diavolo ed aveva avuto strane allucinazioni&lt;/p&gt;\r\n&lt;p&gt;La prossima tappa indicata dalla mappa di Aionios era chiara: l&#039;Isola di Pasqua (Rapa Nui in lingua locale), a 3700km dalle coste del Cile. Jambalaya, Nuliajuk, Klaus e gli altri eroi del bene presero un volo per l&#039;isola, portando con se la loro nuova compagna di viaggio e prigioniera Camila. Una volta atterrati al porto di Rapa Nui, grazie ad un idrovolante, si diressero verso la costa occidentale dell&#039;isola. Dovevano trovare il Moai del capo Knun, il terzo a partire dalla roccia della tartaruga. Nel retro del Moai avrebbero trovato le indicazioni per il ritrovamento del 5 manufatto: il Martello dell&#039;Eden.&lt;/p&gt;', 29, 3, 'L\'Isola di Pasqua', 0, 9, 0, '&lt;p&gt;Ogni Moai ha una distanza precisa in metri dalla roccia della tartaruga.&lt;br&gt;Scrivi un programma in Python che gestisca la raccolta dei Moai con le distanze dalla roccia. Ogni Moai ha una distanza in metri dalla roccia, usa una lista per le distanze, chiedendole in input all&#039;utente.&amp;nbsp;&lt;br&gt;Il programma dovrebbe svolgere le seguenti operazioni:&lt;/p&gt;\r\n&lt;p&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Chiedere all&#039;utente il numero totale di Moai&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Creare una lista di numeri, rappresentanti la distanza in metri di ogni Moai dalla roccia, chiedendo le distanze all&#039;utente per ogni Moai&lt;br&gt;&amp;bull; &amp;nbsp; &amp;nbsp;Calcolare e stampare la media delle distanze dei Moai dalla roccia della tartaruga&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;', 1),
(45, '2fdce92f-d1e7-49eb-9f87-7edc268c18df', '&lt;p&gt;Durante le lunghe ore in mare i nuovi eroi utilizzano la biblioteca di bordo. Crea un programma per aiutarli a gestire i libri che prendono in prestito usando gli insiemi.&lt;br&gt;Il programma dovrebbe svolgere le seguenti operazioni:&lt;br&gt;Creare due set, uno contenente i libri disponibili in biblioteca e l&#039;altro contenente i libri attualmente in prestito.&lt;br&gt;Consentire all&#039;utente di inserire il nome di un libro per indicare che &amp;egrave; stato preso in prestito, oppure di restituire un libro precedentemente preso in prestito. Chiedere quindi una scelta: prendi in prestito/restituisci. Poi il nome del libro. Togliere il libro da un insieme ed inserirlo nell&#039;altro.&lt;br&gt;Stampa l&#039;elenco dei libri disponibili e l&#039;elenco dei libri in prestito dopo ogni operazione.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a75082f1a5.86621243_exercise_editor_6a341558b137e4.27652761.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Le indicazioni del Moai sembravano assurde, indicava di viaggiare verso ovest per 4.000 miglia. Le indicazioni portavano in pieno oceano Pacifico, distante da qualsiasi isola o arcipelago. I nuovi eroi non si scoraggiarono: poteva forse esistere un&#039;isola non segnata dalle mappe. Si imbarcarono nell&#039;unica nave che partiva dall&#039;Isola di Pasqua ed era diretta verso est e che avrebbe raggiunto il punto indicato dal Moai: un tre alberi, molto simile ad un antico veliero. Il viaggio avrebbe impiegato circa 3 giorni ed i ragazzi si sistemarono comodamente nelle cabine sottocoperta.&lt;/p&gt;\r\n&lt;p&gt;Durante il viaggio, i nuovi eroi discussero sulle leggende e le profezie che avevano incontrato fino a quel momento. Jambalaya raccont&amp;ograve; della sua connessione con l&#039;Arco d&#039;Avorio e di come fosse stato scelto per portare avanti l&#039;eredit&amp;agrave; degli eroi del bene. Isabela condivise la sua esperienza nel cercare di impossessarsi della Lancia del Destino e degli ostacoli che aveva incontrato.&lt;/p&gt;\r\n&lt;p&gt;Klaus e Nuliajuk, con il loro passato legato alla Spada Fiammeggiante e allo Scudo Dorato, rifletterono sui poteri che possedevano e su come potessero utilizzarli al meglio per sconfiggere gli eroi malvagi. Nel frattempo, l&#039;oceano si estendeva tutto intorno a loro, senza alcuna traccia di terra in vista.&lt;/p&gt;', 29, 3, 'Viaggio verso l\'ignoto', 0, 9, 0, '&lt;p&gt;Durante le lunghe ore in mare i nuovi eroi utilizzano la biblioteca di bordo. Crea un programma per aiutarli a gestire i libri che prendono in prestito usando gli insiemi.&lt;br&gt;Il programma dovrebbe svolgere le seguenti operazioni:&lt;br&gt;Creare due set, uno contenente i libri disponibili in biblioteca e l&#039;altro contenente i libri attualmente in prestito.&lt;br&gt;Chiedere all&#039;utente il nome del libro preso in prestito. Estrarre quel nome dal primo insieme ed inserirlo nel secondo insieme, usando i metodi appositi. Stampare i due insiemi finali.&lt;/p&gt;', 2),
(46, '45c7bfef-d49b-4075-974f-842ae0a52ac4', '&lt;p&gt;Scendendo nelle profondit&amp;agrave; marine i nuovi eroi incrociano svariate specie di pesci. Aiutali a categorizzarle.&lt;/p&gt;\r\n&lt;p&gt;Chiedi all&#039;utente 6 specie di pesci con la profondit&amp;agrave; dove sono stati individuati. Crea delle tuple con i 2 dati ed inseriscile in un insieme.&lt;/p&gt;\r\n&lt;p&gt;Stampa l&#039;insieme finale cos&amp;igrave; ottenuto.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7508784f8.39152636_exercise_editor_6a3415a9e17e86.57191586.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;La nave raggiunse il punto indicato dal Moai, ma nessuna terra era in vista, solo acqua, ovunque si guardasse, fino all&#039;orizzonte. I nuovi eroi erano perplessi: possibile che avessero interpretato le indicazioni in maniera errata?&lt;/p&gt;\r\n&lt;p&gt;Chijioke, il penultimo ragazzo del gruppo dei nuovi eroi, proveniente dalla Nigeria, con capelli molto scuri e ricci, alto e magro, ebbe un&#039;idea. Se quello che stavano cercando non era al di sopra del mare, forse stava sotto. Nella stiva della nave aveva trovato degli scafandri da palombaro. Utilizzandoli avrebbero potuto immergersi e continuare la ricerca del Martello dell&#039;Eden nelle profondit&amp;agrave; dell&#039;oceano.&lt;/p&gt;\r\n&lt;p&gt;Purtroppo, gli scafandri erano solamente 3, quindi dovettero decidere chi avrebbe intrapreso l&#039;avventura sottomarina. La scelta ricadde su Nuliajuk, che poteva portare con s&amp;egrave; lo Scudo Dorato, Jambalaya e Chijioke, che aveva avuto l&#039;idea.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk, Chijioke e Jambalaya indossarono gli scafandri da palombaro, pronti per immergersi nelle profondit&amp;agrave; dell&#039;oceano alla ricerca del Martello dell&#039;Eden. Con l&#039;oscurit&amp;agrave; che li circondava, le luci dei loro scafandri illuminarono il mondo sottomarino. L&#039;acqua era cristallina e piena di vita marina, ma non c&#039;era alcun segno del Martello.&lt;/p&gt;', 29, 3, 'Chijioke', 0, 9, 0, '&lt;p&gt;Scendendo nelle profondit&amp;agrave; marine i nuovi eroi incrociano svariate specie di pesci. Aiutali a categorizzarle.&lt;/p&gt;\r\n&lt;p&gt;Crea manualmente delle tuple con nome della specie del pesce e numero di metri di profondit&amp;agrave; dove vive ed inseriscile in un insieme usando il metodo apposito.&lt;/p&gt;\r\n&lt;p&gt;Esempio di tupla: (&#039;Barracuda&#039;,20)&lt;/p&gt;\r\n&lt;p&gt;Stampa l&#039;insieme finale cos&amp;igrave; ottenuto.&lt;/p&gt;', 1),
(47, 'ddec9b79-224c-4ddb-9019-40db883b808a', '&lt;p&gt;Al centro della citt&amp;agrave; sottomarina vi era un&#039;antica accademia, dove gli studenti si iscrivevano per studiare sia materie scientifiche che artistiche.&lt;br&gt;Scrivi un programma in Python che gestisca l&#039;antica iscrizione degli studenti alle 2 tipologie di materie utilizzando gli insiemi. Il programma dovrebbe svolgere le seguenti operazioni:&lt;br&gt;Creare due insiemi, uno contenente gli studenti iscritti al corso di scienze e l&#039;altro agli studenti iscritti al corso di arte.&lt;br&gt;Consentire all&#039;utente di inserire i nomi di alcuni studenti chiedendo per ognuno se vuole iscriverlo a scienze, arte o entrambi, utilizzando un ciclo che termina quando il nome dello studente inserito &amp;egrave; nullo&lt;br&gt;Stampa l&#039;elenco degli studenti iscritti a ciascun corso e l&#039;elenco degli studenti iscritti ad almeno un corso (unione degli insiemi).&lt;br&gt;Stampa l&#039;elenco degli studenti iscritti a entrambi i corsi (intersezione degli insiemi).&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7508c4ca4.32398371_exercise_editor_6a3415f332ef86.19582295.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Man mano che scendevano pi&amp;ugrave; in profondit&amp;agrave; iniziarono a notare una struttura somigliante a una citt&amp;agrave; sottomarina. Era come se avessero scoperto un mondo nascosto sotto la superficie dell&#039;oceano. La citt&amp;agrave; era antica e abbandonata, coperta da alghe e coralli.&lt;/p&gt;\r\n&lt;p&gt;Continuarono l&#039;esplorazione, seguendo le indicazioni del Moai che sembravano indicare questo luogo. Improvvisamente, una creatura marina imponente si avvicin&amp;ograve; a loro. Era un antico guardiano dell&#039;oceano, un&#039;enorme balena azzurra. Avvicinandosi, la creatura parl&amp;ograve; nella mente dei 3 ragazzi, rivelando che sapeva del loro scopo e della ricerca del Martello dell&#039;Eden.&lt;/p&gt;\r\n&lt;p&gt;La creatura, chiamata Thalassia, disse loro che il Martello si trovava al centro della citt&amp;agrave; sottomarina, ma era protetto da antichi incantesimi. Per raggiungerlo, avrebbero dovuto superare le prove degli dei del mare. Thalassia offr&amp;igrave; la sua guida e protezione durante la sfida che li attendeva.&lt;/p&gt;\r\n&lt;p&gt;Thalassia, la creatura marina, guid&amp;ograve; i 3 ragazzi attraverso un intricato labirinto di coralli e anfratti sottomarini. La luce filtrava dall&#039;alto, creando giochi di ombre e riflessi.&lt;/p&gt;\r\n&lt;p&gt;I tre eroi giunsero infine in una piazza, che segnava il centro della citt&amp;agrave; in rovina. Thalassia, con grazia, si avvicin&amp;ograve; e indic&amp;ograve; la direzione.&lt;/p&gt;', 29, 3, 'L\'antica città sottomarina', 0, 9, 0, '&lt;p&gt;Al centro della citt&amp;agrave; sottomarina vi era un&#039;antica accademia, dove gli studenti si iscrivevano per studiare sia materie scientifiche che artistiche.&lt;br&gt;Scrivi un programma in Python che gestisca l&#039;antica iscrizione degli studenti alle 2 tipologie di materie utilizzando gli insiemi. Il programma dovrebbe svolgere le seguenti operazioni:&lt;br&gt;Creare due insiemi, uno contenente gli studenti iscritti al corso di scienze e l&#039;altro agli studenti iscritti al corso di arte.&lt;br&gt;Inserisci negli insiemi 3 nomi di studenti ciascuno.&lt;br&gt;Stampa l&#039;elenco degli studenti iscritti a ciascun corso e l&#039;elenco degli studenti iscritti ad almeno un corso (unione degli insiemi).&lt;br&gt;Stampa l&#039;elenco degli studenti iscritti a entrambi i corsi (intersezione degli insiemi).&lt;/p&gt;', 1);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(48, '4de47701-5a6d-4cc4-9aa9-a6d0e54c20c2', '', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750922d70.98291279_exercise_editor_6a34165f8c0466.33551189.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Un piedistallo si ergeva nella piazza, sopra di esso giaceva il Martello dell&#039;Eden, pulsante di un&#039;energia antica. Tuttavia, qualcosa di sinistro si muoveva nell&#039;ombra. Emerse un&#039;altra figura: un essere marino malvagio, il Custode degli Abissi, desideroso di proteggere il prezioso artefatto.&lt;/p&gt;\r\n&lt;p&gt;La battaglia sottomarina tra Nuliajuk, Jambalaya, Chijioke e il Custode degli Abissi stava per avere inizio.&lt;/p&gt;\r\n&lt;p&gt;La battaglia sottomarina inizi&amp;ograve; con un turbinio di movimenti veloci e colpi potenti. Il Custode degli Abissi, una creatura imponente e spaventosa, si scagli&amp;ograve; contro Nuliajuk e Jambalaya con una ferocia senza pari. Con i suoi artigli affilati e la sua forza sovrumana, cercava di respingere i due eroi e proteggere il Martello dell&#039;Eden.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk, protetta dallo Scudo Dorato, riusc&amp;igrave; a respingere gli attacchi del Custode mentre Jambalaya, con l&#039;Arco d&#039;Avorio, scagliava frecce precise e veloci contro il nemico. Tuttavia, il Custode degli Abissi era un avversario formidabile, capace di resistere ai loro colpi e contrattaccare con ferocia.&lt;/p&gt;\r\n&lt;p&gt;La lotta continu&amp;ograve; per un tempo che sembrava infinito, con i tre combattenti che si muovevano tra le correnti marine e gli anfratti sottomarini. Thalassia, la guida del mare, assisteva dalla distanza, offrendo supporto e indicazioni preziose ai due eroi.&lt;/p&gt;\r\n&lt;p&gt;Fino a quando, finalmente, dopo un&#039;intensa battaglia, Nuliajuk e Jambalaya trovarono un&#039;apertura nell&#039;armatura del Custode degli Abissi. Con un colpo ben piazzato dello Scudo Dorato e una freccia scagliata con precisione dall&#039;Arco d&#039;Avorio, riuscirono a sconfiggere il loro avversario permettendo a Chijioke di ottenere il Martello dell&#039;Eden.&lt;/p&gt;', 29, 4, 'Gli eroi contro il Custode degli Abissi', 5, 8, 0, '', 1),
(49, '34d26b73-c3cb-4a8d-b0db-a215b1ef4286', '&lt;p&gt;Gli eroi, ritornati a bordo della nave, devono capire innanzitutto la loro posizione. Per farlo devono eseguire calcoli con meridiani e paralleli.&lt;/p&gt;\r\n&lt;p&gt;Scrivi una funzione che restituisca un valore booleano True o False controllando se due rette, delle quali si conoscono le equazioni y=m1*x+q1 e y=m2*x+q2, sono perpendicolari. Chiedere a Google, Bing o ChatGPT quando 2 rette sono perpendicolari, se non lo si sa gi&amp;agrave;.&lt;/p&gt;\r\n&lt;p&gt;Usare la funzione in un programma principale (main) richiedendo in input i coefficienti delle rette (m e q).&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;I 3 eroi salutarono Thalassia e si diressero verso la superficie. Riemersero dalle profondit&amp;agrave; marine, a poche centinaia di metri dal veliero che li aveva trasportati fino a quel punto dell&#039;oceano. I loro amici li stavano aspettando, non vedevano l&#039;ora di comunicare loro la riuscita dell&#039;impresa. Cominciarono a nuotare verso l&#039;imbarcazione. Pi&amp;ugrave; si avvicinavano, per&amp;ograve;, e pi&amp;ugrave; avvertivano un senso di pericolo. Qualcosa non tornava: non vedevano nessun movimento sul ponte del veliero.&lt;/p&gt;', 28, 3, 'Il ritorno al veliero', 0, 12, 0, '&lt;p&gt;Gli eroi, ritornati a bordo della nave, devono capire innanzitutto la loro posizione. Per farlo devono eseguire calcoli con meridiani e paralleli.&lt;/p&gt;\r\n&lt;p&gt;Scrivi una funzione che prenda come parametri i valori di coefficiente angolare m e intercetta q di 2 rette e restituisca un valore booleano True o False controllando se le due rette, rappresentate dalle equazioni y=m1*x+q1 e y=m2*x+q2, sono parallele. 2 rette sono parallele se hanno lo stesso coefficiente angolare.&lt;/p&gt;\r\n&lt;p&gt;Usare la funzione in un programma principale (main) richiedendo in input i coefficienti delle due rette (m1 e q1, m2 e q2), stampando il risultato dato dalla funzione.&lt;/p&gt;', 1),
(50, '216e927f-e474-455e-9018-1ea726a26f86', '&lt;p&gt;Budi ha un corpo gommoso, quindi se cade da una certa altezza rimbalza come una palla. Scrivi una funzione Python che conti il numero di rimbalzi che fa Budi se cade da una certa altezza, passata come parametro. Ogni volta che rimbalza Budi arriva al 60% dell&#039;altezza che aveva in precedenza. Si ferma quando l&#039;altezza arriva ad essere pi&amp;ugrave; piccola di 0.01&lt;/p&gt;\r\n&lt;p&gt;Usa la funzione in un programma main() che chiede in input l&#039;altezza e stampa il numero di rimbalzi.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750971793.72318733_exercise_editor_6a3416e919de63.95884525.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Giunti alla scaletta di accesso al ponte, salirono con cautela, pronti ad affrontare qualsiasi minaccia potesse attendere loro. Arrivati in cima, ci&amp;ograve; che videro li lasci&amp;ograve; senza fiato: il ponte era deserto, e tutto intorno regnava il silenzio. Non c&#039;era traccia dei loro amici, n&amp;eacute; di alcuna presenza umana.&lt;/p&gt;\r\n&lt;p&gt;All&#039;improvviso per&amp;ograve;, da sottocoperta, emersero due figure: una era Isabela, l&#039;altra era un ragazzo tarchiato, con gli occhi a mandorla e di carnagione scura, evidentemente sovrappeso. Jamabalaya sapeva chi era: Budi, l&#039;ennesimo eroe dell&#039;oscurit&amp;agrave;. Il suo potere: un corpo di gomma, impossibile da scalfire. Ma perch&amp;egrave; Isabela era con lui? E non era di certo prigioniera, infatti recava in mano la Lancia del Destino, che non le era stata sottratta..&lt;/p&gt;\r\n&lt;p&gt;La presenza di Isabela insieme a Budi, un eroe dell&#039;oscurit&amp;agrave;, gettava ombre di dubbio sulla situazione. Era possibile che Isabela avesse tradito il gruppo dei nuovi eroi del bene? O c&#039;era un&#039;altra spiegazione dietro questa strana alleanza?&lt;/p&gt;', 28, 3, 'L\'eroe malvagio Budi', 0, 12, 0, '&lt;p&gt;Budi ha un corpo gommoso, quindi se cade da una certa altezza rimbalza come una palla. Scrivi una funzione Python che conti il numero di rimbalzi che fa Budi se cade da una certa altezza, passata come parametro alla funzione. Crea un ciclo while dove togliere 10 ad ogni ciclo all&#039;altezza di Budi (ad ogni rimbalzo arriva sempre meno in alto), il ciclo termina quando l&#039;altezza &amp;egrave; minore o uguale a 0. Conta quante volte si entra nel ciclo (questo rappresenta il numero di rimbalzi) e restituisci il valore con return.&lt;/p&gt;\r\n&lt;p&gt;Usa la funzione in un programma main() che chiede in input l&#039;altezza e stampa il numero di rimbalzi effettuati dall&#039;eroe malvagio.&lt;/p&gt;', 1),
(51, '795d90e9-25f8-4d4f-bfad-705fb14cbb2f', '&lt;p&gt;Gli eroi del bene rimangono sbigottiti dal tradimento di Isabela, sbigottimento che si accumula rispetto ad ogni volta che hanno pensato fosse un&#039;amica ed invece era una spia degli eroi del male.&lt;/p&gt;\r\n&lt;p&gt;Scrivi una funzione per calcolare lo sbigottimento cumulato.&lt;/p&gt;\r\n&lt;p&gt;La funzione prende in input una lista di numeri e crea una seconda lista dove l&#039;i-esimo elemento &amp;egrave; la somma dei primi i+1 elementi della lista originale. La funzione restituisce la lista cumulata.&lt;/p&gt;\r\n&lt;p&gt;Esempio:&lt;/p&gt;\r\n&lt;p&gt;Lista originale -&amp;gt; [3, 1, 5, 8, 2]&lt;/p&gt;\r\n&lt;p&gt;Lista cumulata -&amp;gt; [3, 4, 9, 17, 19], dove posizione 0 &amp;egrave; la somma del primo numero della lista originale, posizione 1 &amp;egrave; la somma dei primi 2 elementi della lista originale (3+1), posizione 2 &amp;egrave; la somma dei primi 3 elementi della lista originale (3+1+5) e cos&amp;igrave; via.&lt;/p&gt;\r\n&lt;p&gt;Usa la funzione in un programma main, dove creare la prima lista e stampare la seconda lista ottenuta dalla funzione.&lt;/p&gt;', 0, '&lt;p&gt;&quot;Isabela, cosa sta succedendo? Perch&amp;egrave; ti trovi assieme ad uno degli eroi malvagi? Dove sono gli altri?&quot;, chiese Jambalaya. Budi non diede il tempo alla ragazza di rispondere, passando invece ad attaccare i 3 eroi riemersi dalle profondit&amp;agrave; marine: spicc&amp;ograve; un balzo, cercando di schiacciarli sotto la sua imponente mole. Nuliajuk sollev&amp;ograve; lo Scudo Dorato, parando il colpo.&lt;/p&gt;\r\n&lt;p&gt;A questo punto Chijioke utilizz&amp;ograve; il potere del Martello dell&#039;Eden: era in grado di immobilizzare chiunque, costringendolo a rivelare la verit&amp;agrave;. Il nemico non poteva muoversi, fino a che non avesse risposto completamente alle domande rivolte. Il ragazzo indirizz&amp;ograve; il potere dell&#039;arma mistica verso Budi, chiedendogli cosa stesse accadendo.&lt;/p&gt;\r\n&lt;p&gt;Budi scoppi&amp;ograve; in una risata sprezzante: &quot;Non avete capito nulla! Non potete sconfiggere l&#039;oscurit&amp;agrave;! Sar&amp;agrave; sempre pi&amp;ugrave; forte della luce. Cosa pensate stia accadendo? Non siete incorruttibili! Isabela &amp;egrave; passata al lato oscuro. Come pensavate facessimo a trovarci ogni volta nei luoghi dove erano state nascoste le antiche armi? Con la sfera di cristallo? Certo che no, era Isabela a darci le dritte per poter contendervi i manufatti! E ve li abbiamo lasciati, in modo da poterli poi ottenere tutti in una volta, avete lavorato per noi, ed intanto vi abbiamo studiati. Poveri sciocchi!&quot;&lt;/p&gt;\r\n&lt;p&gt;&quot;Isabela, come hai potuto?&quot;, chiese sconvolta Nuliajuk, &quot;Dimmi che sei sotto l&#039;influsso del potere di Camila!&quot;. Per tutta risposta Isabela si scagli&amp;ograve; contro i tre eroi del bene, brandendo la Lancia del Destino.&lt;/p&gt;', 28, 3, 'Il Tradimento', 0, 12, 0, '&lt;p&gt;Gli eroi del bene rimangono sbigottiti dal tradimento di Isabela, sbigottimento che si accumula rispetto ad ogni volta che hanno pensato fosse un&#039;amica ed invece era una spia degli eroi del male.&lt;/p&gt;\r\n&lt;p&gt;Scrivi una funzione per calcolare lo sbigottimento cumulato.&lt;/p&gt;\r\n&lt;p&gt;La funzione prende in input una lista di numeri e crea una seconda lista dove ogni numero &amp;egrave; dato da due volte il numero presente nella prima lista.&lt;/p&gt;\r\n&lt;p&gt;Esempio:&lt;/p&gt;\r\n&lt;p&gt;Lista originale -&amp;gt; [3, 1, 5, 8, 2]&lt;/p&gt;\r\n&lt;p&gt;Lista seconda-&amp;gt; [6, 2, 10, 16, 4]&lt;/p&gt;\r\n&lt;p&gt;La funzione restituisce la seconda lista.&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Usa la funzione in un programma main, dove creare la prima lista e stampare la seconda lista ottenuta dalla funzione.&lt;/p&gt;', 1),
(52, '418e89c2-9051-4743-b7a1-7098247cf633', '&lt;p&gt;Gli eroi del bene sono letteralmente a terra.&lt;/p&gt;\r\n&lt;p&gt;Crea una funzione che prenda una stringa come parametro e restituisca una tupla dove il primo elemento &amp;egrave; il carattere della stringa che compare pi&amp;ugrave; volte all&#039;interno della stringa, il secondo elemento indichi quante volte questo compare. (si pu&amp;ograve; usare il metodo count delle stringhe)&lt;/p&gt;\r\n&lt;p&gt;Esempio: all&#039;interno della stringa &quot;supercalifragilistichespiralidoso&quot; il carattere che compare con maggiore frequenza &amp;egrave; la i e compare 6 volte.&lt;/p&gt;\r\n&lt;p&gt;Usare la funzione definita in una funzione main, dove si chiede in input all&#039;utente una stringa e gli si comunica qual &amp;egrave; il carattere che compare pi&amp;ugrave; volte, con la sua frequenza.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a7509be9a4.94551621_exercise_editor_6a34177ec62046.96940990.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Isabela attacc&amp;ograve; con foga, colpendo con ferocia e determinazione. Nuliajuk sollev&amp;ograve; lo Scudo Dorato per proteggere s&amp;egrave; stessa e i suoi compagni, mentre Jambalaya impugnava l&#039;Arco d&#039;Avorio, pronto a scoccare frecce contro la traditrice.Nuliajuk par&amp;ograve; i colpi di Isabela, Budi si risvegli&amp;ograve; dal potere del Martello dell&#039;Eden, rinnovando i suoi attacchi.&lt;/p&gt;\r\n&lt;p&gt;Jamabalaya esitava nello scoccare frecce contro la sua amica, sperava che nel suo cuore alitasse ancora un soffio di bont&amp;agrave;. Ma Isabela non aveva nessuna remora nel colpire i suoi ex compagni di viaggio. La situazione era disperata, gli eroi del bene non sapevano cosa fare e stavano soccombendo sotto l&#039;impeto dei loro nemici.&lt;/p&gt;\r\n&lt;p&gt;Mentre la battaglia infuriava, la disperazione cresceva nei cuori degli eroi del bene. Isabela, ora completamente immersa nell&#039;oscurit&amp;agrave;, sembrava invincibile, determinata a distruggere coloro che una volta aveva chiamato amici. Nuliajuk continuava a difendere se stessa e i suoi compagni con lo Scudo Dorato, ma i colpi di Isabela e Budi stavano mettendo a dura prova le loro difese.&lt;/p&gt;\r\n&lt;p&gt;Jambalaya era paralizzato, Chijioke non sapeva come poter utilizzare il potere del Martello per aiutare i compagni.&lt;/p&gt;\r\n&lt;p&gt;Nuliajuk opponeva una strenua resistenza, ma ad un certo punto non pot&amp;egrave; che arrendersi: era allo stremo delle forze. Budi la tramort&amp;igrave; con un pugno gommoso, Isabela immobilizz&amp;ograve; Chijioke a terra, puntandogli la Lancia alla gola.&lt;/p&gt;\r\n&lt;p&gt;Gli eroi del bene erano sconfitti. Ma Jambalaya non avrebbe gettato la spugna, doveva mantenere viva la speranza. Si gir&amp;ograve; di scatto, puntando il parapetto della nave, un agile balzo e si lanci&amp;ograve; in mare aperto.&lt;/p&gt;', 28, 3, 'Prigionieri!', 0, 12, 0, '&lt;p&gt;Gli eroi del bene sono letteralmente a terra.&lt;/p&gt;\r\n&lt;p&gt;Crea una funzione che prenda una stringa come parametro e restituisca quante volte compare la lettera &quot;a&quot; all&#039;interno della stringa&lt;/p&gt;\r\n&lt;p&gt;Usare la funzione definita in una funzione main, dove si chiede in input all&#039;utente una stringa e gli si comunica quante volte compare la &quot;a&quot;&lt;/p&gt;', 1),
(53, '8aad16d0-ccc5-4cbc-b847-e5d30194fb09', '&lt;p class=&quot;MsoNormal&quot;&gt;Thalassia nuota per portare in salvo Jambalaya: crea una funzione che prenda come parametro la velocit&amp;agrave; in km/h di Thalassia ed il tempo in minuti per il quale nuota. Usando le formule fisiche che legano velocit&amp;agrave;, tempo e spazio, la funzione deve calcolare e restituire quanti km ha percorso l&amp;rsquo;animale. Crea una seconda funzione che calcoli quante ore sono trascorse, dati i minuti. Usare la seconda funzione all&amp;rsquo;interno della prima. Creare una terza funzione main che chiede all&amp;rsquo;utente velocit&amp;agrave; e tempo in minuti e stampi poi la distanza percorsa.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750a22f53.62004368_exercise_editor_6a3417ce595194.99730768.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya si ritrov&amp;ograve; immerso nell&amp;rsquo;immensit&amp;agrave; del mare aperto, con il respiro affannoso e il cuore che batteva all&amp;rsquo;impazzata. La nave si allontanava sempre di pi&amp;ugrave;, mentre lui lottava contro le onde, l&amp;rsquo;arco d&amp;rsquo;avorio ancora legato alla schiena. Ogni bracciata sembrava pi&amp;ugrave; difficile della precedente, ma la determinazione che lo animava lo spingeva ad andare avanti, nonostante la stanchezza che pesava sulle sue gambe e braccia.&lt;/p&gt;\r\n&lt;p&gt;Improvvisamente, una presenza familiare si fece sentire. Thalassia, la creatura del mare che li aveva gi&amp;agrave; aiutati, emerse dalle acque profonde, la sua forma gigantesca e maestosa che sfidava le leggi della natura. Con movimenti fluidi e potenti, si avvicin&amp;ograve; a Jambalaya. Il ragazzo, riconoscendola, non esit&amp;ograve;: si aggrapp&amp;ograve; saldamente alla sua enorme pinna caudale, sentendo subito il calore e la forza che emanavano dalla creatura. Con un movimento deciso, Thalassia inizi&amp;ograve; a nuotare, sollevando il ragazzo e portandolo lontano dalla nave nemica.&lt;/p&gt;\r\n&lt;p&gt;La velocit&amp;agrave; di Thalassia era straordinaria. La nave, nonostante l&#039;impeto della tempesta scatenata dai suoi avversari, non riusc&amp;igrave; mai a raggiungere la creatura sottomarina. I giorni passarono, ma Thalassia non si ferm&amp;ograve; mai, guidando Jambalaya verso una salvezza che sembrava sempre pi&amp;ugrave; lontana. Il ragazzo si aggrapp&amp;ograve; a lei come una zattera, ma ogni onda che colpiva li faceva sentire pi&amp;ugrave; piccoli, pi&amp;ugrave; vulnerabili.&lt;/p&gt;', 28, 3, 'La fuga di Jambalaya', 0, 12, 0, '&lt;p&gt;Thalassia nuota per portare in salvo Jambalaya: crea una funzione che prenda come parametro la velocit&amp;agrave; in km/h di Thalassia ed il tempo in ore per il quale nuota. Usando le formule fisiche che legano velocit&amp;agrave;, tempo e spazio, la funzione deve calcolare e restituire quanti km ha percorso l&amp;rsquo;animale. &amp;nbsp;Creare una seconda funzione main che chiede all&amp;rsquo;utente velocit&amp;agrave; e tempo in ore e stampi poi la distanza percorsa.&lt;/p&gt;', 1),
(54, 'd92fe368-ce3f-4093-a65c-f80df965ff6b', '&lt;p&gt;Crea un file con un modulo. Il modulo contiene funzioni per riattivare la speranza di Jambalaya. La prima funzione deve prendere come parametro un numero di elementi e restituire una lista di elementi casuali pari al numero passato come parametro. Rappresenta i numeri della speranza. Una seconda funzione deve prendere come parametro una lista e restituire il minor numero dispari presente al suo interno. La minima speranza. Una terza funzione prende una lista come parametro e raddoppia tutti gli elementi al suo interno. La speranza cresce.&lt;/p&gt;\r\n&lt;p&gt;Crea un secondo file dove inserire un main e l&amp;rsquo;import del modulo precedente. Il main chiede all&amp;rsquo;utente il numero di elementi di speranza di Jambalaya, crea una lista di elementi casuali con la funzione apposita, trova e stampa il minimo, raddoppia gli elementi e stampa la lista finale.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750a72cf4.81295042_exercise_editor_6a3418478d8153.44698684.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Finalmente, dopo giorni di lotta contro il mare, raggiunsero la terraferma. La sabbia dell&#039;Australia fu un rifugio tanto atteso quanto doloroso. Jambalaya, esausto e scosso dagli eventi, croll&amp;ograve; sulla spiaggia. Il sole caldo lo colp&amp;igrave; con forza, ma non aveva la forza di alzarsi subito. Thalassia lo guard&amp;ograve; con occhi pieni di saggezza e piet&amp;agrave;. Sapeva che non poteva rimanere troppo a lungo; il suo posto era nelle profondit&amp;agrave; marine, dove sorvegliava la citt&amp;agrave; sottomarina.&lt;/p&gt;\r\n&lt;p&gt;&quot;Jambalaya,&quot; disse, con una voce che sembrava provenire dalle acque stesse, &quot;sono felice di averti aiutato, ma il mio destino &amp;egrave; altrove. Devi proseguire da solo. La tua missione non &amp;egrave; finita, e i tuoi amici hanno bisogno di te. Trova la forza dentro di te per salvarli.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya sollev&amp;ograve; lo sguardo, ma la stanchezza e la preoccupazione offuscarono il suo volto. &quot;Cosa posso fare, Thalassia?&quot; chiese, la sua voce rotta dalla disperazione. &quot;Come posso salvare i miei amici se sono rimasti prigionieri dei pi&amp;ugrave; potenti tra gli eroi del male? Isabela... non riesco a credere che abbia tradito tutto ci&amp;ograve; che ci univa.&quot;&lt;/p&gt;\r\n&lt;p&gt;La balena marittima scosse lentamente la testa. &quot;Non &amp;egrave; solo la forza che conta, ragazzo. Non puoi affrontare il male con la sola forza bruta. Devi trovare il cuore di questa battaglia, la chiave per liberare i tuoi amici. Il destino ti ha scelto, e lo devi accettare. Ogni passo che fai, ogni decisione che prendi, ti porter&amp;agrave; pi&amp;ugrave; vicino alla verit&amp;agrave;. Ricorda, anche la luce pi&amp;ugrave; debole pu&amp;ograve; scacciare le tenebre pi&amp;ugrave; oscure, se &amp;egrave; guidata dalla volont&amp;agrave;.&quot;&lt;/p&gt;\r\n&lt;p&gt;Con un ultimo sguardo di incoraggiamento, Thalassia si tuff&amp;ograve; nelle acque profonde, scomparendo tra le onde con la stessa grazia con cui era emersa. Jambalaya rimase solo, la sabbia calda sotto di lui, il cielo azzurro sopra di lui, ma il suo cuore era ancora tormentato. Aveva la sensazione di essere troppo lontano dalla sua missione, troppo piccolo rispetto alla vastit&amp;agrave; di ci&amp;ograve; che stava affrontando. Tuttavia, qualcosa dentro di lui cominci&amp;ograve; a risvegliarsi, una determinazione che non aveva mai conosciuto prima. Doveva trovare un modo per liberare i suoi amici. Doveva trovare la forza dentro di s&amp;eacute; per affrontare il male che aveva preso il sopravvento.&lt;/p&gt;\r\n&lt;p&gt;Si rialz&amp;ograve; lentamente, sentendo il peso della responsabilit&amp;agrave; che pesava sulle sue spalle, ma anche una scintilla di speranza che illuminava il suo cammino. La battaglia era appena iniziata.&lt;/p&gt;', 28, 3, 'Jambalaya cerca speranza', 0, 13, 0, '&lt;p&gt;Crea un file con un modulo. Il modulo contiene funzioni per riattivare la speranza di Jambalaya. La prima funzione deve prendere come parametro un numero di elementi e restituire una lista di elementi casuali pari al numero passato come parametro. Rappresenta i numeri della speranza. Una seconda funzione deve prendere come parametro una lista e restituire la somma degli elementi della lista, la nuova speranza.&lt;/p&gt;\r\n&lt;p&gt;Crea un secondo file dove inserire un main e l&amp;rsquo;import del modulo precedente. Il main chiede all&amp;rsquo;utente il numero di elementi di speranza di Jambalaya, crea una lista di elementi casuali con la funzione apposita, poi stampala e usala come parametro della seconda funzione, per ottenere la somma degli elementi e stamparla.&lt;/p&gt;', 1),
(55, '32b6495b-490d-4109-ae0e-da7bae889e99', '&lt;p&gt;Le difficolt&amp;agrave; cominciano a dissiparsi per Jambalaya: crea una funzione ricorsiva che permetta di suddividere le difficolt&amp;agrave; nel seguente modo (divisione tra interi ricorsiva): la funzione calcola quante volte si pu&amp;ograve; sottrarre un numero dal numero iniziale prima che diventi negativo. La funzione prende due parametri: il dividendo ed il divisore. Se il divisore &amp;egrave; 0 -&amp;gt; impossibile. Se uno dei numeri &amp;egrave; negativo -&amp;gt; errore. Altrimenti: se il dividendo diventa minore del divisore, la funzione restituisce 0, altrimenti restituisce 1 + la divisione intera del dividendo &amp;ndash; il divisore per il divisore.&lt;/p&gt;\r\n&lt;p&gt;Esempio: divisione intera di 8 per 3 &amp;egrave; data da&lt;/p&gt;\r\n&lt;p&gt;1 + divisione intera di (8-3) per 3&lt;/p&gt;\r\n&lt;p&gt;La divisione di 5 per 3 &amp;egrave; data da:&lt;/p&gt;\r\n&lt;p&gt;1 + divisione intera di (5-3) per 3&lt;/p&gt;\r\n&lt;p&gt;La divisione intera di 2 per 3 restituisce 0 perch&amp;eacute; 2 &amp;egrave; minore di 3.&lt;/p&gt;\r\n&lt;p&gt;Quindi la divisione 5//3 diventa 1+0 e la divisione di 8//3 diventa 1+1, risultato = 2&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750ab8bd3.44162519_exercise_editor_6a341898ea6c39.95865519.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya si rialz&amp;ograve; a fatica, le gambe tremanti e la mente ancora confusa, quando un&#039;ombra si stagli&amp;ograve; contro il sole accecante. Una figura imponente si avvicinava lungo la spiaggia, i passi lenti ma decisi, come se ogni granello di sabbia si spostasse al suo comando. Era un uomo di aspetto singolare: completamente calvo, il cranio e il corpo coperti di intricati tatuaggi antichi che sembravano pulsare leggermente sotto la luce. I suoi occhi erano due abissi di calma e determinazione, capaci di scrutare il cuore di chiunque gli si ponesse davanti.&lt;/p&gt;\r\n&lt;p&gt;Era Strogar, il secondo degli eroi neutrali. Nessuno conosceva esattamente la sua et&amp;agrave;, ma si diceva che vivesse da un tempo che sfuggiva alla memoria degli uomini. Da secoli osservava il mondo dall&amp;rsquo;ombra, grazie ai suoi poteri che gli permettevano di percepire tutto ci&amp;ograve; che accadeva sulla Terra, annotando nei suoi tomi sacri gli eventi pi&amp;ugrave; cruciali della storia umana. Normalmente, si limitava a osservare, mantenendo la neutralit&amp;agrave; che aveva giurato di rispettare. Ma ora, qualcosa era cambiato.&lt;/p&gt;\r\n&lt;p&gt;Con voce profonda e carica di gravit&amp;agrave;, Strogar si rivolse a Jambalaya:&lt;br&gt;&quot;Ti ho osservato, ragazzo. Ho visto il coraggio nei tuoi occhi e la disperazione nel tuo cuore. Ho assistito alla caduta dei tuoi compagni, al tradimento di chi credevi amico. L&#039;equilibrio del mondo &amp;egrave; appeso a un filo, e se gli eroi malvagi prenderanno il sopravvento, l&#039;oscurit&amp;agrave; che ne scaturir&amp;agrave; sar&amp;agrave; pi&amp;ugrave; profonda di qualsiasi altra mai conosciuta.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya lo guard&amp;ograve;, incapace di rispondere subito. Sentiva la solennit&amp;agrave; di quelle parole, il peso che gravava sul momento.&lt;/p&gt;\r\n&lt;p&gt;&quot;Non posso pi&amp;ugrave; restare a guardare,&quot; continu&amp;ograve; Strogar, avanzando ancora di qualche passo. &quot;Per troppo tempo ho mantenuto la mia neutralit&amp;agrave;, ma ora &amp;egrave; diverso. Questo scontro non riguarda pi&amp;ugrave; solo gli eroi: riguarda l&#039;intero futuro dell&#039;umanit&amp;agrave;. Se il male trionfer&amp;agrave;, non ci sar&amp;agrave; pi&amp;ugrave; storia da scrivere, solo silenzio e rovina.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya strinse i pugni, sentendo la speranza che lentamente riaffiorava. Forse non era solo, forse, con l&amp;rsquo;aiuto di Strogar, aveva ancora una possibilit&amp;agrave; di salvare i suoi amici... e il mondo intero.&lt;/p&gt;\r\n&lt;p&gt;&quot;Se accetterai il mio aiuto,&quot; concluse Strogar, &quot;ti guider&amp;ograve;. Ma sappi che il cammino sar&amp;agrave; duro, e ogni scelta che farai potr&amp;agrave; cambiare il destino di tutti.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya annu&amp;igrave;, sentendo una nuova forza accendersi dentro di s&amp;eacute;. Era pronto.&lt;br&gt;O almeno, avrebbe dovuto esserlo.&lt;/p&gt;', 31, 3, 'Strogar', 0, 4, 0, '&lt;p&gt;Crea una funzione ricorsiva con un parametro e stampi tante volte quanto &amp;egrave; il parametro la stringa &quot;La forza aumenta&quot;. La funzione deve effettuare chiamate ricorsive a se stessa se il parametro &amp;egrave; maggiore di 0, non fare nulla altrimenti. Pensare come passare il parametro affinch&amp;egrave; ad un certo punto questo diventi 0.&lt;/p&gt;\r\n&lt;p&gt;Chiamare la funzione ricorsiva in un programma main con un valore iniziale chiesto all&#039;utente.&lt;/p&gt;', 2),
(56, '1e48970f-962c-43b0-a253-e5096fffc7a8', '&lt;p&gt;I due eroi devono gestire le coordinate russe per trovare l&amp;rsquo;Ascia Spaccaterra: scrivi un programma in Python che permetta di aiutarli. Il programma dovr&amp;agrave; usare funzioni per separare le varie operazioni e dovr&amp;agrave; permettere all&amp;rsquo;utente (tramite input) di:&lt;/p&gt;\r\n&lt;p&gt;Inserire una coordinata con valori di latitudine e longitudine, salvata su lista come tupla&lt;/p&gt;\r\n&lt;p&gt;Calcolare la media delle latitudini e longitudini&lt;/p&gt;\r\n&lt;p&gt;Trovare la latitudine pi&amp;ugrave; elevata&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750b23565.35150638_exercise_editor_6a3418ea707320.11800565.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Strogar fiss&amp;ograve; l&amp;rsquo;orizzonte per un lungo istante, poi si volt&amp;ograve; verso Jambalaya.&lt;/p&gt;\r\n&lt;p&gt;&quot;L&amp;rsquo;ultima arma non &amp;egrave; andata perduta, come molti credono,&quot; disse con tono grave. &quot;L&amp;rsquo;Ascia Spaccaterra... uno strumento forgiato nel cuore del mondo, capace di frantumare montagne e dividere gli oceani. La sua potenza &amp;egrave; tale da risvegliare le forze primordiali della Terra stessa. Se cadr&amp;agrave; nelle mani sbagliate, il pianeta potrebbe non sopravvivere.&quot;&lt;/p&gt;\r\n&lt;p&gt;Jambalaya lo guard&amp;ograve; con occhi accesi da una nuova determinazione. &quot;E dove si trova?&quot;&lt;/p&gt;\r\n&lt;p&gt;&quot;Nel luogo dove la Terra respira ancora con il fiato degli d&amp;egrave;i,&quot; rispose Strogar. &quot;Dovremo andare in Russia&quot;.&lt;/p&gt;', 28, 3, 'L\'ultima arma', 0, 12, 0, '&lt;p&gt;Crea una lista con all&#039;interno 3 tuple con 2 valori numerici rappresentanti le coordinate di latitudine e longitudine.&lt;/p&gt;\r\n&lt;p&gt;Con un ciclo trova la somma delle tre latitudini e la somma delle tre longitudini e stampa alla fine i due valori calcolati&lt;/p&gt;', 2),
(57, '0a224e5a-48bd-4432-a610-287b0ffce119', '&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya e Strogar hanno bisogno di salvare le informazioni dell&amp;rsquo;esplosione. Crea un programma Python che apra un file di testo, chieda all&amp;rsquo;utente i nomi delle 5 citt&amp;agrave; colpite dall&amp;rsquo;esplosione e li salvi su file, andando a capo tra l&amp;rsquo;uno e l&amp;rsquo;altro.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750b6fea1.68598357_exercise_editor_6a341960a99f72.51878526.png&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Era il &lt;strong&gt;30 giugno 1908&lt;/strong&gt; quando, sopra le remote foreste della Siberia centrale, si verific&amp;ograve; un evento che ancora oggi resta avvolto nel mistero: una &lt;strong&gt;gigantesca esplosione&lt;/strong&gt; scosse la regione di &lt;strong&gt;Tunguska&lt;/strong&gt;, abbattendo milioni di alberi su un&#039;area di oltre 2.000 chilometri quadrati. I testimoni raccontarono di un &lt;strong&gt;bagliore accecante&lt;/strong&gt;, seguito da un&#039;&lt;strong&gt;onda d&#039;urto&lt;/strong&gt; che viaggi&amp;ograve; per centinaia di chilometri, distruggendo tutto ci&amp;ograve; che incontrava.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Per anni, nessuno os&amp;ograve; avventurarsi fin l&amp;igrave;. Solo nel &lt;strong&gt;1927&lt;/strong&gt;, un gruppo di scienziati guidato dal mineralogista &lt;strong&gt;Leonid Kulik&lt;/strong&gt; os&amp;ograve; addentrarsi nella foresta devastata. Kulik cercava il cratere di un meteorite, ma non trov&amp;ograve; nulla. Solo alberi abbattuti a raggiera, tronchi anneriti e strani campi magnetici che sembravano danzare nell&#039;aria. Nessuno sapeva che, secoli prima, quell&#039;area era stata teatro di un altro mistero: l&#039;ultima arma degli antichi eroi &amp;mdash; &lt;strong&gt;l&#039;Ascia Spaccaterra&lt;/strong&gt; &amp;mdash; era stata sepolta l&amp;igrave;, nascosta tra la terra e il ghiaccio, protetta da incantesimi dimenticati.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nel 1908, fu proprio la &lt;strong&gt;riattivazione&lt;/strong&gt; accidentale dell&#039;Ascia a scatenare l&#039;immensa esplosione. Un risveglio parziale del suo potere, come un battito d&#039;ali in un sonno inquieto. Non un meteorite, non un esperimento umano, ma un&#039;arma antica e potente, capace di frantumare il suolo stesso.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ora, pi&amp;ugrave; di un secolo dopo, Jambalaya e Strogar erano decisi a recuperarla. Solo brandendo l&#039;Ascia Spaccaterra avrebbero potuto contrastare le forze oscure che avevano corrotto gli eroi e riportare la speranza nel mondo.&lt;/p&gt;', 27, 3, 'Tunguska', 0, 14, 0, '&lt;p&gt;Jambalaya e Strogar hanno bisogno di salvare le informazioni dell&amp;rsquo;esplosione. Crea un programma Python che apra un file di testo, chieda all&amp;rsquo;utente il nome di una citt&amp;agrave; e lo salvi su file, andando a capo alla fine.&lt;/p&gt;', 0),
(58, '4076b704-03c2-40a5-80f7-434e05478e05', '&lt;p class=&quot;MsoNormal&quot;&gt;L&amp;rsquo;Ascia Spaccaterra &amp;egrave; l&amp;rsquo;ultima delle armi degli eroi del bene: quali sono i suoi poteri? Scrivi 3 poteri su un file di testo .txt andando a capo tra uno e l&amp;rsquo;altro. Scrivi un programma Python che legge i 3 poteri uno alla volta dal file, stampandoli a video.&lt;/p&gt;', 0, '&lt;p&gt;Tunguska, Siberia centrale.&lt;/p&gt;\r\n&lt;p&gt;La neve scricchiolava sotto i passi di Jambalaya e Strogar, mentre avanzavano tra gli scheletri silenziosi degli alberi abbattuti pi&amp;ugrave; di un secolo prima. L&amp;rsquo;aria era carica di elettricit&amp;agrave;, come se la terra stessa stesse trattenendo il fiato. La luce grigia del giorno filtrava tra le nuvole basse, e un vento tagliente sollevava sbuffi di ghiaccio attorno a loro.&lt;/p&gt;\r\n&lt;p&gt;Giunsero infine al centro della radura. L&amp;igrave;, dove l&amp;rsquo;esplosione aveva avuto origine, il terreno era spaccato in mille fratture, annerito dal tempo e dalla potenza del passato. E proprio in mezzo a quel cratere innaturale, un piedistallo di pietra nera e lucente si ergeva, come appena emerso dalle viscere della terra. Su di esso, piantata profondamente, si trovava l&amp;rsquo;Ascia Spaccaterra.&lt;/p&gt;\r\n&lt;p&gt;Jambalaya sent&amp;igrave; il cuore accelerare. L&amp;rsquo;aria attorno all&amp;rsquo;arma tremava leggermente, come distorta dal calore, anche se faceva un freddo pungente. Strogar fece un passo avanti, i muscoli tesi, gli occhi fissi&lt;/p&gt;', 27, 3, 'Il piedistallo con l\'Ascia', 0, 14, 0, '&lt;p&gt;L&amp;rsquo;Ascia Spaccaterra &amp;egrave; l&amp;rsquo;ultima delle armi degli eroi del bene: quali sono i suoi poteri? Scrivi 1 potere su un file di testo .txt con un editor qualsiasi, tipo Blocco Note. Scrivi un programma Python che legge il potere dal file e lo stampi a video.&lt;/p&gt;', 0),
(59, '6f0432d9-e19a-4a6d-aed2-708003532b02', '&lt;p class=&quot;MsoNormal&quot;&gt;I poteri di Nikolaj sono immensi: forza sovrumana, telecinesi, pirocinesi, capacit&amp;agrave; di volare. Inserisci l&amp;rsquo;elenco dei poteri in un file di testo .txt, scrivendoli uno a capo all&amp;rsquo;altro. Apri il file in lettura tramite Python e per ogni potere chiedi il numero di danni che pu&amp;ograve; provocare a Jambalaia e Strogar, salvando tale numero in un secondo file .txt&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750bfede6.11203921_exercise_editor_6a3419ed2a4730.20388139.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ma Jambalaya e Strogar non erano soli.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Una voce profonda e calma, carica di disprezzo, si fece strada tra le raffiche di vento:&lt;br&gt;&lt;strong&gt;&amp;laquo;Siete arrivati tardi.&amp;raquo;&lt;/strong&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Dalle ombre tra i tronchi spezzati emerse una figura slanciata, un&amp;rsquo;aura di un intenso color rosso emanava dal essa. &lt;strong&gt;Nikolaj&lt;/strong&gt;, l&amp;rsquo;eroe pi&amp;ugrave; potente e spietato del fronte dell&amp;rsquo;oscurit&amp;agrave;.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Strogar serr&amp;ograve; i pugni.&lt;br&gt;&amp;laquo;Nikolaj&amp;hellip; Tu.&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&amp;laquo;Sapevo che sareste arrivati. La &lt;em&gt;mappa di Aionios&lt;/em&gt;, presa ai vostri amici imprigionati, non mente mai. L&amp;rsquo;ultima delle armi &amp;egrave; qui&amp;hellip; ed &amp;egrave; mia.&amp;raquo;&lt;br&gt;La voce di Nikolaj era priva d&amp;rsquo;ira. Era sicuro. Implacabile. L&amp;rsquo;oscurit&amp;agrave; intorno a lui sembrava viva.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya si fece avanti. &amp;laquo;Non ti lasceremo prenderla. Questa guerra finir&amp;agrave;, e tu non sarai colui che la vincer&amp;agrave;.&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolaj rise piano.&lt;br&gt;&amp;laquo;Voi due? Un vecchio eroe stanco e un ragazzo spaventato? Contro &lt;em&gt;me&lt;/em&gt;?&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Poi si mosse. In un battito di ciglia, fu tra loro, e l&amp;rsquo;aria esplose di potere.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;La battaglia per l&amp;rsquo;Ascia Spaccaterra era cominciata.&lt;/p&gt;', 27, 3, 'L\'ultimo eroe malvagio: Nikolaj', 0, 14, 0, '&lt;p&gt;I poteri di Nikolaj sono immensi: forza sovrumana, telecinesi, pirocinesi, capacit&amp;agrave; di volare. Inserisci l&amp;rsquo;elenco dei poteri in un file di testo .txt, scrivendoli uno a capo all&amp;rsquo;altro. Apri il file in lettura tramite Python e stampa i poteri letti.&lt;/p&gt;', 1),
(60, '34e3b177-9413-483e-a804-714b6f8e0c15', '&lt;p class=&quot;MsoNormal&quot;&gt;Tramite Python apri un file in modalit&amp;agrave; write, crea quindi 2 stringhe composte da 10 numeri casuali estratti tra 1 e 100 ognuna. I numeri dovranno comparire separati da punto e virgola all&amp;rsquo;interno delle stringhe. Salva le 2 stringhe su file e poi chiudi il file. La prima stringa rappresenta i colpi sferrrati da Strogar, la seconda quelli di Nikolaj.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Riapri il file in modalit&amp;agrave; read. Leggi le due stringhe, spezzandone le varie componenti con split. Somma le componenti della prima riga e della seconda riga e indica quale dei due eroi vince (vince chi ha il numero sommato maggiore).&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Esempio di stringa da salvare:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;17;92;12;66;35;81;40;84;27;1&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750c4cc19.78964139_exercise_editor_6a341a3a831869.53597265.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolaj avanz&amp;ograve; come una tempesta, i suoi passi scuotevano il suolo ghiacciato. Attorno a lui si sprigionava un&amp;rsquo;aura d&amp;rsquo;energia oscura che distorceva l&amp;rsquo;aria, come se la realt&amp;agrave; stessa faticasse a contenerlo. Sollev&amp;ograve; un braccio e dal nulla si materializz&amp;ograve; la sua arma: &lt;strong&gt;la Lama del Vortice&lt;/strong&gt;, un&amp;rsquo;enorme spada di tenebra pura, affilata come la vendetta.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Strogar&lt;/strong&gt; si lanci&amp;ograve; per primo. Le sue mani nude scintillavano di una luce terrosa, richiamando a s&amp;eacute; la forza della roccia e della storia. Il terreno trem&amp;ograve; sotto i suoi piedi mentre un pugno possente andava a scontrarsi contro la spada di Nikolaj. L&amp;rsquo;impatto fu devastante, un boato risuon&amp;ograve; nella valle, facendo volare neve e polvere tutto intorno.&lt;/p&gt;', 27, 3, 'Battaglia per l\'Ascia', 0, 14, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Tramite Python apri un file in modalit&amp;agrave; write, crea quindi 2 stringhe composte da 10 numeri casuali estratti tra 1 e 100 ognuna. I numeri dovranno comparire separati da punto e virgola all&amp;rsquo;interno delle stringhe. Quindi devi creare le due stringhe vuote, usare un ciclo, estrarre i due numeri casuali, aggiungerli alla stringa relativa con +=, aggiungere il carattere &quot;;&quot;. Salva infine le 2 stringhe su file e poi chiudi il file.&lt;/p&gt;', 1),
(61, '6fe845d0-5304-4223-8b38-2c3e179745cc', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea con Python un file di testo con record strutturati composto da righe di 30 caratteri, dove i caratteri da 0 a 9 indicano il numero di freccia lanciato da Jambalaya, i caratteri da 10 a 20 indicano la potenza della freccia (un numero casuale tra 0 e 100), gli ultimi 10 caratteri indicano dove colpisce la freccia (chiesto in input all&amp;rsquo;utente). Crea dei trattini per riempire gli spazi vuoti moltiplicando il carattere &amp;ldquo;-&amp;ldquo; per il numero di caratteri mancanti. Salva la riga creata sul file.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Esempio di file creato:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;freccia1--44--------braccio---&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;freccia2&amp;mdash;12--------terra------&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750cb6c79.49538184_exercise_editor_6a341a8679b583.81191602.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nel frattempo, &lt;strong&gt;Jambalaya&lt;/strong&gt; si posizion&amp;ograve; dietro un masso spezzato e impugn&amp;ograve; l&amp;rsquo;&lt;strong&gt;Arco d&amp;rsquo;Avorio&lt;/strong&gt;. Respir&amp;ograve; a fondo, punt&amp;ograve; l&amp;rsquo;arco verso l&amp;rsquo;avversario e lasci&amp;ograve; andare la prima freccia incantata: una saetta luminosa fischi&amp;ograve; nell&amp;rsquo;aria, colpendo Nikolaj al fianco. Ma il guerriero dell&amp;rsquo;oscurit&amp;agrave; si limit&amp;ograve; a ruotare leggermente il busto e sorrise.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&amp;laquo;Avrai bisogno di molto pi&amp;ugrave; di questo, ragazzo.&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya, tuttavia, non si lasci&amp;ograve; intimorire. Estrasse altre tre frecce e le lanci&amp;ograve; in rapida successione. Una colp&amp;igrave; il terreno, generando una nube abbagliante di luce. Un&amp;rsquo;altra tracci&amp;ograve; un arco che esplose in aria, distraendo Nikolaj per un istante. La terza... colp&amp;igrave; dritta sul petto.&lt;/p&gt;', 27, 3, 'La difesa di Jambalaya', 0, 14, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Crea con Python un file di testo con record strutturati composto da righe di 10 caratteri, dove i caratteri indicano il numero di freccia lanciato da Jambalaya. Usa un ciclo for. Crea dei trattini per riempire gli spazi vuoti moltiplicando il carattere &amp;ldquo;-&amp;ldquo; per il numero di caratteri mancanti e aggiungili alla stringa &quot;freccia&quot;+contatore del ciclo. Aggiungi alla fine anche il carattere &quot;\\n&quot; per andare a capo. Salva la riga creata sul file.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Esempio di file creato:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;freccia1----&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;freccia2----&lt;/p&gt;', 1),
(62, '887e2100-28fd-4e65-a101-1baa7cd31644', '&lt;p&gt;Crea un programma Python che apra il file creato nell&amp;rsquo;esercizio precedente, chieda in input all&amp;rsquo;utente un numero di freccia e scriva i dati della freccia scelta. Ad esempio, se scrivo 2 otterr&amp;ograve;:&lt;/p&gt;\r\n&lt;p&gt;Freccia 2, potenza: 12, colpisce terra&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Fu allora che &lt;strong&gt;Strogar afferr&amp;ograve; l&amp;rsquo;attimo&lt;/strong&gt;: si avvent&amp;ograve; sull&amp;rsquo;Ascia Spaccaterra, posando entrambe le mani sul manico incastonato nella pietra. Per un istante, il tempo sembr&amp;ograve; fermarsi. Una luce verde, pulsante, avvolse Strogar, come se le radici del mondo gli stessero restituendo la forza di millenni. Con un urlo, l&amp;rsquo;eroe neutrale strapp&amp;ograve; l&amp;rsquo;arma dal piedistallo.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;La terra trem&amp;ograve; di nuovo. Il cielo si oscur&amp;ograve; per un istante, come se l&amp;rsquo;Ascia stessa stesse salutando il suo nuovo portatore.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolaj si volt&amp;ograve; furioso. &amp;laquo;No!&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Lanci&amp;ograve; un&amp;rsquo;onda d&amp;rsquo;energia nera contro Strogar, ma Jambalaya gli si par&amp;ograve; davanti, scoccando una freccia dorata che neutralizz&amp;ograve; l&amp;rsquo;attacco in volo. Strogar alz&amp;ograve; l&amp;rsquo;Ascia Spaccaterra e la cal&amp;ograve; con potenza sul suolo. Un&amp;rsquo;onda tellurica si propag&amp;ograve; in tutte le direzioni, sbalzando Nikolaj indietro di molti metri.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&amp;laquo;Hai ottenuto ci&amp;ograve; che volevi&amp;hellip;&amp;raquo; ringhi&amp;ograve; Nikolaj rialzandosi con fatica. &amp;laquo;Ma non potrai cambiare il destino di questo mondo.&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Poi svan&amp;igrave; nell&amp;rsquo;aria, avvolto in un turbine di fumo e ombra.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya cadde in ginocchio, esausto. Strogar gli si avvicin&amp;ograve; e lo aiut&amp;ograve; ad alzarsi.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&amp;laquo;&amp;Egrave; solo l&amp;rsquo;inizio, ragazzo. Ma ora abbiamo un&amp;rsquo;arma in pi&amp;ugrave;. E con essa&amp;hellip; salveremo i tuoi amici.&amp;raquo;&lt;/p&gt;', 27, 3, 'Strogar prende l\'Ascia', 0, 14, 0, '&lt;p&gt;Crea un programma Python che apra il file creato nell&amp;rsquo;esercizio precedente, chieda in input all&amp;rsquo;utente un numero di freccia e scriva i dati della freccia scelta leggendo il files con readlines e selezionando poi la riga relativa. Ad esempio, se scrivo 2 otterr&amp;ograve;:&lt;/p&gt;\r\n&lt;p&gt;Freccia2---&lt;/p&gt;', 1),
(63, '91894b20-1895-4ec1-8910-bff0c13297a5', '&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya e Strogar riescono ad individuare la base segreta grazie ai poteri dell&amp;rsquo;Arco d&amp;rsquo;Avorio. Scoccando una freccia per colpire la base segreta, &amp;egrave; bastato poi seguirla. L&amp;rsquo;Arco colpisce sempre il bersaglio.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma Python che inserisca all&amp;rsquo;interno di una lista dei numeri casuali compresi tra 1 e 1000, che identificano i km percorsi dalla freccia. Salva i numeri all&amp;rsquo;interno di un file di testo. Apri quindi il file di testo nuovamente in lettura. Leggi tutti i numeri salvati, inserendoli in una lista e stampa la somma dei km totali.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750cff569.65449497_exercise_editor_6a341b2cbc1226.42716214.jpg&quot; alt=&quot;&quot;&gt;Il vento gelido del Nord tagliava il volto come una lama sottile. Tra gli alberi piegati dalla neve, due sagome avanzavano rapide e silenziose: &lt;strong&gt;Jambalaya&lt;/strong&gt;, con l&amp;rsquo;Arco d&amp;rsquo;Avorio sulle spalle, e &lt;strong&gt;Strogar&lt;/strong&gt;, il veterano calvo dai tatuaggi che raccontavano guerre dimenticate.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Davanti a loro, tra le montagne del Quebec, sorgeva la base segreta degli eroi malvagi. Qui venivano tenuti prigionieri gli eroi del bene, rinchiusi in celle fredde e silenziose, vegliate da oscuri meccanismi.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya strinse i denti. Dovevano liberarli. Non c&amp;rsquo;era altro modo.&lt;/p&gt;', 27, 3, 'La base Canadese', 0, 14, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Jambalaya e Strogar riescono ad individuare la base segreta grazie ai poteri dell&amp;rsquo;Arco d&amp;rsquo;Avorio. Scoccando una freccia per colpire la base segreta, &amp;egrave; bastato poi seguirla. L&amp;rsquo;Arco colpisce sempre il bersaglio.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma Python che salvi 10 numeri casuali tra 0 e 100 all&amp;rsquo;interno di un file di testo, andando a capo tra uno e l&#039;altro (quindi usando un write di &quot;\\n&quot;). Apri quindi il file di testo nuovamente in lettura e stampa a video i numeri scritti nel file.&lt;/p&gt;', 1);
INSERT INTO `ct_esercizi` (`id_esercizio`, `uuid`, `testo_esercizio`, `punti_esperienza`, `storia_esercizio`, `fk_argomento`, `tipo_esercizio`, `nome_capitolo`, `num_domande`, `fk_materiale`, `monete_guadagnate`, `testo_ese104`, `livello_diff`) VALUES
(64, 'cffb9c53-8769-412f-8c50-16d0fd068c86', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma Python che gestisca dati su di un file rappresentanti la mappa mentale di Strogar. Il programma deve avere un menu con 3 opzioni:&lt;/p&gt;\r\n&lt;p class=&quot;MsoListParagraph&quot; style=&quot;text-indent: -18.0pt; mso-list: l0 level1 lfo1;&quot;&gt;&lt;!-- [if !supportLists]--&gt;&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;1-&lt;span style=&quot;font: 7.0pt &#039;Times New Roman&#039;;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;!--[endif]--&gt;Inserisci nuova stanza nella mappa mentale. La stanza deve avere un nome, una dimensione in metri quadrati ed un&amp;rsquo;ala dove &amp;egrave; posizionata nell&amp;rsquo;edificio (sud, ovest, est o nord).&lt;/p&gt;\r\n&lt;p class=&quot;MsoListParagraphCxSpLast&quot; style=&quot;text-indent: -18.0pt; mso-list: l0 level1 lfo1;&quot;&gt;&lt;!-- [if !supportLists]--&gt;&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;2-&lt;span style=&quot;font: 7.0pt &#039;Times New Roman&#039;;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;!--[endif]--&gt;Conta quante stanze stanno in una certa ala dell&amp;rsquo;edificio chiesta in input&lt;/p&gt;\r\n&lt;p class=&quot;MsoListParagraphCxSpLast&quot; style=&quot;text-indent: -18.0pt; mso-list: l0 level1 lfo1;&quot;&gt;&lt;!-- [if !supportLists]--&gt;&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;3-&lt;span style=&quot;font: 7.0pt &#039;Times New Roman&#039;;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;!--[endif]--&gt;Uscire dal programma&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750d4dd88.94821571_exercise_editor_6a341b75ae3e56.77713277.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;La base esplose nel caos quando i due irruppero all&amp;rsquo;interno, guidati da una mappa mentale tracciata da Strogar. In pochi minuti riuscirono a spezzare le serrature runiche che imprigionavano gli altri eroi: &lt;strong&gt;Nuliajuk&lt;/strong&gt;, &lt;strong&gt;Chijioke&lt;/strong&gt;, &lt;strong&gt;Klaus&lt;/strong&gt; ed &lt;strong&gt;Emily&lt;/strong&gt; si riversarono nei corridoi inondati dalla luce del giorno.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Emily, canadese, figlia di quelle terre, era l&amp;rsquo;unica del gruppo senza poteri n&amp;eacute; armi sacre. Ma non vacill&amp;ograve;. Ex pattinatrice artistica, si muoveva con rapidit&amp;agrave; e precisione, affilata come la disciplina che aveva coltivato fin da bambina. La sua voce calma e ferma diede ordine agli altri: &amp;ldquo;Andiamo. Prima che sia troppo tardi.&amp;rdquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Gli eroi riuscirono a recuperare le armi sacre, che si trovavano momentaneamente abbandonate in una stanza vicino le celle.&lt;/p&gt;', 27, 3, 'Il salvataggio', 0, 14, 0, '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un programma Python che gestisca dati su di un file rappresentanti la mappa mentale di Strogar. Il programma deve avere un menu con 3 opzioni:&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;&lt;span style=&quot;font: 7.0pt &#039;Times New Roman&#039;;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li class=&quot;MsoNormal&quot;&gt;&lt;!--[endif]--&gt;Inserisci nuova stanza nella mappa mentale chiedendo il nome all&#039;utente. Chiedi in input il nome ed inseriscilo nel file, aprendolo in modalit&amp;agrave; append.&lt;/li&gt;\r\n&lt;li class=&quot;MsoNormal&quot;&gt;Stampare tutte le stanze che si trovano sul file.&lt;span style=&quot;mso-bidi-font-family: Aptos; mso-bidi-theme-font: minor-latin;&quot;&gt;&lt;span style=&quot;mso-list: Ignore;&quot;&gt;&lt;span style=&quot;font: 7.0pt &#039;Times New Roman&#039;;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;\r\n&lt;li class=&quot;MsoNormal&quot;&gt;&lt;!--[endif]--&gt;Uscire dal programma&lt;/li&gt;\r\n&lt;/ul&gt;', 1),
(65, '261fd94c-3558-4a11-a4b9-d8e205145532', '&lt;p class=&quot;MsoNormal&quot;&gt;Crea un dizionario all&amp;rsquo;interno del quale inserire i nomi degli eroi, i loro punti vita e la loro potenza magica. Il nome sar&amp;agrave; la chiave, punti vita e potenza magica (espressi in numeri) sono i valori (inseriti in una tupla). Salva il dizionario su file usando il modulo pickle.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Utilizzando sempre pickle, carica il dizionario da file e stampa ad uno ad uno i nomi degli eroi con la loro vita e potenza magica.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;pickle.dump() -&amp;gt; salva i dati su file&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;pickle.load() -&amp;gt; carica i dati da file&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750daf285.88043951_exercise_editor_6a341bddefe601.89770409.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nel cuore ghiacciato della base, la battaglia finale prese vita.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Isabela li attendeva, impugnando la Lancia del Destino. Al suo fianco &lt;strong&gt;Budi&lt;/strong&gt;, &lt;strong&gt;Camila&lt;/strong&gt;, &lt;strong&gt;Lachlan&lt;/strong&gt;, &lt;strong&gt;Shinzo Hanzei&lt;/strong&gt;, &lt;strong&gt;Aisha&lt;/strong&gt;&amp;hellip; e soprattutto &lt;strong&gt;Nikolaj&lt;/strong&gt;, il comandante supremo degli eroi del male. Freddo come l&amp;rsquo;acciaio, Nikolaj avanz&amp;ograve; senza una parola, gli occhi pieni di disprezzo. Sapeva che i buoni erano inferiori. Sapeva di essere il pi&amp;ugrave; forte.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ma si sbagliava.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Lo scontro esplose. Nuliajuk blocc&amp;ograve; Isabela con lo Scudo Dorato, mentre Strogar affrontava Lachlan. Jambalaya si ritrov&amp;ograve; davanti Shinzo, che usava la sua folle velocit&amp;agrave; per schivare le frecce dell&amp;rsquo;Arco d&amp;rsquo;Avorio.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolaj estrasse la sua lama, attaccando Strogar, con il quale aveva un conto in sospeso. Aisha e Budi usarono i loro poteri per mettere alle corde Klaus. Chijioke affrontava Camila, il potere della verit&amp;agrave; del martello contro il potere della persuasione oscura. Emily, disarmata, non sapeva come poter essere d&amp;rsquo;aiuto ai suoi compagni.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Lo scontro sembrava impari.&lt;/p&gt;', 27, 3, 'Esplode lo scontro', 0, 14, 0, '&lt;p&gt;Crea un dizionario all&amp;rsquo;interno del quale inserire i nomi degli eroi, i loro punti vita e la loro potenza magica. Il nome sar&amp;agrave; la chiave, punti vita e potenza magica (espressi in numeri) sono i valori (inseriti in una tupla). Stampa a video il dizionario.&lt;/p&gt;', 1),
(66, '47d664dd-e387-4281-8a89-95d26289e66e', '&lt;p class=&quot;MsoNormal&quot;&gt;Scrivere un programma che gestisca un archivio di dati su file. Il programma deve gestire eventuali eccezioni di input/output. Una riga di archivio contiene dati su: Arma dell&amp;rsquo;eroe del bene, nome dell&amp;rsquo;eroe del bene, nome del nemico affrontato, potenza dell&amp;rsquo;eroe del bene, potenza dell&amp;rsquo;eroe del male. Il programma deve dare la possibilit&amp;agrave; all&amp;rsquo;utente di: inserire una nuova riga di archivio all&amp;rsquo;interno del file, visionare la potenza totale degli eroi del bene e degli eroi del male all&amp;rsquo;interno del file, per ogni riga dire chi sia pi&amp;ugrave; potente tra il bene ed il male e contare quante volte vincono gli eroi del bene e quante gli eroi del male, dicendo chi vince nel totale.&lt;/p&gt;', 0, '&lt;p&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750dfa0f8.30980465_exercise_editor_6a341c37071715.20362705.jpg&quot; alt=&quot;&quot;&gt;&lt;/p&gt;\r\n&lt;p&gt;Nikolaj sferr&amp;ograve; un colpo furioso, la sua lama nera brillava di un&amp;rsquo;energia oscura che sembrava divorare la luce stessa. Strogar, saldo come una montagna, par&amp;ograve; l&amp;rsquo;attacco con la possente Ascia Spaccaterra. Le due armi si scontrarono con un clangore che fece vibrare le pareti della base. L&amp;rsquo;impatto gener&amp;ograve; un&amp;rsquo;onda d&amp;rsquo;urto che squarci&amp;ograve; il pavimento sotto i loro piedi.&lt;/p&gt;\r\n&lt;p&gt;La lama di Nikolaj venne deviata dalla forza brutale dell&amp;rsquo;ascia, e schizz&amp;ograve; di lato &amp;mdash; proprio verso Aisha, che nel caos della battaglia si era avvicinata troppo.&lt;/p&gt;\r\n&lt;p&gt;Un attimo. Uno solo.&lt;/p&gt;\r\n&lt;p&gt;Klaus vide tutto. Non esit&amp;ograve;.&lt;/p&gt;\r\n&lt;p&gt;Si gett&amp;ograve; davanti ad Aisha, spalancando le braccia. Il colpo lo colp&amp;igrave; in pieno petto. Un&#039;esplosione di luce e sangue.&lt;/p&gt;\r\n&lt;p&gt;Aisha sgran&amp;ograve; gli occhi. Il corpo di Klaus croll&amp;ograve; al suolo, tremante. Il suo respiro si spegneva, ma il suo volto&amp;hellip; era sereno. Come se avesse trovato pace nel proteggere un&amp;rsquo;altra vita, anche se era stata una nemica.&lt;/p&gt;\r\n&lt;p&gt;&amp;laquo;Perch&amp;eacute;&amp;hellip;?&amp;raquo; mormor&amp;ograve; Aisha, inginocchiandosi accanto a lui, confusa, scossa, vulnerabile come non mai.&lt;/p&gt;\r\n&lt;p&gt;Klaus le sorrise debolmente, le labbra spaccate ma calme. &amp;laquo;Perch&amp;eacute; tu puoi essere migliore di cos&amp;igrave;&amp;hellip;&amp;raquo;&lt;/p&gt;\r\n&lt;p&gt;Poi chiuse gli occhi.&lt;/p&gt;\r\n&lt;p&gt;Per la prima volta da anni, Aisha trem&amp;ograve;. Non per rabbia, ma per vergogna. Per rimorso. Perch&amp;eacute; quel gesto &amp;mdash; quel sacrificio &amp;mdash; l&amp;rsquo;aveva trafitta pi&amp;ugrave; della spada di Nikolaj. Si alz&amp;ograve; lentamente, fissando il caos della battaglia intorno a lei con occhi diversi. E per la prima volta, scelse di combattere non per s&amp;eacute;, ma contro ci&amp;ograve; in cui aveva creduto fino a quel momento.&lt;/p&gt;\r\n&lt;p&gt;Con un grido, Aisha si lanci&amp;ograve; nella mischia&amp;hellip; dalla parte degli eroi del bene.&lt;/p&gt;', 27, 3, 'La redenzione', 0, 14, 0, '&lt;p&gt;Scrivere un programma che gestisca un archivio di dati su file. Una riga di archivio contiene dati su: Arma dell&amp;rsquo;eroe del bene, nome dell&amp;rsquo;eroe del bene. Il programma deve dare la possibilit&amp;agrave; all&amp;rsquo;utente di: inserire una nuova riga di archivio all&amp;rsquo;interno del file. Stampare il contenuto del file. Riflettere su come separare i due valori per salvarli all&#039;interno del file.&lt;/p&gt;', 2),
(67, '6d1429f5-528f-4657-acae-ecd96b4678f0', '&lt;p&gt;Alcuni eroi sopravvivono, altri cadono, come nel gioco dell&amp;rsquo;impiccato dove alcune lettere sopravvivono, altre portano alla morte dell&amp;rsquo;omino.&lt;/p&gt;\r\n&lt;p&gt;Creare il gioco dell&amp;rsquo;Impiccato nel seguente modo:&lt;/p&gt;\r\n&lt;p&gt;Salvare su file una lista di parole, una a capo all&amp;rsquo;altra tramite un editor di testo.&lt;/p&gt;\r\n&lt;p&gt;Leggere le parole dal file, salvandole magari in una lista e sceglierne una in maniera casuale con random.randint(), Sar&amp;agrave; la parola da indovinare.&lt;/p&gt;\r\n&lt;p&gt;Inizializzare 3 variabili: una con il numero di tentativi errati, una con il numero di tentativi corretti per le lettere ed una per la scelta dell&amp;rsquo;utente su cosa fare.&lt;/p&gt;\r\n&lt;p&gt;Trasformiamo la parola originaria (una stringa) in una lista.&lt;/p&gt;\r\n&lt;p&gt;Creiamo una seconda lista di trattini lunga quanto la parola originaria, moltiplicando la lista [&amp;ldquo;-&amp;ldquo;] per la lunghezza della parola.&lt;/p&gt;\r\n&lt;p&gt;Creiamo un ciclo while, dal quale usciremo quando l&amp;rsquo;utente sceglier&amp;agrave; di uscire.&lt;/p&gt;\r\n&lt;p&gt;All&amp;rsquo;interno del ciclo inseriamo un menu per far scegliere all&amp;rsquo;utente una delle seguenti opzioni:&lt;/p&gt;\r\n&lt;p&gt;1- Tentare una lettera&lt;/p&gt;\r\n&lt;p&gt;2- Provare ad indovinare la parola completa&lt;/p&gt;\r\n&lt;p&gt;3- Uscire dal programma&lt;/p&gt;\r\n&lt;p&gt;1 - Se si tenta una lettera: si deve permettere l&amp;rsquo;inserimento della lettera in input. Si controlla se la lettera sta nella lista con la parola nascosta con un ciclo for. Ogni volta che si trova la lettera, nella posizione trovata si sostituisce nella lista con i trattini la lettera trovata e si printa la lista con i trattini e le lettere. Inoltre si aggiunge 1 ai tentativi corretti.&lt;/p&gt;\r\n&lt;p&gt;Se alla fine del for non si &amp;egrave; trovata la lettera, si aggiunge 1 ai tentativi errati.&lt;/p&gt;\r\n&lt;p&gt;Si deve fare un controllo: se i tentativi errati sono arrivati al limite massimo che vogliamo dare (tipo 8 tentativi), allora si comunica la sconfitta al giocatore e si esce dal ciclo con un break.&lt;/p&gt;\r\n&lt;p&gt;Si fa un secondo controllo: se i tentativi corretti sono &amp;gt;= alla lunghezza della parola nascosta, allora il giocatore ha vinto, lo si segnala e si esce dal ciclo con un break.&lt;/p&gt;\r\n&lt;p&gt;2 &amp;ndash; Se si tenta una parola: si controlla che la parola che inserisce il giocatore in input sia uguale alla parola nascosta. Se sono uguali, il giocatore ha vinto e si esce dal ciclo con un break. Se sono diversi si aggiunge uno ai tentativi falliti.&lt;/p&gt;\r\n&lt;p&gt;Si deve fare nuovamente il controllo: se i tentativi errati sono arrivati al limite massimo che vogliamo dare (tipo 8 tentativi), allora si comunica la sconfitta al giocatore e si esce dal ciclo con un break.&lt;/p&gt;\r\n&lt;p&gt;3 &amp;ndash; La condizione del ciclo while deve consentire di uscire dal ciclo quando si inserisce la scelta come 3&lt;/p&gt;', 0, '&lt;p&gt;La redenzione di Aisha diede una svolta alla battaglia. Il suo corpo agile si muoveva come il vento, colpendo con precisione e slancio coloro che fino a poco prima erano stati i suoi alleati. Budi esit&amp;ograve; nel vederla schierata dall&amp;rsquo;altra parte, rallentando i suoi attacchi. L&amp;rsquo;equilibrio si spostava.&lt;/p&gt;\r\n&lt;p&gt;Gli eroi del bene, rinvigoriti da quella nuova forza, alzarono la testa. Nuliajuk incalzava Isabela con colpi precisi dello Scudo Dorato, mentre Strogar, grondante sudore, respingeva i fendenti di Nikolaj, la sua Ascia Spaccaterra che vibrava ad ogni impatto.&lt;/p&gt;\r\n&lt;p&gt;Ma Shinzo Hanzei, il corridore ombra, continuava a schivare le frecce di Jambalaya, i suoi occhi ancora annebbiati dalla rabbia di un passato mai perdonato. Si muoveva troppo veloce, sembrava intoccabile.&lt;/p&gt;\r\n&lt;p&gt;&amp;laquo;Perch&amp;eacute; combatti ancora contro di noi?&amp;raquo; url&amp;ograve; Jambalaya, scoccando una freccia che graffi&amp;ograve; appena la guancia di Shinzo. &amp;laquo;La vendetta ti ha gi&amp;agrave; rubato troppo!&amp;raquo;&lt;/p&gt;\r\n&lt;p&gt;Shinzo si ferm&amp;ograve;, per un solo secondo.&lt;/p&gt;\r\n&lt;p&gt;&amp;laquo;Tu non sai cosa mi hanno fatto&amp;hellip;&amp;raquo; mormor&amp;ograve;, serrando i pugni. Ma dentro di lui qualcosa si spezzava. Aveva sempre detto di combattere per giustizia, ma cosa c&amp;rsquo;era di giusto nel massacro a cui stava partecipando?&lt;/p&gt;\r\n&lt;p&gt;Fu allora che Chijioke url&amp;ograve;.&lt;/p&gt;\r\n&lt;p&gt;Camila gli aveva lanciato contro un&amp;rsquo;onda d&amp;rsquo;oscurit&amp;agrave;, alimentata dalla menzogna e dalla seduzione dei sensi. Il Martello della Verit&amp;agrave; brillava in risposta, cercando di disperdere la nebbia illusoria, ma Chijioke era in difficolt&amp;agrave;. Camila sussurrava parole che scavavano nel cuore, tentando di piegarlo con il dubbio.&lt;/p&gt;\r\n&lt;p&gt;Ma Chijioke non cedette. Piuttosto che lasciarsi ingannare, alz&amp;ograve; il martello sopra la testa e lo scagli&amp;ograve; con tutta la forza contro l&amp;rsquo;incantesimo. La verit&amp;agrave; esplose in una luce accecante, ma l&amp;rsquo;energia oscura lo travolse. Camila venne respinta, ma Chijioke cadde in ginocchio, il petto squarciato dalla forza che aveva voluto purificare.&lt;/p&gt;\r\n&lt;p&gt;&amp;laquo;Io&amp;hellip; non mi pento&amp;hellip;&amp;raquo; sussurr&amp;ograve;. &amp;laquo;Finch&amp;eacute; uno di noi resta&amp;hellip; la verit&amp;agrave; vivr&amp;agrave;&amp;hellip;&amp;raquo;&lt;/p&gt;\r\n&lt;p&gt;Fu allora che Shinzo, fermo tra le rovine di quel colpo devastante, guard&amp;ograve; il giovane cadere. Chijioke aveva combattuto fino all&amp;rsquo;ultimo per qualcosa in cui credeva, senza odio, senza vendetta. Per la prima volta, Shinzo si domand&amp;ograve;: E io, per cosa sto combattendo davvero?&lt;/p&gt;\r\n&lt;p&gt;Volt&amp;ograve; lentamente lo sguardo verso Nikolaj, che continuava a colpire senza piet&amp;agrave;. Il suo comandante. Colui che gli aveva dato un motivo per odiare&amp;hellip; ma mai uno per vivere.&lt;/p&gt;\r\n&lt;p&gt;&amp;laquo;Basta&amp;hellip;&amp;raquo; mormor&amp;ograve;.&lt;/p&gt;\r\n&lt;p&gt;Poi url&amp;ograve;: &amp;laquo;BASTA!&amp;raquo;&lt;/p&gt;\r\n&lt;p&gt;E si lanci&amp;ograve; contro Budi, salvando Jambalaya da un colpo alle spalle. I due rotolarono a terra, e Shinzo si rialz&amp;ograve; al fianco degli eroi del bene. La seconda redenzione era compiuta.&lt;/p&gt;\r\n&lt;p&gt;Ma il prezzo era stato altissimo. Chijioke non si rialzava&lt;/p&gt;', 27, 3, 'Una seconda redenzione', 0, 14, 0, '&lt;p&gt;Salvare su un file una serie di parole, andando a capo tra l&#039;una e l&#039;altra.&lt;/p&gt;\r\n&lt;p&gt;Leggere il file con readlines, salvando su lista le varie parole.&amp;nbsp;&lt;/p&gt;\r\n&lt;p&gt;Estrarre un numero casuale tra 0 ed il numero di parole contenuto nella lista.&lt;/p&gt;\r\n&lt;p&gt;Chiedere all&#039;utente di scrivere una parola tra quelle contenute nel file. Controllare se la parola scritta dall&#039;utente &amp;egrave; uguale a quella nella posizione estratta casualmente. Se lo &amp;egrave; dare un messaggio all&#039;utente di vittoria, altrimenti dire che ha sbagliato ed ha perso.&lt;/p&gt;', 3),
(68, '55663b81-41fb-4908-a2ff-e27181f2f9d3', '&lt;p class=&quot;MsoNormal&quot;&gt;La battaglia &amp;egrave; conclusa. Gli eroi del bene hanno vinto. E qui si conclude anche il percorso per imparare la programmazione di base con Python. Come ultimo esercizio un gioco per rilassarsi, dopo le fatiche della battaglia:&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Creare un programma Python che simula una semplificazione del gioco del BlackJack o 21. Il gioco funziona in questo modo: il banco ha 2 carte e il giocatore ha altre 2 carte (casuali) di un mazzo di carte francesi (quelle con cuori,quadri,fiori,picche). Vince chi dei due si avvicina di pi&amp;ugrave; al numero 21 sommando i valori delle carte. Chi va oltre il 21 perde. Il giocatore, se ha un punteggio basso, pu&amp;ograve; richiedere altre carte, una alla volta. Se, allo stop del giocatore, il banco ha un punteggio inferiore, deve estrarre altre carte. Per la creazione del gioco vanno usate funzioni e moduli per separare le funzioni dal programma principale. Ci servir&amp;agrave; una funzione che estragga numeri casuali da 2 a 11 (le figure valgono 10, l&#039;asso 11). Ci serve una seconda funzione per distribuire le carte iniziali (quindi assegnare 2 numeri casuali al banco e al giocatore), che possiamo inserire in 2 liste&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ci serve poi una terza funzione per aggiungere una carta quando il giocatore o il banco lo richieda. Una quarta funzione utile potrebbe fare la somma dei punteggi di una lista di carte. Una quinta funzione per controllare se abbiamo vinto o se ha vinto il banco. Il programma principale deve richiamare la funzione per distribuire le carte iniziali, quindi chiedere al giocatore con un ciclo se vuole aggiungere carte alla mano oppure no. Se dice di si, richiamiamo la funzione per aggiungere una carta alla sua mano. Se dice di no, la mano passa al banco, quindi richiamiamo la funzione per aggiungere carte al banco fino a che il totale dei punti del banco &amp;egrave; inferiore al totale dei punti del giocatore. Alla fine del ciclo controlliamo chi ha vinto con l&#039;apposita funzione.&lt;/p&gt;', 0, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;img src=&quot;/assets/images/Quest/Editor/quest_a4cee784-c7d5-4558-ba91-1f4dcf3a6373/imported_6a43a750e61f34.91289676_exercise_editor_6a341d388b2e79.04622284.jpg&quot; alt=&quot;&quot;&gt;Il campo di battaglia era un caos di ghiaccio, fuoco e luce. I colpi riecheggiavano tra le pareti della base canadese, mentre i due schieramenti lottavano fino allo stremo. Ma ora l&amp;rsquo;equilibrio era cambiato. Con &lt;strong&gt;Aisha&lt;/strong&gt; e &lt;strong&gt;Shinzo Hanzei&lt;/strong&gt; passati dalla parte del bene, gli eroi malvagi cominciavano a vacillare.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Strogar&lt;/strong&gt;, affiancato da &lt;strong&gt;Shinzo&lt;/strong&gt; e &lt;strong&gt;Jambalaya&lt;/strong&gt;, incalzava &lt;strong&gt;Nikolaj&lt;/strong&gt;. Il comandante oscuro combatteva con furia glaciale, la sua spada fendendo l&amp;rsquo;aria in fendenti devastanti, ma il potere della &lt;strong&gt;Ascia Spaccaterra&lt;/strong&gt;, le frecce dell&amp;rsquo;&lt;strong&gt;Arco d&amp;rsquo;Avorio&lt;/strong&gt; e la velocit&amp;agrave; fulminea di Shinzo lo mettevano in seria difficolt&amp;agrave;.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Intorno a loro, uno a uno, gli eroi malvagi cadevano: &lt;strong&gt;Camila&lt;/strong&gt;, abbattuta da Aisha; &lt;strong&gt;Budi&lt;/strong&gt;, immobilizzato da Nuliajuk; &lt;strong&gt;Lachlan&lt;/strong&gt;, costretto in ginocchio da Strogar con un colpo possente che fece tremare la terra.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Isabela&lt;/strong&gt;, isolata e furiosa, cerc&amp;ograve; di vendicare i compagni, ma venne bloccata da &lt;strong&gt;Nuliajuk&lt;/strong&gt; e &lt;strong&gt;Aisha&lt;/strong&gt; in un combattimento intenso e senza esclusione di colpi. Isabela lottava con rabbia cieca, ma l&amp;rsquo;alleanza rinnovata degli eroi del bene era troppo forte. Venne sconfitta, ma non uccisa: il suo sguardo rimaneva gelido, colmo di disprezzo.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Intanto, il duello centrale giungeva al culmine.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Nikolaj, ferito, rallentava. Un colpo potente di Strogar lo fece barcollare. Jambalaya scocc&amp;ograve; una freccia che lo costrinse a indietreggiare. Fu &lt;strong&gt;Emily&lt;/strong&gt; a chiudere il cerchio.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Senza poteri, ma con un coraggio che brillava come luce pura, &lt;strong&gt;Emily&lt;/strong&gt; si mise davanti a Nikolaj, incrociando il suo sguardo. Lui alz&amp;ograve; la lama, ma esit&amp;ograve;: non per piet&amp;agrave;, ma per la confusione. Perch&amp;eacute; quella ragazza, priva di armi, lo sfidava con la forza del cuore, non con la violenza.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Quell&amp;rsquo;istante bast&amp;ograve;.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Strogar cal&amp;ograve; l&amp;rsquo;Ascia Spaccaterra con tutta la forza della giustizia. La lama di Nikolaj si spezz&amp;ograve;. Il colosso malvagio cadde in ginocchio, poi a terra.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&lt;strong&gt;Il comandante supremo degli eroi del male era caduto.&lt;/strong&gt;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Con la sua disfatta, gli altri furono definitivamente sconfitti e incatenati. Nelle profondit&amp;agrave; della base, vennero riattivate le &lt;strong&gt;teche di cristallo&lt;/strong&gt;, antiche prigioni progettate per contenere poteri immensi. Una a una, le teche si chiusero: su Isabela, Camila, Budi, Lachlan... Tutti loro tornarono al sonno progettato dal Creatore.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Ma mancava una guardiana.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Fu allora che &lt;strong&gt;Emily&lt;/strong&gt; si fece avanti.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&amp;laquo;Qualcuno dovr&amp;agrave; restare con loro. Se un giorno dovessero risvegliarsi&amp;hellip; ci dovr&amp;agrave; essere qualcuno a dare l&amp;rsquo;allarme. Qualcuno che ricordi.&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Gli altri protestarono, ma lei sorrise con dolcezza.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;&amp;laquo;Ho fatto la mia scelta. Non ho poteri, ma ho il cuore. E questo &amp;egrave; il mio compito.&amp;raquo;&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Emily entr&amp;ograve; nella stanza delle teche, chiudendo le porte dietro di s&amp;eacute;. L&amp;rsquo;ultima teca si chiuse su di lei, non come prigioniera, ma come custode eterna.&lt;/p&gt;\r\n&lt;div class=&quot;MsoNormal&quot; style=&quot;text-align: center;&quot; align=&quot;center&quot;&gt;&lt;hr align=&quot;center&quot; size=&quot;2&quot; width=&quot;100%&quot;&gt;&lt;/div&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Il silenzio cal&amp;ograve;.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Il ghiaccio torn&amp;ograve; a regnare sulla base canadese. Gli eroi del male dormivano, sorvegliati dalla luce di Emily. E anche se Klaus, Chijioke ed Emily non camminavano pi&amp;ugrave; al fianco dei compagni, i loro nomi sarebbero ricordati per sempre.&lt;/p&gt;\r\n&lt;p class=&quot;MsoNormal&quot;&gt;Perch&amp;eacute;, alla fine, &lt;strong&gt;la luce aveva prevalso sull&amp;rsquo;oscurit&amp;agrave;.&lt;/strong&gt;&lt;/p&gt;', 28, 3, 'La Fine della Storia', 0, 12, 0, '&lt;p&gt;La battaglia &amp;egrave; conclusa. Gli eroi del bene hanno vinto. E qui si conclude anche il percorso per imparare la programmazione di base con Python. Come ultimo esercizio un gioco per rilassarsi, dopo le fatiche della battaglia:&lt;/p&gt;\r\n&lt;p&gt;Creare il gioco del BlackJack: estrarre 2 numeri casuali tra 1 e 11 rappresentanti le carte del banco, estrarre due numeri casuali tra 1 e 11 rappresentanti le carte del giocatore. Inserire i primi due numeri in una prima lista con append ed i secondi due numeri in una seconda lista con append. Comunicare le liste al giocatore e chiedere se vuole pescare una nuova carta. Se dice &quot;si&quot; allora aggiungere un terzo numero casuale alla listad dei numeri del giocatore. Se dice &quot;no&quot; non fare nulla.&lt;/p&gt;\r\n&lt;p&gt;A questo punto si sommano i valori della lista del banco e quelli della lista del giocatore. Se il giocatore super il 21 allora ha perso. Se la somma del giocatore &amp;egrave; inferiore a quella del banco allora ha perso. Se la sua somma &amp;egrave; uguale a quella del banco ha comunque perso. Se la somma del giocatore &amp;egrave; maggiore a quella del banco e minore o uguale a 21 allora comunicare al giocatore che ha vinto.&lt;/p&gt;', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizio_domande`
--

CREATE TABLE `ct_esercizio_domande` (
  `id_ese_dom` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `fk_domanda` int NOT NULL,
  `fk_studente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_esercizi_quest`
--

CREATE TABLE `ct_esercizi_quest` (
  `id_ese_quest` int NOT NULL,
  `fk_capitolo` int NOT NULL,
  `fk_esercizio` int NOT NULL,
  `progressivo` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dump dei dati per la tabella `ct_materiali`
--

INSERT INTO `ct_materiali` (`id_materiale`, `nome_materiale`, `descrizione`, `link_materiale`, `fk_argomento`, `fk_utente`) VALUES
(5, 'Slide Ricorsione', 'Slide sulle basi della ricorsione in Python', '/assets/materiali/Ricorsione/6a32b523c23633.68498945_Ricorsione.pptx', 31, 1),
(6, 'Slide intro SQL - DDL e DML', 'Slide che introducono il linguaggio SQL e parlano di linguaggio DDL e DML, con create, alter, insert, update..', '/assets/materiali/SQL_DDL_e_DML/6a32b56b2fca16.16757160_8_-_LinguaggioSQL.pptx', 32, 1),
(7, 'Slide SQL Avanzato', 'Slide su Query Language avanzato con raggruppamenti e sottoquery', '/assets/materiali/SQL_Select/6a32b593a21b50.43963350_10_-_Query_Language_Avanzato.pptx', 33, 1),
(8, 'Slide SQL Select', 'Slide su come effettuare query SELECT di base, con clausola where e join, ma senza raggruppamenti', '/assets/materiali/SQL_Select/6a32b5ad1e5de9.79814070_9_-_Query_Language.pptx', 33, 1),
(9, 'Slide Dizionari Python', 'Slide che indicano come usare la struttura dati dizionario in Python', '/assets/materiali/Python_Strutture_dati__liste__insiemi__dizionari/6a32b5d3134db4.58102210_Dizionari.pptx', 29, 1),
(10, 'Slide Insiemi Python', 'Slide che indicano come usare tuple ed insiemi in Python', '/assets/materiali/Python_Strutture_dati__liste__insiemi__dizionari/6a32b5eb644fc9.98979995_Tuple_Insiemi.pptx', 29, 1),
(11, 'Slide Liste Python', 'Slide che racchiudono le istruzioni per la gestione delle liste in Python', '/assets/materiali/Python_Strutture_dati__liste__insiemi__dizionari/6a32b601bc9bc0.07889268_Le_Liste.pptx', 29, 1),
(12, 'Slide Matrici, pila, coda Python', 'Slide su matrici, pila e coda in Python. Alcune funzionalità aggiuntive delle liste', '/assets/materiali/Python_Strutture_dati__liste__insiemi__dizionari/6a32b6210d9896.33830500_Matrici_Pila_Coda.pptx', 29, 1),
(13, 'Slide Funzioni Python', 'Slide sulle funzioni in Python', '/assets/materiali/Python_Funzioni_e_Moduli/6a32b6399d9a56.34835765_Le_Funzioni.pptx', 28, 1),
(14, 'Slide Moduli Python', 'Slide sull&#039;utilizzo dei moduli in Python', '/assets/materiali/Python_Funzioni_e_Moduli/6a32b64ce7f960.19483947_I_Moduli.pptx', 28, 1),
(15, 'Slide File Python', 'Slide su input e output su file in Python: open, write, read, file di testo e file binari. File strutturati e funzione seek', '/assets/materiali/Python_file/6a32b66af0f5f0.52275971_Python_Input_Output_su_File.pptx', 27, 1),
(16, 'Slide Basi di Python', 'Slide con le istruzioni base di Python: selezione e cicli', '/assets/materiali/Python__programmazione_strutturata/6a32b685c3bd23.20181440_Basi_Python.pptx', 30, 1),
(17, 'Slide Eccezioni in Python', 'Slide che racchiudono le informazioni per la gestione delle eccezioni di base in Python', '/assets/materiali/Python__programmazione_strutturata/6a32b69b4ff346.74584096_Le_Eccezioni.pptx', 30, 1),
(18, 'Slide Stringhe in Python', 'Slide che racchiudono tutte le funzionalità di base per le stringhe in Python', '/assets/materiali/Python__programmazione_strutturata/6a32b6aec407c9.75610789_Le_Stringhe.pptx', 30, 1),
(19, 'Slide Progettare Database', 'Slide sui passi per progettare un db: dall&#039;analisi ai modelli concettuale e logico, fino alla progettazione fisica. File XML e JSON', '/assets/materiali/Progettare_database/6a32b6dbf3e7f1.58621756_2_-_ProgettareDatabase.pptx', 24, 1),
(20, 'Slide Files in PHP', 'Slide sull&#039;utilizzo di files in PHP: come aprire, leggere, scrivere da file. Gestione della concorrenza con semplici semafori. Caricare un file da form e salvarlo su disco.', '/assets/materiali/PHP__uso_dei_files/6a32b6faecf728.80508362_4L_-_PHP_files.pptx', 23, 1),
(21, 'Slide Sessioni PHP', 'Slide sull&#039;utilizzo di sessioni e cookies per la persistenza dei dati in PHP', '/assets/materiali/PHP__cookies_e_sessioni/6a32b71d5ce826.81402984_5L_-_PHP_sessioni.pptx', 20, 1),
(22, 'Slide Intro a PHP', 'Slide introduttive al linguaggio PHP: come funziona, elementi di base, gli array indicizzati ed associativi', '/assets/materiali/PHP__introduzione__variabili__funzioni__array/6a32b7454b3343.35921898_1L_-_IntroPHP.pptx', 22, 1),
(23, 'Slide PHP Variabili e funzioni', 'Slide sull&#039;utilizzo di funzioni, variabili particolari, include di file', '/assets/materiali/PHP__introduzione__variabili__funzioni__array/6a32b766447d33.74168652_2L_-_PHP_variabili_e_funzioni.pptx', 22, 1),
(24, 'Slide PHP Dati da Form', 'Slide che spiegano come ottenere dati da form in PHP tramite GET o POST', '/assets/materiali/PHP__Dati_da_Form/6a32b78a9e5ef8.65317338_3L_-_PHP_dati_da_form.pptx', 21, 1),
(25, 'Slide PHP e MySQL', 'Slides su come connettersi a database MySQL da PHP ed eseguire delle query', '/assets/materiali/PHP__Connessione_a_DB_e_Query/6a32b7ad4876d3.00922199_6L_-_PHP_ConnessioneDB.pptx', 19, 1),
(26, 'Slide Linguaggi Compilati ed Interpretati', 'Slide sui linguaggi compilati o interpretati, con vantaggi e svantaggi degli uni e degli altri', '/assets/materiali/Linguaggi_Compilati_ed_Interpretati/6a32b7d1ba70d0.13994937_Linguaggio_compilato_o_Interpretato.pptx', 18, 1),
(27, 'Slide Problemi ed Algoritmi', 'Le slide affrontano l&#039;introduzione alla programmazione spiegando cosa siano gli algoritmi, i linguaggi formali, come affrontare e risolvere i problemi', '/assets/materiali/Introduzione_alla_Programmazione/6a32b7f4ea8644.04220454_ALGORITMI.pptx', 12, 1),
(28, 'Slide Introduzione a Python', 'Le slide introducono il linguaggio di programmazione Python. C&#039;è un po&#039; di storia, come fare input output, cosa significa che ha la tipizzazione dinamica', '/assets/materiali/Introduzione_a_Python/6a32b81479b802.39274308_Introduzione_al_linguaggio_Python.pptx', 10, 1),
(29, 'Slide Intro ai Database', 'Slide con un&#039;introduzione generale alle basi di dati ed alle loro proprietà', '/assets/materiali/Introduzione_ai_database/6a32b831538a42.09708334_1_-_IntroDatabase.pptx', 11, 1),
(30, 'Slide CSS', 'Slide sull&#039;utilizzo di CSS, in particolare CSS2, con cenni su CSS3', '/assets/materiali/HTML_e_CSS/6a32b8539b0167.84475265_CSS_-_2.0.pptx', 9, 1),
(31, 'Slide HTML di base', 'Slide di ripasso su tutti gli elementi fondamentali dell&#039;HTML', '/assets/materiali/HTML_e_CSS/6a32b873c7a8d9.97089384_HTML_-_Ripasso_-_2.0.pptx', 9, 1),
(32, 'Slide HTML Form', 'Slide incentrate sulla definizione di Form in HTML', '/assets/materiali/HTML_e_CSS/6a32b888ee7cf9.46509891_HTML_-_Forms_-_2.0.pptx', 9, 1),
(33, 'Slide Cicli di base', 'Slide su cicli definiti ed indefiniti (for e while)', '/assets/materiali/Diagrammi_di_flusso/6a32b8b7e838d8.40001896_CICLI.pptx', 6, 1),
(34, 'Slide Diagrammi a Blocchi', 'Slide sui diagrammi a blocchi (senza selezione)', '/assets/materiali/Diagrammi_di_flusso/6a32b8e6457b69.71674041_Codifichiamo_gli_algoritmi.pptx', 6, 1),
(35, 'Slide Diagrammi a Blocchi - Selezione', 'Slide sulla selezione nei diagrammi a blocchi', '/assets/materiali/Diagrammi_di_flusso/6a32b9063c29a2.34382906_La_selezione.pptx', 6, 1),
(36, 'Slide Associazioni nei diagrammi UML', 'Slide su tipi e cardinalità delle associazioni nei diagrammi UML delle classi', '/assets/materiali/Diagrammi_delle_classi_UML/6a32b923ab72b1.94953330_5_-_DiagrammiUML_-_associazioni.pptx', 5, 1),
(37, 'Slide Chiavi nei Diagrammi UML', 'Slide sulle chiavi nei diagrammi UML delle classi: primarie, esterne, candidate, composte, artificiali', '/assets/materiali/Diagrammi_delle_classi_UML/6a32b93bc67111.25837066_4_-_DiagrammiUML_-_chiavi.pptx', 5, 1),
(38, 'Slide Classi ed Attributi (UML)', 'Slide con spiegazione classi e tipi di attributo nei diagrammi delle classi UML', '/assets/materiali/Diagrammi_delle_classi_UML/6a32b95c4f1279.07827912_3_-_DiagrammiUML_-_Classi_ed_Attributi.pptx', 5, 1),
(39, 'Slide da UML a Modello Logico', 'Slide che spiegano come raffinare il modello concettuale UML per passare al modello logico (aggiunta chiavi esterne)', '/assets/materiali/Diagrammi_delle_classi_UML/6a32b975c91bf1.44506133_6_-_Dal_diagramma_UML_al_modello_logico.pptx', 5, 1),
(40, 'Slide Bootstrap', 'Slide che spiegano come utilizzare la libreria Bootstrap per creare una grafica responsive, omogenea e bella per siti web e web applications', '/assets/materiali/Bootstrap__una_libreria_per_il_web/6a32b992a79e31.18730453_Bootstrap.pptx', 3, 1),
(41, 'Slide Algebra Relazionale e Join', 'Slide che spiegano cosa sono i vari elementi dell&#039;algebra relazionale: selezione, proiezione, prodotto cartesiano, join...', '/assets/materiali/Algebra_Relazionale_nei_DB/6a32b9b7a5d5a0.26546872_7_-_Join.pptx', 1, 1);

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
(4, 'Informatica', 'c2c6e2ff-989d-48ec-b91a-a172e47b7880');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ct_personaggi`
--

INSERT INTO `ct_personaggi` (`id_personaggio`, `uuid`, `nome_personaggio`, `immagine`, `vita_iniziale`, `descrizione`, `color`, `bordercolor`, `mana_iniziale`, `fk_classe`, `img_senza_sfondo`, `originale`) VALUES
(1, NULL, 'Aionios', '/assets/images/Personaggi/character_69ce805aea4733.44071893.png', 5, 'Aionios è un anziano saggio, di età non stimabile, proveniente dall\'India. Avvolto in un\'aura di mistero, possiede una conoscenza profonda delle arti arcane, della filosofia e delle antiche tradizioni. Vive in armonia con la natura e spesso medita per giorni interi, cercando risposte nei regni spirituali. È appassionato di astronomia e musica classica indiana, utilizzando il sitar per accompagnare i suoi momenti di riflessione. Con il suo comportamento calmo e una voce che trasmette saggezza, Aionios è una guida spirituale e morale per gli eroi, sempre pronto a dispensare consigli o a svelare enigmi celati nelle pieghe del tempo.', '#808080', '#efefef', 6, 1, '/assets/images/Personaggi/character_69ce805b01de73.68479614.png', 1),
(2, NULL, 'Aisha', '/assets/images/Personaggi/character_69ce808df3d000.22164240.png', 4, 'Aisha è una ragazza di 17 anni, proveniente dalla Costa d\'Avorio, nota per la sua incredibile agilità e destrezza. Parte del gruppo degli eroi malvagi, Aisha combina la sua grazia felina con una mente astuta e una determinazione inflessibile. Cresciuta in un ambiente che l\'ha temprata, è abile nel combattimento corpo a corpo e nelle missioni furtive. Sebbene il suo cuore sia stato corrotto dal desiderio di potere, il suo passato rivela una profondità che potrebbe celare un conflitto interiore. Elegante e letale, Aisha è una figura che incute rispetto e timore nei suoi avversari.', '#000000', '#000000', 8, 1, '/assets/images/Personaggi/character_69ce808e087186.42143939.png', 1),
(3, NULL, 'Budi', '/assets/images/Personaggi/character_69ce82b760bcd4.91309496.jpg', 8, 'Budi è un ragazzo di 18 anni proveniente dalla Thailandia, noto per la sua natura oscura e per la sua impressionante fame. Nonostante la sua corporatura tarchiata, che lo rende piuttosto imponente, è un combattente temibile, grazie alla sua abilità unica: il suo corpo è elastico come la gomma, permettendogli di sfuggire a colpi e attacchi con una sorprendente agilità. La sua passione per il cibo è leggendaria, e spesso può essere visto mangiare a dismisura, ma il suo appetito insaziabile non è solo un vizio: è anche una forma di energia che alimenta il suo potere. Membro degli eroi del male, Budi è cinico e opportunista, pronto a usare la sua forza per distruggere chiunque osi sfidare la sua volontà. Il suo umore può variare rapidamente, ma quando si tratta di portare a termine una missione, è determinato e spietato.', '#4a7915', '#67a31f', 5, 1, '/assets/images/Personaggi/character_69ce82b765c091.56258820.png', 1),
(4, NULL, 'Camila', '/assets/images/Personaggi/character_69ce830b866289.42179879.jpg', 4, 'Camila è una ragazza di 18 anni proveniente dall\'Argentina, una delle eroine malvagie. Astuta e determinata, è una manipolatrice nata, in grado di controllare le menti altrui con il suo potere, piegandole alla propria volontà. Cresciuta in un ambiente difficile, il suo desiderio di potere e autonomia l\'ha portata a scegliere il lato oscuro, convinta che solo la forza e l\'astuzia possano garantire il suo posto nel mondo. Elegante e carismatica, Camila è una stratega formidabile e sa sfruttare ogni debolezza del nemico a suo vantaggio. Nonostante la sua natura spietata, conserva un’intelligenza affilata e una profonda ambizione che la rendono una figura temibile e complessa.', '#4287dd', '#06388a', 6, 1, '/assets/images/Personaggi/character_69ce830b8b4e02.48398607.png', 1),
(5, NULL, 'Chijioke', '/assets/images/Personaggi/character_69ce835b58bb21.57713415.jpg', 5, 'Chijioke è un ragazzo di 17 anni proveniente dalla Nigeria, un eroe del bene noto per la sua calma risolutezza e la sua straordinaria intelligenza. Alto e snello, con capelli ricci scuri e occhi vivaci, Chijioke è profondamente legato alla sua terra natale e alle sue tradizioni. È appassionato di musica, in particolare del suono delle percussioni tradizionali, e trascorre il suo tempo libero suonando i tamburi o raccontando storie intorno al fuoco. Chijioke ama anche il calcio, unendo le sue doti atletiche alla strategia, e porta questo spirito di squadra anche nelle sue avventure. Oltre a ciò, ha un grande interesse per la natura e spesso si perde a contemplare i paesaggi selvaggi, raccogliendo erbe curative e studiando il mondo naturale. La sua mente analitica e il suo cuore generoso lo rendono una guida preziosa per il gruppo, sempre pronto a sacrificarsi per il bene degli altri.', '#13f01b', '#aee5b0', 5, 1, '/assets/images/Personaggi/character_69ce835b5d7387.92847611.png', 1),
(6, NULL, 'Emily', '/assets/images/Personaggi/character_69ce839443c0f3.60983577.jpg', 5, 'Emily, 17 anni, è una ragazza canadese che rappresenta gli ideali degli eroi del bene con la sua determinazione e il suo spirito gentile. Con i suoi capelli biondo cenere e occhi azzurri come i laghi del suo paese natale, Emily è un simbolo di grazia e forza. Appassionata di pattinaggio su ghiaccio fin da bambina, le sue movenze riflettono l\'eleganza e la precisione di questo sport. Durante le battaglie, queste qualità si traducono in una straordinaria agilità e capacità di anticipare i movimenti degli avversari. Emily è coraggiosa e altruista, sempre pronta a mettere gli altri al primo posto. La sua passione per la natura canadese e per il ghiaccio la rende particolarmente legata alle sfide che coinvolgono il freddo e gli ambienti ostili.', '#0507c9', '#000000', 7, 1, '/assets/images/Personaggi/character_69ce8394486f62.87187658.png', 1),
(7, NULL, 'Isabela', '/assets/images/Personaggi/character_69ce83ecd9fa97.34135328.png', 3, 'Isabela è una ragazza di 16 anni, proveniente dal Brasile. Di natura determinata e ambiziosa, è cresciuta vicino alla foresta amazzonica, sviluppando un legame profondo con la natura e gli animali. È appassionata di arti marziali brasiliane, in particolare della capoeira, e ama danzare la samba, immergendosi nella cultura e nella musica del suo paese. La sua passione per l’avventura e il rischio la porta a volte a compiere scelte estreme.', '#ffbf66', '#d19330', 8, 1, '/assets/images/Personaggi/character_69ce83ece20ea3.50738754.png', 1),
(8, NULL, 'Jambalayah', '/assets/images/Personaggi/character_69ce844f394363.26990335.jpg', 4, 'Jambalayah è un ragazzo di 17 anni proveniente dalla Jamaica, con una corporatura atletica e un sorriso contagioso. Cresciuto vicino al mare, ha una profonda passione per il nuoto e la navigazione. Ama la musica reggae, che lo accompagna in ogni momento libero, e spesso suona il tamburo djembe. Jambalayah è avventuroso e coraggioso, sempre pronto a tuffarsi in nuove sfide, ma anche riflessivo e rispettoso delle tradizioni della sua terra. Nonostante le difficoltà, mantiene sempre viva la speranza, guidato dalla convinzione che il bene possa prevalere.', '#4efee0', '#3e847c', 7, 1, '/assets/images/Personaggi/character_69ce844f3e0f31.74925631.png', 1),
(9, NULL, 'Klaus', '/assets/images/Personaggi/character_69ce8496240a59.26169556.png', 6, 'Klaus è un ragazzo di 16 anni, originario della Germania, caratterizzato da un\'indole introversa e riflessiva. Pur preferendo la solitudine, possiede un profondo senso di giustizia che lo ha spinto a unirsi agli eroi del bene. Klaus eccelle nel pensiero strategico, spesso trovando soluzioni innovative ai problemi più complessi. Ama leggere e approfondire la storia e la filosofia, e il suo intuito lo rende un prezioso alleato in battaglia. Nonostante la sua natura riservata, la sua lealtà verso i compagni è incrollabile, e nei momenti di crisi sa dimostrare un coraggio silenzioso ma potente.', '#a8ecff', '#5f9091', 5, 1, '/assets/images/Personaggi/character_69ce84962b3bf2.56273855.png', 1),
(10, NULL, 'Lachlan', '/assets/images/Personaggi/character_69ce85413b9dc2.02231516.jpg', 5, 'Lachlan, detto il Distruttore, è un ragazzo di 18 anni proveniente dall\'Australia, uno degli eroi malvagi più temuti. Dietro al suo aspetto solare e al sorriso ingannevole si cela una personalità spietata e ambiziosa. Lachlan è un amante degli sport estremi, come il surf su onde gigantesche e l’arrampicata su scogliere inaccessibili, attività che hanno affinato la sua forza fisica e la sua destrezza. Tuttavia, il suo vero interesse è l’adrenalina della distruzione: ama sfruttare la sua abilità di manipolare l\'energia cinetica per seminare il caos e piegare gli avversari. Sebbene sia un eccellente stratega, Lachlan si lascia spesso guidare dal desiderio di dimostrare la sua superiorità, rendendolo un avversario tanto astuto quanto pericoloso.', '#f0137e', '#600b5e', 6, 1, '/assets/images/Personaggi/character_69ce854141e0a9.98704522.png', 1),
(11, NULL, 'Nikolaj', '/assets/images/Personaggi/character_69ce858e33f139.09160165.jpg', 7, 'Nikolaj, 19 anni, proveniente dalla gelida Russia, è il leader indiscusso degli eroi del male. La sua presenza è imponente, con occhi di ghiaccio che sembrano scrutare nell’anima, capelli scuri e corti, e una postura sempre eretta, che trasmette autorità e potere. Nikolaj è un maestro della strategia e dell’inganno, capace di manipolare chiunque per raggiungere i suoi scopi. Dotato di un oscuro potere che sembra attingere energia direttamente dalla sua malvagità interiore, è in grado di generare onde d’urto devastanti, controllare l’oscurità e intaccare la volontà altrui. Freddo, spietato e ambizioso, Nikolaj disprezza qualsiasi forma di debolezza e crede che il potere assoluto sia l’unico scopo degno di essere perseguito. Nonostante la sua crudeltà, è anche incredibilmente intelligente e carismatico, attirando a sé gli altri eroi del male con promesse di gloria e dominio. Il suo unico vero interesse, però, è consolidare la sua posizione come sovrano del mondo.', '#8a0606', '#db0606', 7, 1, '/assets/images/Personaggi/character_69ce858e37bd96.86457513.png', 1),
(12, NULL, 'Shinzo Hanzei', '/assets/images/Personaggi/character_69ce861a64f7b7.11640481.jpg', 5, 'Shinzo Hanzei è un giovane giapponese dal carattere ribelle e determinato, segnato da un passato di dolore e ingiustizia che lo ha condotto a schierarsi con gli eroi del male. Appassionato di corse e velocità, vive per l’adrenalina del rischio e si distingue per la sua incredibile rapidità nei movimenti, che lo rende un avversario quasi inafferrabile. Shinzo è un pilota esperto e un combattente astuto, che utilizza la sua velocità sia per attaccare con precisione che per sfuggire ai pericoli. Dietro la sua maschera di freddezza si nasconde un cuore ferito, che ancora lotta contro i demoni del suo passato.', '#7706c2', '#41016b', 7, 1, '/assets/images/Personaggi/character_69ce861a699737.66095323.png', 1),
(13, NULL, 'Strogar', '/assets/images/Personaggi/character_69ce865a76c014.59840190.jpg', 7, 'Strogar, 45 anni, è un enigmatico eroe neutrale, il cui volto segnato da cicatrici racconta una vita di battaglie e difficoltà. Non si è schierato né con il bene né con il male, preferendo osservare il mondo e i suoi conflitti da una posizione esterna. La sua forza fisica è eguagliata solo dalla sua determinazione a mantenere un equilibrio in un mondo costantemente diviso tra luce e oscurità. Appassionato di storia, Strogar conserva una collezione di manoscritti e testi antichi sulla storia dell\'umanità, che aggiorna meticolosamente con gli eventi che vive in prima persona. Nonostante non abbia la saggezza mistica di Aionios, il suo pragmatismo e la sua conoscenza della storia lo rendono una figura rispettata. Strogar è una presenza silenziosa, ma decisiva, capace di influenzare il corso degli eventi con un solo intervento al momento giusto.', '#a2c904', '#5a7003', 5, 1, '/assets/images/Personaggi/character_69ce865a7b3851.98911790.png', 1),
(14, NULL, 'Nuliajuk', '/assets/images/Personaggi/character_69ce85cf74bfa9.89269479.jpg', 5, 'Nuliajuk è una ragazza di 16 anni proveniente dall\'Alaska, una fiera Inuit e una delle eroine del bene. La sua connessione con la natura è profonda, ed è cresciuta imparando le tradizioni del suo popolo, tra cui la pesca tra i ghiacci. Abile e determinata, ha un\'incredibile resistenza e una forza interiore che le permette di affrontare le sfide più dure. Il suo spirito indomito e la sua saggezza sono fondamentali per il gruppo, e il suo legame con gli elementi naturali le conferisce poteri unici legati al ghiaccio e alla neve. Oltre alla pesca, Nuliajuk ama esplorare il paesaggio selvaggio che la circonda, trovando sempre un senso di pace e forza nella solitudine. La sua lealtà verso i suoi compagni è incrollabile, e combatterà sempre per proteggere chi ama, utilizzando le sue abilità per mantenere l\'armonia tra il mondo umano e quello naturale.', '#f7bc0a', '#b08505', 7, 1, '/assets/images/Personaggi/character_69ce85cf798624.82952706.png', 1);

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
(1, '98a4541a-e0cb-44c5-a9f3-0d2d1ed37f6e', 'Aionios Aqua', '/assets/images/Personalizzazioni/Capelli/Aionios_aqua_20260614_132520_3cae6dd9.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(2, 'fd441f59-9486-4dca-93db-01a860465ef8', 'Aionios Azzurro', '/assets/images/Personalizzazioni/Capelli/Aionios_cyan_20260614_132554_9cc09b4c.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(3, '9712f71d-ca61-48a4-884f-1374e287022b', 'Aionios Blue', '/assets/images/Personalizzazioni/Capelli/Aionios_blue_20260614_132615_52674dc4.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(4, 'a5d59677-576c-4998-aae5-d0e655bb306a', 'Aionios Orange', '/assets/images/Personalizzazioni/Capelli/Aionios_orange_20260614_132635_fd7a69c8.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(5, '8c41d0f7-40c6-4833-a442-93a5de1bd929', 'Aionios Pink', '/assets/images/Personalizzazioni/Capelli/Aionios_pink_20260614_132654_769706d1.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(6, 'bf34600d-1c95-4d43-bbc1-ad66efd6b52a', 'Aionios Red', '/assets/images/Personalizzazioni/Capelli/Aionios_red_20260614_132718_b921ea68.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(7, 'ba7647f2-ab5f-48f0-a9b9-4ef844194b52', 'Aionios Verde', '/assets/images/Personalizzazioni/Capelli/Aionios_green_20260614_132740_0aa92775.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(8, 'a15e7439-a929-44e5-ac64-4edf3064947b', 'Aionios Yellow', '/assets/images/Personalizzazioni/Capelli/Aionios_yellow_20260614_132805_70cda3a7.png', 'Capelli', 220, 1, 1, NULL, 1, '', '', NULL),
(9, '94081967-79b5-4cc7-932b-83997ae9ea1d', 'Aisha Blonde', '/assets/images/Personalizzazioni/Capelli/Aisha_blonde_20260615_075610_2c9cfa70.png', 'Capelli', 200, 2, 1, NULL, 1, '', '', NULL),
(10, '064729ff-3320-4cfc-abf8-e1b8305c317b', 'Aisha Blue', '/assets/images/Personalizzazioni/Capelli/Aisha_blue_20260615_075634_2ebc16e0.png', 'Capelli', 200, 2, 1, NULL, 1, '', '', NULL),
(11, '58b45ca3-6efc-44b6-bc3f-e160166a7f89', 'Aisha Green', '/assets/images/Personalizzazioni/Capelli/Aisha_green_20260615_075730_8c3e90c4.png', 'Capelli', 200, 2, 1, NULL, 1, '', '', NULL),
(12, 'f0aaae31-60ab-44e8-bee4-b4581c0edabc', 'Aisha Light Blue', '/assets/images/Personalizzazioni/Capelli/Aisha_cyan_20260615_075756_f1db3bc5.png', 'Capelli', 200, 2, 1, NULL, 1, '', '', NULL),
(13, 'e70d6c47-0dc9-4d71-9137-2cfd6179bdd6', 'Aisha Pink', '/assets/images/Personalizzazioni/Capelli/Aisha_pink_20260615_075819_aaa3fce8.png', 'Capelli', 200, 2, 1, NULL, 1, '', '', NULL),
(14, 'a6d8845d-9c7c-452e-ad96-cfd957d08769', 'Aisha Red', '/assets/images/Personalizzazioni/Capelli/Aisha_red_20260615_075840_d37fb4e0.png', 'Capelli', 200, 2, 1, NULL, 1, '', '', NULL),
(15, 'b5b2dcf5-d202-415d-8db3-47c4781d2a55', 'Aisha White', '/assets/images/Personalizzazioni/Capelli/Aisha_white_20260615_075903_7e2d4789.png', 'Capelli', 250, 2, 1, NULL, 1, '', '', NULL),
(16, 'd4ac2646-b88b-4471-acec-b4a14a214afb', 'Budi Black', '/assets/images/Personalizzazioni/Capelli/Budi_black_20260615_075930_918bae08.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(17, '481720d2-859c-4d58-9b17-c5b1bf0fe846', 'Budi Blonde', '/assets/images/Personalizzazioni/Capelli/Budi_blonde_20260615_075952_10284528.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(18, '17447997-5650-4c65-8251-15c26e3259f7', 'Budi Blue', '/assets/images/Personalizzazioni/Capelli/Budi_blu_20260615_080015_36c40a07.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(19, '239ab7c2-3f7c-4512-a41d-9d6bf9419af6', 'Budi Green', '/assets/images/Personalizzazioni/Capelli/Budi_green_20260615_080041_f3e1e342.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(20, '6b697672-2383-47e9-843d-aeef2d281b1a', 'Budi Light Blue', '/assets/images/Personalizzazioni/Capelli/Budi_cyan_20260615_080101_6712a330.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(21, '1224fa6e-e367-4b8b-956f-d2e4f40c6de9', 'Budi Pink', '/assets/images/Personalizzazioni/Capelli/Budi_pink_20260615_080124_fdae0ecc.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(22, '16938149-d4d1-4ac3-8c54-f651e3c2d597', 'Budi Red', '/assets/images/Personalizzazioni/Capelli/Budi_red_20260615_080145_62bb82be.png', 'Capelli', 200, 3, 1, NULL, 1, '', '', NULL),
(23, 'f8aa8bfa-b40b-4c5d-8064-4d9d81a33528', 'Budi White', '/assets/images/Personalizzazioni/Capelli/Budi_old_20260615_080207_28a9aa65.png', 'Capelli', 250, 3, 1, NULL, 1, '', '', NULL),
(24, 'cf9d42ca-904d-4fb7-bdf2-05c00c0b8b80', 'Camila Azzurra', '/assets/images/Personalizzazioni/Capelli/Camila_cyan_20260615_080231_359deff5.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(25, '3e6b9cda-387a-4854-a715-df43ea3e2a53', 'Camila Black', '/assets/images/Personalizzazioni/Capelli/Camila_black_20260615_080258_f189df26.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(26, 'b756aa35-31b9-44b2-a130-02c974ef97ad', 'Camila Blonde', '/assets/images/Personalizzazioni/Capelli/Camila_blonde_20260615_080317_5d3cbe76.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(27, '90a068ef-280d-41c3-ba90-dae0439ee23c', 'Camila Blue', '/assets/images/Personalizzazioni/Capelli/Camila_blue_20260615_080336_551b079e.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(28, '7a52851c-3d72-4628-9b9d-40c0c49396f2', 'Camila Green', '/assets/images/Personalizzazioni/Capelli/Camila_green_20260615_080400_838e1104.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(29, '9c7709f7-f4f2-4af1-b466-700e1903cb6c', 'Camila Orange', '/assets/images/Personalizzazioni/Capelli/Camila_orange_20260615_080423_110f790e.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(30, '85033e43-80d9-4903-aec0-7dac61d7c63f', 'Camila Pink', '/assets/images/Personalizzazioni/Capelli/Camila_pink_20260615_080446_0387f4d4.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(31, 'bf9eb1e2-d814-4e0e-b386-1e31d8c76110', 'Camila Red', '/assets/images/Personalizzazioni/Capelli/Camila_red_20260615_080512_28ce830f.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(32, '9150cbdb-2418-45ff-9d11-8f7d615d44ec', 'Camila White', '/assets/images/Personalizzazioni/Capelli/Camila_white_20260615_080533_f5d2be73.png', 'Capelli', 200, 4, 1, NULL, 1, '', '', NULL),
(33, '555f544d-e6ca-4b5d-92ba-430f0fb18445', 'Chijioke Black', '/assets/images/Personalizzazioni/Capelli/Chijioke-Black_20260615_080557_70d86eee.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(34, 'b38ba971-7885-4b3d-a4bf-f511e6e8440e', 'Chijioke Blonde', '/assets/images/Personalizzazioni/Capelli/Chijioke-Blonde_20260615_080618_c87f6da5.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(35, '2da0c098-dd52-41b1-8b68-5e3c4094acab', 'Chijioke Blue', '/assets/images/Personalizzazioni/Capelli/Chijioke-Blue_20260615_080643_1834aac1.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(36, 'de246832-60ba-450f-8f05-c8645d70f51d', 'Chijioke Collana', '/assets/images/Personalizzazioni/Capelli/Chijioke-Collana_20260615_080706_d55ac006.png', 'Capelli', 250, 5, 1, NULL, 1, '', '', NULL),
(37, '5aa7ceef-c4be-4f6f-a1c2-0d1a047c809e', 'Chijioke Green', '/assets/images/Personalizzazioni/Capelli/Chijioke-Green_20260615_080733_4d4f40e7.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(38, 'a5247bf4-96dc-4e23-9d2a-79e72b484d11', 'Chijioke Light Blue', '/assets/images/Personalizzazioni/Capelli/Chijioke-Cyan_20260615_080753_141cccc9.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(39, 'c5402281-509e-45ed-9e94-f6654ec80ccb', 'Chijioke Pink', '/assets/images/Personalizzazioni/Capelli/Chijioke-Pink_20260615_080816_f77da648.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(40, 'ad963230-5c70-4261-9bf8-78145e8637a8', 'Chijioke Red', '/assets/images/Personalizzazioni/Capelli/Chijioke-Red_20260615_080840_962729c9.png', 'Capelli', 200, 5, 1, NULL, 1, '', '', NULL),
(41, '9c73cca0-f74a-44b0-9d56-9dfb4415b9e8', 'Emily Azzurra', '/assets/images/Personalizzazioni/Capelli/Emily_cyan_20260615_080904_afdeb48c.png', 'Capelli', 200, 6, 1, NULL, 1, '', '', NULL),
(42, 'd9a2000a-a91a-4d89-8cb9-9a1b92fdd85e', 'Emily Blue', '/assets/images/Personalizzazioni/Capelli/Emily_blue_20260615_080929_e9a6c8fb.png', 'Capelli', 200, 6, 1, NULL, 1, '', '', NULL),
(43, '9e3ad3d6-7d93-446e-9406-126c52d5c83c', 'Emily Green', '/assets/images/Personalizzazioni/Capelli/Emily_green_20260615_080950_362313d5.png', 'Capelli', 200, 6, 1, NULL, 1, '', '', NULL),
(44, '29b134a8-15f2-487f-93ee-ebfbca0566b1', 'Emily Mesh Azzurre', '/assets/images/Personalizzazioni/Capelli/Emily_sfumata_giallo_cyan_20260615_081016_9b517157.png', 'Capelli', 300, 6, 1, NULL, 1, '', '', NULL),
(45, '7a3762e4-d6fd-4d0a-9b64-a5b7db26e09c', 'Emily Mesh RosseBlu', '/assets/images/Personalizzazioni/Capelli/Emily_sfumatura_rossoblu_20260615_081036_1175a367.png', 'Capelli', 300, 6, 1, NULL, 1, '', '', NULL),
(46, '0423181a-5a4c-4f18-a965-32f02d588dc8', 'Emily Orange', '/assets/images/Personalizzazioni/Capelli/Emily_orange_20260615_081058_418da9db.png', 'Capelli', 200, 6, 1, NULL, 1, '', '', NULL),
(47, '70d02c51-6fca-499e-afd6-a5ca37739a37', 'Emily Pink', '/assets/images/Personalizzazioni/Capelli/Emily_pink_20260615_081120_99909aa3.png', 'Capelli', 200, 6, 1, NULL, 1, '', '', NULL),
(48, '67421d0d-085f-4f07-bf98-eb81723ba895', 'Emily Red', '/assets/images/Personalizzazioni/Capelli/Emily_red_20260615_081142_303e0499.png', 'Capelli', 200, 6, 1, NULL, 1, '', '', NULL),
(49, '06aac316-67c5-4310-85e0-8882797318ff', 'Emily White', '/assets/images/Personalizzazioni/Capelli/Emily_white_20260615_081204_c03b34d9.png', 'Capelli', 220, 6, 1, NULL, 1, '', '', NULL),
(50, 'ecbc8a5f-df52-4f0f-9de4-a657a6ced7eb', 'Isabela Azzurra', '/assets/images/Personalizzazioni/Capelli/Isabela_cyan_20260615_081230_f508ceb3.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(51, 'b900ec9b-7289-4445-9f7a-0efbecac1574', 'Isabela Black', '/assets/images/Personalizzazioni/Capelli/Isabela_black_20260615_081256_a99e86fd.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(52, '80473318-7eb8-4cbd-a43d-6ad7c8e3de13', 'Isabela Blonde', '/assets/images/Personalizzazioni/Capelli/Isabela_blonde_20260615_081320_fb1c6ecf.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(53, 'ab09f97d-c4ba-48b9-b1da-9fd7b1633c7b', 'Isabela Blue', '/assets/images/Personalizzazioni/Capelli/Isabela_blue_20260615_081341_f6a82eec.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(54, '24145b58-50e1-42ae-b2f1-9020f9c072e3', 'Isabela Green', '/assets/images/Personalizzazioni/Capelli/Isabela_green_20260615_081408_0f5ad085.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(55, '5ac80be5-b519-4a4f-9778-b9701ae03523', 'Isabela Old', '/assets/images/Personalizzazioni/Capelli/Isabela_old_20260615_081436_82d530b3.png', 'Capelli', 250, 7, 1, NULL, 1, '', '', NULL),
(56, 'd6231c6c-6d5e-4629-bd4d-1491bbda2b6a', 'Isabela Orange', '/assets/images/Personalizzazioni/Capelli/Isabela_orange_20260615_081457_882b5da1.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(57, '30f37c9b-a492-42d4-8cb4-d1a0ae0006f0', 'Isabela Red', '/assets/images/Personalizzazioni/Capelli/Isabela_red_20260615_081519_43b7e995.png', 'Capelli', 200, 7, 1, NULL, 1, '', '', NULL),
(58, '032ffc14-c71d-44bc-97fa-9d8b879bbe09', 'Jambalaya Aqua', '/assets/images/Personalizzazioni/Capelli/Jambalaya_aqua_20260615_081545_b639ed91.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(59, '655cb61f-1960-4674-9543-bf2d4cc68740', 'Jambalaya Blonde', '/assets/images/Personalizzazioni/Capelli/Jambalaya_biondo_20260615_081605_f998afaf.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(60, '2c8bc0f3-0dac-466a-aa2b-8058ea756e97', 'Jambalaya Blue', '/assets/images/Personalizzazioni/Capelli/Jambalaya_blue_20260615_081627_ed510b32.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(61, '4ef3fa8d-01c6-441f-ae6d-a3e70b8661f6', 'Jambalaya Green', '/assets/images/Personalizzazioni/Capelli/Jambalaya_verde_20260615_081657_44516902.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(62, '1fa1544d-610a-4014-a162-1a09f6f5e395', 'Jambalaya Light Blue', '/assets/images/Personalizzazioni/Capelli/Jambalaya_azzurro_20260615_081722_b5f41519.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(63, '887d1fbd-271e-44e0-a129-26c85fe32afc', 'Jambalaya Pink', '/assets/images/Personalizzazioni/Capelli/Jambalaya_rosa_20260615_081744_74773a4c.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(64, 'b6faddc3-80ef-43c4-accf-53f91a006208', 'Jambalaya Red', '/assets/images/Personalizzazioni/Capelli/Jambalaya_red_20260615_081814_2cc633cc.png', 'Capelli', 200, 8, 1, NULL, 1, '', '', NULL),
(65, 'c69db30e-f279-493b-afc2-78c3f741ad7c', 'Klaus azzurro', '/assets/images/Personalizzazioni/Capelli/Klaus_cyan_20260615_081840_7e6b2bd5.png', 'Capelli', 200, 9, 1, NULL, 1, '', '', NULL),
(66, 'dbcb3c40-8912-426b-acdc-c40c7091f6d6', 'Klaus Blue', '/assets/images/Personalizzazioni/Capelli/Klaus_blue_20260615_081903_e40c57ba.png', 'Capelli', 200, 9, 1, NULL, 1, '', '', NULL),
(67, '61f89bd1-f6bb-4625-9374-4a57917a3459', 'Klaus Green', '/assets/images/Personalizzazioni/Capelli/Klaus_green_20260615_081922_de6d10c6.png', 'Capelli', 200, 9, 1, NULL, 1, '', '', NULL),
(68, 'bbe7be06-e904-47d4-878b-3fa9a2b29a92', 'Klaus Old', '/assets/images/Personalizzazioni/Capelli/Klaus_old_20260615_081949_4e203e6c.png', 'Capelli', 220, 9, 1, NULL, 1, '', '', NULL),
(69, 'ead08594-2a33-4e13-a663-cc5a34ec7323', 'Klaus Orange', '/assets/images/Personalizzazioni/Capelli/Klaus_orange_20260615_082009_e3354921.png', 'Capelli', 200, 9, 1, NULL, 1, '', '', NULL),
(70, 'b8886a06-e151-4828-8c3a-44c0e0af90e0', 'Klaus Pink', '/assets/images/Personalizzazioni/Capelli/Klaus_pink_20260615_082029_3c73f4cf.png', 'Capelli', 200, 9, 1, NULL, 1, '', '', NULL),
(71, '1a736163-5507-4417-bc74-78a55a349ac4', 'Klaus Red', '/assets/images/Personalizzazioni/Capelli/Klaus_red_20260615_082055_536d6767.png', 'Capelli', 200, 9, 1, NULL, 1, '', '', NULL),
(72, '6ab90934-bcd3-4691-8cd6-5cc361d59fc0', 'Klaus Mesh Verdi', '/assets/images/Personalizzazioni/Capelli/Klaus_bluverde_20260615_082122_062fa376.png', 'Capelli', 300, 9, 1, NULL, 1, '', '', NULL),
(73, '20e7561c-6a2f-4676-81dd-fa5390dd5eca', 'Lachlan Azzurro', '/assets/images/Personalizzazioni/Capelli/Lachlan-cyan_20260615_082152_2c2541e6.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(74, '08dbbdc6-7786-47d5-a914-4433c20488dc', 'Lachlan Blonde', '/assets/images/Personalizzazioni/Capelli/Lachlan-yellow_20260615_082215_fde99462.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(75, 'ba7ccac4-05a3-429e-9509-188e587b1088', 'Lachlan Blue', '/assets/images/Personalizzazioni/Capelli/Lachlan-blue_20260615_082236_092d395b.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(76, '6d97cecc-9270-4cc1-98a1-d5bb9a54a9af', 'Lachlan Blue Mesh', '/assets/images/Personalizzazioni/Capelli/Lachlan-blumesh_20260615_082257_c5ede6a3.png', 'Capelli', 250, 10, 1, NULL, 1, '', '', NULL),
(77, '775b4712-91c0-4098-9a9e-15d06bb438d8', 'Lachlan Brizzolato', '/assets/images/Personalizzazioni/Capelli/Lachlan-brizzolato_20260615_082317_195473d8.png', 'Capelli', 250, 10, 1, NULL, 1, '', '', NULL),
(78, '2f949c57-3b97-4e6c-8cde-1bf78de3fe3e', 'Lachlan Green', '/assets/images/Personalizzazioni/Capelli/Lachlan-green_20260615_082339_d9df8a06.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(79, 'db1ce95b-361d-4de8-9142-08969fe3f9d8', 'Lachlan Orange', '/assets/images/Personalizzazioni/Capelli/Lachlan-orange_20260615_082359_fe6d9479.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(80, 'be62cb2a-7f32-4c75-8896-3f50fdf7b848', 'Lachlan Pink', '/assets/images/Personalizzazioni/Capelli/Lachlan-pink_20260615_082419_bb15ecbf.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(81, 'd50520d9-3390-43ae-a366-7dde586fd2b2', 'Lachlan Red', '/assets/images/Personalizzazioni/Capelli/Lachlan_red_20260615_082445_9762ccee.png', 'Capelli', 200, 10, 1, NULL, 1, '', '', NULL),
(82, '839ace28-4aae-406c-90cc-0b873935d0f9', 'Nikolaj Azzurro', '/assets/images/Personalizzazioni/Capelli/Nikolaj-intero_cyan_20260615_082515_635dc54c.png', 'Capelli', 250, 11, 1, NULL, 1, '', '', NULL),
(83, '1bdafcf6-a0bc-4d9a-bd55-d135e17435f6', 'Nikolaj Biondo', '/assets/images/Personalizzazioni/Capelli/Nikolaj-blonde_20260615_082541_46012601.png', 'Capelli', 200, 11, 1, NULL, 1, '', '', NULL),
(84, 'd2cc81ad-8f5d-4945-9f54-75b5a233d5a3', 'Nikolaj Black', '/assets/images/Personalizzazioni/Capelli/Nikolaj-Black_20260615_082600_73f65b23.png', 'Capelli', 200, 11, 1, NULL, 1, '', '', NULL),
(85, 'e80e5b59-2ccb-4991-9024-8fff49a968c3', 'Nikolaj Blue', '/assets/images/Personalizzazioni/Capelli/Nikolaj-Blue_20260615_082625_98a4ff34.png', 'Capelli', 200, 11, 1, NULL, 1, '', '', NULL),
(86, 'f2d5983c-80fe-4fb0-9309-e25f614d82f6', 'Nikolaj Green', '/assets/images/Personalizzazioni/Capelli/Nikolaj-green_20260615_082648_72f09df9.png', 'Capelli', 200, 11, 1, NULL, 1, '', '', NULL),
(87, '069e03fb-eed8-43a7-820b-24dfe8a12ba6', 'Nikolaj Violet', '/assets/images/Personalizzazioni/Capelli/Nikolaj-violet_20260615_082708_d01aa2cc.png', 'Capelli', 200, 11, 1, NULL, 1, '', '', NULL),
(88, 'd4794863-4f80-4b9f-a542-01682472434b', 'Nikolaj White', '/assets/images/Personalizzazioni/Capelli/Nikolaj-white_20260615_082726_76e952eb.png', 'Capelli', 200, 11, 1, NULL, 1, '', '', NULL),
(89, 'e8add188-4830-44ae-9412-2028e3ae5e72', 'Shinzo Blonde', '/assets/images/Personalizzazioni/Capelli/Shinzo_biondo_20260615_083048_2f83b1ad.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(90, '9b1ca4de-507d-46ce-834a-1d7cc611bdb2', 'Shinzo Green', '/assets/images/Personalizzazioni/Capelli/Shinzo_green_20260615_083108_5f5fb8c0.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(91, '1f1825c4-a71a-47fb-92f3-a65709dac491', 'Shinzo Light Blue', '/assets/images/Personalizzazioni/Capelli/Shinzo_azzurro_20260615_083130_d536ca21.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(92, '2f938f97-7200-4452-ae92-b4804ce494bb', 'Shinzo Orange', '/assets/images/Personalizzazioni/Capelli/Shinzo_orange_20260615_083149_98022af0.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(93, '64d954b5-f10a-43c4-94d0-e5bde2be4ab9', 'Shinzo Pink', '/assets/images/Personalizzazioni/Capelli/Shinzo_pink_20260615_083216_57f3ecc0.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(94, '1a0410b3-7468-4cef-8774-c7c54ebad63c', 'Shinzo Red', '/assets/images/Personalizzazioni/Capelli/Shinzo_Red_20260615_083235_23793a6f.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(95, '51dad053-3483-4452-ab40-3457c0a4f4dc', 'Shinzo Violet', '/assets/images/Personalizzazioni/Capelli/Shinzo_viola_20260615_083252_fd05c774.png', 'Capelli', 200, 12, 1, NULL, 1, '', '', NULL),
(96, 'e9e526a3-bc5f-4b2e-992b-ec55eab7feeb', 'Shinzo White', '/assets/images/Personalizzazioni/Capelli/Shinzo_white_20260615_083310_45d78f28.png', 'Capelli', 250, 12, 1, NULL, 1, '', '', NULL),
(97, '070ae40e-7039-4034-ae5e-8494d2cc04a9', 'Strogar Blue', '/assets/images/Personalizzazioni/Capelli/Strogar-Blu_20260615_083336_2b98d481.png', 'Capelli', 200, 13, 1, NULL, 1, '', '', NULL),
(98, '2a138819-b582-49c7-a497-3096c36e47ff', 'Strogar Green', '/assets/images/Personalizzazioni/Capelli/Strogar-Green_20260615_083358_3495cbfe.png', 'Capelli', 200, 13, 1, NULL, 1, '', '', NULL),
(99, '07ab1f77-a5ee-416b-b1d5-a5caab4ddb36', 'Strogar Light Blue', '/assets/images/Personalizzazioni/Capelli/Strogar-Azzurro_20260615_083414_902a497c.png', 'Capelli', 200, 13, 1, NULL, 1, '', '', NULL),
(100, 'ad8f2b60-2280-4625-b361-34a819e1a7a4', 'Strogar Pink', '/assets/images/Personalizzazioni/Capelli/Strogar-Pink_20260615_083432_46b88e9a.png', 'Capelli', 200, 13, 1, NULL, 1, '', '', NULL),
(101, '1df844ff-38e3-4f35-a1af-7ed7dffaa2d8', 'Strogar Red', '/assets/images/Personalizzazioni/Capelli/Strogar-Red_20260615_083451_1ec9e522.png', 'Capelli', 200, 13, 1, NULL, 1, '', '', NULL),
(102, '9035f6f2-85c5-451e-ac98-7b4e33321772', 'Strogar Yellow', '/assets/images/Personalizzazioni/Capelli/Strogar-Yellow_20260615_083511_1a4bd897.png', 'Capelli', 200, 13, 1, NULL, 1, '', '', NULL),
(103, 'b29b07c4-af75-493f-8b06-edf7d8368c71', 'Nuliajuk Azzurra', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_cyan_20260615_082749_05e17525.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(104, '69f349a0-0c62-4b24-a9a5-be9793bf6db7', 'Nuliajuk Black', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_black_20260615_082809_a434cc0d.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(105, '972f8827-8c13-4af3-b190-12fb583d41c5', 'Nuliajuk Blonde', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_blonde_20260615_082838_3bd02033.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(106, '88f1af1c-7707-49f4-b9a4-92605b3783bd', 'Nuliajuk Blue', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_blue_20260615_082858_8f182911.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(107, 'c99be5e6-b413-4ae5-b84e-b7342714e3a6', 'Nuliajuk Green', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_green_20260615_082920_4790eca7.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(108, '287908dd-09cf-48a5-9acf-c0195dbdd880', 'Nuliajuk Orange', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_orange_20260615_082939_817062b9.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(109, '917ea3d1-5db5-4311-b829-496dd29543c4', 'Nuliajuk Pink', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_pink_20260615_083000_9d37ca52.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(110, '9457f241-38b2-48ee-927e-c93f752e1f83', 'Nuliajuk Red', '/assets/images/Personalizzazioni/Capelli/Nuliajuk_red_20260615_083022_6ca54c85.png', 'Capelli', 200, 14, 1, NULL, 1, '', '', NULL),
(111, 'e62dcaef-9d2c-425a-b736-ebe61dd7c492', 'Bandiera Azzurra', '/assets/images/Personalizzazioni/Sfondi/Bandiera_azzurra_20260614_083257_1b3e0ad8.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(112, '02da2954-b3d8-4e32-a0e8-e0268892e99b', 'Bandiera Bianconera', '/assets/images/Personalizzazioni/Sfondi/Bandiera_bianconera_20260614_083321_965d5e26.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(113, 'd91423d8-6f8f-4670-ba4e-27e88e1fe9fb', 'Bandiera GialloBlu', '/assets/images/Personalizzazioni/Sfondi/Bandiera_gialloblu_20260614_083343_a7ffc9d9.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(114, '329c25dd-1ab8-4fca-bc4b-d31be7a05e68', 'Bandiera Giallorossa', '/assets/images/Personalizzazioni/Sfondi/Bandiera_giallorossa_20260614_083411_498d51a8.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(115, '64a162a4-4ce0-40d6-9987-099275aff175', 'Bandiera Nerazzurra', '/assets/images/Personalizzazioni/Sfondi/Bandiera_nerazzurra_20260614_083429_33463ec3.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(116, 'c48b0699-6191-42ed-8795-e8ffc684c83d', 'Bandiera Rossonera', '/assets/images/Personalizzazioni/Sfondi/Bandiera_rossonera_20260614_083449_6b237191.png', 'Sfondo', 130, 0, 1, NULL, 1, '', '', 4),
(117, 'f3b7ecab-d196-429c-acb6-03db3e942622', 'Campo da calcio', '/assets/images/Personalizzazioni/Sfondi/campo_calcio_20260614_083513_53b5d327.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(118, '955e7688-ba11-4bd5-828d-1d7c2c2f235c', 'Città', '/assets/images/Personalizzazioni/Sfondi/sfondo_citta_20260614_083529_9fcd7c5f.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(119, 'f3b8494b-1398-4746-b11d-983d3438e925', 'Collina', '/assets/images/Personalizzazioni/Sfondi/sfondo_paese_collina_20260614_083549_3befd4a6.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(120, '594f7c0d-69ff-41f7-9c3d-8392db419bad', 'Fiume', '/assets/images/Personalizzazioni/Sfondi/sfondo_fiume_20260614_083603_62431c9e.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(121, '3499fada-7889-491d-9bdb-d840fead4d15', 'Foresta', '/assets/images/Personalizzazioni/Sfondi/sfondo_vegetazione_20260614_083633_b28daefc.png', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(122, '18a4a532-dea6-49f5-a1eb-64e05a63d6e5', 'Ghiaccio', '/assets/images/Personalizzazioni/Sfondi/sfondo_ghiacci_20260614_083651_c00483f8.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(123, 'abdb1d09-bde4-4a0b-ad4a-4380c1b1d344', 'Montagne', '/assets/images/Personalizzazioni/Sfondi/sfondo_montagne_20260614_083706_f4f6df07.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(124, 'd8b5144c-6c50-4bd3-918a-5e7e8895fe58', 'Natale', '/assets/images/Personalizzazioni/Sfondi/sfondo_natale_20260614_083723_fe525e00.jpg', 'Sfondo', 120, 0, 1, NULL, 1, '', '', 5),
(125, 'cbb2ee27-cd65-4de0-8606-35f5ce2459fa', 'Oasi', '/assets/images/Personalizzazioni/Sfondi/sfondo_oasi_20260614_083750_6b12003b.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(126, '555088d1-9bba-4c3a-b319-0b9f5f632510', 'Oceano', '/assets/images/Personalizzazioni/Sfondi/sfondo_oceano_20260614_083806_6b7492b2.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(127, 'e0a3eef8-85b1-47f0-9cb9-f87e810156e5', 'Robot Pioggia', '/assets/images/Personalizzazioni/Sfondi/robot_pioggia_20260614_083827_54b7f260.jpg', 'Sfondo', 150, 0, 1, NULL, 1, '', '', 5),
(128, 'a7724b53-4e46-4af5-b074-9e110684bbea', 'Savana', '/assets/images/Personalizzazioni/Sfondi/sfondo_savana_20260614_083903_700f0ed4.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(129, '99b19c4a-2db7-4654-9b48-f313599823fb', 'Universo', '/assets/images/Personalizzazioni/Sfondi/sfondo_spazio_20260614_083849_a6a71f09.jpg', 'Sfondo', 110, 0, 1, NULL, 1, '', '', 5),
(130, 'e172112a-326c-4b80-a930-e2068b00fd05', 'Antiche Rovine', '/assets/images/Personalizzazioni/BigBackground/Ancient_ruins_bg_20260614_084311_4231e076.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(131, '45bb02e9-42c2-455d-85a8-62d0f841183e', 'Arco Fantasy', '/assets/images/Personalizzazioni/BigBackground/fantasy_bg_20260614_084331_58f53b22.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(132, '0f596f16-86bc-42cc-bc1e-d58b7ecd3624', 'Cerchio Mistico', '/assets/images/Personalizzazioni/BigBackground/Rune_Magic_bg_20260614_084354_17ad9784.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(133, '1d68fd9e-fc44-483f-91fa-f49f45fa9935', 'Deserto', '/assets/images/Personalizzazioni/BigBackground/Desert_bg_20260614_084413_b0490951.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(134, '472dbb77-6d8d-4298-99af-ba46cf3762ed', 'Foresta Incantata', '/assets/images/Personalizzazioni/BigBackground/EnchantedForest_bg_20260614_084437_4253220c.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(135, '02e433e5-8102-4475-8d9a-696445a17b5b', 'Landa demoniaca', '/assets/images/Personalizzazioni/BigBackground/Demoniac_bg_20260614_084456_97552417.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(136, '7e674a2d-c2e2-491c-b527-2ca68715b9da', 'Landa Infetta', '/assets/images/Personalizzazioni/BigBackground/Cursed_landscape_bg_20260614_084514_0f690a97.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(137, '338095df-2957-4243-9cd2-721a483755c5', 'Montagne Innevate', '/assets/images/Personalizzazioni/BigBackground/Snow_mountains_bg_20260614_084536_657ee39c.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(138, 'a7be7942-82af-4562-9ba4-8062f71fef09', 'Paesaggio da Fumetto', '/assets/images/Personalizzazioni/BigBackground/DBall_bg_20260614_084605_5d927a9c.png', 'BigBackground', 300, 0, 1, NULL, 1, '', '', 6),
(139, '1638cac8-d193-48b3-b6bf-1b4208792bee', 'Basilisco', '/assets/images/Personalizzazioni/Pet/Basilisco_20260617_115009_1e4b5573.png', 'Pet', 1000, 0, 1, NULL, 1, '', '', 7),
(140, '91f3a548-0b52-424b-b7ff-1fea3b460bc5', 'Basilisco Blu', '/assets/images/Personalizzazioni/Pet/BlueBasilisk_20260617_115048_cf6ecda4.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(141, 'fa6076b4-9d18-40fe-9324-a793758ecf74', 'Basilisco Dorato', '/assets/images/Personalizzazioni/Pet/GoldenBasilisk_20260617_115123_8fdc85a5.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(142, '48922fd6-99a4-4f3a-9a40-685007c36606', 'Basilisco Rosso', '/assets/images/Personalizzazioni/Pet/RedBasilisk_20260617_115154_7eee420e.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(143, '6e5daf58-3721-4f7f-bf5e-9ad8b092185c', 'Blue Fenrir', '/assets/images/Personalizzazioni/Pet/BlueFenrir_20260617_115223_8ec20264.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(144, 'c32d4426-5094-4db4-9186-058c43a5241b', 'Blue Phoenix', '/assets/images/Personalizzazioni/Pet/BluePhoenix_20260617_115257_6e0f091e.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(145, '61b717ca-39a4-4ab2-81bc-3eea684556e5', 'Blue Unicorn', '/assets/images/Personalizzazioni/Pet/Blue_Unicorn_20260617_115330_76075735.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(146, '3f6e2ebc-5b89-4ab7-847d-5427735069a4', 'Drago Bianco', '/assets/images/Personalizzazioni/Pet/Cute_White_Dragon_20260617_115406_7eebc1ea.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(147, '4d72bf74-3a08-47ad-92d3-645a30637e5e', 'Drago Blu', '/assets/images/Personalizzazioni/Pet/Cute_Blue_Dragon_20260617_115437_0eb5c6e9.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(148, 'f943b11e-147a-41e5-be80-083659e90d4f', 'Drago Dorato', '/assets/images/Personalizzazioni/Pet/Cute_Golden_Dragon_20260617_115519_b9550f81.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(149, '64342418-c1e1-4463-8ec0-fd17bd898a7b', 'Drago Rosso', '/assets/images/Personalizzazioni/Pet/Cute_Red_Dragon_20260617_115548_f412519e.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(150, 'cb86e1e7-c919-475f-9551-9f55ef252dd7', 'Drago Verde', '/assets/images/Personalizzazioni/Pet/Cute_green_dragon_20260617_115625_79a0ce68.png', 'Pet', 1000, 0, 1, NULL, 1, '', '', 7),
(151, '00edae5b-09c1-4ac7-bfe8-8c165eaeb0f3', 'Fenrir', '/assets/images/Personalizzazioni/Pet/Fenrir_20260617_115704_18798ebf.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(152, '873b5a78-ac47-4f50-a7c4-1f79aa3ff75f', 'Golden Fenrir', '/assets/images/Personalizzazioni/Pet/GoldenFenrir_20260617_115732_721ee065.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(153, '6b98ffa0-edff-45b6-9a5c-62317daac5b8', 'Golden Unicorn', '/assets/images/Personalizzazioni/Pet/Golden_unicorn_20260617_115810_c89dfe25.png', 'Pet', 2400, 0, 1, NULL, 1, '', '', 7),
(154, 'f7cf5688-3c9e-4b3f-9350-61487aaa7897', 'Green Fenrir', '/assets/images/Personalizzazioni/Pet/GreenFenrir_20260617_115853_adae76d2.png', 'Pet', 2000, 0, 1, NULL, 1, '', '', 7),
(155, '4b98e260-707b-42d2-9292-9eef6fdd35ec', 'Green Phoenix', '/assets/images/Personalizzazioni/Pet/GreenPhoenix_20260617_115920_e105f116.png', 'Pet', 2000, 0, 1, NULL, 1, '', '', 7),
(156, 'd3b6eab2-df34-4ed5-8e35-4d752bfeba82', 'GrifonBlue', '/assets/images/Personalizzazioni/Pet/Grifonblue_20260617_120002_ba9f1778.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(157, 'c80d3aa6-6246-4eed-b86d-3ca83c3af220', 'Grifondoro', '/assets/images/Personalizzazioni/Pet/Grifondoro_20260617_120036_3e23771e.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(158, '53f0a592-0aba-4328-8e79-ced7311e3cf5', 'Grifone', '/assets/images/Personalizzazioni/Pet/GrifonBaby_20260617_120106_ee82f923.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(159, '8d16dc32-f4df-4a69-b5f1-6d218b448215', 'Grifone Bianco', '/assets/images/Personalizzazioni/Pet/GrifoneBianco_20260617_120138_0ca05249.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(160, '4e4c9259-a969-4866-b6e3-dcb7c5e7a306', 'GrifonRed', '/assets/images/Personalizzazioni/Pet/GrifonRed_20260617_120213_bafb86b3.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(161, 'd01161a8-5414-4f60-a4f4-7aa30d84028b', 'Hydra', '/assets/images/Personalizzazioni/Pet/Hydra_20260617_120251_19f932ed.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(162, 'e25f51b5-78aa-46f6-8526-fb3abb40c945', 'Ippogrifo', '/assets/images/Personalizzazioni/Pet/Ippogrifo_20260617_120327_b632e5ba.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(163, '308f16a5-f389-4ffe-bfe8-0c85262d8558', 'Ippogrifo Blu', '/assets/images/Personalizzazioni/Pet/BlueHippo_20260617_120353_ee5b52d2.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(164, '0fca5b40-7d30-46eb-bf09-f9a29ffcbf98', 'Ippogrifo dorato', '/assets/images/Personalizzazioni/Pet/GoldenHippo_20260617_120422_c9315d45.png', 'Pet', 800, 0, 1, NULL, 1, '', '', 7),
(165, '696eaeff-5051-4985-a43c-3856edc3ae64', 'Ippogrifo verde', '/assets/images/Personalizzazioni/Pet/GreenHippo_20260617_120450_cfc2ccb9.png', 'Pet', 1000, 0, 1, NULL, 1, '', '', 7),
(166, '44bcd949-e59a-4f36-8151-55e07155ed33', 'Leviatano', '/assets/images/Personalizzazioni/Pet/Leviathan_20260617_120519_760eb44c.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(167, '94d7f9af-6b41-4d42-99d3-d5418756e876', 'Leviatano Bianco', '/assets/images/Personalizzazioni/Pet/White_Leviathan_20260617_120554_8e1d0fab.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(168, '9a171441-8c12-45c2-9c6d-a0c0b78fcc7b', 'Leviatano Dorato', '/assets/images/Personalizzazioni/Pet/Golden_Leviathan_20260617_120624_233fb5af.png', 'Pet', 2400, 0, 1, NULL, 1, '', '', 7),
(169, '73c78fd1-ae2d-4c99-877e-cbef0df69248', 'Leviatano Rosso', '/assets/images/Personalizzazioni/Pet/RedLeviathan_20260617_120701_2b0b4fcf.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(170, 'd36d288b-cafa-4ea6-b9c6-5872e7d7987d', 'Leviatano Verde', '/assets/images/Personalizzazioni/Pet/GreenLeviathan_20260617_120733_52559198.png', 'Pet', 2000, 0, 1, NULL, 1, '', '', 7),
(171, 'b088bb7c-dae7-4ce8-85a5-be6fe963baf4', 'Manticora', '/assets/images/Personalizzazioni/Pet/Manticore_20260617_120810_2c248f95.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(172, '226c7fcf-def6-4b98-a6c6-a37e52b8300d', 'Manticora Bianca', '/assets/images/Personalizzazioni/Pet/WhiteManticore_20260617_120842_27cc4d0c.png', 'Pet', 1800, 0, 1, NULL, 1, '', '', 7),
(173, '63e497e2-018c-4581-81b8-7fc48d09d71b', 'Manticora dorata', '/assets/images/Personalizzazioni/Pet/GoldenManticore_20260617_120926_b727c73c.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(174, 'ad534211-1b38-495f-99db-9565a3b91394', 'Manticora Verde', '/assets/images/Personalizzazioni/Pet/GreenManticore_20260617_120954_94505bba.png', 'Pet', 3000, 0, 1, NULL, 1, '', '', 7),
(175, 'f70089dc-625f-48df-9b0d-da1637aafc35', 'Manticora Viola', '/assets/images/Personalizzazioni/Pet/PurpleManticore_20260617_121036_928bd5d0.png', 'Pet', 1500, 0, 1, NULL, 1, '', '', 7),
(176, '9a41d0c8-f5a7-49da-badf-f838ad0bdd85', 'Pegaso', '/assets/images/Personalizzazioni/Pet/Pegasus_20260617_121102_1b548884.png', 'Pet', 1600, 0, 1, NULL, 1, '', '', 7),
(177, '8dee8bdf-f9de-4694-ab32-1e2067280302', 'Purple Phoenix', '/assets/images/Personalizzazioni/Pet/VioletPhoenix_20260617_121143_f2693168.png', 'Pet', 4000, 0, 1, NULL, 1, '', '', 7),
(178, 'be421028-f2ea-4ec8-9fe7-d10ccc6f7dfc', 'Red Fenrir', '/assets/images/Personalizzazioni/Pet/RedFenrir_20260617_121211_cf3ef468.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(179, '2fbf2708-5078-4dc8-a7d1-d6a5d2d5c832', 'Red Phoenix', '/assets/images/Personalizzazioni/Pet/Phoenix_20260617_121248_85de8e35.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(180, '0ec0ab7f-82ff-400b-8156-479902085c13', 'Red Unicorn', '/assets/images/Personalizzazioni/Pet/Red_unicorn_20260617_121315_20c49dc0.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(181, '01e2f633-635d-41b4-9a73-236342e13950', 'Sfinge', '/assets/images/Personalizzazioni/Pet/Sphinx_20260617_121358_a308986d.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(182, 'd4c18d8b-6652-4525-bf89-9180515fbc4b', 'Sfinge Bianca', '/assets/images/Personalizzazioni/Pet/WhiteSphinx_20260617_121428_33ddd01d.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(183, '923b07c3-967d-4b82-82e6-24ff3f70af2d', 'Sfinge Blu', '/assets/images/Personalizzazioni/Pet/BlueSphinx_20260617_121456_02728a06.png', 'Pet', 600, 0, 1, NULL, 1, '', '', 7),
(184, '4433417b-127f-4df1-8a0c-b4545ff8c006', 'Sfinge Verde', '/assets/images/Personalizzazioni/Pet/GreenSphinx_20260617_121519_54b90909.png', 'Pet', 3000, 0, 1, NULL, 1, '', '', 7),
(185, '50179346-9abf-4ffa-9a58-9674ef328c55', 'Sfinge Viola', '/assets/images/Personalizzazioni/Pet/VioletSphinx_20260617_121603_4f80cb39.png', 'Pet', 3000, 0, 1, NULL, 1, '', '', 7),
(186, '4462ed84-9cdb-4957-bcd2-b0e65c7f0cdf', 'Unicorn', '/assets/images/Personalizzazioni/Pet/Unicorn_20260617_121640_68ed5051.png', 'Pet', 2200, 0, 1, NULL, 1, '', '', 7),
(187, '9a5cfea1-6cd8-4741-abdc-ef2b6024c9b9', 'White Phoenix', '/assets/images/Personalizzazioni/Pet/WhitePhoenix_20260617_121714_97c3718d.png', 'Pet', 1800, 0, 1, NULL, 1, '', '', 7),
(188, '2e37277b-64c3-40e4-97ec-740b370f63cc', 'White Unicorn', '/assets/images/Personalizzazioni/Pet/WhiteUnicorn_20260617_121744_ff7ea395.png', 'Pet', 1200, 0, 1, NULL, 1, '', '', 7),
(189, '29bdb3da-f4ac-4f06-9d4b-aef13568c67e', 'Maglia Azzurri', '/assets/images/Personalizzazioni/Equip/Maglia_Azzurri_20260617_084236_337c3181.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: Azzurri', '_azzurro.png', 8),
(190, '4e9b5d5f-634f-438a-aa2a-446ac5fef4bc', 'Maglia Bianconera', '/assets/images/Personalizzazioni/Equip/Maglia_bianconera_20260617_084341_53013c1f.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: bianconeri', '_bianconero.png', 8),
(191, 'f85ba766-063b-4833-b11a-ecee69c6ac4c', 'Maglia GialloBlu', '/assets/images/Personalizzazioni/Equip/Maglia_gialloblu_20260617_084438_d39f41a9.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: gialloblu', '_gialloblu.png', 8),
(192, '424b94dc-cb4e-4947-b232-24b3486b8c24', 'Maglia GialloRossi', '/assets/images/Personalizzazioni/Equip/Maglia_GialloRossi_20260617_084548_43426ced.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: giallorosso', '_giallorosso.png', 8),
(193, 'bbb9561d-ca57-4c22-9485-300ac111b4c2', 'Maglia Nerazzurra', '/assets/images/Personalizzazioni/Equip/Maglia_Nerazzurri_20260617_084641_775bf9db.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: nerazzurri', '_nerazzurro.png', 8),
(194, 'd463f8af-0b52-41ea-94ef-a93d570a0716', 'Maglia nerobianca', '/assets/images/Personalizzazioni/Equip/Maglia_Nerobianchi_20260617_084747_5c134000.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: nerobianchi', '_nerobianco.png', 8),
(195, '633a5633-1a02-4cd3-b2bd-f7dd7d8b1170', 'Maglia Rossonera', '/assets/images/Personalizzazioni/Equip/Maglia_RossoNeri_20260617_084837_8a0d2086.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Maglia della squadra del cuore: rossoneri', '_rossonero.png', 8),
(196, '505e5ba5-6bba-4e6d-9620-a09df57c017a', 'Abiti del bibliotecario', '/assets/images/Personalizzazioni/Equip/Veste_bibliotecario_saggio_20260615_084330_2bcab6da.png', 'Equipaggiamento', 800, 0, 1, NULL, 1, 'Abiti che aumentano la saggezza di chi le indossa e quindi l\'esperienza guadagnata per esercizio', '_librarian.png', 9),
(197, '936240b3-57dd-46cf-bac7-6049ffa215d4', 'Abiti del Borgomastro', '/assets/images/Personalizzazioni/Equip/riccone_fantasy_20260615_084436_ee59bda4.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Abiti indossati dai mercanti che arrivano ad essere i più ricchi della città, elevati al rango di Borgomastri. Sono in grado di ottenere guadagni quasi miracolosi.', '_borgomaster.png', 9),
(198, '17b86819-3640-4c90-ba60-c7e586411795', 'Abiti del Mercante', '/assets/images/Personalizzazioni/Equip/mercante_povero_20260615_084521_6f0add5c.png', 'Equipaggiamento', 400, 0, 1, NULL, 1, 'Abiti indossati dai mercanti alle prime armi, permettono di aumentare i guadagni di poco', '_merchant_base.png', 9),
(199, 'beedcdb3-a6f1-4dc0-b9a3-f2923b76e3f6', 'Abiti del Ricco Mercante', '/assets/images/Personalizzazioni/Equip/mercante_20260615_084614_208f8b04.png', 'Equipaggiamento', 700, 0, 1, NULL, 1, 'Abiti indossati dai  mercanti che si sono arricchiti grazie al commercio, in grado di ottenere buoni guadagni dagli scambi', '_merchant_rich.png', 9),
(200, 'd43ad460-1cae-45c4-8fb8-3104aa0bef8d', 'Abito dell\'Assassino Ombra', '/assets/images/Personalizzazioni/Equip/Assassino_ombra_20260617_085615_03cc5a9d.png', 'Equipaggiamento', 3000, 0, 1, NULL, 1, 'Vesti indossate dai più abili assassini del reame, che si muovono nell\'ombra riuscendo a guadagnare esperienza sconfiggendo i nemici', '_shadow_assassin.png', 9),
(201, '9da47d9d-c097-44ba-a743-8b56958d0b44', 'Armatura d\'Oro', '/assets/images/Personalizzazioni/Equip/Opulent_golden_armor_20260615_084805_15299e79.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Armatura in oro ricoperta di gemme. Indossata solo da guerrieri e re che possono permettersela. Aumenta la difesa e permette di guadagnare più monete (i soldi chiamano soldi)', '_golden_armor.png', 9),
(202, '36c8a215-dcdd-4c19-9681-cfa8516053c2', 'Armatura da Football', '/assets/images/Personalizzazioni/Equip/armatura_football_20260615_084902_065cf057.png', 'Equipaggiamento', 1700, 0, 1, NULL, 1, 'Un armatura da giocatore di Football: migliora la difesa, i guadagni (i giocatori di NFL sono ricchi) e anche l\'esperienza accumulata (fare sport aiuta a mantenere attiva anche la mente)', '_football_armor.png', 9),
(203, '37afac1b-6998-4c8b-bd9b-63b11a839588', 'Armatura del Centurione', '/assets/images/Personalizzazioni/Equip/Centurione_20260615_085003_3940d271.png', 'Equipaggiamento', 2600, 0, 1, NULL, 1, 'Armatura indossata dai centurioni Romani nelle campagne militari di Cesare Augusto: difesa aumentata, tattica, esperienza aumentate', '_centurion.png', 9),
(204, '22891956-9565-4862-9bc3-00f9df82ca4f', 'Armatura del Drago', '/assets/images/Personalizzazioni/Equip/dragon_armor_20260615_085056_a3101dd4.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Epica armatura in scaglie di Drago, impenetrabile dagli attacchi fisici. Garantisce la massima protezione.', '_dragon_armor.png', 9),
(205, 'f3b99227-2b58-423d-8c52-d9011b9063c9', 'Armatura del Samurai', '/assets/images/Personalizzazioni/Equip/Samurai_20260615_085239_5add48da.png', 'Equipaggiamento', 3000, 0, 1, NULL, 1, 'Armatura indossata dai saggi e coraggiosi guerrieri Samurai Giapponesi al servizio degli Shogun', '_samurai.png', 9),
(206, 'c1cc7452-5455-4fe0-9234-aca78f65ebff', 'Armatura leggera', '/assets/images/Personalizzazioni/Equip/armatura_leggera_20260615_085326_64e53722.jpg', 'Equipaggiamento', 400, 0, 1, NULL, 1, 'Un\'armatura in cuoio in grado di parare colpi leggeri', '_light_armor.png', 9),
(207, '770de6e5-deb6-46eb-ba7a-a4ce09296c9c', 'Armatura Magitech', '/assets/images/Personalizzazioni/Equip/Magitech_Armor_20260617_083729_af72b6a7.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Leggendaria Armatura forgiata dagli antichi maghi, che hanno infuso poteri magici alle pietre che la rivestono. Offre protezione e potenza magica', '_magitech.png', 9),
(208, '745f6198-d8f4-45a4-a7f1-969c999a4840', 'Armatura pesante', '/assets/images/Personalizzazioni/Equip/armatura_pesante_20260617_083816_3f0a7573.jpg', 'Equipaggiamento', 700, 0, 1, NULL, 1, 'Un\'armatura in piastre d\'acciaio in grado di parare colpi anche pesanti', '_heavy_armor.png', 9),
(209, '5c7c7277-a07d-4c07-b054-29f155175fb3', 'Completo per le arti marziali', '/assets/images/Personalizzazioni/Equip/Martial_arts_suite_20260617_083913_61563a23.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Un completo che dona energia per il combattimento: permette di incrementare i punti esperienza e le monete guadagnate', '_martial_arts.png', 9),
(210, 'f8e3264f-ed9a-41c7-b5ef-a86b67f48913', 'Corazza del Vichingo', '/assets/images/Personalizzazioni/Equip/Vichingo_20260617_084023_34a37f54.png', 'Equipaggiamento', 2100, 0, 1, NULL, 1, 'La corazza bordata di pelliccia indossata dai duri vichinghi del nord', '_viking.png', 9),
(211, '13a5ec51-8581-4b05-9c9b-4518d9400133', 'Druido della Foresta', '/assets/images/Personalizzazioni/Equip/Druido_Foresta_20260617_084131_ba9312e4.png', 'Equipaggiamento', 2500, 0, 1, NULL, 1, 'Vesti indossate dagli arcani e saggi druidi della foresta, che governano la magia della natura, circondandosi di una corazza di corteccia d\'albero', '_forest_druid.png', 9),
(212, '5f4c5df9-eec0-4803-852d-4f72abb8a8ed', 'Veste da Pirata', '/assets/images/Personalizzazioni/Equip/Pirate_suit_20260617_084927_03096f1a.png', 'Equipaggiamento', 1700, 0, 1, NULL, 1, 'La veste da pirata permette di incrementare i guadagni saccheggiando le navi di passaggio e dà l\'esperienza della vita di mare', '_pirate.png', 9),
(213, 'c1111aad-e686-4189-8f48-72169b2f78e1', 'Vesti da Mago Avanzato', '/assets/images/Personalizzazioni/Equip/mage_enchanted_robe_20260617_085032_06583954.png', 'Equipaggiamento', 700, 0, 1, NULL, 1, 'Vesti incantate che permettono l\'aumento dell\'energia magica quando si passa di livello', '_advanced_mage.png', 9),
(214, 'ec553e85-e378-45df-8cf3-ce9701e6695d', 'Vesti da Mago principiante', '/assets/images/Personalizzazioni/Equip/vesti_mago_principiante_20260617_085129_8f39ca2f.png', 'Equipaggiamento', 400, 0, 1, NULL, 1, 'Vesti indossate dai maghi agli inizi della carriera: aumentano di poco la potenza magica', '_apprentice_mage.png', 9),
(215, 'a937809a-8655-44fa-85db-eda80439cfd7', 'Vesti del mago corrotto', '/assets/images/Personalizzazioni/Equip/Obscure_corrupted_mage_20260617_085233_dc41b50f.png', 'Equipaggiamento', 1700, 0, 1, NULL, 1, 'Vesti indossate dai maghi passati al lato oscuro, corrotti dal potere magico e dalla bramosia di denaro, ma con la stessa esperienza dei maghi buoni', '_corrupted_mage.png', 9),
(216, '4aa0e77a-6a58-495b-b4d7-ec99bf5343c6', 'Vesti del Mago Elementale', '/assets/images/Personalizzazioni/Equip/mago_elementale_20260617_085324_915fb8e2.png', 'Equipaggiamento', 1500, 0, 1, NULL, 1, 'Vesti indossate dai maghi che dominano gli elementi: creano energia magica e oro', '_elemental_mage.png', 9),
(217, '6598ad70-e6b4-46a1-b84d-91342337e66b', 'Vesti del Mago Supremo', '/assets/images/Personalizzazioni/Equip/supreme_mage_robe_20260617_085412_65d865c6.png', 'Equipaggiamento', 1200, 0, 1, NULL, 1, 'Vesti indossate dai Maghi Supremi che governano le Torri dell\'Alta Magia, conferiscono una potenza magica di livello leggendario', '_supreme_mage.png', 9);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_poteri`
--

CREATE TABLE `ct_poteri` (
  `id_potere` int NOT NULL,
  `nome_potere` varchar(100) NOT NULL,
  `descrizione_potere` text NOT NULL,
  `img_potere` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `livello` int NOT NULL,
  `mana_necessario` int NOT NULL,
  `fk_classe` int NOT NULL,
  `fisso` int NOT NULL DEFAULT '0',
  `originale` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(33, 'Arco d\'Avorio', 'Puoi chiedere un aiuto su una risposta a crocette durante un compito in classe: rimarranno solo 2 risposte.', '/assets/images/Poteri/power_6a3291244c1912.38148098_arcoav.jpg', 6, 2, 1, 0, 0),
(34, 'Ascia Spaccaterra', 'L&#039;Ascia Spaccaterra permette di distruggere una domanda su un compito (teorico) o interrogazione, cambiandola con una  a piacere', '/assets/images/Poteri/power_6a32914c74ce23.31768365_ascia_spaccaterra.jpg', 15, 3, 1, 0, 0),
(35, 'Barriera Arcana', 'La barriera arcana ti protegge dalla perdita di un cuore se non hai fatto un esercizio dato per casa.', '/assets/images/Poteri/power_6a3291b1a3db40.21941966_barriera_arcana_fin.jpg', 4, 3, 1, 0, 0),
(36, 'Cambia Compagno', 'Per due settimane lo studente ha la possibilità di cambiare banco durante le lezioni della materia per la quale usa il potere, scambiandosi con un suo compagno.', '/assets/images/Poteri/power_6a3291cc3ed940.32237527_cambio_banco.jpg', 14, 4, 1, 0, 0),
(37, 'Circolo di Fuoco', 'Guadagni +0,5 voti su un&#039;interrogazione o compito a piacere', '/assets/images/Poteri/power_6a3291e9ad79e5.92163100_circolo_fuoco.jpg', 21, 5, 1, 0, 0),
(38, 'Il Grande Salto', 'Grazie alla tua agilità puoi utilizzare una giustificazione per saltare un&#039;interrogazione', '/assets/images/Poteri/power_6a32920469ccb8.05378184_grande_salto.jpg', 10, 3, 1, 0, 0),
(39, 'Lancia del destino', 'La Lancia del destino permette di prevedere il futuro: puoi visionare una domanda del prossimo compito teorico (il potere non può essere usato se un altro studente ha già usato lo stesso potere)', '/assets/images/Poteri/power_6a329223d4c475.54048185_lancia_destino.jpg', 18, 5, 1, 0, 0),
(40, 'Martello dell\'Eden', 'Il martello dell&#039;Eden ha il potere di immobilizzare le persone. Puoi tentare di immobilizzare una verifica, chiedendo una votazione della classe per spostare la data (la verifica può essere spostata una sola volta - votano anche i prof)', '/assets/images/Poteri/power_6a3292603b6049.42562337_martelloeden.jpg', 21, 6, 1, 0, 0),
(41, 'Rifocillarsi', 'Puoi mangiare qualcosa di veloce durante la lezione in classe', '/assets/images/Poteri/power_6a3292c0c473e9.21764338_rifocillarsi2.jpg', 3, 1, 1, 0, 0),
(42, 'Saggezza Indispensabile', 'Puoi portare una mappa mentale (no riassunti) e usarla durante un compito in classe', '/assets/images/Poteri/power_6a3292e974ba01.45985006_mago_libro.jpg', 12, 4, 1, 0, 0),
(43, 'Scudo Dorato', 'Lo Scudo Dorato protegge e guarisce. Puoi usare il suo potere per chiedere un&#039;interrogazione di recupero su un compito andato male. Il voto del compito non farà media.', '/assets/images/Poteri/power_6a3293014212b1.35800245_scudo_dorato.jpg', 22, 4, 1, 0, 0),
(44, 'Sfera Infuocata', 'Guadagni +0.25 voti su un compito o interrogazione a piacere', '/assets/images/Poteri/power_6a32931b403a37.37656901_mago_sfera_fuoco.jpg', 16, 4, 1, 0, 0),
(45, 'Spada Fiammeggiante', 'La spada fiammeggiante permette di dissipare le ombre: durante un compito pratico puoi chiedere all&#039;insegnante di chiarirti meglio un esercizio, in maniera da facilitarne lo svolgimento', '/assets/images/Poteri/power_6a329334d1b817.67121807_spada_fiammeggiante.jpg', 13, 3, 1, 0, 0),
(46, 'Udito amplificato', 'Scegliendo un compagno di classe in anticipo, durante un&#039;interrogazione, si può ricevere un suggerimento dal compagno scelto.', '/assets/images/Poteri/power_6a329356d31397.65903013_orecchio.jpg', 8, 3, 1, 0, 0),
(47, 'Eccezione delle Ere', 'Permette allo studente di ignorare una domanda a crocette o un piccolo requisito tecnico della consegna in una prova pratica (da concordare durante la verifica)  ottenendo comunque il punteggio pieno.', '/assets/images/Poteri/power_6a3294bbdd4ad3.30096413_eccezione_ere.png', 20, 5, 1, 0, 0),
(48, 'Biblioteca delle Ere', 'Durante una verifica o un esercizio in classe, lo studente può attivare il potere per 10 minuti, ottenendo il permesso di consultare una risorsa esterna (documentazione ufficiale, manuale PDF o un sito specifico approvato dal prof - quindi no chatGPT).', '/assets/images/Poteri/power_6a3296b164e9f2.42731753_Biblioteca_ere.png', 25, 7, 1, 0, 0),
(49, 'Frattura del Destino', 'Un fallimento viene sospeso nel flusso del tempo. Per cancellarlo, l&#039;eroe dovrà affrontare una prova straordinaria proveniente da una linea temporale alternativa.\r\nIl prof. assegnerà allo studente un unico esercizio di logica/coding &quot;maledetto&quot; (più difficile della norma). Se l&#039;esercizio è svolto correttamente, ovviamente non con chatGPT, il fallimento originale scompare e viene sostituito da un voto minimo di 7. In caso di fallimento della sfida, lo studente perde istantaneamente 2 Cuori e il voto originale viene confermato.', '/assets/images/Poteri/power_6a32974e104aa8.58839622_frattura_destino.png', 27, 6, 1, 0, 0),
(50, 'Convergenza degli Eroi', 'Potere di squadra (va attivato da tutta la squadra): se la media dei voti dei compagni di squadra nel compito sarà pari o superiore al 7,  tutti i membri della squadra ricevono un bonus di +2.5 punti sul totale dei punti del compito (quindi non direttamente voto, dipende da quanto è il totale dei punti). Se la media è inferiore al 7, il potere fallisce e tutti i partecipanti perdono 1 Cuore  oltre al mana usato.', '/assets/images/Poteri/power_6a32984594d1f8.92231805_Convergenza_eroi.png', 22, 4, 1, 0, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ct_punizioni`
--

INSERT INTO `ct_punizioni` (`id_punizione`, `giorni_per_consegna`, `descrizione_punizione`, `img_punizione`, `fk_classe`) VALUES
(16, 7, 'Creare una presentazione PowerPoint sul seguente tema: perché è importante non creare confusione in classe e rimanere attenti alle lezioni\r\n', '/assets/images/Punizioni/6779374a01761_gabbia_deserto.jpg', 1),
(17, 7, 'Crea una presentazione PowerPoint sul seguente argomento: perché è importante svolgere in autonomia gli esercizi della materia (in laboratorio o per casa), senza farli svolgere a chatGPT o copiarli da un compagno\r\n', '/assets/images/Punizioni/677953f1448dd_carcerata.jpg', 1),
(18, 10, 'Informarsi leggendo in rete su: tema e trama del libro Il Signore delle Mosche di Golding. Creare una presentazione che riassuma la trama ed il tema del libro e provare a fare un collegamento tra il tema del libro ed il perché i professori devono assegnare punizioni come note e sospensioni agli studenti. Cosa potrebbe succedere in una classe se non ci fosse il controllo dei prof', '/assets/images//Punizioni/6800b9349ada2_lordflies.jpg', 1),
(19, 7, 'Scrivi una pagina da leggere in classe con tue personali riflessioni sul tema:Come l\'uso scorretto dell\'intelligenza artificiale può ostacolare il mio apprendimento e la mia autonomia.\r\n', '/assets/images/Punizioni/683aee9672886_uso_scorretto_ia.jpg', 1),
(20, 10, 'Registra un video di almeno 2 minuti in cui spieghi: Perché è importante il valore dell\'onestà: Immagina un mondo dove tutte le persone sono oneste e un secondo mondo dove tutte le persone sono disoneste. In quale preferiresti vivere?\r\n', '/assets/images/Punizioni/683aef72c4aae_mondo_disonesto.jpg', 1),
(21, 14, 'Realizza un fumetto (a mano o con un software online) che racconti: Le avventure di uno studente che copia sempre e dove finisce.\r\nIl fumetto dovrà quindi far riflettere sul perché è sbagliato copiare sia esercizi che verifiche\r\n', '/assets/images/Punizioni/683af048582b2_studente_sbarre.jpg', 1),
(22, 7, 'Crea un volantino dal titolo: Smartphone VS attenzione: chi vince davvero?\r\nConfronta tempo perso, calo della concentrazione, e suggerimenti per usare il telefono in modo intelligente.\r\n', '/assets/images/Punizioni/683af0d7bfb94_cellulare.jpg', 1),
(23, 7, 'Scrivi una lettera da leggere in classe al te stesso futuro: Lettera da un cervello addormentato in classe: cronaca di un\'occasione persa\r\n', '/assets/images/Punizioni/683af1b1f391e_dorme.jpg', 1),
(24, 7, 'Dalla passività alla partecipazione: il mio piano per essere più presente e attivo: crea una mappa mentale come roadmap per arrivare ad essere più attento e partecipativo durante le lezioni.\r\n', '/assets/images/Punizioni/683af25e67cca_strada.jpg', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ct_quest`
--

INSERT INTO `ct_quest` (`id_quest`, `uuid`, `nome_quest`, `image_quest`, `piantina_quest`, `originale`, `blocca_ese`) VALUES
(6, 'a4cee784-c7d5-4558-ba91-1f4dcf3a6373', '14 Eori (Python strutturato)', '/assets/images/Quest/imported_6a43a74f704731.79000552_quest_6a32b3c0b33f50.64012251.jpg', '/assets/images/Quest/imported_6a43a74f745ed8.67137619_piantina_6a32b3c0b7b7d1.73995565.png', 1, 1);

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
(1, 'Un tipo di programmazione in cui si suddivide il programma in funzioni e moduli riutilizzabili in altri programmi', 1, 1),
(2, 'Un tipo di programmazione dove vengono inserite classi che rappresentano oggetti reali', 0, 1),
(3, 'Un tipo di programmazione dove tutto &egrave; una funzione: ad esempio per creare cicli si devono utilizzare funzioni ricorsive', 0, 1),
(4, 'Un tipo di programmazione dove si utilizzano solo funzioni logiche per ottenere risultati logici a partire da predicati', 0, 1),
(5, 'Riusabilit&agrave; del codice', 1, 2),
(6, 'Portabilit&agrave; del codice su altri sistemi operativi', 0, 2),
(7, 'Facilit&agrave; di manutenzione', 1, 2),
(8, 'Maggiore rapidit&agrave; enll&#039;esecuzione delle istruzioni', 0, 2),
(9, 'Sviluppo collaborativo da parte di pi&ugrave; programmatori', 1, 2),
(10, 'Minore quantit&agrave; di bug al&#039;interno del codice', 1, 2),
(11, 'Un&#039;istanza di una classe &egrave; la definizione della classe', 0, 4),
(12, 'Un&#039;istanza di una classe &egrave; un oggetto della classe', 1, 4),
(13, 'Un&#039;istanza di una classe &egrave; un&#039;astrazione che comprende attributi e metodi', 0, 4),
(14, 'Un&#039;istanza di una classe &egrave; la funzione principale di un programma', 0, 4),
(15, 'Tramite un diagramma delle classi UML', 1, 5),
(16, 'Tramite un diagramma di flusso RST', 0, 5),
(17, 'Tramite il diagramma delle componenti UML', 0, 5),
(18, 'Tramite diagramma delle gerarchie RST', 0, 5),
(19, 'Quando si vuole creare un nuovo oggetto di quella classe', 1, 7),
(20, 'Quando si devono definire i metodi della classe', 0, 7),
(21, 'Quando si vuole richiamare la funzione principale del programma', 0, 7),
(22, 'Quando si deve eliminare dalla memoria un oggetto', 0, 7),
(23, 'La tecnica con la quale si racchiudono all&#039;interno della classe attributi e metodi', 1, 8),
(24, 'La tecnica grazie alla quale posso definire una classe all&#039;interno di un modulo', 0, 8),
(25, 'Il fatto che posso richiamare un metodo su di un oggetto', 0, 8),
(26, 'La possibilit&agrave; di riutilizzare classi su pi&ugrave; programmi', 0, 8),
(27, 'class Automobile(object):', 1, 9),
(28, 'class Automobile:', 0, 9),
(29, 'object Automobile(class):', 0, 9),
(30, 'object Automobile():', 0, 9),
(31, 'def nomeMetodo(self, par_a, par_b):', 1, 10),
(32, 'classe.nomeMetodo(self, x, y):', 0, 10),
(33, 'def nomeMetodo(par_a, par_b):', 0, 10),
(34, 'function nomeMetodo(self,x,y):', 0, 10),
(35, 'ogg.stampa()', 1, 11),
(36, 'from ogg import stampa()', 0, 11),
(37, 'ogg-&gt;stampa()', 0, 11),
(38, 'stampa(ogg)', 0, 11),
(39, 't1 = new Triangolo()', 0, 12),
(40, 't1 = Triangolo(5,3)', 1, 12),
(41, 'Triangolo(base=5, altezza=3) = t1', 0, 12),
(42, 't1 = class Triangolo(self, 5, 3)', 0, 12),
(43, 'def __init__(self, a):', 1, 13),
(44, 'def init(self, x):', 0, 13),
(45, 'def __const__(self,a):', 0, 13),
(46, 'def __init(self):', 0, 13),
(47, '__colore = &quot; &quot;', 1, 14),
(48, 'colore__ = &quot; &quot;', 0, 14),
(49, 'private colore = &quot; &quot;', 0, 14),
(50, 'def colore(self, private):', 0, 14),
(51, 'r1.__base', 0, 15),
(52, 'Non &egrave; possibile accedere direttamente agli attributi privati di una classe da una funzione esterna', 1, 15),
(53, 'r1.base', 0, 15),
(54, 'get(r1,__base)', 0, 15),
(55, 'Classe base', 0, 16),
(56, 'Classe derivata', 1, 16),
(57, 'Sopraclasse', 0, 16),
(58, 'Classe multipla', 0, 16),
(59, 'class Cerchio inehrit from FiguraGeometrica:', 1, 17),
(60, 'from FiguraGeometrica import Cerchio', 0, 17),
(61, 'class Cerchio(FiguraGeometrica):', 0, 17),
(62, 'class Cerchio(object) -&gt; FiguraGeometrica:', 0, 17),
(63, 'super()', 1, 18),
(64, 'greater()', 0, 18),
(65, 'import()', 0, 18),
(66, 'class()', 0, 18),
(67, 'Significa che una classe deriva da una singola superclasse', 1, 19),
(68, 'Significa che pi&ugrave; sottoclassi derivano da una singola superclasse', 0, 19),
(69, 'Significa che una classe deriva da pi&ugrave; superclassi', 0, 19),
(70, 'Significa che una classe non deriva da nessun&#039;altra classe', 0, 19),
(71, 'E&#039; un sinonimo di programmazione ad oggetti', 0, 20),
(72, 'La capacit&agrave; di un metodo di assumere pi&ugrave; forme', 1, 20),
(73, 'La capacit&agrave; di un attributo di essere pubblico o privato', 0, 20),
(74, 'Il fatto che si possono richiamare pi&ugrave; metodi sullo stesso oggetto', 0, 20),
(75, 'Un metodo di una superclasse viene ridefinito in una sottoclasse, cambiandone il codice', 1, 21),
(76, 'Vi sono pi&ugrave; metodi in una classe con lo stesso nome, ma parametri differenti', 0, 21),
(77, 'Vi sono dei metodi ereditati di default dalla classe object, che possiamo utilizzare nelle sottoclassi', 0, 21),
(78, 'Una classe pu&ograve; ereditare attributi e metodi di una superclasse senza doverli riscrivere', 0, 21),
(79, '__init__', 1, 22),
(80, '__str__', 0, 22),
(81, '__add__', 0, 22),
(82, '__len__', 0, 22),
(83, 'Metodo', 1, 23),
(84, 'Funzione di classe', 0, 23),
(85, 'Operazione', 0, 23),
(86, 'Fabbrica', 0, 23),
(87, 'Ereditariet&agrave; multipla', 1, 25),
(88, 'Ereditariet&agrave; multilivello', 0, 25),
(89, 'Ereditariet&agrave; gerarchica', 0, 25),
(90, 'Ereditariet&agrave; single-level', 0, 25),
(91, 'Oggetto', 1, 26),
(92, 'Attributo', 0, 26),
(93, 'Metodo', 0, 26),
(94, 'OOP', 0, 26),
(95, 'shadow = Cane(&quot;Shadow&quot;)', 0, 27),
(96, 'cane = Cane(&quot;Shadow&quot;)', 0, 27),
(97, 'rex = Cane()', 0, 27),
(98, 'stella = new Cane(&quot;Stella&quot;)', 1, 27),
(99, 'Uno dei vantaggi della OOP &egrave; che pu&ograve; nascondere la complessit&agrave; dell&#039;implementazione di un oggetto tramite incapsulamento', 0, 28),
(100, 'Una grossa opportunit&agrave; che d&agrave; la OOP &egrave; la possibilit&agrave; di costruire una classe a partire da una classe base, estendendola con nuove funzionalit&agrave;', 0, 28),
(101, 'Una classe contiene funzioni dette metodi e variabili dette attributi, che vengono utilizzate dai metodi', 0, 28),
(102, 'Un vantaggio della OOP &egrave; che rende i programmi portabili su diversi sistemi operativi', 1, 28),
(103, 'class', 1, 29),
(104, 'instance', 0, 29),
(105, 'object', 0, 29),
(106, 'import', 0, 29),
(107, 'class', 0, 30),
(108, 'def', 0, 30),
(109, 'self', 1, 30),
(110, 'init', 0, 30),
(111, 'Vero', 0, 31),
(112, 'Falso', 1, 31),
(113, 'def __init__(titolo, autore):', 0, 32),
(114, 'def __init__(self, titolo, autore):', 1, 32),
(115, 'def __init__():', 0, 32),
(116, '__init__(self, titolo, autore):', 0, 32),
(117, 'x contiene un valore intero', 0, 33),
(118, 'x contiene un riferimento ad un oggetto di calsse Cerchio', 1, 33),
(119, 'x contiene il valore dell&#039;area di un cerchio', 0, 33),
(120, 'la classe Cerchio contiene un attributo denominato x', 0, 33),
(121, '__str__', 0, 34),
(122, '__init__', 0, 34),
(123, '__add__', 1, 34),
(124, '__many__', 0, 34),
(125, 'class', 0, 48),
(126, 'def', 0, 48),
(127, 'self', 1, 48),
(128, 'init', 0, 48),
(129, 'Definisce attributi e metodi di una classe', 0, 49),
(130, 'Viene richiamata quando un nuovo oggetto viene istanziato', 1, 49),
(131, 'Inizializza sempre tutti gli attributi a 0 quando viene richiamata', 0, 49),
(132, 'Nessuna delle altre risposte &egrave;  corretta', 0, 49),
(133, 'ogg.stampa()', 1, 50),
(134, 'from ogg import stampa()', 0, 50),
(135, 'ogg-&gt;stampa()', 0, 50),
(136, 'stampa(ogg)', 0, 50),
(137, 'def __init__(self, a):', 1, 51),
(138, 'def init(self, x):', 0, 51),
(139, 'def __const__(self,a):', 0, 51),
(140, 'def __init(self):', 0, 51),
(141, 'Un Oggetto', 1, 52),
(142, 'Un Attributo', 0, 52),
(143, 'Un Metodo', 0, 52),
(144, 'Una OOP', 0, 52),
(145, 'cane = Cane(&quot;Shadow&quot;)', 0, 53),
(146, 'rex = Cane()', 0, 53),
(147, 'stella = new Cane(&quot;Stella&quot;)', 1, 53),
(148, 'shadow = Cane(&quot;Shadow&quot;)', 0, 53),
(149, '__init__()', 1, 54),
(150, '__new__()', 0, 54),
(151, '__create__()', 0, 54),
(152, '__instance__()', 0, 54),
(153, '__str__()', 1, 55),
(154, '__repr__()', 0, 55),
(155, '__tostring__()', 0, 55),
(156, '__convert__()', 0, 55),
(157, 'super().methodName()', 1, 56),
(158, 'super.methodName()', 0, 56),
(159, 'parent.methodName()', 0, 56),
(160, 'parent().methodName()', 0, 56),
(161, 'void', 0, 57),
(162, 'null', 0, 57),
(163, 'None', 1, 57),
(164, 'empty', 0, 57),
(165, 'write', 0, 62),
(166, 'append', 0, 62),
(167, 'open', 1, 62),
(168, 'update', 0, 62),
(169, 'readline', 0, 62),
(170, 'Sezioni di codice che possono essere richiamate pi&ugrave; volte all&#039;interno di un programma', 1, 98),
(171, 'Cicli for con variabili di iterazione complesse', 0, 98),
(172, 'Istruzioni condizionali annidate', 0, 98),
(173, 'Variabili locali all&#039;interno di una funzione', 0, 98),
(174, 'Instradamenti di flusso alternativi', 0, 98),
(175, 'Un&#039;istruzione break', 0, 99),
(176, 'Una struttura condizionale if-else', 0, 99),
(177, 'Una funzione definita dall&#039;utente che calcola la media di una lista di numeri', 1, 99),
(178, 'Una variabile globale', 0, 99),
(179, 'Un&#039;istruzione pass', 0, 99),
(180, 'Un&#039;istruzione condizionale', 0, 100),
(181, 'Una variabile globale', 0, 100),
(182, 'Una funzione o procedura separata', 1, 100),
(183, 'Un&#039;operazione matematica', 0, 100),
(184, 'Solo nei sottoprogrammi', 0, 101),
(185, 'Solo tra gli argomenti', 0, 101),
(186, 'In nessun punto del programma', 0, 101),
(187, 'In qualunque punto del programma', 1, 101),
(188, 'Il passaggio per valore consente di modificare direttamente la variabile originale passata alla funzione, per riferimento lavora su una sua copia', 0, 103),
(189, 'Il passaggio per riferimento permette alla funzione di modificare direttamente l&#039;oggetto originale, mentre il passaggio per valore lavora su una copia.', 1, 103),
(190, 'Il passaggio per riferimento &egrave; usato solo nei linguaggi orientati agli oggetti, mentre quello per valore nei linguaggi procedurali.', 0, 103),
(191, 'Il passaggio per riferimento permette di usare i parametri di default, mentre il passaggio per valore no', 0, 103),
(192, 'Record e File', 0, 107),
(193, 'Liste e Dizionari', 1, 107),
(194, 'Pile e Code', 0, 107),
(195, 'Array e Matrici', 0, 107),
(196, 'Stringhe e Booleani', 0, 107),
(197, 'Definire le istruzioni condizionali', 0, 108),
(198, 'Organizzare e gestire raccolte di dati', 1, 108),
(199, 'Creare diagrammi di flusso complessi', 0, 108),
(200, 'Rappresentare insiemi matematici', 0, 108),
(201, 'x(2)', 0, 112),
(202, 'x[1]', 1, 112),
(203, 'x{1}', 0, 112),
(204, 'x&lt;2&gt;', 0, 112),
(205, 'lista', 0, 113),
(206, 'dizionario', 0, 113),
(207, 'insieme', 0, 113),
(208, 'tupla', 1, 113),
(209, '[1, 2, 2, 3]', 0, 114),
(210, '{1, 2, 3}', 1, 114),
(211, '(1, 2, 2, 3)', 0, 114),
(212, '{1: 2, 2: 1, 3: 1}', 0, 114),
(213, 'add()', 0, 115),
(214, 'append()', 1, 115),
(215, 'insert()', 0, 115),
(216, 'update()', 0, 115),
(217, 'lista', 0, 116),
(218, 'insieme', 0, 116),
(219, 'dizionario', 1, 116),
(220, 'tupla', 0, 116),
(221, '{}', 0, 117),
(222, '[]', 0, 117),
(223, 'set()', 1, 117),
(224, 'dict()', 0, 117),
(225, 'Restituisce i valori', 0, 118),
(226, 'Restituisce le coppie chiave/valore', 0, 118),
(227, 'Restituisce le chiavi', 1, 118),
(228, 'Cancella le chiavi', 0, 118),
(229, 'append()', 0, 119),
(230, 'pop()', 0, 119),
(231, 'concatenazione con +', 1, 119),
(232, 'remove()', 0, 119),
(233, 'insieme', 0, 120),
(234, 'tupla', 0, 120),
(235, 'lista', 1, 120),
(236, 'dizionario', 0, 120),
(237, 'L&#039;utilizzo di una variabile senza inizializzarla', 0, 138),
(238, 'L&#039;annidamento di pi&ugrave; funzioni all&#039;interno di una stessa funzione', 0, 138),
(239, 'La chiamata di una funzione da parte di se stessa', 1, 138),
(240, 'La ricreazione di una variabile ogni volta che viene utilizzata', 0, 138),
(241, 'La ripetizione di un&#039;istruzione per un numero definito di volte', 0, 138),
(242, 'O(1)', 0, 150),
(243, 'O(log n)', 0, 150),
(244, 'O(n)', 1, 150),
(245, 'O(n log n)', 0, 150),
(246, 'O(n^2)', 0, 150),
(247, 'O(&radic;n)', 0, 150),
(248, 'Accesso diretto per indice', 0, 151),
(249, 'Ricerca binaria', 0, 151),
(250, 'Inserimento in testa', 1, 151),
(251, 'Ordinamento', 0, 151),
(252, 'Accesso casuale', 0, 151),
(253, 'Calcolo della lunghezza', 0, 151),
(254, 'Solo il dato', 0, 152),
(255, 'Dato e puntatore al successivo', 0, 152),
(256, 'Dato e puntatore al precedente', 0, 152),
(257, 'Dato e due puntatori', 1, 152),
(258, 'Tre puntatori', 0, 152),
(259, 'Nessun puntatore', 0, 152),
(260, '1', 0, 153),
(261, '2', 1, 153),
(262, '3', 0, 153),
(263, 'Illimitati', 0, 153),
(264, 'Dipende dall altezza', 0, 153),
(265, 'Nessuno', 0, 153),
(266, 'Preorder', 0, 154),
(267, 'Postorder', 0, 154),
(268, 'Level order', 0, 154),
(269, 'Inorder', 1, 154),
(270, 'DFS casuale', 0, 154),
(271, 'BFS inversa', 0, 154),
(272, 'O(1)', 0, 155),
(273, 'O(log n)', 0, 155),
(274, 'O(n)', 1, 155),
(275, 'O(n log n)', 0, 155),
(276, 'O(n^2)', 0, 155),
(277, 'O(&radic;n)', 0, 155),
(278, 'Solo vertici', 0, 156),
(279, 'Solo archi', 0, 156),
(280, 'Vertici e archi', 1, 156),
(281, 'Solo pesi', 0, 156),
(282, 'Matrici', 0, 156),
(283, 'Liste', 0, 156),
(284, 'Numero totale di nodi', 0, 157),
(285, 'Numero di archi uscenti', 0, 157),
(286, 'Numero di archi entranti', 1, 157),
(287, 'Numero di cicli', 0, 157),
(288, 'Numero di componenti', 0, 157),
(289, 'Numero di cammini minimi', 0, 157),
(290, 'Un grafo con almeno un ciclo', 0, 158),
(291, 'Un grafo connesso e aciclico', 1, 158),
(292, 'Un grafo orientato con pesi', 0, 158),
(293, 'Un grafo completo', 0, 158),
(294, 'Una lista concatenata', 0, 158),
(295, 'Un DAG qualsiasi', 0, 158),
(296, 'Proiezione', 0, 176),
(297, 'Selezione', 1, 176),
(298, 'Intersezione', 0, 176),
(299, 'Differenza', 0, 176),
(300, 'Unione', 1, 177),
(301, 'Join', 0, 177),
(302, 'Selezione', 0, 177),
(303, 'Prodotto cartesiano', 0, 177),
(304, 'Unione', 0, 178),
(305, 'Intersezione', 1, 178),
(306, 'Differenza', 0, 178),
(307, 'Join', 0, 178),
(308, 'Join', 0, 179),
(309, 'Unione', 0, 179),
(310, 'Differenza', 1, 179),
(311, 'Proiezione', 0, 179),
(312, 'Prodotto cartesiano', 1, 180),
(313, 'Join', 0, 180),
(314, 'Selezione', 0, 180),
(315, 'Intersezione', 0, 180),
(316, 'Join', 1, 181),
(317, 'Unione', 0, 181),
(318, 'Proiezione', 0, 181),
(319, 'Differenza', 0, 181),
(320, 'Join', 1, 182),
(321, 'Proiezione', 0, 182),
(322, 'Intersezione', 0, 182),
(323, 'Unione', 0, 182),
(324, 'Join', 0, 183),
(325, 'Selezione', 0, 183),
(326, 'Proiezione', 1, 183),
(327, 'Intersezione', 0, 183),
(328, 'Join', 1, 184),
(329, 'Unione', 0, 184),
(330, 'Differenza', 0, 184),
(331, 'Proiezione', 0, 184),
(332, 'Proiezione', 0, 185),
(333, 'Join', 0, 185),
(334, 'Selezione', 1, 185),
(335, 'Unione', 0, 185),
(336, 'Selezione', 0, 186),
(337, 'Proiezione', 1, 186),
(338, 'Join', 0, 186),
(339, 'Intersezione', 0, 186),
(340, 'Unione', 0, 187),
(341, 'Differenza', 0, 187),
(342, 'Join', 1, 187),
(343, 'Selezione', 0, 187),
(344, 'Unione', 1, 188),
(345, 'Intersezione', 0, 188),
(346, 'Proiezione', 0, 188),
(347, 'Differenza', 0, 188),
(348, 'Join', 0, 189),
(349, 'Differenza', 0, 189),
(350, 'Unione', 0, 189),
(351, 'Intersezione', 1, 189),
(352, 'Intersezione', 0, 190),
(353, 'Differenza', 1, 190),
(354, 'Unione', 0, 190),
(355, 'Selezione', 0, 190),
(356, 'Prodotto cartesiano', 1, 191),
(357, 'Join', 0, 191),
(358, 'Unione', 0, 191),
(359, 'Differenza', 0, 191),
(360, 'Join', 0, 192),
(361, 'Proiezione', 1, 192),
(362, 'Selezione', 0, 192),
(363, 'Unione', 0, 192),
(364, 'Bubble sort', 1, 193),
(365, 'Insertion sort', 0, 193),
(366, 'Selection sort', 0, 193),
(367, 'Merge sort', 0, 193),
(368, 'Bubble sort', 0, 194),
(369, 'Insertion sort', 0, 194),
(370, 'Selection sort', 1, 194),
(371, 'Merge sort', 0, 194),
(372, 'Ricerca binaria', 1, 195),
(373, 'Ricerca ordinata', 0, 195),
(374, 'Ricerca per tentativi', 0, 195),
(375, 'Ricerca lineare', 0, 195),
(376, 'Bubble sort', 0, 196),
(377, 'Insertion sort', 0, 196),
(378, 'Selection sort', 0, 196),
(379, 'Merge sort', 1, 196),
(380, 'Ricerca binaria', 0, 197),
(381, 'Ricerca sequenziale', 1, 197),
(382, 'Ricerca per tentativi', 0, 197),
(383, 'Ricerca ordinata', 0, 197),
(384, 'O(n^2)', 1, 198),
(385, 'O(1)', 0, 198),
(386, 'O(n)', 0, 198),
(387, 'O(n log n)', 0, 198),
(388, 'O(n)', 1, 199),
(389, 'O(n^2)', 0, 199),
(390, 'O(1)', 0, 199),
(391, 'O(log n)', 0, 199),
(392, 'O(n)', 0, 200),
(393, 'O(n^2)', 0, 200),
(394, 'O(1)', 0, 200),
(395, 'O(log n)', 1, 200),
(396, 'Not SQL', 0, 201),
(397, 'Not Only SQL', 1, 201),
(398, 'Near Only SQL', 0, 201),
(399, 'Not or SQL', 0, 201),
(400, 'Utilizzano tabelle a schema fisso', 0, 202),
(401, 'Sono schema-less', 1, 202),
(402, 'Utilizzano sia tabelle a schema fisso che documenti a schema libero', 0, 202),
(403, 'Hanno necessit&agrave; di definire lo schema di dati da utilizzare in anticipo', 0, 202),
(404, 'La propriet&agrave; di avere tutti i dati in un unico server sempre attivo', 0, 203),
(405, 'La propriet&agrave; di garantire la disponibilit&agrave; dei dati anche nel caso di caduta di alcuni nodi in un sistema distribuito', 1, 203),
(406, 'La propriet&agrave; di garantire transazioni con modalit&agrave; ACID', 0, 203),
(407, 'La propriet&agrave; di garantire la consistenza dei dati in ogni singolo istante', 0, 203),
(408, 'Che i dati sono sempre disponibili fintanto che l&#039;host di database &egrave; online', 0, 204),
(409, 'Che viene garantito il fatto che i dati siano consistenti dopo un certo tempo se non avvengono modifiche', 1, 204),
(410, 'Che si garantisce in ogni istante la completa consistenza dell&#039;intero database', 0, 204),
(411, 'Che i dati vengono salvati su documenti anzich&eacute; su tabelle', 0, 204),
(412, 'Posso avere al massimo 2 tra le seguenti propriet&agrave; per un db: Coerenza, Disponibilit&agrave;, Tolleranza di partizione', 1, 205),
(413, 'Solo in rari casi riesco ad ottenere tutte e tre le seguenti propriet&agrave; per un db: Coerenza, Disponibilit&agrave;, Tolleranza di partizione', 0, 205),
(414, 'Posso avere al massimo una tra le seguenti propriet&agrave;: Coerenza, Disponibilit&agrave;, Scalabilit&agrave; orizzontale', 0, 205),
(415, 'Posso avere al massimo 2 tra le seguenti propriet&agrave; per un db: Coerenza, Flessibilit&agrave;, Tolleranza di partizione', 0, 205),
(416, 'Coerenza: tutti i nodi vedono gli stessi dati allo stesso tempo', 0, 206),
(417, 'Availability: ogni operazione riceve sempre una risposta', 0, 206),
(418, 'Partition Tolerance: il sistema pu&ograve; aggiungere nodi, un nodo pu&ograve; cadere o essere rimosso in ottica distribuita', 1, 206),
(419, 'Elevate prestazioni: i dati sono raggiungibili in breve tempo', 0, 206),
(420, 'Se ogni richiesta deve avere risposta e tutti devono vedere lo stesso dato, allora non posso avere distribuzione del db', 0, 207),
(421, 'Se ogni richiesta deve avere risposta e ho un db distribuito, devo necessariamente duplicare i dati, che potrebbero essere diversi da un nodo all&#039;altro per un certo tempo', 1, 207),
(422, 'Se ho db distribuito e i dati devono essere gli stessi per tutti, allora potrei non avere risposta ad alcune domande, fintanto che si propagano le modifiche', 0, 207),
(423, 'Se ogni richiesta deve avere risposta e ho un db distribuito, allora se cade un nodo della rete non riesco pi&ugrave; ad accedere ai dati', 0, 207),
(424, 'Orientato alle colonne', 0, 208),
(425, 'A documenti', 0, 208),
(426, 'A grafo', 0, 208),
(427, 'A valori duplicati', 1, 208),
(428, 'MongoDB', 0, 209),
(429, 'Cassandra', 0, 209),
(430, 'CouchDB', 0, 209),
(431, 'Oracle', 1, 209),
(432, 'XML', 0, 210),
(433, 'YAML', 0, 210),
(434, 'JSON', 1, 210),
(435, 'Testo semplice', 0, 210),
(436, 'A bus', 0, 211),
(437, 'A maglia', 0, 211),
(438, 'Ad anello', 1, 211),
(439, 'A stella', 0, 211),
(440, 'Chiave esterna', 0, 226),
(441, 'Chiave candidata', 0, 226),
(442, 'Chiave primaria', 1, 226),
(443, 'Chiave artificiale', 0, 226),
(444, 'Chiave esterna', 0, 227),
(445, 'Chiave composta', 1, 227),
(446, 'Chiave candidata', 0, 227),
(447, 'Chiave secondaria', 0, 227),
(448, 'Chiave esterna', 0, 228),
(449, 'Chiave composta', 0, 228),
(450, 'Chiave candidata', 1, 228),
(451, 'Chiave artificiale', 0, 228),
(452, 'Chiave primaria', 0, 229),
(453, 'Chiave artificiale', 0, 229),
(454, 'Chiave esterna', 1, 229),
(455, 'Chiave candidata', 0, 229),
(456, 'Chiave composta', 0, 230),
(457, 'Chiave artificiale', 1, 230),
(458, 'Chiave esterna', 0, 230),
(459, 'Chiave candidata', 0, 230),
(460, 'Sono derivate da attributi esistenti', 0, 231),
(461, 'Sono scelte tra le chiavi candidate', 0, 231),
(462, 'Sono generate artificialmente e non derivano da attributi esistenti', 1, 231),
(463, 'Possono contenere riferimenti esterni', 0, 231),
(464, 'una chiave candidata', 0, 232),
(465, 'una chiave artificiale', 0, 232),
(466, 'una chiave composta', 0, 232),
(467, 'una chiave esterna', 1, 232),
(468, 'Garantire l&rsquo;univocit&agrave; dei dati nella tabella', 0, 233),
(469, 'Permettere collegamenti tra entit&agrave; diverse', 1, 233),
(470, 'Creare nuovi identificatori artificiali', 0, 233),
(471, 'Evitare ridondanza nei nomi degli attributi', 0, 233),
(472, 'Un identificatore univoco per gli ordini', 0, 234),
(473, 'Un collegamento tra ordine e cliente', 1, 234),
(474, 'Un attributo calcolato', 0, 234),
(475, 'Un campo facoltativo', 0, 234),
(476, 'Chiave candidata', 0, 235),
(477, 'Chiave composta', 0, 235),
(478, 'Chiave esterna', 1, 235),
(479, 'Chiave artificiale', 0, 235),
(480, 'Chiusa di tipo EULA', 0, 296),
(481, 'Open Source', 1, 296),
(482, 'Creative Commons', 0, 296),
(483, 'ADWare', 0, 296),
(484, 'Crea un nuovo blocco di codice, la inseriamo ad esempio dopo un if', 1, 297),
(485, 'Pu&ograve; essere inserita ovunque, non d&agrave; problemi', 0, 297),
(486, 'Serve ad inserire un commento', 0, 297),
(487, 'Serve solamente ad avere del codice pi&ugrave; ordinato', 0, 297),
(488, 'a=b=c=5', 1, 298),
(489, 'a=5, b=5, c=5', 0, 298),
(490, 'a==b==c==5', 0, 298),
(491, 'a!=b=!c!=5', 0, 298),
(492, 'la stringa &quot;a5&quot;', 0, 299),
(493, 'la stringa &quot;5a&quot;', 0, 299),
(494, 'la stringa &quot;aaaaa&quot;', 1, 299),
(495, 'Errore', 0, 299),
(496, 'Modificare il valore di una variabile', 0, 300),
(497, 'Modificare il tipo di una variabile', 1, 300),
(498, 'Solo trasformare una stringa in un intero', 0, 300),
(499, 'Richiedere in input un tipo intero', 0, 300),
(500, 'try', 0, 301),
(501, 'for', 0, 301),
(502, 'switch', 0, 301),
(503, 'if', 1, 301),
(504, 'Utilizzando la parola chiave &quot;final&quot;', 0, 302),
(505, 'Utilizzando la parola chiave &quot;constant&quot;', 0, 302),
(506, 'Utilizzando il simbolo &quot;=&quot; per assegnare un valore al nome della variabile', 1, 302),
(507, 'Utilizzando il simbolo &quot;:&quot; davanti al nome della variabile', 0, 302),
(508, 'Che ogni operazione &egrave; veloce', 0, 309),
(509, 'Che i dati sono leggibili', 0, 309),
(510, 'Che una transazione venga completata tutta o nulla', 1, 309),
(511, 'Che la transazione &egrave; fatta da un solo utente', 0, 309),
(512, 'Che tutti i dati sono in formato JSON', 0, 310),
(513, 'Che i dati sono duplicati', 0, 310),
(514, 'Che i dati rispettano i vincoli definiti nel DB', 1, 310),
(515, 'Che i dati sono ordinati alfabeticamente', 0, 310),
(516, 'Accesso pi&ugrave; veloce', 0, 311),
(517, 'Ridondanza e incongruenza dei dati', 1, 311),
(518, 'Maggiore sicurezza', 0, 311),
(519, 'Miglior backup', 0, 311),
(520, 'A creare pagine web', 0, 312),
(521, 'A visualizzare PDF', 0, 312),
(522, 'A gestire e organizzare i dati in un database', 1, 312),
(523, 'A comprimere file', 0, 312),
(524, 'Che il database &egrave; criptato', 0, 313),
(525, 'Che ogni transazione &egrave; indipendente da altre concorrenti', 1, 313),
(526, 'Che il database non &egrave; accessibile', 0, 313),
(527, 'Che solo l&#039;amministratore pu&ograve; accedere', 0, 313),
(528, 'Aumento della ridondanza', 0, 314),
(529, 'Difficolt&agrave; nell&#039;accesso concorrente', 0, 314),
(530, 'Controllo centralizzato dei dati', 1, 314),
(531, 'Costi maggiori', 0, 314),
(532, 'Miglioramento delle performance', 0, 315),
(533, 'Dati sempre aggiornati', 0, 315),
(534, 'Aggiornamenti incoerenti e perdita di dati', 1, 315),
(535, 'Maggiore sicurezza', 0, 315),
(536, 'Che i dati vengono eliminati subito dopo l&rsquo;uso', 0, 316),
(537, 'Che i dati possono essere modificati da chiunque', 0, 316),
(538, 'Che i dati persistono anche dopo un crash', 1, 316),
(539, 'Che i dati sono temporanei', 0, 316),
(540, 'Dati accessibili a tutti', 0, 317),
(541, 'Dati non formattati', 0, 317),
(542, 'Ripetizione inutile delle stesse informazioni', 1, 317),
(543, 'Dati mancanti nel database', 0, 317),
(544, 'Un&#039;app di messaggistica', 0, 318),
(545, 'Una raccolta strutturata di dati gestita da un sistema software', 1, 318),
(546, 'Un foglio di calcolo', 0, 318),
(547, 'Un browser web', 0, 318),
(548, 'Durabilit&agrave;', 0, 319),
(549, 'Vincoli di integrit&agrave; referenziale', 0, 319),
(550, 'Atomicit&agrave;', 1, 319),
(551, 'Isolamento', 0, 319),
(552, 'Isolamento', 1, 320),
(553, 'Consistenza', 0, 320),
(554, 'Durabilit&agrave;', 0, 320),
(555, 'Atomicit&agrave;', 0, 320),
(556, 'Isolamento', 0, 321),
(557, 'Durabilit&agrave;', 0, 321),
(558, 'Consistenza', 1, 321),
(559, 'Atomicit&agrave;', 0, 321),
(560, 'Consistenza', 0, 322),
(561, 'Atomicit&agrave;', 0, 322),
(562, 'Isolamento', 0, 322),
(563, 'Durabilit&agrave;', 1, 322),
(564, '3lato', 0, 331),
(565, 'lato triangolo', 0, 331),
(566, 'latoTriangolo', 1, 331),
(567, 'lato_triangolo!', 0, 331),
(568, 'Una rappresentazione grafica del flusso delle istruzioni di un programma', 1, 345),
(569, 'Una lista delle parole chiave del linguaggio di programmazione', 0, 345),
(570, 'Un elenco delle funzioni disponibili in Python', 0, 345),
(571, 'Una tabella riassuntiva delle variabili utilizzate nel programma', 0, 345),
(572, 'Una collezione di esempi di codice Python', 0, 345),
(573, 'Una funzione da richiamare', 0, 346),
(574, 'Una condizione da verificare', 1, 346),
(575, 'Un&#039;operazione aritmetica da compiere', 0, 346),
(576, 'Una variabile da inizializzare', 0, 346),
(577, 'Un&#039;istruzione da eseguire', 0, 346),
(578, 'Su un disco fisso', 0, 347),
(579, 'Nella memoria ROM', 0, 347),
(580, 'Nella CPU', 0, 347),
(581, 'Nella memoria RAM', 1, 347),
(582, 'Nell&#039;ALU', 0, 347),
(583, 'Rappresentare graficamente l&#039;algoritmo', 1, 348),
(584, 'Definire le variabili del programma', 0, 348),
(585, 'Eseguire istruzioni condizionali', 0, 348),
(586, 'Creare strutture dati complesse', 0, 348),
(587, 'Variabili', 0, 349),
(588, 'Istruzioni condizionali', 1, 349),
(589, 'Strutture dati', 0, 349),
(590, 'Sottoprogrammi', 0, 349),
(591, 'Memorizzare dati temporanei', 1, 350),
(592, 'Creare cicli di ripetizione', 0, 350),
(593, 'Definire funzioni personalizzate', 0, 350),
(594, 'Creare diagrammi di flusso', 0, 350),
(595, 'javac', 1, 354),
(596, 'java', 0, 354),
(597, 'rmic', 0, 354),
(598, 'gcc', 0, 354),
(599, 'Una speciale variabile salvata a livello di sistema operativo', 1, 355),
(600, 'Una variabile di tipo intero dichiarata senza assegnare un valore', 0, 355),
(601, 'E&#039; un sinonimo di costante in Java', 0, 355),
(602, 'Una variabile speciale Java visibile a tutte le possibili classi di un programma', 0, 355),
(603, 'JDK (Java Development Kit)', 1, 356),
(604, 'JRE (Java Runtime Environment)', 0, 356),
(605, 'J2EE (Java 2 Enterprise Edition)', 0, 356),
(606, 'JSP (Java Server Pages)', 0, 356),
(607, 'Un comando per eseguire un&#039;istruzione, per esempio if', 0, 357),
(608, 'Il nome che assegnamo ad una variabile', 1, 357),
(609, 'Una lista di elementi', 0, 357),
(610, 'Un ciclo indefinito', 0, 357),
(611, 'Parole che identificano i comandi del linguaggio, ad esempio if', 1, 358),
(612, 'I nomi che vengono assegnati alle variabili', 0, 358),
(613, 'Una convenzione dei programmatori per dichiarare costanti', 0, 358),
(614, 'La possibilit&agrave; di inserire caratteri speciali all&#039;interno delle stringhe usando backslash prima del carattere', 0, 358),
(615, 'Che lettere maiuscole e minuscole fanno differenza nei nomi di variabile, ad esempio pippo e Pippo sono due variabili diverse', 1, 359),
(616, 'Che non &egrave; pi&ugrave; possibile cambiare il tipo di una variabile dopo la sua dichiarazione', 0, 359),
(617, 'Che il tipo di una variabile viene dedotto dal valore che le assegnamo', 0, 359),
(618, 'Che la convenzione per la scrittura delle variabili in Java &egrave; prima lettera minuscola, se vi sono pi&ugrave; parole, dalla seconda in poi vanno con l&#039;iniziale maiuscola', 0, 359),
(619, 'lato', 0, 360),
(620, '3lato', 1, 360),
(621, 'lato3', 0, 360),
(622, 'latoTriangolo', 0, 360),
(623, 'latoQuadrato', 1, 361),
(624, 'lato_quadrato', 0, 361),
(625, 'LatoQuadrato', 0, 361),
(626, 'latoquadrato', 0, 361),
(627, 'const PIGRECO = 3.14;', 0, 362),
(628, 'final double PIGRECO = 3.14;', 1, 362),
(629, 'static float PIGRECO = 3.14f;', 0, 362),
(630, 'double PIGRECO = 3.14;', 0, 362),
(631, 'Modificare il tipo di una variabile, per esempio trasformando un double in un intero', 1, 363),
(632, 'Che il compilatore capisce da solo qual &egrave; il tipo di una variabile', 0, 363),
(633, 'Che non possiamo modificare il tipo di una variabile dopo la sua dichiarazione', 0, 363),
(634, 'Che posso assegnare un valore ad una variabile', 0, 363),
(635, 'Che java esegue in automatico il cast tra tipi diversi se non c&#039;&egrave; perdita di informazione', 1, 364),
(636, 'Che posso sempre modificare il tipo di una variabile', 0, 364),
(637, 'Che in Java devo sempre effettuare un cast esplicito per modificare il tipo di una variabile', 0, 364),
(638, 'Che in Java non &egrave; mai possibile modificare il tipo di una variabile', 0, 364),
(639, 'Che una volta assegnato un tipo ad una variabile, poi non lo posso pi&ugrave; modificare', 1, 365),
(640, 'Che posso modificare dinamicamente il tipo di una variabile, assegnandole un diverso valore', 0, 365),
(641, 'Che il compilatore capisce da solo qual &egrave; il tipo di una variabile, in base al valore assegnatole', 0, 365),
(642, 'Che vi sono 4 tipi di variabile: int, float, boolean, char', 0, 365),
(643, 'Ogni variabile deve avere un suo tipo, non modificabile', 1, 366),
(644, 'Le variabili possono assumere tipi diversi durante l&#039;esecuzione del programma', 0, 366),
(645, 'Il compilatore o l&#039;interprete capiscono da soli il tipo della variabile in base al valore assegnato', 0, 366),
(646, 'Che vi &egrave; una differenziazione tra tipi primitivi e tipi riferimento', 0, 366),
(647, 'AND', 0, 367),
(648, '&amp;&amp;', 1, 367),
(649, '||', 0, 367),
(650, '!%', 0, 367),
(651, '&amp;&amp;', 0, 368),
(652, 'OR', 0, 368),
(653, '||', 1, 368),
(654, '!?', 0, 368),
(655, 'Una libreria che posso importare all&#039;interno dei miei programmi, simile ai moduli di Python', 1, 369),
(656, 'E&#039; l&#039;istruzione di assegnazione in Java', 0, 369),
(657, 'E&#039; uno strumento per creare liste di elementi, simile alle liste di Python', 0, 369),
(658, 'E&#039; il programma eseguibile su JVM ottenuto dal compilatore', 0, 369),
(659, 'E&#039; il codice ottenuto dalla compilazione di un programma Java, interpretabile dalla JVM', 1, 370),
(660, 'E&#039; una libreria Java che posso importare all&#039;interno dei programmi, come per i moduli in Python', 0, 370),
(661, 'E&#039; un programma direttamente eseguibile dal sistema operativo con estensione .exe', 0, 370),
(662, 'E&#039; un sinonimo di codice binario formato da 0 e 1', 0, 370),
(663, 'E&#039; una via di mezzo tra il linguaggio di alto livello Java e il linguaggio macchina', 1, 371),
(664, 'Pu&ograve; essere mandato in esecuzione direttamente alla CPU, senza che vi sia bisogno di altri programmi', 0, 371),
(665, 'E&#039; il file Java scritto dal programmatore con il codice sorgente', 0, 371),
(666, 'E&#039; l&#039;interprete che trasforma codice Java in codice macchina eseguibile dal processore', 0, 371),
(667, 'E&#039; la Java Virtual Machine, l&#039;interprete che trasforma il ByteCode in codice eseguibile dalla CPU', 1, 372),
(668, 'E&#039; la Java Visual Procedure, una specifica procedura per effettuare input in Java', 0, 372),
(669, 'E&#039; la Java Vehicle Merge, il compilatore che trasforma il codice sorgente in codice eseguibile', 0, 372),
(670, 'Sta per Just Virtual Merchandise, indica il fatto che Java &egrave; portabile su pi&ugrave; sistemi operativi', 0, 372),
(671, 'Scanner', 1, 373),
(672, 'Input', 0, 373),
(673, 'String', 0, 373),
(674, 'System.out', 0, 373),
(675, 'System.out.println(&quot;stringa&quot;);', 1, 374),
(676, 'System.in;', 0, 374),
(677, 'print(&quot;stringa&quot;);', 0, 374),
(678, 'Scanner output = new Scanner(&quot;stringa&quot;);', 0, 374),
(679, 'int x = myScanner.nextInt();', 1, 375),
(680, 'double x = myScanner.nextDouble();', 0, 375),
(681, 'int x = new Scanner(System.in);', 0, 375),
(682, 'int x = myScanner.input(&quot;Inserire un numero intero&quot;);', 0, 375),
(683, 'Con due barre //', 1, 376),
(684, 'Con /* pe iniziare e */ per finire', 0, 376),
(685, 'Con il carattere cancelletto #', 0, 376),
(686, 'Con i caratteri ?$', 0, 376),
(687, 'Con due barre //', 0, 377),
(688, 'Con /* per iniziare e */ per finire', 1, 377),
(689, 'Inizia con i caratteri &lt;!-- e finisce con --&gt;', 0, 377),
(690, 'Con tre asterischi *** per iniziare e due per finire **', 0, 377),
(691, 'Il risultato sar&agrave; di tipo int e la parte decimale verr&agrave; troncata senza arrotondamenti', 1, 378),
(692, 'Il risultato sar&agrave; di tipo int se non c&#039;&egrave; resto nella divisione, di tipo double altrimenti', 0, 378),
(693, 'Il risultato sar&agrave; sempre di tipo double', 0, 378),
(694, 'Il risultato sar&agrave; di tipo int e il numero verr&agrave; arrotondato (quindi se parte decimale &egrave; superiore a .5 si arrotonda per eccesso)', 0, 378),
(695, 'La variabile sar&agrave; sempre visibile anche all&#039;esterno di quel blocco di codice', 0, 379),
(696, 'La variabile sar&agrave; visibile solo all&#039;interno di quel blocco di codice e nei suoi blocchi interni', 1, 379),
(697, 'Se il blocco di codice contiene un altro blocco di codice, la variabile non sar&agrave; visibile nel blocco interno', 0, 379),
(698, 'Le variabili devono essere dichiarate all&#039;inizio e non le posso dichiarare all&#039;interno di blocchi di codice successivi', 0, 379),
(699, 'Sono equivalenti, non vi sono differenze tra i due', 0, 380),
(700, 'Il doppio &amp;&amp; si ferma appena trova una condizione falsa ritornando falso, senza controllare le altre condizioni, il singolo &amp; controlla sempre tutte le condizioni', 1, 380),
(701, 'Il singolo &amp; si ferma appena trova una condizione falsa ritornando vero, senza controllare le altre condizioni, il doppio &amp;&amp; controlla sempre tutte le condizioni', 0, 380),
(702, 'Il doppio &amp;&amp; controlla sempre tutte le condizioni inserite, il singolo &amp; si ferma appena trova una condizione vera ritornando vero', 0, 380),
(703, 'Il doppio || si ferma non appena trova una condizione vera e ritorna vero, senza controllare le altre condizioni, il singolo | controlla sempre tutte le condizioni', 1, 381),
(704, 'Il doppio || si ferma non appena trova una condizione vera e ritorna falso, senza controllare le altre condizioni, il singolo | controlla sempre tutte le condizioni', 0, 381),
(705, 'Il singolo | si ferma non appena trova una condizione vera e ritorna vero, senza controllare le altre condizioni, il doppio || controlla sempre tutte le condizioni', 0, 381),
(706, 'Non vi sono differenze tra i due', 0, 381),
(707, 'Oggetto della classe', 1, 393),
(708, 'Costruttore della classe', 0, 393),
(709, 'Attributo della classe', 0, 393),
(710, 'Metodo della classe', 0, 393),
(711, 'Indica la propriet&agrave; degli oggetti di incorporare al loro interno attributi e metodi', 1, 394),
(712, 'Indica il fatto che gli attributi di una classe sono privati', 0, 394),
(713, 'Indica il fatto che una classe non pu&ograve; avere sottoclassi', 0, 394),
(714, 'E&#039; una propriet&agrave; Java che ci permette di creare delle costanti', 0, 394),
(715, 'E&#039; un metodo speciale con lo stesso nome della classe', 1, 395),
(716, 'E&#039; un qualsiasi metodo della classe', 0, 395),
(717, 'Non pu&ograve; avere parametri', 0, 395),
(718, 'E&#039; una variabile d&#039;istanza della classe', 0, 395),
(719, 'E&#039; un attributo valorizzato per un dato oggetto', 1, 396),
(720, 'E&#039; una variabile dichiarata all&#039;interno di un qualsiasi metodo', 0, 396),
(721, 'E&#039; una variabile dichiarata nel metodo main', 0, 396),
(722, 'E&#039; una variabile globale visibile a tutto il programma Java', 0, 396),
(723, 'Cerchio c  = new Cerchio(8);', 1, 397),
(724, 'Cerchio c = __init__(8);', 0, 397),
(725, 'Cerchio c = new Cerchio[8];', 0, 397),
(726, 'Cerchio c = Cerchio(8);', 0, 397),
(727, 'public int somma()', 0, 398),
(728, 'protected double divisione(int x,int y)', 0, 398),
(729, 'String saluto()', 0, 398),
(730, 'public versamento(int denaro)', 1, 398),
(731, 'E&#039; un attributo della classe', 0, 399),
(732, 'E&#039; una variabile definita all&#039;interno di un metodo e visibile solo al suo interno', 1, 399),
(733, 'E&#039; una varaibile visibile dall&#039;intero programma Java', 0, 399),
(734, 'E&#039; una variabile definita con la parola chiave static', 0, 399),
(735, 'Viene usata per definire costanti', 1, 400),
(736, 'Viene usata per creare varaibili globali', 0, 400),
(737, 'Viene usata per creare variabili d&#039;istanza', 0, 400),
(738, 'Viene usata per definire metodi richiamabili con nomeClasse.nomeMetodo()', 0, 400),
(739, 'Viene usata quando ci si riferisce ad un riferimento nullo, che non punta a nessun oggetto', 1, 401),
(740, 'Viene usata quando non &egrave; stato dato un valore ad un tipo primitivo, come int', 0, 401),
(741, 'Viene usata quando voglio confrontare due stringhe ed essere sicuro che siano uguali', 0, 401),
(742, 'Viene usata quando voglio riferirmi ad un attributo della classe all&#039;interno del codice di un metodo', 0, 401),
(743, 'Array a1 = new Array(5,Automobile)', 0, 402),
(744, 'Automobile a1 = new Array[5]', 0, 402),
(745, 'Automobile a1[] = new Automobile[5]', 1, 402),
(746, 'Automobile a1 = new Automobile(5)', 0, 402),
(747, 'L&#039;insieme di attributi e metodi privati di una classe', 1, 406),
(748, 'L&#039;insieme dei metodi che il programmatore pu&ograve; utilizzare su di un oggetto di una certa classe', 0, 406),
(749, 'E&#039; un sinonimo di costruttore della classe', 0, 406),
(750, 'E&#039; il package contenente la classe', 0, 406),
(751, 'Un modello di un oggetto reale, con le sue caratterisitche e funzionalit&agrave;', 1, 407),
(752, 'Una particolare funzione che pu&ograve; essere importata all&#039;interno dei programmi', 0, 407),
(753, 'E&#039; un sinonimo di package in Java', 0, 407),
(754, 'E&#039; una libreria che mi permette di eseguire operazioni complesse', 0, 407),
(755, 'r1.altezza', 0, 408),
(756, 'Non &egrave; possibile accedere direttamente agli attributi privati di una classe da una classe esterna', 1, 408),
(757, 'r1.__altezza', 0, 408),
(758, 'get(r1,altezza)', 0, 408),
(759, 'ogg.calcolaPerimetro()', 1, 409),
(760, 'ogg-&gt;calcolaPerimetro()', 0, 409),
(761, 'calcolaPerimetro(ogg)', 0, 409),
(762, 'import ogg.calcolaPerimetro()', 0, 409),
(763, 'Il mascheramento delle modalit&agrave; di implementazione di un oggetto, rendendone disponibili le sole funzionalit&agrave;', 1, 410),
(764, 'Il fatto di poter attribuire alle classi attributi e metodi pubblici', 0, 410),
(765, 'Il fatto che non possiamo sapere come sia implementato il package java.util.Random', 0, 410),
(766, 'La condivisione di informazioni tra oggetti della stessa classe', 0, 410),
(767, 'x contiene un valore intero', 0, 411),
(768, 'x contiene un riferimento ad un oggetto di classe Cerchio', 1, 411),
(769, 'x contiene il valore dell&#039;area di un cerchio', 0, 411),
(770, 'la classe Cerchio contiene un attributo denominato x', 0, 411),
(771, 'Tutti gli attributi della classe sono pubblici', 0, 413),
(772, 'Una parte degli attributi della classe sono privati', 1, 413),
(773, 'Si inserisce un metodo toString() all&rsquo;interno della classe per trasformarla in stringa', 0, 413),
(774, 'Inserisco attributi statici all&rsquo;interno della classe', 0, 413),
(775, 'Ho un riferimento null', 1, 414),
(776, 'Posso chiamare il metodo toString() su quel riferimento, perch&eacute; ereditato dalla classe Object', 0, 414),
(777, 'Posso accedere agli attributi dell&rsquo;oggetto con l&rsquo;operatore . (punto)', 0, 414),
(778, 'Non posso farlo a meno che la classe non sia stata dichiarata static', 0, 414),
(779, 'Dichiarare una variabile di classe, anzich&eacute; legata agli oggetti', 0, 415),
(780, 'Dichiarare un metodo Costruttore', 0, 415),
(781, 'Dichiarare una costante, che una volta inizializzata non pu&ograve; pi&ugrave; essere modificata', 1, 415),
(782, 'Dichiarare che la classe che stiamo costruendo eredita da una superclasse', 0, 415),
(783, 'Solo se i nomi dei due costruttori sono diversi', 0, 416),
(784, 'Solo se contengono gli stessi parametri', 0, 416),
(785, 'Solo se contengono parametri diversi', 1, 416),
(786, 'Falso, non &egrave; mai possibile dichiarare pi&ugrave; costruttori per una classe in Java', 0, 416),
(787, 'public void ClassName()', 0, 417),
(788, 'public ClassName()', 1, 417),
(789, 'private ClassName()', 0, 417),
(790, 'Non &egrave; possibile definire una classe senza un costruttore', 0, 417),
(791, 'extends', 1, 418),
(792, 'inherits', 0, 418),
(793, 'implements', 0, 418),
(794, 'extendsOf', 0, 418),
(795, 'public', 0, 419),
(796, 'private', 0, 419),
(797, 'protected', 1, 419),
(798, 'default', 0, 419),
(799, 'void', 1, 420),
(800, 'null', 0, 420),
(801, 'none', 0, 420),
(802, 'empty', 0, 420),
(803, 'Poter creare degli oggetti semplificati a partire dall&#039;interfaccia', 0, 425),
(804, 'Specificare un contratto che le classi devono seguire', 1, 425),
(805, 'Danno la possibilit&agrave; di aggiungere metodi statici alle classi', 0, 425),
(806, 'Consentono l&#039;Information hiding dato che tutti gli attributi sono privati', 0, 425),
(807, 'Sottoclasse', 1, 427),
(808, 'Superclasse', 0, 427),
(809, 'Classe ad oggetti', 0, 427),
(810, 'Costruttore', 0, 427),
(811, 'Estensione e ridefinizione', 1, 428),
(812, 'Estensione e polimorfismo', 0, 428),
(813, 'Ridefinizione e polimorfismo', 0, 428),
(814, 'Overloading e overriding', 0, 428),
(815, 'Ereditariet&agrave; singola', 1, 429),
(816, 'Ereditariet&agrave; multipla', 0, 429),
(817, 'Ereditariet&agrave; gerarchica', 0, 429),
(818, 'Ereditariet&agrave; multilivello', 0, 429),
(819, 'Ereditariet&agrave; singola', 0, 430),
(820, 'Ereditariet&agrave; multilivello', 0, 430),
(821, 'Ereditariet&agrave; multipla', 1, 430),
(822, 'Ereditariet&agrave; gerarchica', 0, 430),
(823, 'No, mai', 0, 431),
(824, 'Si, sempre, per qualsiasi classe', 0, 431),
(825, 'Si, ma solo se ereditiamo da una classe singola e pi&ugrave; interfacce', 1, 431),
(826, 'Si, solo se ereditiamo da una singola interfaccia e pi&ugrave; classi', 0, 431),
(827, 'extends', 1, 432),
(828, 'super', 0, 432),
(829, 'implements', 0, 432),
(830, 'Inserisco la superclasse tra parentesi dopo la dichiarazione della classe', 0, 432),
(831, 'Object', 1, 433),
(832, 'Main', 0, 433),
(833, 'Util', 0, 433),
(834, 'Javac', 0, 433),
(835, 'Overloading', 1, 434),
(836, 'Overriding', 0, 434),
(837, 'Information Hiding', 0, 434),
(838, 'Dei costruttori della classe', 0, 434),
(839, 'Overriding', 1, 435),
(840, 'Overloading', 0, 435),
(841, 'Information Hiding', 0, 435),
(842, 'Encapsulation', 0, 435),
(843, 'Non vengono mai ereditati dalle sottoclassi', 1, 436),
(844, 'Vengono sempre ereditati dalle sottoclassi', 0, 436),
(845, 'Vengono ereditati dalle sottoclassi solo se sono anche protected', 0, 436),
(846, 'Vengono ereditati dalle sottoclassi solo se le sottoclassi sono interfacce', 0, 436),
(847, 'Salvare dati all&#039;interno di una memoria secondaria', 1, 439),
(848, 'Salvare dati all&#039;interno della memoria RAM', 0, 439),
(849, 'Salvare dati all&#039;interno di una memoria ROM', 0, 439),
(850, 'Visualizzare dati all&#039;interno del blocco note', 0, 439),
(851, 'Apertura', 0, 440),
(852, 'Chiusura', 0, 440),
(853, 'Scrittura', 0, 440),
(854, 'Modellazione', 1, 440),
(855, 'Crea un collegamento tra memoria centrale e memoria secondaria', 1, 441),
(856, 'Crea un collegamento tra la RAM e la stampante', 0, 441),
(857, 'Non &egrave; mai possibile se il file non &egrave; gi&agrave; presente su hard disk', 0, 441),
(858, 'Crea un collegamento tra il disco fisso e lo schermo dove visualizzo il file', 0, 441),
(859, 'Operazione di input: i dati passano dalla memoria secondaria al programma in esecuzione', 1, 442),
(860, 'Operazione di output: i dati passano dalla memoria secondaria al programma in esecuzione', 0, 442),
(861, 'Operazione di input: i dati passano dal programma in esecuzione alla memoria secondaria', 0, 442),
(862, 'Operazione di output: i dati passano dal programma in esecuzione alla memoria secondaria', 0, 442),
(863, 'Operazione di output: i dati passano dal programma in esecuzione alla memoria secondaria', 1, 443),
(864, 'Operazione di input: i dati passano dal programma in esecuzione alla memoria secondaria', 0, 443),
(865, 'Operazione di input: i dati passano dalla memoria secondaria al programma in esecuzione', 0, 443),
(866, 'Operazione di output: i dati passano dalla memoria secondaria al programma in esecuzione', 0, 443),
(867, 'java.io', 1, 444),
(868, 'java.util', 0, 444),
(869, 'java.lang', 0, 444),
(870, 'java.awt', 0, 444),
(871, 'Un flusso di dati', 1, 445),
(872, 'Una connessione Internet', 0, 445),
(873, 'Un programma per visualizzare film o ascoltare musica (appunto in streaming)', 0, 445),
(874, 'Una classe Java ereditata', 0, 445),
(875, 'Basate sui Byte e basate sui Caratteri', 1, 446),
(876, 'Basate sulle Stringhe e basate sui bit', 0, 446),
(877, 'Basate sull&#039;input e basate sull&#039;output', 0, 446),
(878, 'Basate su binario e basate su eadecimale', 0, 446),
(879, 'File strutturati e File di testo', 1, 447),
(880, 'File binari e File esadecimali', 0, 447),
(881, 'File eseguibili e file di dati', 0, 447),
(882, 'File pesanti e file leggeri', 0, 447),
(883, 'Deve avere una struttura definita che devo conoscere se voglio leggere il file', 1, 448),
(884, 'Contiene solo righe di testo', 0, 448),
(885, 'Non pu&ograve; mai contenere stringhe', 0, 448),
(886, 'Ha una struttura variabile e posso leggere i dati nell&#039;ordine che preferisco', 0, 448),
(887, 'Ereditare dalla classe Object', 0, 449),
(888, 'Implementare l&#039;interfaccia Serializable', 1, 449),
(889, 'Non essere sottoclasse di nesssuna superclasse', 0, 449),
(890, 'Ereditare dalla classe Stream', 0, 449),
(891, 'Non possono mai dare errori', 0, 450),
(892, 'Possono generare eccezioni che vanno gestite con blocchi try..catch', 1, 450),
(893, 'Possono generare eccezioni che possiamo anche non gestire, della gestione si preoccupa la JVM', 0, 450),
(894, 'Generano in ogni caso l&#039;eccezione IOException, che deve essere dichiarata all&#039;inizio del programma', 0, 450),
(895, 'Split', 0, 451),
(896, 'StringTokenizer', 1, 451),
(897, 'StringExplode', 0, 451),
(898, 'StripCharacter', 0, 451),
(899, 'Il programma &egrave; stato compilato, creando un eseguibile. Lo posso lanciare direttamente dal sistema operativo', 1, 461),
(900, 'Il programma ha bisogno di un interprete per essere eseguito', 0, 461),
(901, 'Il programma non &egrave; stato scritto da un programmatore', 0, 461),
(902, 'Il programma &egrave; stato compilato, ma serve anche un programma interprete per eseguirlo', 0, 461),
(903, 'Il programma &egrave; in codice macchina, praticamente direttamente eseguibile dalla CPU', 1, 462),
(904, 'Il programma &egrave; in bytecode, &egrave; stato compilato, ma necessita di un interprete per essere eseguito', 0, 462),
(905, 'Il programma &egrave; in codice sorgente, viene mandato in esecuzione riga per riga da un interprete', 0, 462),
(906, 'Il programma &egrave; in codice ottale, accede alla memoria ROM', 0, 462),
(907, 'Esegue pi&ugrave; rapidamente di un programma interpretato', 1, 463),
(908, 'Esegue pi&ugrave; lentamente rispetto un programma interpretato', 0, 463),
(909, 'Esegue pi&ugrave; o meno alla stessa velocit&agrave; di un programma interpretato', 0, 463),
(910, 'Non pu&ograve; accedere alla memoria RAM, quindi &egrave; limitato rispetto un programma interpretato', 0, 463),
(911, 'Posso utilizzarlo tranquillamente anche su Windows', 0, 464),
(912, 'Funzioner&agrave; solo per il sistema Linux', 1, 464),
(913, 'Viene eseguito pi&ugrave; velocemente rispetto a Windows, ma utilizzo lo stesso programma compilato', 0, 464),
(914, 'Non lo posso mai compilare anche per Windows, utilizzando un diverso compilatore', 0, 464),
(915, 'Lo pu&ograve; lanciare direttamente dal sistema operativo', 0, 465),
(916, 'Deve installare l&#039;interprete Python per poterlo eseguire', 1, 465),
(917, 'Deve installare un compilatore, compilare il programma e poi lo pu&ograve; lanciare', 0, 465),
(918, 'Non potr&agrave; mai lanciare il programma Python', 0, 465),
(919, 'Editor di testo, compilatore o interprete, debugger', 1, 466),
(920, 'Editor di testo, programma eseguibile, cartella di progetto', 0, 466),
(921, 'Debugger, compilatore o interprete, ma non l&#039;editor di testo', 0, 466),
(922, 'Solo editor di testo', 0, 466),
(923, 'wile scritto al posto di while', 1, 467),
(924, 'if: senza la condizione', 0, 467),
(925, 'Calcolare l&#039;area del triangolo come base * altezza (senza /2)', 0, 467),
(926, 'Tentare di dividere per 0', 0, 467),
(927, 'di seguire il programma un&rsquo;istruzione per volta, cos&igrave; da verificarne la corretta evoluzione', 0, 468),
(928, 'di controllare i valori assunti dalle variabili durante l&rsquo;esecuzione del programma', 0, 468),
(929, 'di stabilire punti di interruzione (breakpoint) durante l&rsquo;esecuzione per stabilire dei controlli', 0, 468),
(930, 'la correzione in automatico degli errori (bug) senza l&#039;intervento del programmatore', 1, 468),
(931, 'Dare nomi significativi alle variabili', 0, 469),
(932, 'Inserire molti commenti', 0, 469),
(933, 'Scrivere un manuale di istruzioi per l&#039;utilizzatore finale', 0, 469),
(934, 'Inserire meno cicli possibile per non appesantire il programma', 1, 469),
(935, 'Nascondere informazioni sensibili all&#039;interno della classe', 1, 470),
(936, 'Mostrare tutte le informazioni pubblicamente', 0, 470),
(937, 'Condividere le informazioni con altre classi', 0, 470),
(938, 'Ignorare le informazioni all&#039;interno della classe', 0, 470),
(939, 'Nascondere le informazioni all&#039;interno della classe', 0, 471),
(940, 'Creare nuove istanze di una classe', 0, 471),
(941, 'Estendere il comportamento di una classe esistente', 1, 471),
(942, 'Eliminare le informazioni dalla classe genitore', 0, 471),
(943, 'Un&#039;istanza di un oggetto', 0, 472),
(944, 'Una collezione di attributi e metodi', 1, 472),
(945, 'Un&#039;istanza di un metodo', 0, 472),
(946, 'Una funzione che opera su dati', 0, 472),
(947, 'Una classe &egrave; istanza di un oggetto, mentre un oggetto &egrave; un&#039;istanza di una classe', 0, 473),
(948, 'Una classe contiene metodi, mentre un oggetto contiene dati', 0, 473),
(949, 'Una classe &egrave; statica, mentre un oggetto &egrave; dinamico', 0, 473),
(950, 'Una classe &egrave; astratta, mentre un oggetto &egrave; concreto', 1, 473),
(951, 'Nascondere un metodo di una classe genitore', 0, 474),
(952, 'Aggiungere un nuovo metodo a una classe', 0, 474),
(953, 'Sostituire un metodo di una classe genitore in una sottoclasse', 1, 474),
(954, 'Eliminare un metodo da una classe', 0, 474),
(955, 'Nascondendo i dettagli di implementazione', 0, 475),
(956, 'Consentendo la riutilizzazione del codice esistente', 1, 475);
INSERT INTO `ct_risposte` (`id_risposta`, `risposta`, `corretta`, `fk_domanda`) VALUES
(957, 'Aggregando oggetti in una classe', 0, 475),
(958, 'Creando classi intermedie', 0, 475),
(959, '::', 0, 476),
(960, '-&gt;', 0, 476),
(961, '.', 1, 476),
(962, '&gt;&gt;', 0, 476),
(963, 'Funzione', 0, 477),
(964, 'Classe', 1, 477),
(965, 'Oggetto', 0, 477),
(966, 'Interfaccia', 0, 477),
(967, 'Composizione', 0, 478),
(968, 'Aggregazione', 0, 478),
(969, 'Ereditariet&agrave;', 1, 478),
(970, 'Associazione', 0, 478),
(971, 'LIFO Last In First Out', 1, 479),
(972, 'FIFO First In First Out', 0, 479),
(973, 'LILO Last In Last Out', 0, 479),
(974, 'Round Robin', 0, 479),
(975, 'LIFO Last In First Out', 0, 480),
(976, 'FIFO First In First Out', 1, 480),
(977, 'Round Robin', 0, 480),
(978, 'FILO First In Last Out', 0, 480),
(979, 'Un modello da cui creare oggetti', 1, 481),
(980, 'Un comando per stampare a video', 0, 481),
(981, 'Un tipo di ciclo iterativo', 0, 481),
(982, 'Un file eseguibile', 0, 481),
(983, 'Una variabile che descrive lo stato degli oggetti', 1, 482),
(984, 'Un metodo che esegue calcoli', 0, 482),
(985, 'Una libreria esterna', 0, 482),
(986, 'Una parola chiave per i cicli', 0, 482),
(987, 'Un&#039;operazione o comportamento degli oggetti', 1, 483),
(988, 'Un valore numerico costante', 0, 483),
(989, 'Un commento del programma', 0, 483),
(990, 'Un tipo di visibilit&agrave;', 0, 483),
(991, 'private', 1, 484),
(992, 'public', 0, 484),
(993, 'protected', 0, 484),
(994, 'static', 0, 484),
(995, 'public', 1, 485),
(996, 'private', 0, 485),
(997, 'protected', 0, 485),
(998, 'default', 0, 485),
(999, 'Nascondere i dettagli interni di una classe mostrando solo ci&ograve; che serve', 1, 486),
(1000, 'Eliminare tutti gli attributi privati', 0, 486),
(1001, 'Scrivere classi senza metodi', 0, 486),
(1002, 'Usare solo variabili pubbliche', 0, 486),
(1003, 'Attraverso metodi pubblici come getter e setter', 1, 487),
(1004, 'Accedendo direttamente dall&#039;esterno', 0, 487),
(1005, 'Trasformandolo sempre in public', 0, 487),
(1006, 'Usando un ciclo for', 0, 487),
(1007, 'Un metodo speciale usato per inizializzare un oggetto', 1, 488),
(1008, 'Un attributo obbligatorio della classe', 0, 488),
(1009, 'Un metodo che restituisce sempre int', 0, 488),
(1010, 'Una classe gi&agrave; pronta del linguaggio', 0, 488),
(1011, 'Ha lo stesso nome della classe', 1, 489),
(1012, 'Deve essere sempre public', 0, 489),
(1013, 'Restituisce sempre un oggetto', 0, 489),
(1014, 'Pu&ograve; essere scritto una sola volta', 0, 489),
(1015, 'Il compilatore fornisce un costruttore di default senza parametri', 1, 490),
(1016, 'La classe non pu&ograve; essere istanziata', 0, 490),
(1017, 'Tutti gli attributi diventano public', 0, 490),
(1018, 'I metodi non possono essere usati', 0, 490),
(1019, 'Il meccanismo con cui una classe deriva caratteristiche da un&#039;altra classe', 1, 491),
(1020, 'La possibilit&agrave; di creare pi&ugrave; oggetti uguali', 0, 491),
(1021, 'L&#039;obbligo di usare attributi privati', 0, 491),
(1022, 'Un tipo speciale di costruttore', 0, 491),
(1023, 'extends', 1, 492),
(1024, 'implements', 0, 492),
(1025, 'inherits', 0, 492),
(1026, 'super', 0, 492),
(1027, 'sottoclasse', 1, 493),
(1028, 'metodo', 0, 493),
(1029, 'interfaccia', 0, 493),
(1030, 'istanza', 0, 493),
(1031, 'Definire pi&ugrave; metodi con lo stesso nome ma parametri diversi', 1, 494),
(1032, 'Ridefinire un metodo ereditato con la stessa firma', 0, 494),
(1033, 'Nascondere un attributo privato', 0, 494),
(1034, 'Creare un metodo dentro un altro metodo', 0, 494),
(1035, 'Ridefinire in una sottoclasse un metodo ereditato mantenendo la stessa firma', 1, 495),
(1036, 'Scrivere due costruttori identici', 0, 495),
(1037, 'Usare un attributo con lo stesso nome in due classi', 0, 495),
(1038, 'Dichiarare un metodo private', 0, 495),
(1039, 'I metodi devono avere parametri diversi', 1, 496),
(1040, 'I metodi devono appartenere a classi diverse', 0, 496),
(1041, 'I metodi devono avere nomi diversi', 0, 496),
(1042, 'I metodi devono restituire sempre void', 0, 496),
(1043, 'Il metodo nella sottoclasse deve avere stessa firma del metodo della superclasse', 1, 497),
(1044, 'Il metodo deve avere per forza parametri diversi', 0, 497),
(1045, 'Il metodo deve essere static', 0, 497),
(1046, 'Il metodo deve cambiare nome', 0, 497),
(1047, 'A richiamare il costruttore della superclasse', 1, 498),
(1048, 'A creare una nuova sottoclasse', 0, 498),
(1049, 'A rendere un attributo privato', 0, 498),
(1050, 'A sovraccaricare un metodo', 0, 498),
(1051, 'Per proteggere lo stato interno dell&#039;oggetto', 1, 499),
(1052, 'Per permettere l&#039;accesso diretto da ogni classe', 0, 499),
(1053, 'Per obbligare l&#039;ereditariet&agrave;', 0, 499),
(1054, 'Per evitare l&#039;uso dei metodi', 0, 499),
(1055, 'Studente eredita attributi e metodi accessibili di Persona', 1, 500),
(1056, 'Persona eredita tutto da Studente', 0, 500),
(1057, 'Studente non pu&ograve; avere metodi propri', 0, 500),
(1058, 'Persona e Studente devono essere nello stesso file', 0, 500);

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_set_personalizzazioni`
--

CREATE TABLE `ct_set_personalizzazioni` (
  `id_set` int NOT NULL,
  `nome_set` varchar(200) NOT NULL,
  `colore_set` varchar(100) NOT NULL,
  `tipologia` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ct_set_personalizzazioni`
--

INSERT INTO `ct_set_personalizzazioni` (`id_set`, `nome_set`, `colore_set`, `tipologia`) VALUES
(4, 'Bandiere', '#edde35', 'Sfondo'),
(5, 'Sfondi', '#2f80ed', 'Sfondo'),
(6, 'BigBackgrounds', '#20a22f', 'BigBackground'),
(7, 'Animali Mitologici', '#f70202', 'Pet'),
(8, 'Maglie del calcio', '#29ff69', 'Equipaggiamento'),
(9, 'Armature Base', '#7c7f83', 'Equipaggiamento');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studenti_classi`
--

CREATE TABLE `ct_studenti_classi` (
  `id_stud_classe` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_classe` int NOT NULL,
  `esercizi_cons` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_studenti_poteri`
--

CREATE TABLE `ct_studenti_poteri` (
  `id_stud_pot` int NOT NULL,
  `fk_studente` int NOT NULL,
  `fk_potere` int NOT NULL,
  `usato` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(354, 1, 354),
(355, 1, 355),
(356, 1, 356),
(357, 1, 357),
(358, 1, 358),
(359, 1, 359),
(360, 1, 360),
(361, 1, 361),
(362, 1, 362),
(363, 1, 363),
(364, 1, 364),
(365, 1, 365),
(366, 1, 366),
(367, 1, 367),
(368, 1, 368),
(369, 1, 369),
(370, 1, 370),
(371, 1, 371),
(372, 1, 372),
(373, 1, 373),
(374, 1, 374),
(375, 1, 375),
(376, 1, 376),
(377, 1, 377),
(378, 1, 378),
(379, 1, 379),
(380, 1, 380),
(381, 1, 381),
(382, 1, 382),
(383, 1, 383),
(384, 1, 384),
(385, 1, 385),
(386, 1, 386),
(387, 1, 387),
(388, 1, 388),
(389, 1, 389),
(390, 1, 390),
(391, 1, 391),
(392, 1, 392),
(393, 1, 393),
(394, 1, 394),
(395, 1, 395),
(396, 1, 396),
(397, 1, 397),
(398, 1, 398),
(399, 1, 399),
(400, 1, 400),
(401, 1, 401),
(402, 1, 402),
(403, 1, 403),
(404, 1, 404),
(405, 1, 405),
(406, 1, 406),
(407, 1, 407),
(408, 1, 408),
(409, 1, 409),
(410, 1, 410),
(411, 1, 411),
(412, 1, 412),
(413, 1, 413),
(414, 1, 414),
(415, 1, 415),
(416, 1, 416),
(417, 1, 417),
(418, 1, 418),
(419, 1, 419),
(420, 1, 420),
(421, 1, 421),
(422, 1, 422),
(423, 1, 423),
(424, 1, 424),
(425, 1, 425),
(426, 1, 426),
(427, 1, 427),
(428, 1, 428),
(429, 1, 429),
(430, 1, 430),
(431, 1, 431),
(432, 1, 432),
(433, 1, 433),
(434, 1, 434),
(435, 1, 435),
(436, 1, 436),
(437, 1, 437),
(438, 1, 438),
(439, 1, 439),
(440, 1, 440),
(441, 1, 441),
(442, 1, 442),
(443, 1, 443),
(444, 1, 444),
(445, 1, 445),
(446, 1, 446),
(447, 1, 447),
(448, 1, 448),
(449, 1, 449),
(450, 1, 450),
(451, 1, 451),
(452, 1, 452),
(453, 1, 453),
(454, 1, 454),
(455, 1, 455),
(456, 1, 456),
(457, 1, 457),
(458, 1, 458),
(459, 1, 459),
(460, 1, 460),
(461, 1, 461),
(462, 1, 462),
(463, 1, 463),
(464, 1, 464),
(465, 1, 465),
(466, 1, 466),
(467, 1, 467),
(468, 1, 468),
(469, 1, 469),
(470, 1, 470),
(471, 1, 471),
(472, 1, 472),
(473, 1, 473),
(474, 1, 474),
(475, 1, 475),
(476, 1, 476),
(477, 1, 477),
(478, 1, 478),
(479, 1, 479),
(480, 1, 480),
(481, 1, 481),
(482, 1, 482),
(483, 1, 483),
(484, 1, 484),
(485, 1, 485),
(486, 1, 486),
(487, 1, 487),
(488, 1, 488),
(489, 1, 489),
(490, 1, 490),
(491, 1, 491),
(492, 1, 492),
(493, 1, 493),
(494, 1, 494),
(495, 1, 495),
(496, 1, 496),
(497, 1, 497),
(498, 1, 498),
(499, 1, 499),
(500, 1, 500);

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
(1, 'Admin', 'Admin', 'admin', '$2y$10$OFkv3q3fjOy7ynAfOfMWM.4OrY/SfGmc0XSiYt41yOjMH8UZ8GGL2', '', NULL, 1, 1, 0, '', 'M', '', 'it');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  MODIFY `id_classe_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `ct_consegne_studenti`
--
ALTER TABLE `ct_consegne_studenti`
  MODIFY `id_consegna` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_domande`
--
ALTER TABLE `ct_domande`
  MODIFY `id_domanda` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;

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
  MODIFY `id_materiale` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
  MODIFY `id_personaggio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `ct_personalizzazioni`
--
ALTER TABLE `ct_personalizzazioni`
  MODIFY `id_personalizzazione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

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
  MODIFY `id_punizione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT per la tabella `ct_quest`
--
ALTER TABLE `ct_quest`
  MODIFY `id_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id_utente_dom` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;

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
