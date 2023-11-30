<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129134222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_relation (id INT NOT NULL, user_id INT NOT NULL, target_user_id INT NOT NULL, type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8204A349A76ED395 ON user_relation (user_id)');
        $this->addSql('CREATE INDEX IDX_8204A3496C066AFE ON user_relation (target_user_id)');
        $this->addSql('ALTER TABLE user_relation ADD CONSTRAINT FK_8204A349A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_relation ADD CONSTRAINT FK_8204A3496C066AFE FOREIGN KEY (target_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP likes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_relation_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_relation DROP CONSTRAINT FK_8204A349A76ED395');
        $this->addSql('ALTER TABLE user_relation DROP CONSTRAINT FK_8204A3496C066AFE');
        $this->addSql('DROP TABLE user_relation');
        $this->addSql('ALTER TABLE "user" ADD likes TEXT NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".likes IS \'(DC2Type:array)\'');
    }
}
