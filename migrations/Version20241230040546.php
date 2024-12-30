<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230040546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_isotope (product_id INT NOT NULL, isotope_id INT NOT NULL, PRIMARY KEY(product_id, isotope_id))');
        $this->addSql('CREATE INDEX IDX_717E05FC4584665A ON product_isotope (product_id)');
        $this->addSql('CREATE INDEX IDX_717E05FC8332B8A0 ON product_isotope (isotope_id)');
        $this->addSql('ALTER TABLE product_isotope ADD CONSTRAINT FK_717E05FC4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_isotope ADD CONSTRAINT FK_717E05FC8332B8A0 FOREIGN KEY (isotope_id) REFERENCES isotope (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE isotope_product DROP CONSTRAINT fk_9aef6b218332b8a0');
        $this->addSql('ALTER TABLE isotope_product DROP CONSTRAINT fk_9aef6b214584665a');
        $this->addSql('DROP TABLE isotope_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE isotope_product (isotope_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(isotope_id, product_id))');
        $this->addSql('CREATE INDEX idx_9aef6b214584665a ON isotope_product (product_id)');
        $this->addSql('CREATE INDEX idx_9aef6b218332b8a0 ON isotope_product (isotope_id)');
        $this->addSql('ALTER TABLE isotope_product ADD CONSTRAINT fk_9aef6b218332b8a0 FOREIGN KEY (isotope_id) REFERENCES isotope (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE isotope_product ADD CONSTRAINT fk_9aef6b214584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_isotope DROP CONSTRAINT FK_717E05FC4584665A');
        $this->addSql('ALTER TABLE product_isotope DROP CONSTRAINT FK_717E05FC8332B8A0');
        $this->addSql('DROP TABLE product_isotope');
    }
}
