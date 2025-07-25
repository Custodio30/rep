<?php

  namespace Drupal\rep;

  use Drupal\rep\Vocabulary\FOAF;
  use Drupal\rep\Vocabulary\HASCO;
  use Drupal\rep\Vocabulary\PROV;
  use Drupal\rep\Vocabulary\SCHEMA;
  use Drupal\rep\Vocabulary\SIO;
  use Drupal\rep\Vocabulary\VSTOI;
use Drupal\sir\Entity\Task;

  class EntryPoints {

    const ANNOTATION_STEM           = VSTOI::ANNOTATION_STEM;
    const ATTRIBUTE                 = SIO::ATTRIBUTE;
    const ACTUATOR_ATTRIBUTE        = "http://purl.obolibrary.org/obo/UBERON_0000061"; // ITS MISSING THIS VALUE
    const CODEBOOK                  = VSTOI::CODEBOOK;
    const DETECTOR_ATTRIBUTE        = "http://purl.obolibrary.org/obo/UBERON_0000061";
    const COMPONENT_STEM            = VSTOI::COMPONENT_STEM;
    const COMPONENT                 = VSTOI::COMPONENT;
    const ENTITY                    = SIO::ENTITY;
    const GROUP                     = FOAF::GROUP;
    const INSTRUMENT                = VSTOI::INSTRUMENT;
    const ORGANIZATION              = SCHEMA::ORGANIZATION;
    const PERSON                    = SCHEMA::PERSON;
    const PLACE                     = SCHEMA::PLACE;
    const PLATFORM                  = VSTOI::PLATFORM;
    const PROCESS_STEM              = VSTOI::PROCESS_STEM;
    const QUESTIONNAIRE             = VSTOI::QUESTIONNAIRE;
    const RESPONSE_OPTION           = VSTOI::RESPONSE_OPTION;
    const STUDY                     = HASCO::STUDY;
    const TASK                      = VSTOI::TASK;
    const TASK_TEMPORAL_DEPENDENCY  = VSTOI::TASK_TEMPORAL_DEPENDENCY;
    const UNIT                      = SIO::UNIT;

  }
