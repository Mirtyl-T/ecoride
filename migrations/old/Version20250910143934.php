<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250910143934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `participations` (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, user_id INT NOT NULL, date_participation DATETIME DEFAULT NULL, statut VARCHAR(20) NOT NULL, INDEX IDX_FDC6C6E8A5BC2E0E (trip_id), INDEX IDX_FDC6C6E8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `preferences` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, valeur VARCHAR(255) DEFAULT NULL, INDEX IDX_E931A6F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `reviews` (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, reviewer_id INT NOT NULL, driver_id INT NOT NULL, note SMALLINT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, valide TINYINT(1) DEFAULT 0 NOT NULL, is_incident TINYINT(1) DEFAULT 0 NOT NULL, incident_description LONGTEXT DEFAULT NULL, etat VARCHAR(20) NOT NULL, INDEX IDX_6970EB0FA5BC2E0E (trip_id), INDEX IDX_6970EB0F70574616 (reviewer_id), INDEX IDX_6970EB0FC3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trips` (id INT AUTO_INCREMENT NOT NULL, driver_id INT NOT NULL, vehicule_id INT NOT NULL, ville_depart VARCHAR(255) DEFAULT NULL, ville_arrivee VARCHAR(255) DEFAULT NULL, date_depart DATETIME DEFAULT NULL, date_arrivee DATETIME DEFAULT NULL, prix NUMERIC(5, 2) DEFAULT NULL, places_dispo INT DEFAULT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_AA7370DAC3423909 (driver_id), INDEX IDX_AA7370DA4A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(50) NOT NULL, credits INT DEFAULT 20 NOT NULL, actif TINYINT(1) DEFAULT 1 NOT NULL, date_creation DATETIME NOT NULL, is_verified TINYINT(1) NOT NULL, is_suspended TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `vehicule` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, fumeur TINYINT(1) DEFAULT 0 NOT NULL, animaux TINYINT(1) DEFAULT 0 NOT NULL, preferences LONGTEXT DEFAULT NULL, marque VARCHAR(50) DEFAULT NULL, modele VARCHAR(50) DEFAULT NULL, fuel_type VARCHAR(20) NOT NULL, couleur VARCHAR(50) DEFAULT NULL, immatriculation VARCHAR(255) DEFAULT NULL, date_immat DATE DEFAULT NULL, nb_places INT DEFAULT NULL, UNIQUE INDEX UNIQ_292FFF1DBE73422E (immatriculation), INDEX IDX_292FFF1DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `participations` ADD CONSTRAINT FK_FDC6C6E8A5BC2E0E FOREIGN KEY (trip_id) REFERENCES `trips` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `participations` ADD CONSTRAINT FK_FDC6C6E8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `preferences` ADD CONSTRAINT FK_E931A6F5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `reviews` ADD CONSTRAINT FK_6970EB0FA5BC2E0E FOREIGN KEY (trip_id) REFERENCES `trips` (id)');
        $this->addSql('ALTER TABLE `reviews` ADD CONSTRAINT FK_6970EB0F70574616 FOREIGN KEY (reviewer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `reviews` ADD CONSTRAINT FK_6970EB0FC3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `trips` ADD CONSTRAINT FK_AA7370DAC3423909 FOREIGN KEY (driver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `trips` ADD CONSTRAINT FK_AA7370DA4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES `vehicule` (id)');
        $this->addSql('ALTER TABLE `vehicule` ADD CONSTRAINT FK_292FFF1DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `participations` DROP FOREIGN KEY FK_FDC6C6E8A5BC2E0E');
        $this->addSql('ALTER TABLE `participations` DROP FOREIGN KEY FK_FDC6C6E8A76ED395');
        $this->addSql('ALTER TABLE `preferences` DROP FOREIGN KEY FK_E931A6F5A76ED395');
        $this->addSql('ALTER TABLE `reviews` DROP FOREIGN KEY FK_6970EB0FA5BC2E0E');
        $this->addSql('ALTER TABLE `reviews` DROP FOREIGN KEY FK_6970EB0F70574616');
        $this->addSql('ALTER TABLE `reviews` DROP FOREIGN KEY FK_6970EB0FC3423909');
        $this->addSql('ALTER TABLE `trips` DROP FOREIGN KEY FK_AA7370DAC3423909');
        $this->addSql('ALTER TABLE `trips` DROP FOREIGN KEY FK_AA7370DA4A4A3511');
        $this->addSql('ALTER TABLE `vehicule` DROP FOREIGN KEY FK_292FFF1DA76ED395');
        $this->addSql('DROP TABLE `participations`');
        $this->addSql('DROP TABLE `preferences`');
        $this->addSql('DROP TABLE `reviews`');
        $this->addSql('DROP TABLE `trips`');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE `vehicule`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
