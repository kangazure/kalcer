<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception khusus untuk kegagalan pada modul network monitoring (gagal
 * polling node, gagal parsing data SNMP/API NOC, dst). Dipisah dari
 * exception umum agar dapat di-log ke channel 'network_monitor' secara
 * khusus (lihat bootstrap/app.php -> withExceptions) tanpa mencampur
 * dengan log error aplikasi umum.
 */
class NetworkMonitorException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?int $nodeId = null,
        public readonly array $context = [],
    ) {
        parent::__construct($message);
    }
}
