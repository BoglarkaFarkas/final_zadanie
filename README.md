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

Ak by sa nenačítal script na init databázy tak pred spustením kontajnera: ` docker volume rm data_volume `


### Databáza
Tabuľka: myUserPanel

Táto tabuľka slúži na ukldanie používateľov.
Role: 
- Študent
- Učiteľ

| meno | priezvisko | email | heslo | role|
|------|:----------:|:-----:|:-----:|----:|


## Tech Stack

**Client:** HTML 5, CSS, JavaScript, Bootstrap

**Server:** PHP

**DB:** MySQL
