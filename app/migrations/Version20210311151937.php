<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210311151937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initiates database';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE notification (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', contact_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', status TINYTEXT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_BF5476CAE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE contact (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name TINYTEXT NOT NULL, email TINYTEXT NOT NULL, INDEX IDX_4C62E638A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE SET NULL;');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;');
    }

    public function down(Schema $schema) : void
    {
    }

    /**
     * @desc https://github.com/doctrine/migrations/issues/1104
     */
    public function isTransactional(): bool
    {
        return false;
    }
}