<?php

use App\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', 'HomeController@frontPage');

Auth::routes([
    'register' => false, // Registration Routes
    'reset' => true, // Password Reset Routes
    'verify' => false, // Email Verification Routes
]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('notificacoes', 'HomeController@notifications')->name('notificacoes');
});

Route::prefix('usuario')->name('user.')->middleware('auth')->group(function () {
    Route::prefix('senha')->name('password.')->group(function () {
        Route::get('', 'UserController@editPassword')->name('edit');
        Route::put('', 'UserController@updatePassword')->name('update');
    });
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('logs', [
        'middleware' => 'role:admin',
        'uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index'
    ])->name('logs');

    Route::prefix('usuario')->name('user.')->group(function () {
        Route::get('', 'Admin\UserController@index')->name('index');
        Route::get('novo', 'Admin\UserController@create')->name('new');
        Route::post('', 'Admin\UserController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('editar', 'Admin\UserController@edit')->name('edit');
            Route::put('', 'Admin\UserController@update')->name('update');
            Route::delete('', 'Admin\UserController@destroy')->name('destroy');

            Route::prefix('senha')->name('password.')->group(function () {
                Route::get('', 'Admin\UserController@editPassword')->name('edit');
                Route::put('', 'Admin\UserController@updatePassword')->name('update');
            });
        });
    });

    Route::prefix('configuracao')->name('config.')->group(function () {
        Route::prefix('curso')->name('course.')->group(function () {
            Route::get('', 'Admin\GeneralConfigurationController@index')->name('index');
            Route::get('novo', 'Admin\GeneralConfigurationController@create')->name('new');
            Route::post('', 'Admin\GeneralConfigurationController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Admin\GeneralConfigurationController@edit')->name('edit');
                Route::put('', 'Admin\GeneralConfigurationController@update')->name('update');
                Route::delete('', 'Admin\GeneralConfigurationController@destroy')->name('destroy');
            });
        });

        Route::prefix('parametros')->name('parameters.')->group(function () {
            Route::get('', 'Admin\SystemConfigurationController@index')->name('index');
            Route::get('novo', 'Admin\SystemConfigurationController@create')->name('new');
            Route::post('', 'Admin\SystemConfigurationController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Admin\SystemConfigurationController@edit')->name('edit');
                Route::put('', 'Admin\SystemConfigurationController@update')->name('update');
                Route::delete('', 'Admin\SystemConfigurationController@destroy')->name('destroy');
            });
        });

        Route::prefix('backup')->name('backup.')->group(function () {
            Route::get('', 'Admin\BackupController@index')->name('index');
            Route::get('download', 'Admin\BackupController@backup')->name('download');
            Route::post('restaurar', 'Admin\BackupController@restore')->name('restore');
            Route::post('salvarConfig', 'Admin\BackupController@storeConfig')->name('storeConfig');
        });
    });

    Route::prefix('coordenador')->name('coordinator.')->group(function () {
        Route::get('', 'Admin\CoordinatorController@index')->name('index');
        Route::get('novo', 'Admin\CoordinatorController@create')->name('new');
        Route::post('', 'Admin\CoordinatorController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('editar', 'Admin\CoordinatorController@edit')->name('edit');
            Route::put('', 'Admin\CoordinatorController@update')->name('update');
            Route::delete('', 'Admin\CoordinatorController@destroy')->name('destroy');
        });
    });

    Route::prefix('curso')->name('course.')->group(function () {
        Route::get('', 'Admin\CourseController@index')->name('index');
        Route::get('novo', 'Admin\CourseController@create')->name('new');
        Route::post('', 'Admin\CourseController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Admin\CourseController@show')->name('show');
            Route::get('editar', 'Admin\CourseController@edit')->name('edit');
            Route::put('', 'Admin\CourseController@update')->name('update');
            Route::delete('', 'Admin\CourseController@destroy')->name('destroy');

            Route::get('coordenador', 'Admin\CoordinatorController@indexByCourse')->name('coordinator');

            Route::prefix('configuracao')->name('config.')->group(function () {
                Route::get('', 'Admin\CourseConfigurationController@index')->name('index');
                Route::get('novo', 'Admin\CourseConfigurationController@create')->name('new');
                Route::post('', 'Admin\CourseConfigurationController@store')->name('store');

                Route::prefix('{id_config}')->where(['id_config' => '[0-9]+'])->group(function () {
                    Route::get('editar', 'Admin\CourseConfigurationController@edit')->name('edit');
                    Route::put('', 'Admin\CourseConfigurationController@update')->name('update');
                    Route::delete('', 'Admin\CourseConfigurationController@destroy')->name('destroy');
                });
            });
        });
    });

    Route::prefix('mensagem')->name('message.')->group(function () {
        Route::get('', 'Admin\MessageController@index')->name('index');
        Route::post('enviar', 'Admin\MessageController@sendEmail')->name('enviar');
    });

    Route::prefix('colacao')->name('graduation.')->group(function () {
        Route::get('', 'Admin\GraduationController@index')->name('index');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::put('graduar', 'Admin\GraduationController@graduate')->name('graduate');
        });
    });
});

