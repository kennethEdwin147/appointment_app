CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NULL, -- Autoriser NULL pour les comptes invités initialement
    role ENUM('user', 'creator', 'admin', 'guest') DEFAULT 'user',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL
);

CREATE TABLE creators (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    bio TEXT NULL,
    platform_name VARCHAR(255) NULL,
    platform_url VARCHAR(255) NULL,
    type VARCHAR(255) NULL,
    platform_commission_rate DECIMAL(5, 2) DEFAULT 0.05 COMMENT 'Taux de commission de la plateforme pour ce créateur (par défaut 5%)',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE creator_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creator_id BIGINT UNSIGNED NOT NULL UNIQUE,
    slug VARCHAR(255) UNIQUE NULL,
    profile_color VARCHAR(50) NULL,
    banner_image VARCHAR(255) NULL,
    custom_css TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (creator_id) REFERENCES creators(id) ON DELETE CASCADE
);

CREATE TABLE event_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE, -- Ex: Session de jeu, Coaching, Q&A, Atelier
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE availabilities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creator_id BIGINT UNSIGNED NOT NULL,
    event_type_id BIGINT UNSIGNED NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    start_date DATE NULL,       -- Date de début de cette disponibilité (si non récurrente indéfiniment)
    end_date DATE NULL,         -- Date de fin de cette disponibilité
    is_recurring BOOLEAN DEFAULT TRUE, -- Indique si la disponibilité est récurrente
    price DECIMAL(8, 2) NOT NULL,
    max_participants INT UNSIGNED NULL,
    meeting_link VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (creator_id) REFERENCES creators(id) ON DELETE CASCADE,
    FOREIGN KEY (event_type_id) REFERENCES event_types(id) ON DELETE CASCADE,
    UNIQUE KEY creator_day_time_event (creator_id, day_of_week, start_time, end_time, start_date, end_date, event_type_id)
);

CREATE TABLE reservations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    creator_id BIGINT UNSIGNED NOT NULL,
    event_type_id BIGINT UNSIGNED NOT NULL,
    reserved_datetime DATETIME NOT NULL, -- Date et heure spécifiques de la réservation
    reservation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_status VARCHAR(50) DEFAULT 'pending',
    payment_id VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (creator_id) REFERENCES creators(id) ON DELETE CASCADE,
    FOREIGN KEY (event_type_id) REFERENCES event_types(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    type VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    enabled BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reservation_id BIGINT UNSIGNED NOT NULL UNIQUE,
    payment_method_id BIGINT UNSIGNED NULL,
    transaction_id VARCHAR(255) NULL,
    amount DECIMAL(8, 2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'CAD',
    status VARCHAR(50) NOT NULL,
    platform_commission_rate DECIMAL(5, 2) NULL COMMENT 'Taux de commission de la plateforme appliqué à cette transaction',
    platform_commission_amount DECIMAL(8, 2) NULL COMMENT 'Montant de la commission de la plateforme sur cette transaction',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE SET NULL
);

CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    creator_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (creator_id) REFERENCES creators(id) ON DELETE CASCADE,
    UNIQUE INDEX user_creator (user_id, creator_id)
);

CREATE TABLE wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    creator_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_creator (user_id, creator_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (creator_id) REFERENCES creators(id) ON DELETE CASCADE
);