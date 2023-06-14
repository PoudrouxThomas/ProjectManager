<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614191535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating project/task relation.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks ADD project_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865976C1197C9 FOREIGN KEY (project_id_id) REFERENCES projects (id)');
        $this->addSql('CREATE INDEX IDX_505865976C1197C9 ON tasks (project_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865976C1197C9');
        $this->addSql('DROP INDEX IDX_505865976C1197C9 ON tasks');
        $this->addSql('ALTER TABLE tasks DROP project_id_id');
    }
}
