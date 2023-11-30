<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121131739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE messages_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, id_sender INT NOT NULL, id_receiver INT NOT NULL, text TEXT NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE user_likes DROP CONSTRAINT fk_ab08b525a76ed395');
        $this->addSql('ALTER TABLE user_likes DROP CONSTRAINT fk_ab08b525dd7690df');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT fk_db021e96f624b39d');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT fk_db021e96cd53edb6');
        $this->addSql('DROP TABLE user_likes');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_likes (user_id INT NOT NULL, liked_user_id INT NOT NULL, PRIMARY KEY(user_id, liked_user_id))');
        $this->addSql('CREATE INDEX idx_ab08b525dd7690df ON user_likes (liked_user_id)');
        $this->addSql('CREATE INDEX idx_ab08b525a76ed395 ON user_likes (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, gender VARCHAR(50) NOT NULL, location VARCHAR(255) NOT NULL, interests JSON NOT NULL, favorite_book VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
        $this->addSql('CREATE TABLE messages (id INT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, created_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_db021e96cd53edb6 ON messages (receiver_id)');
        $this->addSql('CREATE INDEX idx_db021e96f624b39d ON messages (sender_id)');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT fk_ab08b525a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT fk_ab08b525dd7690df FOREIGN KEY (liked_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT fk_db021e96f624b39d FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT fk_db021e96cd53edb6 FOREIGN KEY (receiver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE message');
    }
}
