<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171229020409 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact_phone_call ADD uuid VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E83324BBD17F50A6 ON contact_phone_call (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47B68E6F96901F54 ON contact_phone_number (number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_E83324BBD17F50A6 ON contact_phone_call');
        $this->addSql('ALTER TABLE contact_phone_call DROP uuid');
        $this->addSql('DROP INDEX UNIQ_47B68E6F96901F54 ON contact_phone_number');
    }
}
