#!/usr/bin/php
<?php
    class PharPackager {
        public static function main(array $argv, int $argc) {

            $argv = getopt("ht:s:d:");

            if (in_array('h', array_keys($argv))) self::displayHelp();

            if (in_array('s', array_keys($argv))) $source = $argv['s'];
            else self::displayHelp();

            if (in_array('t', array_keys($argv))) $target = $argv['t'];
            else self::displayHelp();

            if (in_array('d', array_keys($argv))) $stub = $argv['d'];
            else self::displayHelp();

            self::createArchive($source, $target, $stub);

        }

        protected function createArchive(string $source, string $target, string $stub ):void {
            $phar = new Phar($target);
            $defaultStub = $phar->createDefaultStub($stub);

            $phar->startBuffering();

            $phar->buildFromDirectory($source);

            $stub = "#!/usr/bin/php \n" . $defaultStub;

            $phar->setStub($stub);

            $phar->stopBuffering();

            $phar->compressFiles(Phar::GZ);

            chmod($target, 0770);

            echo "$target successfully created" . PHP_EOL;

        }

        protected function displayHelp():void {
            echo 'usage: ./PharPackage.php -t [target] -s [source] -d [default-stub]'.PHP_EOL;
            die;
        }
    }

    PharPackager::main($argv, $argc);
?>