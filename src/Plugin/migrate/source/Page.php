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
 * Drupal 7 node source from database.
 *
 * @MigrateSource(
 *   id = "d7_page"
 * )
 */
class Page extends DrupalSqlBase implements SourceEntityInterface {

  /**
   * The join options between the node and the node_revisions table.
   */
  const JOIN = 'n.vid = fdb.revision_id AND n.nid = fdb.entity_id';

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Select node in its last revision.
    $query = $this->select('node', 'n')
      ->condition('n.type', 'page', '=')
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
      ))
      ->fields('fdb', array(
//        'body_summary',
        'body_format',
        'body_value',
      ));
    $query->leftJoin('field_data_body', 'fdb', static::JOIN);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
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
//      'revision' => $this->t('Create new revision'),
//      'log' => $this->t('Revision Log message'),
      'language' => $this->t('Language (fr, en, ...)'),
//      'tnid' => $this->t('The translation set id for this node'),
//      'body_summary' => $this->t('Summary of the Body'),
      'body_format' => $this->t('fromat like filtered_html, full_html'),
      'body_value' => $this->t('Text/HTML of the body'),
    );
    return $fields;
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

}
