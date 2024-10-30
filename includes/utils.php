<?php

function decode_value($value)
{
  return wp_specialchars_decode($value, ENT_QUOTES);
}
