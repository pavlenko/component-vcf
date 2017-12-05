<?php

namespace PE\Component\VCF;

class Card
{
    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $additional;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $revision;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $organization;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Image
     */
    private $photo;

    /**
     * @var Image
     */
    private $logo;

    /**
     * @var string
     */
    private $note;

    /**
     * @var string[]
     */
    private $categories = [];

    /**
     * @var Addresses[]
     */
    private $addresses = [];

    /**
     * @var Phones[]
     */
    private $phones = [];

    /**
     * @var Emails[]
     */
    private $emails = [];

    /**
     * @var Urls[]
     */
    private $urls = [];

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param string $additional
     *
     * @return $this
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     *
     * @return $this
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return $this
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @param string $revision
     *
     * @return $this
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string $organization
     *
     * @return $this
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Image
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param Image $photo
     *
     * @return $this
     */
    public function setPhoto(Image $photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return Image
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param Image $logo
     *
     * @return $this
     */
    public function setLogo(Image $logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     *
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string[] $categories
     *
     * @return $this
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasAddresses($type = 'WORK;POSTAL')
    {
        return array_key_exists($type, $this->addresses);
    }

    /**
     * @param string $type
     *
     * @return Addresses|null
     */
    public function getAddresses($type = 'WORK;POSTAL')
    {
        return array_key_exists($type, $this->addresses) ? $this->addresses[$type] : null;
    }

    /**
     * @param string    $type
     * @param Addresses $addresses
     *
     * @return $this
     */
    public function setAddresses($type, Addresses $addresses)
    {
        $this->addresses[$type] = $addresses;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function removeAddresses($type)
    {
        unset($this->addresses[$type]);
        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasPhones($type = 'default')
    {
        return array_key_exists($type, $this->phones);
    }

    /**
     * @param string $type
     *
     * @return Phones|null
     */
    public function getPhones($type = 'default')
    {
        return array_key_exists($type, $this->phones) ? $this->phones[$type] : null;
    }

    /**
     * @param string $type
     * @param Phones $phones
     *
     * @return $this
     */
    public function setPhones($type, Phones $phones)
    {
        $this->phones[$type] = $phones;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function removePhones($type)
    {
        unset($this->phones[$type]);
        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasEmails($type = 'default')
    {
        return array_key_exists($type, $this->emails);
    }

    /**
     * @param string $type
     *
     * @return Emails|null
     */
    public function getEmails($type = 'default')
    {
        return array_key_exists($type, $this->emails) ? $this->emails[$type] : null;
    }

    /**
     * @param string $type
     * @param Emails $emails
     *
     * @return $this
     */
    public function setEmails($type, Emails $emails)
    {
        $this->emails[$type] = $emails;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function removeEmails($type)
    {
        unset($this->emails[$type]);
        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasUrls($type = 'default')
    {
        return array_key_exists($type, $this->urls);
    }

    /**
     * @param string $type
     *
     * @return Urls|null
     */
    public function getUrls($type = 'default')
    {
        return array_key_exists($type, $this->urls) ? $this->urls[$type] : null;
    }

    /**
     * @param string $type
     * @param Urls   $urls
     *
     * @return $this
     */
    public function setUrls($type, Urls $urls)
    {
        $this->urls[$type] = $urls;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function removeUrls($type)
    {
        unset($this->urls[$type]);
        return $this;
    }

    /**
     * @return Addresses[]
     */
    public function getAllAddresses()
    {
        return $this->addresses;
    }

    /**
     * @return Phones[]
     */
    public function getAllPhones()
    {
        return $this->phones;
    }

    /**
     * @return Emails[]
     */
    public function getAllEmails()
    {
        return $this->emails;
    }

    /**
     * @return Urls[]
     */
    public function getAllUrls()
    {
        return $this->urls;
    }
}