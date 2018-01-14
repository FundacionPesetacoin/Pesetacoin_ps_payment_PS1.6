<?php
function upgrade_module_1_1($module)
{
  // Execute module update SQL statements
  $sql_file = dirname(__FILE__).'/sql/install-1.1.sql';
  if (!$module->loadSQLFile($sql_file))
  return false;

  // All went well!
    return true;
}