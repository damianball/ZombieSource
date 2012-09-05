<?php

class DatastoreException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class PlayerNotMemberOfAnyTeamException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class PlayerMemberOfTeamException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class PlayerDoesNotExistException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}


class GameDoesNotExistException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class IllegalFunctionResultException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class ClassCreationException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class InvalidHumanCodeException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class PlayerDoesNotHaveAnyValidFeedsException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class InvalidParametersException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class LogManagerException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class UserIsNotModeratorException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class NoJobException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class NoNotificationException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

class NoSubscriptionGroupException extends Exception{
    public function __construct($message, $code=null) {
        parent::__construct($message, $code);
    }
}

?>
