<?php

trait Logger
{
    public static function LogStatic($log, $logName = '')
    {
        static::write($log, $logName);
    }

    public function Log($log, $logName = '')
    {
        static::write($log, $logName);
    }

    protected static function write($log, $logName = '')
    {
        $logEntry = sprintf('[%s] %s%s', date('d-M-y H:i:s T'), $log, PHP_EOL);
        if (!file_put_contents(static::getLogPath($logName), $logEntry, FILE_APPEND))
        {
            throw new RuntimeException(sprintf('%s could not write to file "%s".', __TRAIT__, static::getLogPath($logName)));
        }
    }

    final private static function getLogPath($logName = '')
    {
        static $logFiles;

        if (!is_array($logFiles))
        {
            $logFiles = array();
        }

        if (!strlen($logName))
        {
            $logName = 'default';
        }

        if (!isset($logFiles[$logName]))
        {
            $newPaths = array(__TRAIT__, __CLASS__);
            $dir = './';
            foreach ($newPaths as $newPath)
            {
                $dir .= $newPath;
                if (!is_dir($dir) && !mkdir($dir))
                {
                    throw new RuntimeException(sprintf('%s could not create directory "%s".', __TRAIT__, $dir));
                }
                $dir .= DIRECTORY_SEPARATOR;
            }

            $logFile = $dir . $logName  . '.log';
            if (!is_file($logFile) && !touch($logFile))
            {
                throw new RuntimeException(sprintf('%s could not create file "%s".', __TRAIT__, $logFile));
            }

            $logFiles[$logName] = $logFile;
        }

        return $logFiles[$logName];
    }
}

?>