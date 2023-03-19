<?php

class DatabaseHelper
{
    // Details for connecting with the database
    const username = ' ';
    const password = ' ';
    const con_string = '//oracle19.cs.univie.ac.at:1521/orclcdb';

    protected $conn;

    // Create connection in the constructor
    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        // clean up
        oci_close($this->conn);
    }

    // This function creates and executes a SQL select statement and returns an array as the result
    // 2-dimensional array: the result array contains nested arrays (each contains the data of a single row)
    public function selectAllLocations($fid, $stadt, $land, $adresse)
    {
        $sql = "SELECT * FROM filiale
            WHERE fid LIKE '%{$fid}%'
              AND upper(stadt) LIKE upper('%{$stadt}%')
              AND upper(land) LIKE upper('%{$land}%')
              AND upper(adresse) LIKE upper('%{$adresse}')
            ORDER BY land ASC";

        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectCarsFromLocation($aid, $marke, $modell, $lnr, $id)
    {
        $sql = "SELECT * FROM AUTO 
         WHERE AID IN (SELECT AID from HAT WHERE fid = '$id')
          AND aid LIKE '%{$aid}%'
          AND upper(marke) LIKE upper('%{$marke}%')
          AND upper(modell) LIKE upper('%{$modell}%')
          AND lnr LIKE '%{$lnr}%'
          ORDER BY marke ASC";

        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectEmployeesFromLocation($fid)
    {
        $sql = "SELECT * FROM Mitarbeiter 
         WHERE FID = '{$fid}'";

        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectSales($fid)
    {
        $sql = "select * from total_sales where mid in (select mid FROM Mitarbeiter WHERE FID = '{$fid}')";

        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    // This function creates and executes a SQL insert statement and returns true or false
    public function insertIntoLocation($stadt, $land, $adresse)
    {
        $sql = "INSERT INTO FILIALE (stadt, land, adresse) VALUES ('{$stadt}', '{$land}','{$adresse}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function insertIntoCar($marke, $modell, $lnr, $fid)
    {
        $sql = "INSERT INTO AUTO (marke, modell, lnr) VALUES ('{$marke}','{$modell}', '{$lnr}')";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);

        $sql = "SELECT AID from AUTO WHERE MARKE = '{$marke}' AND MODELL ='{$modell}' AND aid = (SELECT MAX(aid) FROM AUTO)";
        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);

        while ($row = oci_fetch_array($statement, OCI_NUM)) {
            $AutoID = $row[0];
        }

        $sql = "INSERT INTO HAT (FID, AID) VALUES ('{$fid}', '{$AutoID}')";
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement);

        oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function insertIntoEmployee($name, $surname, $fid)
    {
        $errorcode = 0;

        $sql = 'BEGIN add_mitarbeiter(:name, :surname, :fid, :errorcode); END;';
        $statement = @oci_parse($this->conn, $sql);

        //  Bind the parameters
        @oci_bind_by_name($statement, ':name', $name);
        @oci_bind_by_name($statement, ':surname', $surname);
        @oci_bind_by_name($statement, ':fid', $fid);
        @oci_bind_by_name($statement, ':errorcode', $errorcode);

        @oci_execute($statement);
        @oci_free_statement($statement);

        return $errorcode;
    }

    public function deleteLocation($location_id)
    {
        $test = 'SELECT * FROM FILIALE WHERE fid = :location_id';
        $statement = oci_parse($this->conn, $test);
        oci_bind_by_name($statement, ':location_id', $location_id);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        if ($res == null) {
            oci_free_statement($statement);
            return 0;
        }

        $sql = 'DELETE FROM FILIALE WHERE fid= :location_id';
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':location_id', $location_id);

        oci_execute($statement);
        oci_free_statement($statement);
        return 1;
    }

    public function deleteCar($location_id, $car_id)
    {
        $test = "SELECT * FROM HAT WHERE fid = '{$location_id}' AND aid = '{$car_id}'";
        $statement = oci_parse($this->conn, $test);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        if ($res == null) {
            oci_free_statement($statement);
            return 0;
        }

        $sql = "DELETE FROM HAT WHERE FID = '{$location_id}' AND AID = '{$car_id}'";
        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);
        oci_free_statement($statement);
        return 1;
    }

    public function deleteEmployee($id)
    {
        $errorcode = 0;

        $sql = 'BEGIN DELETE_Mitarbeiter(:id, :errorcode); END;';
        $statement = @oci_parse($this->conn, $sql);


        @oci_bind_by_name($statement, ':id', $id);
        @oci_bind_by_name($statement, ':errorcode', $errorcode);

        @oci_execute($statement);
        @oci_free_statement($statement);
        return $errorcode;
    }

    public function getLocationName($id)
    {
        $sql = 'SELECT stadt FROM filiale WHERE fid = :id';
        $statement = oci_parse($this->conn, $sql);
        oci_bind_by_name($statement, ':id', $id);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($statement);

        return $res;
    }
}
