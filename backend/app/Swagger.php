<?php

declare(strict_types=1);

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API documentation for the News Aggregator project",
    title: "News Aggregator API",
    contact: new OA\Contact(email: "support@example.com")
)]
class Swagger
{
    // This class is intentionally left empty.
}
