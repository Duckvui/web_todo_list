<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

class OtpDelivery
{
    public function available(string $channel): bool
    {
        return $channel === 'email' || (config('services.twilio.sid') && config('services.twilio.token') && config('services.twilio.verify_sid'));
    }

    public function send(string $channel, string $destination, string $code, string $purpose): ?string
    {
        if ($channel === 'email') {
            $label = $purpose === 'reset' ? 'đặt lại mật khẩu' : 'xác minh email';
            Mail::raw("Mã {$label} của bạn: {$code}. Hết hạn sau 10 phút. Không chia sẻ mã này.", function ($message) use ($destination, $label): void {
                $message->to($destination)->subject("TodoApp: Mã {$label}");
            });

            return null;
        }

        return $this->client()->verify->v2->services(config('services.twilio.verify_sid'))
            ->verifications->create($this->internationalPhone($destination), 'sms')->sid;
    }

    public function check(string $sid, string $code): bool
    {
        try {
            return $this->client()->verify->v2->services(config('services.twilio.verify_sid'))
                ->verificationChecks->create(['verificationSid' => $sid, 'code' => $code])->status === 'approved';
        } catch (RestException $exception) {
            if ($exception->getStatusCode() === 404) {
                return false;
            }
            throw $exception;
        }
    }

    private function client(): Client
    {
        return new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    private function internationalPhone(string $phone): string
    {
        return $phone[0] === '0' ? '+84'.substr($phone, 1) : '+84'.$phone;
    }
}
