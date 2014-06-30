<?php

/**
 * @file
 * Contains \Drupal\migrate_drupal7\Plugin\migrate\source\Node.
 */

namespace Drupal\migrate_drupal7\Plugin\migrate\source;

use Drupal\migrate\Plugin\SourceEntityInterface;
use Drupal\migrate\Row;
use Drupal\migrate_drupal7\Plugin\migrate\source\DrupalSqlBase;

/**
 * Drupal 7 Lesson node source from database.
 *
 * @MigrateSource(
 *   id = "d7_lesson"
 * )
 */
class Lesson extends DrupalSqlBase implements SourceEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Select node in its last revision.
    $query = $this->select('node', 'n')
      ->condition('n.type', 'lesson', '=')
      ->fields('n', array(
        'nid',
        'vid',
        'type',
        'language',
        'title',
        'uid',
        'status',
        'created',
        'changed',
        'promote',
        'sticky',
        'uuid',
      ));
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = $this->baseFields();
    //field_lesson_description
    $fields['field_lesson_description_value'] = $this->t('Value of field_lesson_description');
    $fields['field_lesson_description_format'] = $this->t('format of the value of field_lesson_description');
    //field_lesson_draft_status
    $fields['field_lesson_draft_status_value'] = $this->t('Value of field_lesson_draft_status');    
    //field_lesson_drupal_version
    //field_lesson_last_peer_review
    //field_lesson_maintainers
    //field_lesson_overview
    //field_lesson_prerequisites
    //field_lesson_project_name
    $fields['field_lesson_project_name_value'] = $this->t('Value of field_lesson_project_name');
    $fields['field_lesson_project_name_format'] = $this->t('format of the value of field_lesson_project_name');   
    //field_lesson_project_type
    //field_lesson_steps
    //field_lesson_tags
    //field_lesson_type
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $nid = $row->getSourceProperty('nid');
    
    //field_lesson_description
    $result = $this->getDatabase()->query('
      SELECT
        fld.field_lesson_description_value,
        fld.field_lesson_description_format
      FROM
        {field_data_field_lesson_description} fld
      WHERE
        fld.entity_id = :nid
    ', array(':nid' => $nid));
    //ASSUMPTION: assuming that there will be only one record/row as a result from above query.
    foreach ($result as $record) {
      $row->setSourceProperty('field_lesson_description_value', $record->field_lesson_description_value );
      $row->setSourceProperty('field_lesson_description_format', $record->field_lesson_description_format );
    }

    
    //field_lesson_draft_status
    $result = $this->getDatabase()->query('
      SELECT
        flds.field_lesson_draft_status_value
      FROM
        {field_data_field_lesson_draft_status} flds
      WHERE
        flds.entity_id = :nid
    ', array(':nid' => $nid));
    //ASSUMPTION: assuming that there will be only one record/row as a result from above query.
    foreach ($result as $record) {
      $row->setSourceProperty('field_lesson_draft_status_value', $record->field_lesson_draft_status_value );
    }

    //field_lesson_project
    $result = $this->getDatabase()->query('
      SELECT
        flp.field_lesson_project_name_value,
        flp.field_lesson_project_name_format
      FROM
        {field_data_field_lesson_project_name} flp
      WHERE
        flp.entity_id = :nid
    ', array(':nid' => $nid));
    //ASSUMPTION: assuming that there will be only one record/row as a result from above query.
    foreach ($result as $record) {
      $row->setSourceProperty('field_lesson_project_name_value', $record->field_lesson_project_name_value );
      $row->setSourceProperty('field_lesson_project_name_format', $record->field_lesson_project_name_format );      
    }    
    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['nid']['type'] = 'integer';
    $ids['nid']['alias'] = 'n';
    return $ids;
  }


  /**
   * {@inheritdoc}
   */
  public function bundleMigrationRequired() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function entityTypeId() {
    return 'node';
  }

  /**
   * Returns the user base fields to be migrated.
   *
   * @return array
   *   Associative array having field name as key and description as value.
   */
  protected function baseFields() {
    $fields = array(
      'nid' => $this->t('Node ID'),
      'vid' => $this->t('Version ID'),
      'type' => $this->t('Type'),
      'title' => $this->t('Title'),
      'format' => $this->t('Format'),
      'teaser' => $this->t('Teaser'),
      'uid' => $this->t('Authored by (uid)'),
      'created' => $this->t('Created timestamp'),
      'changed' => $this->t('Modified timestamp'),
      'status' => $this->t('Published'),
      'promote' => $this->t('Promoted to front page'),
      'sticky' => $this->t('Sticky at top of lists'),
      'uuid' => $this->t('Universally Unique ID'),
      'language' => $this->t('Language (fr, en, ...)'),
    );
    return $fields;
  }

}
