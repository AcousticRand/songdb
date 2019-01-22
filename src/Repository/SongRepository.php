<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 1/21/2019
 * Time: 11:05 PM
 */

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SongRepository extends DocumentRepository
{
    public function search(string $criteria)
    {
        return $this->createQueryBuilder()
            ->field('title')
            ->equals(new \MongoDB\BSON\Regex($criteria))
            ->hydrate(false)
            ->getQuery()
            ->execute();
    }
}