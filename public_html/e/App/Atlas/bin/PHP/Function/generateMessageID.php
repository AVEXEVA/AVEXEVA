function generateMessageID(){
    return sprintf(
      "<%s.%s@%s>",
      base_convert(microtime(), 10, 36),
      base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
      $_SERVER['SERVER_NAME']
    );
  }