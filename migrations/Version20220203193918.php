<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203193918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gerente');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gerente (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, login VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, senha VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cargo CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE classificacao CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE funcionario CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE login login VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE senha senha VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE projeto CHANGE nome nome VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cliente cliente VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE task CHANGE nome nome VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
