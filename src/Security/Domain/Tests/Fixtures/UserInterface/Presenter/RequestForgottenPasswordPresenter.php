<?php

/**
 * Copyright (C) Thomas Boileau - All Rights Reserved.
 *
 * This source code is protected under international copyright law.
 * All rights reserved and protected by the copyright holders.
 * This file is confidential and only available to authorized individuals with the
 * permission of the copyright holders. If you encounter this file and do not have
 * permission, please contact the copyright holders and delete this file.
 */

declare(strict_types=1);

namespace App\Security\Domain\Tests\Fixtures\UserInterface\Presenter;

use App\Security\Domain\UseCase\RequestForgottenPassword\RequestForgottenPasswordOutput;
use App\Security\Domain\UseCase\RequestForgottenPassword\RequestForgottenPasswordPresenterInterface;

final class RequestForgottenPasswordPresenter implements RequestForgottenPasswordPresenterInterface
{
    public RequestForgottenPasswordOutput $output;

    public function present(RequestForgottenPasswordOutput $output): void
    {
        $this->output = $output;
    }
}
