Installation
===========================================
    composer require phpunit/phpunit

Execution tests
===========================================
    ./vendor/bin/phpunit

Config file
===========================================
    <?xml version="1.0" encoding="utf-8" ?>
    <phpunit colors="true">
        <testsuite name="Mes super tests">
            <directory>./tests</directory>
        </testsuite>
        <filter>
            <whitelist proceessUncoveredFromWhitelist="true">
                <directory suffic=".php">src/</directory>
            </whitelist>
        </filter>
    </phpunit>



Mocks
============================================
    // On crée un mock
    $observer = $this->getMockBuilder('MaSuperClass')
      ->setMethods(array('getLastPost'))
      ->getMock();

    // On précise les attentes concernant la méthode getLastPost
    $observer->expects($this->once())
      ->method('getLastPost')
      ->with($this->equalTo('blabla'));

    // On déroule notre code normalement
    $observer->get();


generate coverage
============================================
    .\vendor\bin\phpunit --coverage-html coverage