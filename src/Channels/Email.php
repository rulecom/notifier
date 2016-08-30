<?php namespace RuleCom\Notifier\Channels;

use Rule\ApiWrapper\Api\V2\Transaction\Transaction;

class Email implements Channel
{
    /**
     * @var string|null
     */
    private $transaction;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $toName;

    /**
     * @var string
     */
    private $toEmail;

    /**
     * @var string
     */
    private $htmlContent;

    /**
     * @var string
     */
    private $plainContent;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function fromName($name)
    {
        $this->fromName = $name;
        return $this;
    }


    /**
     * @param string $email
     * @return $this
     */
    public function fromEmail($email)
    {
        $this->fromEmail = $email;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function toName($name)
    {
        $this->toName = $name;
        return $this;
    }

    /**
     * @param string$email
     * @return $this
     */
    public function toEmail($email)
    {
        $this->toEmail = $email;
        return $this;
    }

    /**
     * @param string $html
     * @return $this
     */
    public function htmlContent($html)
    {
        $this->htmlContent = $html;
        return $this;
    }

    /**
     * @param string $plain
     * @return $this
     */
    public function plainContent($plain)
    {
        $this->plainContent = $plain;
        return $this;
    }

    /**
     * Dispatches notification message to Rule
     */
    public function dispatch()
    {
        $this->transaction->send([
            'transaction_type' => 'email',
            'transaction_name' => $this->subject,
            'subject' => $this->subject,
            'from' => [
                'name' => $this->fromName,
                'email' => $this->fromEmail
            ],
            'to' => [
                'name' => $this->toName,
                'email' => $this->toEmail
            ],
            'content' => [
                'html' => $this->htmlContent,
                'plain' => $this->plainContent
            ]
        ]);
    }
}
