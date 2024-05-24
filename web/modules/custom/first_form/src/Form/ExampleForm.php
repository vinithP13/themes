<?php

namespace Drupal\first_form\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Drupal form with an example of form validation.
 */

class ExampleForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'first_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // print_r($form_state);
    // exit;

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#placeholder' => 'Enter the title',
      '#required' => TRUE,
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#placeholder' => 'Enter the name',
      '#required' => TRUE,
    ];
    $form['age'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Age'),
      '#placeholder' => 'Enter the age',
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('email'),
      '#placeholder' => 'Enter the email',
      '#required' => TRUE,
    ];
    $form['qualification'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Qualification'),
      '#placeholder' => 'Enter the qualification',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // Validate name.
    if (strlen($form_state->getValue('name')) < 3) {
      $form_state->setErrorByName(
        'name',
        $this->t('Your name should be longer than 3 letters')
      );
    }
    // Validate email address.  
    if (!empty($email) && !EmailValidator::isValid($email, TRUE)) {
      $form_state->setErrorByName(
        'mail',
        $this->t('Please enter a valid email address.')
      );
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // print_r($form_state);
    // exit;

    // Retrieve submitted values
    $title = $form_state->getValue('title');
    $name = $form_state->getValue('name');
    $age = $form_state->getValue('age');
    $email = $form_state->getValue('email');
    $qualification = $form_state->getValue('qualification');

    // Check if there's an existing node ID in the query string
    $nid = \Drupal::request()->query->get('id');

    // Check if any of the required fields are empty
    if (empty($title) || empty($name) || empty($age) || empty($email) || empty($qualification)) {
      \Drupal::messenger()->addError($this->t('Please fill out all the required fields.'));
      return;
    }

    // Create or load node based on the presence of $nid
    if ($nid) {
      // Load existing node
      $node = \Drupal\node\Entity\Node::load($nid);
      if (!$node) {
        \Drupal::messenger()->addError($this->t('Invalid node ID.'));
        return;
      }
      // Update node fields
      $node->set('title', $title);
      $node->set('field_name', $name);
      $node->set('field_age', $age);
      $node->set('field_email', $email);
      $node->set('field_qualification', $qualification);
    } else {
      // Create new node
      $node = \Drupal\node\Entity\Node::create([
        'type' => 'form',
        'title' => $title,
        'field_name' => $name,
        'field_age' => $age,
        'field_email' => $email,
        'field_qualification' => $qualification,
      ]);
    }

    // Save the node
    $node->save();

    // Optionally, you can also show a message to the user
    \Drupal::messenger()->addMessage($this->t('Form submitted successfully!'));
  }
}