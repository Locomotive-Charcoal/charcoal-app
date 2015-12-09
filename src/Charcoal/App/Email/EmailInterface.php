<?php

namespace Charcoal\App\Email;

interface EmailInterface
{
    /**
     * @param array $data The data to set.
     * @return EmailInterface Chainable
     */
    public function set_data(array $data);

    /**
     * @param string $campaign The campaign identifier.
     * @return EmailInterface Chainable
     */
    public function set_campaign($campaign);

    /**
     * @return string
     */
    public function campaign();

    /**
     * @param mixed $to The email's main recipient(s).
     * @return EmailInterface Chainable
     */
    public function set_to($to);

    /**
     * @param mixed $to The email's recipient to add, either as a string or an "email" array.
     * @return EmailInterface Chainable
     */
    public function add_to($to);

    /**
     * @return string[] The email's recipients.
     */
    public function to();

    /**
     * @param mixed $cc The emails' carbon-copy (CC) recipient(s).
     * @return EmailInterface Chainable
     */
    public function set_cc($cc);

    /**
     * @param mixed $cc The emails' carbon-copy (CC) recipient to add.
     * @return EmailInterface Chainable
     */
    public function add_cc($cc);

    /**
     * @return string[] The emails' carbon-copy (CC) recipient(s).
     */
    public function cc();

     /**
      * @param mixed $bcc The emails' black-carbon-copy (BCC) recipient(s).
      * @return EmailInterface Chainable
      */
    public function set_bcc($bcc);

    /**
     * @param mixed $bcc The emails' black-carbon-copy (BCC) recipient to add.
     * @return EmailInterface Chainable
     */
    public function add_bcc($bcc);

    /**
     * @return string[] The emails' black-carbon-copy (BCC) recipient(s).
     */
    public function bcc();

    /**
     * @param mixed $from The message's sender email address.
     * @return EmailInterface Chainable
     */
    public function set_from($from);

    /**
     * @return string The message's sender email address.
     */
    public function from();

    /**
     * Set the "reply-to" header field.
     *
     * @param mixed $reply_to The sender's reply-to email address.
     * @return EmailInterface Chainable
     */
    public function set_reply_to($reply_to);

    /**
     * @return string The sender's reply-to email address.
     */
    public function reply_to();

    /**
     * @param string $subject The emails' subject.
     * @return EmailInterface Chainable
     */
    public function set_subject($subject);

    /**
     * @return string The emails' subject.
     */
    public function subject();

    /**
     * @param string $msg_html The message's HTML body.
     * @return EmailInterface Chainable
     */
    public function set_msg_html($msg_html);

    /**
     * @return string The message's HTML body.
     */
    public function msg_html();

    /**
     * @param string $msg_txt The message's text body.
     * @return EmailInterface Chainable
     */
    public function set_msg_txt($msg_txt);

    /**
     * @return string The message's text body.
     */
    public function msg_txt();

    /**
     * @param array $attachments The attachments.
     * @return EmailInterface Chainable
     */
    public function set_attachments(array $attachments);

    /**
     * @param mixed $attachment The attachments.
     * @return EmailInterface Chainable
     */
    public function add_attachment($attachment);

    /**
     * @return array
     */
    public function attachments();

    /**
     * Enable or disable logging for this particular email.
     *
     * @param boolean $log The log flag.
     * @return EmailInterface Chainable
     */
    public function set_log($log);

    /**
     * @return boolean
     */
    public function log();

    /**
     * Enable or disable tracking for this particular email.
     *
     * @param boolean $track The track flag.
     * @return EmailInterface Chainable
     */
    public function set_track($track);

    /**
     * @return boolean
     */
    public function track();

    /**
     * Send the email to all recipients.
     *
     * @return boolean Success / Failure.
     */
    public function send();

    /**
     * Add the email to the queue pool.
     *
     * @return boolean Success / Failure.
     */
    public function queue();
}
