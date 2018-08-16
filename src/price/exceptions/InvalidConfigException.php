<?php

class InvalidConfigException extends CException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Configuration';
    }
}
