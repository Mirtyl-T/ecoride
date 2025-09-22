CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `pseudo` varchar(50) UNIQUE NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum(utilisateur,chauffeur,passager,employe,admin) DEFAULT 'utilisateur',
  `credits` int DEFAULT 20,
  `actif` boolean DEFAULT true,
  `date_creation` timestamp DEFAULT (current_timestamp)
);

CREATE TABLE `vehicles` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `marque` varchar(50),
  `modele` varchar(50),
  `energie` enum(essence,diesel,hybride,electrique) NOT NULL,
  `couleur` varchar(30),
  `immatriculation` varchar(20) UNIQUE,
  `date_immat` date,
  `nb_places` int
);

CREATE TABLE `trips` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `driver_id` int,
  `vehicle_id` int,
  `ville_depart` varchar(100),
  `ville_arrivee` varchar(100),
  `date_depart` datetime,
  `date_arrivee` datetime,
  `prix` decimal(5,2),
  `places_dispo` int,
  `status` enum(prévu,démarré,terminé,annulé) DEFAULT 'prévu'
);

CREATE TABLE `participations` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `trip_id` int,
  `user_id` int,
  `date_participation` timestamp DEFAULT (current_timestamp),
  `statut` enum(en attente,confirmé,annulé) DEFAULT 'en attente'
);

CREATE TABLE `preferences` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `libelle` varchar(100),
  `valeur` varchar(100)
);

CREATE TABLE `reviews` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `trip_id` int,
  `reviewer_id` int,
  `driver_id` int,
  `note` int,
  `commentaire` text,
  `valide` boolean DEFAULT false
);

ALTER TABLE `vehicles` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `trips` ADD FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`);

ALTER TABLE `trips` ADD FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

ALTER TABLE `participations` ADD FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`);

ALTER TABLE `participations` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `preferences` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`);
