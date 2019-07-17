<?php

class Database extends PDO{

	/**
	*@param String $DBTYPE database type
	*@param String $DBHOST host name
	*@param String $DBNAME database name
	*@param String $DBUSER username with db access
	*@param String $DBPASS password to the username
	**/
	function __construct($DBTYPE,$DBHOST,$DBNAME,$DBUSER,$DBPASS){
		parent::__construct($DBTYPE.':host='.$DBHOST.';dbname='.$DBNAME, $DBUSER, $DBPASS);
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	}

	/**
	*@param String $sql Sql fetch query
	*@param String $array Parameters to bind to sql
	*@param PDO CONSTANT $fetchMode Set PDO fetch mode
	**/

	public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC){
		$sth = $this->prepare($sql);
		foreach ($array as $key => $value) {
			$sth->bindValue($key, $value);
		}
		$sth->execute();
		return $sth->fetchAll($fetchMode);
	}
	/**
	*@param String $table A name of the table to insertdata
	*@param String $data An associate array
	**/

	public function insert($table, $data){
		$fieldNames = implode('`,`', array_keys($data));
		$fieldValues = ':'.implode(', :', array_keys($data));
		$sth = $this->prepare("INSERT INTO ".$table."(`".$fieldNames."`) VALUES (".$fieldValues.")
			");

		foreach ($data as $key => $value) {
			$sth->bindValue($key, $value);
		}

		return $sth->execute();

	}

	/**
	*@param String $table A name of the table to insertdata
	*@param String $data An associate array
	*@param String $where The where query part of Update query
	**/

	public function update($table, $data, $where){

		$fieldDetails = null;
		foreach ($data as $key => $value) {
			$fieldDetails .= "`$key` = :$key,";
		}

		$fieldDetails = rtrim($fieldDetails, ',');

		$fieldNames = implode('`,`', array_keys($data));
		$fieldValues = ':'.implode(', :', array_keys($data));
		$sth = $this->prepare("UPDATE ".$table." SET ".$fieldDetails." WHERE ".$where);

		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}

		return $sth->execute();
	}

	/**
	* delete
	*@param String $table A name of the table to *delete data
	*@param String $where
	*@param Integer $limit
	*@return Integer 
	**/
	public function delete($table, $where, $limit = 1){

		return $this->exec("DELETE FROM ".$table." WHERE ".$where." LIMIT ".$limit);

	}
}