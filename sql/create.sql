CREATE TABLE filiale(
    fid integer GENERATED BY DEFAULT ON NULL AS IDENTITY (START WITH 1 INCREMENT BY 1),
    stadt varchar(40) NOT NULL,
    land varchar(40) NOT NULL,
    adresse varchar(70),
    
    PRIMARY KEY (fid)
);

CREATE TABLE autowerkstatt(
    fid integer,
    aw_id integer GENERATED BY DEFAULT AS IDENTITY (START WITH 10 INCREMENT BY 1),
    telefonnummer varchar(20) NOT NULL,
    anzahl_mitarbeiter integer DEFAULT 3,
    
    UNIQUE(telefonnummer),
    
    PRIMARY KEY (fid, aw_id),
    FOREIGN KEY (fid) REFERENCES Filiale ON DELETE CASCADE
);

CREATE TABLE leasing(
    lnr integer GENERATED BY DEFAULT AS IDENTITY (START WITH 100 INCREMENT BY 1),
    dauer integer DEFAULT 5,
    preis numeric(10,2) NOT NULL,
    
    CONSTRAINT dauer_check CHECK (dauer >= 0 AND dauer <= 20),
    CONSTRAINT preis_check CHECK (preis >= 0),    
    
    PRIMARY KEY (lnr)
);

CREATE TABLE auto(
    aid integer,
    marke varchar(40),
    modell varchar(50),
    lnr integer,
    
    PRIMARY KEY (aid),
    FOREIGN KEY (lnr) REFERENCES leasing
);

CREATE TABLE elektroauto(
    aid integer,
    reichweite integer,
    kWh numeric(5,2),
    
    PRIMARY KEY(aid),
    FOREIGN KEY(aid) REFERENCES auto ON DELETE CASCADE
);

CREATE TABLE suv(
    aid integer,
    motorgroesse numeric (3,1),
    verbrauch numeric(3,1),
    
    PRIMARY KEY(aid),
    FOREIGN KEY(aid) REFERENCES auto ON DELETE CASCADE
);

CREATE TABLE mitarbeiter(
    mid integer,
    vorname varchar(20) NOT NULL,
    nachname varchar(30) NOT NULL,
    fid integer,
    chefid integer DEFAULT NULL,
    
    PRIMARY KEY(mid),
    FOREIGN KEY (fid) REFERENCES filiale ON DELETE SET NULL,
    FOREIGN KEY (chefid) REFERENCES mitarbeiter ON DELETE SET NULL
);

CREATE TABLE verkauft(
    mid integer,
    aid integer,
    preis numeric(10,2) NOT NULL,
    datum date NOT NULL,
    
    CONSTRAINT preis_check_v CHECK (preis >= 0),   
    
    PRIMARY KEY(mid,aid),
    FOREIGN KEY(mid) REFERENCES mitarbeiter,
    FOREIGN KEY(aid) REFERENCES auto
);

CREATE TABLE hat(
    fid integer,
    aid integer,
    
    PRIMARY KEY(fid,aid),
    FOREIGN KEY (fid) REFERENCES filiale ON DELETE CASCADE,
    FOREIGN KEY (aid) REFERENCES auto ON DELETE CASCADE
);


------SEQUENCE------
CREATE SEQUENCE auto_seq
START WITH 100
INCREMENT BY 1;

------TRIGGER------
CREATE OR REPLACE TRIGGER auto_trig
BEFORE INSERT ON auto
FOR EACH ROW
BEGIN
    SELECT auto_seq.nextval
    INTO :new.aid
    FROM dual;
END;


------SEQUENCE------
CREATE SEQUENCE mitarbeiter_seq
START WITH 100
INCREMENT BY 1;

------TRIGGER------
CREATE OR REPLACE TRIGGER mitarbeiter_trig
BEFORE INSERT ON mitarbeiter
FOR EACH ROW
BEGIN
    SELECT mitarbeiter_seq.nextval
    INTO :new.mid
    FROM dual;
END;


-----VIEWS-----
CREATE VIEW leasing_options AS 
SELECT auto.marke, auto.modell, leasing.dauer, leasing.preis
FROM auto JOIN leasing
ON auto.lnr  = leasing.lnr;

CREATE VIEW ecars AS
SELECT AUTO.marke, auto.modell, ELEKTROAUTO.reichweite, ELEKTROAUTO.kwh
FROM auto join ELEKTROAUTO
on auto.aid = ELEKTROAUTO.aid;



CREATE VIEW total_sales AS
    SELECT mitarbeiter.mid, mitarbeiter.vorname, mitarbeiter.nachname, auto.marke, auto.modell,  verkauft.datum, SUM(preis) sum
    FROM verkauft JOIN mitarbeiter
                      ON mitarbeiter.mid = verkauft.mid
    JOIN AUTO
    ON auto.AID = verkauft.aid
GROUP BY mitarbeiter.mid, mitarbeiter.vorname, mitarbeiter.nachname, auto.marke, auto.modell,verkauft.datum
HAVING SUM(preis) > 100000
ORDER BY SUM(preis) DESC;


------Procedures------
CREATE OR REPLACE PROCEDURE add_mitarbeiter(
    mitarbeiter_vorname  IN  mitarbeiter.VORNAME%TYPE,
    mitarbeiter_nachname IN MITARBEITER.NACHNAME%TYPE,
   mitarbeiter_fid IN MITARBEITER.FID%Type,
    error_code OUT NUMBER
)
AS
BEGIN
    INSERT INTO MITARBEITER( vorname, nachname, fid)
    VALUES (mitarbeiter_vorname, mitarbeiter_nachname, mitarbeiter_fid);

    error_code := SQL%ROWCOUNT;
    IF (error_code = 1)
    THEN
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
EXCEPTION
    WHEN OTHERS
        THEN
            error_code := SQLCODE;
END add_mitarbeiter;




CREATE OR REPLACE PROCEDURE delete_mitarbeiter(
    id  IN  MITARBEITER.mid%TYPE,
    error_code OUT NUMBER
)
AS
BEGIN
    DELETE
    FROM MITARBEITER
    WHERE id = MITARBEITER.MID;

    error_code := SQL%ROWCOUNT;
    IF (error_code = 1)
    THEN
        COMMIT;
    ELSE
        ROLLBACK;
    END IF;
EXCEPTION
    WHEN OTHERS
        THEN
            error_code := SQLCODE;
END delete_mitarbeiter;



commit;
