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

?>
