<?php

  class Message 
  {
    private $url;

    /* ======================================
      Constructor
    ====================================== */
    public function __construct($url)
    {
      $this->url = $url;
    }

    
    /* ======================================
      Set message
    ====================================== */
    public function setMessage($msg, $type, $redirect = "index.php") 
    {

      $_SESSION["msg"] = $msg;
      $_SESSION["type"] = $type;

      if ($redirect != "back") {
        header("Location: $this->url" . $redirect);
      } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
      }

    } 


    /* ======================================
      Get message
    ====================================== */
    public function getMessage() 
    {
      
      if (!empty($_SESSION["msg"])) {
        return [
          "msg" => $_SESSION["msg"],
          "type" => $_SESSION["type"]
        ];
      } else {
        return false;
      }

    } 

    /* ======================================
      Clear message
    ====================================== */
    public function clearMessage() 
    {
       $_SESSION["msg"] = "";
      $_SESSION["type"] = "";
    } 
  }