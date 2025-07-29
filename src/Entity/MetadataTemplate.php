<?php

namespace Drupal\rep\Entity;

use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\rep\Vocabulary\HASCO;
use Drupal\rep\Vocabulary\REPGUI;
use Drupal\rep\Entity\DataFile;
use Drupal\rep\Constant;
use Drupal\rep\Utils;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;


class MetadataTemplate
{

  protected $preservedMT;

  protected $preservedDF;

  // Constructor
  public function __construct() {}

  public function getPreservedMT()
  {
    return $this->preservedMT;
  }

  public function setPreservedMT($mt)
  {
    if ($this->preservedMT == NULL) {
      $this->preservedMT = new MetadataTemplate();
    }
    $this->preservedMT->uri = $mt->uri;
    $this->preservedMT->label = $mt->label;
    $this->preservedMT->typeUri = $mt->typeUri;
    $this->preservedMT->hascoTypeUri = $mt->hascoTypeUri;
    $this->preservedMT->hasDataFileUri = $mt->hasDataFileUri;
    $this->preservedMT->comment = $mt->comment;
    $this->preservedMT->hasSIRManagerEmail = $mt->hasSIRManagerEmail;
  }

  public function getPreservedDF()
  {
    return $this->preservedDF;
  }

  public function setPreservedDF($df)
  {
    if ($this->preservedDF == NULL) {
      $this->preservedDF = new DataFile();
    }
    $this->preservedDF->uri = $df->uri;
    $this->preservedDF->label = $df->label;
    $this->preservedDF->filename = $df->filename;
    $this->preservedDF->id = $df->id;
    $this->preservedDF->fileStatus = $df->fileStatus;
    $this->preservedDF->hasSIRManagerEmail = $df->hasSIRManagerEmail;
  }

  public static function generateHeader()
  {

    return $header = [
      'element_uri' => t('URI'),
      'element_name' => t('Name'),
      'element_filename' => t('FileName'),
      'element_status' => t('Status'),
      'element_log' => t('Log'),
      'element_download' => t('Download'),
    ];
  }

  public static function generateHeaderCompact()
  {

    return $header = [
      'element_filename' => t('FileName'),
      // 'element_stream' => t('Stream'),
      // 'element_status' => t('Status'),
      'element_log' => t('Log'),
      'element_operations' => t('Operations'),
    ];
  }

  public static function generateStreamHeader($streamType)
  {

    $header = [];

    $header['element_filename'] = t('FileName');

    if ($streamType == 'files') {
      $header['element_messages_datapoints'] = t('Data Points');
    } else {
      $header['element_messages_recorded_messages'] = t('Recorded Messages');
      $header['element_messages_data_points'] = t('Data Points');
    }

    $header['element_status'] = t('Status');
    $header['element_log'] = t('Log');
    $header['element_operations'] = t('Operations');

    return $header;
  }

  public static function generateOutput($elementType, $list)
  {
    return MetadataTemplate::generateOutputWithMode($elementType, $list, 'normal');
  }

  public static function generateOutputCompact($elementType, $list)
  {
    return MetadataTemplate::generateOutputWithMode($elementType, $list, 'compact');
  }

  public static function generateStreamOutputCompact($streamType, $list)
  {
    return MetadataTemplate::generateStreamOutputWithMode($streamType, $list);
  }

