-- phpMyAdmin SQL Dump
-- version 4.5.5.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 26, 2017 at 01:48 PM
-- Server version: 5.6.30-1
-- PHP Version: 5.6.22-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bailleux_cuiteur`
--

-- --------------------------------------------------------

--
-- Table structure for table `blablas`
--

CREATE TABLE `blablas` (
  `blID` int(10) UNSIGNED NOT NULL,
  `blIDAuteur` int(10) UNSIGNED NOT NULL,
  `blDate` int(8) UNSIGNED NOT NULL DEFAULT '0',
  `blHeure` char(8) COLLATE latin1_general_ci NOT NULL,
  `blTexte` char(255) COLLATE latin1_general_ci NOT NULL,
  `blAvecCible` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `blIDOriginal` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `blablas`
--

INSERT INTO `blablas` (`blID`, `blIDAuteur`, `blDate`, `blHeure`, `blTexte`, `blAvecCible`, `blIDOriginal`) VALUES
(81, 21, 20160125, '16:53:35', 'L\'intelligence artificielle se définit comme le contraire de la bêtise naturelle. #ia #intelligence', 0, 0),
(82, 3, 20160125, '16:58:19', 'La théorie, c\'est quand on sait tout et que rien ne fonctionne. La pratique, c\'est quand tout fonctionne et que personne ne sait pourquoi. Si la pratique et la théorie sont réunies, rien ne fonctionne et on ne sait pas pourquoi.', 0, 0),
(83, 3, 20160125, '17:02:27', 'Les #USA sont le seul pays du monde à être passés directement de la barbarie à la décadence sans passer par la civilisation.', 0, 0),
(84, 4, 20160126, '17:03:17', '@albert Quand les riches se font la guerre, ce sont les pauvres qui meurent. #Jedi', 1, 0),
(85, 6, 20160126, '17:04:36', '@yoda Si on a pu prendre en charge les baby-boomers quand ils étaient enfants, pourquoi ne peut-on pas le faire quand ils ont plus de soixante ans? C\'est une simple question de priorités financières.', 1, 0),
(86, 2, 20160126, '17:06:26', 'A la faculté de médecine et de pharmacie, il est communément admis que les comprimés ne sont pas systématiquement des imbéciles diplômés. #zéro', 0, 0),
(87, 4, 20160126, '17:07:14', 'Ne pas trop réfléchir sur la valeur de l\'Histoire. On court le risque de s\'en dégoûter. #Aie', 0, 0),
(88, 4, 20160126, '17:08:17', '@albert @chomsky @yoda Dans la vie on ne fait pas ce que l\'on veut mais on est responsable de ce que l\'on est.', 1, 0),
(89, 1, 20160128, '17:09:11', 'Un conducteur dangereux (@pdac), c\'est celui qui vous dépasse malgré tous vos efforts pour l\'en empêcher.', 0, 0),
(90, 1, 20160128, '17:13:04', '@chomsky L\'avantage d\'être intelligent, c\'est qu\'on peut toujours faire l\'#imbécile, alors que l\'inverse est totalement impossible. #lol', 1, 0),
(91, 6, 20160128, '17:11:03', 'On ne peut, la nature des choses étant ce qu\'elle est, s\'attendre à ce qu\'un petit arbre qui a été transformé en gourdin engendre des feuilles (même pour @nono)', 0, 0),
(92, 3, 20160128, '17:25:38', 'L\'homme évite habituellement d\'accorder de l\'intelligence à autrui, sauf quand par hasard il s\'agit d\'un ennemi. #intelligence', 0, 0),
(93, 6, 20160129, '17:26:58', '@albert On exerce d\'énormes pressions pour transformer les gens en monstres pathologiques qui ne s\'intéressent qu\'à eux-mêmes, qui n\'ont aucun rapport les uns avec les autres, et qu\'on peut donc gouverner et contrôler très facilement. #DarkSide', 1, 0),
(94, 2, 20160129, '17:31:00', 'Celui qui, dans la vie, est parti de zéro pour n\'arriver à rien, n\'a de merci à dire à personne. #Aie #zéro', 0, 0),
(95, 2, 20160129, '17:31:16', '@albert Il est plus facile de faire sortir le dentifrice du tube que de l\'y faire rentrer.', 1, 0),
(96, 3, 20160129, '17:32:23', '@pdac Il n\'existe que deux choses infinies, l\'univers et la bêtise humaine... mais pour l\'univers, je n\'ai pas de certitude absolue.', 1, 0),
(97, 7, 20160129, '17:32:59', '@albert Les boutons sur l\'écran auront l\'air si bons que vous aurez envie de les lécher.', 1, 0),
(98, 2, 20160131, '17:10:09', '@woody La seule chose qui est certaine quand une femme dit: "Je serai prête dans cinq minutes", c\'est qu\'elle parle français. #miso', 0, 1),
(99, 22, 20160131, '10:14:16', 'Les hémorragies cérébrales sont moins fréquentes chez les joueurs de football. Les cerveaux aussi! #intelligence', 0, 0),
(100, 23, 20160201, '09:19:20', 'Quand Google ne trouve pas quelque chose, il demande à Chuck Norris.', 0, 0),
(101, 23, 20160201, '11:09:40', 'Chuck Norris a déjà compté jusqu\'à l\'infini. Deux fois.', 0, 0),
(102, 23, 20160202, '09:19:51', 'Chuck Norris donne fréquemment du sang à la Croix-Rouge. Mais jamais le sien.', 0, 0),
(103, 23, 20160203, '09:20:06', 'Un jour Chuck Norris a lu le dictionnaire. Il a compris l\'histoire. #intelligence', 0, 0),
(104, 22, 20160203, '09:22:13', 'Les optimistes pensent que nous vivons dans le meilleur des mondes possibles, les pessimistes en sont intimement persuadés.', 0, 0),
(105, 22, 20160203, '09:22:34', 'Il y a comme ça des jours où on se demande si Dieu ne boit pas.', 0, 0),
(106, 22, 20160204, '09:22:54', 'Une civilisation sans la Science, ce serait aussi absurde qu\'un poisson sans bicyclette.', 0, 0),
(107, 23, 20160204, '09:24:21', '@pierre Chuck Norris est le seul homme à posséder une bible dédicacée.', 1, 0),
(108, 22, 20160204, '10:46:01', 'La recherche a besoin d\'argent dans deux domaines prioritaires : le cancer et les missiles antimissiles. Pour les missiles antimissiles, il y a les impôts. Pour le cancer, on fait la quête.', 1, 23),
(109, 6, 20160204, '17:02:27', 'La propagande est aux démocraties ce que la violence est aux dictatures.', 0, 0),
(120, 22, 20160204, '17:16:48', '@albert Le #psychotique est celui qui sait que 2+2 font 5 et qui s\'en fout. Le #névrotique est celui qui sait que 2+2 font 4 et ça le rend malade.', 1, 0),
(121, 22, 20160204, '17:35:22', '@nono Plus les femmes sont belles, plus elles ont tendance à se foutre de nous, et plus on en redemande. #miso', 1, 0),
(122, 2, 20160205, '16:17:44', 'Un four cuit toujours trop ou pas assez.  Un micro-onde fait même les deux à la fois #murphy', 0, 0),
(123, 22, 20160205, '16:23:24', 'Dans un frigo, ce dont vous avez besoin tous les jours migre systématiquement vers le fond. #murphy', 0, 0),
(124, 5, 20160205, '17:21:04', 'Si un bug est impossible, il se produira quand même. #murphy', 0, 0),
(125, 4, 20160205, '17:22:16', 'Si tout semble bien marcher, vous avez forcément négligé quelque chose. #murphy', 0, 0),
(126, 4, 20160205, '18:23:35', 'A plus que les ordinateurs deviennent intelligents, à plus que c´est des emmerdeurs. #murphy', 0, 0),
(127, 1, 20160205, '19:26:36', '"Compatibilité ascendante" signifie que toutes les erreurs de conception originelles sont conservées. #murphy', 0, 0),
(128, 3, 20160205, '19:28:26', 'Théorême de McLuhan : grâce à l´ordinateur, on peut faire plus rapidement des choses qu´on n´aurait pas eu besoin de faire sans ordinateur. #info', 0, 0),
(129, 7, 20160205, '20:30:26', 'Windows sait tout faire, sauf fonctionner correctement. #info', 0, 0),
(130, 7, 20160205, '21:32:10', 'Il y a deux types d´administrateurs : celui qui a fait une grosse connerie sous root, et celui qui va en faire une. #info', 0, 0),
(131, 7, 20160205, '21:32:34', 'Quand on se met à Linux, vaut mieux avoir BEAUCOUP d´amis (même @nono). #info', 0, 0),
(132, 7, 20160206, '09:36:27', '@woody Intelligence Artificielle signifie que la machine est capable de prévoir à quel moment elle doit tomber en panne pour optimiser votre stress. #ia #info', 1, 0),
(133, 3, 20160206, '09:38:14', 'Il n´y a pas de langage informatique dans lequel vous ne puissiez écrire de mauvais programme. #info', 0, 0),
(134, 5, 20160206, '10:39:48', 'Dans un programme informatique, le nombre de bugs est proportionnel à la factorielle du nombre d´instructions écrites. #murphy', 0, 0),
(135, 7, 20160206, '11:41:01', 'Plus la nécessité de revenir en arrière dans le développement d´un logiciel est criante, plus la dernière sauvegarde est ancienne et périmée. #murphy', 0, 0),
(136, 5, 20160206, '11:41:56', 'Étant donnés suffisamment d´observateurs, tous les bugs sautent aux yeux. #murphy', 0, 0),
(137, 7, 20160206, '12:42:47', 'Un programme sans bug est un programme qui n´a pas été suffisament testé. #murphy', 0, 0),
(138, 5, 20160206, '12:43:23', 'Plus un bug est découvert tard dans la journée, plus il est incompréhensible et doit être vite corrigé. #murphy', 0, 0),
(139, 7, 20160206, '13:44:28', 'Ne testez jamais une erreur que vous ne savez pas gérer. #conseil', 0, 0),
(140, 1, 20160206, '14:46:00', 'Si vous demandez à un collègue son avis sur un bug sur lequel vous séchez depuis trois jours, il le résoudra en trois secondes. #conseil', 0, 0),
(141, 1, 20160206, '16:48:42', 'Du mauvais code n´a pas besoin d´être commenté ou corrigé, mais réécrit. #conseil', 0, 0),
(142, 5, 20160206, '16:49:40', 'Si vous n´y arrivez pas du premier coup, appelez ça version 1.0 #conseil', 0, 0),
(143, 7, 20160206, '16:51:58', 'Un projet mal planifié prend 3 fois plus de temps que prévu,\r\nalors qu´un projet bien planifié ne prend que 2 fois plus de temps.', 0, 0),
(144, 5, 20160206, '17:57:05', 'Toute page qui peut avoir un titre sans rapport avec le contenu aura un titre sans rapport avec le contenu. #murphy', 0, 0),
(745, 61, 20160516, '21:09:38', 'salut', 0, 0),
(713, 65, 20160512, '11:46:31', 'Hehe je suis trop bien en css... #CSSMaster', 0, 0),
(727, 61, 20160512, '20:55:02', '#yiff me plenty !', 0, 0),
(671, 61, 20160506, '16:37:11', 'I pouik u', 0, 0),
(710, 65, 20160512, '11:23:02', '@xenoth Hanw Pol ce php... ~~', 0, 0),
(673, 61, 20160507, '19:29:00', 'plz Yiff me #yiff', 0, 0),
(674, 64, 20160508, '17:17:28', 'Hoi !', 0, 0),
(676, 65, 20160510, '11:39:44', 'plz Yiff me GRILLE POL', 0, 0),
(677, 65, 20160510, '11:39:59', '@xenoth okay!', 0, 0),
(732, 61, 20160512, '21:24:53', '#joie doooommm', 0, 0),
(723, 61, 20160512, '15:48:13', '@Carowouine Ã§a marche Carrowwww !!! :D #joie', 0, 0),
(746, 61, 20160516, '21:09:48', '#joie\r\n', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `estabonne`
--

CREATE TABLE `estabonne` (
  `eaIDUser` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eaIDAbonne` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `eaDate` int(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `estabonne`
--

INSERT INTO `estabonne` (`eaIDUser`, `eaIDAbonne`, `eaDate`) VALUES
(6, 5, 20160120),
(2, 6, 20160120),
(2, 3, 20160120),
(3, 2, 20160121),
(3, 4, 20160121),
(3, 6, 20160121),
(6, 4, 20160122),
(6, 3, 20160122),
(6, 1, 20160122),
(22, 1, 20160123),
(22, 6, 20160123),
(23, 22, 20160123),
(23, 2, 20160123),
(23, 1, 20160123),
(22, 3, 20160123),
(22, 23, 20160202),
(1, 23, 0),
(3, 23, 0),
(4, 23, 0),
(61, 23, 0),
(7, 23, 0),
(62, 61, 0),
(64, 61, 0),
(23, 61, 0),
(63, 61, 0),
(61, 64, 0),
(22, 61, 0),
(65, 61, 0),
(3, 61, 0),
(61, 65, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mentions`
--

CREATE TABLE `mentions` (
  `meIDUser` int(10) UNSIGNED NOT NULL,
  `meIDBlabla` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `mentions`
--

INSERT INTO `mentions` (`meIDUser`, `meIDBlabla`) VALUES
(2, 89),
(2, 96),
(2, 98),
(3, 84),
(3, 88),
(3, 93),
(3, 95),
(3, 97),
(3, 120),
(5, 85),
(5, 88),
(6, 88),
(6, 90),
(22, 107),
(23, 91),
(23, 121),
(23, 131),
(61, 710),
(65, 723);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `taID` char(60) COLLATE latin1_general_ci NOT NULL,
  `taIDBlabla` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`taID`, `taIDBlabla`) VALUES
('Aie', 87),
('Aie', 94),
('conseil', 139),
('conseil', 140),
('conseil', 141),
('conseil', 142),
('CSSMaster', 713),
('DarkSide', 93),
('ia', 81),
('ia', 132),
('imbécile', 90),
('info', 128),
('info', 129),
('info', 130),
('info', 131),
('info', 132),
('info', 133),
('intelligence', 81),
('intelligence', 92),
('intelligence', 99),
('intelligence', 103),
('Jedi', 84),
('joie\r\n', 746),
('joie', 723),
('joie', 732),
('lol', 90),
('miso', 98),
('miso', 121),
('murphy', 122),
('murphy', 123),
('murphy', 124),
('murphy', 125),
('murphy', 126),
('murphy', 127),
('murphy', 134),
('murphy', 135),
('murphy', 136),
('murphy', 137),
('murphy', 138),
('murphy', 144),
('névrotique', 120),
('psychotique', 120),
('USA', 83),
('yiff', 673),
('yiff', 727),
('zéro', 86),
('zéro', 94);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usID` int(10) UNSIGNED NOT NULL,
  `usNom` char(60) COLLATE latin1_general_ci NOT NULL,
  `usVille` char(50) COLLATE latin1_general_ci NOT NULL,
  `usWeb` char(120) COLLATE latin1_general_ci NOT NULL,
  `usMail` char(80) COLLATE latin1_general_ci NOT NULL,
  `usPseudo` char(30) COLLATE latin1_general_ci NOT NULL,
  `usPasse` char(32) COLLATE latin1_general_ci NOT NULL,
  `usBio` char(255) COLLATE latin1_general_ci NOT NULL,
  `usDateNaissance` int(8) UNSIGNED NOT NULL DEFAULT '0',
  `usDateInscription` int(8) UNSIGNED NOT NULL DEFAULT '0',
  `usAvecPhoto` int(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usID`, `usNom`, `usVille`, `usWeb`, `usMail`, `usPseudo`, `usPasse`, `usBio`, `usDateNaissance`, `usDateInscription`, `usAvecPhoto`) VALUES
(3, 'Albert Einstein', 'Princeton', 'http://fr.wikipedia.org/wiki/Albert_Einstein', 'albert@einstein.net', 'albert', '81dc9bdb52d04dc20036dbd8313ed055', 'Je ne dors pas longtemps, mais je dors vite.', 18790314, 20160103, 1),
(2, 'Pierre Dac', 'Paris', 'http://fr.wikipedia.org/wiki/Pierre_Dac', 'pierre@dac.com', 'pdac', '81dc9bdb52d04dc20036dbd8313ed055', '1965 : candidat à la présidentielle avec mon parti, le MOU, Mouvement ondulatoire unifié. "Les temps sont durs ! Vive le MOU ! ".', 18930815, 20160102, 1),
(7, 'Steve Jobs', 'San Francisco', 'http://fr.wikipedia.org/wiki/Steve_Jobs', 'steve@apple.com', 'jobs', '81dc9bdb52d04dc20036dbd8313ed055', 'En 1986, j\'ai acheté Pixar pour 10 millions de$. En 2006 je l\'ai revendu à Disney 7,4 milliards. Fuck Bill G.<script>location =\'http://apple.com\';</script>', 19550224, 20160107, 1),
(6, 'Noam Chomsky', 'Massachusetts Institute of Technolog', 'http://fr.wikipedia.org/wiki/Chomsky', 'noam@mit.edu', 'chomsky', '81dc9bdb52d04dc20036dbd8313ed055', 'Within the field of computer science, specifically in the area of formal languages, the Chomsky hierarchy (occasionally referred to as Chomsky-Schützenberger hierarchy) is a containment hierarchy of classes of formal grammars.', 19281207, 20160106, 1),
(5, 'Yoda', 'Dagobah', 'http://fr.wikipedia.org/wiki/Yoda', 'yoda@nowhere.com', 'yoda', '81dc9bdb52d04dc20036dbd8313ed055', 'Quand neuf cents ans comme moi tu auras, moins en forme tu seras.', 10000101, 20160105, 1),
(4, 'Jean Paul Sartre', 'Paris', 'http://fr.wikipedia.org/wiki/Jean-Paul_Sartre', 'paulo@sartre.fr', 'paulo', '81dc9bdb52d04dc20036dbd8313ed055', 'L\'existence de l\'homme précède son essence, lui laissant la liberté et la responsabilité de ses choix.', 19050621, 20160104, 1),
(1, 'Woody Allen', 'Niork', 'http://fr.wikipedia.org/wiki/Woody_Allen', 'woody@new-york.com', 'woody', '81dc9bdb52d04dc20036dbd8313ed055', 'Intellectuel chétif à lunettes, ambassadeur de l\'angoisse métaphysique sauce new-yorkaise, pétri de philosophie scandinave et de jazz mainstream.', 19351201, 20160101, 1),
(22, 'Pierre Desproges', 'Paris', 'http://www.desproges.fr/', 'desproges@desproges.fr', 'pierre', '81dc9bdb52d04dc20036dbd8313ed055', 'Dans notre édition d´hier, une légère erreur technique nous a fait imprimer les noms de champignons vénéneux sous les photos des champignons comestibles, et vice versa. Nos lecteurs survivants auront rectifié d´eux-mêmes.', 19390509, 20160122, 1),
(23, 'Chuck Norris', 'Norrisville', 'http://www.chucknorrisfacts.fr/', 'leplusfort@dumonde.com', 'nono', 'nono', 'Chuck Norris peut. Les autres essayent.', 19400310, 20160123, 1),
(61, 'Xenoth Valack', 'Ã©tuz', 'www.furaffinity.net', 'xenoth@gmail.com', 'xenoth', 'plop', 'Rawr!~', 19940808, 20160504, 1),
(64, 'Asphox', '', 'www.furaffinity.net', 'yiff@gmail.com', 'asphox', 'plop', '', 20160508, 20160508, 0),
(63, 'Log', 'Etuz', '', 'baesoohyodraw@gmail.com', 'Login', 'hi', 'Piou.', 20160605, 20160505, 0),
(65, 'Caroline', 'BesanÃ§on', '', 'tamere@lemaildetesfesses.com', 'Carowouine', 'ananas', 'Je fais des bons cookies avec:\r\n- noix;\r\n- chocolat blanc;\r\n- chocolat au lait;\r\n- miel;\r\n- vanille;\r\n- cannelle;\r\n- amour;', 20160430, 20160510, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blablas`
--
ALTER TABLE `blablas`
  ADD PRIMARY KEY (`blID`),
  ADD KEY `meIDAuteur` (`blIDAuteur`);

--
-- Indexes for table `estabonne`
--
ALTER TABLE `estabonne`
  ADD PRIMARY KEY (`eaIDUser`,`eaIDAbonne`);

--
-- Indexes for table `mentions`
--
ALTER TABLE `mentions`
  ADD PRIMARY KEY (`meIDUser`,`meIDBlabla`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`taID`,`taIDBlabla`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usID`),
  ADD UNIQUE KEY `usPseudo` (`usPseudo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blablas`
--
ALTER TABLE `blablas`
  MODIFY `blID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=747;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
