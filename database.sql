DROP DATABASE IF EXISTS Digimon;

CREATE DATABASE Digimon;
USE Digimon;

-- Create User table
CREATE TABLE User (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL,
  dob DATE NOT NULL,
  password VARCHAR(40) NOT NULL,
  registered TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  active BOOLEAN NULL DEFAULT FALSE
);

ALTER TABLE `User`
  ADD UNIQUE(`email`);

-- Create Stage table
CREATE TABLE Stage (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(20) NOT NULL
);

ALTER TABLE `Stage`
  ADD UNIQUE(`name`);

-- Create Digimon table
CREATE TABLE Digimon (
  id INT AUTO_INCREMENT PRIMARY KEY,
  stage INT,
  name VARCHAR(13) NOT NULL,
  active BOOLEAN NULL DEFAULT FALSE
);

ALTER TABLE `Digimon`
  ADD UNIQUE(`name`),
  ADD INDEX `stage` (`stage`),
  ADD CONSTRAINT fk_Digimon_StageId
    FOREIGN KEY (stage)
      REFERENCES Stage(id);

-- Create State table
CREATE TABLE State (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(20) NOT NULL
);

ALTER TABLE `State`
  ADD UNIQUE(`name`);

-- Create Sprite table
CREATE TABLE Sprite (
  id INT AUTO_INCREMENT PRIMARY KEY,
  digimon INT NOT NULL,
  state INT NOT NULL,
  height INT NOT NULL,
  width INT NOT NULL
);

ALTER TABLE `Sprite`
  ADD UNIQUE(`digimon`, `state`);

-- Create Monster table
CREATE TABLE Monster (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user INT NOT NULL,
  digimon INT NOT NULL
);

ALTER TABLE `Monster`
  ADD INDEX `user` (`user`),
  ADD INDEX `digimon` (`digimon`),
  ADD CONSTRAINT fk_Monster_DigimonId
    FOREIGN KEY (digimon)
      REFERENCES Digimon(id),
  ADD CONSTRAINT fk_Monster_UserId
    FOREIGN KEY (user)
      REFERENCES User(id);

-- Create Event table
CREATE TABLE Event (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  userEvent BOOLEAN NOT NULL DEFAULT 1
);

-- Create Function: isUserEvent
DELIMITER $$
CREATE FUNCTION `isUserEvent`(name VARCHAR(30))
	RETURNS BOOLEAN
BEGIN
	DECLARE userEvent BOOLEAN;
	SELECT `userEvent` INTO userEvent FROM Event WHERE `name` = name;
	RETURN userEvent;
END$$
DELIMITER ;

-- Create Action table
CREATE TABLE Action (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user INT NULL,
  event INT NOT NULL,
  value VARCHAR(50) NOT NULL,
  triggered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `Action`
  ADD INDEX `user` (`user`),
  ADD INDEX `event` (`event`),
  ADD CONSTRAINT ck_Action_UserId
    CHECK (
      `user` IS NULL AND (0 = isUserEvent(event)) AND
      `user` IS NOT NULL AND (1 = isUserEvent(event))
    ),
  ADD CONSTRAINT fk_Action_EventId
    FOREIGN KEY (event)
      REFERENCES Event(id);


-- Populate with default data
INSERT INTO Stage (`name`)
  VALUES
    ('Baby'), ('In-Training'), ('Rookie'), ('Champion'), ('Ultimate');

INSERT INTO Digimon (`name`, `stage`)
  VALUES
    ('Botamon', (SELECT `id` FROM Stage WHERE `name` = 'Baby')),
    ('Koromon', (SELECT `id` FROM Stage WHERE `name` = 'In-Training')),
    ('Agumon', (SELECT `id` FROM Stage WHERE `name` = 'Rookie')),
    ('Betamon', (SELECT `id` FROM Stage WHERE `name` = 'Rookie')),
    ('Greymon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Tyranomon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Devimon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Meramon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Airdramon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Seadramon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Numemon', (SELECT `id` FROM Stage WHERE `name` = 'Champion')),
    ('Metal Greymon', (SELECT `id` FROM Stage WHERE `name` = 'Ultimate')),
    ('Mamemon', (SELECT `id` FROM Stage WHERE `name` = 'Ultimate')),
    ('Monzaemon', (SELECT `id` FROM Stage WHERE `name` = 'Ultimate'));

INSERT INTO State (`name`)
  VALUES
    ('big-left'),
    ('big-right'),
    ('small-left'),
    ('small-right'),
    ('eating'),
    ('happy'),
    ('refuse'),
    ('angry'),
    ('attack'),
    ('hurt-big'),
    ('hurt-small'),
    ('sleep-big'),
    ('sleep-small');

INSERT INTO Event (`name`, `userEvent`)
  VALUES
    ('create', 1),
    ('evolve', 1),
    ('die', 1),
    ('meat', 1),
    ('vitamin', 1),
    ('train', 1),
    ('battle', 1),
    ('poop', 1),
    ('pageview', 0),
    ('log-in', 1),
    ('log-out', 1);

INSERT INTO User (`firstname`, `lastname`, `email`, `dob`, `password`)
  VALUES ('Bradly', 'Sharpe', 'fake@bradlysharpe.com.au', '1989-12-02', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');


-- Activate objects
UPDATE Digimon SET active = 1;
UPDATE User SET active = 1;
