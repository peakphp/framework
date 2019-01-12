<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Backpack\Http\Request;
use \Psr\Http\Message\ServerRequestInterface;

class RequestTest extends TestCase
{
    protected function createServerRequest()
    {
        return $this->createMock(ServerRequestInterface::class);
    }

    public function testIsMethod()
    {
        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('POST'));

        $this->assertFalse(Request::isGet($request));

        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('GET'));
        $this->assertTrue(Request::i  db:
    image: mysql:8.0
    command: ["--default-authentication-plugin=mysql_native_password", "--skip-symbolic-links" ,"--innodb-use-native-aio=0"]
    container_name: db
    restart: always
    ports:
      - 3306:3306
    volumes:
      - ./db/mysql:/var/lib/mysql:rw
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_ROOT_HOST=%
      - TZ=America/New_YorksGet($request));
    }
}
