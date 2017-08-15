<?php
/**
 * Created by PhpStorm.
 * User: yangqihua
 * Date: 2017/8/16
 * Time: 上午12:12
 */

namespace amazon\mail;


class POP3Stream
{
    var $opened = 0;
    var $report_errors = 1;
    var $read = 0;
    var $buffer = "";
    var $end_of_message = 1;
    var $previous_connection = 0;
    var $pop3;

    function SetError($error)
    {
        if ($this->report_errors)
            trigger_error($error);
        return (FALSE);
    }

    function ParsePath($path, &$url)
    {
        if (!$this->previous_connection) {
            if (IsSet($url["host"]))
                $this->pop3->hostname = $url["host"];
            if (IsSet($url["port"]))
                $this->pop3->port = intval($url["port"]);
            if (IsSet($url["scheme"])
                && !strcmp($url["scheme"], "pop3s")
            )
                $this->pop3->tls = 1;
            if (!IsSet($url["user"]))
                return ($this->SetError("it was not specified a valid POP3 user"));
            if (!IsSet($url["pass"]))
                return ($this->SetError("it was not specified a valid POP3 password"));
            if (!IsSet($url["path"]))
                return ($this->SetError("it was not specified a valid mailbox path"));
        }
        if (IsSet($url["query"])) {
            parse_str($url["query"], $query);
            if (IsSet($query["debug"]))
                $this->pop3->debug = intval($query["debug"]);
            if (IsSet($query["html_debug"]))
                $this->pop3->html_debug = intval($query["html_debug"]);
            if (!$this->previous_connection) {
                if (IsSet($query["tls"]))
                    $this->pop3->tls = intval($query["tls"]);
                if (IsSet($query["realm"]))
                    $this->pop3->realm = UrlDecode($query["realm"]);
                if (IsSet($query["workstation"]))
                    $this->pop3->workstation = UrlDecode($query["workstation"]);
                if (IsSet($query["authentication_mechanism"]))
                    $this->pop3->realm = UrlDecode($query["authentication_mechanism"]);
            }
            if (IsSet($query["quit_handshake"]))
                $this->pop3->quit_handshake = intval($query["quit_handshake"]);
        }
        return (TRUE);
    }

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->report_errors = (($options & STREAM_REPORT_ERRORS) != 0);
        if (strcmp($mode, "r"))
            return ($this->SetError("the message can only be opened for reading"));
        $url = parse_url($path);
        $host = $url['host'];
        $pop3 = &POP3::SetConnection(0, $host, $this->pop3);
        if (IsSet($pop3)) {
            $this->pop3 = &$pop3;
            $this->previous_connection = 1;
        } else
            $this->pop3 = new POP3;
        if (!$this->ParsePath($path, $url))
            return (FALSE);
        $message = substr($url["path"], 1);
        if (strcmp(intval($message), $message)
            || $message <= 0
        )
            return ($this->SetError("it was not specified a valid message to retrieve"));
        if (!$this->previous_connection) {
            if (strlen($error = $this->pop3->Open()))
                return ($this->SetError($error));
            $this->opened = 1;
            $apop = (IsSet($url["query"]["apop"]) ? intval($url["query"]["apop"]) : 0);
            if (strlen($error = $this->pop3->Login(UrlDecode($url["user"]), UrlDecode($url["pass"]), $apop))) {
                $this->stream_close();
                return ($this->SetError($error));
            }
        }
        if (strlen($error = $this->pop3->OpenMessage($message, -1))) {
            $this->stream_close();
            return ($this->SetError($error));
        }
        $this->end_of_message = FALSE;
        if ($options & STREAM_USE_PATH)
            $opened_path = $path;
        $this->read = 0;
        $this->buffer = "";
        return (TRUE);
    }

    function stream_eof()
    {
        if ($this->read == 0)
            return (FALSE);
        return ($this->end_of_message);
    }

    function stream_read($count)
    {
        if ($count <= 0)
            return ($this->SetError("it was not specified a valid length of the message to read"));
        if ($this->end_of_message)
            return ("");
        if (strlen($error = $this->pop3->GetMessage($count, $read, $this->end_of_message)))
            return ($this->SetError($error));
        $this->read += strlen($read);
        return ($read);
    }

    function stream_close()
    {
        while (!$this->end_of_message)
            $this->stream_read(8000);
        if ($this->opened) {
            $this->pop3->Close();
            $this->opened = 0;
        }
    }
}
