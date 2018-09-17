CREATE TABLE `member` (
  `memberID` int NOT NULL AUTO_INCREMENT,
  `password` varchar(39) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address`	varchar(255) NOT NULL,
  `email`	varchar(255) NOT NULL,
  `DOB`	date NOT NULL,
  `accountBalance` float NOT NULL,
  `status`	boolean NOT NULL DEFAULT 1,
  `privilege` boolean NOT NULL DEFAULT 0,
  `lastActivity` timestamp,
   PRIMARY KEY (memberID)
)AUTO_INCREMENT=11000;


CREATE TABLE `driver` (
  `memberID` int NOT NULL UNIQUE,
  `driverID` int NOT NULL,
  `rating` 	 int(5) DEFAULT 5,
  `driversLisence` boolean NOT NULL,
  `insuranceStatus` boolean NOT NULL,
  PRIMARY KEY(driverID),
  FOREIGN KEY(memberID) references member(memberID) ON DELETE CASCADE  
);

CREATE TABLE `rider` (
  `memberID` int NOT NULL UNIQUE,
  `riderID` int NOT NULL,  
  PRIMARY KEY(riderID),
  FOREIGN KEY(memberID) references member(memberID)ON DELETE CASCADE
);

CREATE TABLE `request` (
  `riderID` int NOT NULL,
  `tripID` int NOT NULL,  
  PRIMARY KEY(tripID),
  FOREIGN KEY(riderID) references rider(riderID) ON DELETE CASCADE,
  FOREIGN KEY(tripID) references trip(tripID) ON DELETE CASCADE  
);

CREATE TABLE `offer` (
  `driverID` int NOT NULL,
  `tripID` int NOT NULL,  
  PRIMARY KEY(tripID),
  FOREIGN KEY(driverID) references driver(driverID) ON DELETE CASCADE,
  FOREIGN KEY(tripID) references trip(tripID) ON DELETE CASCADE  
);

CREATE TABLE `post` (
  `postID` int NOT NULL AUTO_INCREMENT,
  `posterID` int NOT NULL,
  `postBody` varchar(255) NOT NULL,
  PRIMARY KEY(postID),
  FOREIGN KEY(posterID) references member(memberID) ON DELETE CASCADE
)AUTO_INCREMENT=1;


CREATE TABLE `inbox` (
  `messageID` int NOT NULL AUTO_INCREMENT,
  `senderID` int NOT NULL,
  `receipientID` int NOT NULL,
  `messageBody` varchar(255) NOT NULL,
  PRIMARY KEY(messageID),
  FOREIGN KEY(senderID) references member(memberID) ON DELETE CASCADE,
  FOREIGN KEY(receipientID) references member(memberID) ON DELETE CASCADE  
)AUTO_INCREMENT=1;


CREATE TABLE `trip` (
  `tripID` int NOT NULL AUTO_INCREMENT,
  `departureCity` int NOT NULL UNIQUE,
  `destinationCity` int UNIQUE,  
  `tripType` varchar (7) NOT NULL,
  `departureDate` date,
  `daysOfWeek` varchar(13),
  `radius` int,
  `tariff` int,
  `tripDescription` varchar(255),
  PRIMARY KEY(tripID),
  FOREIGN KEY(departureCity) references city(cityID) ON DELETE CASCADE,
  FOREIGN KEY(destinationCity) references city(cityID) ON DELETE CASCADE   
)AUTO_INCREMENT=1;


CREATE TABLE `city` (
  `cityID` int AUTO_INCREMENT,
  `tripID` int,  
  `postalCode` varchar(7) NOT NULL,  
  `city_province` varchar(150) NOT NULL,  
  PRIMARY KEY(cityID) 
)AUTO_INCREMENT=1;
ALTER TABLE city ADD CONSTRAINT fk_trip_id FOREIGN KEY (tripID) REFERENCES trip(tripID) ON DELETE CASCADE;

CREATE TABLE `matched` (
  `matchID` int NOT NULL AUTO_INCREMENT,
  `offerTripID` int NOT NULL,
  `requestTripID` int NOT NULL,
  `distance` double NOT NULL,
  PRIMARY KEY(matchID),
  FOREIGN KEY(offerTripID) references trip(tripID) ON DELETE CASCADE,
  FOREIGN KEY(requestTripID) references trip(tripID) ON DELETE CASCADE
)AUTO_INCREMENT=1;


CREATE TABLE `ride` (  
  `matchedID` int NOT NULL,
  `rideCost` double NOT NULL,
  `rideRating` int(11),
  `driverID` int(11),
  `riderID` int(11),
  PRIMARY KEY(matchedID),
  FOREIGN KEY(matchedID) references matched(matchID) ON DELETE CASCADE, 
  FOREIGN KEY(driverID) references driver(driverID) ON DELETE SET NULL,
  FOREIGN KEY(riderID) references rider(riderID) ON DELETE SET NULL
);

DELIMITER $$
CREATE TRIGGER `member_after_addition` AFTER INSERT ON `member` FOR EACH ROW BEGIN 
INSERT INTO rider (memberID, riderID) VALUES (NEW.memberID, NEW.memberID);
INSERT INTO driver (memberID, driverID, driversLisence, insuranceStatus) VALUES (NEW.memberID, NEW.memberID, '1', '1'); 
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `ride_after_addition` AFTER INSERT ON `ride` FOR EACH ROW BEGIN 
UPDATE member SET accountBalance = accountBalance - NEW.rideCost WHERE memberID = NEW.riderID;
UPDATE member SET accountBalance = accountBalance + (0.95 * NEW.rideCost) WHERE memberID = NEW.driverID;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `ride_after_rating` AFTER UPDATE ON `ride` FOR EACH ROW BEGIN 
UPDATE driver SET rating = ROUND(((rating + NEW.rideRating)/2),0) WHERE driverID = NEW.driverID;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `driver_rating` AFTER UPDATE ON `driver` FOR EACH ROW BEGIN 
IF (new.rating > 5 OR new.rating < 1) 
THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Rating must be between 1 - 5'; 
END IF; 
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `ride_rating` BEFORE UPDATE ON `ride` FOR EACH ROW BEGIN 
IF (new.rideRating > 5 OR new.rideRating < 1) 
THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Rating must be between 1 - 5'; 
END IF; 
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `trip_validate` BEFORE INSERT ON `trip` FOR EACH ROW BEGIN 
IF (new.radius > 30 OR new.radius < 1) 
THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Radius cannot be greater than 30km'; 
END IF;
IF (new.tripType <> 'oneTime' AND new.tripType <> 'regular')
THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Trip type must be either oneTime or regular'; 
END IF;  
END$$
DELIMITER ;


