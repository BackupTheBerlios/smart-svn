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
// | Author: Lorenzo Alberton <l.alberton@quipo.it>                       |
// +----------------------------------------------------------------------+
//
// $Id: ibase.php,v 1.23 2004/04/23 17:06:46 lsmith Exp $

/**
 * MDB2 FireBird/InterBase driver
 *
 * @package MDB2
 * @category Database
 * @author  Lorenzo Alberton <l.alberton@quipo.it>
 */
class MDB2_Driver_ibase extends MDB2_Driver_Common
{
    // {{{ properties
    var $escape_quotes = "'";

    var $transaction_id = 0;

    var $database_path = '';
    var $database_extension = '';

    var $query_parameters = array();
    var $query_parameter_values = array();

    // }}}
    // {{{ constructor

    /**
     * Constructor
     */
    function MDB2_Driver_ibase()
    {
        $this->MDB2_Driver_Common();
        $this->phptype  = 'ibase';
        $this->dbsyntax = 'ibase';

        $this->supported['sequences'] = true;
        $this->supported['indexes'] = true;
        $this->supported['affected_rows'] = true;
        $this->supported['summary_functions'] = true;
        $this->supported['order_by_text'] = true;
        $this->supported['transactions'] = true;
        $this->supported['current_id'] = true;
        // maybe this needs different handling for ibase and firebird?
        $this->supported['limit_queries'] = true;
        $this->supported['LOBs'] = true;
        $this->supported['replace'] = false;
        $this->supported['sub_selects'] = true;

        $this->options['database_path'] = '';
        $this->options['database_extension'] = '.gdb';
        $this->options['default_text_field_length'] = 4000;
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
        $native_msg = @ibase_errmsg();

        if (function_exists('ibase_errcode')) {
            $native_code = @ibase_errcode();
        } else {
            // memo for the interbase php module hackers: we need something similar
            // to mysql_errno() to retrieve error codes instead of this ugly hack
            if (preg_match('/^([^0-9\-]+)([0-9\-]+)\s+(.*)$/', $native_msg, $m)) {
                $native_code = (int)$m[2];
            } else {
                $native_code = null;
            }
        }
        if (is_null($error)) {
            $error = MDB2_ERROR;
            if ($native_code) {
                // try to interpret Interbase error code (that's why we need ibase_errno()
                // in the interbase module to return the real error code)
                switch ($native_errno) {
                    case -204:
                        if (is_int(strpos($m[3], 'Table unknown'))) {
                            $errno = MDB2_ERROR_NOSUCHTABLE;
                        }
                    break;
                    default:
                        static $ecode_map;
                        if (empty($ecode_map)) {
                            $ecode_map = array(
                                -104 => MDB2_ERROR_SYNTAX,
                                -150 => MDB2_ERROR_ACCESS_VIOLATION,
                                -151 => MDB2_ERROR_ACCESS_VIOLATION,
                                -155 => MDB2_ERROR_NOSUCHTABLE,
                                  88 => MDB2_ERROR_NOSUCHTABLE,
                                -157 => MDB2_ERROR_NOSUCHFIELD,
                                -158 => MDB2_ERROR_VALUE_COUNT_ON_ROW,
                                -170 => MDB2_ERROR_MISMATCH,
                                -171 => MDB2_ERROR_MISMATCH,
                                -172 => MDB2_ERROR_INVALID,
                                -204 => MDB2_ERROR_INVALID,
                                -205 => MDB2_ERROR_NOSUCHFIELD,
                                -206 => MDB2_ERROR_NOSUCHFIELD,
                                -208 => MDB2_ERROR_INVALID,
                                -219 => MDB2_ERROR_NOSUCHTABLE,
                                -297 => MDB2_ERROR_CONSTRAINT,
                                -530 => MDB2_ERROR_CONSTRAINT,
                                -551 => MDB2_ERROR_ACCESS_VIOLATION,
                                -552 => MDB2_ERROR_ACCESS_VIOLATION,
                                -607 => MDB2_ERROR_NOSUCHTABLE,
                                -803 => MDB2_ERROR_CONSTRAINT,
                                -913 => MDB2_ERROR_DEADLOCK,
                                -922 => MDB2_ERROR_NOSUCHDB,
                                -923 => MDB2_ERROR_CONNECT_FAILED,
                                -924 => MDB2_ERROR_CONNECT_FAILED,
                            );
                        }
                        if (isset($ecode_map[$native_code])) {
                            $error = $ecode_map[$native_code];
                        }
                        break;
                }
            } else {
                static $error_regexps;
                if (!isset($error_regexps)) {
                    $error_regexps = array(
                        '/[tT]able not found/' => MDB2_ERROR_NOSUCHTABLE,
                        '/[tT]able .* already exists/' => MDB2_ERROR_ALREADY_EXISTS,
                        '/validation error for column .* value "\*\*\* null/' => MDB2_ERROR_CONSTRAINT_NOT_NULL,
                        '/violation of [\w ]+ constraint/' => MDB2_ERROR_CONSTRAINT,
                        '/conversion error from string/' => MDB2_ERROR_INVALID_NUMBER,
                        '/no permission for/' => MDB2_ERROR_ACCESS_VIOLATION,
                        '/arithmetic exception, numeric overflow, or string truncation/' => MDB2_ERROR_DIVZERO,
                        '/deadlock/' => MDB2_ERROR_DEADLOCK,
                        '/attempt to store duplicate value/' => MDB2_ERROR_CONSTRAINT,
                    );
                }
                foreach ($error_regexps as $regexp => $code) {
                    if (preg_match($regexp, $native_msg, $m)) {
                        $error = $code;
                        break;
                    }
                }
            }
        }
        return array($error, $native_code, $native_msg);
    }

