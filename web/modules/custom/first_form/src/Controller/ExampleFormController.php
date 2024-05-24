<?php

namespace Drupal\first_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse; // Import RedirectResponse class

/**
 * Controller for displaying form data in a table.
 */
class ExampleFormController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ExampleFormController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Displays form data in a table.
   */
  public function displayFormData() {
    $header = [
      'title' => $this->t('Title'),
      'name' => $this->t('Name'),
      'age' => $this->t('Age'),
      'email' => $this->t('Email'),
      'qualification' => $this->t('Qualification'),
      'actions' => $this->t('Actions'),
     
    ];
    // print_r($header);
    // exit();

    $rows = [];

    // Load all entities of the custom content type.
    $entities = $this->entityTypeManager->getStorage('node')->loadByProperties(['type' => 'Form']);

    foreach ($entities as $entity) {
      $rows[] = [
        'title' => $entity->getTitle(),
        'name' => $entity->get('field_name')->value,
        'age' => $entity->get('field_age')->value,
        'email' => $entity->get('field_email')->value,
        'qualification' => $entity->get('field_qualification')->value,
        'actions' => [
          'data' => [
            '#type' => 'container',
            'edit' => [
              '#type' => 'link',
              '#title' => $this->t('Edit'),
              '#url' =>  Url::fromRoute('first_form.edit_form', ['node' => $entity->id()]),
            ],
          ],
        ],
      ];
    }

    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }

  /**
   * Edit form for the submitted values.
   */
  public function editForm($node) {
    // Load the entity to be edited.
    $entity = $this->entityTypeManager->getStorage('node')->load($node);

    // Redirect if the entity doesn't exist.
    if (!$entity) {
      return new RedirectResponse(Url::fromRoute('first_form.display_form_data')->toString());
    }

    // Create the edit form using Drupal's form API.
    $form = \Drupal::formBuilder()->getForm('Drupal\first_form\Form\EditForm', $entity);

    return $form;
  }
}