<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203154541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE club DROP CONSTRAINT FK_B8EE3872783E3463');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872783E3463 FOREIGN KEY (manager_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT FK_1323A575A76ED395');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT FK_16DB4F89A76ED395');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT FK_2301D7E461190A32');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT FK_2301D7E461190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE71D4DE21');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C71D4DE21');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C71D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT FK_1323A57571D4DE21');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57571D4DE21 FOREIGN KEY (hike_id) REFERENCES hike (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT fk_16db4f89a76ed395');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT fk_16db4f89a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE club DROP CONSTRAINT fk_b8ee3872783e3463');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT fk_b8ee3872783e3463 FOREIGN KEY (manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526ca76ed395');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526ca76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT fk_e00ceddea76ed395');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT fk_e00ceddea76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT fk_1323a575a76ed395');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT fk_1323a575a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hike DROP CONSTRAINT fk_2301d7e461190a32');
        $this->addSql('ALTER TABLE hike ADD CONSTRAINT fk_2301d7e461190a32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT fk_e00cedde71d4de21');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT fk_e00cedde71d4de21 FOREIGN KEY (hike_id) REFERENCES hike (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526c71d4de21');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526c71d4de21 FOREIGN KEY (hike_id) REFERENCES hike (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE evaluation DROP CONSTRAINT fk_1323a57571d4de21');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT fk_1323a57571d4de21 FOREIGN KEY (hike_id) REFERENCES hike (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