Route::prefix('coordenador')->name('coordinator.')->middleware('auth')->group(function () {
    Route::prefix('empresa')->name('company.')->group(function () {
        Route::get('', 'Coordinator\CompanyController@index')->name('index');
        Route::get('novo', 'Coordinator\CompanyController@create')->name('new');
        Route::post('', 'Coordinator\CompanyController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Coordinator\CompanyController@show')->name('show');
            Route::get('editar', 'Coordinator\CompanyController@edit')->name('edit');
            Route::put('', 'Coordinator\CompanyController@update')->name('update');
            Route::delete('', 'Coordinator\CompanyController@destroy')->name('destroy');

            Route::get('supervisor', 'Coordinator\SupervisorController@indexByCompany')->name('supervisor');
            Route::get('convenio', 'Coordinator\AgreementController@indexByCompany')->name('agreement');

            Route::get('pdf', 'Coordinator\CompanyController@pdf')->name('pdf');
        });

        Route::prefix('setor')->name('sector.')->group(function () {
            Route::get('', 'Coordinator\SectorController@index')->name('index');
            Route::get('novo', 'Coordinator\SectorController@create')->name('new');
            Route::post('', 'Coordinator\SectorController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Coordinator\SectorController@edit')->name('edit');
                Route::put('', 'Coordinator\SectorController@update')->name('update');
                Route::delete('', 'Coordinator\SectorController@destroy')->name('destroy');
            });
        });

        Route::prefix('convenio')->name('agreement.')->group(function () {
            Route::get('', 'Coordinator\AgreementController@index')->name('index');
            Route::get('novo', 'Coordinator\AgreementController@create')->name('new');
            Route::post('', 'Coordinator\AgreementController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Coordinator\AgreementController@edit')->name('edit');
                Route::put('', 'Coordinator\AgreementController@update')->name('update');
                Route::delete('', 'Coordinator\AgreementController@destroy')->name('destroy');
                Route::put('cancelar', 'Coordinator\AgreementController@cancel')->name('cancel');
                Route::put('reativar', 'Coordinator\AgreementController@reactivate')->name('reactivate');
            });
        });

        Route::prefix('supervisor')->name('supervisor.')->group(function () {
            Route::get('', 'Coordinator\SupervisorController@index')->name('index');
            Route::get('novo', 'Coordinator\SupervisorController@create')->name('new');
            Route::post('', 'Coordinator\SupervisorController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Coordinator\SupervisorController@edit')->name('edit');
                Route::put('', 'Coordinator\SupervisorController@update')->name('update');
                Route::delete('', 'Coordinator\SupervisorController@destroy')->name('destroy');
            });
        });
    });

    Route::prefix('estagio')->name('internship.')->group(function () {
        Route::get('', 'Coordinator\InternshipController@index')->name('index');
        Route::get('novo', 'Coordinator\InternshipController@create')->name('new');
        Route::post('', 'Coordinator\InternshipController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Coordinator\InternshipController@show')->name('show');
            Route::get('editar', 'Coordinator\InternshipController@edit')->name('edit');
            Route::put('', 'Coordinator\InternshipController@update')->name('update');
            Route::delete('', 'Coordinator\InternshipController@destroy')->name('destroy');
            Route::put('cancelar', 'Coordinator\InternshipController@cancel')->name('cancel');
            Route::put('reativar', 'Coordinator\InternshipController@reactivate')->name('reactivate');

            Route::get('aditivo', 'Coordinator\AmendmentController@indexByInternship')->name('amendment');
        });

        Route::prefix('aditivo')->name('amendment.')->group(function () {
            Route::get('', 'Coordinator\AmendmentController@index')->name('index');
            Route::get('novo', 'Coordinator\AmendmentController@create')->name('new');
            Route::post('', 'Coordinator\AmendmentController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Coordinator\AmendmentController@edit')->name('edit');
                Route::put('', 'Coordinator\AmendmentController@update')->name('update');
                Route::delete('', 'Coordinator\AmendmentController@destroy')->name('destroy');
            });
        });
    });

    Route::prefix('trabalho')->name('job.')->group(function () {
        Route::get('', 'Coordinator\JobController@index')->name('index');
        Route::get('novo', 'Coordinator\JobController@create')->name('new');
        Route::post('', 'Coordinator\JobController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Coordinator\JobController@show')->name('show');
            Route::get('pdf', 'Coordinator\JobController@pdf')->name('pdf');
            Route::get('editar', 'Coordinator\JobController@edit')->name('edit');
            Route::put('', 'Coordinator\JobController@update')->name('update');
            Route::delete('', 'Coordinator\JobController@destroy')->name('destroy');
            Route::put('cancelar', 'Coordinator\JobController@cancel')->name('cancel');
            Route::put('reativar', 'Coordinator\JobController@reactivate')->name('reactivate');
        });

        Route::prefix('empresa')->name('company.')->group(function () {
            Route::get('', 'Coordinator\JobCompanyController@index')->name('index');
            Route::get('novo', 'Coordinator\JobCompanyController@create')->name('new');
            Route::post('', 'Coordinator\JobCompanyController@store')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('', 'Coordinator\JobCompanyController@show')->name('show');
                Route::get('editar', 'Coordinator\JobCompanyController@edit')->name('edit');
                Route::put('', 'Coordinator\JobCompanyController@update')->name('update');
                Route::delete('', 'Coordinator\JobCompanyController@destroy')->name('destroy');
            });
        });
    });

    Route::prefix('relatorio')->name('report.')->group(function () {
        Route::get('', 'Coordinator\ReportController@index')->name('index');

        Route::prefix('bimestral')->name('bimonthly.')->group(function () {
            Route::get('novo', 'Coordinator\ReportController@createBimonthly')->name('new');
            Route::post('', 'Coordinator\ReportController@storeBimonthly')->name('store');
            Route::post('pdf', 'Coordinator\ReportController@pdfBimonthly')->name('pdf');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Coordinator\ReportController@editBimonthly')->name('edit');
                Route::put('', 'Coordinator\ReportController@updateBimonthly')->name('update');
                Route::delete('', 'Coordinator\ReportController@destroyBimonthly')->name('destroy');
            });
        });

        Route::prefix('final')->name('final.')->group(function () {
            Route::get('novo', 'Coordinator\ReportController@createFinal')->name('new');
            Route::post('', 'Coordinator\ReportController@storeFinal')->name('store');

            Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
                Route::get('editar', 'Coordinator\ReportController@editFinal')->name('edit');
                Route::put('', 'Coordinator\ReportController@updateFinal')->name('update');
                Route::delete('', 'Coordinator\ReportController@destroyFinal')->name('destroy');
                Route::get('pdf', 'Coordinator\ReportController@pdfFinal')->name('pdf');
                Route::get('pdf2', 'Coordinator\ReportController@pdf2Final')->name('pdf2');
            });
        });
    });

    Route::prefix('mensagem')->name('message.')->group(function () {
        Route::get('', 'Coordinator\MessageController@index')->name('index');
        Route::post('enviar', 'Coordinator\MessageController@sendEmail')->name('enviar');
    });

    Route::prefix('aluno')->name('student.')->group(function () {
        Route::get('', 'Coordinator\StudentController@index')->name('index');
        Route::get('pdf', 'Coordinator\StudentController@pdf')->name('pdf');
        Route::post('gerarPDF', 'Coordinator\StudentController@makePDF')->name('makePDF');

        Route::prefix('{ra}')->where(['ra' => '[0-9]+'])->group(function () {
            Route::get('', 'Coordinator\StudentController@show')->name('show');
        });
    });

    Route::prefix('proposta')->name('proposal.')->group(function () {
        Route::get('', 'Coordinator\ProposalController@index')->name('index');
        Route::get('novo', 'Coordinator\ProposalController@create')->name('new');
        Route::post('', 'Coordinator\ProposalController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Coordinator\ProposalController@show')->name('show');
            Route::get('editar', 'Coordinator\ProposalController@edit')->name('edit');
            Route::put('', 'Coordinator\ProposalController@update')->name('update');
            Route::delete('', 'Coordinator\ProposalController@destroy')->name('destroy');
            Route::put('aprovar', 'Coordinator\ProposalController@approve')->name('approve');
            Route::put('rejeitar', 'Coordinator\ProposalController@reject')->name('reject');
        });
    });
});

