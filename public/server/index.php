<?php

// Parameters
$dnsbl_lookup = [
    "all.s5h.net",
    "b.barracudacentral.org",
    #"bl.emailbasura.org",
    #"bl.spamcannibal.org",
    "bl.spamcop.net",
    "blacklist.woody.ch",
    "bogons.cymru.com",
    "cbl.abuseat.org",
    "cdl.anti-spam.org.cn",
    "combined.abuse.ch",
    "db.wpbl.info",
    "dnsbl-1.uceprotect.net",
    "dnsbl-2.uceprotect.net",
    "dnsbl-3.uceprotect.net",
    "dnsbl.anticaptcha.net",
    "dnsbl.cyberlogic.net",
    "dnsbl.dronebl.org",
    "dnsbl.inps.de",
    "dnsbl.sorbs.net",
    "dnsbl.spfbl.net",
    "drone.abuse.ch",
    "duinv.aupads.org",
    "dul.dnsbl.sorbs.net",
    "dyna.spamrats.com",
    "dynip.rothen.com",
    "exitnodes.tor.dnsbl.sectoor.de",
    "http.dnsbl.sorbs.net",
    "ips.backscatterer.org",
    "ix.dnsbl.manitu.net",
    "korea.services.net",
    "misc.dnsbl.sorbs.net",
    "noptr.spamrats.com",
    "orvedb.aupads.org",
    "pbl.spamhaus.org",
    "proxy.bl.gweep.ca",
    "psbl.surriel.com",
    "relays.bl.gweep.ca",
    "relays.nether.net",
    "sbl.spamhaus.org",
    "short.rbl.jp",
    "singular.ttk.pte.hu",
    "smtp.dnsbl.sorbs.net",
    "socks.dnsbl.sorbs.net",
    "spam.abuse.ch",
    "spam.dnsbl.anonmails.de",
    "spam.dnsbl.sorbs.net",
    "spam.spamrats.com",
    "spambot.bls.digibase.ca",
    "spamrbl.imp.ch",
    "spamsources.fabel.dk",
    "ubl.lashback.com",
    "ubl.unsubscore.com",
    "virus.rbl.jp",
    "web.dnsbl.sorbs.net",
    "wormrbl.imp.ch",
    "xbl.spamhaus.org",
    "z.mailspike.net",
    "zen.spamhaus.org",
    "zombie.dnsbl.sorbs.net"
    /*
    "dnsbl-1.uceprotect.net",
    "dnsbl-2.uceprotect.net",
    "dnsbl-3.uceprotect.net",
    "dnsbl.dronebl.org",
    "dnsbl.sorbs.net",
    "zen.spamhaus.org",
    "bl.spamcop.net",
    "list.dsbl.org",
    "sbl.spamhaus.org",
    "xbl.spamhaus.org",
    "relays.osirusoft.com"
    */
];

$repeat = count($dnsbl_lookup);

$ip = filter_input(
    INPUT_GET,
    'ip',
    FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ['options' => ['default' => '']]
);

$msg = '';
if ($ip === '') {
    $msg = 'No IP given';
}

// Make sure output buffering is turned off
@ob_end_flush();

// Header

header('Access-Control-Allow-Origin: *');
header('Content-Type: text/event-stream; charset=UTF-8');

echo dataToStreamEvent
(
    'header',
    ['totalItems' => $repeat, 'msg' => $msg]
);
flush();

// Message times repeat


$reverse_ip = implode(".", array_reverse(explode(".", $ip)));

foreach ($dnsbl_lookup as $i => $host) {
    $dnsr = $reverse_ip . "." . $host . ".";
    $time_start = microtime(true);
    if (checkdnsrr($dnsr, "A")) {
        $listed = true;
    } else {
        $listed = false;
    }
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    $time = ($time >= 1.0) ? round($time, 2) . 's' : round($time * 1000, 2) . 'ms';

    echo dataToStreamEvent(
        'item',
        [
            'cnt' => $i+1,
            'host' => $host,
            'listed' => $listed,
            'time' => $time,
        ]
    );
    flush();
}

// Tell client to close the connection

echo dataToStreamEvent('close', []);
flush();

/**
 * @param string $type
 * @param array $data
 * @param string $id
 * @return string text/event-stream formatted string
 */
function dataToStreamEvent($type, array $data, $id = '')
{
    $result = '';

    if ($type !== '') {
        $result .= sprintf("event:%s\n", strtr($type, ["\r" => ' ', "\n" => ' ']));
    }

    if ($id !== '') {
        $result .= sprintf("id:%s\n", strtr($id, ["\r" => ' ', "\n" => ' ']));
    }

    $result .= "data:" . strtr(json_encode($data), ["\r\n" => "\n", "\r" => "\n", "\n" => "\ndata:"]);

    $result .= "\n\n";

    return $result;
}
