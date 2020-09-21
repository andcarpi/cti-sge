<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'API\UserController@get');

Route::prefix('')->name('api.')->group(function () {
    Route::prefix('external')->name('external.')->group(function () {
        Route::get('ufs', 'API\ExternalAPISController@getUFS')->name('ufs');
        Route::get('cities/{uf}', 'API\ExternalAPISController@getCities')->name('cities');
        Route::get('cep/{cep}', 'API\ExternalAPISController@getAddress')->name('cep');
        Route::get('cnpj/{cnpj}', 'API\ExternalAPISController@getCompanyInfo')->name('cnpj');
    });

    Route::group(['middleware' => ['apiSession']], function () {
        Route::prefix('usuario')->name('user.')->middleware('auth')->group(function () {
            Route::get('', 'API\UserController@get')->name('get');
            Route::get('apiToken', 'API\UserController@generateAPIToken')->name('apiToken');

            Route::prefix('notificacao')->name('notification.')->group(function () {
                Route::get('', 'API\NotificationController@get')->name('get');

                Route::prefix('{id}')->group(function () {
                    Route::put('lida', 'API\NotificationController@markAsSeen')->name('lida');
                });
            });
        });

        Route::prefix('alunos')->name('students.')->middleware('auth')->group(function () {
            Route::get('', 'API\NSac\StudentController@get')->name('get');
            Route::get('curso/{course}', 'API\NSac\StudentController@getByCourse')->name('getByCourse');
            Route::get('ano/{year}', 'API\NSac\StudentController@getByYear')->name('getByYear');
            Route::get('turma/{class}', 'API\NSac\StudentController@getByClass')->name('getByClass');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('', 'API\NSac\StudentController@getByRA')->name('getByRA');
                Route::get('foto', 'API\NSac\StudentController@getPhoto')->name('photo');
            });
        });

        Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
            Route::get('sysUsage', 'API\Admin\SystemUsage@index')->name('sysUsage');
            Route::post('down', 'API\Admin\SystemUsage@down')->name('down');
            Route::post('up', 'API\Admin\SystemUsage@up')->name('up');

            Route::prefix('coordenador')->name('coordinator.')->group(function () {
                Route::get('', 'API\Admin\CoordinatorController@get')->name('get');
                Route::get('curso/{id}', 'API\Admin\CoordinatorController@getByCourse')->name('getByCourse');

                Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                    Route::get('', 'API\Admin\CoordinatorController@getById')->name('getById');
                });
            });
        });

        Route::prefix('coordenador')->name('coordinator.')->middleware('auth')->group(function () {
            Route::prefix('empresa')->name('company.')->group(function () {
                Route::get('', 'API\Coordinator\CompanyController@get')->name('get');

                Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                    Route::get('', 'API\Coordinator\CompanyController@getById')->name('getById');

                    Route::prefix('setor')->name('sector.')->group(function () {
                        Route::get('', 'API\Coordinator\SectorController@getFromCompany')->name('getFromCompany');
                    });

                    Route::prefix('supervisor')->name('supervisor.')->group(function () {
                        Route::get('', 'API\Coordinator\SupervisorController@getFromCompany')->name('getFromCompany');
                    });
                });

                Route::prefix('setor')->name('sector.')->group(function () {
                    Route::get('', 'API\Coordinator\SectorController@get')->name('get');
                    Route::post('', 'API\Coordinator\SectorController@store')->name('store');

                    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                        Route::get('', 'API\Coordinator\SectorController@getById')->name('getById');
                        Route::put('', 'API\Coordinator\SectorController@update')->name('update');
                    });
                });

                Route::prefix('supervisor')->name('supervisor.')->group(function () {
                    Route::get('', 'API\Coordinator\SupervisorController@get')->name('get');
                    Route::post('', 'API\Coordinator\SupervisorController@store')->name('store');

                    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                        Route::get('', 'API\Coordinator\SupervisorController@getById')->name('getById');
                        Route::put('', 'API\Coordinator\SupervisorController@update')->name('update');
                    });
                });
            });

            Route::prefix('estagio')->name('internship.')->group(function () {
                Route::get('', 'API\Coordinator\InternshipController@get')->name('get');

                Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                    Route::get('', 'API\Coordinator\InternshipController@getById')->name('getById');
                });

                Route::prefix('ra')->group(function () {
                    Route::get('{ra}', 'API\Coordinator\InternshipController@getByRA')->name('getByRA');
                });
            });

            Route::prefix('trabalho')->name('job.')->group(function () {
                Route::get('', 'API\Coordinator\JobController@get')->name('get');

                Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                    Route::get('', 'API\Coordinator\JobController@getById')->name('getById');
                });

                Route::prefix('ra')->group(function () {
                    Route::get('{ra}', 'API\Coordinator\JobController@getByRA')->name('getByRA');
                });

                Route::prefix('empresa')->name('company.')->group(function () {
                    Route::get('', 'API\Coordinator\JobCompanyController@get')->name('get');
                    Route::post('', 'API\Coordinator\JobCompanyController@store')->name('store');

                    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                        Route::get('', 'API\Coordinator\JobCompanyController@getById')->name('getById');
                        Route::put('', 'API\Coordinator\JobCompanyController@update')->name('update');
                    });
                });
            });
        });

        Route::prefix('aluno')->name('student.')->middleware('auth')->group(function () {
            Route::prefix('documento')->name('document.')->group(function () {
                Route::get('formato', 'API\Student\DocumentController@getFormat')->name('format');
            });
        });
    });
});