Route::prefix('aluno')->name('student.')->middleware('auth')->group(function () {
    Route::prefix('proposta')->name('proposal.')->group(function () {
        Route::get('', 'Student\ProposalController@index')->name('index');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Student\ProposalController@show')->name('show');
        });
    });

    Route::prefix('documento')->name('document.')->group(function () {
        Route::get('', 'Student\DocumentController@index')->name('index');
        Route::get('manual', 'Student\DocumentController@getManual')->name('manual');
        Route::get('protocolo', 'Student\DocumentController@generateProtocol')->name('protocol');

        Route::get('plano', 'Student\DocumentController@generatePlan')->name('plano');
        Route::get('termo', 'Student\DocumentController@generateTerm')->name('term');
        Route::get('convenio', 'Student\DocumentController@generateAgreement')->name('agreement');

        Route::get('certificado', 'Student\DocumentController@generateCertificate')->name('certificate');
        Route::get('avaliacao', 'Student\DocumentController@generateEvaluation')->name('evaluation');
        Route::get('apresentacao', 'Student\DocumentController@generatePresentation')->name('presentation');
        Route::get('conteudo', 'Student\DocumentController@generateContent')->name('content');
        Route::get('questionario', 'Student\DocumentController@generateQuestionnaire')->name('questionnaire');

        Route::get('relatorio', 'Student\DocumentController@generateReport')->name('report');
        Route::get('aditivo', 'Student\DocumentController@generateAditive')->name('amendment');
        Route::get('situacao', 'Student\DocumentController@generateSituation')->name('situation');

        Route::prefix('ajuda')->name('help.')->group(function () {
            Route::get('plano', 'Student\DocumentHelpController@getPlan')->name('plano');
            Route::get('termo', 'Student\DocumentHelpController@getTerm')->name('term');
            Route::get('convenio', 'Student\DocumentHelpController@getAgreement')->name('agreement');

            Route::get('certificado', 'Student\DocumentHelpController@getCertificate')->name('certificate');
            Route::get('avaliacao', 'Student\DocumentHelpController@getEvaluation')->name('evaluation');
            Route::get('apresentacao', 'Student\DocumentHelpController@getPresentation')->name('presentation');
            Route::get('conteudo', 'Student\DocumentHelpController@getContent')->name('content');
            Route::get('questionario', 'Student\DocumentHelpController@getQuestionnaire')->name('questionnaire');
        });
    });
});

Route::prefix('empresa')->name('company.')->middleware('auth')->group(function () {
    Route::prefix('proposta')->name('proposal.')->group(function () {
        Route::get('', 'Company\ProposalController@index')->name('index');
        Route::get('novo', 'Company\ProposalController@create')->name('new');
        Route::post('', 'Company\ProposalController@store')->name('store');

        Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('', 'Company\ProposalController@show')->name('show');
            Route::get('editar', 'Company\ProposalController@edit')->name('edit');
            Route::put('', 'Company\ProposalController@update')->name('update');
            Route::delete('', 'Company\ProposalController@destroy')->name('destroy');
        });
    });
});

Route::prefix('ajuda')->name('help.')->middleware('auth')->group(function () {
    Route::get('', 'HelpController@index')->name('index');
});

Route::prefix('sobre')->name('about.')->middleware('auth')->group(function () {
    Route::get('', 'AboutController@index')->name('index');
});
