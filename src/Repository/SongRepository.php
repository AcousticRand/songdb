<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 1/21/2019
 * Time: 11:05 PM
 */

namespace App\Repository;

use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use MongoDB\BSON\Regex;
use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

class SongRepository extends DocumentRepository
{
    /**
     * @param string $criteria
     *
     * @return array|Iterator|int|DeleteResult|InsertOneResult|UpdateResult|object|null
     * @throws MongoDBException
     */
    public function searchTitle(string $criteria)
    {
        return $this->createQueryBuilder()
            ->field('title')
            ->equals(new Regex($criteria))
            ->hydrate(false)
            ->getQuery()
            ->execute();
    }
}