  private static function generateOutputWithMode($elementType, $list, $mode)
  {

    $useremail = \Drupal::currentUser()->getEmail();

    // ROOT URL
    $root_url = \Drupal::request()->getBaseUrl();

    $output = array();
    if ($list == NULL) {
      return $output;
    }

    foreach ($list as $element) {

      $uri = ' ';
      if ($element->uri != NULL) {
        $uri = $element->uri;
      }
      $uri = Utils::namespaceUri($uri);
      $label = ' ';
      if ($element->label != NULL) {
        $label = $element->label;
      }
      $name = ' ';
      if ($element->label != NULL && $element->label != '') {
        $name = $element->label;
      }
      $filename = ' ';
      $filestatus = ' ';
      $log = ' ';
      $download = ' ';
      $root_url = \Drupal::request()->getBaseUrl();
      if ($element->hasDataFile != NULL) {

        // RETRIEVE DATAFILE BY URI
        //$api = \Drupal::service('rep.api_connector');
        //$dataFile = $api->parseObjectResponse($api->getUri($element->hasDataFile),'getUri');

        if (
          $element->hasDataFile->filename != NULL &&
          $element->hasDataFile->filename != ''
        ) {
          $filename = $element->hasDataFile->filename;
        }
        if (
          $element->hasDataFile->fileStatus != NULL &&
          $element->hasDataFile->fileStatus != ''
        ) {
          if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED && $element->streamUri == NULL) {
            $filestatus = '<b><font style="color:#000000;">' . Constant::FILE_STATUS_UNPROCESSED . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED) {
            $filestatus = '<b><font style="color:#ff0000;">' . Constant::FILE_STATUS_UNPROCESSED . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED) {
            $filestatus = '<b><font style="color:#008000;">' . Constant::FILE_STATUS_PROCESSED . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_WORKING) {
            $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_WORKING . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED_STD) {
            $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_PROCESSED_STD . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_WORKING_STD) {
            $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_WORKING_STD . '</font></b>';
          } else {
            $filestatus = ' ';
          }
        }
        if (isset($element->hasDataFile->log) && $element->hasDataFile->log != NULL) {
          $link = $root_url . REPGUI::DATAFILE_LOG . base64_encode($element->hasDataFile->uri);
          $log = '<a href="' . $link . '" class="use-ajax btn btn-primary btn-sm read-button" ' .
            'data-dialog-type="modal" ' .
            'data-dialog-options=\'{"width": 700}\' role="button">Read</a>';

          //$log = '<a href="'.$link.'" class="btn btn-primary btn-sm" role="button">Read</a>';
        }
        $downloadLink = '';
        if ($element->hasDataFile->id != NULL && $element->hasDataFile->id != '') {
          $file_entity = \Drupal\file\Entity\File::load($element->hasDataFile->id);
          if ($file_entity != NULL) {
            $downloadLink = base64_encode($element->hasDataFile->uri);
            $download = '<a href="#" data-view-url="' . $downloadLink . '" class="btn btn-primary btn-sm download-button" role="button" disabled>Get It</a>';
          }
        }
      }

      // STREAM RELATED
      // dpm($element->streamUri);
      if ($element->streamUri !== null) {
        $stream = array();
        $api = \Drupal::service('rep.api_connector');
        $strRawResponse = $api->getUri($element->streamUri);
        $strObj = json_decode($strRawResponse);
        if ($strObj->isSuccessful) {
          $stream = $strObj->body;
          // dpm($stream);
        } else {
          \Drupal::messenger()->addError(t("Failed to retrieve Stream."));
          return;
        }
      }

      if ($mode == 'normal') {
        $output[$element->uri] = [
          'element_uri' => t('<a href="' . $root_url . REPGUI::DESCRIBE_PAGE . base64_encode($uri) . '">' . $uri . '</a>'),
          'element_name' => t($label),
          'element_filename' => $filename,
          'element_status' => t($filestatus),
          'element_log' => t($log),
          'element_download' => t($download),
        ];
      } else {

        $previousUrl = base64_encode(Url::fromRoute('std.manage_study_elements', [
          'studyuri' => base64_encode($element->isMemberOf->uri),
        ])->toString());

        $view_da_str = base64_encode(Url::fromRoute('rep.describe_element', ['elementuri' => base64_encode($element->uri)])->toString());
        $view_da_route = 'rep.describe_element';
        $view_da = Url::fromRoute('rep.back_url', [
          'previousurl' => $previousUrl,
          'currenturl' => $view_da_str,
          'currentroute' => 'rep.describe_element'
        ]);

        $ingest_da = Url::fromRoute('dpl.fileIngest', [
          'elementuri' => base64_encode($element->uri),
          'currenturl' => $previousUrl,
        ]);

        $uningest_da = Url::fromRoute('dpl.fileUningest', [
          'elementuri' => base64_encode($element->uri),
          'currenturl' => $previousUrl,
        ]);

        $edit_da_str = base64_encode(Url::fromRoute('rep.edit_mt', [
          'elementtype' => 'da',
          'elementuri' => base64_encode($element->uri),
          'fixstd' => 'T',
        ])->toString());
        $edit_da = Url::fromRoute('rep.back_url', [
          'previousurl' => $previousUrl,
          'currenturl' => $edit_da_str,
          'currentroute' => 'rep.edit_mt'
        ]);

        $delete_da = Url::fromRoute('rep.delete_element', [
          'elementtype' => 'da',
          'elementuri' => base64_encode($element->uri),
          'currenturl' => $previousUrl,
        ]);

        $view_da = $view_da instanceof Url ? $view_da : Url::fromRoute('<nolink>');
        $edit_da = $edit_da instanceof Url ? $edit_da : Url::fromRoute('<nolink>');
        $ingest_da = $ingest_da instanceof Url ? $ingest_da : Url::fromRoute('<nolink>');
        $uningest_da = $uningest_da instanceof Url ? $uningest_da : Url::fromRoute('<nolink>');
        $delete_da = $delete_da instanceof Url ? $delete_da : Url::fromRoute('<nolink>');
        $download_da = $root_url . '/std/download-file/' . base64_encode($element->hasDataFile->filename) . '/' . base64_encode($element->isMemberOf->uri) . '/da';

        $view_bto = Link::fromTextAndUrl(
          Markup::create('<i class="fa-solid fa-eye"></i>'),
          $view_da
        )->toRenderable();
        $view_bto['#attributes'] = [
          'class' => ['btn', 'btn-sm', 'btn-secondary', 'me-1'],
        ];

        if ($element->hasSIRManagerEmail === $useremail) {
          $edit_bto = Link::fromTextAndUrl(
            Markup::create('<i class="fa-solid fa-pen-to-square"></i>'),
            $edit_da
          )->toRenderable();
          $edit_bto['#attributes'] = [
            'class' => ['btn', 'btn-sm', 'btn-secondary', 'me-1'],
          ];
        }

        // Delete button
        $data_url = $delete_da instanceof Url ? $delete_da->toString() : '#';

        if ($element->hasSIRManagerEmail === $useremail) {
          $delete_bto = [
            '#markup' => Markup::create('<a href="#" title="Delete file" class="btn btn-sm btn-secondary btn-danger delete-button"
              data-url="' . $data_url . '"
              onclick="return false;">
              <i class="fa-solid fa-trash-can"></i>
              </a>'),
          ];

          // Build the ingest link render array.
          $encoded = base64_encode($element->uri);
          $ingest_bto = [
            '#markup' => Markup::create('
              <button
                type="button"
                '.($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED ? 'disabled' : '').'
                class="btn btn-sm btn-secondary ingest-button"
                data-elementuri="' . $encoded . '"
                title="' . t('Ingest the file') . '">
                <i class="fa-solid fa-down-long"></i>
              </button>
            '),
          ];

          // Build the uningest button
          $uningest_bto = [
            '#markup' => Markup::create('
              <button
                type="button"
                '.($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED ? 'disabled' : '').'
                class="btn btn-sm btn-secondary uningest-button"
                data-elementuri="' . $encoded . '"
                title="' . t('Uningest the file') . '">
                <i class="fa-solid fa-up-long"></i>
              </button>
            '),
          ];
        }

        $download_bto = [
          '#type' => 'link',
          '#title' => Markup::create('<i class="fa-solid fa-download"></i>'),
          '#url' => Url::fromUserInput("#", ['attributes' => ['data-download-url' => $download_da]]),
          '#attributes' => [
            'title' => t('Download file'),
            'class' => ['btn', 'btn-sm', 'btn-secondary', 'download-unassociated-url'],
          ],
        ];

        $links = [
          // \Drupal::service('renderer')->render($view_bto),
          // \Drupal::service('renderer')->render($edit_bto),
          \Drupal::service('renderer')->render($ingest_bto),
          \Drupal::service('renderer')->render($uningest_bto),
          \Drupal::service('renderer')->render($download_bto),
          \Drupal::service('renderer')->render($delete_bto),
        ];

        $output[$element->uri] = [
          'element_filename' => t('<span style="display: inline-block; white-space: normal; overflow-wrap: anywhere; word-break: break-all;">'.$filename.'</span>'),
          // 'element_stream' => t('<span style="display: inline-block; max-width: 30ch; white-space: normal; overflow-wrap: anywhere; word-break: break-all;">' . (isset($stream) ? $stream->datasetPattern : '-') . '</span>'),
          // 'element_status' => t($filestatus),
          'element_log' => t($log),
          'element_operations' => implode(' ', $links), // Concatenar links com espaço entre eles
        ];
      }
    }

    return $output;
  }

  private static function generateStreamOutputWithMode($streamType, $list)
  {

    $useremail = \Drupal::currentUser()->getEmail();

    // ROOT URL
    $root_url = \Drupal::request()->getBaseUrl();

    $output = array();
    if ($list == NULL) {
      return $output;
    }

    $api = \Drupal::service('rep.api_connector');
    foreach ($list as $element) {

      // IF STREAM MESSAGE no operations can be made if topic is recording
      $bto_active = true;
      if ($element->method !== 'files' && $element->hasDataFile->streamTopicUri !== NULL && $element->hasDataFile->streamTopicUri !== '') {
        $streamTopic = $api->parseObjectResponse(
          $api->getUri($element->hasDataFile->streamTopicUri),
          'getUri'
        ) ?? NULL;

        if ($streamTopic !== NULL && $streamTopic->hasTopicStatus === HASCO::RECORDING)
            $bto_active = false;
      }

      $uri = ' ';
      if ($element->uri != NULL) {
        $uri = $element->uri;
      }
      $uri = Utils::namespaceUri($uri);
      $label = ' ';
      if ($element->label != NULL) {
        $label = $element->label;
      }
      $name = ' ';
      if ($element->label != NULL && $element->label != '') {
        $name = $element->label;
      }
      $filename = ' ';
      $filestatus = ' ';
      $log = ' ';
      $download = ' ';
      $root_url = \Drupal::request()->getBaseUrl();
      if ($element->hasDataFile != NULL) {

        // RETRIEVE DATAFILE BY URI
        //$api = \Drupal::service('rep.api_connector');
        //$dataFile = $api->parseObjectResponse($api->getUri($element->hasDataFile),'getUri');

        if (
          $element->hasDataFile->filename != NULL &&
          $element->hasDataFile->filename != ''
        ) {
          $filename = $element->hasDataFile->filename;
        }
        if (
          $element->hasDataFile->fileStatus != NULL &&
          $element->hasDataFile->fileStatus != ''
        ) {
          if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED && $element->streamUri == NULL) {
            $filestatus = '<b><font style="color:#000000;">' . Constant::FILE_STATUS_UNPROCESSED . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED) {
            $filestatus = '<b><font style="color:#ff0000;">' . Constant::FILE_STATUS_UNPROCESSED . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED) {
            $filestatus = '<b><font style="color:#008000;">' . Constant::FILE_STATUS_PROCESSED . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_WORKING) {
            $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_WORKING . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED_STD) {
            $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_PROCESSED_STD . '</font></b>';
          } else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_WORKING_STD) {
            $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_WORKING_STD . '</font></b>';
          } else {
            $filestatus = ' ';
          }
        }
        if (isset($element->hasDataFile->log) && $element->hasDataFile->log != NULL) {
          $link = $root_url . REPGUI::DATAFILE_LOG . base64_encode($element->hasDataFile->uri);
          $log = '<a href="' . $link . '" class="use-ajax btn btn-primary btn-sm read-button" ' .
            'data-dialog-type="modal" ' .
            'data-dialog-options=\'{"width": 700}\' role="button">Read</a>';
        }
        $downloadLink = '';
        if ($element->hasDataFile->id != NULL && $element->hasDataFile->id != '') {
          $file_entity = \Drupal\file\Entity\File::load($element->hasDataFile->id);
          if ($file_entity != NULL) {
            $downloadLink = base64_encode($element->hasDataFile->uri);
            $download = '<a href="#" data-view-url="' . $downloadLink . '" class="btn btn-primary btn-sm download-button" role="button" disabled>Get It</a>';
          }
        }
      }

      // STREAM RELATED
      // dpm($element->streamUri);
      if ($element->streamUri !== null) {
        $stream = array();
        $strRawResponse = $api->getUri($element->streamUri);
        $strObj = json_decode($strRawResponse);
        if ($strObj->isSuccessful) {
          $stream = $strObj->body;
          // dpm($stream);
        } else {
          \Drupal::messenger()->addError(t("Failed to retrieve Stream."));
          return;
        }
      }

      $previousUrl = base64_encode(Url::fromRoute('std.manage_study_elements', [
        'studyuri' => base64_encode($element->isMemberOf->uri),
      ])->toString());

      $view_da_str = base64_encode(Url::fromRoute('rep.describe_element', ['elementuri' => base64_encode($element->uri)])->toString());
      $view_da_route = 'rep.describe_element';
      $view_da = Url::fromRoute('rep.back_url', [
        'previousurl' => $previousUrl,
        'currenturl' => $view_da_str,
        'currentroute' => 'rep.describe_element'
      ]);

      $edit_da_str = base64_encode(Url::fromRoute('rep.edit_mt', [
        'elementtype' => 'da',
        'elementuri' => base64_encode($element->uri),
        'fixstd' => 'T',
      ])->toString());
      $edit_da = Url::fromRoute('rep.back_url', [
        'previousurl' => $previousUrl,
        'currenturl' => $edit_da_str,
        'currentroute' => 'rep.edit_mt'
      ]);

      $delete_da = Url::fromRoute('rep.delete_element', [
        'elementtype' => 'da',
        'elementuri' => base64_encode($element->uri),
        'currenturl' => $previousUrl,
      ]);

      $view_da = $view_da instanceof Url ? $view_da : Url::fromRoute('<nolink>');
      $edit_da = $edit_da instanceof Url ? $edit_da : Url::fromRoute('<nolink>');
      $delete_da = $delete_da instanceof Url ? $delete_da : Url::fromRoute('<nolink>');
      $download_da = $root_url . '/std/download-file/' . base64_encode($element->hasDataFile->filename) . '/' . base64_encode($element->isMemberOf->uri) . '/da';

      $view_bto = Link::fromTextAndUrl(
        Markup::create('<i class="fa-solid fa-eye"></i>'),
        $view_da
      )->toRenderable();
      $view_bto['#attributes'] = [
        'class' => ['btn', 'btn-sm', 'btn-secondary', 'me-1'],
      ];

      if ($element->hasSIRManagerEmail === $useremail) {
        $edit_bto = Link::fromTextAndUrl(
          Markup::create('<i class="fa-solid fa-pen-to-square"></i>'),
          $edit_da
        )->toRenderable();
        $edit_bto['#attributes'] = [
          'class' => ['btn', 'btn-sm', 'btn-secondary', 'me-1'],
        ];
      }

      // Delete button
      $data_url = $delete_da instanceof Url ? $delete_da->toString() : '#';

      if ($element->hasSIRManagerEmail === $useremail) {
        $delete_bto = [
          '#markup' => Markup::create('<a href="#" title="Delete Stream Data file" class="btn btn-sm btn-secondary btn-danger delete-stream-file-button"
            data-url="' . $data_url . '"
            onclick="return false;">
            <i class="fa-solid fa-trash-can"></i>
            </a>'),
        ];

        $uningest_bto = Link::fromTextAndUrl(
          Markup::create('<i class="fa-solid fa-upload"></i>'),
          $view_da
        )->toRenderable();
        $uningest_bto['#attributes'] = [
          'class' => ['btn', 'btn-sm', 'me-1', 'btn-secondary', ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED ? 'disabled' : ''), (!$bto_active ? 'disabled' : '')],
        ];
      }

      // Build the ingest link render array.
      $encoded = base64_encode($element->uri);
      $ingest_bto = [
        '#markup' => Markup::create('
          <button
            type="button"
            '.($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED ? 'disabled' : '').'
            class="btn btn-sm btn-secondary ingest-button '.(!$bto_active ? 'disabled' : '').'"
            data-elementuri="' . $encoded . '"
            title="' . t('Ingest the file') . '">
            <i class="fa-solid fa-down-long"></i>
          </button>
        '),
      ];

      // Build the uningest button
      $uningest_bto = [
        '#markup' => Markup::create('
          <button
            type="button"
            '.($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED ? 'disabled' : '').'
            class="btn btn-sm btn-secondary uningest-button '.(!$bto_active ? 'disabled' : '').'"
            data-elementuri="' . $encoded . '"
            title="' . t('Uningest the file') . '">
            <i class="fa-solid fa-up-long"></i>
          </button>
        '),
      ];

      $download_bto = [
        '#type' => 'link',
        '#title' => Markup::create('<i class="fa-solid fa-download"></i>'),
        '#url' => Url::fromUserInput('#', [
          'attributes' => [
            'class' => [(!$bto_active ? 'disabled' : '')],
            'title' => t('Download file'),
            'data-download-url' => $download_da,
          ],
        ]),
        '#attributes' => [
          'class' => ['btn', 'btn-sm', 'btn-secondary', 'download-associated-url'],
        ],
      ];

      $links = [
        // \Drupal::service('renderer')->render($view_bto),
        // \Drupal::service('renderer')->render($edit_bto),
        \Drupal::service('renderer')->render($ingest_bto),
        \Drupal::service('renderer')->render($uningest_bto),
        \Drupal::service('renderer')->render($download_bto),
        \Drupal::service('renderer')->render($delete_bto),
      ];

      // dpm($element);
      $output[$element->uri] = [];
      $output[$element->uri]['element_filename'] = t('<span style="display: inline-block; white-space: normal; overflow-wrap: anywhere; word-break: break-all;">'.$filename.'</span>');
      if ($streamType == 'files') {
        $output[$element->uri]['element_messages_datapoints'] = isset($element->hasNumberDataPoints) ? $element->hasNumberDataPoints : 0;
      } else {
        $output[$element->uri]['element_messages_recorded_messages'] = isset($element->hasTotalRecordedMessages) ? $element->hasTotalRecordedMessages : 0;
        $output[$element->uri]['element_messages_data_points'] = isset($element->hasNumberDataPoints ) ? $element->hasNumberDataPoints  : 0;
      }

      $output[$element->uri]['element_status'] = t($filestatus);
      $output[$element->uri]['element_log'] = t($log);
      $output[$element->uri]['element_operations'] = Markup::create( implode(' ', $links) ); // Concatenar links com espaço entre eles
    }

    return $output;
  }

  public function savePreservedMT($elementType)
  {

    if ($this->getPreservedMT() == NULL || $this->getPreservedDF() == NULL) {
      return FALSE;
    }

    try {
      $datafileJSON = '{"uri":"' . $this->getPreservedDF()->uri . '",' .
        '"typeUri":"' . HASCO::DATAFILE . '",' .
        '"hascoTypeUri":"' . HASCO::DATAFILE . '",' .
        '"label":"' . $this->getPreservedDF()->label . '",' .
        '"filename":"' . $this->getPreservedDF()->filename . '",' .
        '"id":"' . $this->getPreservedDF()->id . '",' .
        '"fileStatus":"' . Constant::FILE_STATUS_UNPROCESSED . '",' .
        '"hasSIRManagerEmail":"' . $this->getPreservedDF()->hasSIRManagerEmail . '"}';

      $mtJSON = '{"uri":"' . $this->getPreservedMT()->uri . '",' .
        '"typeUri":"' . $this->getPreservedMT()->typeUri . '",' .
        '"hascoTypeUri":"' . $this->getPreservedMT()->hascoTypeUri . '",' .
        '"label":"' . $this->getPreservedMT()->label . '",' .
        '"hasDataFileUri":"' . $this->getPreservedMT()->hasDataFileUri . '",' .
        '"comment":"' . $this->getPreservedMT()->comment . '",' .
        '"hasSIRManagerEmail":"' . $this->getPreservedMT()->hasSIRManagerEmail . '"}';

      $api = \Drupal::service('rep.api_connector');

      // ADD DATAFILE
      $msg1 = NULL;
      $msg2 = NULL;
      $dfRaw = $api->datafileAdd($datafileJSON);
      if ($dfRaw != NULL) {
        $msg1 = $api->parseObjectResponse($dfRaw, 'datafileAdd');

        // ADD MT
        $mtRaw = $api->elementAdd($elementType, $mtJSON);
        if ($mtRaw != NULL) {
          $msg2 = $api->parseObjectResponse($mtRaw, 'elementAdd');
        }
      }

      if ($msg1 != NULL && $msg2 != NULL) {
        return TRUE;
      } else {
        return FALSE;
      }
    } catch (\Exception $e) {
    }
  }

  public static function generateOutputAsCards($elementType, $list) {

    $useremail = \Drupal::currentUser()->getEmail();

    $edit_da = NULL;
    $delete_da = NULL;
    $download_da = NULL;
    $ingest_da = NULL;
    $uningest_da = NULL;

    $cards = [];

    // Return an empty array if the list is empty.
    if (empty($list)) {
      return [];
    }

    // Get the current request URL for use in links.
    $previousUrl = base64_encode(\Drupal::request()->getRequestUri());

    // Process each element in the list.
    foreach ($list as $index => $element) {
      // Basic values.
      $uri = isset($element->uri) ? $element->uri : '';
      $label = $element->label ?? '';
      $title = $element->title ?? '';

      // Determine the URL if the URI is complete.
      $url = '';
      $urlComponents = parse_url($uri);
      if (isset($urlComponents['scheme']) && isset($urlComponents['host'])) {
        $url = Url::fromUri($uri);
      }

      // Process file data.
      $filename = '';
      if (isset($element->hasDataFile) && !empty($element->hasDataFile->filename)) {
        $filename = $element->hasDataFile->filename;
      }
      $filestatus = '';
      if (isset($element->hasDataFile) && !empty($element->hasDataFile->fileStatus)) {
        if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_UNPROCESSED) {
          $filestatus = '<b><font style="color:#ff0000;">' . Constant::FILE_STATUS_UNPROCESSED . '</font></b>';
        }
        else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED) {
          $filestatus = '<b><font style="color:#008000;">' . Constant::FILE_STATUS_PROCESSED . '</font></b>';
        }
        else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_WORKING) {
          $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_WORKING . '</font></b>';
        }
        else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_PROCESSED_STD) {
          $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_PROCESSED_STD . '</font></b>';
        }
        else if ($element->hasDataFile->fileStatus == Constant::FILE_STATUS_WORKING_STD) {
          $filestatus = '<b><font style="color:#ffA500;">' . Constant::FILE_STATUS_WORKING_STD . '</font></b>';
        }
        else {
          $filestatus = ' ';
        }
      }

