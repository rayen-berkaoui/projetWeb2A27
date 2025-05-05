-- Création de la table des réservations
CREATE TABLE IF NOT EXISTS `reservations` (
    `id_reservation` int(11) NOT NULL AUTO_INCREMENT,
    `nom_participant` varchar(100) NOT NULL,
    `email` varchar(100),
    `date_reservation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id_evenement` int(11) NOT NULL,
    PRIMARY KEY (`id_reservation`),
    FOREIGN KEY (`id_evenement`) REFERENCES `evenements`(`id_evenement`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Exemples d'insertions
INSERT INTO `reservations` (`nom_participant`, `email`, `id_evenement`) VALUES
('Jean Dupont', 'jean.dupont@email.com', 1),
('Marie Martin', 'marie.martin@email.com', 1),
('Pierre Durand', 'pierre.durand@email.com', 2);

-- Requêtes de jointure utiles

-- 1. Liste des réservations avec les détails des événements
SELECT 
    r.id_reservation,
    r.nom_participant,
    r.email,
    r.date_reservation,
    e.titre as titre_evenement,
    e.date_evenement,
    e.lieu
FROM 
    reservations r
INNER JOIN 
    evenements e ON r.id_evenement = e.id_evenement
ORDER BY 
    r.date_reservation DESC;

-- 2. Nombre de réservations par événement
SELECT 
    e.titre,
    e.date_evenement,
    COUNT(r.id_reservation) as nombre_reservations
FROM 
    evenements e
LEFT JOIN 
    reservations r ON e.id_evenement = r.id_evenement
GROUP BY 
    e.id_evenement
ORDER BY 
    e.date_evenement;

-- 3. Liste des événements avec leurs réservations (même ceux sans réservation)
SELECT 
    e.titre,
    e.date_evenement,
    e.lieu,
    COALESCE(COUNT(r.id_reservation), 0) as nombre_reservations,
    GROUP_CONCAT(r.nom_participant SEPARATOR ', ') as participants
FROM 
    evenements e
LEFT JOIN 
    reservations r ON e.id_evenement = r.id_evenement
GROUP BY 
    e.id_evenement
ORDER BY 
    e.date_evenement;

-- 4. Dernières réservations des 7 derniers jours
SELECT 
    r.nom_participant,
    r.email,
    r.date_reservation,
    e.titre as titre_evenement,
    e.date_evenement
FROM 
    reservations r
INNER JOIN 
    evenements e ON r.id_evenement = e.id_evenement
WHERE 
    r.date_reservation >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY 
    r.date_reservation DESC;

-- 5. Événements complets (si on limite à 3 réservations par événement)
SELECT 
    e.titre,
    e.date_evenement,
    COUNT(r.id_reservation) as nombre_reservations
FROM 
    evenements e
LEFT JOIN 
    reservations r ON e.id_evenement = r.id_evenement
GROUP BY 
    e.id_evenement
HAVING 
    nombre_reservations >= 3
ORDER BY 
    e.date_evenement; 