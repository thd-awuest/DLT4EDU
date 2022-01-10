<?php

$settings['saml2'] = array(
    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them to be signed or encrypted.
    // Also it will reject the messages if the SAML standard is not strictly
    // followed: Destination, NameId, Conditions ... are validated too.
    'strict' => false,

    // Enable debug mode (to print errors).
    'debug' => true,

    // Set a BaseURL to be used instead of try to guess
    // the BaseURL of the view that process the SAML Message.
    // Ex http://sp.example.com/
    //    http://example.com/sp/
    'baseurl' => 'https://dlt4edu.th-deg.de/',

    // Service Provider Data that we are deploying.
    'sp' => array(
        // Identifier of the SP entity  (must be a URI)
        'entityId' => 'https://dlt4edu.th-deg.de/users/metadata',
        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned
            'url' => 'https://dlt4edu.th-deg.de/users/acs',
            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports this endpoint for the
            // HTTP-POST binding only.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        ),
        // If you need to specify requested attributes, set a
        // attributeConsumingService. nameFormat, attributeValue and
        // friendlyName can be omitted
        "attributeConsumingService" => array(
            "serviceName" => "DLT4EDU Service",
            "serviceDescription" => "DLT4EDU Service",
            "requestedAttributes" => array(
                array(
                    "name" => "",
                    "isRequired" => false,
                    "nameFormat" => "",
                    "friendlyName" => "",
                    "attributeValue" => array()
                )
            )
        ),
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned
            'url' => 'https://dlt4edu.th-deg.de/users/logout',
            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports the HTTP-Redirect binding
            // only for this endpoint.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        // Specifies the constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported.
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => '__HERE__',
        'privateKey' => '__HERE__',

        /*
         * Key rollover
         * If you plan to update the SP x509cert and privateKey
         * you can define here the new x509cert and it will be
         * published on the SP metadata so Identity Providers can
         * read them and get ready for rollover.
         */
        // 'x509certNew' => '',
    ),

    // Identity Provider Data that we want connected with our SP.
    'idp' => array(
        // Identifier of the IdP entity  (must be a URI)
        'entityId' => 'https://saml-bird.daad.com/saml2/idp/metadata.php',
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => array(
            // URL Target of the IdP where the Authentication Request Message
            // will be sent.
            'url' => 'https://saml-bird.daad.com/saml2/idp/SSOService.php',
            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports the HTTP-Redirect binding
            // only for this endpoint.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
        ),
        // SLO endpoint info of the IdP.
        'singleLogoutService' => array(
            // URL Location of the IdP where SLO Request will be sent.
            'url' => 'https://saml-bird.daad.com/saml2/idp/SingleLogoutService.php',
            // URL location of the IdP where SLO Response will be sent (ResponseLocation)
            // if not set, url for the SLO Request will be used
            'responseUrl' => 'https://dlt4edu.th-deg.de/users/logout',
            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports the HTTP-Redirect binding
            // only for this endpoint.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        // Public x509 certificate of the IdP
        'x509cert' => 'MIIE8TCCA1mgAwIBAgIUNpw+rKevxzZhoeSeVhxuJ8j5hyIwDQYJKoZIhvcNAQELBQAwgYcxCzAJBgNVBAYTAkRFMR8wHQYDVQQIDBZOb3J0aCBSaGluZS1XZXN0cGhhbGlhMTowOAYDVQQKDDFEZXV0c2NoZSBBa2FkZW1pc2NoZSBBdXN0YXVzY2hkaWVuc3QgZS4gVi4gKERBQUQpMRswGQYDVQQDDBJzYW1sLWJpcmQuZGFhZC5jb20wHhcNMjExMTE3MTUxNzAzWhcNMjIxMTE3MTUxNzAzWjCBhzELMAkGA1UEBhMCREUxHzAdBgNVBAgMFk5vcnRoIFJoaW5lLVdlc3RwaGFsaWExOjA4BgNVBAoMMURldXRzY2hlIEFrYWRlbWlzY2hlIEF1c3RhdXNjaGRpZW5zdCBlLiBWLiAoREFBRCkxGzAZBgNVBAMMEnNhbWwtYmlyZC5kYWFkLmNvbTCCAaIwDQYJKoZIhvcNAQEBBQADggGPADCCAYoCggGBAK1hHVJnjj8N18ghpStwWsOd+zYLfb2cVZ6kWdtnNlGUyNUbgFJaM+ktUM9p87OpiRgV+sC4r5NY3hmIt6WrgSk1fWex4k0lW9PjliI40wIKc/6EyOsPCRzXI7QgiGuuy+GmO+YTvGJJ1OsAdH4ytKmsUZpAiNE039zKQJnt4LG8iy5duCJQeAtge80X7LOX33YGlJpZ7fa35U/l/xyvT9kRb6WBxwQfOQQaBXFtpfV263Hj2N/1PdGAfbq88AuNqlQwJ5YsYuhWLq9Lc2DEeDkG28wzZO0ryeQtr/cHGoTRpM62d/lURBJRxjlpX7Xl019NOHJ32KgVbYcCOzM20B9UWIWXVLWbMKN1wnJosXsbyXKr0THFcyFN0WCnhIdiUuHQj6Clz9A6ijMBSozwu9A5YeBXFWC+5uceC0Kpvw2E2y8zVP6gtEQtXUy74o3g8fFTWdsrA4IBJVjn3ug9vaIOj0CZ28uM33oIbS3M9kqfbD2x5Jdw2AxuVslJ91G/swIDAQABo1MwUTAdBgNVHQ4EFgQUOmVKWFGY+JTT3gfiVPKYcDx5MGEwHwYDVR0jBBgwFoAUOmVKWFGY+JTT3gfiVPKYcDx5MGEwDwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAYEApIodJcrRcxCAdG0iPuljp42Ijl8MouuXhH4c2Mc/PRD9yXKYu8tmFV0NK12T5eWVOZYanrj/KWldk+VM7fbmO13DphOKVyCtC/HnNfHKvC3IdzH6swyZs/sdV30ERF05p80u8l/Ug49aVELk3gFiJVutnKSuE378XZzQ9mUmMLzcqmzlMHJ0BduWcjA7Lt+dJF2Bs4qu242YA0281MkYbjrvGLaekxspJseRRo5p/pkHG/8jD/20afqMTNkLRV2kUWWM/y/S7sZPIGAAhDvv5GVD3OvVLMxS+J3RiGRWAhlG3DOoJjox+dQQ0TGHJv2C4snaytgxzWZlBSxMsLrhRRMlPrfh+a90kYn9tcMyu8Dw/8z8HY8gMxgIRHh5L7rbPva35Xnn7pNAe4J9QjEI9OddyJdEz0N1dzDe/w4z+ED/iA+CqCdmxaekCnickzx87axTHSQqTb5TF6N4dHanFgsPY6hxnFDsTHfCE2UJzAaWl2AzIVqhI/ugnMU/XYa4',
        /*
         *  Instead of use the whole x509cert you can use a fingerprint in order to
         *  validate a SAMLResponse, but we don't recommend to use that
         *  method on production since is exploitable by a collision attack.
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
         *   or add for example the -sha256 , -sha384 or -sha512 parameter)
         *
         *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
         *  let the toolkit know which algorithm was used. Possible values: sha1, sha256, sha384 or sha512
         *  'sha1' is the default value.
         *
         *  Notice that if you want to validate any SAML Message sent by the HTTP-Redirect binding, you
         *  will need to provide the whole x509cert.
         */
        // 'certFingerprint' => '',
        // 'certFingerprintAlgorithm' => 'sha1',

        /* In some scenarios the IdP uses different certificates for
         * signing/encryption, or is under key rollover phase and
         * more than one certificate is published on IdP metadata.
         * In order to handle that the toolkit offers that parameter.
         * (when used, 'x509cert' and 'certFingerprint' values are
         * ignored).
         */
        // 'x509certMulti' => array(
        //      'signing' => array(
        //          0 => '<cert1-string>',
        //      ),
        //      'encryption' => array(
        //          0 => '<cert2-string>',
        //      )
        // ),
    )


);


return $settings;
