<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Wekreit\SMS;
use Wekreit\Http\SMSClient;

final class SMSTest extends TestCase
{
    public function testCanBeCreatedFromValidRecipientPhoneNumber(): void
    {
        $recipientPhoneNumber = '0788012501';
        //$this->assertSame($recipientPhoneNumber, )
    }

    public function testCannotBeCreatedFromInvalidRecipientPhoneNumber(): void
    {
        $client =  SMSClient::initInstance('test', 'test');
        //$this->expectException(InvalidArgumentException::class);
        $sms = new SMS($client);
        $sms->from('invalid');
    }
}
