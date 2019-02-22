<?php
/**
 * Created by PhpStorm.
 * User: minhhoangdang
 * Date: 21/02/19
 * Time: 23:02
 */

namespace App\Entity;


interface GenericEntity
{
    public function getId(): ?int;
    public function toArray();
    public function  updateAll($entity);
}