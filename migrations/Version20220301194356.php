<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301194356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD recent_painel_id INT DEFAULT NULL, ADD recent_project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64998330E12 FOREIGN KEY (recent_painel_id) REFERENCES painel (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ACBA032B FOREIGN KEY (recent_project_id) REFERENCES projeto (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64998330E12 ON user (recent_painel_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ACBA032B ON user (recent_project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE classificacao CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE funcionario CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cpf cpf VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE gerente CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE note CHANGE id id VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(50) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE conteudo conteudo VARCHAR(1000) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE color color VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE painel CHANGE title title VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE projeto CHANGE nome nome VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cliente cliente VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descricao descricao VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url_img_cover url_img_cover VARCHAR(500) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE task CHANGE nome nome VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE conclued_at conclued_at DATETIME DEFAULT NULL, CHANGE descricao descricao VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo CHANGE nome nome VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64998330E12');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ACBA032B');
        $this->addSql('DROP INDEX IDX_8D93D64998330E12 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649ACBA032B ON user');
        $this->addSql('ALTER TABLE user DROP recent_painel_id, DROP recent_project_id, CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
