<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618085719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating task_comments table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_comments (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, task_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_1F5E7C66F675F31B (author_id), INDEX IDX_1F5E7C668DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_comments ADD CONSTRAINT FK_1F5E7C66F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE task_comments ADD CONSTRAINT FK_1F5E7C668DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_comments DROP FOREIGN KEY FK_1F5E7C66F675F31B');
        $this->addSql('ALTER TABLE task_comments DROP FOREIGN KEY FK_1F5E7C668DB60186');
        $this->addSql('DROP TABLE task_comments');
    }
}
