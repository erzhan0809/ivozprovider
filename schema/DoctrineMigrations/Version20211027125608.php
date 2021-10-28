<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419125608 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ast_voicemail_messages (id INT AUTO_INCREMENT NOT NULL, dir VARCHAR(255) NOT NULL, msgnum INT DEFAULT NULL, context VARCHAR(80) NOT NULL, macrocontext VARCHAR(80) NOT NULL, callerid VARCHAR(80) NOT NULL, origtime INT DEFAULT NULL, duration INT DEFAULT NULL, recording INT DEFAULT NULL, flag VARCHAR(30) DEFAULT NULL, category VARCHAR(30) DEFAULT NULL, mailboxuser VARCHAR(30) DEFAULT NULL, mailboxcontext VARCHAR(30) DEFAULT NULL, msg_id VARCHAR(40) DEFAULT NULL, recordingFileFileSize INT UNSIGNED DEFAULT NULL COMMENT \'[FSO:keepExtension]\', recordingFileMimeType VARCHAR(80) DEFAULT NULL, recordingFileBaseName VARCHAR(255) DEFAULT NULL, userId INT UNSIGNED DEFAULT NULL, residentialDeviceId INT UNSIGNED DEFAULT NULL, INDEX IDX_F62CF54264B64DCC (userId), INDEX IDX_F62CF5428B329DCD (residentialDeviceId), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ast_voicemail_messages ADD CONSTRAINT FK_F62CF54264B64DCC FOREIGN KEY (userId) REFERENCES Users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ast_voicemail_messages ADD CONSTRAINT FK_F62CF5428B329DCD FOREIGN KEY (residentialDeviceId) REFERENCES ResidentialDevices (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ast_voicemail_messages');
    }
}
