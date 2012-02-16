<?php
error_reporting(E_ALL | E_STRICT);
ini_set('log_errors', 1);
set_time_limit(1);
ini_set('max_execution_time', '1');

require_once 'trait.getset.php';
require_once 'trait.singleton.php';

class Db {
    public function who() {
        echo __CLASS__, ' (', get_class($this), ')', PHP_EOL;
        return $this;
    }
}

interface iExportSchema {
    public function ExportSchema();
}

trait tExportSchema {
    protected $schema;
    function ExportSchema()
    {
        printf('export for %s: %s%s', __CLASS__, $this->getSchema(), PHP_EOL);
    }
    public function traitWho() {
        echo __CLASS__, ' (', get_class($this), ')', PHP_EOL;
        return $this;
    }
}

class Db_A
    extends Db
    implements iExportSchema
{
    use Singleton;
    use tExportSchema;
    use GetSet;
}

class Db_B extends Db implements iExportSchema {
    use Singleton; use tExportSchema; use GetSet;
}

echo PHP_EOL, str_pad(' Some basic identity checks: ', 50, '-', STR_PAD_BOTH), PHP_EOL;
$Db = (new Db)->who();
$Db_A = Db_A::getInstance()->who();
$Db_B = Db_B::getInstance()->who();

echo PHP_EOL, str_pad(' Some TRAIT identity checks: ', 50, '-', STR_PAD_BOTH), PHP_EOL;
$Db_A->traitWho();
$Db_B->traitWho();

echo PHP_EOL, str_pad(' Set some SQL and then export it: ', 50, '-', STR_PAD_BOTH), PHP_EOL;
$Db_A->setSchema('/* SOME Db_A SQL */');
$Db_B->setSchema('/* SOME Db_B SQL */');
$Db_A->ExportSchema();
$Db_B->ExportSchema();

?>