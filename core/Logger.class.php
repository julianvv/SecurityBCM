<?php


namespace core;


class Logger
{
    private $file;
    private $filePath;
    private $employeeFile;
    private $employeeFilepath;

    public function __construct()
    {
        $directory = '/var/log/EnergieBewust/';
        $filename = sprintf("customer.php.log");

        $this->filePath = $directory.$filename;
        $this->file = fopen($this->filePath, 'a');

        $employeeFilename = sprintf("employee.php.log");
        $this->employeeFilepath = $directory.$employeeFilename;
        $this->employeeFile = fopen($this->employeeFilepath, 'a');
    }

    public function __destruct()
    {
       fclose($this->file);
       fclose($this->employeeFile);
    }

    public function writeToLog($message)
    {
        fwrite($this->file, $this->getPrefix().' '.$message.PHP_EOL);
    }

    public function writeToEmployeeLog($message)
    {
        fwrite($this->employeeFile, $this->getPrefix().' '.$message.PHP_EOL);
    }

    public function readLogByCN($cn)
    {
        $pattern = "/`\b".$cn."\b`/";
        $this->writeToEmployeeLog(sprintf("`%s` heeft de log van `%s` opgevraagd vanaf ip: %s", Application::$app->session->get('employee_data')['cn'][0], $cn, $_SERVER['REMOTE_ADDR']));
        return preg_grep($pattern, file($this->employeeFilepath));
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