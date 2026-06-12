<?php

namespace App\Services;

use App\Services\SSOService;
use Illuminate\Support\Facades\Http;

class SoapAuditService
{
    public function sendAudit($data)
{
    $sso = new SSOService();

    $token = $sso->getM2MToken();

    $xml = '
    <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
                   xmlns:iae="http://iae.central/audit">
        <soap:Body>
            <iae:AuditRequest>
                <iae:TeamID>TEAM-08</iae:TeamID>
                <iae:ActivityName>PropertyCreated</iae:ActivityName>
                <iae:LogContent><![CDATA[' . json_encode($data) . ']]></iae:LogContent>
            </iae:AuditRequest>
        </soap:Body>
    </soap:Envelope>';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'text/xml'
    ])
    ->withBody($xml, 'text/xml')
    ->post('https://iae-sso.virtualfri.id/soap/v1/audit');

    $xml = simplexml_load_string($response->body());

$xml->registerXPathNamespace(
    'iae',
    'http://iae.central/audit'
);

$receipt = $xml->xpath(
    '//iae:ReceiptNumber'
);

return [
    'status' => 'success',
    'receipt_number' => (string) $receipt[0]
];
}
}