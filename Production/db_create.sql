CREATE DATABASE IF NOT EXISTS oso;
USE oso;

CREATE TABLE IF NOT EXISTS Person (
    personID int NOT NULL AUTO_INCREMENT,
    essexAccount varchar(255) NOT NULL,
    userName varchar(255) NOT NULL,
    userSurname varchar(255) NOT NULL,
    userType varchar(255) NOT NULL,
    PRIMARY KEY (personID)
);

CREATE TABLE IF NOT EXISTS Requester (
    requesterID int NOT NULL AUTO_INCREMENT,
    requestedby varchar(255) NOT NULL,
    requesterEmail varchar(255) NOT NULL,
    PRIMARY KEY (requesterID)
);

CREATE TABLE IF NOT EXISTS PersonToLocation (
    personID int NOT NULL,
    location varchar(255) NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PersonToDisability (
    personID int NOT NULL,
    disability varchar(255) NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS PersonToTime (
    personID int NOT NULL,
    startDate date NOT NULL,
    endDate date NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PersonToEquipment (
    personID int NOT NULL,
    equipment varchar(255) NOT NULL,
    INVNumbers varchar(255),
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PersonToSupervisor (
    personID int NOT NULL,
    supervisor varchar(255) NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PersonToKey (
    personID int NOT NULL,
    roomKey varchar(255) NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PersonToRequester (
    personID int NOT NULL,
    requesterID int NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID) ON DELETE CASCADE,
    FOREIGN KEY (requesterID) REFERENCES Requester(requesterID) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS PersontToStatus (
    personID int NOT NULL,
    reqStatus varchar(255) NOT NULL,
    submitedDate date NOT NULL,
    lastupdate date NOT NULL,
    FOREIGN KEY (personID) REFERENCES Person(personID)
);
