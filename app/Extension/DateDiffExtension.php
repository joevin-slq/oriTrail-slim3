<?php

namespace App\Extension;

class DateDiffExtension extends \Twig_Extension
{

  public function getFilters()
  {
    return array(
        new \Twig_SimpleFilter('date_diff', array($this, 'diffCalculate')),
    );
  }

  public function diffCalculate($dateA, $dateB = null)
  {
    $dateA = new \DateTime($dateA);
    $dateB = new \DateTime($dateB);
    $diff = $dateA->diff($dateB);

    return $diff->format('%H:%I:%S');
  }

  public function getName()
  {
      return 'date_diff_extension';
  }
}
