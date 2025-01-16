<?php

namespace NotificationChannels\Bird;

use Random\RandomException;

class BirdMessage
{
    protected string $originator;

    protected array $recipients;

    protected string $reference;

    protected string $reportUrl;

    protected string $dataCoding;

    public function __construct(protected string $body = '')
    {
        if (!empty($body)) {
            $this->body = trim($body);
        }
    }

    public function setOriginator(string $originator): static
    {
        $this->originator = $originator;

        return $this;
    }

    public function doesntHaveOriginator(): bool
    {
        return empty($this->originator);
    }

    public function setRecipients(array $recipients): static
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function doesntHaveRecipients(): bool
    {
        return empty($this->recipients);
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @throws RandomException
     */
    public function generateReference(): void
    {
        $this->reference = bin2hex(random_bytes(10));
    }

    public function setDataCoding(string $dataCoding): static
    {
        $this->dataCoding = $dataCoding;

        return $this;
    }

    public function setReportUrl(string $reportUrl): static
    {
        $this->reportUrl = $reportUrl;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'receiver' => [
                'contacts' => array_map(fn($recipient) => ['identifierValue' => $recipient], $this->recipients),
            ],
            'body' => [
                'type' => 'text',
                'text' => [
                    'text' => $this->body,
                ],
            ],
            'reference' => $this->reference,
        ];
    }
}
