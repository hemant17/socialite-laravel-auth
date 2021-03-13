  protected $listen = [
        Registered::class => [
           -----
        ],
        // mention in the socialite doc -
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\\Yahoo\\YahooExtendSocialite@handle',
            'SocialiteProviders\\GitHub\\GitHubExtendSocialite@handle',
            'SocialiteProviders\\Twitter\\TwitterExtendSocialite@handle',
             -- More depend on your Need -- 
        ],
    ];

