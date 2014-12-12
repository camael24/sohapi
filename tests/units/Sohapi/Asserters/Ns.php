<?php

namespace Sohtest\Asserters;

class Ns extends Generic {
    public function get() {
        $data   = $this->_data['namespace'];


        return $this->call('array', $data);
    }
}
