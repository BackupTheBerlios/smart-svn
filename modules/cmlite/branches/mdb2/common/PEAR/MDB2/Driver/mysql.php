<?php
// vim: set et ts=4 sw=4 fdm=marker:
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1998-2004 Manuel Lemos, Tomas V.V.Cox,                 |
// | Stig. S. Bakken, Lukas Smith                                         |
// | All rights reserved.                                                 |
// +----------------------------------------------------------------------+
// | MDB2 is a merge of PEAR DB and Metabases that provides a unified DB  |
// | API as well as database abstraction for PHP applications.            |
// | This LICENSE is in the BSD license style.                            |
// |                                                                      |
// | Redistribution and use in source and binary forms, with or without   |
// | modification, are permitted provided that the following conditions   |
// | are met:                                                             |
// |                                                                      |
// | Redistributions of source code must retain the above copyright       |
// | notice, this list of conditions and the following disclaimer.        |
// |                                                                      |
// | Redistributions in binary form must reproduce the above copyright    |
// | notice, this list of conditions and the following disclaimer in the  |
// | documentation and/or other materials provided with the distribution. |
// |                                                                      |
// | Neither the name of Manuel Lemos, Tomas V.V.Cox, Stig. S. Bakken,    |
// | Lukas Smith nor the names of his contributors may be used to endorse |
// | or promote products derived from this software without specific prior|
// | written permission.                                                  |
// |                                                                      |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS    |
// | FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE      |
// | REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,          |
// | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, |
// | BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS|
// |  OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED  |
// | AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT          |
// | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY|
// | WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE          |
// | POSSIBILITY OF SUCH DAMAGE.                                          |
// +----------------------------------------------------------------------+
// | Author: Lukas Smith <smith@backendmedia.com>                         |
// +----------------------------------------------------------------------+
//
// $Id: mysql.php,v 1.13 2004/04/25 10:04:59 lsmith Exp $
//

/**
 * MDB2 MySQL driver
 *
 * @package MDB2
 * @category Database
 * @author  Lukas Smith <smith@backendmedia.com>
 */
class MDB2_Driver_mysql extends MDB2_Driver_Common
{
    // {{{ properties
    var $escape_quotes = "\\";

    var $dummy_primary_key = 'dummy_primary_key';

    // }}}
    // {{{ constructor

    /**
    * Constructor
    */
    function MDB2_Driver_mysql()
    {
        $this->MDB2_Driver_Common();
        $this->phptype = 'mysql';
        $this->dbsyntax = 'mysql';

        $this->supported['sequences'] = true;
        $this->supported['indexes'] = true;
        $this->supported['affected_rows'] = true;
        $this->supported['summary_functions'] = true;
        $this->supported['order_by_text'] = true;
        $this->supported['current_id'] = true;
        $this->supported['limit_queries'] = true;
        $this->supported['LOBs'] = true;
        $this->supported['replace'] = true;
        $this->supported['sub_selects'] = false;

        $this->options['default_table_type'] = false;
    }

    // }}}
    // {{{ errorInfo()

