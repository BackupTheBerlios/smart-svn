<?php
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

// $Id: oci8.php,v 1.3 2004/03/28 12:12:57 lsmith Exp $

require_once 'MDB2/Driver/Manager/Common.php';

/**
 * MDB2 oci8 driver for the management modules
 *
 * @package MDB2
 * @category Database
 * @author Lukas Smith <smith@backendmedia.com>
 */
class MDB2_Driver_Manager_oci8 extends MDB2_Driver_Manager_Common
{
    // }}}
    // {{{ constructor

    /**
     * Constructor
     */
    function MDB2_Driver_Manager_oci8($db_index)
    {
        $this->MDB2_Driver_Manager_Common($db_index);
    }

    // {{{ createDatabase()

    /**
     * create a new database
     *
     * @param object $db database object that is extended by this class
     * @param string $name name of the database that should be created
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function createDatabase($name)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        $tablespace = $db->options['default_tablespace'];
        if (!MDB2::isError($tablespace) && $tablespace) {
            $tablespace = ' DEFAULT TABLESPACE '.$tablespace;
        } else {
            $tablespace = '';
        }
        if (!($password = $db->dsn['password'])) {
            $password = $name;
        }
        $username = $db->options['database_name_prefix'].$name;
        $query = 'CREATE USER '.$username.' IDENTIFIED BY '.$password.$tablespace;
        $result = $db->standaloneQuery($query);
        if (!MDB2::isError($result)) {
            $query = 'GRANT CREATE SESSION, CREATE TABLE,UNLIMITED TABLESPACE,CREATE SEQUENCE TO '.$username;
            $result = $db->standaloneQuery($query);
            if (!MDB2::isError($result)) {
                return MDB2_OK;
            } else {
                $query = 'DROP USER '.$username.' CASCADE';
                if (MDB2::isError($result2 = $db->standaloneQuery($query))) {
                    return $db->raiseError(MDB2_ERROR, null, null,
                        'createDatabase: could not setup the database user ('.$result->getUserinfo().') and then could drop its records ('.$result2->getUserinfo().')');
                }
                return $db->raiseError(MDB2_ERROR, null, null,
                    'createDatabase: could not setup the database user ('.$result->getUserinfo().')');
            }
        }
        return $result;
    }

    // }}}
    // {{{ dropDatabase()

    /**
     * drop an existing database
     *
     * @param object $db database object that is extended by this class
     * @param string $name name of the database that should be dropped
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function dropDatabase($name)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        $username = $db->options['database_name_prefix'].$name;
        return $db->standaloneQuery('DROP USER '.$username.' CASCADE');
    }

    // }}}
    // {{{ alterTable()

    /**
     * alter an existing table
     *
     * @param object $db database object that is extended by this class
     * @param string $name name of the table that is intended to be changed.
     * @param array $changes associative array that contains the details of each type
     *                              of change that is intended to be performed. The types of
     *                              changes that are currently supported are defined as follows:
     *
     *                              name
     *
     *                                 New name for the table.
     *
     *                             added_fields
     *
     *                                 Associative array with the names of fields to be added as
     *                                  indexes of the array. The value of each entry of the array
     *                                  should be set to another associative array with the properties
     *                                  of the fields to be added. The properties of the fields should
     *                                  be the same as defined by the Metabase parser.
     *
     *                                 Additionally, there should be an entry named Declaration that
     *                                  is expected to contain the portion of the field declaration already
     *                                  in DBMS specific SQL code as it is used in the CREATE TABLE statement.
     *
     *                             removed_fields
     *
     *                                 Associative array with the names of fields to be removed as indexes
     *                                  of the array. Currently the values assigned to each entry are ignored.
     *                                  An empty array should be used for future compatibility.
     *
     *                             renamed_fields
     *
     *                                 Associative array with the names of fields to be renamed as indexes
     *                                  of the array. The value of each entry of the array should be set to
     *                                  another associative array with the entry named name with the new
     *                                  field name and the entry named Declaration that is expected to contain
     *                                  the portion of the field declaration already in DBMS specific SQL code
     *                                  as it is used in the CREATE TABLE statement.
     *
     *                             changed_fields
     *
     *                                 Associative array with the names of the fields to be changed as indexes
     *                                  of the array. Keep in mind that if it is intended to change either the
     *                                  name of a field and any other properties, the changed_fields array entries
     *                                  should have the new names of the fields as array indexes.
     *
     *                                 The value of each entry of the array should be set to another associative
     *                                  array with the properties of the fields to that are meant to be changed as
     *                                  array entries. These entries should be assigned to the new values of the
     *                                  respective properties. The properties of the fields should be the same
     *                                  as defined by the Metabase parser.
     *
     *                                 If the default property is meant to be added, removed or changed, there
     *                                  should also be an entry with index ChangedDefault assigned to 1. Similarly,
     *                                  if the notnull constraint is to be added or removed, there should also be
     *                                  an entry with index ChangedNotNull assigned to 1.
     *
     *                                 Additionally, there should be an entry named Declaration that is expected
     *                                  to contain the portion of the field changed declaration already in DBMS
     *                                  specific SQL code as it is used in the CREATE TABLE statement.
     *                             Example
     *                                 array(
     *                                     'name' => 'userlist',
     *                                     'added_fields' => array(
     *                                         'quota' => array(
     *                                             'type' => 'integer',
     *                                             'unsigned' => 1
     *                                             'declaration' => 'quota INT'
     *                                         )
     *                                     ),
     *                                     'removed_fields' => array(
     *                                         'file_limit' => array(),
     *                                         'time_limit' => array()
     *                                         ),
     *                                     'changed_fields' => array(
     *                                         'gender' => array(
     *                                             'default' => 'M',
     *                                             'change_default' => 1,
     *                                             'declaration' => "gender CHAR(1) DEFAULT 'M'"
     *                                         )
     *                                     ),
     *                                     'renamed_fields' => array(
     *                                         'sex' => array(
     *                                             'name' => 'gender',
     *                                             'declaration' => "gender CHAR(1) DEFAULT 'M'"
     *                                         )
     *                                     )
     *                                 )
     * @param boolean $check indicates whether the function should just check if the DBMS driver
     *                              can perform the requested table alterations if the value is true or
     *                              actually perform them otherwise.
     * @access public
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     */
    function alterTable($name, $changes, $check)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        if ($check) {
            for ($change = 0, reset($changes);
                $change < count($changes);
                next($changes), $change++)
            {
                switch (key($changes)) {
                    case 'added_fields':
                    case 'removed_fields':
                    case 'changed_fields':
                    case 'name':
                        break;
                    case 'renamed_fields':
                    default:
                        return $db->raiseError(MDB2_ERROR, null, null,
                            'alterTable: change type "'.key($changes).'" not yet supported');
                }
            }
            return MDB2_OK;
        }
        if (isset($changes['removed_fields'])) {
            $query = ' DROP (';
            $fields = $changes['removed_fields'];
            for ($field = 0, reset($fields);
                $field < count($fields);
                next($fields), $field++)
            {
                if ($field > 0) {
                    $query .= ', ';
                }
                $query .= key($fields);
            }
            $query .= ')';
            if (MDB2::isError($result = $db->query("ALTER TABLE $name $query"))) {
                return $result;
            }
            $query = '';
        }
        $query = (isset($changes['name']) ? 'RENAME TO '.$changes['name'] : '');
        if (isset($changes['added_fields'])) {
            $fields = $changes['added_fields'];
            for ($field = 0, reset($fields);
                $field < count($fields);
                next($fields), $field++)
            {
                $query .= ' ADD ('.$fields[key($fields)]['declaration'].')';
            }
        }
        if (isset($changes['changed_fields'])) {
            $fields = $changes['changed_fields'];
            for ($field = 0, reset($fields);
                $field < count($fields);
                next($fields), $field++)
            {
                $current_name = key($fields);
                if (isset($renamed_fields[$current_name])) {
                    $field_name = $renamed_fields[$current_name];
                    unset($renamed_fields[$current_name]);
                } else {
                    $field_name = $current_name;
                }
                $change = '';
                $change_type = $change_default = false;
                if (isset($fields[$current_name]['type'])) {
                    $change_type = $change_default = true;
                }
                if (isset($fields[$current_name]['length'])) {
                    $change_type = true;
                }
                if (isset($fields[$current_name]['changed_default'])) {
                    $change_default = true;
                }
                if ($change_type) {
                    $db->loadModule('datatype');
                    $change .= ' '.$db->datatype->getTypeDeclaration($fields[$current_name]['definition']);
                }
                if ($change_default) {
                    $default = (isset($fields[$current_name]['definition']['default']) ? $fields[$current_name]['definition']['default'] : null);
                    $change .= ' DEFAULT '.$db->quote($default, $fields[$current_name]['definition']['type']);
                }
                if (isset($fields[$current_name]['changed_not_null'])) {
                    $change .= (isset($fields[$current_name]['notnull']) ? ' NOT' : '').' NULL';
                }
                if (strcmp($change, '')) {
                    $query .= " MODIFY ($field_name$change)";
                }
            }
        }
        if ($query != '' &&
            MDB2::isError($result = $db->query("ALTER TABLE $name $query"))
        ) {
            return $result;
        }
        return MDB2_OK;
    }

    // }}}
    // {{{ listDatabases()

    /**
     * list all databases
     *
     * @return mixed data array on success, a MDB2 error on failure
     * @access public
     */
    function listDatabases()
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        "SELECT SUBSTR(table_name, 4) FROM user_tables WHERE table_name = 'MDB2%'";
        $result = $db->query($query);
        if (MDB2::isError($result)) {
            return $result;
        }
        $databases = $result->fetchCol();
        $result->free();
        return $databases;
    }

    // }}}
    // {{{ listTables()

    /**
     * list all tables in the current database
     *
     * @return mixed data array on success, a MDB error on failure
     * @access public
     **/
    function listTables(&$db)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        $query = 'SELECT table_name FROM sys.user_tables';
        return($db->queryCol($sql));
    }

    // }}}
    // {{{ listTableFields()

    /**
     * list all fields in a tables in the current database
     *
     * @param string $table name of table that should be used in method
     * @return mixed data array on success, a MDB error on failure
     * @access public
     */
    function listTableFields(&$db, $table)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        $table = strtoupper($table);
        $query = "SELECT column_name FROM user_tab_columns WHERE table_name='$table' ORDER BY column_id";
        $columns = $db->queryCol($query);
        if (MDB::isError($result)) {
            return($result);
        }
        if ($db->options['optimize'] == 'portability') {
            $columns = array_flip($columns);
            $columns = array_change_key_case($columns, CASE_LOWER);
            $columns = array_flip($columns);
        }
        return($columns);
    }

    // }}}
    // {{{ createSequence()

    /**
     * create sequence
     *
     * @param object $db database object that is extended by this class
     * @param string $seq_name name of the sequence to be created
     * @param string $start start value of the sequence; default is 1
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function createSequence($seq_name, $start = 1)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        $sequence_name = $db->getSequenceName($seq_name);
        return $db->query("CREATE SEQUENCE $sequence_name START WITH $start INCREMENT BY 1".
            ($start < 1 ? " MINVALUE $start" : ''));
    }

    // }}}
    // {{{ dropSequence()

    /**
     * drop existing sequence
     *
     * @param object $db database object that is extended by this class
     * @param string $seq_name name of the sequence to be dropped
     * @return mixed MDB2_OK on success, a MDB2 error on failure
     * @access public
     */
    function dropSequence($seq_name)
    {
        $db =& $GLOBALS['_MDB2_databases'][$this->db_index];
        $sequence_name = $db->getSequenceName($seq_name);
        return $db->query("DROP SEQUENCE $sequence_name");
    }
}
?>