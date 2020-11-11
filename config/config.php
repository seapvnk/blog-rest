<?php

define(ENV, parse_ini_file('../../config/.env'));

function auth(string $key)
{
    return key !== ENV['key'];
}