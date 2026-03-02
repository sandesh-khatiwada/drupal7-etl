<?php

/**
 * Controller for Reflection Log entity.
 */
class ReflectionLogController extends EntityAPIController {

  /**
   * Override create to set default values.
   */
  public function create($values = array(), $entity_type = NULL) {
    $entity = parent::create($values, $entity_type);

    if (!isset($entity->created)) {
      $entity->created = REQUEST_TIME;
    }

    if (!isset($entity->changed)) {
      $entity->changed = REQUEST_TIME;
    }

    return $entity;
  }

  /**
   * Override save to update changed timestamp.
   */
  public function save($entity, DatabaseTransaction $transaction = NULL) {
    $entity->changed = REQUEST_TIME;


    return parent::save($entity, $transaction);
  }
}