<?php

namespace Drupal\first_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class EditForm extends FormBase
{

  /**
   * the node being edited.
   * 
   * @var\Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'first_form_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Node $node = NULL)
  {
    $this->node = $node;
    
    // print_r($node);
    // exit();
    
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $node->getTitle(),
      '#required' => TRUE,
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#placeholder' => $this->t('Enter the name'),
      '#default_value' => $node->get('field_name')->value,
      '#required' => TRUE,
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
      '#placeholder' => $this->t('Enter the age'),
      '#default_value' => $node->get('field_age')->value,
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#placeholder' => $this->t('Enter the email'),
      '#default_value' => $node->get('field_email')->value,
      '#required' => TRUE,
    ];
    $form['qualification'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Qualification'),
      '#placeholder' => $this->t('Enter the qualification'),
      '#default_value' => $node->get('field_qualification')->value,
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Get the entity from the form state values.
    $values = $form_state->getValues();

    // print_r($values);
    // exit;
    $this->node->setTitle($values['title']);
    $this->node->set('field_name', $values['name']);
    $this->node->set('field_age', $values['age']);
    $this->node->set('field_email', $values['email']);
    $this->node->set('field_qualification', $values['qualification']);

    $this->node->save();

    // Provide a message to the user.
    \Drupal::messenger()->addMessage($this->t('Node updated successfully.'));
  }

}