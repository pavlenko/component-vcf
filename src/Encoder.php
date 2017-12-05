<?php

namespace PE\Component\VCF;

class Encoder
{
    const V_AS_SOURCE = '';
    const V_21 = '2.1';
    const V_30 = '3.0';
    const V_40 = '4.0';

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     */
    public function __construct($version = self::V_AS_SOURCE)
    {
        $this->version = (string) $version;
    }

    /**
     * @param Cards $cards
     *
     * @return string
     */
    public function encode(Cards $cards)
    {
        $strings = [];

        foreach ($cards->all() as $card) {
            $string = [];

            $version = $this->version !== static::V_AS_SOURCE ? $this->version: $card->getVersion();

            $string[] = 'BEGIN:VCARD';
            $string[] = "VERSION:{$version}";

            $string[] = $this->fold('FN:' . $this->escape($card->getFullName()));

            $string[] = $this->fold('N:' . $this->escape(
                $card->getLastName()
                . ';' . $card->getFirstName()
                . ';' . $card->getAdditional()
                . ';' . $card->getPrefix()
                . ';' . $card->getSuffix()
            ));

            if ($value = $card->getBirthday()) {
                $string[] = 'BDAY:' . $value->format('Ymd');
            }

            foreach ($card->getAllAddresses() as $type => $addresses) {
                foreach ($addresses->all() as $address) {
                    $string[] = $this->fold(
                        'ADR;' . ($version !== static::V_21 ? 'TYPE=' : '') . strtoupper($type) . ':' . $this->escape(
                            $address->getName()
                            . ';' . $address->getExtended()
                            . ';' . $address->getStreet()
                            . ';' . $address->getCity()
                            . ';' . $address->getRegion()
                            . ';' . $address->getZip()
                            . ';' . $address->getCountry()
                        )
                    );
                }
            }

            foreach ($card->getAllEmails() as $type => $emails) {
                foreach ($emails->all() as $email) {
                    $string[] = $this->fold(
                        'EMAIL;' . ($version !== static::V_21 ? 'TYPE=' : '') . strtoupper($type) . ':' . $this->escape(
                            $email
                        )
                    );
                }
            }

            foreach ($card->getAllPhones() as $type => $phones) {
                foreach ($phones->all() as $phone) {
                    $string[] = $this->fold(
                        'TEL;' . ($version !== static::V_21 ? 'TYPE=' : '') . strtoupper($type) . ':' . $this->escape(
                            $phone
                        )
                    );
                }
            }

            if ($value = $card->getOrganization()) {
                $string[] = $this->fold('ORG:' . $this->escape($value));
            }

            foreach ($card->getAllUrls() as $type => $urls) {
                foreach ($urls->all() as $url) {
                    $string[] = $this->fold('URL:' . $this->escape($url));
                }
            }

            if ($value = $card->getTitle()) {
                $string[] = $this->fold('TITLE:' . $this->escape($value));
            }

            //TODO photo
            //TODO logo

            if ($value = $card->getNote()) {
                $string[] = $this->fold('NOTE:' . $this->escape($value));
            }

            if (count($value = $card->getCategories())) {
                $string[] = $this->fold('CATEGORIES:' . $this->escape(implode(',', $value)));
            }

            $string[] = "REV:" . date("Y-m-d") . "T" . date("H:i:s") . "Z";
            $string[] = 'END:VCARD';

            $strings[] = implode("\r\n", $string);
        }

        return implode("\r\n", $strings);
    }

    /**
     * Fold a line according to RFC2425 section 5.8.1.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.1
     *
     * @param  string $text
     *
     * @return mixed
     */
    private function fold($text)
    {
        // split, wrap and trim trailing separator
        return strlen($text) <= 75
            ? $text
            : substr(chunk_split($text, 73, "\r\n "), 0, -3);
    }

    /**
     * Escape newline characters according to RFC2425 section 5.8.4.
     *
     * @link http://tools.ietf.org/html/rfc2425#section-5.8.4
     *
     * @param  string $text
     *
     * @return string
     */
    private function escape($text)
    {
        $text = str_replace("\r\n", "\\n", $text);
        $text = str_replace("\n", "\\n", $text);

        return $text;
    }
}