      // Documentation data.
      $dd = '(none)';
      if (isset($element->hasDD) && $element->hasDD != NULL) {
        $dd = $element->hasDD->label . ' (' . $element->hasDD->hasDataFile->filename . ') [<b>' . $element->hasDD->hasDataFile->fileStatus . '</b>] ';
      }
      $sdd = '(none)';
      if (isset($element->hasSDD) && $element->hasSDD != NULL) {
        $sdd = $element->hasSDD->label . ' (' . $element->hasSDD->hasDataFile->filename . ') [<b>' . $element->hasSDD->hasDataFile->fileStatus . '</b>] ';
      }

      if (is_string($uri) && !empty($uri)) {
        $url = Url::fromUserInput(REPGUI::DESCRIBE_PAGE . base64_encode($uri));
        $url->setOption('attributes', ['target' => '_new']);
        $link = Link::fromTextAndUrl($uri, $url)->toString();
      }

      // Build the properties string based on the element type.
      if ($elementType == 'da') {
        $properties = t('<p class="card-text">' .
          '<b>URI</b>: ' . $link . '<br>' .
          '<b>File Name</b>: ' . $filename . ' [' . $filestatus . ']<br><br>' .
          'Documentation: <br>' .
          '<b>Data Dictionary</b>: ' . $dd . '<br>' .
          '<b>Semantic Data Dictionary</b>: ' . $sdd . '<br>' .
          '</p>');
      }
      else {
        $properties = t('<p class="card-text">' .
          '<b>URI</b>: ' . $link . '<br>' .
          '<b>File Name</b>: ' . $filename . ' (' . $filestatus . ')<br>' .
          '</p>');
      }

