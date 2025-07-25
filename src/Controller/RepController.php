<?php

namespace Drupal\rep\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for the Rep module help page.
 */
class RepController extends ControllerBase {

  /**
   * Displays the help content for the Test module.
   */
  public function help() {
    $content = "<h3>Instrument's Structure Registration</h3>";
    $content .= "<ul>";
    $content .= "<li>Building canonical instrument descriptions with SIR Elements</li>";
    $content .= "<li>Creating my first instrument</li>";
    $content .= "<li>Creating components and connecting them to instruments</li>";
    $content .= "<li>Creating subcontainers inside my instrument (e.g., sections, subsections, etc)</li>";
    $content .= "<li>Navigating within my instrument and its subsections</li>";
    $content .= "<li>Explaing how is the general SIR layout for containers (i.e., instruments and their subcontainers)</li>";
    $content .= "<li>Creating visual annotations to my instrument (e.g., titles, instructions)</li>";
    $content .= "<li>How to add CSS style to annotations</li>";
    $content .= "</ul><br>";
    $content .= "<h3>Instrument's Semantics Registration</h3>";
    $content .= "<ul>";
    $content .= "<li>Semantics building blocks</li>";
    $content .= "<li>Connecting SIR semantic elements to existing community-built ontologies</li>";
    $content .= "<li>Registering entity types</li>";
    $content .= "<li>Registering attribute types</li>";
    $content .= "<li>Registering unit types</li>";
    $content .= "<li>Creating semantic variables</li>";
    $content .= "</ul>";
    return [
      '#markup' => $content,
    ];
  }


}
