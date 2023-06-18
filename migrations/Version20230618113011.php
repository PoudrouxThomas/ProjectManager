<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618113011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding a project manager to a project.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects ADD project_manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A460984F51 FOREIGN KEY (project_manager_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A460984F51 ON projects (project_manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A460984F51');
        $this->addSql('DROP INDEX IDX_5C93B3A460984F51 ON projects');
        $this->addSql('ALTER TABLE projects DROP project_manager_id');
    }
}
