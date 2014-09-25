<?php
namespace Sohapi\Parser\Php {
    class Generic implements Element
    {
        public function getUntilValue(\SplQueue &$handle, $value, &$type = null)
        {
            if (is_string($value)) {
                $value = array($value);
            }
            $return   = new \SplQueue();
            foreach ($handle as $k => $v) {
                if (isset($v[0]) === true && $v[0] !== 'T_WHITESPACE') {
                    if (isset($v[1]) === true && in_array($v[1], $value) !== true) {
                        $return->enqueue($handle->dequeue());
                    } else {
                        $type = $v[1];

                        return $return;
                    }
                } else {
                    $handle->dequeue();
                }
            }

            return $return;

        }

        public function getUntilToken(\SplQueue &$handle, $value)
        {
            if (is_string($value)) {
                $value = array($value);
            }
            $return   = new \SplQueue();
            foreach ($handle as $k => $v) {
                if (isset($v[0]) === true && $v[0] !== 'T_WHITESPACE') {
                    if (isset($v[0]) === true && in_array($v[0], $value) !== true) {
                        $return->enqueue($handle->dequeue());
                    } else {
                        $handle->dequeue();

                        return $return;
                    }
                } else {
                    $handle->dequeue();
                }
            }

            return $return;
        }

        public function extractToken(\SplQueue $handle, $value)
        {
            if (is_string($value)) {
                $value = array($value);
            }

            $return  = array();
            $current = '';

            foreach ($value as $a) {
                $return[$a] = new \SplQueue();
            }

            foreach ($handle as $v) {
                $item = $handle->dequeue();
                $current = '';
                if (isset($item[0])) {
                    $type = $item[0];
                    if (in_array($type, $value)) {
                        $current = $type;
                    } else {
                        if (isset($return[$current]) && is_object($return[$current])) {
                            $return[$current]->enqueue($item);
                        }
                    }
                }
            }

            return $return;
        }

        public function concatNodes($nodes)
        {
            $out = '';

            foreach ($nodes as $node)
                $out .= strval($node[1]);

            return $out;
        }

        public function getListData(\SplQueue $handle)
        {
            $return = array();
            foreach ($handle as $key => $value) {
                if ($value[0] !== 'T_STRUCTURAL') {
                    $return[] = $value[1];
                }
            }

            return $return;
        }

        public function getNodeBetween($handle, $start, $end)
        {
            $return   = new \SplQueue();
            $count    = 0;

            foreach ($handle as $item) {
                $item      = $handle->dequeue();
                $value     = (isset($item[1])) ? $item[1] : '';

                if ($start === $value) {
                    $count++;
                } elseif ($end === $value) {
                    $count--;
                }

                if (isset($item[0]) === true and $item[0] !== 'T_WHITESPACE') {
                    $return->enqueue($item);
                }

                if ($count === 0) {
                    $fist = $return->shift();
                    $last = $return->pop();

                    return $return;
                }
            }

            $fist = $return->shift();
            $last = $return->pop();

            return $return; // End of queue
        }
    }
}
