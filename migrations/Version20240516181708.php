<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516181708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ollama_request (id INT AUTO_INCREMENT NOT NULL, input LONGTEXT DEFAULT NULL, output LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, picked_up_by_worker_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, done_at DATETIME DEFAULT NULL, prompt_prefix LONGTEXT DEFAULT NULL, prompt_suffix LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prompt_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, prompt_prefix LONGTEXT DEFAULT NULL, prompt_suffix LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ollama_request');
        $this->addSql('DROP TABLE prompt_type');
    }
}