      // Generate action links.
      // Link for View.
      $view_da_str = base64_encode(Url::fromRoute('rep.describe_element', ['elementuri' => base64_encode($uri)])->toString());
      $view_da = Url::fromRoute('rep.back_url', [
        'previousurl' => $previousUrl,
        'currenturl' => $view_da_str,
        'currentroute' => 'rep.describe_element'
      ]);

      // Link for Edit.
      // if ($element->hasSIRManagerEmail === $useremail) {
      //   $edit_da_str = base64_encode(Url::fromRoute('rep.edit_mt', [
      //     'elementtype' => 'da',
      //     'elementuri' => base64_encode($uri),
      //     'fixstd' => 'T',
      //   ])->toString());
      //   $edit_da = Url::fromRoute('rep.back_url', [
      //     'previousurl' => $previousUrl,
      //     'currenturl' => $edit_da_str,
      //     'currentroute' => 'rep.edit_mt'
      //   ]);
      // }

      // Link for Delete.
      // if ($element->hasSIRManagerEmail === $useremail) {
      //   $delete_da = Url::fromRoute('rep.delete_element', [
      //     'elementtype' => 'da',
      //     'elementuri' => base64_encode($uri),
      //     'currenturl' => $previousUrl,
      //   ]);
      // }

      // Link for Download.
      $download_da = Url::fromRoute('rep.datafile_download', [
        'datafileuri' => isset($element->hasDataFile) ? base64_encode($element->hasDataFile->uri) : '',
      ]);

