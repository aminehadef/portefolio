<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503182306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_project DROP FOREIGN KEY FK_BAA387323DA5256D');
        $this->addSql('DROP INDEX IDX_BAA387323DA5256D ON image_project');
        $this->addSql('ALTER TABLE image_project CHANGE image_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_project ADD CONSTRAINT FK_BAA38732166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_BAA38732166D1F9C ON image_project (project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_project DROP FOREIGN KEY FK_BAA38732166D1F9C');
        $this->addSql('DROP INDEX IDX_BAA38732166D1F9C ON image_project');
        $this->addSql('ALTER TABLE image_project CHANGE project_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_project ADD CONSTRAINT FK_BAA387323DA5256D FOREIGN KEY (image_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_BAA387323DA5256D ON image_project (image_id)');
    }
}
