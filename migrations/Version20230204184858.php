<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230204184858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE locality_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE locality (id INT NOT NULL, region VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE hike ADD locality_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E488823A92 FOREIGN KEY (locality_id) REFERENCES locality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2301D7E488823A92 ON hike (locality_id)');
        $this->addSql('ALTER TABLE "user" ADD locality_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64988823A92 FOREIGN KEY (locality_id) REFERENCES locality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64988823A92 ON "user" (locality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT FK_2301D7E488823A92');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64988823A92');
        $this->addSql('DROP SEQUENCE locality_id_seq CASCADE');
        $this->addSql('DROP TABLE locality');
        $this->addSql('DROP INDEX IDX_8D93D64988823A92');
        $this->addSql('ALTER TABLE "user" DROP locality_id');
        $this->addSql('DROP INDEX IDX_2301D7E488823A92');
        $this->addSql('ALTER TABLE hike DROP locality_id');
    }
}