      // Create the card outer container.
      $card = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['col-md-6'],
          'style' => 'margin-bottom: 1.5rem!important;',
          'id' => 'card-item-' . md5($uri),
        ],
      ];

      // Card inner container.
      $card['card'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['card', 'mb-3']],
      ];

      // Card header.
      $card['card']['header'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['card-header', 'mb-0'],
          'style' => 'margin-bottom:0!important;',
        ],
        '#markup' => '<h5>' . $label . '</h5>',
      ];

      // Set the image: if an image exists in the element, use it; otherwise, use a placeholder.
      if (!empty($element->hasImageUri)) {
        $image_uri = $element->hasImageUri;
      }
      else {
        // $image_uri = base_path() . \Drupal::service('extension.list.module')->getPath('rep') . '/images/std_placeholder.png';
        $image_uri = Utils::getAPIImage($uri, $element->hasImageUri, UTILS::placeholderImage($element->hascoTypeUri,'study'));
      }

      // Card body: divided into a row with a single column for details.
      $card['card']['body'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['card-body', 'mb-0'],
          'style' => 'margin-bottom:0!important;',
        ],
        'row' => [
          '#type' => 'container',
          '#attributes' => [
            'class' => ['row'],
            'style' => 'margin-bottom:0!important;',
          ],
          // Left column: image.
          'image_column' => [
            '#type' => 'container',
            '#attributes' => [
              'class' => ['col-md-5', 'd-flex', 'justify-content-center', 'align-items-center'],
              'style' => 'margin-bottom:0!important;',
            ],
            'image' => [
              '#theme' => 'image',
              '#uri' => $image_uri,
              '#alt' => t('Image for @name', ['@name' => $title]),
              '#attributes' => [
                'class' => ['img-fluid', 'mb-0', 'border', 'border-5', 'rounded', 'rounded-5'],
                // 'style' => 'width: 70%',
              ],
            ],
          ],
          // Details column.
          'text_column' => [
            '#type' => 'container',
            '#attributes' => [
              'class' => ['col-md-7'],
              'style' => 'margin-bottom:0!important;',
            ],
            'text' => [
              '#markup' => $properties,
            ],
          ],
        ],
      ];

      // Card footer: action buttons.
      $card['card']['footer'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['card-footer', 'text-right', 'd-flex', 'justify-content-end'],
          'style' => 'margin-bottom:0!important;',
        ],
        'actions' => [
          'link1' => [
            '#type' => 'link',
            '#title' => Markup::create('<i class="fa-solid fa-eye"></i> View'),
            '#url' => $view_da,
            '#attributes' => [
              'class' => ['btn', 'btn-sm', 'btn-secondary', 'mx-1'],
              'target' => '_new',
            ],
          ],
          'link2' => [
            '#type' => 'link',
            '#title' => Markup::create('<i class="fa-solid fa-pen-to-square"></i> Edit'),
            '#url' => $edit_da,
            '#access' => !is_null($edit_da), // Hide if not set
            '#attributes' => [
              'class' => ['btn', 'btn-sm', 'btn-secondary', 'mx-1'],
            ],
          ],
          'link3' => [
            '#type' => 'link',
            '#title' => Markup::create('<i class="fa-solid fa-trash-can"></i> Delete'),
            '#url' => $delete_da,
            '#access' => !is_null($delete_da), // Hide if not set
            '#attributes' => [
              'class' => ['btn', 'btn-sm', 'btn-secondary', 'btn-danger', 'mx-1'],
              'onclick' => 'if(!confirm("Really Delete?")){return false;}',
            ],
          ],
          'link4' => [
            '#type' => 'link',
            '#title' => Markup::create('<i class="fa-solid fa-download"></i> Download'),
            '#url' => $download_da,
            '#attributes' => [
              'title' => t('Download file'),
              'class' => ['btn', 'btn-sm', 'btn-secondary', 'mx-1'],
              'onclick' => 'if(!confirm("Really Download?")){return false;}',
            ],
          ],
          'link5' => [
            '#type' => 'link',
            '#title' => Markup::create('<i class="fa-solid fa-arrow-down"></i> Ingest'),
            '#url' => $view_da,
            '#access' => !is_null($ingest_da), // Hide if not set
            '#attributes' => [
              'class' => ['btn', 'btn-sm', 'btn-secondary', 'disabled', 'mx-1'],
            ],
          ],
          'link6' => [
            '#type' => 'link',
            '#title' => Markup::create('<i class="fa-solid fa-arrow-up"></i> Uningest'),
            '#url' => $view_da,
            '#access' => !is_null($uningest_da), // Hide if not set
            '#attributes' => [
              'class' => ['btn', 'btn-sm', 'btn-secondary', 'disabled', 'mx-1'],
            ],
          ],
        ],
      ];

      // Add the card to the cards array.
      $cards[] = $card;
    }

    // Group the cards into rows (3 cards per row).
    $output = [];
    $output[] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['row', 'mb-0'],
        ],
        'cards' => $cards,
      ];
    // foreach (array_chunk($cards, 3) as $row) {
    //   $output[] = [
    //     '#type' => 'container',
    //     '#attributes' => [
    //       'class' => ['row', 'mb-0'],
    //     ],
    //     'cards' => $row,
    //   ];
    // }

    return $output;
  }

}
