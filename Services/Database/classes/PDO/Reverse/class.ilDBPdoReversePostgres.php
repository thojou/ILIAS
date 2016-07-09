<?php
require_once('class.ilDBPdoReverse.php');

/**
 * Class ilDBPdoReverse
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilDBPdoReversePostgres extends ilDBPdoReverse {

	// {{{ getTableFieldDefinition()

	/**
	 * Get the structure of a field into an array
	 *
	 * @param string $table      name of table that should be used in method
	 * @param string $field_name name of field that should be used in method
	 * @return mixed data array on success, a MDB2 error on failure
	 * @access public
	 */
	public function getTableFieldDefinition($table, $field_name) {
		/**
		 * @var $db     ilDBPdoPostgreSQL
		 * @var $result ilDBPdoReversePostgres
		 */
		$db = $this->db_instance;
		$result = $this->db_instance->loadModule(ilDBConstants::MODULE_REVERSE);

		$query = "SELECT a.attname AS name,
                         t.typname AS type,
                         CASE a.attlen
                           WHEN -1 THEN
	                         CASE t.typname
	                           WHEN 'numeric' THEN (a.atttypmod / 65536)
	                           WHEN 'decimal' THEN (a.atttypmod / 65536)
	                           WHEN 'money'   THEN (a.atttypmod / 65536)
	                           ELSE CASE a.atttypmod
                                 WHEN -1 THEN NULL
	                             ELSE a.atttypmod - 4
	                           END
                             END
	                       ELSE a.attlen
                         END AS length,
	                     CASE t.typname
	                       WHEN 'numeric' THEN (a.atttypmod % 65536) - 4
	                       WHEN 'decimal' THEN (a.atttypmod % 65536) - 4
	                       WHEN 'money'   THEN (a.atttypmod % 65536) - 4
	                       ELSE 0
                         END AS scale,
                         a.attnotnull,
                         a.atttypmod,
                         a.atthasdef,
                         (SELECT substring(pg_get_expr(d.adbin, d.adrelid) for 128)
                            FROM pg_attrdef d
                           WHERE d.adrelid = a.attrelid
                             AND d.adnum = a.attnum
                             AND a.atthasdef
                         ) as default
                    FROM pg_attribute a,
                         pg_class c,
                         pg_type t
                   WHERE c.relname = " . $db->quote($table, 'text') . "
                     AND a.atttypid = t.oid
                     AND c.oid = a.attrelid
                     AND NOT a.attisdropped
                     AND a.attnum > 0
                     AND a.attname = " . $db->quote($field_name, 'text') . "
                ORDER BY a.attnum";
		$column = $db->queryRow($query, null, MDB2_FETCHMODE_ASSOC);

		if (empty($column)) {
			throw new ilDatabaseException('it was not specified an existing table column');
		}

		$column = array_change_key_case($column, CASE_LOWER);
		$mapped_datatype = $db->getFieldDefinition()->mapNativeDatatype($column);

		list($types, $length, $unsigned, $fixed) = $mapped_datatype;
		$notnull = false;
		if (!empty($column['attnotnull']) && $column['attnotnull'] == 't') {
			$notnull = true;
		}
		$default = null;
		if ($column['atthasdef'] === 't'
		    && !preg_match("/nextval\('([^']+)'/", $column['default'])
		) {
			$default = $column['default'];#substr($column['adsrc'], 1, -1);
			if (is_null($default) && $notnull) {
				$default = '';
			}
		}
		$autoincrement = false;
		if (preg_match("/nextval\('([^']+)'/", $column['default'], $nextvals)) {
			$autoincrement = true;
		}
		$definition[0] = array( 'notnull' => $notnull, 'nativetype' => $column['type'] );
		if (!is_null($length)) {
			$definition[0]['length'] = $length;
		}
		if (!is_null($unsigned)) {
			$definition[0]['unsigned'] = $unsigned;
		}
		if (!is_null($fixed)) {
			$definition[0]['fixed'] = $fixed;
		}
		if ($default !== false) {
			$definition[0]['default'] = $default;
		}
		if ($autoincrement !== false) {
			$definition[0]['autoincrement'] = $autoincrement;
		}
		foreach ($types as $key => $type) {
			$definition[$key] = $definition[0];
			if ($type == 'clob' || $type == 'blob') {
				unset($definition[$key]['default']);
			}
			$definition[$key]['type'] = $type;
			$definition[$key]['mdb2type'] = $type;
		}

		return $definition;
	}


	/**
	 * @param $table
	 * @param $index_name
	 * @return array
	 * @throws \ilDatabaseException
	 */
	public function getTableIndexDefinition($table, $index_name) {
		$db = $this->db_instance;
		$manager = $db->loadModule(ilDBConstants::MODULE_MANAGER);
		/**
		 * @var $manager ilDBPdoManagerPostgres
		 */

		$query = 'SELECT relname, indkey FROM pg_index, pg_class';
		$query .= ' WHERE pg_class.oid = pg_index.indexrelid';
		$query .= " AND indisunique != 't' AND indisprimary != 't'";
		$query .= ' AND pg_class.relname = %s';
		$index_name_mdb2 = $db->getIndexName($index_name);
		$failed = false;
		try {
			$row = $db->queryRow(sprintf($query, $db->quote($index_name_mdb2, 'text')), null, ilDBConstants::FETCHMODE_DEFAULT);
		} catch (Exception $e) {
			$failed = true;
		}
		if ($failed || empty($row)) {
			$row = $db->queryRow(sprintf($query, $db->quote($index_name, 'text')), null, ilDBConstants::FETCHMODE_DEFAULT);
		}

		if (empty($row)) {
			throw new ilDatabaseException('it was not specified an existing table index');
		}

		$row = array_change_key_case($row, CASE_LOWER);

		$columns = $manager->listTableFields($table);

		$definition = array();

		$index_column_numbers = explode(' ', $row['indkey']);

		$colpos = 1;
		foreach ($index_column_numbers as $number) {
			$definition['fields'][$columns[($number - 1)]] = array(
				'position' => $colpos ++,
				'sorting'  => 'ascending',
			);
		}

		return $definition;
	}

	// }}}
	// {{{ getTableConstraintDefinition()
	/**
	 * Get the structure of a constraint into an array
	 *
	 * @param string $table           name of table that should be used in method
	 * @param string $constraint_name name of constraint that should be used in method
	 * @return mixed data array on success, a MDB2 error on failure
	 * @access public
	 */
	function getTableConstraintDefinition($table, $constraint_name) {
		$db = $this->db_instance;

		$query = 'SELECT relname, indisunique, indisprimary, indkey FROM pg_index, pg_class';
		$query .= ' WHERE pg_class.oid = pg_index.indexrelid';
		$query .= " AND (indisunique = 't' OR indisprimary = 't')";
		$query .= ' AND pg_class.relname = %s';
		$constraint_name_mdb2 = $db->getIndexName($constraint_name);
		try {
			$row = $db->queryRow(sprintf($query, $db->quote($constraint_name_mdb2, 'text')), null, ilDBConstants::FETCHMODE_ASSOC);
		} catch (Exception $e) {
		}

		if ($e instanceof PDOException || empty($row)) {
			// fallback to the given $index_name, without transformation
			$row = $db->queryRow(sprintf($query, $db->quote($constraint_name, 'text')), null, ilDBConstants::FETCHMODE_ASSOC);
		}

		if (empty($row)) {
			throw new ilDatabaseException($constraint_name . ' is not an existing table constraint');
		}

		$row = array_change_key_case($row, CASE_LOWER);
		$columns = $db->loadModule(ilDBConstants::MODULE_MANAGER)->listTableFields($table);

		$definition = array();
		if ($row['indisprimary'] == 't') {
			$definition['primary'] = true;
		} elseif ($row['indisunique'] == 't') {
			$definition['unique'] = true;
		}

		$index_column_numbers = explode(' ', $row['indkey']);

		$colpos = 1;
		foreach ($index_column_numbers as $number) {
			$definition['fields'][$columns[($number - 1)]] = array(
				'position' => $colpos ++,
				'sorting'  => 'ascending',
			);
		}

		return $definition;
	}

	// }}}
	// {{{ getTriggerDefinition()

	/**
	 * Get the structure of a trigger into an array
	 *
	 * EXPERIMENTAL
	 *
	 * WARNING: this function is experimental and may change the returned value
	 * at any time until labelled as non-experimental
	 *
	 * @param string $trigger name of trigger that should be used in method
	 * @return mixed data array on success, a MDB2 error on failure
	 * @access public
	 *
	 * @TODO   : add support for plsql functions and functions with args
	 */
	function getTriggerDefinition($trigger) {
		$db = $this->db_instance;

		$query = "SELECT trg.tgname AS trigger_name,
                         tbl.relname AS table_name,
                         CASE
                            WHEN p.proname IS NOT NULL THEN 'EXECUTE PROCEDURE ' || p.proname || '();'
                            ELSE ''
                         END AS trigger_body,
                         CASE trg.tgtype & cast(2 as int2)
                            WHEN 0 THEN 'AFTER'
                            ELSE 'BEFORE'
                         END AS trigger_type,
                         CASE trg.tgtype & cast(28 as int2)
                            WHEN 16 THEN 'UPDATE'
                            WHEN 8 THEN 'DELETE'
                            WHEN 4 THEN 'INSERT'
                            WHEN 20 THEN 'INSERT, UPDATE'
                            WHEN 28 THEN 'INSERT, UPDATE, DELETE'
                            WHEN 24 THEN 'UPDATE, DELETE'
                            WHEN 12 THEN 'INSERT, DELETE'
                         END AS trigger_event,
                         trg.tgenabled AS trigger_enabled,
                         obj_description(trg.oid, 'pg_trigger') AS trigger_comment
                    FROM pg_trigger trg,
                         pg_class tbl,
                         pg_proc p
                   WHERE trg.tgrelid = tbl.oid
                     AND trg.tgfoid = p.oid
                     AND trg.tgname = " . $db->quote($trigger, 'text');
		$types = array(
			'trigger_name'    => 'text',
			'table_name'      => 'text',
			'trigger_body'    => 'text',
			'trigger_type'    => 'text',
			'trigger_event'   => 'text',
			'trigger_comment' => 'text',
			'trigger_enabled' => 'boolean',
		);

		return $db->queryRow($query, $types, MDB2_FETCHMODE_ASSOC);
	}

	// }}}
	// {{{ tableInfo()

	/**
	 * Returns information about a table or a result set
	 *
	 * NOTE: only supports 'table' and 'flags' if <var>$result</var>
	 * is a table name.
	 *
	 * @param object|string $result    MDB2_result object from a query or a
	 *                                 string containing the name of a table.
	 *                                 While this also accepts a query result
	 *                                 resource identifier, this behavior is
	 *                                 deprecated.
	 * @param int $mode                a valid tableInfo mode
	 *
	 * @return array  an associative array with the information requested.
	 *                 A MDB2_Error object on failure.
	 *
	 * @see MDB2_Driver_Common::tableInfo()
	 */
	function tableInfo($result, $mode = null) {
		if (is_string($result)) {
			return parent::tableInfo($result, $mode);
		}

		$db = $this->db_instance;

		$resource = MDB2::isResultCommon($result) ? $result->getResource() : $result;
		if (!is_resource($resource)) {
			return $db->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null, 'Could not generate result resource', __FUNCTION__);
		}

		if ($db->options['portability'] & MDB2_PORTABILITY_FIX_CASE) {
			if ($db->options['field_case'] == CASE_LOWER) {
				$case_func = 'strtolower';
			} else {
				$case_func = 'strtoupper';
			}
		} else {
			$case_func = 'strval';
		}

		$count = @pg_num_fields($resource);
		$res = array();

		if ($mode) {
			$res['num_fields'] = $count;
		}

		$db->loadModule('Datatype', null, true);
		for ($i = 0; $i < $count; $i ++) {
			$res[$i] = array(
				'table'  => function_exists('pg_field_table') ? @pg_field_table($resource, $i) : '',
				'name'   => $case_func(@pg_field_name($resource, $i)),
				'type'   => @pg_field_type($resource, $i),
				'length' => @pg_field_size($resource, $i),
				'flags'  => '',
			);
			$mdb2type_info = $db->datatype->mapNativeDatatype($res[$i]);
			if (PEAR::isError($mdb2type_info)) {
				return $mdb2type_info;
			}
			$res[$i]['mdb2type'] = $mdb2type_info[0][0];
			if ($mode & MDB2_TABLEINFO_ORDER) {
				$res['order'][$res[$i]['name']] = $i;
			}
			if ($mode & MDB2_TABLEINFO_ORDERTABLE) {
				$res['ordertable'][$res[$i]['table']][$res[$i]['name']] = $i;
			}
		}

		return $res;
	}
}
