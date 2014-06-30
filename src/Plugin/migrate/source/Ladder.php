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
 * Drupal 7 Ladder node source from database.
 *
 * @MigrateSource(
 *   id = "d7_ladder"
 * )
 */
class Ladder extends DrupalSqlBase implements SourceEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Select node in its last revision.
    $query = $this->select('node', 'n')
      ->condition('n.type', 'ladder', '=')
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
    //field_lesson_overview: Field re-used in `ladder` content-type (i.e. field with multiple instances)
    $fields['field_lesson_overview_value'] = $this->t('Value of field_lesson_overview');
    $fields['field_lesson_overview_format'] = $this->t('format of the value of field_lesson_overview');
    //field_ladder_maintainers
    //field_lessons

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $nid = $row->getSourceProperty('nid');

    //field_lesson_overview
    $result = $this->getDatabase()->query('
      SELECT
        flo.field_lesson_overview_value,
        flo.field_lesson_overview_format
      FROM
        {field_data_field_lesson_overview} flo
      WHERE
        flo.entity_id = :nid
    ', array(':nid' => $nid));
    //ASSUMPTION: assuming that there will be only one record/row as a result from above query.
    foreach ($result as $record) {
      $row->setSourceProperty('field_lesson_overview_value', $record->field_lesson_overview_value );
      $row->setSourceProperty('field_lesson_overview_format', $record->field_lesson_overview_format );
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
