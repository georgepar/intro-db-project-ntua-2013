<?php
class SQLBuilder {
	private $_sqlQuery, $_params, $_operators, $_andor;

	public function __construct() {
		$this -> _sqlQuery = '';
		$this -> _params = array();
		$this -> _operators = array('=', '<>', '>', '>=', '<=', 'BETWEEEN', 'IN', 'LIKE');
		$this -> _andor = array('AND', 'OR');
	}

	/**
	 * Error message generating function
	 * echos the error message passed to it
	 * TODO better error messages
	 * @return boolean = false
	 */
	private function error($err) {
		echo $err;
		return false;
	}

	/**
	 * Checks for empty query and calls error
	 */
	private function errorEmpty($err) {
		if (empty($this -> _sqlQuery)) {
			$this -> error($err);
		}
	}

	/**
	 * Returns the query to be executed and clears the old data
	 * @return array(sql => string, parameters => array(string))
	 */
	public function buildQuery() {
		$res = array("sql" => $this -> _sqlQuery, "parameters" => $this -> _params);
		$this -> _sqlQuery = '';
		$this -> _params = array();
		return $res;
	}

	/**
	 * builds a SELECT FROM query
	 * the WHERE part needs to be appended
	 * @param string $table
	 * 	the table name
	 * @param boolean $distinct
	 *  use keyword DISTINCT
	 * @param array(string) $fields
	 *  select specific columns. It defaults to *
	 * @param array(string) $join
	 *  use these tables for join
	 * @param boolean $nested
	 *  declare if this is part of a subquery
	 * @return SQLBuilder
	 */
	public function select($table, $distinct = false, $fields = array(), $join = array(), $nested = false) {
		$fieldsToString = '*';
		if (!empty($fields)) {
			$fieldsToString = implode(', ', $fields);
		}

		$joinToString = '`' . $table . '`';
		$joinToString .= (!empty($join)) ? ', `' . implode('`, `', $join) : '';

		$dis = $distinct ? 'DISTINCT' : '';

		$prepend = !$nested ? '' : '(';
		$this -> _sqlQuery .= "{$prepend} SELECT {$dis} {$fieldsToString} FROM  {$joinToString} ";
		return $this;
	}

	/**
	 * builds a DELETE FROM query
	 * the WHERE part needs to be appended
	 * @param string $table
	 *  the table you want to delete from
	 * @return SQLBuilder
	 */
	public function delete($table) {
		if (!empty($this -> _sqlQuery)) {
			$this -> error("Another Query in progress. Delete cancelled.");
		}

		$this -> _sqlQuery = "DELETE FROM `{$table}` ";
		return $this;
	}

	/**
	 * builds an INSERT INTO query
	 * the WHERE part needs to be appended
	 * @param string $table
	 *  the table you want to insert into
	 * @param array(string) $fields
	 *  the columns you want to specify
	 * @param array(string) $values
	 *  the values of these columns
	 * @return SQLBuilder
	 */
	public function insert($table, $fields, $values) {
		if (!empty($this -> _sqlQuery)) {
			$this -> error("Another Query in progress, Insert cancelled.");
		}

		$fieldsToString = implode('`, `', $fields);
		$this -> _sqlQuery = "INSERT INTO {$table} (`{$fieldsToString}`) ";

		$vals = substr(str_repeat('?, ', count($values)), 0, -2);
		$this -> _sqlQuery .= "VALUES ({$vals})";
		$this -> _params = $values;

		return $this;
	}

	/**
	 * builds an UPDATE query
	 * the WHERE part needs to be appended
	 * @param string $table
	 *  the table you want to update
	 * @param array(string) $fields
	 * 	the fields you want to update
	 * @param array(string) $values
	 *  the values of these fields
	 * @return SQLBuilder
	 */
	public function update($table, $fields, $values) {
		if (!empty($this -> _sqlQuery)) {
			$this -> error("Another Query in progress, Update cancelled.");
		}
		$N = count($fields);
		if ($N != count($values)) {
			$this -> error("Fields to values correspondence must be one to one.");
		}

		array_walk($fields, function(&$item) {$item .= '=?';
		});
		$fieldsToString = implode(', ', $fields);

		$this -> _sqlQuery = "UPDATE `{$table}` SET {$fieldsToString} ";
		$this -> _params = $values;

		return $this;
	}

	// 		public function where($condition = '', $params = array()) {
	// 			$this->errorEmpty("This function must be appended to already existing query");

	// 			$this->_sqlQuery .= "WHERE ";
	// 			if(!empty($condition)) {
	// 				$this->_sqlQuery .= $condition . " ";
	// 			}

