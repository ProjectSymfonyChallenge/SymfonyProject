<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222230916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT FK_16DB4F8971D4DE21');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT FK_16DB4F8961190A32');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT FK_16DB4F89F7A2C2FC');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8971D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8961190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT fk_16db4f8971d4de21');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT fk_16db4f8961190a32');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT fk_16db4f89f7a2c2fc');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT fk_16db4f8971d4de21 FOREIGN KEY (hike_id) REFERENCES hike (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT fk_16db4f8961190a32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT fk_16db4f89f7a2c2fc FOREIGN KEY (badge_id) REFERENCES badge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
