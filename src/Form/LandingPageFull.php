<?php

/**
 * @file
 * Contains the settings for admninistering the rep Module
 */

namespace Drupal\rep\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\URL;
use Drupal\rep\Utils;
use Drupal\rep\ListKeywordLanguagePage;
use Drupal\rep\Entity\Tables;

class LandingPageFull extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "rep_landing_page_full";

    }


     /**
     * {@inheritdoc}
     */

     public function buildForm(array $form, FormStateInterface $form_state){

        $form['rep_home'] = [
            '#type' => 'item',
            '#title' => '<br>This is a <a href="http://hadatac.org/software/hascorepo/">HAScO/Repo</a> instance ' .
                'developed by <a href="http://hadatac.org/">HADatAc.org</a> community.',
        ];

        $form['rep_full_list'] = [
            '#type' => 'item',
            '#title' => 'There is a list of <a href="/rep">kinds of core elements</a> in this knowledge graph.<br>',
        ];


        $form['rep_content1'] = [
            '#type' => 'item',
            '#title' => 'This repository currently hosts a knowledge graph about the following:<br>',
        ];

        $form['row1'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('row')),
        );

        $totalsSocial = '<h3>Social/Organizational Elements</h3>';
        $totalsSocial .= '<div class="row text-center">';

        $totalsSocial .= '<div class="col-md-3">' .
                         ' <a href="' . Utils::selectBackUrl('organization')->toString() . '">Organization(s)</a><br>' .
                         About::total('organization') . '</div>';

        $totalsSocial .= '<div class="col-md-3">' .
                         ' <a href="' . Utils::selectBackUrl('person')->toString() . '">Person(s)</a><br>' .
                         About::total('person') . '</div>';

        $totalsSocial .= '<div class="col-md-3">' .
                         ' <a href="' . Utils::selectBackUrl('place')->toString() . '">Place(s)</a><br>' .
                         About::total('place') . '</div>';

        $totalsSocial .= '<div class="col-md-3">' .
                         ' <a href="' . Utils::selectBackUrl('postaladdress')->toString() . '">Postal Address(es)</a><br>' .
                         About::total('postaladdress') . '</div>';

        $totalsSocial .= '</div>';


        $form['row1']['column1'] = array(
            '#type' => 'container',
            //'#attributes' => array('class' => array('row')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<div class="card"><div class="card-body">' . $totalsSocial . '</div></div>',
                //'<div class="card-footer text-center"><a href="(link)" class="btn btn-secondary">Manage</a></div></div>',
            ),
        );

        $form['row2'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('row')),
        );

        $form['row2']['column1'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-12')),
        );

        //
        //  FIRST COLUMN
        //

        $form['row2']['column1'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-3')),
        );

        $totalsInst = '<h3>Instrument Elements</h3>';
        $totalsInst .= '<ul>';
        $totalsInst .=  '<li> ' . About::total('ins') . ' <a href="'.Utils::selectBackUrl('ins')->toString().'">INS(s)</a> (MT)</li>';
        $totalsInst .=  '<li> ' . About::total('instrument') . ' <a href="'.Utils::selectBackUrl('instrument')->toString().'">instrument(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('processstem') . ' <a href="'.Utils::selectBackUrl('processstem')->toString().'">process stem(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('componentstem') . ' <a href="'.Utils::selectBackUrl('componentstem')->toString().'">component stem(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('component') . ' <a href="'.Utils::selectBackUrl('component')->toString().'">component(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('codebook') . ' <a href="'.Utils::selectBackUrl('codebook')->toString().'">codebook(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('process') . ' <a href="'.Utils::selectBackUrl('process')->toString().'">process(es)</a></li>';
        $totalsInst .=  '<li> ' . About::total('responseoption') . ' <a href="'.Utils::selectBackUrl('responseoption')->toString().'">response option(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('annotationstem') . ' <a href="'.Utils::selectBackUrl('annotationstem')->toString().'">annotation stem(s)</a></li>';
        $totalsInst .=  '<li> ' . About::total('annotation') . ' <a href="'.Utils::selectBackUrl('annotation')->toString().'">annotation(s)</a></li>';
        $totalsInst .= '</ul>';

        $form['row2']['column1']['card1'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-12')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<div class="card"><div class="card-body">' . $totalsInst . '</div></div>',
            ),
        );

        $form['row2']['column1']['filler'] = [
            '#type' => 'markup',
            '#markup' => '<br>',
        ];

        $totalsStudy = '<h3>Study Elements</h3>';
        $totalsStudy .= '<ul>';
        $totalsStudy .=  '<li> ' . About::total('dsg') . ' <a href="'.Utils::selectBackUrl('dsg')->toString().'">DSG(s)</a> (MT)</li>';
        $totalsStudy .=  '<li> ' . About::total('dd') . ' <a href="'.Utils::selectBackUrl('dd')->toString().'">DDs</a> (MT)</li>';
        $totalsStudy .=  '<li> ' . About::total('sdd') . ' <a href="'.Utils::selectBackUrl('sdd')->toString().'">SDD(s)</a> (MT)</li>';
        $totalsStudy .=  '<li> ' . About::total('study') . ' <a href="'.Utils::selectBackUrl('study')->toString().'">study(ies)</a></li>';
        $totalsStudy .=  '<li> ' . About::total('studyrole') . ' <a href="'.Utils::selectBackUrl('studyrole')->toString().'">studyrole(s)</a></li>';
        $totalsStudy .=  '<li> ' . About::total('virtualcolumn') . ' <a href="'.Utils::selectBackUrl('virtualcolumn')->toString().'">virtualcolumn(s)</a></li>';
        $totalsStudy .=  '<li> ' . About::total('studyobjectcollection') . ' <a href="'.Utils::selectBackUrl('studyobjectcollection')->toString().'">studyobjectcollection(s)</a></li>';
        $totalsStudy .=  '<li> ' . About::total('studyobject') . ' <a href="'.Utils::selectBackUrl('studyobject')->toString().'">studyobject(s)</a></li>';
        $totalsStudy .= '</ul>';
        $totalsStudy  .= '<br>';
        $totalsStudy  .= '<br>';
        $totalsStudy  .= '<br>';

        $form['row2']['column1']['card2'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-12')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<div class="card"><div class="card-body">' . $totalsStudy . '</div></div>',
            ),
        );


        $module_handler = \Drupal::service('module_handler');

        $module_path = "";
        // Check if the module 'rep' is enabled.
        if ($module_handler->moduleExists('rep')) {
            $module_path = $module_handler->getModule('rep')->getPath();
        }

        $image_path = './' . $module_path . '/images/hasco_cycle.png';

        //
        // SECOND COLUMN: MAIN IMAGE
        //

        /*
        $form['row']['column2'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-4')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<img src="' . $image_path . '" alt="HASCO Cycle" style="width:100%;" border="0" />',
            ),
        );
        */

        //
        // THIRD COLUMN: MAIN IMAGE
        //

        $form['row2']['column3'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-3')),
        );

        $totalsDeploy = '<h3>Deployment Elements</h3>';
        $totalsDeploy .= '<ul>';
        $totalsDeploy .=  '<li> ' . About::total('dp2') . ' <a href="'.Utils::selectBackUrl('dp2')->toString().'">DP2(s)</a> (MT)</li>';
        $totalsDeploy .=  '<li> ' . About::total('str') . ' <a href="'.Utils::selectBackUrl('str')->toString().'">STR(s)</a> (MT)</li>';
        $totalsDeploy .=  '<li> ' . About::total('platform') . ' <a href="'.Utils::selectBackUrl('platform')->toString().'">platform(s)</a></li>';
        $totalsDeploy .=  '<li> ' . About::total('platforminstance') . ' <a href="'.Utils::selectBackUrl('platforminstance')->toString().'">platform instance(s)</a></li>';
        $totalsDeploy .=  '<li> ' . About::total('instrumentinstance') . ' <a href="'.Utils::selectBackUrl('instrumentinstance')->toString().'">instrument instance(s)</a></li>';
        $totalsDeploy .=  '<li> ' . About::total('componentinstance') . ' <a href="'.Utils::selectBackUrl('componentinstance')->toString().'">component instance(s)</a></li>';
        $totalsDeploy .=  '<li> ' . About::total('deployment') . ' <a href="'.Utils::selectBackUrl('deployment')->toString().'">deployment(s)</a></li>';
        $totalsDeploy .=  '<li> ' . About::total('stream') . ' <a href="'.Utils::selectBackUrl('stream')->toString().'">stream(s)</a></li>';
        $totalsDeploy  .= '</ul>';
        $totalsDeploy  .= '<br>';
        $totalsDeploy  .= '<br>';

        $form['row2']['column3']['card3'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-12')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<div class="card"><div class="card-body">' . $totalsDeploy . '</div></div>',
            ),
        );

        $form['row2']['column3']['filler'] = [
            '#type' => 'markup',
            '#markup' => '<br>',
        ];

        $totalsData = '<h3>Data Elements</h3>';
        $totalsData .= '<h5>Data Content</h5>';
        $totalsData .= '<ul>';
        $totalsData .=  '<li> ' . About::total('da') . ' <a href="'.Utils::selectBackUrl('da')->toString().'">dataset\'s data file(s)</a></li>';
        $totalsData .=  '<li> ' . About::total('value') . ' <a href="'.Utils::selectBackUrl('da')->toString().'">data value(s)</a></li>';
        $totalsData .= '</ul>';
        $totalsData .= '<h5>Data Semantics</h5>';
        $totalsData .= '<ul>';
        $totalsData .=  '<li> ' . About::total('sdd') . ' <a href="'.Utils::selectBackUrl('sdd')->toString().'">semantic data dictionary(ies)</a> (MT)</li>';
        $totalsData .=  '<li> ' . About::total('semanticvariable') . ' <a href="'.Utils::selectBackUrl('semanticvariable')->toString().'">semantic variable(s)</a></li>';
        $totalsData .=  '<li> ' . About::total('entity') . ' <a href="'.Utils::selectBackUrl('entity')->toString().'">entity(ies)</a></li>';
        $totalsData .=  '<li> ' . About::total('attribute') . ' <a href="'.Utils::selectBackUrl('attribute')->toString().'">attribute(s)</a></li>';
        $totalsData .=  '<li> ' . About::total('unit') . ' <a href="'.Utils::selectBackUrl('unit')->toString().'">unit(s)</a></li>';
        $totalsData .= '</ul>';

        // Define each card individually
        $form['row2']['column3']['card4'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-12')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<div class="card"><div class="card-body">' . $totalsData . '</div></div>',
            ),
        );

        //
        // FORTH COLUMN: ONTOLOGIES AND SOCIAL GRAPH
        //

        // Second row with 3 cards
        $form['row2']['column4'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-6')),
        );

        $ontologies = '<h3>Ontologies</h3><table>';

        $tables = new Tables;
        $namespaces = $tables->getNamespaces();

        $ontologies .= '<thead><tr><th>Abbreviation</th><th>Namespace</th><th>Triples</th></tr></thead><tbody>';

        if ($namespaces != NULL) {
            foreach ($namespaces as $abbrev => $ns) {
                $ontologies .= '<tr>';
                $ontologies .= '<td>'. $abbrev . '</td>';
                $ontologies .= '<td><a href="'. $ns .'">'. $ns . '</a></td>';
                $ontologies .= '<td> </td>';
                $ontologies .= '</tr>';
            }
        } else {
            $ontologies .= '<tr><td colspan="2">No NAMESPACE information available at the moment</td></tr>';
        }

        $ontologies .= '</tbody></table>';

        /*
        $ontologies = '<h3>Ontologies</h3><ul>';
        $tables = new Tables;
        $namespaces = $tables->getNamespaces();
        if ($namespaces != NULL) {
          foreach ($namespaces as $abbrev => $ns) {
             $ontologies .= '<li><a href="'. $ns .'">'. $ns . '</a> ('. $abbrev . ')</li>';
          }
        } else {
            $ontologies .= '<li>No NAMESPACE information available at the moment</li>';
        }
        $ontologies .= '</ul>';
        */

        $form['row2']['column4']['card1'] = array(
            '#type' => 'container',
            '#attributes' => array('class' => array('col-md-12')),
            'card' => array(
                '#type' => 'markup',
                '#markup' => '<div class="card"><div class="card-body">' . $ontologies . '</div></div>',
                //'<div class="card-footer text-center"><a href="(link)" class="btn btn-secondary">Manage</a></div></div>',
            ),
        );

        $form['rep_newline1'] = [
            '#type' => 'item',
            '#title' => '<br><br>',
        ];

        return $form;

     }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $submitted_values = $form_state->cleanValues()->getValues();
      $triggering_element = $form_state->getTriggeringElement();
      $button_name = $triggering_element['#name'];

      //if ($button_name === 'back') {
      //  $url = Url::fromRoute('rep.home');
      //  $form_state->setRedirectUrl($url);
      //  return;
      //}

    }

    public static function total($elementtype) {
        return ListKeywordLanguagePage::total($elementtype, NULL, NULL);
    }

}
