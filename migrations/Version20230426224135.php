<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426224135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE i23_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_62571961A76ED395 FOREIGN KEY (user_id) REFERENCES i23_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62571961F347EFB FOREIGN KEY (produit_id) REFERENCES i23_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_62571961A76ED395 ON i23_paniers (user_id)');
        $this->addSql('CREATE INDEX IDX_62571961F347EFB ON i23_paniers (produit_id)');
        $this->addSql('CREATE TABLE i23_produits (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(20) NOT NULL, prix DOUBLE PRECISION NOT NULL, stock INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE i23_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB DEFAULT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(30) NOT NULL, ddn DATE DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_67D32048AA08CB10 ON i23_users (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE i23_paniers');
        $this->addSql('DROP TABLE i23_produits');
        $this->addSql('DROP TABLE i23_users');
    }
}
