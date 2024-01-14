<?php

namespace Middleware;

use Helpers\Authenticate;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\RedirectRenderer;

class EmailVerifiedMiddleware implements Middleware
{
    public function handle(callable $next): HTTPRenderer
    {
        error_log('Running verification check...');
        if(!Authenticate::isEmailVerified()){
            FlashData::setFlashData('error', 'Must verify email to view this page.');
            return new RedirectRenderer('verify/resend');
        }

        return $next();
    }
}
