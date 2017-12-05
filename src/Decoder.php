<?php

namespace PE\Component\VCF;

class Decoder
{
    /**
     * @param $string
     *
     * @return Cards
     */
    public function decode($string)
    {
        // Normalize new lines.
        $string = str_replace(["\r\n", "\r"], "\n", $string);

        // RFC2425 5.8.1. Line delimiting and folding
        // Unfolding is accomplished by regarding CRLF immediately followed by
        // a white space character (namely HTAB ASCII decimal 9 or. SPACE ASCII
        // decimal 32) as equivalent to no characters at all (i.e., the CRLF
        // and single white space character are removed).
        $string = preg_replace("/\n(?:[ \t])/", "", $string);

        $card  = null;
        $lines = explode("\n", $string);
        $cards = new Cards();

        // Parse the VCard, line by line.
        foreach ($lines as $line) {
            $line = trim($line);

            if (strtoupper($line) === 'BEGIN:VCARD') {
                $card = new Card();
            } else if (strtoupper($line) === 'END:VCARD') {
                $cards->add($card);
            } else if (!empty($line)) {
                // Strip grouping information. We don't use the group names. We
                // simply use a list for entries that have multiple values.
                // As per RFC, group names are alphanumerical, and end with a
                // period (.).
                $line = preg_replace('/^\w+\./', '', $line);

                list($type, $value) = array_pad(explode(':', $line, 2), 2, '');

                $types   = explode(';', strtolower($type));
                $element = strtoupper($types[0]);

                array_shift($types);

                // Normalize types. A type can either be a type-param directly,
                // or can be prefixed with "type=". E.g.: "INTERNET" or
                // "type=INTERNET".
                if (!empty($types)) {
                    $types = array_map(function($type) {
                        return preg_replace('/^type=/i', '', $type);
                    }, $types);
                }

                $i = 0;
                $rawValue = false;
                foreach ($types as $type) {
                    if (false !== strpos($type, 'base64')) {
                        $value = base64_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (preg_match('/encoding=b/', $type)) {
                        $value = base64_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (false !== strpos($type, 'quoted-printable')) {
                        $value = quoted_printable_decode($value);
                        unset($types[$i]);
                        $rawValue = true;
                    } elseif (0 === strpos($type, 'charset=')) {
                        try {
                            $value = mb_convert_encoding($value, 'UTF-8', substr($type, 8));
                        } catch (\Exception $e) {}
                        unset($types[$i]);
                    }
                    $i++;
                }

                switch (strtoupper($element)) {
                    case 'FN':
                        $card->setFullName($value);
                        break;
                    case 'N':
                        $this->decodeName($card, $value);
                        break;
                    case 'BDAY':
                        $card->setBirthday(new \DateTime($value));
                        break;
                    case 'ADR':
                        $key = !empty($types) ? implode(';', $types) : 'WORK;POSTAL';

                        if (!$addresses = $card->getAddresses($key)) {
                            $card->setAddresses($key, $addresses = new Addresses());
                        }

                        $addresses->add($this->decodeAddress(new Address(), $value));
                        break;
                    case 'TEL':
                        $key = !empty($types) ? implode(';', $types) : 'default';

                        if (!$phones = $card->getPhones($key)) {
                            $card->setPhones($key, $phones = new Phones());
                        }

                        $phones->add($value);
                        break;
                    case 'EMAIL':
                        $key = !empty($types) ? implode(';', $types) : 'default';

                        if (!$emails = $card->getEmails($key)) {
                            $card->setEmails($key, $emails = new Emails());
                        }

                        $emails->add($value);
                        break;
                    case 'REV':
                        $card->setRevision($value);
                        break;
                    case 'VERSION':
                        $card->setVersion($value);
                        break;
                    case 'ORG':
                        $card->setOrganization($value);
                        break;
                    case 'URL':
                        $key = !empty($types) ? implode(';', $types) : 'default';

                        if (!$urls = $card->getUrls($key)) {
                            $card->setUrls($key, $urls = new Urls());
                        }

                        $urls->add($value);
                        break;
                    case 'TITLE':
                        $card->setTitle($value);
                        break;
                    case 'PHOTO':
                        //TODO parse metadata
                        if ($rawValue) {
                            $card->setPhoto(Image::fromData($value));
                        } else {
                            $card->setPhoto(Image::fromUrl($value));
                        }
                        break;
                    case 'LOGO':
                        //TODO parse metadata
                        if ($rawValue) {
                            $card->setLogo(Image::fromData($value));
                        } else {
                            $card->setLogo(Image::fromUrl($value));
                        }
                        break;
                    case 'NOTE':
                        // Un-escape newline characters according to RFC2425 section 5.8.4.
                        // This function will replace escaped line breaks with PHP_EOL.
                        // @link http://tools.ietf.org/html/rfc2425#section-5.8.4
                        $card->setNote(str_replace("\\n", PHP_EOL, $value));
                        break;
                    case 'CATEGORIES':
                        $card->setCategories(array_map('trim', explode(',', $value)));
                        break;
                }
            }
        }

        return $cards;
    }

    /**
     * @param Card  $card
     * @param string $value
     *
     * @return Card
     */
    private function decodeName(Card $card, $value)
    {
        list($lastName, $firstName, $additional, $prefix, $suffix) = array_pad(explode(';', $value), 5, null);

        return $card->setLastName($lastName)
            ->setFirstName($firstName)
            ->setAdditional($additional)
            ->setPrefix($prefix)
            ->setSuffix($suffix);
    }

    /**
     * @param Address $address
     * @param string       $value
     *
     * @return Address
     */
    private function decodeAddress(Address $address, $value)
    {
        list($name, $extended, $street, $city, $region, $zip, $country) = array_pad(explode(';', $value), 6, null);

        return $address->setName($name)
            ->setExtended($extended)
            ->setStreet($street)
            ->setCity($city)
            ->setRegion($region)
            ->setZip($zip)
            ->setCountry($country);
    }
}