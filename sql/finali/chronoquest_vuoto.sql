-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Giu 10, 2026 alle 12:01
-- Versione del server: 8.0.45
-- Versione PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chronoquest`
--

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `ct_plugin` (
  `id_plugin` int NOT NULL,
  `nome_plugin` varchar(255) NOT NULL,
  `versione` varchar(10) NOT NULL,
  `attivo` int NOT NULL,
  `codice_plugin` varchar(255) NOT NULL,
  `descrizione` text,
  `configurazione_json` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS ct_plugin_classe (
  id_plugin_classe int NOT NULL AUTO_INCREMENT,
  fk_plugin int NOT NULL,
  fk_classe int NOT NULL,
  attivo int NOT NULL DEFAULT 0,
  configurazione_json text NULL,
  PRIMARY KEY (id_plugin_classe),
  UNIQUE KEY uq_ct_plugin_classe_plugin_classe (fk_plugin, fk_classe)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(10, '2034/2035')
;

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

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_classi_quest`
--

CREATE TABLE `ct_classi_quest` (
  `id_classe_quest` int NOT NULL,
  `fk_classe` int NOT NULL,
  `fk_quest` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Struttura della tabella `ct_materie`
--

CREATE TABLE `ct_materie` (
  `id_materia` int NOT NULL,
  `nome_materia` varchar(200) NOT NULL,
  `uuid` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(31, 'Aumenta il mana', 'Aumenta permanentemente di 1 il mana massimo. Il potere viene dimenticato dopo l\'utilizzo e va scelto nuovamente per poter essere usato\r\n', '/assets/images/Poteri/683c54cf4e318_aumenta_mana.jpg', 16, 4, 1, 1, 0);

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
(1, 'Admin', 'Admin', 'admin', '$2y$10$E75W3txWTVS0gd9WOYLJxOehCrlnq4K8jQvSdMcDhZuqbyxaw68LW', '', NULL, 1, 1, 1, '', 'M', '', 'en');

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
  MODIFY `id_abilita` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_abilita_equipaggiamento`
--
ALTER TABLE `ct_abilita_equipaggiamento`
  MODIFY `id_abilita_equip` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_alerts`
--
ALTER TABLE `ct_alerts`
  MODIFY `id_alert` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_anni_scolastici`
--
ALTER TABLE `ct_anni_scolastici`
  MODIFY `id_anno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `ct_argomenti`
--
ALTER TABLE `ct_argomenti`
  MODIFY `id_argomento` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_argomenti_kahoot`
--
ALTER TABLE `ct_argomenti_kahoot`
  MODIFY `id_arg_kahoot` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_badge`
--
ALTER TABLE `ct_badge`
  MODIFY `id_badge` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_badge_alunni`
--
ALTER TABLE `ct_badge_alunni`
  MODIFY `id_badge_alunno` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_capitoli`
--
ALTER TABLE `ct_capitoli`
  MODIFY `id_capitolo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `ct_capitoli_quest`
--
ALTER TABLE `ct_capitoli_quest`
  MODIFY `id_cap_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `ct_classi`
--
ALTER TABLE `ct_classi`
  MODIFY `id_classe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_classi_esercizi_attivi`
--
ALTER TABLE `ct_classi_esercizi_attivi`
  MODIFY `id_attivi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_classi_quest`
--
ALTER TABLE `ct_classi_quest`
  MODIFY `id_classe_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ct_consegne_studenti`
--
ALTER TABLE `ct_consegne_studenti`
  MODIFY `id_consegna` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_domande`
--
ALTER TABLE `ct_domande`
  MODIFY `id_domanda` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_esercizi`
--
ALTER TABLE `ct_esercizi`
  MODIFY `id_esercizio` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_ese_quest` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_materia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ct_messages`
--
ALTER TABLE `ct_messages`
  MODIFY `id_messaggio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_personaggi`
--
ALTER TABLE `ct_personaggi`
  MODIFY `id_personaggio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_personalizzazioni`
--
ALTER TABLE `ct_personalizzazioni`
  MODIFY `id_personalizzazione` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_poteri`
--
ALTER TABLE `ct_poteri`
  MODIFY `id_potere` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `ct_punizioni`
--
ALTER TABLE `ct_punizioni`
  MODIFY `id_punizione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `ct_quest`
--
ALTER TABLE `ct_quest`
  MODIFY `id_quest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id_risposta` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ct_set_personalizzazioni`
--
ALTER TABLE `ct_set_personalizzazioni`
  MODIFY `id_set` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_traduzione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `ct_utente_domande`
--
ALTER TABLE `ct_utente_domande`
  MODIFY `id_utente_dom` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_utmat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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

ALTER TABLE ct_consegne_studenti
  ADD COLUMN data_consegna datetime NULL AFTER file_consegnato;

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
