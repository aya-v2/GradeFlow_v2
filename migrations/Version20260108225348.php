<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108225348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, level INT NOT NULL, school_year_id INT NOT NULL, filiere_id INT NOT NULL, INDEX IDX_8F87BF96D2EECC3F (school_year_id), INDEX IDX_8F87BF96180AA129 (filiere_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE enrollment (id INT AUTO_INCREMENT NOT NULL, enrollment_date DATE NOT NULL, student_id INT NOT NULL, associated_class_id INT NOT NULL, INDEX IDX_DBDCD7E1CB944F1A (student_id), INDEX IDX_DBDCD7E19BAAC766 (associated_class_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE filiere (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, cc_grade DOUBLE PRECISION DEFAULT NULL, exam_grade DOUBLE PRECISION DEFAULT NULL, is_published TINYINT NOT NULL, student_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_595AAE34CB944F1A (student_id), INDEX IDX_595AAE34AFC2B591 (module_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, coefficient DOUBLE PRECISION NOT NULL, semester VARCHAR(20) NOT NULL, filiere_id INT NOT NULL, INDEX IDX_C242628180AA129 (filiere_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE school_year (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, is_current TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE teaching_assignment (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, associated_class_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_93BFC67E41807E1D (teacher_id), INDEX IDX_93BFC67E9BAAC766 (associated_class_id), INDEX IDX_93BFC67EAFC2B591 (module_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, cin VARCHAR(20) NOT NULL, type VARCHAR(20) NOT NULL, cne VARCHAR(50) DEFAULT NULL, specialty VARCHAR(100) DEFAULT NULL, birth_date DATE DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96D2EECC3F FOREIGN KEY (school_year_id) REFERENCES school_year (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E19BAAC766 FOREIGN KEY (associated_class_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('ALTER TABLE teaching_assignment ADD CONSTRAINT FK_93BFC67E41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE teaching_assignment ADD CONSTRAINT FK_93BFC67E9BAAC766 FOREIGN KEY (associated_class_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE teaching_assignment ADD CONSTRAINT FK_93BFC67EAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96D2EECC3F');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96180AA129');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E1CB944F1A');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E19BAAC766');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34AFC2B591');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628180AA129');
        $this->addSql('ALTER TABLE teaching_assignment DROP FOREIGN KEY FK_93BFC67E41807E1D');
        $this->addSql('ALTER TABLE teaching_assignment DROP FOREIGN KEY FK_93BFC67E9BAAC766');
        $this->addSql('ALTER TABLE teaching_assignment DROP FOREIGN KEY FK_93BFC67EAFC2B591');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE enrollment');
        $this->addSql('DROP TABLE filiere');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE school_year');
        $this->addSql('DROP TABLE teaching_assignment');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
