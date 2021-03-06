<?PHP

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests;

use Mailgun\Mailgun;

class MailgunTest extends \Mailgun\Tests\MailgunTestCase
{
    public function testSendMessageMissingRequiredMIMEParametersExceptionGetsFlung()
    {
        $this->setExpectedException('\\Mailgun\\Messages\\Exceptions\\MissingRequiredMIMEParameters');

        $client = new Mailgun();
        $client->sendMessage('test.mailgun.com', 'etss', 1);
    }

    public function testVerifyWebhookGood()
    {
        $client = new Mailgun('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');
        $postData = [
            'timestamp' => '1403645220',
            'token' => '5egbgr1vjgqxtrnp65xfznchgdccwh5d6i09vijqi3whgowmn6',
            'signature' => '9cfc5c41582e51246e73c88d34db3af0a3a2692a76fbab81492842f000256d33',
        ];
        assert($client->verifyWebhookSignature($postData));
    }

    public function testVerifyWebhookBad()
    {
        $client = new Mailgun('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');
        $postData = [
            'timestamp' => '1403645220',
            'token' => 'owyldpe6nxhmrn78epljl6bj0orrki1u3d2v5e6cnlmmuox8jr',
            'signature' => '9cfc5c41582e51246e73c88d34db3af0a3a2692a76fbab81492842f000256d33',
        ];
        assert(!$client->verifyWebhookSignature($postData));
    }

    public function testVerifyWebhookEmptyRequest()
    {
        $client = new Mailgun('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');
        $postData = [];
        assert(!$client->verifyWebhookSignature($postData));
    }

    public function testGetAttachmentOk()
    {
        $attachmentUrl = 'http://example.com';
        $client = new Mailgun('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');
        $response = $client->getAttachment($attachmentUrl);

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals($response->http_response_code, 200);
    }

    public function testGetAttachmentFail()
    {
        $this->setExpectedException('\\Mailgun\\Connection\\Exceptions\\GenericHTTPError');
        $attachmentUrl = 'https://api.mailgun.net/non.existing.uri/1/2/3';
        $client = new Mailgun('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');
        $client->getAttachment($attachmentUrl);
    }
}
