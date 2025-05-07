-- Create feedbacks table if it doesn't exist
CREATE TABLE IF NOT EXISTS feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    commentaire TEXT NOT NULL,
    note INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    is_public TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_note CHECK (note >= 1 AND note <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure de la table avis
CREATE TABLE IF NOT EXISTS avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    note INT NOT NULL,
    is_visible BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_note CHECK (note >= 1 AND note <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Structure de la table commentaires
CREATE TABLE IF NOT EXISTS commentaires (
    commentaire_id INT AUTO_INCREMENT PRIMARY KEY,
    avis_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    contenu TEXT NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    is_visible BOOLEAN DEFAULT TRUE,
    signaled BOOLEAN DEFAULT FALSE,
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (avis_id) REFERENCES avis(avis_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vérifier et mettre à jour la structure existante si nécessaire
ALTER TABLE avis
MODIFY COLUMN is_visible BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS email VARCHAR(255) NOT NULL AFTER prenom;

ALTER TABLE commentaires
ADD COLUMN IF NOT EXISTS is_admin BOOLEAN DEFAULT FALSE AFTER contenu,
ADD COLUMN IF NOT EXISTS signaled BOOLEAN DEFAULT FALSE AFTER is_visible,
ADD COLUMN IF NOT EXISTS likes INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS dislikes INT DEFAULT 0;

