<?php
namespace Sohapi\Parser\Php {
    class Generic
    {
        protected function consume(&$tokens)
        {
            return array_shift($tokens);
        }

        public function dump($tokens)
        {
            echo $this->concat($tokens);
        }

        public function concat($tokens)
        {
            $buffer = '';
            foreach ($tokens as $key => $value) {
                $buffer .= strval($value[1]);
            }

            return trim($buffer);
        }

        protected function getTokensBetweenToken(&$tokens, $tStart, $tEnd, $limit = 0)
        {
            return $this->_getTokenBetween($tokens, $tStart, $tEnd, $limit, '0');
        }
        protected function getTokensBetweenValue(&$tokens, $tStart, $tEnd, $limit = 0)
        {
            return $this->_getTokenBetween($tokens, $tStart, $tEnd, $limit, '1');
        }

        protected function _getTokenBetween(&$tokens, $tStart, $tEnd, $limit, $type)
        {
            $buffer  = array();
            $process = false;
            $block   = 0;
            $count   = 0;
            while (($token = $this->consume($tokens)) !== null) {

                if ($tStart === $token[$type]) {
                    $process = true;
                    $buffer[] = $token;
                    $count++;
                } elseif ($tEnd === $token[$type]) {
                    $process = false;
                    $count--;
                    $buffer[] = $token;
                    if ($count == 0) {
                        return $buffer;
                    }
                } else {
                    $buffer[] = $token;
                }

            }

            return $buffer;
        }

        protected function getUntilValue(&$tokens, $limit)
        {
            return $this->_getUntil($tokens, $limit, '1');
        }

        protected function getUntilToken(&$tokens, $limit)
        {
            return $this->_getUntil($tokens, $limit, '0');
        }

        protected function _getUntil(&$tokens, $limit, $type)
        {
            if (is_string($limit)) {
                $limit = array($limit);
            }

            $buffer = array();

            while (($token = $this->consume($tokens)) !== null) {

                $buffer[] = $token;

                if (in_array($token[$type], $limit)) {
                    return $buffer;
                }
            }

            return $buffer;
        }

        protected function extractToken(&$tokens, $list)
        {
        }

        protected function tokenExists($token, $search)
        {
            return $this->_exists($token, $search, 0);
        }

        protected function valueExists($token, $search)
        {
            return $this->_exists($token, $search, 1);
        }

        protected function _exists($token, $search, $type)
        {
            foreach ($token as $t)
                if($t[$type] === $search)

                    return true;

            return false;
        }

    }
}
