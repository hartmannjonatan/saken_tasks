<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203174708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7510A3CF3E3E11F0 ON funcionario (cpf)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7510A3CFAA08CB10 ON funcionario (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE classificacao CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_7510A3CF3E3E11F0 ON funcionario');
        $this->addSql('DROP INDEX UNIQ_7510A3CFAA08CB10 ON funcionario');
        $this->addSql('ALTER TABLE funcionario CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE login login VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE senha senha VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE gerente CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE login login VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE senha senha VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE projeto CHANGE nome nome VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cliente cliente VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE task CHANGE nome nome VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