    /**
     * This method is used to collect information about an error
     *
     * @param integer $error
     * @return array
     * @access public
     */
    function errorInfo($error = null)
    {
        $native_code = @mysql_errno($this->connection);
        $native_msg  = @mysql_error($this->connection);
        if (is_null($error)) {
            static $ecode_map;
            if (empty($ecode_map)) {
                $ecode_map = array(
                    1004 => MDB2_ERROR_CANNOT_CREATE,
                    1005 => MDB2_ERROR_CANNOT_CREATE,
                    1006 => MDB2_ERROR_CANNOT_CREATE,
                    1007 => MDB2_ERROR_ALREADY_EXISTS,
                    1008 => MDB2_ERROR_CANNOT_DROP,
                    1022 => MDB2_ERROR_ALREADY_EXISTS,
                    1046 => MDB2_ERROR_NODBSELECTED,
                    1050 => MDB2_ERROR_ALREADY_EXISTS,
                    1051 => MDB2_ERROR_NOSUCHTABLE,
                    1054 => MDB2_ERROR_NOSUCHFIELD,
                    1062 => MDB2_ERROR_ALREADY_EXISTS,
                    1064 => MDB2_ERROR_SYNTAX,
                    1100 => MDB2_ERROR_NOT_LOCKED,
                    1136 => MDB2_ERROR_VALUE_COUNT_ON_ROW,
                    1146 => MDB2_ERROR_NOSUCHTABLE,
                    1048 => MDB2_ERROR_CONSTRAINT,
                    1213 => MDB2_ERROR_DEADLOCK,
                    1216 => MDB2_ERROR_CONSTRAINT,
                );
            }
            if (isset($ecode_map[$native_code])) {
                $error = $ecode_map[$native_code];
            } else if ($this->options['portability'] & MDB2_PORTABILITY_ERRORS) {
                if ($native_code == 1022) $error = MDB2_ERROR_CONSTRAINT;
                if ($native_code == 1048) $error = MDB2_ERROR_CONSTRAINT_NOT_NULL;
                if ($native_code == 1062) $error = MDB2_ERROR_CONSTRAINT;
            }
        }
        return array($error, $native_code, $native_msg);
    }

    // }}}
    // {{{ escape()

    /**
     * Quotes a string so it can be safely used in a query. It will quote
     * the text so it can safely be used within a query.
     *
     * @param string $text the input string to quote
     * @return string quoted string
     * @access public
     */
    function escape($text)
    {
        return @mysql_escape_string($text);
    }

    // }}}
    // {{{ quoteIdentifier()

    /**
     * Quote a string so it can be safely used as a table or column name
     *
     * Quoting style depends on which database driver is being used.
     *
     * MySQL can't handle the backtick character (<kbd>`</kbd>) in
     * table or column names.
     *
     * @param string $str  identifier name to be quoted
     *
     * @return string  quoted identifier string
     *
     * @access public
     * @internal
     */
    function quoteIdentifier($str)
    {
        return '`' . $str . '`';
    }

    // }}}
    // {{{ autoCommit()

