<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200522171025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD cat_id_id INT DEFAULT NULL, ADD sub_cat_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC33F2EBA FOREIGN KEY (cat_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD1326804 FOREIGN KEY (sub_cat_id_id) REFERENCES subcategory (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADC33F2EBA ON product (cat_id_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADD1326804 ON product (sub_cat_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADC33F2EBA');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD1326804');
        $this->addSql('DROP INDEX IDX_D34A04ADC33F2EBA ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADD1326804 ON product');
        $this->addSql('ALTER TABLE product DROP cat_id_id, DROP sub_cat_id_id');
    }
}
