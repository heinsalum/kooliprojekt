# CSS Pildigalerii
Pilt tuleb siia:
https://imgur.com/a/JI4xN


Rühma liikmed:

Markus Heinsalu

Erik Enden

Kristo Roots

Meie eesmärgiks oli luua interaktiivne veebileht, kuhu saab lisada pilte, ja neid hallata.
1. Saab teha kasutaja ja sisselogida.
2. Saad lisada enda valitud pilte.
3. Pilte saab hinnata.


**Mida õppisid juurde?**

**Kristo Roots**: Õppisin juurde PHP ülesehitust, natukene CSS, keeruline oli tihtipeale oma enda "väikest" näpukat leida, kui jätad kuhugile punkti, semikooloni panemata, siis hiljem maksab see kätte.

**Erik Enden**: Sain oluliselt rohkem tuttavaks reaalse projekti käigus PHP ja MySQL sidumisega. Tunnen ennast PHP-d kirjutades oluliselt mugavamalt ning tunnen, et probleemide lahendamine internetist lahenduse otsimise asemel improviseerides on oluliselt jõukohasem. Samuti sain targemaks veaotsingul ning eriti SQL-ist tulevnevate vigade diagnoosimisel. 

**Markus Heinsalu**: Graafiline kujundus(CSS, BS), erinevat tüüpi PHP ja HTML ülesehitused, veidi JavaScripti. CSS ülesehitus vajab harjumist, kuna antud hetkel on see liiga uus ja võrreldes BS'iga on see palju palju raskem.

Andmebaaside pilt:
https://imgur.com/a/BZb4X

Tabeli loomine:
Piltide andmebaas:
CREATE TABLE galeriid(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nimi VARCHAR(50),
    yleslaadija INT,
    hinne FLOAT,
    fail VARCHAR(50)
);

Kasutajate andmebaas:
CREATE TABLE users(
	id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	firstname VARCHAR(30),
	lastname VARCHAR(30),
	birthday DATE,
	gender INT,
	email VARCHAR(100)
	password VARCHAR(128)
	created TIMESTAMP
);