    // }}}
    // {{{ autoCommit()

    /**
     * Define whether database changes done on the database be automatically
     * committed. This function may also implicitly start or end a transaction.
     *
     * @param boolean $auto_commit flag that indicates whether the database
     *     changes should be committed right after executing every query
     *     statement. If this argument is 0 a transaction implicitly started.
     *     Otherwise, if a transaction is in progress it is ended by committing
     *     any database changes that were pending.
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function autoCommit($auto_commit)
    {
        $this->debug(($auto_commit ? 'On' : 'Off'), 'autoCommit');
        if ($this->auto_commit == $auto_commit) {
            return MDB2_OK;
        }
        if ($this->connection && $auto_commit
            && MDB2::isError($commit = $this->commit())
        ) {
            return $commit;
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
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function commit()
    {
        $this->debug('commit transaction', 'commit');
        if ($this->auto_commit) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'commit: transaction changes are being auto commited');
        }
        if (!@ibase_commit($this->connection)) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'commit: could not commit');
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ rollback()

    /**
     * Cancel any database changes done during a transaction that is in
     * progress. This function may only be called when auto-committing is
     * disabled, otherwise it will fail. Therefore, a new transaction is
     * implicitly started after canceling the pending changes.
     *
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function rollback()
    {
        $this->debug('rolling back transaction', 'rollback');
        if ($this->auto_commit) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'rollback: transactions can not be rolled back when changes are auto commited');
        }

        if ($this->transaction_id && !@ibase_rollback($this->connection)) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'rollback: Could not rollback a pending transaction: '.ibase_errmsg());
        }
        if (!$this->transaction_id = @ibase_trans(IBASE_COMMITTED, $this->connection)) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'rollback: Could not start a new transaction: '.ibase_errmsg());
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ getDatabaseFile()

    /**
     * Builds the string with path+dbname+extension
     *
     * @return string full database path+file
     * @access private
     */
    function _getDatabaseFile($database_name)
    {
        $this->database_path = $this->options['database_path'];
        $this->database_extension = $this->options['database_extension'];

        return $this->database_path.$database_name.$this->database_extension;
    }

    // }}}
    // {{{ _doConnect()

