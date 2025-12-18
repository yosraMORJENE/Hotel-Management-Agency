<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251215193622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404553243BB18');
        $this->addSql('DROP INDEX IDX_C74404553243BB18 ON client');
        $this->addSql('ALTER TABLE client ADD date_arrivee DATETIME NOT NULL, ADD date_depart DATETIME NOT NULL, DROP hotel_id');
        $this->addSql('ALTER TABLE hotel ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD hotel_id INT NOT NULL, DROP date_arrivee, DROP date_depart');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('CREATE INDEX IDX_C74404553243BB18 ON client (hotel_id)');
        $this->addSql('ALTER TABLE hotel DROP image');
    }
}
