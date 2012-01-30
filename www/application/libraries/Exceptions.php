<?php 

class DatastoreException extends Exception{
    public function __construct($message, $code=null, $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}

class PlayerNotMemberOfAnyTeamException extends Exception{
    public function __construct($message, $code=null, $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}

class PlayerMemberOfTeamException extends Exception{
    public function __construct($message, $code=null, $previous=null) {
        parent::__construct($message, $code, $previous);
    }
}
?>
