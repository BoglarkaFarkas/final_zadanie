# Záverečné zadanie

Repozitár k záverečnému zadaniu z predmetu Webové technológie 2 v LS 2022/2023.




## Členovia tímu

- Boglárka Farkas - 111222
- Patrik Novák  - 111404
- Stanislava Pecková - 111412
- Gábor Varga - 111481


## Documentation

### Docker

Spustenie kontajnera: ` docker-compose up --build -d `

Zastavenie kontajnera: ` docker-compose down ` 

Ak by sa nenačítal script na init databázy tak pred spustením kontajnera: ` docker volume rm final_zadanie_db_data `


### Databáza
http://localhost:8081/ - ak si to spustíte pomocou docker compose u seba

Prihlasovacie meno: `webtefinal`

Heslo: `password`

Tabuľka: users

Táto tabuľka slúži na ukldanie používateľov.
Role: 
- student
- ucitel

| id | name | surname | email | password | role|
|----|:----:|:----------:|:-----:|:-----:|----:|

Tabuľka: generatedExamples

| id | id_student | id_example | status |
|----|:----------:|:----------:|-------:|

Tabuľka: examples
| id | file_name | example_name | example_body | solution | start_date | deadline_date | points| solvable |
|----|:---------:|:------------:|:------------:|:--------:|:----------:|:-------------:|:-----:|----:|

## Tech Stack

**Client:** HTML 5, CSS, JavaScript, Bootstrap

**Server:** PHP

**DB:** MySQL

**Libs:** TCPDF, MathJax, Mathquill, jQuery

## Nesplnené časti zadania
- ekvivalencia zápisu a vyhodnotenia odpovedí

## Upozornenie
Úlohy existujú až keď existuje aspoň jeden učiteľ
