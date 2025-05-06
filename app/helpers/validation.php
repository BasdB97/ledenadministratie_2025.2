<?php
function validateForm($data)
{
  if (empty($data['name'])) {
    $data['name_err'] = 'Vul een naam in.';
  } elseif (preg_match('/[0-9]/', $data['name'])) {
    $data['name_err'] = 'De familienaam mag geen cijfers bevatten.';
  }

  if (empty($data['street'])) {
    $data['street_err'] = 'Vul een straatnaam in.';
  } elseif (preg_match('/[0-9]/', $data['street'])) {
    $data['street_err'] = 'De straatnaam mag geen cijfers bevatten.';
  }

  if (empty($data['house_number'])) {
    $data['house_number_err'] = 'Vul een huisnummer in.';
  } elseif (!preg_match('/^[0-9]+$/', $data['house_number'])) {
    $data['house_number_err'] = 'Het huisnummer mag alleen cijfers bevatten.';
  }

  if (empty($data['postal_code'])) {
    $data['postal_code_err'] = 'Vul een postcode in.';
  } elseif (!preg_match('/^[0-9]{4}[A-Z]{2}$/', $data['postal_code'])) {
    $data['postal_code_err'] = 'Vul een geldige postcode in (bijv. 1234AB).';
  }

  if (empty($data['city'])) {
    $data['city_err'] = 'Vul een plaats in.';
  } elseif (preg_match('/[0-9]/', $data['city'])) {
    $data['city_err'] = 'De plaatsnaam mag geen cijfers bevatten.';
  }

  if (empty($data['country'])) {
    $data['country_err'] = 'Vul het land in.';
  }

  return $data;
}


function checkErrors($data)
{
  // Return true if any error is present
  if (
    !empty($data['name_err']) || !empty($data['street_err']) || !empty($data['house_number_err']) ||
    !empty($data['postal_code_err']) || !empty($data['city_err']) || !empty($data['country_err']) ||
    !empty($data['address_err'])
  ) {
    return true;
  }
  return false;
}
