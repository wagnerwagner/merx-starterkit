<?php

/**
 * Host-specific config, loaded in addition to (and merged over) the main
 * /site/config/config.php whenever the request’s hostname matches the part
 * of this filename between "config." and ".php" — here:
 * starterkit.merx.wagnerwagner.test, the local development domain.
 * https://getkirby.com/docs/guide/configuration#multi-environment-setup
 *
 * Mailpit (https://mailpit.axllent.org) is a local, fake SMTP server with a
 * web UI. It catches every mail this site sends instead of delivering it,
 * so the “Sending emails” flow (the `wagnerwagner.merx.createOrder:after`
 * hook in /site/config/config.php) can be tested without spamming real
 * inboxes. Install & run it locally, e.g. `brew install mailpit && mailpit`,
 * then view caught mails at http://localhost:8025.
 *
 * If your local PHP setup runs inside a container or VM, `127.0.0.1` below
 * refers to that environment, not your host machine, and won’t reach a
 * Mailpit instance running on the host — point it at whatever address your
 * setup uses to reach the host instead (or run Mailpit inside the same
 * container/network).
 */
return [
    'debug' => true,
    'email' => [
      'transport' => [
        'type' => 'smtp',
        'host' => 'mail.test',
        'port' => 1025,
        'security' => false,
        'auth' => false,
      ],
    ],
];
