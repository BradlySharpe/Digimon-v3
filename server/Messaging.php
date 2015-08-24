<?php

  class Messaging {

    public static function error($message) {
      return json_encode(
        [
          'error' => true,
          'message' => $message
        ]
      );
    }

    public static function pong() {
      return json_encode(
        [
          'error' => false,
          'event' => 'pong'
        ]
      );
    }

    public static function request($event, $action, $data) {
      return json_encode(
        [
          'error' => false,
          'event' => $event,
          'action' => $action."Request",
          'data' => $data
        ]
      );
    }

    public static function response($event, $action, $data) {
      return json_encode(
        [
          'error' => false,
          'event' => $event,
          'action' => $action."Response",
          'data' => $data
        ]
      );
    }

  }

 ?>
