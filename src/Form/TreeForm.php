<?php

namespace Drupal\rep\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rep\Vocabulary\SIO;
use Drupal\rep\Vocabulary\VSTOI;
use Drupal\rep\EntryPoints;
use Drupal\Core\Url;
use Drupal\rep\Utils;
use Drupal\rep\Entity\Tables;

class TreeForm extends FormBase {

  protected $elementType;
  protected $rootNode;

  public function getElementType() {
    return $this->elementType;
  }

  public function setElementType($elementType) {
    return $this->elementType = $elementType;
  }

  public function getRootNode() {
    return $this->rootNode;
  }

  public function setRootNode($rootNode) {
    return $this->rootNode = $rootNode;
  }

  public function getFormId() {
    return 'tree_form';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param null|string $mode Ex: 'browse' ou 'select'
   * @param null|string $elementtype Ex: 'unit', 'attribute', etc.
   * @param array|null $branches_param Ex: [
   *    ['id' => 'unit', 'uri' => SIO::UNIT, 'label' => 'Units']
   * ]
   * @param string|null $output_field_selector Ex: '#my-custom-field'
   */
  public function buildForm(array $form, FormStateInterface $form_state, $mode = NULL, $elementtype = NULL, array $branches_param = NULL, $output_field_selector = NULL, $silent = false, $prefix = false) {

    $form['#cache']['max-age'] = 0;

    // Toggles
    $hide_draft = $form_state->getValue('hide_draft') ?? true;
    $hide_deprecated = $form_state->getValue('hide_deprecated') ?? true;
    $show_namespace = $form_state->getValue('show_namespace') ?? true;
    $show_prefix = $form_state->getValue('show_prefix') ?? true;

    $show_label = $form_state->getValue('show_label') ?? 'label';

    // basic validation of parameters
    if (empty($mode) || empty($elementtype)) {
      \Drupal::messenger()->addError($this->t('Invalid parameters provided.'));
      return [];
    }

    // Additional form settings
    if ($mode === 'modal')
      $form['#attached']['library'][] = 'rep/rep_modal';

    $api = \Drupal::service('rep.api_connector');

    if ($mode == NULL || $mode == '') {
      \Drupal::messenger()->addError(t("A mode is required to inspect a concept hierarchy."));
      return [];
    }
    if ($mode != 'modal' && $mode != 'browse' && $mode != 'select') {
      \Drupal::messenger()->addError(t("A valid mode is required to inspect a concept hierarchy."));
      return [];
    }

    if ($elementtype == NULL || $elementtype == '') {
      \Drupal::messenger()->addError(t("An element type is required to inspect a concept hierarchy."));
      return [];
    }

    //$this->setElementType($elementtype);

    // Valid types
    $validTypes = [
      'annotationstem' => ["Annotation Stem", EntryPoints::ANNOTATION_STEM],
      'attribute' => ["Attribute", EntryPoints::ATTRIBUTE],
      'componentstem' => ["Component Stem", EntryPoints::COMPONENT_STEM],
      'entity' => ["Entity", EntryPoints::ENTITY],
      'group' => ["Group", EntryPoints::GROUP],
      'instrument' => ["Instrument", EntryPoints::INSTRUMENT],
      'organization' => ["Organization", EntryPoints::ORGANIZATION],
      'person' => ["Person", EntryPoints::PERSON],
      'place' => ["Place", EntryPoints::PLACE],
      'platform' => ["Platform", EntryPoints::PLATFORM],
      'processstem' => ["Process Stem", EntryPoints::PROCESS_STEM],
      'questionnaire' => ["Questionnaire", EntryPoints::QUESTIONNAIRE],
      'responseoption' => ["Response Option", EntryPoints::RESPONSE_OPTION],
      'study' => ["Study", EntryPoints::STUDY],
      'task' => ["Task Type", EntryPoints::TASK],
      'tasktemporaldependency' => ["Task Temporal Dependency", EntryPoints::TASK_TEMPORAL_DEPENDENCY],
      'unit' => ["Unit", EntryPoints::UNIT],
      'detectorattribute' => ["Detector Attribute", EntryPoints::DETECTOR_ATTRIBUTE],
      'actuatorattribute' => ["Actuator Attribute", EntryPoints::ACTUATOR_ATTRIBUTE],
      'component' => ["Component", EntryPoints::COMPONENT],
      // 'platforminstance' => ["Platform Instance", EntryPoints::PLATFORM_INSTANCE],
      // 'instrumentinstance' => ["Instrument Instance", EntryPoints::INSTRUMENT_INSTANCE],
    ];

    $branches_param = [
      [
        'id' => 'annotationstem',
        'uri' => EntryPoints::ANNOTATION_STEM,
        'label' => 'Annotation Stem',
        'uriNamespace' => EntryPoints::ANNOTATION_STEM
      ],
      [
        'id' => 'attribute',
        'uri' => EntryPoints::ATTRIBUTE,
        'label' => 'Attribute',
        'uriNamespace' => EntryPoints::ATTRIBUTE
      ],
      [
        'id' => 'componentstem',
        'uri' => EntryPoints::COMPONENT_STEM,
        'label' => 'Component Stem',
        'typeNamespace' => EntryPoints::COMPONENT_STEM,
        'uriNamespace' => EntryPoints::COMPONENT_STEM
      ],
      [
        'id' => 'component',
        'uri' => EntryPoints::COMPONENT,
        'label' => 'Component',
        'typeNamespace' => EntryPoints::COMPONENT,
        'uriNamespace' => EntryPoints::COMPONENT
      ],
      [
        'id' => 'detectorattribute',
        'uri' => EntryPoints::DETECTOR_ATTRIBUTE,
        'label' => 'Detector Attribute',
        'uriNamespace' => EntryPoints::DETECTOR_ATTRIBUTE
      ],
      [
        'id' => 'actuatorattribute',
        'uri' => EntryPoints::ACTUATOR_ATTRIBUTE,
        'label' => 'Actuator Attribute',
        'uriNamespace' => EntryPoints::ACTUATOR_ATTRIBUTE
      ],
      [
        'id' => 'entity',
        'uri' => EntryPoints::ENTITY,
        'label' => 'Entity'
      ],
      [
        'id' => 'group',
        'uri' => EntryPoints::GROUP,
        'label' => 'Group'
      ],
      [
        'id' => 'instrument',
        'uri' => EntryPoints::INSTRUMENT,
        'label' => 'Instrument',
        'uriNamespace' => Utils::namespaceUri(EntryPoints::INSTRUMENT),
      ],
      [
        'id' => 'organization',
        'uri' => EntryPoints::ORGANIZATION,
        'label' => 'Organization',
        'uriNamespace' => EntryPoints::ORGANIZATION,
      ],
      [
        'id' => 'place',
        'uri' => EntryPoints::PLACE,
        'label' => 'Place',
        'uriNamespace' => EntryPoints::PLACE,
      ],
      [
        'id' => 'platform',
        'uri' => EntryPoints::PLATFORM,
        'label' => 'Platform',
        'uriNamespace' => EntryPoints::PLATFORM
      ],
      [
        'id' => 'processstem',
        'uri' => EntryPoints::PROCESS_STEM,
        'label' => 'Process Stem',
        'uriNamespace' => EntryPoints::PROCESS_STEM
      ],
      [
        'id' => 'questionnaire',
        'uri' => EntryPoints::QUESTIONNAIRE,
        'label' => 'Questionnaire',
        'uriNamespace' => EntryPoints::QUESTIONNAIRE,
      ],
      [
        'id' => 'responseoption',
        'uri' => EntryPoints::RESPONSE_OPTION,
        'label' => 'Response Option',~
        'uriNamespace' => EntryPoints::RESPONSE_OPTION,
      ],
      [
        'id' => 'study',
        'uri' => EntryPoints::STUDY,
        'label' => 'Study',
        'uriNamespace' => EntryPoints::STUDY,
      ],
      [
        'id' => 'task',
        'uri' => EntryPoints::TASK,
        'label' => 'Task Type',
        'uriNamespace' => EntryPoints::TASK,
      ],
      [
        'id' => 'tasktemporaldependency',
        'uri' => EntryPoints::TASK_TEMPORAL_DEPENDENCY,
        'label' => 'Task Temporal Dependency',
        'uriNamespace' => EntryPoints::TASK_TEMPORAL_DEPENDENCY,
      ],
      [
        'id' => 'unit',
        'uri' => EntryPoints::UNIT,
        'label' => 'Unit',
        'uriNamespace' => EntryPoints::UNIT,
      ],
    ];

    // Divide string $elementtype into an array
    $elementtypesArray = explode(',', $elementtype);

    // Filter valid types from $validTypes array
    $validElementtypes = array_filter($elementtypesArray, function ($type) use ($validTypes) {
        return array_key_exists($type, $validTypes);
    });

    // Check if any valid type was found
    if (empty($validElementtypes)) {
        \Drupal::messenger()->addError(t("No valid element type has been provided."));
        return [];
    }

    // Prepare branches based on valid types
    $branches_param = array_values(array_filter($branches_param, function ($branch) use ($validElementtypes) {
        return in_array($branch['id'], $validElementtypes);
    }));

    // Set the primary element (optional, based on the first valid type)
    $firstType = reset($validElementtypes);
    if ($firstType && array_key_exists($firstType, $validTypes)) {
        [$elementName, $nodeUri] = $validTypes[$firstType];
    } else {
        \Drupal::messenger()->addError(t("Failed to determine the primary element type."));
        return [];
    }

    // Split $elementtype and remove spaces
    $elementtypesArray = array_map('trim', explode(',', $elementtype));

    // Filter branches based on $elementtypesArray
    $branches_param = array_filter($branches_param, function ($branch) use ($elementtypesArray) {
      return in_array($branch['id'], $elementtypesArray);
    });

    // Reindex and ensure clean array
    $branches_param = array_values($branches_param);

    // If empty, we can replace with a default
    if (empty($branches_param)) {
      $branches_param = [
        [
          'id' => 'instrument',
          'uri' => VSTOI::INSTRUMENT,
          'label' => 'Instruments',
          'uriNamespace' => Utils::namespaceUri(EntryPoints::INSTRUMENT),
        ],
      ];
    }
    //dpm($elementtype, 'Debug $elementtype');           // See which string is arriving
    //dpm($branches_param, 'Debug $branches_param');     // See the final array of branches

    // 1) Leia o valor que veio pela URL (se existir)
    $search_value = \Drupal::request()->query->get('search_value');
    // Se não vir nada, pode ficar como string vazia:
    if ($search_value === NULL) {
      $search_value = '';
    }

    // Retrieve root node
    // dpm($api->getUri($nodeUri), 'Debug $nodeUri'); // See the URI being used
    // dpm($api->parseObjectResponse($api->getUri($nodeUri), 'getUri'), 'Debug $api->parseObjectResponse'); // See the response from the API
    $this->setRootNode($api->parseObjectResponse($api->getUri($nodeUri), 'getUri'));
    if ($this->getRootNode() == NULL) {
      \Drupal::messenger()->addError(t("Failed to retrieve root node " . $nodeUri . "."));
      return [];
    }

    // If output_field_selector is not provided, use the default
    if ($output_field_selector === NULL) {
      $output_field_selector = '#edit-search-keyword--2';
    }

    $form['#attached']['library'][] = 'rep/rep_tree';

    $tables = new Tables;

    $base_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::request()->getBaseUrl();
    $form['#attached']['drupalSettings']['rep_tree'] = [
      'baseUrl' => $base_url,
      'username' => \Drupal::currentUser()->getAccountName(),
      'managerEmail' => \Drupal::currentUser()->getEmail(),
      'apiEndpoint' => $base_url . '/rep/getchildren',
      'searchSubClassEndPoint' => $base_url . '/rep/subclasskeyword',
      'searchSuperClassEndPoint' => $base_url . '/rep/getsuperclasses',
      'superclass' => $branches_param[0]["uri"],
      'branches' => $branches_param,
      'outputField' => '[name="' . \Drupal::request()->query->get('field_id') . '"]',
      'elementType' => $elementtype,
      'typeNameSpace' => $branches_param[0]["uriNamespace"],
      'hideDraft' => $hide_draft,
      'hideDeprecated' => $hide_deprecated,
      'showLabel' => $show_label,
      'nameSpacesList' => $tables->getNamespaces(),
      'searchValue' => $search_value,
      'prefix' => $prefix,
    ];

    if ($mode == 'browse')
    {
      $form['title'] = [
          '#type' => 'markup',
          '#markup' => '<h3 class="mt-4 mb-4">'.$elementName.' Graph Hierarchy</h3>',
      ];
    }

    $form['search_wrapper'] = [
      '#type' => 'container',
    ];

    $form['search_wrapper']['search_input'] = [
      '#type' => 'textfield',
      //'#title' => $this->t('Search'),
      '#placeholder' => $this->t('Search'),
      //'#autocomplete_route_name' => 'rep.get_subclasskeyword',
      '#attributes' => [
          'id' => 'search_input',
          'class' => ['mt-2', 'w-50'],
          'style' => 'float:left',
          'autocomplete' => 'off'
      ],
      '#autocomplete' => 'off'
    ];

    $form['search_wrapper']['select_node'] = [
      '#type' => 'inline_template',
      '#attributes' => [
        'id' => 'reset-tree',
        'class' => ['btn', 'btn-primary', 'mt-2'],
        'style' => 'float:right',
      ],
      '#template' => '<button type="button" id="reset-tree" class="btn btn-primary mt-2 ms-2" data-field-id="">'.t('Reset Tree').'</button>'
    ];

    $form['search_wrapper']['filters'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['d-flex', 'mt-2', 'mx-0'],
        'id' => 'edit-filters'
      ]
    ];

    $form['search_wrapper']['filters']['toggle_draft'] = [
      '#type' => 'checkbox',
      '#prefix' => '<div class="filter-label">Content to be shown:&nbsp;&nbsp;</div>',
      '#title' => $this->t('Hide Other User\'s Draft?&nbsp;&nbsp;&nbsp;'),
      '#default_value' => $hide_draft,
      // AJAX to rebuild the tree when toggled
      '#ajax' => [
        'callback' => '::toggleDraftCallback',
        'wrapper' => 'tree-wrapper',
        'method' => 'replace',
      ],
      '#attributes' => [
        'id' => 'toggle-draft',
        'class' => ['mb-2', 'me-2'],
        'title' => $this->t('Click to show/hide Another User\'s Draft elements'),
      ],
    ];

    $form['search_wrapper']['filters']['toggle_deprecated'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide Other User\'s Deprecated?&nbsp;&nbsp;&nbsp;'),
      '#default_value' => $hide_deprecated,
      '#ajax' => [
        'callback' => '::toggleDeprecatedCallback',
        'wrapper' => 'tree-wrapper',
        'method' => 'replace',
      ],
      '#attributes' => [
        'id' => 'toggle-deprecated',
        'class' => ['mb-2'],
        'title' => $this->t('Click to show/hide Another User\'s Deprecated elements'),
      ],
    ];

