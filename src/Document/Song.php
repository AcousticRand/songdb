<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 12/31/2018
 * Time: 9:08 AM
 */

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
/**
 * @MongoDB\Document(collection="songs")
 */
class Song
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $artist;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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

}