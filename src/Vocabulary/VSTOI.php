<?php

  namespace Drupal\rep\Vocabulary;

use Drupal\sir\Entity\Instrument;

  class VSTOI {

    const VSTOI                           = "http://hadatac.org/ont/vstoi#";

    /*
     *    CLASSES
     */
    const ANNOTATION                      = VSTOI::VSTOI . "Annotation";
    const ANNOTATION_STEM                 = VSTOI::VSTOI . "AnnotationStem";
    const CODEBOOK                        = VSTOI::VSTOI . "Codebook";
    const CONTAINER                       = VSTOI::VSTOI . "Container";
    const CONTAINER_SLOT                  = VSTOI::VSTOI . "ContainerSlot";
    const COMPONENT                       = VSTOI::VSTOI . "Component";
    const COMPONENT_INSTANCE              = VSTOI::VSTOI . "ComponentInstance";
    const COMPONENT_STEM                  = VSTOI::VSTOI . "ComponentStem";
    const DEPLOYMENT                      = VSTOI::VSTOI . "Deployment";
    const INSTANCE                        = VSTOI::VSTOI . "Instance";
    const INSTRUMENT                      = VSTOI::VSTOI . "Instrument";
    const INSTRUMENT_INSTANCE             = VSTOI::VSTOI . "InstrumentInstance";
    const ITEM                            = VSTOI::VSTOI . "Item";
    const PLATFORM                        = VSTOI::VSTOI . "Platform";
    const PLATFORM_INSTANCE               = VSTOI::VSTOI . "PlatformInstance";
    const PSYCHOMETRIC_QUESTIONNAIRE      = VSTOI::VSTOI . "PsychometricQuestionnaire";
    const QUESTIONNAIRE                   = VSTOI::VSTOI . "Questionnaire";
    const RESPONSE_OPTION                 = VSTOI::VSTOI . "ResponseOption";
    const SUBCONTAINER                    = VSTOI::VSTOI . "Subcontainer";
    const PROCESS_STEM                    = VSTOI::VSTOI . "ProcessStem";
    const PROCESS                         = VSTOI::VSTOI . "Process";
    const TASK                            = VSTOI::VSTOI . "Task";
    const TASK_TEMPORAL_DEPENDENCY        = VSTOI::VSTOI . "TemporalDependency";


    /*
     *    PROPERTIES
     */

    const BELONGS_TO                      = VSTOI::VSTOI . "belongsTo";
    const HAS_ANNOTATION_STEM             = VSTOI::VSTOI . "hasAnnotationStem";
    const HAS_PLATFORM                    = VSTOI::VSTOI . "hasPlatform";
    const HAS_SERIAL_NUMBER               = VSTOI::VSTOI . "hasSerialNumber";
    const HAS_WEB_DOCUMENTATION           = VSTOI::VSTOI . "hasWebDocumentation";
    const HAS_CONTENT                     = VSTOI::VSTOI . "hasContent";
    const HAS_CODEBOOK                    = VSTOI::VSTOI . "hasCodebook";
    const HAS_COMPONENT                   = VSTOI::VSTOI . "hasComponent";
    const HAS_COMPONENT_STEM              = VSTOI::VSTOI . "hasComponentStem";
    const HAS_INSTRUCTION                 = VSTOI::VSTOI . "hasInstruction";
    const HAS_LANGUAGE                    = VSTOI::VSTOI . "hasLanguage";
    const HAS_POSITION                    = VSTOI::VSTOI . "hasPosition";
    const HAS_PRIORITY                    = VSTOI::VSTOI . "hasPriority";
    const HAS_SHORT_NAME                  = VSTOI::VSTOI . "hasShortName";
    const HAS_STYLE                       = VSTOI::VSTOI . "hasStyle";
    const HAS_STATUS                      = VSTOI::VSTOI . "hasStatus";
    const HAS_SIR_MANAGER_EMAIL           = VSTOI::VSTOI . "hasSIRManagerEmail";
    const HAS_VERSION                     = VSTOI::VSTOI . "hasVersion";
    const OF_CODEBOOK                     = VSTOI::VSTOI . "ofCodebook";
    const HAS_PROCESS                     = VSTOI::VSTOI . "hasProcess";
    const OF_PROCESS                      = VSTOI::VSTOI . "ofProcess";

    /*
     * SIR/DPL STATUS
     */

    const DRAFT                           = VSTOI::VSTOI . "Draft";         // Cannot be deployed
    const UNDER_REVIEW                    = VSTOI::VSTOI . "UnderReview";   // Cannot be deployed
    const CURRENT                         = VSTOI::VSTOI . "Current";       // Undeployed and can be Deployed, not Damaged
    const DEPRECATED                      = VSTOI::VSTOI . "Deprecated";    // Cannot be deployed
    const DAMAGED                         = VSTOI::VSTOI . "Damaged";       // Cannot be deployed
    const DEPLOYED                        = VSTOI::VSTOI . "Deployed";      // Needs to become current before being deprecated or damaged

    /*
     * PERMISSION
     */

     const PUBLIC                          = VSTOI::VSTOI . "Public";
     const PRIVATE                         = VSTOI::VSTOI . "Private";

     /*
     *    POSITIONS
     */

    const NOT_VISIBLE                     = VSTOI::VSTOI . "NotVisible";
    const TOP_LEFT                        = VSTOI::VSTOI . "TopLeft";
    const TOP_CENTER                      = VSTOI::VSTOI . "TopCenter";
    const TOP_RIGHT                       = VSTOI::VSTOI . "TopRight";
    const LINE_BELOW_TOP                  = VSTOI::VSTOI . "LineBelowTop";
    const LINE_ABOVE_BOTTOM               = VSTOI::VSTOI . "LineAboveBottom";
    const BOTTOM_LEFT                     = VSTOI::VSTOI . "BottomLeft";
    const BOTTOM_CENTER                   = VSTOI::VSTOI . "BottomCenter";
    const BOTTOM_RIGHT                    = VSTOI::VSTOI . "BottomRight";
    const PAGE_TOP_LEFT                   = VSTOI::VSTOI . "PageTopLeft";
    const PAGE_TOP_CENTER                 = VSTOI::VSTOI . "PageTopCenter";
    const PAGE_TOP_RIGHT                  = VSTOI::VSTOI . "PageTopRight";
    const PAGE_LINE_BELOW_TOP             = VSTOI::VSTOI . "PageLineBelowTop";
    const PAGE_LINE_ABOVE_BOTTOM          = VSTOI::VSTOI . "PageLineAboveBottom";
    const PAGE_BOTTOM_LEFT                = VSTOI::VSTOI . "PageBottomLeft";
    const PAGE_BOTTOM_CENTER              = VSTOI::VSTOI . "PageBottomCenter";
    const PAGE_BOTTOM_RIGHT               = VSTOI::VSTOI . "PageBottomRight";


    /*
    *    TASK TYPES
    */
    const ABSTRACT_TASK                  = VSTOI::VSTOI . "AbstractTask";
    const APPLICATION_TASK               = VSTOI::VSTOI . "ApplicationTask";
    const INTERACTION_TASK               = VSTOI::VSTOI . "InteractionTask";
    const USER_TASK                      = VSTOI::VSTOI . "UserTask";

    /*
    *    TASK TEMPORAL DEPENDENCY
    */
    const CHOICEOPERATOR_TASK_DEP                  = VSTOI::VSTOI . "ChoiceOperator";
    const CONCURRENCYOPERATOR_TASK_DEP             = VSTOI::VSTOI . "ConcurrencyOperator";
    const ENABLINGOPERATOR_TASK_DEP                = VSTOI::VSTOI . "EnablingOperator";
    const ENABLINGINFORMATIONOPERATOR_TASK_DEP     = VSTOI::VSTOI . "EnablingInformationOperator";
    const ITERATIONOPERATOR_TASK_DEP               = VSTOI::VSTOI . "IterationOperator";
    const ORDERINDEPENDENTOPERATOR_TASK_DEP        = VSTOI::VSTOI . "OrderIndependentOperator";
    const SUSPENDRESUMEOPERATOR_TASK_DEP           = VSTOI::VSTOI . "SuspendResumeOperator";

  }