    /**
     * Does the grunt work of connecting to the database
     *
     * @return mixed connection resource on success, MDB2 Error Object on failure
     * @access private
     **/
    function _doConnect($database_name, $persistent = false)
    {
        $dsninfo = $this->dsn;
        $user = $dsninfo['username'];
        $pw   = $dsninfo['password'];
        $dbhost = $dsninfo['hostspec'] ?
            ($dsninfo['hostspec'].':'.$database_name) : $database_name;

        $params = array();
        $params[] = $dbhost;
        $params[] = !empty($user) ? $user : null;
        $params[] = !empty($pw) ? $pw : null;
        $params[] = isset($dsninfo['charset']) ? $dsninfo['charset'] : null;
        $params[] = isset($dsninfo['buffers']) ? $dsninfo['buffers'] : null;
        $params[] = isset($dsninfo['dialect']) ? $dsninfo['dialect'] : null;
        $params[] = isset($dsninfo['role'])    ? $dsninfo['role'] : null;

        $function = ($persistent ? 'ibase_pconnect' : 'ibase_connect');
        $connection = @call_user_func_array($function, $params);
        if ($connection > 0) {
            @ibase_timefmt("%Y-%m-%d %H:%M:%S", IBASE_TIMESTAMP);
            @ibase_timefmt("%Y-%m-%d", IBASE_DATE);
            return $connection;
        }
        if (isset($php_errormsg)) {
            $error_msg = $php_errormsg;
        } else {
            $error_msg = 'Could not connect to FireBird/InterBase server';
        }
        return $this->raiseError(MDB2_ERROR_CONNECT_FAILED, null, null, $error_msg);
    }

    // }}}
    // {{{ connect()

