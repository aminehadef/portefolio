<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190501231422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_project DROP FOREIGN KEY FK_BAA38732B3E79F4B');
        $this->addSql('DROP INDEX IDX_BAA38732B3E79F4B ON image_project');
        $this->addSql('ALTER TABLE image_project DROP id_project_id, DROP created_at, CHANGE image path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project ADD image_project_id INT DEFAULT NULL, DROP created_at');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7AB308AA FOREIGN KEY (image_project_id) REFERENCES image_project (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE7AB308AA ON project (image_project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_project ADD id_project_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, CHANGE path image VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE image_project ADD CONSTRAINT FK_BAA38732B3E79F4B FOREIGN KEY (id_project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_BAA38732B3E79F4B ON image_project (id_project_id)');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7AB308AA');
        $this->addSql('DROP INDEX IDX_2FB3D0EE7AB308AA ON project');
        $this->addSql('ALTER TABLE project ADD created_at DATETIME NOT NULL, DROP image_project_id');
    }
}
