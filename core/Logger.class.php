<?php


namespace core;


class Logger
{
    private $file;
    private $employeeFile;

    public function __construct()
    {
        $date = $this->getDate();
        $directory = '/var/log/EnergieBewust/';
        $filename = sprintf("%s.php.log", $date);

        $path = $directory.$filename;
        $this->file = fopen($path, 'a');

        $employeeFilename = sprintf("%s.php.employee.log", $date);
        $employeePath = $directory.$employeeFilename;
        $this->employeeFile = fopen($employeePath, 'a');
    }

    public function __destruct()
    {
       fclose($this->file);
    }

    public function writeToLog($message)
    {
        fwrite($this->file, $this->getPrefix().' '.$message.PHP_EOL);
    }

    public function writeToEmployeeLog($message)
    {
        fwrite($this->employeeFile, $this->getPrefix().' '.$message.PHP_EOL);
    }

    private function getDate()
    {
        return date("d-m-Y");
    }

    private function getTimestamp()
    {
        return date("H:i:s");
    }

    private function getPrefix()
    {
        return sprintf("[%s %s]", $this->getDate(), $this->getTimestamp());
    }
}