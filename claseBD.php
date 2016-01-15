<?php
$hostname = 'localhost';
$dbname = 'dwes';
$dns="mysql:host=$hostname;dbname=$dbname";
$username = 'root';
$password = 'root';
$options= array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_FOUND_ROWS => true);


function connectPDO($dns, $username, $password, $options) {
    $conexion = new PDO($dns, $username, $password, $options);
    $conexion->exec("SET CHARACTER SET utf8");
    return $conexion;
}

function createInsert($table, $values) {
    $sql = "Insert into $table (";
    $control = 0;
    foreach ($values as $index => $value) {
        $sql.="$index";
        $vsql.=":$index";
        if ($control != count($values)-1) {
            $sql .=", ";
            $vsql .=", ";
        }
        $control++;
    }
    $sql .= ") values (";
    $sql .= $vsql;
    $sql .= ")";
    return $sql;
}


function createUpdate($table, $values, $conditions) {
    $sql = "update $table set ";
    $control = 0;
    foreach ($values as $index => $value) {
        $sql.=" $index =:$index";
        if ($control != count($values)-1) {
            $sql.=",";
        }
        $control++;
    }
    $sql .= " ";
    $sql .= createCondition($conditions);
    return $sql;
}


function createSelect($table, $columns, $conditions) {
    $sql = "Select ";
    $control = 0;
    foreach ($columns as $index => $value) {
        $sql.=" $index";
        if ($control != count($columns)-1) {
            $sql.=",";
        }
        $control++;
    }
    $sql .= " from $table ";
    if($conditions != null){
        $sql .= createCondition($conditions);
    }
    return $sql;
}

function createCondition($conditions) {
    $where = "where ";
    $control = 0;
    foreach ($conditions as $index => $value) {
            $cond = "cond" . $control;
        if ($control == count($conditions)-1) {
            $where .="$index =:$cond";
        } else {
            $where.="$index =:$cond and ";
        }
        $control++;
    }
    return $where;
}

function execSelect($connection, $sql, $conditions) {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $connection->prepare($sql);
        $control = 0;
        foreach ($conditions as $index => $value) {
            if ($control != count($conditions)) {
                $cond = "cond" . $control;
                $stmt->bindValue(":".$cond, $value);
            }
            $control++;
        }
        $stmt->execute();
        return $stmt;
    } catch (Exception $ex) {
        echo "Error code: " . $ex->getCode() . " __ Error message: " . $ex->getMessage();
        return "Error";
    }
}

function execInsert($connection, $sql, $values) {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    try {
        $stmt = $connection->prepare($sql);
        $control = 0;
        foreach ($values as $index => $value) {
            if ($control != count($values)) {
                $stmt->bindValue(":".$index, $value);
            }
            $control++;
        }
        $stmt->execute();
        $filas = $stmt->rowCount();
        return $filas;
    } catch (Exception $ex) {
        echo "<br/>Error code: " . $ex->getCode() . " __ Error message: " . $ex->getMessage();
        return "Error";
    }
}

function execUpdate($connection, $sql, $values, $conditions) {
    $updValues = arrayVal_Cond($values, $conditions);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $connection->prepare($sql);
        $control = 0;
        foreach ($values as $index => $value) {
            if ($control != count($values)) {
                $stmt->bindValue(":".$index, $value);
            }
            $control++;
        }
        $control = 0;
        foreach ($conditions as $index => $value) {
            if ($control != count($conditions)) {
                $cond = "cond" . $control;
                $stmt->bindValue(":".$cond, $value);
            }
            $control++;
        }
        $stmt->execute();
        $filas = $stmt->rowCount();
        return $filas;
    } catch (Exception $ex) {
        echo "<br/>Error code: " . $ex->getCode() . " __ Error message: " . $ex->getMessage();
        return "Error";
    }
}

function arrayVal_Cond($values, $conditions) {
    $i = 0;
    foreach ($conditions as $index => $value) {
        $cond = "cond" . $i;
        $values[$cond] = $value;
        $i++;
    }
    return $values;
}
