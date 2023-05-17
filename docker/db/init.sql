USE db;

CREATE TABLE IF NOT EXISTS examples (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	file_name VARCHAR(100) NOT NULL , 
	example_name VARCHAR(50) NOT NULL , 
	example_body TEXT NOT NULL , 
	solution TEXT NOT NULL , 
	start_date DATETIME NULL DEFAULT NULL , 
	deadline_date DATETIME NULL DEFAULT NULL , 
	points TINYINT UNSIGNED NOT NULL , 
	solvable BOOLEAN NOT NULL , 
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	name VARCHAR(100) NOT NULL , 
	surname VARCHAR(50) NOT NULL , 
	email VARCHAR(50) NOT NULL , 
	password VARCHAR(100) NOT NULL , 
	role VARCHAR(10) NOT NULL , 
	PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE TABLE IF NOT EXISTS generatedStudent (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_student INT UNSIGNED NOT NULL,
  id_example INT UNSIGNED NOT NULL,
  status TINYINT(1) DEFAULT NULL
) ENGINE=InnoDB;
