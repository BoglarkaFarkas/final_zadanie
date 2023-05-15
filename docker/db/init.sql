USE db;

CREATE TABLE IF NOT EXISTS myUserPanel (
  id INT PRIMARY KEY AUTO_INCREMENT,
  meno VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  priezvisko VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  email VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  heslo VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  role VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL
);

CREATE TABLE IF NOT EXISTS zadanieLatex (
  id INT PRIMARY KEY AUTO_INCREMENT,
  latexSubor VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL
);

CREATE TABLE IF NOT EXISTS testForStudents (
  id INT PRIMARY KEY AUTO_INCREMENT,
  startDate VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  timeDate VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  deadlineDate VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  deadlineTime VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  latexSubor_id INT NOT NULL,
  teacher_id INT NOT NULL,
  maxPoint INT NOT NULL
);

INSERT IGNORE INTO myUserPanel (id, meno, priezvisko, email, heslo, role)
VALUES (1,'Olga', 'Kohut', 'kohut9@stuba.sk', '$argon2id$v=19$m=65536,t=4,p=1$OVpBMEE4cXJjcTVENFZWRQ$AtLSBxR3m230vZ3u/gyZviZV+KHhJk1OS5b5LV+AYC8', 'ucitel');
