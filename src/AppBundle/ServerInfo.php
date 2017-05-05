<?php

namespace AppBundle;

class ServerInfo {
    public static function getCpuCount() {

        $cmd = "uname";
        $OS = strtolower(trim(shell_exec($cmd)));

        switch($OS) {
            case('linux'):
                $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
                break;
            case('freebsd'):
                $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
                break;
            default:
                unset($cmd);
        }

        if ($cmd != '') {
            $cpuCoreNo = intval(trim(shell_exec($cmd)));
        }

        return empty($cpuCoreNo) ? 1 : $cpuCoreNo;

    }

    public static function getMemoryAmount() {
        $info = file_get_contents('/proc/meminfo');
        $ret = preg_match('/^MemTotal:\s+(\d+) kB$/m', $info, $matches);
        if ($ret !== 1) return 'unknown';
        return (int)($matches[1] / 1024);
        exit;
    }

    public static function getStorageAmount() {
        return (int)(disk_free_space('/') / 1024 / 1024);
    }
}