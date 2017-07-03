<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/3/2017
 * Time: 10:13 AM
 */

namespace TwoQuakers\testing;

use Tops\sys\ITaxonomyManager;

class FakeTaxonomy implements ITaxonomyManager
{
    private $taxonomy = array('test' => array("one","two","three"));



    public function getTaxonomyTerms($taxonomyPath)
    {
        if (array_key_exists($taxonomyPath,$this->taxonomy)) {
            return $this->taxonomy[$taxonomyPath];
        }
        return array();
    }

    public function createTaxonomy($taxonomyPath, array $taxonomyTerms)
    {
        $this->taxonomy[$taxonomyPath] = $taxonomyTerms;
    }

    public function addTaxonomyTerm($taxonomyPath, $taxonomyTerm)
    {
        if (empty($this->taxonomy[$taxonomyPath])) {
            $this->taxonomy[$taxonomyPath] = array($taxonomyTerm);
        }
        else {
            if (!array_key_exists($taxonomyTerm,$this->taxonomy[$taxonomyPath])) {
                $this->taxonomy[$taxonomyPath][] = $taxonomyTerm;
            }
        }
    }

    public function removeTaxonomyTerm($taxonomyPath, $taxonomyTerm)
    {
        if (!empty($this->taxonomy[$taxonomyPath])) {
            $i = array_search($taxonomyTerm,$this->taxonomy[$taxonomyPath]);
            if ($i !== false) {
                unset($this->taxonomy[$taxonomyPath][$i]);
            }
        }
    }
}