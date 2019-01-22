<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 12/31/2018
 * Time: 9:08 AM
 */

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="songs", repositoryClass="App\Repository\SongRepository")
 * @MongoDB\Index(keys={"artist"="text", "title"="text"})
 */
class Song
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $artist;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $key;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $camelot;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $duration;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $bpm;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param mixed $artist
     */
    public function setArtist($artist): void
    {
        $this->artist = $artist;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getCamelot()
    {
        return $this->camelot;
    }

    /**
     * @param mixed $camelot
     */
    public function setCamelot($camelot): void
    {
        $this->camelot = $camelot;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getBpm()
    {
        return $this->bpm;
    }

    /**
     * @param mixed $bpm
     */
    public function setBpm($bpm): void
    {
        $this->bpm = $bpm;
    }

}