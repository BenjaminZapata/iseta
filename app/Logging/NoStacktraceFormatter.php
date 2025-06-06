<?php
namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class NoStacktraceFormatter
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            // Validamos que el handler tenga setFormatter
            if (method_exists($handler, 'setFormatter')) {
                $format = "[%datetime%] %level_name%: %message% %context%\n";
                $formatter = new LineFormatter($format, null, true, true);
                $formatter->ignoreEmptyContextAndExtra();
                $handler->setFormatter($formatter);
            }
        }
    }
}