    /**
     * Connect to the database
     *
     * @return true on success, MDB2 Error Object on failure
     * @access public
     **/
    function connect()
    {
        $database_file = $this->_getDatabaseFile($this->database_name);

        if ($this->connection != 0) {
            if (count(array_diff($this->connected_dsn, $this->dsn)) == 0
                && $this->connected_database_name == $database_file
                && $this->opened_persistent == $this->options['persistent']
            ) {
                return MDB2_OK;
            }
            @ibase_close($this->connection);
            $this->connection = 0;
        }

        if (!PEAR::loadExtension('interbase')) {
            return $this->raiseError(MDB2_ERROR_NOT_FOUND, null, null,
                'connect: extension '.$this->phptype.' is not compiled into PHP');
        }

        if (!empty($this->database_name)) {
            $connection = $this->_doConnect($database_file, $this->options['persistent']);
            if (MDB2::isError($connection)) {
                return $connection;
            }
            $this->connection = $connection;
            $this->connected_dsn = $this->dsn;
            $this->connected_database_name = $database_file;
            $this->opened_persistent = $this->options['persistent'];

            if (!$this->auto_commit && MDB2::isError($trans_result = $this->_doQuery('BEGIN'))) {
                @ibase_close($this->connection);
                $this->connection = 0;
                return $trans_result;
            }
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ _close()
    /**
     * Close the database connection
     *
     * @return boolean
     * @access private
     **/
    function _close()
    {
        if ($this->connection != 0) {
            if (!$this->auto_commit) {
                $result = $this->_doQuery('END');
            }
            @ibase_close($this->connection);
            $this->connection = 0;
            unset($GLOBALS['_MDB2_databases'][$this->db_index]);

            if (isset($result) && MDB2::isError($result)) {
                return $result;
            }
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ _doQuery()

    /**
     * Execute a query
     * @param string $query the SQL query
     * @return mixed result identifier if query executed, else MDB2_error
     * @access private
     **/
    function _doQuery($query, $prepared_query = false)
    {
        $connection = ($this->auto_commit ? $this->connection : $this->transaction_id);
        if ($prepared_query
            && isset($this->query_parameters[$prepared_query])
            && count($this->query_parameters[$prepared_query]) > 2)
        {
            $this->query_parameters[$prepared_query][0] = $connection;
            $this->query_parameters[$prepared_query][1] = $query;
            $result = @call_user_func_array('ibase_query', $this->query_parameters[$prepared_query]);
        } else {
            //Not Prepared Query
            $result = @ibase_query($connection, $query);
            if (ibase_errmsg() == 'Query argument missed') { //ibase_errcode() only available in PHP5
                //connection lost, try again...
                $this->connect();
                //rollback the failed transaction to prevent deadlock and execute the query again
                if ($this->transaction_id) {
                    $this->rollback();
                }
                $result = @ibase_query($this->connection, $query);
            }
        }
        if (!$result) {
            return $this->raiseError($result);
        }
        return $result;
    }

    // }}}
    // {{{ _executePrepared()

    /**
     * Execute a prepared query statement.
     *
     * @param int $prepared_query argument is a handle that was returned by
     *       the function prepare()
     * @param string $query query to be executed
     * @param array $types array that contains the types of the columns in the result set
     * @param mixed $result_class string which specifies which result class to use
     * @param mixed $result_wrap_class string which specifies which class to wrap results in
     * @return mixed a result handle or MDB2_OK on success, a MDB2 error on failure
     *
     * @access private
     */
    function &_executePrepared($prepared_query, $query, $types = null,
        $result_class = false, $result_wrap_class = false)
    {
        $ismanip = MDB2::isManip($query);
        $offset = $this->row_offset;
        $limit = $this->row_limit;
        $this->row_offset = $this->row_limit = 0;
        $this->debug($query, 'query');
        $this->last_query = $query;

        $connect = $this->connect();
        if (MDB2::isError($connect)) {
            return $connect;
        }

        $result = $this->_doQuery($query, $prepared_query);
        if (!MDB2::isError($result)) {
            if ($ismanip) {
                return MDB2_OK;
            } else {
                if (!$result_class) {
                    $result_class = $this->options['result_buffering']
                        ? $this->options['buffered_result_class'] : $this->options['result_class'];
                }
                $class_name = sprintf($result_class, $this->phptype);
                $result =& new $class_name($this, $result, $offset, $limit);
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
        return $result;
    }

    // }}}
    // {{{ query()

    /**
     * Send a query to the database and return any results
     *
     * @param string $query the SQL query
     * @param array $types array that contains the types of the columns in the result set
     * @param mixed $result_class string which specifies which result class to use
     * @param mixed $result_wrap_class string which specifies which class to wrap results in
     * @return mixed a result handle or MDB2_OK on success, a MDB2 error on failure
     *
     * @access public
     */
    function &query($query, $types = null, $result_class = false, $result_wrap_class = false)
    {
        $result =& $this->_executePrepared(false, $query, $types, $result_class, $result_wrap_class);
        return $result;
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
        if (function_exists('ibase_affected_rows')) { //PHP5 only
            $affected_rows = @ibase_affected_rows($this->connection);
            if ($affected_rows === false) {
                return $this->raiseError(MDB2_ERROR_NEED_MORE_DATA);
            }
            return $affected_rows;
        }
        return parent::affectedRows();
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
     * @return mixed MDB2 Error Object or id
     * @access public
     */
    function nextID($seq_name, $ondemand = true)
    {
        $sequence_name = $this->getSequenceName($seq_name);
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $query = "SELECT GEN_ID($sequence_name, 1) as the_value FROM RDB\$DATABASE";
        $result = $this->queryOne($query);
        $this->popErrorHandling();
        if (MDB2::isError($result)) {
            if ($ondemand) {
                $this->loadModule('manager');
                // Since we are creating the sequence on demand
                // we know the first id = 1 so initialize the
                // sequence at 2
                $result = $this->manager->createSequence($seq_name, 2);
                if (MDB2::isError($result)) {
                    return $this->raiseError(MDB2_ERROR, null, null,
                        'nextID: on demand sequence could not be created');
                } else {
                    // First ID of a newly created sequence is 1
                    return 1;
                }
            }
        }
        return $result;
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
        //$sequence_name = $this->getSequenceName($seq_name);
        $sequence_name = strtoupper($this->getSequenceName($seq_name));
        $query = "SELECT RDB\$GENERATOR_ID FROM RDB\$GENERATORS WHERE RDB\$GENERATOR_NAME='$sequence_name'";
        $value = $this->queryOne($query);
        if (MDB2::isError($value)) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'currID: Unable to select from ' . $seqname) ;
        }
        if (!is_numeric($value)) {
            return $this->raiseError(MDB2_ERROR, null, null,
                'currID: could not find value in sequence table');
        }
        return $value;
    }
}

class MDB2_Result_ibase extends MDB2_Result_Common
{
    var $limits;

    // }}}
    // {{{ constructor

    /**
     * Constructor
     */
    function MDB2_Result_ibase(&$mdb, &$result, $offset, $limit)
    {
        parent::MDB2_Result_Common($mdb, $result);
        if ($offset || $limit) {
            $this->limits = array(
                'offset' => $offset,
                'count' => 0,
                'limit' => ($limit - 1),
            );
        }
    }

    // }}}
    // {{{ _skipLimitOffset()

    /**
     * Skip the first row of a result set.
     *
     * @param resource $result
     * @return mixed a result handle or MDB2_OK on success, a MDB2 error on failure
     * @access private
     */
    function _skipLimitOffset()
    {
        if (isset($this->limits) && is_array($this->limits)) {
            if ($this->rownum > $this->limits['limit']) {
                return false;
            }
            while ($this->limits['count'] < $this->limits['offset']) {
                ++$this->limits['count'];
                if (!is_array(@ibase_fetch_row($this->result))) {
                    $this->limits['count'] = $this->limits['offset'];
                    return false;
                }
            }
        }
        return true;
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
        $seek = $this->seek($rownum);
        if (MDB2::isError($seek)) {
            return $seek;
        }
        $fetchmode = is_numeric($colnum) ? MDB2_FETCHMODE_ORDERED : MDB2_FETCHMODE_ASSOC;
        $row = $this->fetchRow($fetchmode);
        if (!$row || MDB2::isError($row)) {
            return $row;
        }
        if (!array_key_exists($colnum, $row)) {
            return null;
        }
        return $row[$colnum];
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
        /*
        if ($this->result === true) {
            //query successfully executed, but without results...
            return null;
        }
        */

        if ($fetchmode == MDB2_FETCHMODE_DEFAULT) {
            $fetchmode = $this->mdb->fetchmode;
        }
        if (!$this->_skipLimitOffset()) {
            return null;
        }
        if ($fetchmode & MDB2_FETCHMODE_ASSOC) {
            $row = @ibase_fetch_assoc($this->result);
            if (is_array($row)
                && $this->mdb->options['portability'] & MDB2_PORTABILITY_LOWERCASE
            ) {
                $row = array_change_key_case($row, CASE_LOWER);
            }
        } else {
            $row = @ibase_fetch_row($this->result);
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
        if ($this->mdb->options['portability'] & MDB2_PORTABILITY_RTRIM) {
            $this->mdb->_rtrimArrayValues($row);
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
     * @return mixed associative array variable
     *      that holds the names of columns. The indexes of the array are
     *      the column names mapped to lower case and the values are the
     *      respective numbers of the columns starting from 0. Some DBMS may
     *      not return any columns when the result set does not contain any
     *      rows.
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
            $column_info = @ibase_field_info($this->result, $column);
            $columns[$column_info['name']] = $column;
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
     * @return mixed integer value with the number of columns, a MDB2 error
     *      on failure
     * @access public
     */
    function numCols()
    {
        /*
        if ($this->result === true) {
            //query successfully executed, but without results...
            return 0;
        }
        */
        if (!is_resource($this->result)) {
            return $this->mdb->raiseError('numCols(): not a valid ibase resource');
        }
        $cols = @ibase_num_fields($this->result);
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
     * Free the internal resources associated with $result.
     *
     * @return boolean true on success, false if $result is invalid
     * @access public
     */
    function free()
    {
        if (is_resource($this->result)) {
            $free = @ibase_free_result($this->result);
            if (!$free) {
                if (is_null($this->result)) {
                    return MDB2_OK;
                }
                return $this->mdb->raiseError();
            }
        }
        $this->result = null;
        return MDB2_OK;
    }
}

class MDB2_BufferedResult_ibase extends MDB2_Result_ibase
{
    var $buffer;
    var $buffer_rownum = - 1;

    // }}}
    // {{{ constructor

    /**
     * Constructor
     */
    function MDB2_BufferedResult_ibase(&$mdb, &$result, $offset, $limit)
    {
        parent::MDB2_Result_ibase($mdb, $result, $offset, $limit);
    }

    // }}}
    // {{{ _fillBuffer()

    /**
     * Fill the row buffer
     *
     * @param int $rownum   row number upto which the buffer should be filled
                            if the row number is null all rows are ready into the buffer
     * @return boolean true on success, false on failure
     * @access private
     */
    function _fillBuffer($rownum = null)
    {
        if (isset($this->buffer) && is_array($this->buffer)) {
            if (is_null($rownum)) {
                if (!end($this->buffer)) {
                    return false;
                }
            } else if (isset($this->buffer[$rownum])) {
                return (bool) $this->buffer[$rownum];
            }
        }

        if (!$this->_skipLimitOffset()) {
            return false;
        }

        $buffer = true;
        while ((is_null($rownum) || $this->buffer_rownum < $rownum)
            && (!isset($this->limits) || $this->buffer_rownum < $this->limits['limit'])
            && ($buffer = @ibase_fetch_row($this->result))
        ) {
            ++$this->buffer_rownum;
            $this->buffer[$this->buffer_rownum] = $buffer;
        }

        if (!$buffer) {
            ++$this->buffer_rownum;
            $this->buffer[$this->buffer_rownum] = false;
            return false;
        } elseif (isset($this->limits) && $this->buffer_rownum >= $this->limits['limit']) {
            ++$this->buffer_rownum;
            $this->buffer[$this->buffer_rownum] = false;
        }
        return true;
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
        if (is_null($this->result)) {
            return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                'fetchRow: resultset has already been freed');
        }
        $target_rownum = $this->rownum + 1;
        if ($fetchmode == MDB2_FETCHMODE_DEFAULT) {
            $fetchmode = $this->mdb->fetchmode;
        }
        if (!$this->_fillBuffer($target_rownum)) {
            return null;
        }
        $row = $this->buffer[$target_rownum];
        if ($fetchmode & MDB2_FETCHMODE_ASSOC) {
            $column_names = $this->getColumnNames();
            foreach ($column_names as $name => $i) {
                $column_names[$name] = $row[$i];
            }
            $row = $column_names;
        }
        if (isset($this->types)) {
            $row = $this->mdb->datatype->convertResultRow($this->types, $row);
        }
        if ($this->mdb->options['portability'] & MDB2_PORTABILITY_RTRIM) {
            $this->mdb->_rtrimArrayValues($row);
        }
        if ($this->mdb->options['portability'] & MDB2_PORTABILITY_EMPTY_TO_NULL) {
            $this->mdb->_convertEmptyArrayValuesToNull($row);
        }
        ++$this->rownum;
        return $row;
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
        if (is_null($this->result)) {
            return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                'seek: resultset has already been freed');
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
        if (is_null($this->result)) {
            return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                'valid: resultset has already been freed');
        }
        if ($this->_fillBuffer($this->rownum + 1)) {
            return true;
        }
        return false;
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
        if (is_null($this->result)) {
            return $this->mdb->raiseError(MDB2_ERROR_NEED_MORE_DATA, null, null,
                'seek: resultset has already been freed');
        }
        $this->_fillBuffer();
        return $this->buffer_rownum;
    }

    // }}}
    // {{{ free()

    /**
     * Free the internal resources associated with $result.
     *
     * @return boolean true on success, false if $result is invalid
     * @access public
     */
    function free()
    {
        $this->buffer = null;
        $this->buffer_rownum = null;
        $free = parent::free();
    }
}

?>