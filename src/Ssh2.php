<?php

namespace raphaeu;

class Ssh2 {

    private $host;
    private $user;
    private $port = '22';
    private $password;
    private $con = null;
    private $shell_type = 'xterm';
    private $shell = null;
    private $log = '';

    function __construct($host, $port=null  ) {

        if( !is_null($port) ) $this->port  = $port;
        $this->host = $host;

        $this->con  = @ssh2_connect($this->host, $this->port);
        if( !$this->con ) {
            $this->log .= "Connection failed !";
        }

    }

    function authPassword( $user = '', $password = '' ) {

        if( $user!='' ) $this->user  = $user;
        if( $password!='' ) $this->password  = $password;

        if( @!ssh2_auth_password( $this->con, $this->user, $this->password ) ) {
        $this->log .= "Authorization failed !";
        }

    }

    function openShell( $shell_type = '' ) {

        if ( $shell_type != '' ) $this->shell_type = $shell_type;
        $this->shell = ssh2_shell( $this->con,  $this->shell_type );
        if( !$this->shell ) $this->log .= " Shell connection failed !";

    }

    function writeShell( $command = '' ) {

        fwrite($this->shell, $command."\n");

    }

    function cmdExec($cmd ) {

        $stream = ssh2_exec( $this->con, $cmd );
        stream_set_blocking( $stream, true );
        return stream_get_contents( $stream);

    }

    function getLog() {
        return $this->log;
    }

}