    /**
     * Define whether database changes done on the database be automatically
     * committed. This function may also implicitly start or end a transaction.
     *
     * @param boolean $auto_commit    flag that indicates whether the database
     *                                changes should be committed right after
     *                                executing every query statement. If this
     *                                argument is 0 a transaction implicitly
     *                                started. Otherwise, if a transaction is
     *                                in progress it is ended by committing any
     *                                database changes that were pending.
     *
     * @access public
     *
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     */
    function autoCommit($auto_commit)
    {
        $this->debug(($auto_commit ? 'On' : 'Off'), 'autoCommit');
        if (!isset($this->supported['transactions'])) {
            return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
                'autoCommit: transactions are not in use');
        }
        if ($this->auto_commit == $auto_commit) {
            return MDB2_OK;
        }
        if ($this->connection) {
            if ($auto_commit) {
                $result = $this->query('COMMIT');
                if (MDB2::isError($result)) {
                    return $result;
                }
                $result = $this->query('SET AUTOCOMMIT = 1');
                if (MDB2::isError($result)) {
                    return $result;
                }
            } else {
                $result = $this->query('SET AUTOCOMMIT = 0');
                if (MDB2::isError($result)) {
                    return $result;
                }
            }
        }
        $this->auto_commit = $auto_commit;
        $this->in_transaction = !$auto_commit;
        return MDB2_OK;
    }

    // }}}
    // {{{ commit()

    /**
     * Commit the database changes done during a transaction that is in
     * progress. This function may only be called when auto-committing is
     * disabled, otherwise it will fail. Therefore, a new transaction is
     * implicitly started after committing the pending changes.
     *
     * @access public
     *
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     */
    function commit()
    {
        $this->debug('commit transaction', 'commit');
        if (!isset($this->supported['transactions'])) {
            return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
                'commit: transactions are not in use');
        }
        if ($this->auto_commit) {
            return $this->raiseError(MDB2_ERROR, null, null,
            'commit: transaction changes are being auto commited');
        }
        return $this->query('COMMIT');
    }

    // }}}
    // {{{ rollback()

    /**
     * Cancel any database changes done during a transaction that is in
     * progress. This function may only be called when auto-committing is
     * disabled, otherwise it will fail. Therefore, a new transaction is
     * implicitly started after canceling the pending changes.
     *
     * @access public
     *
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     */
    function rollback()
    {
        $this->debug('rolling back transaction', 'rollback');
        if (!isset($this->supported['transactions'])) {
            return $this->raiseError(MDB2_ERROR_UNSUPPORTED, null, null,
                'rollback: transactions are not in use');
        }
        if ($this->auto_commit) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'rollback: transactions can not be rolled back when changes are auto commited');
        }
        return $this->query('ROLLBACK');
    }

    // }}}
    // {{{ connect()

    /**
     * Connect to the database
     *
     * @return true on success, MDB2 Error Object on failure
     **/
    function connect()
    {
        if ($this->connection != 0) {
            if (count(array_diff($this->connected_dsn, $this->dsn)) == 0
                && $this->opened_persistent == $this->options['persistent']
            ) {
                return MDB2_OK;
            }
            @mysql_close($this->connection);
            $this->connection = 0;
        }

        if (!PEAR::loadExtension($this->phptype)) {
            return $this->raiseError(MDB2_ERROR_NOT_FOUND, null, null,
                'connect: extension '.$this->phptype.' is not compiled into PHP');
        }

        $function = ($this->options['persistent'] ? 'mysql_pconnect' : 'mysql_connect');

        $dsninfo = $this->dsn;
        if (isset($dsninfo['protocol']) && $dsninfo['protocol'] == 'unix') {
            $dbhost = ':' . $dsninfo['socket'];
        } else {
            $dbhost = $dsninfo['hostspec'] ? $dsninfo['hostspec'] : 'localhost';
            if (!empty($dsninfo['port'])) {
                $dbhost .= ':' . $dsninfo['port'];
            }
        }
        $user = $dsninfo['username'];
        $pw = $dsninfo['password'];

        @ini_set('track_errors', true);
        $connection = @$function($dbhost, $user, $pw, true);
        @ini_restore('track_errors');
        if ($connection <= 0) {
            return $this->raiseError(MDB2_ERROR_CONNECT_FAILED, null, null,
                $php_errormsg);
        }
        $this->connection = $connection;
        $this->connected_dsn = $this->dsn;
        $this->connected_database_name = '';
        $this->opened_persistent = $this->options['persistent'];

        if ($this->options['use_transactions']) {
            $this->supported['transactions'] = true;
            $this->options['default_table_type'] = 'BDB';
        } else {
            $this->options['default_table_type'] = '';
        }

        $default_table_type = $this->options['default_table_type'];
        if ($default_table_type) {
            switch ($this->options['default_table_type'] = strtoupper($default_table_type)) {
                case 'BERKELEYDB':
                    $this->options['default_table_type'] = 'BDB';
                case 'BDB':
                case 'INNODB':
                case 'GEMINI':
                    break;
                case 'HEAP':
                case 'ISAM':
                case 'MERGE':
                case 'MRG_MYISAM':
                case 'MYISAM':
                    if (isset($this->supported['transactions'])) {
                        $this->warnings[] = $default_table_type.
                            ' is not a transaction-safe default table type';
                    }
                    break;
                default:
                    $this->warnings[] = $default_table_type.
                        ' is not a supported default table type';
            }
        }

        if (isset($this->supported['transactions']) && !$this->auto_commit) {
            if (!@mysql_query('SET AUTOCOMMIT = 0', $this->connection)) {
                @mysql_close($this->connection);
                $this->connection = 0;
                return $this->raiseError();
            }
            $this->in_transaction = true;
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ _close()
    /**
     * all the RDBMS specific things needed close a DB connection
     *
     * @return boolean
     * @access private
     **/
    function _close()
    {
        if ($this->connection != 0) {
            if (isset($this->supported['transactions']) && !$this->auto_commit) {
                $result = $this->autoCommit(true);
            }
            @mysql_close($this->connection);
            $this->connection = 0;
            unset($GLOBALS['_MDB2_databases'][$this->db_index]);

            if (isset($result) && MDB2::isError($result)) {
                return $result;
            }
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ _modifyQuery()

    /**
     * This method is used by backends to alter queries for various
     * reasons.
     *
     * @param string $query  query to modify
     * @return the new (modified) query
     * @access private
     */
    function _modifyQuery($query)
    {
        // "DELETE FROM table" gives 0 affected rows in mysql.
        // This little hack lets you know how many rows were deleted.
        if (preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $query)) {
            $query = preg_replace(
                '/^\s*DELETE\s+FROM\s+(\S+)\s*$/',
                'DELETE FROM \1 WHERE 1=1', $query
            );
        }
        return $query;
    }

    // }}}
    // {{{ query()

    /**
     * Send a query to the database and return any results
     *
     * @param string  $query  the SQL query
     * @param mixed   $types  string or array that contains the types of the
     *                        columns in the result set
     * @param mixed $result_class string which specifies which result class to use
     * @param mixed $result_wrap_class string which specifies which class to wrap results in
     * @return mixed a result handle or MDB2_OK on success, a MDB2 error on failure
     *
     * @access public
     */
    function &query($query, $types = null, $result_class = false, $result_wrap_class = false)
    {
        $ismanip = MDB2::isManip($query);
        $offset = $this->row_offset;
        $limit = $this->row_limit;
        $this->row_offset = $this->row_limit = 0;
        if ($limit > 0) {
            if ($ismanip) {
                $query .= " LIMIT $limit";
            } else {
                $query .= " LIMIT $offset,$limit";
            }
        }
        if ($this->options['portability'] & MDB2_PORTABILITY_DELETE_COUNT) {
            $query = $this->_modifyQuery($query);
        }
        $this->last_query = $query;
        $this->debug($query, 'query');

        $connected = $this->connect();
        if (MDB2::isError($connected)) {
            return $connected;
        }

        if ($this->database_name
            && $this->database_name != $this->connected_database_name
        ) {
            if (!@mysql_select_db($this->database_name, $this->connection)) {
                $error =& $this->raiseError();
                return $error;
            }
            $this->connected_database_name = $this->database_name;
        }

        $function = $this->options['result_buffering']
            ? 'mysql_query' : 'mysql_unbuffered_query';
        if ($result = @$function($query, $this->connection)) {
            if ($ismanip) {
                return MDB2_OK;
            } else {
                if (!$result_class) {
                    $result_class = $this->options['result_buffering']
                        ? $this->options['buffered_result_class'] : $this->options['result_class'];
                }
                $class_name = sprintf($result_class, $this->phptype);
                $result =& new $class_name($this, $result);
                if ($types) {
                    $err = $result->setResultTypes($types);
                    if (MDB2::isError($err)) {
                        $result->free();
                        return $err;
                    }
                }
                if (!$result_wrap_class) {
                    $result_wrap_class = $this->options['result_wrap_class'];
                }
                if ($result_wrap_class) {
                    $result =& new $result_wrap_class($result);
                }
                return $result;
            }
        }
        $error =& $this->raiseError();
        return $error;
    }

    // }}}
    // {{{ affectedRows()

    /**
     * returns the affected rows of a query
     *
     * @return mixed MDB2 Error Object or number of rows
     * @access public
     */
    function affectedRows()
    {
        $affected_rows = @mysql_affected_rows($this->connection);
        if ($affected_rows === false) {
            return $this->raiseError(MDB2_ERROR_NEED_MORE_DATA);
        }
        return $affected_rows;
    }

    // }}}
    // {{{ subSelect()

    /**
     * simple subselect emulation for Mysql
     *
     * @access public
     *
     * @param string $query the SQL query for the subselect that may only
     *                      return a column
     * @param string $type determines type of the field
     *
     * @return string comma seperated values
     */
    function subSelect($query, $type = false)
    {
        if ($this->supported['sub_selects'] == true) {
            return $query;
        }
        $col = $this->queryCol($query, $type);
        if (!is_array($col) || count($col) == 0) {
            return 'NULL';
        }
        if ($type) {
            $this->loadModule('datatype');
            return $this->datatype->implodeArray($col, $type);
        }
        return implode(', ', $col);
    }

    // }}}
    // {{{ replace()

    /**
     * Execute a SQL REPLACE query. A REPLACE query is identical to a INSERT
     * query, except that if there is already a row in the table with the same
     * key field values, the REPLACE query just updates its values instead of
     * inserting a new row.
     *
     * The REPLACE type of query does not make part of the SQL standards. Since
     * practically only MySQL implements it natively, this type of query is
     * emulated through this method for other DBMS using standard types of
     * queries inside a transaction to assure the atomicity of the operation.
     *
     * @access public
     *
     * @param string $table name of the table on which the REPLACE query will
     *  be executed.
     * @param array $fields associative array that describes the fields and the
     *  values that will be inserted or updated in the specified table. The
     *  indexes of the array are the names of all the fields of the table. The
     *  values of the array are also associative arrays that describe the
     *  values and other properties of the table fields.
     *
     *  Here follows a list of field properties that need to be specified:
     *
     *    value:
     *          Value to be assigned to the specified field. This value may be
     *          of specified in database independent type format as this
     *          function can perform the necessary datatype conversions.
     *
     *    Default:
     *          this property is required unless the Null property
     *          is set to 1.
     *
     *    type
     *          Name of the type of the field. Currently, all types Metabase
     *          are supported except for clob and blob.
     *
     *    Default: no type conversion
     *
     *    null
     *          Boolean property that indicates that the value for this field
     *          should be set to null.
     *
     *          The default value for fields missing in INSERT queries may be
     *          specified the definition of a table. Often, the default value
     *          is already null, but since the REPLACE may be emulated using
     *          an UPDATE query, make sure that all fields of the table are
     *          listed in this function argument array.
     *
     *    Default: 0
     *
     *    key
     *          Boolean property that indicates that this field should be
     *          handled as a primary key or at least as part of the compound
     *          unique index of the table that will determine the row that will
     *          updated if it exists or inserted a new row otherwise.
     *
     *          This function will fail if no key field is specified or if the
     *          value of a key field is set to null because fields that are
     *          part of unique index they may not be null.
     *
     *    Default: 0
     *
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     */
    function replace($table, $fields)
    {
        $count = count($fields);
        for ($keys = 0, $query = $values = '',reset($fields), $colnum = 0;
            $colnum < $count;
            next($fields), $colnum++)
        {
            $name = key($fields);
            if ($colnum > 0) {
                $query .= ',';
                $values .= ',';
            }
            $query .= $name;
            if (isset($fields[$name]['null']) && $fields[$name]['null']) {
                $value = 'NULL';
            } else {
                $value = $this->quote($fields[$name]['value'], $fields[$name]['type']);
            }
            $values .= $value;
            if (isset($fields[$name]['key']) && $fields[$name]['key']) {
                if ($value === 'NULL') {
                    return $this->raiseError(MDB2_ERROR_CANNOT_REPLACE, null, null,
                        'replace: key value '.$name.' may not be NULL');
                }
                $keys++;
            }
        }
        if ($keys == 0) {
            return $this->raiseError(MDB2_ERROR_CANNOT_REPLACE, null, null,
                'replace: not specified which fields are keys');
        }
        return $this->query("REPLACE INTO $table ($query) VALUES ($values)");
    }

    // }}}
    // {{{ nextID()

    /**
     * returns the next free id of a sequence
     *
     * @param string  $seq_name name of the sequence
     * @param boolean $ondemand when true the seqence is
     *                          automatic created, if it
     *                          not exists
     *
     * @return mixed MDB2 Error Object or id
     * @access public
     */
    function nextID($seq_name, $ondemand = true)
    {
        $sequence_name = $this->getSequenceName($seq_name);
        $this->expectError(MDB2_ERROR_NOSUCHTABLE);
        $result =& $this->query("INSERT INTO $sequence_name VALUES (NULL)");
        $this->popExpect();
        if (MDB2::isError($result)) {
            if ($ondemand && $result->getCode() == MDB2_ERROR_NOSUCHTABLE) {
                $this->loadModule('manager');
                // Since we are creating the sequence on demand
                // we know the first id = 1 so initialize the
                // sequence at 2
                $result = $this->manager->createSequence($seq_name, 2);
                if (MDB2::isError($result)) {
                    return $this->raiseError(MDB2_ERROR, null, null,
                        'nextID: on demand sequence '.$seq_name.' could not be created');
                } else {
                    // First ID of a newly created sequence is 1
                    return 1;
                }
            }
            return $result;
        }
        $value = $this->queryOne('SELECT LAST_INSERT_ID()', 'integer');
        if (is_numeric($value)
            && MDB2::isError($this->query("DELETE FROM $sequence_name WHERE ".$this->options['seqname_col_name']." < $value"))
        ) {
            $this->warnings[] = 'nextID: could not delete previous sequence table values from '.$seq_name;
        }
        return $value;
    }

    // }}}
    // {{{ currID()

    /**
     * returns the current id of a sequence
     *
     * @param string  $seq_name name of the sequence
     * @return mixed MDB2 Error Object or id
     * @access public
     */
    function currID($seq_name)
    {
        $sequence_name = $this->getSequenceName($seq_name);
        return $this->queryOne("SELECT MAX(".$this->options['seqname_col_name'].") FROM $sequence_name", 'integer');
    }
}

class MDB2_Result_mysql extends MDB2_Result_Common
{
    // }}}
    // {{{ constructor

    /**
     * Constructor
     */
    function MDB2_Result_mysql(&$mdb, &$result)
    {
        parent::MDB2_Result_Common($mdb, $result);
    }

    // }}}
    // {{{ fetch()

    /**
    * fetch value from a result set
    *
    * @param int    $rownum    number of the row where the data can be found
    * @param int    $colnum    field number where the data can be found
    * @return mixed string on success, a MDB2 error on failure
    * @access public
    */
    function fetch($rownum = 0, $colnum = 0)
    {
        $value = @mysql_result($this->result, $rownum, $colnum);
        if (!$value) {
            if (is_null($this->result)) {
                return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                    'fetch: resultset has already been freed');
            }
        }
        if (isset($this->types[$colnum])) {
            $value = $this->mdb->datatype->convertResult($value, $this->types[$colnum]);
        }
        if ($this->mdb->options['portability'] & MDB2_PORTABILITY_RTRIM) {
            $value = rtrim($value);
        }
        if ($value === ''
            && $this->mdb->options['portability'] & MDB2_PORTABILITY_EMPTY_TO_NULL
        ) {
            $value = null;
        }
        return $value;
    }

    // }}}
    // {{{ fetchRow()

    /**
     * Fetch a row and insert the data into an existing array.
     *
     * @param int       $fetchmode  how the array data should be indexed
     * @return int data array on success, a MDB2 error on failure
     * @access public
     */
    function fetchRow($fetchmode = MDB2_FETCHMODE_DEFAULT)
    {
        if ($fetchmode == MDB2_FETCHMODE_DEFAULT) {
            $fetchmode = $this->mdb->fetchmode;
        }
        if ($fetchmode & MDB2_FETCHMODE_ASSOC) {
            $row = @mysql_fetch_assoc($this->result);
            if (is_array($row)
                && $this->mdb->options['portability'] & MDB2_PORTABILITY_LOWERCASE
            ) {
                $row = array_change_key_case($row, CASE_LOWER);
            }
        } else {
           $row = @mysql_fetch_row($this->result);
        }
        if (!$row) {
            if (is_null($this->result)) {
                return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                    'fetchRow: resultset has already been freed');
            }
            return null;
        }
        if (isset($this->types)) {
            $row = $this->mdb->datatype->convertResultRow($this->types, $row);
        }
        if ($this->mdb->options['portability'] & MDB2_PORTABILITY_EMPTY_TO_NULL) {
            $this->mdb->_convertEmptyArrayValuesToNull($row);
        }
        ++$this->rownum;
        return $row;
    }

    // }}}
    // {{{ getColumnNames()

    /**
     * Retrieve the names of columns returned by the DBMS in a query result.
     *
     * @return mixed                an associative array variable
     *                              that will hold the names of columns. The
     *                              indexes of the array are the column names
     *                              mapped to lower case and the values are the
     *                              respective numbers of the columns starting
     *                              from 0. Some DBMS may not return any
     *                              columns when the result set does not
     *                              contain any rows.
     *
     *                              a MDB2 error on failure
     * @access public
     */
    function getColumnNames()
    {
        $columns = array();
        $numcols = $this->numCols();
        if (MDB2::isError($numcols)) {
            return $numcols;
        }
        for ($column = 0; $column < $numcols; $column++) {
            $column_name = @mysql_field_name($this->result, $column);
            $columns[$column_name] = $column;
        }
        if ($this->mdb->options['portability'] & MDB2_PORTABILITY_LOWERCASE) {
            $columns = array_change_key_case($columns, CASE_LOWER);
        }
        return $columns;
    }

    // }}}
    // {{{ numCols()

    /**
     * Count the number of columns returned by the DBMS in a query result.
     *
     * @access public
     * @return mixed integer value with the number of columns, a MDB2 error
     *                       on failure
     */
    function numCols()
    {
        $cols = @mysql_num_fields($this->result);
        if (is_null($cols)) {
            if (is_null($this->result)) {
                return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                    'numCols: resultset has already been freed');
            }
            return $this->mdb->raiseError();
        }
        return $cols;
    }

    // }}}
    // {{{ free()

    /**
     * Free the internal resources associated with result.
     *
     * @return boolean true on success, false if result is invalid
     * @access public
     */
    function free()
    {
        $free = @mysql_free_result($this->result);
        if (!$free) {
            if (is_null($this->result)) {
                return MDB2_OK;
            }
            return $this->mdb->raiseError();
        }
        $this->result = null;
        return MDB2_OK;
    }
}

