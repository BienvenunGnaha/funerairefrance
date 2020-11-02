<?php


namespace App\Services;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class UpdataSchema
{
    private $em;
    private $entity;
    private $tableName;
    private $tableNameOriginal;

     public function __construct(EntityManager $entityManager, string $entity,
                                 string $tableName, string $tableNameOriginal)
     {
         $this->em = $entityManager;
         $this->entity = $entity;
         $this->tableName = $tableName;
         $this->tableNameOriginal = $tableNameOriginal;
     }

     public function update(){
         $em = clone $this->em;

         $metadata = $this->em->getClassMetadata($this->entity);
         $metadata->setTableName($this->tableName);
         $schemaTool = new SchemaTool($this->em);
         $schemaTool->updateSchema(array($metadata), true);

         $metadataOriginal = $em->getClassMetadata($this->entity);
         $metadata->setTableName($this->tableNameOriginal);
         $schemaToolOriginal = new SchemaTool($em);
         $schemaToolOriginal->updateSchema(array($metadataOriginal), true);
     }


}