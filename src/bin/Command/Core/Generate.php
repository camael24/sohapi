<?php
namespace Sohapi\Bin\Command\Core {

    use Hoa\Console\Chrome\Text;
    use Hoa\File\Finder;

    class Generate extends \Hoa\Console\Dispatcher\Kit
    {

        protected $options = array(
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
            array('debug', \Hoa\Console\GetOption::NO_ARGUMENT, 'v'),
            array('dry-run', \Hoa\Console\GetOption::NO_ARGUMENT, 'p'),
            array('file', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'f'),
            array('directory', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'd'),
            array('output', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'o'),
            array('style-formatter', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 's'),
        );

        /**
         * The entry method.
         *
         * @access  public
         * @return  int
         */
        public function main()
        {

            $file               = null;
            $debug              = false;
            $dry                = false;
            $directory          = null;
            $output             = 'out/';
            $style_formatter    = 'Cli';

            while (false !== $c = $this->getOption($v)) {
                switch ($c) {
                    case 'f':
                        $file = $v;
                        break;
                    case 'd':
                        $directory = $v;
                        break;
                    case 'o':
                        $output = $v;
                        break;
                    case 's':
                        $style_formatter = $v;
                        break;
                    case 'v':
                        $debug = true;
                        break;
                    case 'p':
                        $dry = true;
                        break;
                    case 'h':
                    case '?':
                    default:
                        return $this->usage();
                        break;
                }
            }

            echo \Hoa\Console\Chrome\Text::colorize('Sohapi', 'fg(yellow)'), "\n\n";
            $root       = realpath(__DIR__.'/../../../../');
            $out        = realpath($root.'/'.$output); // TODO : Detect relative avec absolute path !
            $formatter  = '\\Sohapi\\Formatter\\'.ucfirst($style_formatter);

            if($file !== null)
                $file = realpath($root.'/'.$file);

            if($directory !== null)
                $directory = realpath($root.'/'.$directory);

            if($out ===  false)
                $out = $root;

            if ($debug === true) {
                $a = [
                    ['ROOT', var_export($root, true)],
                    ['Debug', var_export($debug, true)],
                    ['Formatter', var_export($formatter, true)],
                    ['Output', var_export($out, true)],
                    ['File', var_export($file, true)],
                    ['Directory', var_export($directory, true)],

                ];

                echo \Hoa\Console\Chrome\Text::columnize($a);
            }

            $files = array();

            $this->searchLocalConfig($root, $files);

            if($file !== null and !in_array($file, $files))
                $files = array($file);

            if ($directory !== null) {
                if ($this->searchLocalConfig($directory, $files) === false) {
                    $finder = new \Hoa\File\Finder();
                    $finder
                        ->in($directory)
                        ->files()
                        ->name('#\.php$#');

                    foreach ($finder as $f) {
                        if (!in_array($this->clean($f->getPathName()), $files)) {
                            $files[] = $this->clean($f->getPathName());
                        }
                    }
                }
            }

            echo 'Found '. count($files).' Files'."\n";

            foreach ($files as $i => $file) {
                echo 'Parsing : ['.($i +1).'/'. count($files) .'] '.$file."\n";
                if ($dry === false) {
                    (new \Sohapi\Parser\Reader($file))->build();
                }
            }

            dnew($formatter, ['options' => ['output' => $out, 'debug' => $debug, 'dry' => $dry]])->render();

            return;
        }

        private function searchLocalConfig($directory, &$store)
        {
            $config = $directory.'/.sohapi.php';
            if (file_exists($config)) {
                echo \Hoa\Console\Chrome\Text::colorize('Read '.$config, 'fg(green)'), "\n";
                $files = require_once $config;
                foreach ($files as $f) {
                    if (!in_array($this->clean($f->getPathName()), $store)) {
                        $store[] = $this->clean($f->getPathName());
                    }
                }

            }

            return file_exists($config);
        }

        private function clean($path)
        {
            return str_replace(['/' , '\\'], '\\', $path);
        }

        /**
         * The command usage.
         *
         * @access  public
         * @return  int
         */
        public function usage()
        {
            echo \Hoa\Console\Chrome\Text::colorize('Usage:', 'fg(yellow)') . "\n";
            echo '   Welcome ' . "\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.'
            ));

            return;
        }
    }
}

__halt_compiler();
Generate an api documentation
