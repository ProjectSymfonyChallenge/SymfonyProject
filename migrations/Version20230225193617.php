<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225193617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hike ADD longitude NUMERIC(10, 7)');
        $this->addSql('ALTER TABLE hike ADD latitude NUMERIC(10, 7)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hike DROP longitude');
        $this->addSql('ALTER TABLE hike DROP latitude');
    }
}
