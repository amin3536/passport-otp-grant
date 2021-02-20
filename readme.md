# PassportOtpGrant

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

this package help you to implement otp grant (register - login with verify code ore two verification code ) via laravel-passport
## Installation

Via Composer

``` bash
$ composer require amin3536/passport-otp-grant
```

##  initial 
1-install and initial laravel passport in your project and create a password-client 

2- add below two rows to your user migration ( if you want use custom rows see customising section)
```php
    //...
    $table->string('phone_number')->unique();
    $table->integer('otp')->nullable();
    //...


```

add `` use HasOTP;``  in your model :
```php
<?php
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasOTP;
    //...
    }
```
## sample usage 
below sample and logic  is  about  login and register with ``otp``. (it's not about two verification )
```php
public function userLoginOrRegister(UserLoginRequest $request)
    {

        $user = $this->userModel->wherePhoneNumber($request['phone_number'])->first();
        if (!$user) {
            $user = $this->userModel->create(['phone_number' => $request['phone_number']]);
        }

        $user->otp = $code_verifier = rand(10000, 99999);
        //you cand send otp code via sms , email , any messanger , ..... 
        $user->save();
        this->sendOtpCodeToUser(user);


    }
        
        
        
```
now you can verify user with passport  like below 

```php
Request::create('/oauth/token',
            'POST',
            [
                'grant_type' => 'otp_grant',
                'client_id' => 'client_id',
                'client_secret' => client_secret',
                'phone_number' => 'phone_number',
                'otp' => 'otp',
                'scope' =>'',
            ]);
```


## customising

```php
<?php
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasOTP;
    
    $phoneNumberColumn='anything';
    $OTPColumn='my_otp';
    //otp expire time in minute 
    $OTPExpireTime=15;
    //...
    }
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```
## to do list 
- [ ] change phone_number to user 
- [ ] add test 
- [x] add CI 
    
## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/amin3536/passport-otp-grant.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/amin3536/passport-otp-grant.svg?style=flat-square
[ico-travis]: https://travis-ci.com/amin3536/passport-otp-grant.svg?branch=main
[ico-styleci]: https://github.styleci.io/repos/339999725/shield

[link-packagist]: https://packagist.org/packages/amin3536/passport-otp-grant
[link-downloads]: https://packagist.org/packages/amin3536/passport-otp-grant
[link-travis]: https://travis-ci.com/github/amin3536/passport-otp-grant
[link-styleci]: https://github.styleci.io/repos/339999725
[link-author]: https://github.com/amin3536
[link-contributors]: ../../contributors