class MDB2_BufferedResult_mysql extends MDB2_Result_mysql
{
    // }}}
    // {{{ constructor

    /**
     * Constructor
     */
    function MDB2_BufferedResult_mysql(&$mdb, &$result)
    {
        parent::MDB2_Result_mysql($mdb, $result);
    }

    // }}}
    // {{{ seek()

    /**
    * seek to a specific row in a result set
    *
    * @param int    $rownum    number of the row where the data can be found
    * @return mixed MDB2_OK on success, a MDB2 error on failure
    * @access public
    */
    function seek($rownum = 0)
    {
        if (!@mysql_data_seek($this->result, $rownum)) {
            if (is_null($this->result)) {
                return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                    'seek: resultset has already been freed');
            }
            return $this->mdb->raiseError(MDB2_ERROR_INVALID, null, null,
                'seek: tried to seek to an invalid row number ('.$rownum.')');
        }
        $this->rownum = $rownum - 1;
        return MDB2_OK;
    }

    // }}}
    // {{{ valid()

    /**
    * check if the end of the result set has been reached
    *
    * @return mixed true or false on sucess, a MDB2 error on failure
    * @access public
    */
    function valid()
    {
        $numrows = $this->numRows();
        if (MDB2::isError($numrows)) {
            return $numrows;
        }
        return $this->rownum < ($numrows - 1);
    }

    // }}}
    // {{{ numRows()

    /**
    * returns the number of rows in a result object
    *
    * @return mixed MDB2 Error Object or the number of rows
    * @access public
    */
    function numRows()
    {
        $rows = @mysql_num_rows($this->result);
        if (is_null($rows)) {
            if (is_null($this->result)) {
                return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                    'numRows: resultset has already been freed');
            }
            return $this->raiseError();
        }
        return $rows;
    }
}

?>