	// 			if(!empty($params)) {
	// 				$this->_params = array_merge($this->_params, $params);
	// 			}

	// 			return $this;
	//  		}

	/**
	 * Builds the WHERE part of a query
	 * @param array(array(string)) $andConditions
	 * 	the conditions you want to connect with the AND logical operator
	 *  or a single condition
	 *  the format is array(array($field, $operator, $value))
	 * @param array(array(string)) $orConditions
	 * 	the conditions you want to connect with the OR logical operator
	 * 	the format is array(array($field, $operator, $value))
	 * @param string $append
	 *  an appended string takes the values
	 *  '(' for terminating a subquery | 'AND|OR {$operator}' for appending a subquery
	 * @return SQLBuilder
	 */
	public function where($andConditions, $orConditions = array(), $append = '') {
		$this -> errorEmpty("This function must be appended to already existing query");
		$apString = '';
		if(empty($andConditions)) {
			return $this;
		}

		if (!empty($append)) {
			if ($append === ')') {
				$apString = ')';
			} else {
				$terms = explode(" ", $sappend);
				if (count($terms) === 3 && in_array($terms[1], $this -> _andor) && in_array($terms[2], $this -> _operators)) {
					$apString = $append;
				} else {
					$this -> error("Invalid append string.");
				}
			}
		}

		$andConditionsString = $this -> parseConditions("AND", $andConditions);
		$orConditionsString = $this -> parseConditions("OR", $orConditions);
		$orConditionsString['conditions'] = empty($orConditionsString['conditions']) ? '' : 'OR ' . $orConditionsString;

		$this -> _sqlQuery .= "WHERE {$andConditionsString['conditions']} {$orConditionsString['conditions']} {$append}";

		$this -> _params = (empty($orConditionsString['parameters'])) ?
		 array_merge($this -> _params, $andConditionsString['parameters'], $orConditionsString['parameters']) : 
		 array_merge($this -> _params, $andConditionsString['parameters']);

		return $this;
	}

	/**
	 * Helper method to concatenate a set of conditions in the form
	 * array(array($field, $operator, $value))
	 * @param string $glue
	 * 	'AND' for logical conjuction | 'OR' for logical disjunction
	 * @param array(array(string)) $conditions
	 * @return array("conditions" => string, "parameters" array(string))
	 */
	private function parseConditions($glue, $conditions) {
		$stringContainer = '';
		$values = array();
		$i = 0;
		$N = count($conditions);

		if (empty($conditions)) {
			return array("conditions" => $stringContainer, "parameters" => $values);
		}
		foreach ($conditions as $condition) {
			$field = $condition[0];
			$operator = $condition[1];
			$value = $condition[2];
			if (!in_array($operator, $this -> _operators)) {
				$this -> error("Invalid operator.");
			}
			$stringContainer .= "{$field} {$operator} ? ";
			array_push($values, $value);
			if ($i++ < $N-1) {
				$stringContainer .= "{$glue} ";
			}
		}
		return array("conditions" => $stringContainer, "parameters" => $values);
	}

	public function orderBy($columns, $nested = false) {
		$this -> errorEmpty("This function must be appended to already existing query");

		$append = !$nested ? '' : ')';
		$columnsToString = implode(', ', $columns);
		$this -> _sqlQuery .= "ORDER BY {$columnsToString} {$append} ";
		return $this;
	}

	public function groupBy($columns, $nested = false) {
		$this -> errorEmpty("This function must be appended to already existing query");

		$append = !$nested ? '' : ')';
		$columnsToString = implode(', ', $columns);
		$this -> _sqlQuery .= "GROUP BY {$columnsToString} {$append} ";
		return $this;
	}

	public function having($aggregate, $column, $operator, $value, $nested = false) {
		$this -> errorEmpty("This function must be appended to already existing query");

		$append = !$nested ? '' : ')';
		$this -> _sqlQuery .= "HAVING {$aggregate}({$column}) {$operator} {$value} {$append} ";
		return $this;
	}

	public function limit($num, $nested = false) {
		$this -> errorEmpty("This function must be appended to already existing query");

		$append = !$nested ? '' : ')';
		$this -> _sqlQuery .= "LIMIT {$num} {$append} ";
	}

	/**
	 * function to append any missing string to a query
	 * if the string is a condition one should write
	 * appendToQuery("$field $operator ?", array($value))
	 * TODO find workaround and remove
	 * @param unknown $string
	 * @return SQLBuilder
	 */
	public function appendToQuery($string, $params = array()) {
		$this -> _sqlQuery .= $string;
		$this -> params = array_map($this -> _params, $params);
		return $this;
	}

}
?>