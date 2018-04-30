<?php

namespace App\Extension;

class AgeExtension extends \Twig_Extension
{
  public function getFilters()
  {
    return array(
      new \Twig_SimpleFilter('age', array($this, 'ageCalculate')),
    );
  }

  public function ageCalculate($bithdayDate)
  {
    $now = new \DateTime();
    $bithdayDate = new \DateTime($bithdayDate);
    $interval = $now->diff($bithdayDate);

    return $interval->y;
  }

  public function getName()
  {
    return 'age_extension';
  }
}
