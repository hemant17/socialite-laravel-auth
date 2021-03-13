Route::get('auth/{social_type}', 'Auth\SocialAuthController@redirectToSocial');
Route::get('auth/{social_type}/callback', 'Auth\SocialAuthController@handleCallback');
