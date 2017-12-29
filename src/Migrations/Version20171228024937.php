<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171228024937 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phone_log (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, call_date DATETIME DEFAULT NULL, from_number VARCHAR(255) DEFAULT NULL, to_number VARCHAR(255) DEFAULT NULL, direction VARCHAR(255) DEFAULT NULL, from_number_hidden TINYINT(1) DEFAULT NULL, answer_date DATETIME DEFAULT NULL, hangup_type VARCHAR(255) DEFAULT NULL, hangup_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_phone_call (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, duration INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E83324BBE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_note (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E74278EBE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_info_field (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, meta TINYINT(1) NOT NULL, sorting INT DEFAULT NULL, UNIQUE INDEX UNIQ_9615D93A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_log_status (id INT AUTO_INCREMENT NOT NULL, phone_log_id INT DEFAULT NULL, date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_46F1F02B17F6FE89 (phone_log_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_info (id BIGINT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, field_id INT DEFAULT NULL, integer_value INT DEFAULT NULL, string_value VARCHAR(255) DEFAULT NULL, decimal_value DOUBLE PRECISION DEFAULT NULL, date_value DATE DEFAULT NULL, datetime_value DATETIME DEFAULT NULL, INDEX IDX_E376B3A8E7A1254A (contact_id), INDEX IDX_E376B3A8443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_phone_call ADD CONSTRAINT FK_E83324BBE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_note ADD CONSTRAINT FK_E74278EBE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE phone_log_status ADD CONSTRAINT FK_46F1F02B17F6FE89 FOREIGN KEY (phone_log_id) REFERENCES phone_log (id)');
        $this->addSql('ALTER TABLE contact_info ADD CONSTRAINT FK_E376B3A8E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_info ADD CONSTRAINT FK_E376B3A8443707B0 FOREIGN KEY (field_id) REFERENCES contact_info_field (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE phone_log_status DROP FOREIGN KEY FK_46F1F02B17F6FE89');
        $this->addSql('ALTER TABLE contact_phone_call DROP FOREIGN KEY FK_E83324BBE7A1254A');
        $this->addSql('ALTER TABLE contact_note DROP FOREIGN KEY FK_E74278EBE7A1254A');
        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8E7A1254A');
        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8443707B0');
        $this->addSql('DROP TABLE phone_log');
        $this->addSql('DROP TABLE contact_phone_call');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_note');
        $this->addSql('DROP TABLE contact_info_field');
        $this->addSql('DROP TABLE phone_log_status');
        $this->addSql('DROP TABLE contact_info');
    }
}
