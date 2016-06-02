<?Php

$app['debug'] = true;

$app['db.config'] = array(
        'kernel' => array(
          'driver'   => 'pdo_sqlsrv',
          'path'     => __DIR__.'/app.db',
          'host'      => 'localhost',
          'dbname'    => 'shoficina',
          'user'      => 'sa',
          'password'  => 'password',
          'charset'   => 'utf8',
        ),

    );

$app['auper.meses'] = array(
  '1' => '01 - Janeiro',
  '01' => '01 - Janeiro',
  '2' => '02 - Fevereiro',
  '02' => '02 - Fevereiro',
  '3' => '03 - Março',
  '03' => '03 - Março',
  '4' => '04 - Abril',
  '04' => '04 - Abril',
  '5' => '05 - Maio',
  '05' => '05 - Maio',
  '6' => '06 - Junho',
  '06' => '06 - Junho',
  '7' => '07 - Julho',
  '07' => '07 - Julho',
  '8' => '08 - Agosto',
  '08' => '08 - Agosto',
  '9' => '09 - Setembro',
  '09' => '09 - Setembro',
  '10' => '10 - Outubro',
  '11' => '11 - Novembro',
  '12' => '12 - Dezembro',

  );