    $form['search_wrapper']['labels'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['d-flex', 'mt-2', 'mx-0'],
        'id' => 'edit-labels'
      ]
    ];

    $form['search_wrapper']['labels']['label_mode'] = [
      '#type' => 'radios',
      '#prefix' => '<div>Rendering mode:&nbsp;',
      '#suffix' => '</div>',
      '#options' => [
        'label' => $this->t('Just Label'),
        'labelprefix' => $this->t('Prefix:Label'),
        'uri' => $this->t('URI'),
        'uriprefix' => $this->t('Prefix:URI'),
      ],
      '#default_value' => $show_label ?? 'label',
      '#ajax' => [
        'callback' => '::toggleLabelModeCallback',
        'wrapper' => 'tree-wrapper',
        'method' => 'replace',
        'event' => 'change',
      ],
      '#attributes' => [
        'id' => 'toggle-label-mode',
        'class' => ['radios-inline'],
      ],
    ];

    $form['wait_message'] = [
      '#type' => 'markup',
      '#markup' => '<div id="wait-message" style="text-align: center; font-style: italic; color: grey; margin-top: 10px;" class="mt-3 mb-3 '.($mode == 'modal' ?? 'text-center').'">Wait please...</div>',
    ];

    $form['tree_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'tree-wrapper'],
    ];

    $form['tree_container']['tree_root'] = [
      '#type' => 'markup',
      '#markup' => '<div id="tree-root" data-initial-uri="' . $this->getRootNode()->uri . '" style="display:none;"></div>',
    ];

    $form['hide_draft'] = [
      '#type' => 'hidden',
      '#value' => $hide_draft
    ];

    if ($mode == 'modal')
    {
      $form['node_comment_display'] = [
        '#type' => 'container',
        '#text' => '',
        '#attributes' => [
            'id' => 'node-comment-display',
            'class' => ['mt-2', 'w-100'],
            'style' => 'display:none;'
            //'style' => 'float:left',
        ],
      ];
    }

    if ($mode === 'modal') {
      // Determine who opened this modal.
      $caller = \Drupal::request()->query->get('caller');

      // Base button classes.
      $button_classes = ['btn', 'btn-primary', 'mt-3', 'mb-3'];

      // Only add the automatic data-dialog-close if NOT called from the AddTaskForm.
      $auto_close = ($caller !== 'add_task_form');

      // dpm($silent, 'Debug $silent'); // Check if silent mode is set
      if ($silent === "false") {
        $form['select_node'] = [
          '#type'     => 'inline_template',
          '#template' => '
            <div style="margin-bottom: 10px;">
              <button type="button"
                      id="select-tree-node"
                      class="{{ classes|join(" ") }}"
                      data-field-id="{{ field_id }}"
                      {% if auto_close %}data-dialog-close="true"{% endif %}>
                {{ label }}
              </button>
            </div>',
          '#context'  => [
            'classes'    => $button_classes,
            'field_id'   => \Drupal::request()->query->get('field_id'),
            'auto_close' => $auto_close,
            'label'      => $this->t('Select Node'),
          ],
        ];
      }
    }

    return $form;
  }

  public function toggleDraftCallback(array &$form, FormStateInterface $form_state) {
    // Read the checkbox value directly
    $new = $form_state->getValue('toggle_draft') ? true : false;
    // Store in form state so we can use it elsewhere if needed
    $form_state->setValue('hide_draft', $new);

    // Update drupalSettings
    $form['#attached']['drupalSettings']['rep_tree']['hideDraft'] = $new;

    // Return only the part of the form that needs re-rendering
    return $form['tree_container'];
  }

  public function toggleDeprecatedCallback(array &$form, FormStateInterface $form_state) {
    $new = $form_state->getValue('toggle_deprecated') ? true : false;
    $form_state->setValue('hide_deprecated', $new);

    $form['#attached']['drupalSettings']['rep_tree']['hideDeprecated'] = $new;

    return $form['tree_container'];
  }

  public function toggleShowNameSpaceCallback(array &$form, FormStateInterface $form_state) {
    $new = $form_state->getValue('toggle_shownamespace') ? false : true;
    $form_state->setValue('show_namespace', $new);

    $form['#attached']['drupalSettings']['rep_tree']['showNameSpace'] = $new;

    return $form['tree_container'];
  }

  public function toggleShowPrefixCallback(array &$form, FormStateInterface $form_state) {
    $new = $form_state->getValue('toggle_showprefix') ? false : true;
    $form_state->setValue('show_prefix', $new);

    $form['#attached']['drupalSettings']['rep_tree']['showPrefix'] = $new;

    return $form['tree_container'];
  }

  public function toggleLabelModeCallback(array &$form, FormStateInterface $form_state) {
    // The name of the radio group is 'label_mode' in the example above
    $value = $form_state->getValue('label_mode');

    $form['#attached']['drupalSettings']['rep_tree']['showLabel'] = $value;

    return $form['tree_container'];
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    // No submission logic
  }
}
