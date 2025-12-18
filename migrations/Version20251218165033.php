<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251218165033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'No-op: duplicate of Version20251218164752 (kept to preserve migration ordering).';
    }

    public function up(Schema $schema): void
    {
        // NOTE:
        // This migration was accidentally generated with the same SQL as Version20251218164752.
        // Keeping it as a no-op avoids failing on duplicate column additions while preserving
        // the migration chain for environments where this file may already exist.
    }

    public function down(Schema $schema): void
    {
        // no-op
    }
}
