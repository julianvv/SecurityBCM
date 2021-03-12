<?php


namespace core;


class LoadEnv
{
    public function Load($rootDir)
    {
        $array = [];
        $configFile = $rootDir."/.env";
        $file = file($configFile);
        if($file)
        {
            foreach ($file as $line)
            {
                $pos = strpos($line, '=');
                $key = substr($line, 0, $pos);
                $value = substr($line, $pos+1, -2);

                $array[$key] = $value;
            }
        }else{
            error_log("Environment file empty");
        }

        return $array;
    }
}