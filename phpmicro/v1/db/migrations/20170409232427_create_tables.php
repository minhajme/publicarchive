<?php

use Phinx\Migration\AbstractMigration;

class CreateTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->execute("DROP TABLE IF EXISTS listing_view;");
        $this->execute("DROP TABLE IF EXISTS listing;");
        $this->execute("DROP TABLE IF EXISTS listing_source;");
        $this->execute("DROP TABLE IF EXISTS users");

        $this->execute("CREATE TABLE users (
            id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE KEY,
            password VARCHAR(100) NOT NULL,
            salt VARCHAR(50) NOT NULL 
            ) ENGINE =InnoDB DEFAULT CHARSET =utf8mb4");

        $this->execute("CREATE TABLE listing_source (
            id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            url TEXT NOT NULL,
            title VARCHAR(250) NOT NULL,
            user_id INT(10) UNSIGNED,
            FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
          ) ENGINE =InnoDB DEFAULT CHARSET =utf8mb4");

        $this->execute("CREATE TABLE listing (
            id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            source_id INT(10) UNSIGNED,
            title VARCHAR(250) NOT NULL, 
            price VARCHAR(50) NOT NULL,
            url TEXT NOT NULL,
            posted VARCHAR(50) NOT NULL,
            FOREIGN KEY (source_id) REFERENCES listing_source(id) ON DELETE CASCADE ON UPDATE CASCADE 
            ) ENGINE=InnoDB DEFAULT CHARSET =utf8mb4");

        $this->execute("CREATE TABLE listing_view (
            id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            listing_id INT(10) UNSIGNED,
            user_id INT(10) UNSIGNED,
            FOREIGN KEY (listing_id) REFERENCES listing(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE =InnoDB DEFAULT CHARSET =utf8mb4");
    }
}
